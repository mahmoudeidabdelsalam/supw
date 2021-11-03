jQuery(document).ready( function($){
  $('#apf-product-form').on('submit', function (e) {
    e.preventDefault();
    $('.apf-modal').show();
    $('.apf-modal .apf-loading-section').show();

    var form_data = $(this).serializeArray();

    form_data = form_data.filter(x => x.name !== 'upsell_ids');
    form_data = form_data.filter(x => x.name !== 'crossell_ids');
    form_data.push({ name: 'upsell_ids',value: $('#apf-upsell').val()});
    form_data.push({ name: 'crossell_ids',value: $('#apf-crossell').val()});

    var categories_formatted = $('input[name="supw_product_cat[]"]:checked').map(function(){ 
        return this.value; 
    }).get();
    
    var attr_values = $('select.attribute_value').serializeArray();
    
    // Attribute
    var attr_name = $('input:text.attr_name').serializeArray();

    // attr_values.serializeArray();

    var attr_is_visible = $('.attribute_visibility').serializeArray();



    
    var attr_formatted = {};
    for (let index = 0; index < attr_name.length; index++) {
      attr_formatted[attr_name[index].value] = {val: attr_values[index].value, visibility:  attr_is_visible[index] ? attr_is_visible[index].value : 'off'};
    }


    var attributes = $('.attributes-variable');

    var attribute_slug_one = $('input[name="attribute_slug_one"]').serializeArray();
    var attribute_slug_two = $('input[name="attribute_slug_two"]').serializeArray();

    var attribute_one = $('input[name="attribute_one"]').serializeArray();
    var attribute_two = $('input[name="attribute_two"]').serializeArray();


    var variable_regular_price = $('input[name="variable_regular_price"]').serializeArray();
    var variable_sku = $('input[name="variable_sku"]').serializeArray();


    var variables = {};
    for (let index = 0; index < attributes.length; index++) {
      variables[index] = {slug_one:attribute_slug_one[index].value, slug_two:attribute_slug_two[index].value, attr_one:attribute_one[index].value, attr_two:attribute_two[index].value, variable_price:variable_regular_price[index].value, number_sku:variable_sku[index].value};
    }



    // Custom attribute
    var cus_attr_names = $('input:text.cus_attribute_name').serializeArray();
    var cus_attr_values = $('textarea.cus_attribute_value').serializeArray();
    var cus_attr_is_visible = $('.cus_attribute_visibility').serializeArray();
    var cus_attr_formatted = {};
    for (let index = 0; index < cus_attr_names.length; index++) {
      cus_attr_formatted[cus_attr_names[index].value] = {val: cus_attr_values[index].value, visibility:  cus_attr_is_visible[index] ? cus_attr_is_visible[index].value : 'off'};
    }



    var data
     = {
      action: 'save_simple_product',
      security : savesimpleproduct.security,
      formdata: form_data,
      attributes: attr_formatted,
      custom_attributes: cus_attr_formatted,
      categories: categories_formatted,
      variables: variables,
    };

    $.post(savesimpleproduct.ajax_url, data, function(response) {
      if(response.success) {
        $('.apf-modal .apf-loading-section').hide();
        $('.apf-p-name span').append('<b><a href="'+response.data.permalink+'">'+response.data.prod_name+'</a></b>');
        $('.apf-modal .apf-modal-content .apf-modal-result').show();
      }
      else {
        $('.apf-modal .apf-loading-section').hide();
        $('.apf-modal .apf-modal-content .apf-modal-result-error .apf-modal-main-section p').html(response.data.description);
        $('.apf-modal .apf-modal-content .apf-modal-result-error').show();
      }

    });



  });

  $('.apf-modal .apf-modal-result button').on('click', function(e) {
    location.reload();
  });

  $('.apf-modal .apf-modal-result-error button').on('click', function(e) {
    $('.apf-modal .apf-modal-content .apf-modal-result-error').hide();
    $('.apf-modal').hide();
  });







  $('.attribute_do_add').on('click', function (e) {
    e.preventDefault();


    var value_one     = $('[name="attribute_value_one"]').find(":selected").text();
    var value_two     = $('[name="attribute_value_two"]').find(":selected").text();
    var slug_attr_one = $('[name="slug_attr_one"]').val();
    var slug_attr_two = $('[name="slug_attr_tow"]').val();
    var counter       = parseInt($('[name="counter"]').val()) + parseInt(1);

    var data
    = {
     action: 'save_attr_product',
     security : savesimpleproduct.security,
     value_one: value_one,
     value_two: value_two,
     slug_attr_one: slug_attr_one,
     slug_attr_two: slug_attr_two,
     counter:   counter
   };

   $.post(savesimpleproduct.ajax_url, data, function(response) {
     if(response.success) {
       $('.box-container-attribute').append(response.data);
     }
     else {
       $('.box-container-attribute').append('<b>error</b>');
     }

   });


  });

});