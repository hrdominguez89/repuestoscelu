"use strict";

let selectCategory;
let productId;
let categoryId;
let subcategoryId;

let subcategories;

let imagenId;
let slider;
let totalImages;


$(document).ready(() => {
  initSelect2();
  initInputs();
  listenSelectCategories();
  loadGalery();
});


const initSelect2 = () => {
  $('select').select2({
    theme: 'bootstrap4',
  });
}

const loadGalery = () => {
  if ($('.pgwSlideshow').length > 0) {
    slider = $('.pgwSlideshow').pgwSlideshow();
    totalImages = slider.getSlideCount();
    listenButtonDelete();
    listenButtonPrincipal();
  }
}

const listenButtonDelete = () => {
  $('#deleteImage').click(function () {
    let img_slide = slider.getCurrentSlide();
    let imageId = $(`.elt_${img_slide}`)[1].dataset.id;
    let principalImage = $(`.elt_${img_slide}`)[1].dataset.principalImage;
    //elimino imagen by ajax.
    $.ajax({
      url: `/secure/product/deleteImageProduct`,
      method: "POST",
      data: { image_id: imageId, principal_image: principalImage },
      success: async (res) => {
        if (res.status) {

          if (res.new_principal_image_id) {
            //obtengo la imagen
            $(`#imagen-${res.new_principal_image_id}`).after(`<p id="p-portada" data-image-id="${res.new_principal_image_id}" class="text-center text-black bg-white m-0 p-0 b-0">Portada</p>`);
            $(`#img-${res.new_principal_image_id}`).attr('data-principal-image', '1');
          }

          $(`.elt_${img_slide}`).remove();

          $('#modalDelete').modal('hide');
          if (totalImages - 1 == 0) {//si es la ultima imagen que elimino, remuevo el div entero de imagenes cargadas
            slider.destroy();
            $('#images_galery_div').remove();
          } else {
            slider.reload();
            totalImages = slider.getSlideCount();
          }
        } else {
          console.log('no fue posible borrar la imagen');
        }
      },
    });
    //fin.
  });
}

const listenButtonPrincipal = () => {
  $('#principalImageButton').click(function () {
    let img_slide = slider.getCurrentSlide();
    let NuevoPortadaimageId = $(`.elt_${img_slide}`)[1].dataset.id;
    let principalImage = $(`.elt_${img_slide}`)[1].dataset.principalImage;
    if (!principalImage) {
      let textoPortadaViejo = $('#p-portada');
      let idImagenPortadaViejo = textoPortadaViejo.data('image-id');
      $.ajax({
        url: `/secure/product/newPrincipalImage`,
        method: "POST",
        data: { old_principal_image_id: idImagenPortadaViejo, new_principal_image_id: NuevoPortadaimageId },
        success: async (res) => {
          if (res.status) {
            //si todo oke remuevo parrafo
            textoPortadaViejo.remove();
            //y agrego parrafo y datos extra para que todo funcione para la proxima llamada
            $(`#imagen-${NuevoPortadaimageId}`).after(`<p id="p-portada" data-image-id="${NuevoPortadaimageId}" class="text-center text-black bg-white m-0 p-0 b-0">Portada</p>`);
            $(`#img-${NuevoPortadaimageId}`).attr('data-principal-image', '1');

          } else {
            console.log('no fue posible establecer la imagen como portada');
          }
        },
      });
    }
  });
}


const initInputs = () => {
  subcategoryId = $('#label-subcategory').data('subcategory-id')
  categoryId = $('#label-category').data('category-id')
  getSubcategories();
}

const listenSelectCategories = () => {
  selectCategory = $('#product_category');
    selectCategory.on("change", async () => {
      categoryId = parseInt(selectCategory.val());
      if(categoryId){
        await getSubcategories();
        $("#product_subcategory").trigger("chosen:updated");
      }else{
        cleanSelects();
      }
    });
};

function addZeros(text, zerosQuantity) {
  while (text.length < zerosQuantity) {
    text = "0" + text;
  }
  return text;
}

const getSubcategories = () => {
  $.ajax({
    url: `/secure/subcategory/getSubcategories/${categoryId}`,
    method: "GET",
    success: async (res) => {
      if (res.status) {
        subcategories = await res.data;
        cleanSelects();
        $("#product_subcategory").prop("disabled", false);
        $("#product_subcategory").prop("required", 'required');
        for (let i = 0; i < subcategories.length; i++) {
          const element = subcategories[i];
          const option = $("<option></option>").text(element.name);
          option.attr("value", element.id);
          if (subcategoryId && subcategoryId == element.id) {
            option.attr("selected", "selected");
          }
          $("#product_subcategory").append(option);
        }
      } else {
        cleanSelects(true);
      }
    },
  });
};

const cleanSelects = (disable = false) => {
  const defaultOptionSelect = $("<option value selected hidden disabled</option>").text(
    "Seleccione una subcategoría"
  );
  $("#product_subcategory").html(defaultOptionSelect);
  if (disable) {
    $("#product_subcategory").prop("disabled", true);
    $("#product_subcategory").prop("required", 'required');
  }
};