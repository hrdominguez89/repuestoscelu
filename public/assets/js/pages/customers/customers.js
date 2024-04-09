"use strict";

let selectState;
let stateId;
let cityId;

let cities;

$(document).ready(() => {
  initSelect2();
  initInputs();
  listenSelectStates();
});
const initSelect2 = () => {
  $('select').select2({
    theme: 'bootstrap4',
  });
}
const initInputs = () => {
  cityId = $('#label-city').data('city-id')
  stateId = $('#label-state').data('state-id')
  getCities();
}
const listenSelectStates = () => {
  selectState = $('#customer_state');
  selectState.on("change", async () => {
    stateId = parseInt(selectState.val());
    await getCities();
    $("#customer_city").trigger("chosen:updated");
  });
};
const getCities = () => {
  $.ajax({
    url: `/secure/customer/getCities/${stateId}`,
    method: "GET",
    success: async (res) => {
      if (res.status) {
        cities = await res.data;
        cleanSelects();
        $("#customer_city").prop("disabled", false);
        for (let i = 0; i < cities.length; i++) {
          const element = cities[i];
          const option = $("<option></option>").text(element.name);
          option.attr("value", element.id);
          if (cityId && cityId == element.id) {
            option.attr("selected", "selected");
          }
          $("#customer_city").append(option);
        }
      } else {
        cleanSelects(true);
      }
    },
  });
};
const cleanSelects = (disable = false) => {
  const defaultOptionSelect = $("<option></option>").text(
    "Seleccione una localidad/ciudad"
  );
  $("#customer_city").html(defaultOptionSelect);
  if (disable) {
    $("#customer_city").prop("disabled", true);
  }
};