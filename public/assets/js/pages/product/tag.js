"use strict";

let tags;

$(document).ready(() => {
  let tagsSelected = $('#label-tag').data('tagsSelected')
  if(tagsSelected){
    tags = tagsSelected.toString().split('-');
    if (tags.length > 0) {
      tags.forEach(tag => {
        $(`#product_sale_point_tag_tag_${tag}`).attr('checked','checked');
      });
    }
  }
});

