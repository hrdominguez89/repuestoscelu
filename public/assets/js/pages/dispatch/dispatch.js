"use strict";

$(document).ready(() => {
    initSelect2();
});


const initSelect2 = () => {
    $('select').select2({
        theme: 'bootstrap4',
    });
}