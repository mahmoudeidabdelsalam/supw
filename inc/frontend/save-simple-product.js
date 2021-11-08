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
    


    var attributes_input = $('input[name="variables"]').serializeArray();


    // console.log(attributes_input);

    var variables = {};
    for (let index = 0; index < attributes_input.length; index++) {
      variables[index] = {attribute:attributes_input[index].value};
    }
    
    var attribute     = $('[name="attribute_value_one"]').find(":selected").val();
    var attribute_text     = $('[name="attribute_value_one"]').find(":selected").text();

    var data
     = {
      action: 'save_simple_product',
      security : savesimpleproduct.security,
      formdata: form_data,
      categories: categories_formatted,
      variables: variables,
      attribute: attribute,
      attribute_text: attribute_text,
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

    var attribute     = $('[name="attribute_value_one"]').find(":selected").val();

    var data
    = {
     action: 'save_attr_product',
     security : savesimpleproduct.security,
     attribute: attribute,
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