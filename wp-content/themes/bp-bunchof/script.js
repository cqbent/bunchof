/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery.validator.addMethod("zipcode", function(value, element) {
  return this.optional(element) || /^\d{5}(?:-\d{4})?$/.test(value);
}, "Please provide a valid zipcode.");


jQuery(document).ready(function($) {
    $('#closepanel').click(function() {
        $('#helpopen').fadeOut(300);
        $('#helpclosed').delay(400).fadeIn(300);
    });
    $('#helpclosed').click(function() {
        $('#helpclosed').fadeOut(300);
        $('#helpopen').delay(400).fadeIn(300);
    });
    jQuery('#create-group-form').validate({
        rules: {
            'group-name': "required",
            'group-desc': "required",
            'group-field-one': {
                required: true,
                zipcode: true,
            }
        }
    });
    // remove bookings dropdown items
    $('#em-bookings-table select[name=status] option:gt(3)').remove();
    /*
    $("#group-creation-create").on("click", function(event) {
        //Your validation code here
        if($("#group-field-one").val() == ""){
            alert("Please enter your zip code!");
            return false;}
        });
    });
    */
   // add "visibility"
   $('#wp-admin-bar-my-account-settings-profile a').text('Profile Visibility');
});
