var table = null;
var action = null;
var image = null;

// Prevent Dropzone from auto discovering this element:
Dropzone.options.myAwesomeDropzone = false;
// This is useful when you want to create the
// Dropzone programmatically later

// Disable auto discover for all elements:
Dropzone.autoDiscover = false;

$(document).ready(function () {
  //table
  table = $("#datatable-configurations").DataTable({
    columns: [
      {
        data: null,
        className: "text-center",
        width: "20px",
        searchable: false,
        orderable: false,
        bSortable: false,
      },
      {
        visible: false,
        data: "id",
      },
      {
        data: "image",
        className: "text-center",
        width: "30px",
        render: function (data, type, row, meta) {
          return `
            <img class="image-brands" src="${row.image}"
            alt="Header Avatar">
            `;
        },
      },
      {
        data: "titulo",
      },
      {
        data: "descripcion",
      },
      {
        data: "btn",
        className: "text-center",
        width: "50px",
        bSortable: false,
        render: function (data, type, row, meta) {
          var put = is_granted("ROLE_ADMIN")
            ? `<i class="icon-put far fa-edit text-warning waves-effect me-2" onclick="btnPut(this)"></i>`
            : "";

          var remove = is_granted("ROLE_ADMIN")
            ? `<i class="icon-remove far fa-trash-alt text-danger waves-effect" onclick="btnRemove(this,'${row.id}')"></i>`
            : "";

          return put + remove;
        },
      },
    ],
  });

  //index column table
  table
    .on("order.dt search.dt", function () {
      table
        .column(0, { search: "applied", order: "applied" })
        .nodes()
        .each(function (cell, i) {
          cell.innerHTML = i + 1;
        });
    })
    .draw();

  //init load
  getAll();

  //dropzone upload image
  $("#dZUpload").dropzone({
    url: "hn_SimpeFileUploader.ashx",
    maxFiles: 1,
    uploadMultiple: false,
    autoProcessQueue: false,
    acceptedFiles: "image/*",
    previewTemplate: `
        <div class="dz-preview dz-image-preview">
            <div class="dz-image">
                <img id="image" height="120px" data-dz-thumbnail="" alt="Logo" src=""/>                                      
            </div>              
            <a class="dz-remove" href="javascript:undefined;" data-dz-remove="">
                <i class="mt-2 far fa-trash-alt text-danger waves-effect" onclick="uploadImageShowHide()"></i>
            </a>
        </div>
        `,
    init: function () {
      this.on("addedfile", function (file) {
        toBase64(file);
      });
    },
    maxfilesexceeded: function (files) {
      this.removeAllFiles();
      this.addFile(files);
    },
  });
});

//convert to base64
function toBase64(file) {
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = function (event) {
    image = event.target.result; //- is dataURL data
    $("#dZUpload").parent().removeClass("has-danger");
    $("#dZUploadError").addClass("d-none");
  };
}

//upload Image Show
function uploadImageShowHide(hide = "dZUploadView", show = "dZUpload") {
  if (show == "dZUpload") image = null;
  $("#" + hide).addClass("d-none");
  $("#" + show).removeClass("d-none");
}

//toggle form
function toggleForm() {
  $(".form-configuration,.table-configurations").toggle("slide");
}

//end toogle form

//action btn
function btnPost() {
  //set action
  action = post;

  //set titles
  toogleLabelPost();

  //show upload image
  uploadImageShowHide();

  //show form
  toggleForm();

  //clear form
  clearForm($(".form-configuration"));
  pristine.reset();
  $("#dZUploadError").addClass("d-none");

  //set input x focus
  $("#name").focus();
}

function btnPut(btn) {
  //set action
  action = put;

  //set titles
  toogleLabelPut();

  //clear form
  clearForm($(".form-configuration"));
  pristine.reset();
  $("#dZUploadError").addClass("d-none");

  //load form data
  var obj = table.row($(btn).closest("tr")).data();
  $.each(obj, function (key, value) {
    if (key == "image") {
      $("#image").attr("src", value);
    } else $(`[name='${key}']`).val(value);
  });

  //hide upload image
  uploadImageShowHide("dZUpload", "dZUploadView");

  //show form
  toggleForm();

  //set input x focus
  $("#name").focus();
}

function btnRemove(btn, id) {
  //confirmation dialog
  messageConfirmation(undefined, undefined, function () {
    remove($(btn).closest("tr"), id);
  });
}

//end action btn

//api functions
function getAll() {
  //show table loader
  toogleTableLoader("table-configurations-loader");

  // clear table
  table.rows().remove().draw(false);

  //send request
  send(
    Method.GET,
    "/public/configurations",
    null,
    function (response) {
      //transform response data
      data = [];

      var data = response.data.map((obj) => {
        return obj;
      });

      if (data.length == 4 || data.length > 3) $("#btn-post").hide();
      else $("#btn-post").show();

      // clear table
      table.rows().remove().draw(false);

      // load data
      table.rows.add(data).draw(false);
    },
    function () {
      //hide table loader
      toogleTableLoader("table-configurations-loader");
    }
  );
}

function post() {
  //get form data
  var formData = $(".form-configuration")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});

  formData.base64Image = image;

  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/configurations",
    formData,
    function (response) {
      //reload grid
      getAll();

      // message ok
      message();

      //hide form
      toggleForm();
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

function put() {
  //get form data
  var formData = $(".form-configuration")
    .serializeArray()
    .reduce(function (obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});

  formData.base64Image = image;

  //show loader
  toggleLoader();

  //send request
  send(
    Method.POST,
    "/configurations/" + formData.id,
    formData,
    function (response) {
      //reload grid
      getAll();

      // message ok
      message();

      //hide form
      toggleForm();
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

function remove(tr, id) {
  //show loader
  toggleLoader();

  //send request
  send(
    Method.DELETE,
    "/configurations/" + id,
    null,
    function (response) {
      getAll();
      $("#btn-post").show();
      // mensaje ok
      message("Eliminado!", "Your file has been deleted.!");
    },
    function () {
      //hide loader
      toggleLoader();
    }
  );
}

//end api functions

//prevent form submit
window.onload = function () {
  var form = document.getElementById("form-configuration");

  pristine = new Pristine(form);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var valid = pristine.validate();
    if ($(".modal-title").text() == "Adicionar") {
      if (image == null) {
        $("#dZUpload").parent().addClass("has-danger");
        $("#dZUploadError").removeClass("d-none");
      } else {
        $("#dZUpload").parent().removeClass("has-danger");
        $("#dZUploadError").addClass("d-none");
      }
      if (valid && image != null) action();
    } else {
      if (valid) action();
    }
  });
};
//end prevent submit
