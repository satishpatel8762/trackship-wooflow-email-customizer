/* email_snackbar jquery */
(function( $ ){
	$.fn.email_snackbar = function(msg) {
		if ( jQuery('.snackbar-logs').length === 0 ){
			$("body").append("<section class=snackbar-logs></section>");
		}
		var email_snackbar = $("<article></article>").addClass('snackbar-log snackbar-log-success snackbar-log-show').text( msg );
		$(".snackbar-logs").append(email_snackbar);
		setTimeout(function(){ email_snackbar.remove(); }, 3000);
		return this;
	}; 
})( jQuery );

/* email_snackbar_warning jquery */
(function( $ ){
	$.fn.email_snackbar_warning = function(msg) {
		if ( jQuery('.snackbar-logs').length === 0 ){
			$("body").append("<section class=snackbar-logs></section>");
		}
		var email_snackbar_warning = $("<article></article>").addClass( 'snackbar-log snackbar-log-error snackbar-log-show' ).html( msg );
		$(".snackbar-logs").append(email_snackbar_warning);
		setTimeout(function(){ email_snackbar_warning.remove(); }, 3000);
		return this;
	}; 
})( jQuery );
/*header script end*/ 

/*
on change alert box open
*/
jQuery(document).on("click", ".back_to_notice", function(){
	var r = confirm( 'The changes you made will be lost if you navigate away from this page.' );
	if (r === true ) {
	} else {	
		return false;
	}
});

function setting_change_trigger() {	
	jQuery('.woocommerce-save-button').removeAttr("disabled");
	jQuery('.wclp-save-content .wclp-btn').html('Save');
	jQuery('.zoremmail-back-wordpress-link').addClass('back_to_notice');
}


jQuery(document).on("click", ".woocommerce-save-button", function(){
	save_customizer_email_setting();
});


function save_customizer_email_setting(){
	
	jQuery('.zoremmail-layout-content-preview').addClass('customizer-unloading');
	// setting_change_trigger();

	// change_trigger();

	jQuery('.woocommerce-save-button');
	
	var form = jQuery('#woocommerce_email_options');
	jQuery.ajax({
		url: ajaxurl,//csv_workflow_update,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",		
		success: function(response) {
			if( response.success === "true" ){
				jQuery('iframe').attr('src', jQuery('iframe').attr('src'));
			} else {
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

jQuery(document).ready(function($){
	var mediaUploader;
	change_submenu_item();
	jQuery('.upload-button, #widget-image, .sma-replace-btn').click(function(e) {
		e.preventDefault();

		// If the uploader object has already been created, reopen the dialog
		if (mediaUploader) {
			mediaUploader.open();
			return;
		}
		// Extend the wp.media object
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
			text: 'Choose Image'
		}, multiple: false });
		mediaUploader.open();
		// When a file is selected, grab the URL and set it as the text field's value
		mediaUploader.on('select', function() {
			attachment = mediaUploader.state().get('selection').first().toJSON();
			jQuery('.upload-button').hide();
			jQuery('#uploaded_image').val(attachment.url);
			jQuery('#widget-image').attr('src' , attachment.url).show();			
			jQuery("#customizer_email_preview").contents().find( 'div#template_header_image p img' ).attr('src' , attachment.url).show();
			jQuery('.sma-replace-btn,.sma-remove-btn').css( 'display', 'inline-block' );
			setting_change_trigger();

		});	
	});

	jQuery("#customizer_email_preview").contents().find('.tvalue-total.copify-td').removeClass('copify-td');
	footer_option_hide();
	button_option_show_only_link_and_btn_option();
	
});

jQuery(document).on('click','.Wooflow-email-export',function(){
	
	var data = {
		action: 'woflow_export_import_setting',
		nonce: jQuery('#import_export_nonce_field').val()
	};
	jQuery.ajax({
		url: ajaxurl,//csv_workflow_update,		
		data: data,
		type: 'POST',
		dataType:"json",
		success: function(response) {
			jQuery(document).email_snackbar( "Settings Successfully Export." );
			const linkSource = `data:application/dat;base64,${response}`;
			const downloadLink = document.createElement("a");
			const fileName = "wooflow-email-settings-export.dat";
		
			downloadLink.href = linkSource;
			downloadLink.download = fileName;
			downloadLink.click();
		},
		error: function(response) {
			jQuery(document).email_snackbar_warning( "Settings Not Successfully Export." );
			console.log(response);	
		}
	});
	return;
});

// Add click event handler to button
jQuery( '.Wooflow-email-import' ).click( function () {
	var fileName = document.getElementById('Wooflow-email-import-file').files[0].name;
	
	if ( ! window.FileReader ) {
		return alert( 'FileReader API is not supported by your browser.' );
	}
	var jQueryi = jQuery( '.Wooflow-email-import-file' ), // Put file input ID here
		input = jQueryi[0]; // Getting the element from jQuery
	if ( input.files && input.files[0] && fileName == 'wooflow-email-settings-export.dat') {
		file = input.files[0]; // The file
		fr = new FileReader(); // FileReader instance
		fr.onload = function () {
			var data = {
				action: 'woflow_import_email_setting',
				file_data:fr.result,
				nonce: jQuery('#import_export_nonce_field').val()
			};
			jQuery.ajax({
				url: ajaxurl,		
				data: data,
				type: 'POST',
				dataType:"json",
				success: function(response) {
					jQuery(document).email_snackbar( "Settings Successfully Import." );
					location.reload();
				},
				error: function(response) {
					jQuery(document).email_snackbar_warning( "Settings Not Successfully Import." );
					console.log(response);	
				}
			});
			return;
		};
		//fr.readAsText( file );
		fr.readAsDataURL( file );
	} else {
		// Handle errors here
		alert( "File not selected or browser incompatible." )
	}
} );

jQuery(document).on('click','.email_templates_selects .tab_input',function(){
	if ( jQuery(this).hasClass('checked_class' ) ) {
		return;
	}
	jQuery('.email_templates_selects .tab_input').removeClass('checked_class');
	jQuery(this).addClass('checked_class');
	var r = confirm( 'WARNING: This will override all of your current settings. Are you sure you want to do that? We suggest geting an export of your current settings incase you want to revert back.' );
	if (r === true ) {
		var id = jQuery(this).attr('id');
		var data = {
			action: 'save_email_templete',
			last_clicked_templete: id,
			nonce: email_customizer.nonce,
		};
		jQuery.ajax({
			url: ajaxurl,	
			data: data,
			type: 'POST',
			dataType:"json",
			success: function(response) {
				if( id == 'trackship_SaaS' ) {
					trackship_SaaS();
					// location.reload(true);
				} else if( id == 'woocommerce' ) {
					woocommerce_templete_normal();
					// location.reload(true);
				}
			},
			error: function(response) {
				console.log(response);	
			}
		});
	}
	return;
});

function woocommerce_templete_normal(){
	jQuery("#footer_border_bottom").val('solid');
	jQuery("#footer_border_bottom_color").val('#dcdee2');
	jQuery("#footer_border_bottom_width").val(1);
	jQuery(".footer_border_bottom_width .slider__value").val(1);
	jQuery("#container_width").val(600);
	jQuery(".container_width .slider__value").val(600);
	jQuery("#logo_alignment").val('center');
	jQuery("#heading_text_alignment").val('left');
	jQuery("#logo_position").val('outside');
	jQuery("#logo_width").val(150);
	jQuery(".logo_width .slider__value").val(150);
	jQuery("#container_padding_left_right").val(30);
	jQuery(".container_padding_left_right .slider__value").val(30);
	jQuery("#container_border").val(1);
	jQuery(".container_border .slider__value").val(1);
	jQuery("#container_border_radius").val(0);
	jQuery(".container_border_radius .slider__value").val(0);
	jQuery("#woocommerce_email_body_background_color").val('#ffffff');
	jQuery("#primary_color").val('#96588a');
	jQuery("#woocommerce_email_text_color").val('#180a0a');
	jQuery("#header_font_color").val('#ffffff');
	jQuery("#woocommerce_email_base_color").val('#96588a');
	jQuery("#footer_background_color").val('#ffffff');
	jQuery("#footer_text_color").val('#180a0a');
	jQuery("#woocommerce_email_background_color").val('#ffffff');
	jQuery("#all_link_color").val('#114ed6');
	jQuery("#all_border_color").val('#e0e0e0');
	jQuery("#heading_padding_left_right").val(30);
	jQuery(".heading_padding_left_right .slider__value").val(30);
	jQuery(".radio-button-label .footer_width#full_width, .radio-button-label .header_width#full_width, .radio-button-label .fluid_button_size#Normal").trigger('click');
	jQuery("#contant_font_size").val(14);
	jQuery(".contant_font_size .slider__value").val(14);
	jQuery("#heading_font_size").val(16);
	jQuery(".heading_font_size .slider__value").val(16);
	jQuery("#footer_icon_background_color").val('#ffffff');
	jQuery("#table_left_right_padding").val(15);
	jQuery(".table_left_right_padding .slider__value").val(15);
	jQuery("#fluid_button_background_color").val('#3858e9');
	jQuery("#fluid_button_font_color").val('#ffffff');
	jQuery("#fluid_button_radius").val(0);
	jQuery(".fluid_button_radius .slider__value").val(0);
	jQuery("#custom_style_textarea").val('');
	jQuery("#container_padding_top").val(20);
	jQuery(".container_padding_top .slider__value").val(20);
	jQuery("#container_padding_bottom").val(20);
	jQuery(".container_padding_bottom .slider__value").val(20);
	jQuery("#header_font_size").val(30);
	jQuery(".header_font_size .slider__value").val(30);
	jQuery("#heading_padding_top").val(20);
	jQuery(".heading_padding_top .slider__value").val(20);
	jQuery("#heading_padding_bottom").val(20);
	jQuery(".heading_padding_bottom .slider__value").val(20);
	jQuery("#show_product_image").val('1');
	jQuery("#order_summary_background_color").val('#ffffff');
	jQuery("#table_padding").val(10);
	jQuery(".table_padding .slider__value").val(10);
	jQuery("#order_details_border").val(1);
	jQuery(".order_details_border .slider__value").val(1);
	jQuery("#order_details_border_radius").val(0);
	jQuery(".order_details_border_radius .slider__value").val(0);
	jQuery("#address_alignment").val('left');
	jQuery("#address_box_background_color").val('#ffffff');
	jQuery("#address_border").val(1);
	jQuery(".address_border .slider__value").val(1);
	jQuery("#address_border_radius").val(1);
	jQuery(".address_border_radius .slider__value").val(1);
	jQuery("#subscription_table_padding").val(0);
	jQuery(".subscription_table_padding .slider__value").val(0);
	jQuery("#subscription_table_left_right_padding").val(0);
	jQuery(".subscription_table_left_right_padding .slider__value").val(0);
	jQuery("#subscription_order_details_border").val(1);
	jQuery(".subscription_order_details_border .slider__value").val(1);
	jQuery("#subscription_order_details_border_radius").val(1);
	jQuery(".subscription_order_details_border_radius .slider__value").val(1);
	jQuery("#subscription_order_summary_background_color").val('#ffffff');
	jQuery("#footer_layout").val('center');
	jQuery("#footer_position").val('Outside');
	jQuery("#link_show").val(1);
	jQuery(".link_show .slider__value").val(1);
	jQuery("#subscription_table_padding_top").val(0);
	jQuery(".subscription_table_padding_top .slider__value").val(0);
	jQuery("#subscription_table_padding_bottom").val(0);
	jQuery(".subscription_table_padding_bottom .slider__value").val(0);
	jQuery("#header_line_height").val(35);
	jQuery(".header_line_height .slider__value").val(35);
	// jQuery("#table_padding_top").val(10);
	// jQuery(".table_padding_top .slider__value").val(10);
	// jQuery("#table_padding_bottom").val(10);
	// jQuery(".table_padding_bottom .slider__value").val(10);
	jQuery("#table_heading_font_family").val('"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif');
	jQuery("#table_heading_font_family, #footer_border_bottom, #header_line_height, #subscription_table_padding_bottom, #subscription_table_padding_top, #container_width, #logo_alignment, #heading_text_alignment, #logo_width, #container_padding_left_right, #container_border, #container_border_radius, #woocommerce_email_body_background_color, #primary_color, #woocommerce_email_text_color, #header_font_color, #woocommerce_email_base_color, #logo_position, #header_font_size, #heading_padding_bottom, #order_details_border, #order_summary_background_color, #show_product_image, #heading_padding_top, #container_padding_bottom, #container_padding_top, #custom_style_textarea, #address_border, #fluid_button_radius, #address_box_background_color, #address_alignment, #footer_position, #fluid_button_background_color, #fluid_button_font_color, #table_padding, #subscription_order_details_border, #subscription_table_left_right_padding, #subscription_table_padding, #subscription_order_summary_background_color, #footer_background_color, #footer_layout, #footer_text_color, #woocommerce_email_background_color, #all_link_color, #all_border_color, #heading_padding_left_right, #contant_font_size, #heading_font_size, #footer_icon_background_color, #link_show, #table_left_right_padding").trigger('change');
	setting_change_trigger();
}

function trackship_SaaS() {
	jQuery("#fluid_button_background_color").val('#3858e9');
	jQuery("#fluid_button_font_color").val('#ffffff');
	jQuery("#container_border_radius").val(20);
	jQuery(".container_border_radius .slider__value").val(20);
	jQuery("#heading_text_alignment").val('left');
	jQuery("#table_heading_color").val('#212121');
	jQuery("#table_heading_font_family").val('"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif');
	jQuery("#table_heading_font_size").val(15);
	jQuery(".table_heading_font_size .slider__value").val(15);
	jQuery("#table_heading_font_style").val(600);
	jQuery("#table_heading_line_height").val(35);
	jQuery(".table_heading_line_height .slider__value").val(35);
	jQuery("#table_content_font_family").val('"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif');
	jQuery("#table_content_font_size").val(15);
	jQuery(".table_content_font_size .slider__value").val(15);
	jQuery("#table_content_font_style").val(400);
	jQuery("#table_content_line_height").val(25);
	jQuery(".table_content_line_height .slider__value").val(25);
	jQuery("#address_table_font_family").val('"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif');
	jQuery("#address_table_font_size").val(15);
	jQuery(".address_table_font_size .slider__value").val(15);
	jQuery("#address_table_font_style").val(400);
	jQuery("#address_table_line_height").val(25);
	jQuery(".address_table_line_height .slider__value").val(25);
	jQuery("#footer_content_font_family").val('"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif');
	jQuery("#footer_content_font_size").val(15);
	jQuery(".footer_content_font_size .slider__value").val(15);
	jQuery("#footer_content_font_style").val(400);
	jQuery("#footer_content_line_height").val(25);
	jQuery(".footer_content_line_height .slider__value").val(25);
	jQuery("#header_font_family").val('"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif');
	jQuery("#header_font_size").val(20);
	jQuery(".header_font_size .slider__value").val(20);
	jQuery("#header_font_style").val(600);
	jQuery("#header_line_height").val(20);
	jQuery(".header_line_height .slider__value").val(20);
	jQuery("#address_border").val(1);
	jQuery(".address_border .slider__value").val(1);
	jQuery("#footer_background_color").val('#e0e0e0');
	jQuery("#address_table_padding_top").val(0);
	jQuery(".address_table_padding_top .slider__value").val(0);
	jQuery("#address_table_padding_bottom").val(0);
	jQuery(".address_table_padding_bottom .slider__value").val(0);
	jQuery("#address_order_details_border").val(1);
	jQuery(".address_order_details_border .slider__value").val(1);
	jQuery("#subscription_table_padding_top").val(10);
	jQuery(".subscription_table_padding_top .slider__value").val(10);
	jQuery("#subscription_table_padding_bottom").val(10);
	jQuery(".subscription_table_padding_bottom .slider__value").val(10);
	jQuery("#primary_color").val('#000000');
	jQuery("#woocommerce_email_text_color").val('#212121');
	jQuery("#all_link_color").val('#114ed6');
	jQuery("#all_border_color").val('#e0e0e0');
	jQuery("#footer_text_color").val('#180a0a');
	jQuery("#woocommerce_email_body_background_color").val('#f5f5f5');
	jQuery("#heading_font_size").val(18);
	jQuery(".heading_font_size .slider__value").val(18);
	jQuery("#contant_font_size").val(14);
	jQuery(".contant_font_size .slider__value").val(14);
	jQuery("#logo_alignment").val('left');
	jQuery("#footer_layout").val('center');
	jQuery("#container_padding_top").val(30);
	jQuery(".container_padding_top .slider__value").val(30);
	jQuery("#container_padding_bottom").val(30);
	jQuery(".container_padding_bottom .slider__value").val(30);
	jQuery("#container_width").val(700);
	jQuery(".container_width .slider__value").val(700);
	jQuery("#logo_width").val(150);
	jQuery(".logo_width .slider__value").val(150);
	jQuery("#logo_position").val('inside');
	jQuery("#woocommerce_email_base_color").val('#ffffff');
	jQuery("#header_font_color").val('#424242');
	jQuery("#header_font_size").val(20);
	jQuery(".header_font_size .slider__value").val(20);
	jQuery("#heading_padding_top").val(20);
	jQuery(".heading_padding_top .slider__value").val(20);
	jQuery("#heading_padding_bottom").val(20);
	jQuery(".heading_padding_bottom .slider__value").val(20);
	jQuery("#heading_padding_left_right").val(30);
	jQuery(".heading_padding_left_right .slider__value").val(30);
	jQuery("#show_product_image").val('1');
	jQuery("#order_summary_background_color").val('#ffffff');
	jQuery("#table_padding_top").val(0);
	jQuery(".table_padding_top .slider__value").val(0);
	jQuery("#table_padding_bottom").val(0);
	jQuery(".table_padding_bottom .slider__value").val(0);
	jQuery("#table_left_right_padding").val(15);
	jQuery(".table_left_right_padding .slider__value").val(15);
	jQuery("#order_details_border").val(1);
	jQuery(".order_details_border .slider__value").val(1);
	jQuery("#order_details_border_radius").val(0);
	jQuery(".order_details_border_radius .slider__value").val(0);
	jQuery("#address_alignment").val('left');
	jQuery("#address_box_background_color").val('#ffffff');
	jQuery("#address_border").val(1);
	jQuery(".address_border .slider__value").val(1);
	jQuery("#address_border_radius").val(0);
	jQuery(".address_border_radius .slider__value").val(0);
	jQuery("#subscription_order_summary_background_color").val('#ffffff');
	jQuery("#subscription_table_left_right_padding").val(0);
	jQuery(".subscription_table_left_right_padding .slider__value").val(0);
	jQuery("#subscription_order_details_border").val(1);
	jQuery(".subscription_order_details_border .slider__value").val(1);
	jQuery("#subscription_order_details_border_radius").val(0);
	jQuery(".subscription_order_details_border_radius .slider__value").val(0);
	jQuery("#footer_icon_background_color").val('#ffffff');
	jQuery("#woocommerce_email_background_color").val('#f5f5f5');
	jQuery("#footer_position").val('Inside');
	jQuery("#container_padding_left_right").val(30);
	jQuery(".container_padding_left_right .slider__value").val(30);
	jQuery("#footer_position, #container_padding_left_right, #address_table_padding_bottom, #address_order_details_border, #address_table_padding_top, #subscription_table_padding_top, #subscription_table_padding_bottom, #primary_color, #woocommerce_email_text_color, #all_link_color, #all_border_color, #footer_text_color, #woocommerce_email_body_background_color, #heading_text_alignment, #logo_alignment, #footer_layout, #container_padding_top, #container_padding_bottom, #container_width, #logo_width, #logo_position, #woocommerce_email_base_color, #header_font_color, #header_font_size, #heading_padding_top, #heading_padding_bottom, #heading_padding_left_right, #show_product_image, #order_summary_background_color, #table_padding_top, #table_padding_bottom, #table_left_right_padding, #order_details_border, #order_details_border_radius, #address_alignment, #address_box_background_color, #address_border, #address_border_radius, #subscription_order_summary_background_color, #subscription_table_left_right_padding, #subscription_order_details_border, #subscription_order_details_border_radius, #footer_icon_background_color, #woocommerce_email_background_color, #contant_font_size, #container_border_radius, #fluid_button_background_color, #fluid_button_font_color").trigger('change');
	setting_change_trigger();
}

// jQuery(document).on("click", "#email_settings_two", function(){
// 	var sPageURL = window.location.href.split('&')[0];
// 	window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+'customer_completed_order');
// 	var iframe_url = email_customizer.email_iframe_url+'&email_type='+'customer_completed_order';
	
// 	jQuery('.options_panel').attr('data-iframe_url',iframe_url);
// 	jQuery('iframe').attr('src', iframe_url);

// 	setting_change_trigger();
// });
// jQuery(document).on("click", "#email_settings_three", function(){
// 	var sPageURL = window.location.href.split('&')[0];
// 	window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+'new_order');
// 	var iframe_url = email_customizer.email_iframe_url+'&email_type='+'new_order';
	
// 	jQuery('.options_panel').attr('data-iframe_url',iframe_url);
// 	jQuery('iframe').attr('src', iframe_url);
// 	setting_change_trigger();
// });
// jQuery(document).on("click", "#email_settings_five", function(){
// 	var sPageURL = window.location.href.split('&')[0];
// 	window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+'new_renewal_order');
// 	var iframe_url = email_customizer.email_iframe_url+'&email_type='+'new_renewal_order';
	
// 	jQuery('.options_panel').attr('data-iframe_url',iframe_url);
// 	jQuery('iframe').attr('src', iframe_url);
// 	setting_change_trigger();
// });

jQuery(document).on('click','.sma-remove-btn',function(){
	"use strict"; 
	jQuery('#uploaded_image').val('');
	jQuery("#customizer_email_preview").contents().find( 'div#template_header_image p img' ).hide('src');
	jQuery('#widget-image,.sma-replace-btn,.sma-remove-btn').hide();
	jQuery('.upload-button').show();
	setting_change_trigger();
});

jQuery(document).on("click", ".zoremmail-sub-panel-title", function(event){
	
	var id = jQuery(this).attr('id');
	jQuery('.zoremmail-sub-panels').hide();
	jQuery('.zoremmail-sub-second-panels').show();
	jQuery('.zoremmail-sub-second-panels li.'+id).show();
	button_option_show_only_link_and_btn_option();
	jQuery( ".customize-section-back" ).removeClass('panels').addClass('sub-panels').show();
	
	jQuery('.zoremmail-menu-submenu-title').each(function(index, element) {
		if ( jQuery(this).data('id') ===  id ) {
			jQuery(this).addClass('open');
			jQuery(this).next('.zoremmail-menu-contain').addClass('active');
		} else {
			jQuery(this).removeClass('open');
			jQuery(this).next('.zoremmail-menu-contain').removeClass('active');
		}
	});
		
	jQuery( '#orderStatus' ).select2({
		templateSelection: text_contain,
		minimumResultsForSearch: Infinity
	});
	jQuery( '#customer_email_status' ).select2({
		templateSelection: all_status_text_contain,
		minimumResultsForSearch: Infinity
	});
	
	change_submenu_item();	
	
});

jQuery(document).on("click", ".zoremmail-sub-second-panel-title", function(event){
	
	var id = jQuery(this).attr('id');
	jQuery('.zoremmail-sub-second-panels').hide();
	jQuery( ".customize-section-back" ).removeClass('sub-panels').addClass('sub-second-panels').show();

	jQuery( '.customizer_Breadcrumb' ).html( 'Email Style' );
	
	jQuery('.zoremmail-menu-submenu-title').each(function(index, element) {
		if ( jQuery(this).data('id') ===  id ) {
			jQuery(this).addClass('open');
			jQuery(this).next('.zoremmail-menu-contain').addClass('active');
		} else {
			jQuery(this).removeClass('open');
			jQuery(this).next('.zoremmail-menu-contain').removeClass('active');
		}
	});
		
	jQuery( '#orderStatus' ).select2({
		templateSelection: text_contain,
		minimumResultsForSearch: Infinity
	});
	jQuery( '#customer_email_status' ).select2({
		templateSelection: all_status_text_contain,
		minimumResultsForSearch: Infinity
	});
	change_submenu_item();	
	
});

jQuery(document).on("click", ".zoremmail-panel-title", function(event){
	
	var lable = jQuery(this).data('label');
	var id = jQuery(this).attr('id');
	var Status = jQuery('#orderStatus').val();
	jQuery('.zoremmail-panels').hide();
	jQuery('.sub_options_panel, .sub_second_options_panel').hide();
	jQuery( "#content_slide" ).show();
	jQuery('.header_orderStatus').show();
	jQuery('span.select2.select2-container.select2-container--default').css('width','auto');
	jQuery('.header_order_id_Status').show();
	jQuery( '.zoremmail-layout-content-container' ).css('border-bottom','1px solid #e0e0e0');
	jQuery('.zoremmail-sub-panels, .zoremmail-sub-panels li.'+id).show();
	jQuery( ".customize-section-back" ).addClass('panels').show();
	
    jQuery( ".zoremmail-sub-panel-heading.sub_options_panel."+id ).addClass('open');
	jQuery('.tgl-btn-parent').show();
	jQuery( '.customizer_Breadcrumb' ).html( lable );
	
	// var sPageURL = window.location.href.split('&')[0];
	// window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+Status);
	
	// var iframe_url = email_customizer.email_iframe_url+'&email_type='+Status;
	
	// jQuery('.options_panel').attr('data-iframe_url',iframe_url);
	// jQuery('iframe').attr('src', iframe_url);
	
	if ( jQuery('.zoremmail-sub-panel-title:visible').length == 1 ) {
		jQuery(".zoremmail-sub-panel-title:visible").trigger('click');
	}
	
	jQuery( '#orderStatus' ).select2({
		templateSelection: text_contain,
		minimumResultsForSearch: Infinity
	});
	jQuery( '#customer_email_status' ).select2({
		templateSelection: all_status_text_contain,
		minimumResultsForSearch: Infinity
	});
	
	change_submenu_item();
	var type = getUrlParameter('type');
	if ( type == 'email_content' ) {
		jQuery( ".tgl-btn-parent .tgl_"+Status ).show();
	}
	
});

jQuery(document).on("click", ".customize-section-back", function(){
	
	if ( jQuery(this).hasClass('panels') ) {
		var id = jQuery(this).parents('.zoremmail-sub-panel-heading').data('id');
		var back_id = jQuery(this).parents('.zoremmail-menu-submenu-title').data('id');
		jQuery( '.zoremmail-layout-content-container' ).css('border-bottom','0');
		jQuery('.header_orderStatus').hide();
		jQuery('.header_order_id_Status').hide();
		jQuery('.sub_options_panel, .sub_second_options_panel').hide();
		jQuery('.tgl-btn-parent').hide();
		jQuery( '.customizer_Breadcrumb' ).html('');
		jQuery( ".customize-section-back" ).hide();
		jQuery( ".zoremmail-sub-second-panels" ).hide();
		jQuery( ".zoremmail-panel-title, .zoremmail-layout-sider-heading .main_logo" ).show();
		jQuery( ".zoremmail-panels" ).show();
		if( back_id == 'email_templets' ||  back_id == 'email_footer_panel' || back_id == 'Container_sub_panel' || back_id == 'header_sub_panel' || back_id == 'import_export_options' || back_id == 'send_test_email_options' || back_id == 'email_settings_two') {
			jQuery( ".zoremmail-sub-panel-heading.sub_options_panel.select_email_template" ).removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.select_email_template').removeClass('active');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.email_content').removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.email_content').removeClass('active');
			jQuery( ".zoremmail-sub-panel-heading.sub_options_panel.email_footer" ).removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.email_footer').removeClass('active');
			jQuery( ".zoremmail-sub-panel-heading.sub_options_panel.Container_panel" ).removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.Container_panel').removeClass('active');
			jQuery( ".zoremmail-sub-panel-heading.sub_options_panel.header_panel" ).removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.header_panel').removeClass('active');
			jQuery( ".zoremmail-sub-panel-heading.sub_options_panel.import_export_btn" ).removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.import_export_btn').removeClass('active');
			jQuery( ".zoremmail-sub-panel-heading.sub_options_panel.send_test_email" ).removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.send_test_email').removeClass('active');
			
		} else {
			jQuery( ".zoremmail-sub-panel-heading.sub_options_panel."+id ).removeClass('open');
			jQuery('.zoremmail-sub-panel-heading.sub_options_panel.'+id).removeClass('active');
		}
		
	}
	if ( jQuery(this).hasClass('sub-panels') ) {
		jQuery('.zoremmail-sub-second-panels, .zoremmail-sub-second-panels li').hide();
		jQuery( ".customize-section-back" ).removeClass('sub-panels').addClass('panels');
		jQuery( ".zoremmail-sub-panels" ).show();
		if ( jQuery('.zoremmail-sub-panel-title:visible').length == 1 ) {
			jQuery(this).trigger('click');
		}
		jQuery('.zoremmail-menu-contain').removeClass('active');
		jQuery('.zoremmail-menu-submenu-title').removeClass('open');
		jQuery('.zoremmail-menu-submenu-title').removeClass('active');
	}

	if ( jQuery(this).hasClass('sub-second-panels') ) {
		jQuery( '.customizer_Breadcrumb' ).html( 'Email Design' );
		jQuery( ".customize-section-back" ).removeClass('sub-second-panels').addClass('sub-panels');
		jQuery( ".zoremmail-sub-second-panels" ).show();
		if ( jQuery('.zoremmail-sub-second-panel-title:visible').length == 1 ) {
			jQuery(this).trigger('click');
		}
		jQuery('.zoremmail-menu-contain').removeClass('active');
		jQuery('.zoremmail-menu-submenu-title').removeClass('open');
		jQuery('.zoremmail-menu-submenu-title').removeClass('active');
	}

});

jQuery(document).on("click", "#woocommerce_email_options .wclp-save", function(){
	"use strict";
	var form = jQuery('#woocommerce_email_options');
	var btn = jQuery('#woocommerce_email_options .wclp-save');
	jQuery.ajax({
		url: ajaxurl,//csv_workflow_update,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",
		beforeSend: function(){
			btn.prop('disabled', true).html('Please wait..');
		},		
		success: function(response) {
			if( response.success === "true" ){
				btn.prop('disabled', true).html('Saved');
				jQuery(document).email_snackbar( "Settings Successfully Saved." );
				jQuery('.zoremmail-back-wordpress-link').removeClass('back_to_notice');
				setting_change_trigger();
			} else {
				if( response.permission === "false" ){
					btn.prop('disabled', false).html('Save Changes');
					jQuery(document).email_snackbar_warning( "you don't have permission to save settings." );
				}
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
});

jQuery(document).on("change", "#orderStatus", function(){
	
	"use strict";
	
	jQuery('.zoremmail-layout-content-preview').addClass('customizer-unloading');

	var sPageURL = window.location.href.split('&')[0];
	var Status = jQuery('#orderStatus').val();
	jQuery('#selected_orderStatus').val(Status);
	jQuery('#customer_email_status').val(Status);
	window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+Status);
	
	var iframe_url = email_customizer.email_iframe_url+'&email_type='+Status;
	
	jQuery('.options_panel').attr('data-iframe_url',iframe_url);
	jQuery('iframe').attr('src', iframe_url);

	change_submenu_item();
	jQuery( ".tgl-btn-parent span" ).hide();
	jQuery( ".tgl-btn-parent .tgl_"+Status ).show();
	button_option_show_only_link_and_btn_option();
	return false;

});

function button_option_show_only_link_and_btn_option(){
	jQuery('.zoremmail-menu-sub.button_option_show_only_link_and_btn').hide();
	jQuery('li#template_setting_seven.button_option_show_only_link_and_btn.email_style_panel').hide();
	
	var Status = jQuery('#orderStatus').val();
	if ( Status == 'customer_invoice' || Status == 'customer_new_account' || Status == 'customer_reset_password' || Status == 'customer_renewal_invoice' ) {
		jQuery('.zoremmail-menu-sub.button_option_show_only_link_and_btn').show();
		jQuery('li#template_setting_seven.button_option_show_only_link_and_btn.email_style_panel').show();
	}
}

jQuery(document).on("click", "#email_content", function(){
	jQuery( ".header_orderStatus" ).hide();
	jQuery('.header_order_id_Status').hide();
	jQuery('span.select2.select2-container.select2-container--default').css('width','100%');
	jQuery( "#content_slide" ).hide();
});

jQuery(document).on("change", "#email_selected_order_id", function(){
	
	"use strict";
	jQuery('.zoremmail-layout-content-preview').addClass('customizer-unloading');

	var email_selected_order_id = jQuery('#email_selected_order_id').val();
	var data = {
		action: 'email_header_setting',
		email_selected_order_id: email_selected_order_id,
		nonce: email_customizer.nonce,
	};
	
	jQuery.ajax({
		url: ajaxurl,//csv_workflow_update,		
		data: data,
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			if( response.success === "true" ){
				var Status = jQuery('#selected_orderStatus').val();
				var iframe_url = email_customizer.email_iframe_url+'&email_type='+Status;
				jQuery('iframe').attr('src', iframe_url);
			} else {
				if( response.permission === "false" ){
					jQuery(document).email_snackbar_warning( "please reload page." );
					console.log(response);
				}
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;

});
jQuery(document).on("click", ".woflow-send-to-email", function(){

	"use strict";
	var btn = jQuery('.efc-save.woflow-send-to-email');
	var email = jQuery("#send_to_email").val();
	var email_type = jQuery('#selected_orderStatus').val();
	jQuery("#send_to_email").css('border-color', '');
	jQuery('.validation_message').remove();

	if ( email == '' ) {
		jQuery("#send_to_email").css('border-color', 'red');
		return false;
	}
	var emails = email.split(',');
	var valid = true;
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	for (var i = 0; i < emails.length; i++) {
        if( emails[i] === "" || !regex.test(emails[i])){
            valid = false;
        }
    }
	if ( valid === false ) { 
		jQuery('#send_to_email').after('<span class="validation_message" style="color:#f44336;font-size:12px;display: 		  block;">Please enter valid Email address.</span>')
		return false;
	}
	var ajaxdata = {
		action:'woflow_send_to_email_setting',
		email:email,
		email_type:email_type,
		nonce:jQuery('#send_test_email_nonce_field').val(),
	}
	jQuery.ajax({
		url: ajaxurl,	
		data: ajaxdata,
		type: 'POST',
		dataType:"json",
		beforeSend: function(){
			btn.html('<div class="dot-carousel"></div>');
		},	
		success: function(response) {
			if( response.success === "true" ){
				btn.html('Send Email');
				jQuery(document).email_snackbar( "Test email was sent successfully" );
			}
		},
		error: function(response) {
			btn.html('Send Email');		
			jQuery(document).email_snackbar_warning( "Email sent has Failed!" );
		}
	});
	return false;
});

jQuery('.collapse-sidebar-hide').click(function () {
	jQuery('.zoremmail-layout-sider').toggle( function (){
		jQuery('.collapse-sidebar').show();
	});
	jQuery('.zoremmail-layout.zoremmail-layout-has-sider').css('margin-left','0');
	jQuery('.zoremmail-layout-content-container').css('display','none');
});
jQuery('.collapse-sidebar').click(function () {
	jQuery('.collapse-sidebar').hide();
	jQuery('.zoremmail-layout-content-container').css('display','block');
	jQuery('.zoremmail-layout-sider').toggle( 1000 );
	jQuery('.zoremmail-layout.zoremmail-layout-has-sider').css('margin-left','346px');
});

jQuery('.question_icon').click(function () {

	if ( jQuery(this).hasClass('slide_show') ) {
		jQuery('.email_important_msg').slideUp("slow");
		jQuery('.question_icon').removeClass('slide_show');
		jQuery('div.customize-section-title h3').css('border-bottom','0');
	} else {
		jQuery('.email_important_msg').slideDown("slow");
		jQuery('.email_important_msg').show();
		jQuery('div.customize-section-title h3').css('border-bottom','1px solid #e0e0e0');
		jQuery('.question_icon').addClass('slide_show');
	}
});


function change_submenu_item() {

	var Status = jQuery('#orderStatus').val();
	jQuery( '.all_status_submenu' ).hide();
	jQuery( '.all_status_submenu.' + Status + '_sub_menu' ).show();

	var customer_email_status = jQuery('#customer_email_status').val();
	jQuery( '.all_cusomer_status_submenu' ).hide();
	jQuery( '.all_cusomer_status_submenu.' + customer_email_status + '_sub_menu' ).show();

	// var admin_email_status = jQuery('#admin_email_status').val();
	// jQuery( '.all_admin_status_submenu' ).hide();
	// jQuery( '.all_admin_status_submenu.' + admin_email_status + '_sub_menu' ).show();

	// var Subscription_emails_status = jQuery('#Subscription_emails_status').val();
	// jQuery( '.all_subscription_status_submenu' ).hide();
	// jQuery( '.all_subscription_status_submenu.' + Subscription_emails_status + '_sub_menu' ).show();

}
jQuery(document).on("click", ".radio-button-label input", function(){
	if( jQuery( this ).val() == 15 ) {
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'padding', '8px 30px');
	} else {
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'padding', '8px 50px');
	}
	setting_change_trigger();
});

jQuery(document).on("click", "#email_content", function(){
	jQuery('.header_order_id_Status').show().css('margin-left','5px');
});

jQuery(document).on("change", "#customer_email_status", function(){
	"use strict";
	jQuery('.zoremmail-layout-content-preview').addClass('customizer-unloading');
	
	var sPageURL = window.location.href.split('&')[0];
	var customer_emails_orderStatus = jQuery('#customer_email_status').val();
	
	jQuery('#orderStatus').val(customer_emails_orderStatus);

	jQuery('#selected_orderStatus').val(customer_emails_orderStatus);

	window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+customer_emails_orderStatus);
	
	var iframe_url = email_customizer.email_iframe_url+'&email_type='+customer_emails_orderStatus;
	
	jQuery('.options_panel').attr('data-iframe_url',iframe_url);
	jQuery('iframe').attr('src', iframe_url);
	change_submenu_item();
	return false;

});

// jQuery(document).on("change", "#admin_email_status", function(){
// 	"use strict";
// 	jQuery('.zoremmail-layout-content-preview').addClass('customizer-unloading');
	
// 	var sPageURL = window.location.href.split('&')[0];
// 	var admin_emails_orderStatus = jQuery('#admin_email_status').val();
	
// 	jQuery('#selected_orderStatus').val(admin_emails_orderStatus);

// 	window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+admin_emails_orderStatus);
	
// 	var iframe_url = email_customizer.email_iframe_url+'&email_type='+admin_emails_orderStatus;
	
// 	jQuery('.options_panel').attr('data-iframe_url',iframe_url);
// 	jQuery('iframe').attr('src', iframe_url);
// 	change_submenu_item();
// 	return false;

// });

// jQuery(document).on("change", "#Subscription_emails_status", function(){
// 	"use strict";
// 	jQuery('.zoremmail-layout-content-preview').addClass('customizer-unloading');
	
// 	var sPageURL = window.location.href.split('&')[0];
// 	var subscription_emails_orderStatus = jQuery('#Subscription_emails_status').val();
// 	jQuery('#selected_orderStatus').val(subscription_emails_orderStatus);
// 	// jQuery('#orderStatus').val(subscription_emails_orderStatus);

// 	window.history.pushState("object or string", sPageURL, sPageURL+'&email_type='+subscription_emails_orderStatus);
	
// 	var iframe_url = email_customizer.email_iframe_url+'&email_type='+subscription_emails_orderStatus;
	
// 	jQuery('.options_panel').attr('data-iframe_url',iframe_url);
// 	jQuery('iframe').attr('src', iframe_url);

// 	change_submenu_item();
// 	return false;

// });

function text_contain(state) {
	return 'Preview: ' + state.text;
};
function all_status_text_contain(state) {
	return state.text;
};

jQuery('iframe').load(function(){
	jQuery('.zoremmail-layout-content-preview').removeClass('customizer-unloading');
	jQuery("#customizer_email_preview").contents().find( 'div#query-monitor-main' ).css( 'display', 'none');
	jQuery( '.zoremmail-layout-content-media .last-checked .dashicons' ).trigger('click');
	jQuery( '#customizer_email_preview' ).contents().find( 'body' ).css( 'margin', '0');
	jQuery( '#customizer_email_preview' ).contents().find( 'body' ).css( 'background', '#ffffff');
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	
	if ( select_email_template == 'woocommerce' ) {
		jQuery( '.header_show_zorem_template' ).addClass( 'show_zorem_template' );
		jQuery( '.header_show_woocommerce_template' ).removeClass( 'show_zorem_template' );
		jQuery( '.show_footer_position, .footer_background_color, .footer_border_bottom, .footer_border_bottom_color, .footer_border_bottom_width, .link_show, .show_product_image, .order_summary_background_color, .order_details_border_radius, .subscription_order_details_border_radius, .subscription_order_summary_background_color, .subscription_table_left_right_padding, .heading_padding_top, .heading_padding_bottom, .heading_padding_left_right, .woocommerce_email_base_color, .container_border, .container_background_color' ).removeClass( 'hide_footer_position' );
		jQuery( '.header_width' ).addClass( 'hide_footer_position' );
	} else if( select_email_template == 'trackship_SaaS' ) {
		// jQuery( '.subscription_order_details_border_radius' ).addClass( 'hide_options_zorem_template' );

		jQuery( '.address_border.zorem_template_none, .address_border_radius.zorem_template_none' ).removeClass( 'hide_options_zorem_template' );

		jQuery( '.logo_position' ).addClass( 'hide_options_zorem_template' );

		jQuery( '.show_footer_position, .footer_background_color, .footer_border_bottom, .footer_border_bottom_color, .footer_border_bottom_width, .link_show, .show_product_image, .order_summary_background_color, .order_details_border_radius, .subscription_order_details_border_radius, .subscription_order_summary_background_color, .subscription_table_left_right_padding, .heading_padding_top, .heading_padding_bottom, .heading_padding_left_right, .header_width, .woocommerce_email_base_color, .container_border, .container_background_color' ).addClass( 'hide_footer_position' );

		jQuery( '.subscription_table_left_right_padding' ).addClass( 'hide_footer_position' );

		jQuery( '.header_show_zorem_template, .header_show_woocommerce_template' ).addClass( 'show_zorem_template' );
	} else {
		jQuery( '.logo_position' ).removeClass( 'hide_options_zorem_template' );
		jQuery( '.header_show_zorem_template' ).removeClass( 'show_zorem_template' );
		jQuery( '.show_footer_position, .footer_background_color, .footer_border_bottom, .footer_border_bottom_color, .footer_border_bottom_width, .link_show, .show_product_image, .order_summary_background_color, .order_details_border_radius, .subscription_order_details_border_radius, .subscription_order_summary_background_color, .subscription_table_left_right_padding, .heading_padding_top, .heading_padding_bottom, .heading_padding_left_right, .header_width, .woocommerce_email_base_color, .container_border, .container_background_color' ).addClass( 'hide_footer_position' );
	}
});

jQuery(document).ready(function(){
	jQuery( '#customizer_email_preview' ).contents().find( 'body' ).css( 'margin', '0');
	jQuery( '#customizer_email_preview' ).contents().find( 'body' ).css( 'background', '#ffffff');
	jQuery( '#orderStatus' ).select2({
		templateSelection: text_contain,
		minimumResultsForSearch: Infinity
	});
	jQuery( '#customer_email_status' ).select2({
		templateSelection: all_status_text_contain,
		minimumResultsForSearch: Infinity
	});
	jQuery('.zoremmail-input.color').wpColorPicker();
	
	// jQuery('.zoremmail-input').on("keyup", function(){
	// 	setting_change_trigger();
	// });
	
	jQuery(document).on("change", ".tgl.tgl-flat, .zoremmail-checkbox, .zoremmail-input.color, .zoremmail-range, .zoremmail-input.select", function(){
		setting_change_trigger();
	});
	change_submenu_item();
});
jQuery( ".zoremmail-menu.heading" ).keyup( function( event ) {
	var str = event.target.value;
	var res = str.replace("{site_title}", email_customizer.site_title);
	var res = res.replace("{order_number}", email_customizer.order_number);
	var res = res.replace("{customer_first_name}", email_customizer.customer_first_name);
	var res = res.replace("{customer_last_name}", email_customizer.customer_last_name);
	var res = res.replace("{order_date}", email_customizer.order_date);
	if( str ){				
		jQuery("#customizer_email_preview").contents().find( 'h1:first' ).text(res);
	} else{
		jQuery("#customizer_email_preview").contents().find( 'h1:first' ).text(event.target.placeholder);
	}
	setting_change_trigger();
});
jQuery( "#custom_style_textarea" ).keyup( function( event ) {
	setting_change_trigger();
});

jQuery( ".zoremmail-menu.heading_paid" ).keyup( function( event ) {
	var str = event.target.value;
	var res = str.replace("{site_title}", email_customizer.site_title);
	var res = res.replace("{order_number}", email_customizer.order_number);
	if( str ){				
		jQuery("#customizer_email_preview").contents().find( 'h1:first' ).text(res);
	} else{
		jQuery("#customizer_email_preview").contents().find( 'h1:first' ).text(event.target.placeholder);
	}
	setting_change_trigger();
});

// Initialize a variable to store the timeout ID
var debounceTimer;

// Debounced function
function debounce(func, delay) {
  // Clear the previous timeout, if any
  clearTimeout(debounceTimer);
  
  // Set a new timeout
  debounceTimer = setTimeout(func, delay);
}

// Event handler for input changes
jQuery( ".zoremmail-menu.email_additional_content .email_additional_content " ).keyup( function( event ) {

  // Define the function to be debounced
  var updatePreview = function() {
    var str = jQuery(this).val();
    var res = str.replace("{site_title}", email_customizer.site_title)
      .replace("{order_number}", email_customizer.order_number)
      .replace("{customer_first_name}", email_customizer.customer_first_name)
      .replace("{customer_last_name}", email_customizer.customer_last_name)
      .replace("{customer_company_name}", email_customizer.customer_company_name)
      .replace("{customer_username}", email_customizer.customer_username)
      .replace("{customer_email}", email_customizer.customer_email)
      .replace("{order_date}", email_customizer.order_date)
      .replace("{customer_full_name}", email_customizer.customer_full_name);

    if (str) {
      jQuery("#customizer_email_preview").contents().find('div#body_content_inner p.first_span_text').text(res);
    } else {
      var placeholder = jQuery(this).attr("placeholder");
      jQuery("#customizer_email_preview").contents().find('div#body_content_inner p.first_span_text').text(placeholder);
    }
    setting_change_trigger();
  };
  
  // Call the debounced function with a delay of 300 milliseconds
  debounce(updatePreview.bind(this), 500);
});

jQuery( ".zoremmail-input.additional_content" ).keyup( function( event ) {
	setting_change_trigger();
	var str = event.target.value;
	var res = str.replace("{site_title}", email_customizer.site_title);
	var res = res.replace("{site_url}", email_customizer.site_url);
	if( str ){				
		jQuery("#customizer_email_preview").contents().find( 'div#body_content_inner p.additional_content_h6' ).text(res);
	} else{
		jQuery("#customizer_email_preview").contents().find( 'div#body_content_inner p.additional_content_h6' ).text(event.target.placeholder);
	}
}); 
jQuery(document).on("change", "#logo_position", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
});

//Logo Alignment
jQuery(document).on("change", "#logo_alignment", function(){

	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == 'left' ){
		jQuery("#customizer_email_preview").contents().find( ' div#template_header_image p ,td#header_wrapper p' ).css( 'text-align', 'left' );
	} else if ( value == 'right' ){					
		jQuery("#customizer_email_preview").contents().find( 'div#template_header_image p,td#header_wrapper p' ).css( 'text-align', 'right' );
	} else if ( value == 'center' ){					
		jQuery("#customizer_email_preview").contents().find( 'div#template_header_image p,td#header_wrapper p' ).css( 'text-align', 'center' );
	}

});

//header h1 text Alignment
jQuery(document).on("change", "#heading_text_alignment", function(){

	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == 'left' ){
		jQuery("#customizer_email_preview").contents().find( 'h1' ).css( 'text-align', 'left' );
	} else if ( value == 'right' ){					
		jQuery("#customizer_email_preview").contents().find( 'h1' ).css( 'text-align', 'right' );
	} else if ( value == 'center' ){					
		jQuery("#customizer_email_preview").contents().find( 'h1' ).css( 'text-align', 'center' );
	}
});

//show product image
jQuery(document).on("change", "#show_product_image", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
});

//logo position
jQuery(document).on("change", "#logo_position", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
});
	
//color filter
if ( jQuery.fn.wpColorPicker ) {

	jQuery('#email_bg_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('div#wrapper' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
	});

	//new design start
	jQuery('#primary_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('h2' ).css( 'color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#woocommerce_email_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			
			var footer_position = jQuery('#footer_position').val();
			if(footer_position == 'Outside' ){
				jQuery("#customizer_email_preview").contents().find('table#template_footer' ).css( 'background-color', color );
			}
			var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
			if( select_email_template == 'trackship_SaaS' ) {
				jQuery("#customizer_email_preview").contents().find('body #wrapper, td#header_wrapper, #template_header, #template_container' ).css( 'background-color', color );
				jQuery("#customizer_email_preview").contents().find('table#template_footer' ).css( 'background-color', color );
			} else if ( select_email_template == 'woocommerce' && footer_position == 'Outside' ) {
				jQuery("#customizer_email_preview").contents().find('#wrapper, table#template_footer' ).css( 'background-color', color );
			} else {
				jQuery("#customizer_email_preview").contents().find('#wrapper' ).css( 'background-color', color );
			}
			setting_change_trigger();
		}, 	
	});
	jQuery('#footer_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('#template_footer, .footer_container' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#footer_icon_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('.social-link-url img' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#woocommerce_email_text_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
			if( select_email_template == 'trackship_SaaS' ) {
				jQuery("#customizer_email_preview").contents().find('.woocommerce_table_style tr td span, .woocommerce_table_style tr.woo_label_tr th, .subscription-table tr.woo_label_tr th, .subscription-table tr.woo_label_tr td, table#addresses .address p, p.first_span_text, p.additional_content_h6, .subscription-table th.subscription_price_th, .subscription-table td.subscription_price, table.subscription-table td.subscription_end_date, table.subscription-table th.subscription_end_date_th, .last_message, .additional_content_h6, p, ul li' ).css( 'color', color );
			} else {
				jQuery("#customizer_email_preview").contents().find('#body_content_inner .woocommerce_table_style *, #body_content_inner .subscription-table tr th, #body_content_inner .subscription-table tr td, .first_span_text, .address p, .last_message, .additional_content_h6, p, ul li' ).css( 'color', color );
			}
			
			setting_change_trigger();
		}, 	
	});
	jQuery('#all_link_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('a' ).css( 'color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#footer_text_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('table#template_footer p' ).css( 'color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#all_border_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('#template_container, table.woocommerce_table_style,address.address, table.subscription-table, table.addresses, .subscription-table-background-color, table.subscription_table, .additional_content_h6, div.last_message, div.heading_with_content, th.woo_product_th, th.woo_price_th, th.subscription_id_th' ).css( 'border-color', color );
			jQuery("#customizer_email_preview").contents().find('th.subscription_id_th, th.subscription_price_th, th.subscription_start_date_th ' ).css( 'border-right-color', color );
			jQuery("#customizer_email_preview").contents().find('td.subscription_id, td.subscription_price, td.subscription_start_date' ).css( 'border-right-color', color );
			jQuery("#customizer_email_preview").contents().find('td.subscription_id, td.subscription_price, td.subscription_start_date, td.subscription_end_date' ).css( 'border-top-color', color );

			jQuery("#customizer_email_preview").contents().find('th.woo_product_th, th.woo_qty_th, td.woo_image_id, td.woo_qty_id, td.woo_qty_id, th.woo_label_th, th.woo_note_th' ).css( 'border-right-color', color );
			jQuery("#customizer_email_preview").contents().find('td.woo_image_id, .td.woo_qty_id, td.woo_price_id, th.woo_label_th, td.woo_value_th, th.woo_note_th, td.woo_note_value_th' ).css( 'border-top-color', color );
			jQuery("#customizer_email_preview").contents().find('td.woo_image_id.td.img-padding.copify-td, .order_item td, .woocommerce_table_style tbody .order_item:first-child td, tfoot tr:last-child th, tfoot tr:last-child td' ).css( 'border-top-color', color );
			jQuery("#customizer_email_preview").contents().find('.order_item td:first-child, .order_item td:last-child, .td.woo_qty_id' ).css( 'border-bottom-color', color );
			jQuery("#customizer_email_preview").contents().find('.subscription-table-background-color table.subscription-table thead tr td, .subscription-table-background-color table.subscription-table thead tr th' ).css( 'border-bottom-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#woocommerce_email_body_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('#body_content_inner' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#table_heading_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
			if( select_email_template == 'trackship_SaaS' ) {
				jQuery("#customizer_email_preview").contents().find('.woo_product_th, .woo_price_th, .subscription_id_th, #addresses h2, h2' ).css( 'color', color );
			} else {
				jQuery("#customizer_email_preview").contents().find(' h2' ).css( 'color', color );
			}
			setting_change_trigger();
		}, 	
	});
	
	jQuery('#order_summary_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('#body_content_inner table.woocommerce_table_style, table.woocommerce_table_style tbody .order_item, table.subscription_table' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
		defaultColor: true,
	});
	jQuery('#subscription_order_summary_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('div.subscription-table-background-color, table.subscription-table' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#fluid_button_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('p.customer_account_btn, .invoice_btn' ).css( 'background-color', color );
			jQuery("#customizer_email_preview").contents().find('p.customer_account_btn, .invoice_btn' ).css( 'border-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#fluid_button_font_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('p.customer_account_btn a, .invoice_btn a' ).css( 'color', color );
			setting_change_trigger();
		}, 	
	});
	//new design end
	jQuery('#woocommerce_email_base_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('#template_header, td#header_wrapper' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#header_font_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('td#header_wrapper h1, h1' ).css( 'color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#address_box_background_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('address.address, table#addresses' ).css( 'background-color', color );
			setting_change_trigger();
		}, 	
	});
	jQuery('#footer_border_bottom_color').wpColorPicker({
		change: function(e, ui) {		
			var color = ui.color.toString();
			jQuery("#customizer_email_preview").contents().find('table#template_footer' ).css( 'border-color', color );
			setting_change_trigger();
		}, 	
	});
	
}
//footer inside and outside
jQuery(document).on("change", "#footer_position", function(){
	var value = jQuery( this ).val();
	var footer_background_color = jQuery('#footer_background_color').val();
	var woocommerce_email_background_color = jQuery('#woocommerce_email_background_color').val();
	if ( 'Inside' == value ) {
		jQuery("#customizer_email_preview").contents().find( 'td#credit' ).css( 'border-top', 'none');	
		jQuery("#customizer_email_preview").contents().find('table#template_footer' ).css( 'background-color', footer_background_color );
	} else {
		jQuery("#customizer_email_preview").contents().find('table#template_footer' ).css( 'background-color', woocommerce_email_background_color );
	}
	footer_option_hide();
	setting_change_trigger();

});

function footer_option_hide() {
	var value = jQuery( '#footer_position' ).val();
	jQuery('.zoremmail-menu-sub.Footer_position_outside_hide').hide();
	if ( value == 'Inside' ) {
		jQuery('.zoremmail-menu-sub.Footer_position_outside_hide').show();
	}
}

jQuery(document).on("change", "#padding_top_bottom", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('div#wrapper' ).css( 'padding-top', value+'px' );
	jQuery("#customizer_email_preview").contents().find('div#wrapper' ).css( 'padding-bottom', value+'px' );
});

jQuery(document).on("change", ".padding_top_bottom .slider__value", function(){
	var value = jQuery( this ).val();
	
	setting_change_trigger();
	jQuery('#padding_top_bottom').val(value);
	jQuery('.zoremmail-menu #padding_top_bottom').trigger('change');
	
});

jQuery(document).on("change", "#logo_width", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('div#template_header_image p img, td#header_wrapper p img' ).css( 'width', value+'px' );
});
jQuery(document).on("change", ".logo_width .slider__value", function(){
	var value = jQuery( this ).val();
	
	setting_change_trigger();
	jQuery('#logo_width').val(value);
	jQuery('.zoremmail-menu #logo_width').trigger('change');
	
});
jQuery(document).on("change", "#top_bottom_padding", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('div#template_header_image p img, td#header_wrapper p img' ).css( 'padding-top', value+'px' );
	jQuery("#customizer_email_preview").contents().find('div#template_header_image p img, td#header_wrapper p img' ).css( 'padding-bottom', value+'px' );
});
jQuery(document).on("change", ".top_bottom_padding .slider__value", function(){
	var value = jQuery( this ).val();
	
	setting_change_trigger();
	jQuery('#top_bottom_padding').val(value);
	jQuery('.zoremmail-menu #top_bottom_padding').trigger('change');
	
});

jQuery(document).on("change", "#heading_top_bottom_padding", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('div#template_header_image h1, td#header_wrapper h1' ).css( 'padding-top', value+'px' );
	jQuery("#customizer_email_preview").contents().find('div#template_header_image h1, td#header_wrapper h1' ).css( 'padding-bottom', value+'px' );
});
jQuery(document).on("change", ".heading_top_bottom_padding .slider__value", function(){
	var value = jQuery( this ).val();
	
	setting_change_trigger();
	jQuery('#heading_top_bottom_padding').val(value);
	jQuery('.zoremmail-menu #heading_top_bottom_padding').trigger('change');
	
});

jQuery(document).on("change", "#content_width", function(){
	var value = jQuery( this ).val();
	jQuery('a.last-checked').attr('data-width' , value+'px');
	jQuery("#customizer_email_preview").contents().find('#template_container' ).css( 'width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('#template_header' ).css( 'width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('#template_header_image' ).css( 'width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('#template_body' ).css( 'width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('#template_footer' ).css( 'width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('#template_footer_container' ).css( 'width', value+'px' );
	setting_change_trigger();
});

jQuery(document).on("change", ".content_width .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#content_width').val(value);
	jQuery('.zoremmail-menu #content_width').trigger('change');
	
});
jQuery(document).on("change", "#Border_width", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('body #template_container' ).css( 'border-width', value+'px' );

});
jQuery(document).on("change", ".Border_width .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#Border_width').val(value);
	jQuery('.zoremmail-menu #Border_width').trigger('change');
});

jQuery(document).on("change", "#fluid_button_border", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('p.customer_account_btn, button.btn_zorem_my_account.customer_account_btn' ).css( 'border-width', value+'px' );
});

jQuery(document).on("change", ".fluid_button_border .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#fluid_button_border').val(value);
	jQuery('.zoremmail-menu #fluid_button_border').trigger('change');
});

//new font-size start date( 15-8-2022 )
jQuery(document).on("change", "#heading_font_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('h2' ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".heading_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#heading_font_size').val(value);
	jQuery('.zoremmail-menu #heading_font_size').trigger('change');
});
jQuery(document).on("change", "#contant_font_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('p,td,address,h6,span,th, #template_footer a, .heading_with_content ul li, .additional_content_h6, .heading_with_content p, p.first_span_text' ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".contant_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#contant_font_size').val(value);
	jQuery('.zoremmail-menu #contant_font_size').trigger('change');
});
jQuery(document).on("change", "#container_padding_top", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#body_content_inner' ).css( 'padding-top', value+'px' );
});
jQuery(document).on("change", ".container_padding_top .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#container_padding_top').val(value);
	jQuery('.zoremmail-menu #container_padding_top').trigger('change');
});
jQuery(document).on("change", "#container_padding_bottom", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#body_content_inner' ).css( 'padding-bottom', value+'px' );
});
jQuery(document).on("change", ".container_padding_bottom .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#container_padding_bottom').val(value);
	jQuery('.zoremmail-menu #container_padding_bottom').trigger('change');
});
jQuery(document).on("change", "#container_width", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#template_container, #template_header, #template_header_image, #template_footer' ).css( 'width', value+'px' );
	
});
jQuery(document).on("change", ".container_width .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#container_width').val(value);
	jQuery('.zoremmail-menu #container_width').trigger('change');
});
jQuery(document).on("change", "#container_padding_left_right", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#body_content_inner' ).css( 'padding-left', value+'px' );
	jQuery("#customizer_email_preview").contents().find('#body_content_inner' ).css( 'padding-right', value+'px' );
});
jQuery(document).on("change", ".container_padding_left_right .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#container_padding_left_right').val(value);
	jQuery('.zoremmail-menu #container_padding_left_right').trigger('change');
});
jQuery(document).on("change", "#container_border", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#template_container' ).css( 'border-width', value+'px' );
});
jQuery(document).on("change", ".container_border .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#container_border').val(value);
	jQuery('.zoremmail-menu #container_border').trigger('change');
});
jQuery(document).on("change", "#container_border_radius", function(){
	var value = jQuery( this ).val();
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table#template_body').css( 'border-radius', value+'px');
	} else if ( 'woocommerce' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table#template_container').css( 'border-radius', value+'px');
	}
	setting_change_trigger();
});
jQuery(document).on("change", ".container_border_radius .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#container_border_radius').val(value);
	jQuery('.zoremmail-menu #container_border_radius').trigger('change');
});
jQuery(document).on("change", "#fluid_button_radius", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('p.customer_account_btn, .invoice_btn' ).css( 'border-radius', value+'px' );
});
jQuery(document).on("change", ".fluid_button_radius .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#fluid_button_radius').val(value);
	jQuery('.zoremmail-menu #fluid_button_radius').trigger('change');
});
jQuery(document).on("change", "#order_details_border", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table.woocommerce_table_style' ).css( 'border-width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('th.woo_product_th, th.woo_qty_th, td.woo_image_id, td.woo_qty_id, th.woo_label_th, th.woo_note_th' ).css( 'border-right-width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('th.woo_product_th, th.woo_qty_th, td.woo_image_id, td.woo_qty_id, th.woo_label_th, th.woo_note_th, .woo_price_th' ).css( 'border-bottom-width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('td.woo_image_id, td.woo_qty_id, td.woo_price_id, th.woo_label_th, td.woo_value_th, th.woo_note_th, td.woo_note_value_th, td.woo_image_id.td.img-padding.copify-td, .woocommerce_table_style tbody .order_item:first-child td' ).css( 'border-top-width', value+'px' );
	jQuery("#customizer_email_preview").contents().find('.order_item td:first-child, .order_item td:last-child, .order_item td' ).css( 'border-bottom-width', value+'px' );
	
});
jQuery(document).on("change", ".order_details_border .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#order_details_border').val(value);
	jQuery('.zoremmail-menu #order_details_border').trigger('change');
});
jQuery(document).on("change", "#order_details_border_radius", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table.woocommerce_table_style' ).css( 'border-radius', value+'px' );
});
jQuery(document).on("change", ".order_details_border_radius .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#order_details_border_radius').val(value);
	jQuery('.zoremmail-menu #order_details_border_radius').trigger('change');
});
//subscription order_details_border 
jQuery(document).on("change", "#subscription_order_details_border", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('.subscription_id_th' ).css( 'border-width', value+'px' );
});
jQuery(document).on("change", ".subscription_order_details_border .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#subscription_order_details_border').val(value);
	jQuery('.zoremmail-menu #subscription_order_details_border').trigger('change');
});
jQuery(document).on("change", "#subscription_order_details_border_radius", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table.subscription-table' ).css( 'border-radius', value+'px' );
});
jQuery(document).on("change", ".subscription_order_details_border_radius .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#subscription_order_details_border_radius').val(value);
	jQuery('.zoremmail-menu #subscription_order_details_border_radius').trigger('change');
});
jQuery(document).on("change", "#address_border", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('address.address, table.addresses' ).css( 'border-width', value+'px' );
});
jQuery(document).on("change", ".address_border .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#address_border').val(value);
	jQuery('.zoremmail-menu #address_border').trigger('change');
});
jQuery(document).on("change", "#address_border_radius", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('address.address, table.addresses' ).css( 'border-radius', value+'px' );
});
jQuery(document).on("change", ".address_border_radius .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#address_border_radius').val(value);
	jQuery('.zoremmail-menu #address_border_radius').trigger('change');
});
jQuery(document).on("change", "#heading_padding_top", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#header_wrapper' ).css( 'padding-top', value+'px' );
});
jQuery(document).on("change", ".heading_padding_top .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#heading_padding_top').val(value);
	jQuery('.zoremmail-menu #heading_padding_top').trigger('change');
});
jQuery(document).on("change", "#heading_padding_bottom", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#header_wrapper' ).css( 'padding-bottom', value+'px' );
});
jQuery(document).on("change", ".heading_padding_bottom .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#heading_padding_bottom').val(value);
	jQuery('.zoremmail-menu #heading_padding_bottom').trigger('change');
});
jQuery(document).on("change", "#heading_padding_left_right", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#header_wrapper' ).css( 'padding-left', value+'px' );
	jQuery("#customizer_email_preview").contents().find('#header_wrapper' ).css( 'padding-right', value+'px' );
});
jQuery(document).on("change", ".heading_padding_left_right .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#heading_padding_left_right').val(value);
	jQuery('.zoremmail-menu #heading_padding_left_right').trigger('change');
});
//order summary table padding
jQuery(document).on("change", "#table_padding_top", function(){
	var value = jQuery( this ).val();
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table.woocommerce_table_style').css( 'padding-top', value+'px');

	} else if ( 'woocommerce' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table.woocommerce_table_style').css( 'margin-top', value+'px');
	}
	setting_change_trigger();
});
jQuery(document).on("change", ".table_padding_top .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_padding_top').val(value);
	jQuery('.zoremmail-menu #table_padding_top').trigger('change');
});
jQuery(document).on("change", "#table_padding_bottom", function(){
	var value = jQuery( this ).val();
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table.woocommerce_table_style').css( 'padding-bottom', value+'px');

	} else if ( 'woocommerce' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table.woocommerce_table_style').css( 'margin-bottom', value+'px');
	}
	setting_change_trigger();
});
jQuery(document).on("change", ".table_padding_bottom .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_padding_bottom').val(value);
	jQuery('.zoremmail-menu #table_padding_bottom').trigger('change');
});
//subscription order table
//order summary table padding
jQuery(document).on("change", "#subscription_table_padding_top", function(){
	var value = jQuery( this ).val();
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table.subscription-table').css( 'padding-top', value+'px');

	} else if ( 'woocommerce' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('div.subscription-table-background-color').css( 'margin-top', value+'px');
	}
	setting_change_trigger();
});
jQuery(document).on("change", ".subscription_table_padding_top .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#subscription_table_padding_top').val(value);
	jQuery('.zoremmail-menu #subscription_table_padding_top').trigger('change');
});
jQuery(document).on("change", "#subscription_table_padding_bottom", function(){
	var value = jQuery( this ).val();
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('table.subscription-table').css( 'padding-bottom', value+'px');

	} else if ( 'woocommerce' == select_email_template ) {
		jQuery("#customizer_email_preview").contents().find('div.subscription-table-background-color').css( 'margin-bottom', value+'px');
	}
	setting_change_trigger();
});
jQuery(document).on("change", ".subscription_table_padding_bottom .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#subscription_table_padding_bottom').val(value);
	jQuery('.zoremmail-menu #subscription_table_padding_bottom').trigger('change');
});
jQuery(document).on("change", "#address_table_padding_top", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table#addresses').css( 'padding-top', value+'px');
});
jQuery(document).on("change", ".address_table_padding_top .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#address_table_padding_top').val(value);
	jQuery('.zoremmail-menu #address_table_padding_top').trigger('change');
});
jQuery(document).on("change", "#address_table_padding_bottom", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table#addresses' ).css( 'padding-bottom', value+'px' );
});
jQuery(document).on("change", ".address_table_padding_bottom .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#address_table_padding_bottom').val(value);
	jQuery('.zoremmail-menu #address_table_padding_bottom').trigger('change');
});
jQuery(document).on("change", "#address_order_details_border", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table#addresses address.address' ).css( 'border-width', value+'px' );
});
jQuery(document).on("change", ".address_order_details_border .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#address_order_details_border').val(value);
	jQuery('.zoremmail-menu #address_order_details_border').trigger('change');
});
//order summary table padding left/right
jQuery(document).on("change", "#table_left_right_padding", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table.woocommerce-table-td thead tr th.td, table.woocommerce-table-td  tbody tr td.td, div#body_content_inner > div table > tbody > tr.order_item > td.td, div#body_content_inner > div table.td > thead > tr > th.td	, div#body_content_inner > div table.td > tbody > tr > td.td, th.td.tlabel-subtotal, td.td.tvalue-subtotal, td.td.tvalue-total, th.td.tlabel-shipping, th.td.tlabel-tax, th.td.tlabel-paymentmethod,td.td.tvalue-shipping, td.td.tvalue-tax, td.td.tvalue-paymentmethod, th.td.tlabel-total, td.td.tvalue-vat, td.td.tlabel-vat, td.td.note-add, table.woocommerce-table-td tfoot tr td.td.note-two, th#note_border_bottom, td#note_border_bottom, td.td.copify-td, th.td.copify-td').css( 'padding-left', value+'px');
	jQuery("#customizer_email_preview").contents().find('div#body_content_inner > div table.td > tfoot > tr > th.td, td.td.note-add, td.td.note-two, th.td.copify-td, td.td.copify-td').css( 'padding-left', value+'px');
	jQuery("#customizer_email_preview").contents().find('div#body_content_inner > div table.td > tfoot > tr > th.td, td.td.note-add, td.td.note-two, th.td.copify-td, th.td.copify-td, td.td.copify-td').css( 'padding-right', value+'px' );
	jQuery("#customizer_email_preview").contents().find('div#body_content_inner > div table.td > tfoot > tr > td.td').css( 'padding-left', value+'px' );
	jQuery("#customizer_email_preview").contents().find('div#body_content_inner > div table.td > tfoot > tr > td.td').css( 'padding-right', value+'px' );
});

jQuery(document).on("change", ".table_left_right_padding .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_left_right_padding').val(value);
	jQuery('.zoremmail-menu #table_left_right_padding').trigger('change');
});

//subscription order table left-right-padding
//order summary table padding left/right
jQuery(document).on("change", "#subscription_table_left_right_padding", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table.subscription-table tr th, table.subscription-table tr td ').css( 'padding-left', value+'px');
	jQuery("#customizer_email_preview").contents().find('table.subscription-table tr th, table.subscription-table tr td').css( 'padding-right', value+'px' );
});

jQuery(document).on("change", ".subscription_table_left_right_padding .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#subscription_table_left_right_padding').val(value);
	jQuery('.zoremmail-menu #subscription_table_left_right_padding').trigger('change');
});
//order summary address Alignment
jQuery(document).on("change", "#address_alignment", function(){

	var value = jQuery( this ).val();
	//.log(value);
	setting_change_trigger();
	if( value == 'left' ) {
		jQuery("#customizer_email_preview").contents().find( 'table#addresses h2 , address.address, table.ast_shipping_addresses h2, table.ast_billing_addresses h2' ).css( 'text-align', 'left' );
	} else if ( value == 'right' ) {					
		jQuery("#customizer_email_preview").contents().find( 'table#addresses h2 , address.address, table.ast_shipping_addresses h2, table.ast_billing_addresses h2' ).css( 'text-align', 'right' );
	} else if ( value == 'center' ) {					
		jQuery("#customizer_email_preview").contents().find( 'table#addresses h2 , address.address, table.ast_shipping_addresses h2, table.ast_billing_addresses h2' ).css( 'text-align', 'center' );
	}
});
jQuery(document).on("change", "#footer_padding_top", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#template_footer #credit' ).css( 'padding-top', value+'px' );
});

jQuery(document).on("change", ".footer_padding_top .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#footer_padding_top').val(value);
	jQuery('.zoremmail-menu #footer_padding_top').trigger('change');
});
//new font-size end

jQuery(document).on("change", "#button_and_link, #customer_button_and_link, #customer_renewal_invoice_button_and_link, #customer_invoice_paid_button_and_link,#customer_renewal_invoice_button_and_link", function(){
	setting_change_trigger();
});

jQuery(document).on("change", "#email_button", function(){
	var value = jQuery( this ).val();
	var value_button_bg_color = jQuery( '#button_bg_color' ).val();
	setting_change_trigger();
	if( value == 'outline' ){
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'background-color', value_button_bg_color );
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'border', '1px solid #e0e0e0' );
		jQuery(".button_border_color").removeClass('button_border_color_hide');
	} else if ( value == 'full' ){					
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'background-color', value_button_bg_color );
		jQuery(".button_border_color").addClass('button_border_color_hide');
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'border', 'none' );
	}
});

jQuery(document).on("change", "#button_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'width', value+'px' );
});

jQuery(document).on("change", ".button_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#button_size').val(value);
	jQuery('.zoremmail-menu #button_size').trigger('change');
	
});

// fluid_button_size
jQuery(document).on("change", ".radio-button-label .fluid_button_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if ( value == 15 ) {
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'padding', '12px 0' );
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'width', '160px' );
		jQuery("#customizer_email_preview").contents().find( '.invoice_btn' ).css( 'width', '120px' );
	} else {
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'padding', '12px 0' );
		jQuery("#customizer_email_preview").contents().find( 'p.customer_account_btn' ).css( 'width', '200px' );
		jQuery("#customizer_email_preview").contents().find( '.invoice_btn' ).css( 'width', '160px' );
	}
});

jQuery(document).on("click", ".radio-button-label .footer_width", function(){
	var container_width = jQuery( '#container_width' ).val();
	var value = jQuery( this ).val();
	
	if ( value == 1 ) {
		jQuery("#customizer_email_preview").contents().find( '.footer_container' ).css( 'width', '100%' );
	} else {
		jQuery("#customizer_email_preview").contents().find( '.footer_container' ).css( 'width', container_width+'px' );
	}
});
jQuery(document).on("click", ".radio-button-label .header_width", function(){
	
	var container_width = jQuery( '#container_width' ).val();
	var value = jQuery( this ).val();
	// console.log(container_width);
	if ( value == 1 ) {
		jQuery("#customizer_email_preview").contents().find( '.header_container' ).css( 'width', '100%' );
	} else {
		jQuery("#customizer_email_preview").contents().find( '.header_container' ).css( 'width', container_width+'px' );
	}
	
});
//end fluid_button_size

jQuery(document).on("change", "#button_border_Width", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('p.customer_account_btn, p.customer_account_btn' ).css( 'border-width', value+'px' );
});

jQuery(document).on("change", ".button_border_Width .slider__value", function(){
	var value = jQuery( this ).val();
	
	setting_change_trigger();
	jQuery('#button_border_Width').val(value);
	jQuery('.zoremmail-menu #button_border_Width').trigger('change');
	
});

jQuery(document).on("change", "#button_border_radius", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('p.customer_account_btn' ).css( 'border-radius', value+'px' );
});

jQuery(document).on("change", ".button_border_radius .slider__value", function(){
	var value = jQuery( this ).val();
	
	setting_change_trigger();
	jQuery('#button_border_radius').val(value);
	jQuery('.zoremmail-menu #button_border_radius').trigger('change');
	
});

jQuery(document).on("change", "#footer_border_bottom", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == 'solid' ){
		jQuery("#customizer_email_preview").contents().find( 'table#template_footer' ).css( 'border-top-style', 'solid' );
	} else if ( value == 'dotted' ){					
		jQuery("#customizer_email_preview").contents().find( 'table#template_footer' ).css( 'border-top-style', 'dotted' );
	} else if ( value == 'double' ){					
		jQuery("#customizer_email_preview").contents().find( 'table#template_footer' ).css( 'border-top-style', 'double' );
	} else if ( value == 'groove' ){					
		jQuery("#customizer_email_preview").contents().find( 'table#template_footer' ).css( 'border-top-style', 'groove' );
	} else if ( value == 'dashed' ){					
		jQuery("#customizer_email_preview").contents().find( 'table#template_footer' ).css( 'border-top-style', 'dashed' );
	} else if ( value == 'ridge' ){					
		jQuery("#customizer_email_preview").contents().find( 'table#template_footer' ).css( 'border-top-style', 'ridge' );
	}

});

jQuery(document).on("change", "#footer_border_bottom_width", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table#template_footer' ).css( 'border-top-width', value+'px' );
});

jQuery(document).on("change", ".footer_border_bottom_width .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#footer_border_bottom_width').val(value);
	jQuery('.zoremmail-menu #footer_border_bottom_width').trigger('change');
});

jQuery(document).on("change", "#border_radius", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('body #template_container' ).css( 'border-radius', value+'px' );
});

jQuery(document).on("change", ".border_radius .slider__value", function(){
	var value = jQuery( this ).val();
	
	setting_change_trigger();
	jQuery('#border_radius').val(value);
	jQuery('.zoremmail-menu #border_radius').trigger('change');
	
});
//header Show Order Number(Link) yes and no
jQuery(document).on("change", "#show_order_number", function(){

	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == 'no' ){
		jQuery("#customizer_email_preview").contents().find( 'td#header_wrapper h1 a' ).attr('href','')
	} else if ( value == 'yes' ){					
		jQuery("#customizer_email_preview").contents().find( 'td#header_wrapper h1 a' ).css('text-decoration', 'underline');
		jQuery("#customizer_email_preview").contents().find( 'td#header_wrapper h1 a' ).css('cursor', 'pointer');
	}
	setting_change_trigger();
});


jQuery(document).on("change", "#link_show", function(){
	this.value = this.checked ? 1 : 0;
	var value = this.value;
	if ( 1 == value ) {
		jQuery("#customizer_email_preview").contents().find( 'td#credit div' ).css( 'display', 'block' );
		jQuery( 'div.footer_layout' ).css( 'display', 'block' );
		jQuery( 'div.footer_links' ).css( 'display', 'block' );
		jQuery( 'div.footer_text_add, div.footer_text_link_add, div.footer_second_text_add, div.footer_second_text_link_add, div.footer_three_text_add, div.footer_three_text_link_add' ).css( 'display', 'block' );
		jQuery('.zoremmail-menu.hide_show').removeClass(' layout_box_hide');
		
	} else {
		jQuery("#customizer_email_preview").contents().find( 'td#credit div' ).css( 'display', 'none' );
		jQuery( 'div.footer_layout' ).css( 'display', 'none' );
		jQuery( 'div.footer_text_add, div.footer_text_link_add, div.footer_second_text_add, div.footer_second_text_link_add, div.footer_three_text_add, div.footer_three_text_link_add' ).css( 'display', 'none' );
		jQuery( 'div.footer_links' ).css( 'display', 'none' );
		jQuery('.zoremmail-menu.hide_show').addClass('layout_box_hide');

	}
	setting_change_trigger();
});
jQuery( ".footer_text_add, .footer_text_link_add , .footer_second_text_add , .footer_second_text_link_add, .footer_three_text_add, .footer_three_text_link_add" ).keyup( function( event ) {
	setting_change_trigger();
}); 

jQuery( ".footer_text_edit" ).keyup( function( event ) {
	setting_change_trigger();
	var str = event.target.value;
	if( str ) {				
		jQuery("#customizer_email_preview").contents().find( 'td#credit p' ).text(str);
	} else {
		jQuery("#customizer_email_preview").contents().find( 'td#credit p' ).text(event.target.placeholder);			
	}
	setting_change_trigger();
}); 

jQuery(document).on("change", "#footer_layout", function(){
	var value = jQuery( this ).val();
	if( value == 'center' ){
		jQuery("#customizer_email_preview").contents().find( 'div#footersocial ul, table#template_footer p, div.footer_links' ).css( 'text-align', 'center');
	} else if ( value == 'left' ) {
		jQuery("#customizer_email_preview").contents().find( 'div#footersocial ul, table#template_footer p, div.footer_links' ).css( 'text-align', 'left');
		jQuery("#customizer_email_preview").contents().find( 'div#footersocial ul, table#template_footer p, div.footer_links' ).css( 'margin-left', '48px');
		jQuery("#customizer_email_preview").contents().find( 'div#footersocial ul, table#template_footer p, div.footer_links span:first-child a' ).css( 'padding-left', '0px');
	} else if ( value == 'right' ) {
		jQuery("#customizer_email_preview").contents().find( 'div#footersocial ul, table#template_footer p, div.footer_links' ).css( 'text-align', 'right');
		jQuery("#customizer_email_preview").contents().find( 'div#footersocial ul, table#template_footer p, div.footer_links' ).css( 'margin-right', '48px');
		jQuery("#customizer_email_preview").contents().find( 'div#footersocial ul, table#template_footer p, div.footer_links span:last-child a' ).css( 'padding-right', '0px');
	}
	setting_change_trigger();
});


jQuery( ".zoremmail-layout-content-media .dashicons" ).on( "click", function() {
	jQuery(this).parent().siblings().removeClass('active');
	var width = jQuery(this).parent().data('width');
	var iframeWidth = jQuery(this).parent().data('iframe-width');
	jQuery('#template_container, #template_body').css('width', width);
	jQuery( ".zoremmail-layout-content-media .dashicons" ).css('color', '#bdbdbd');
	jQuery( ".zoremmail-layout-content-media .dashicons" ).css('border-bottom-color', '');
	jQuery(this).parent().addClass('active');
	jQuery(this).css('color', '#1d2327');
	jQuery(this).css('border-bottom-color', '#1d2327');
	jQuery("#customizer_email_preview").css('width', iframeWidth);
	//jQuery("#customizer_email_preview").contents().find('#template_container, #template_body, #template_footer').css('width', width);
});

jQuery(document).on("click", ".footer_add_title", function () {
	// var spinner = jQuery('#trackship_mapping_form').find(".add-custom-mapping.spinner").addClass("active");
	// jQuery('.footer_title_url_table').css('display', 'block');
	var ajax_data = {
		action: 'add_footer_row',
	};
	jQuery.ajax({
		url: ajaxurl,
		data: ajax_data,
		type: 'POST',
		dataType:"json",
		success: function (response) {

			jQuery('.footer_title_url_table tr:last').after(response.table_title_url_row);
			jQuery( "input.footer_new_title, input.footer_new_url" ).keyup( function( event ) {
				setting_change_trigger();
			}); 
		},
		error: function (response, jqXHR, exception) {
			console.log(response);
			//spinner.removeClass("active");
		}
	});
	return false;
});

jQuery(document).on("click", ".remove_custom_footer_title_and_url_row", function () {
	jQuery(this).closest('tr:last-child');
	// console.log(abc);
	jQuery(this).closest('tr').remove();
	setting_change_trigger();
});

jQuery( "input.footer_new_title, input.footer_new_url" ).keyup( function( event ) {
	setting_change_trigger();
});

jQuery(document).on("change", "#table_heading_font_family", function(){

	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		var trackship_and_woocommerce_content = '.woo_product_th, .woo_product_th a, .woo_price_th, .subscription_id_th, .subscription_id_th a, #addresses h2, h2';
	} else if ( 'woocommerce' == select_email_template ) {
		var trackship_and_woocommerce_content = 'h2';
	}

	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' );
	} else if ( value == 'Arial, Helvetica, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', 'Arial, Helvetica, sans-serif' );
	} else if ( value == '"Arial Black", Gadget, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Arial Black", Gadget, sans-serif' );
	} else if ( value == '"Courier New", Courier, monospace' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Courier New", Courier, monospace' );
	} else if ( value == 'Impact, Charcoal, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', 'Impact, Charcoal, sans-serif' );
	} else if ( value == '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Lucida Sans Unicode", "Lucida Grande", sans-serif' );
	} else if ( value == '"Palatino Linotype", "Book Antiqua", Palatino, serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Palatino Linotype", "Book Antiqua", Palatino, serif' );
	} else if ( value == 'Georgia, serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', 'Georgia, serif' );
	}
});
jQuery(document).on("change", "#table_heading_font_size", function(){
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		var trackship_and_woocommerce_content = '.woo_product_th, .woo_product_th a, .woo_price_th, .subscription_id_th, .subscription_id_th a, #addresses h2, h2';
	} else if ( 'woocommerce' == select_email_template ) {
		var trackship_and_woocommerce_content = 'h2';
	}

	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".table_heading_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_heading_font_size').val(value);
	jQuery('.zoremmail-menu #table_heading_font_size').trigger('change');
});
jQuery(document).on("change", "#table_heading_font_style", function(){
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		var trackship_and_woocommerce_content = '.woo_product_th, .woo_product_th a, .woo_price_th, .subscription_id_th, .subscription_id_th a, #addresses h2, h2';
	} else if ( 'woocommerce' == select_email_template ) {
		var trackship_and_woocommerce_content = 'h2';
	}

	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '100' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '200' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '300' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '400' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '500' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '600' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '700' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '800' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '900' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == 'bold' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	}
});
jQuery(document).on("change", "#table_heading_line_height", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('.woo_product_th, .woo_product_th a, .woo_price_th, .subscription_id_th, .subscription_id_th a, #addresses h2, h2' ).css( 'line-height', value+'px' );
});
jQuery(document).on("change", ".table_heading_line_height .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_heading_line_height').val(value);
	jQuery('.zoremmail-menu #table_heading_line_height').trigger('change');
});

jQuery(document).on("change", "#table_content_font_family", function(){
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		var trackship_and_woocommerce_content = '.woocommerce_table_style tr, .subscription-table tr.woo_label_tr, table#addresses .address p, p.additional_content_h6, p.first_span_text, .subscription-table th.subscription_price_th, .subscription-table td.subscription_price, table.subscription-table td.subscription_end_date, table.subscription-table th.subscription_end_date_th, p, ul li';
	} else if ( 'woocommerce' == select_email_template ) {
		var trackship_and_woocommerce_content = '#body_content_inner .woocommerce_table_style *, #body_content_inner .subscription-table *, .first_span_text, .address p, .last_message, .additional_content_h6, #body_content_inner p, ul li';
	}
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' );
	} else if ( value == 'Arial, Helvetica, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', 'Arial, Helvetica, sans-serif' );
	} else if ( value == '"Arial Black", Gadget, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Arial Black", Gadget, sans-serif' );
	} else if ( value == '"Courier New", Courier, monospace' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Courier New", Courier, monospace' );
	} else if ( value == 'Impact, Charcoal, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', 'Impact, Charcoal, sans-serif' );
	} else if ( value == '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Lucida Sans Unicode", "Lucida Grande", sans-serif' );
	} else if ( value == '"Palatino Linotype", "Book Antiqua", Palatino, serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', '"Palatino Linotype", "Book Antiqua", Palatino, serif' );
	} else if ( value == 'Georgia, serif' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-family', 'Georgia, serif' );
	}
});
jQuery(document).on("change", "#table_content_font_size", function(){
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		var trackship_and_woocommerce_content = '.woocommerce_table_style tr, .subscription-table tr.woo_label_tr, table#addresses .address p, p.additional_content_h6, p.first_span_text, .subscription-table th.subscription_price_th, .subscription-table td.subscription_price, table.subscription-table td.subscription_end_date, table.subscription-table th.subscription_end_date_th, p, ul li';
	} else if ( 'woocommerce' == select_email_template ) {
		var trackship_and_woocommerce_content = '#body_content_inner .woocommerce_table_style *, #body_content_inner .subscription-table *, .first_span_text, .address p, .last_message, .additional_content_h6, p, ul li';
	}
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".table_content_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_content_font_size').val(value);
	jQuery('.zoremmail-menu #table_content_font_size').trigger('change');
});
jQuery(document).on("change", "#table_content_font_style", function(){
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		var trackship_and_woocommerce_content = '.woocommerce_table_style tr td span, .woocommerce_table_style tr.woo_label_tr th, .subscription-table tr.woo_label_tr th, .subscription-table tr.woo_label_tr, table#addresses .address p, a.user_id, p.additional_content_h6, p.first_span_text, .subscription-table th.subscription_price_th, .subscription-table td.subscription_price, table.subscription-table td.subscription_end_date, table.subscription-table th.subscription_end_date_th, p, ul li';
	} else if ( 'woocommerce' == select_email_template ) {
		var trackship_and_woocommerce_content = 'tr td, p';
	}
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '100' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '200' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '300' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '400' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '500' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '600' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '700' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '800' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == '900' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	} else if ( value == 'bold' ){
		jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'font-weight', value );
	}
});
jQuery(document).on("change", "#table_content_line_height", function(){
	var select_email_template = jQuery( '.email_templates_selects .checked_class' ).attr('id');
	if ( 'trackship_SaaS' == select_email_template ) {
		var trackship_and_woocommerce_content = '.woocommerce_table_style tr, .subscription-table tr.woo_label_tr, table#addresses .address p, p.additional_content_h6, p.first_span_text, .subscription-table th.subscription_price_th, .subscription-table td.subscription_price, table.subscription-table td.subscription_end_date, table.subscription-table th.subscription_end_date_th';
	} else if ( 'woocommerce' == select_email_template ) {
		var trackship_and_woocommerce_content = 'tr th, tr td, p';
	}
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find( trackship_and_woocommerce_content ).css( 'line-height', value+'px' );
});
jQuery(document).on("change", ".table_content_line_height .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_content_line_height').val(value);
	jQuery('.zoremmail-menu #table_content_line_height').trigger('change');
});
jQuery(document).on("change", "#footer_content_font_family", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' );
	} else if ( value == 'Arial, Helvetica, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', 'Arial, Helvetica, sans-serif' );
	} else if ( value == '"Arial Black", Gadget, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', '"Arial Black", Gadget, sans-serif' );
	} else if ( value == '"Courier New", Courier, monospace' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', '"Courier New", Courier, monospace' );
	} else if ( value == 'Impact, Charcoal, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', 'Impact, Charcoal, sans-serif' );
	} else if ( value == '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', '"Lucida Sans Unicode", "Lucida Grande", sans-serif' );
	} else if ( value == '"Palatino Linotype", "Book Antiqua", Palatino, serif' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', '"Palatino Linotype", "Book Antiqua", Palatino, serif' );
	} else if ( value == 'Georgia, serif' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-family', 'Georgia, serif' );
	}
});
jQuery(document).on("change", "#footer_content_font_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".footer_content_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#footer_content_font_size').val(value);
	jQuery('.zoremmail-menu #footer_content_font_size').trigger('change');
});
jQuery(document).on("change", "#footer_content_font_style", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '100' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '200' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '300' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '400' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '500' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '600' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '700' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '800' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == '900' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	} else if ( value == 'bold' ){
		jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'font-weight', value );
	}
});
jQuery(document).on("change", "#footer_content_line_height", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('#template_footer p' ).css( 'line-height', value+'px' );
});
jQuery(document).on("change", ".footer_content_line_height .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#footer_content_line_height').val(value);
	jQuery('.zoremmail-menu #footer_content_line_height').trigger('change');
});
jQuery(document).on("change", ".table_content_line_height .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#table_content_line_height').val(value);
	jQuery('.zoremmail-menu #table_content_line_height').trigger('change');
});
jQuery(document).on("change", "#header_font_family", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' );
	} else if ( value == 'Arial, Helvetica, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', 'Arial, Helvetica, sans-serif' );
	} else if ( value == '"Arial Black", Gadget, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', '"Arial Black", Gadget, sans-serif' );
	} else if ( value == '"Courier New", Courier, monospace' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', '"Courier New", Courier, monospace' );
	} else if ( value == 'Impact, Charcoal, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', 'Impact, Charcoal, sans-serif' );
	} else if ( value == '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', '"Lucida Sans Unicode", "Lucida Grande", sans-serif' );
	} else if ( value == '"Palatino Linotype", "Book Antiqua", Palatino, serif' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', '"Palatino Linotype", "Book Antiqua", Palatino, serif' );
	} else if ( value == 'Georgia, serif' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-family', 'Georgia, serif' );
	}
});
jQuery(document).on("change", "#header_font_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".header_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#header_font_size').val(value);
	jQuery('.zoremmail-menu #header_font_size').trigger('change');
});
jQuery(document).on("change", "#header_font_style", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '100' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '200' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '300' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '400' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '500' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '600' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '700' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '800' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == '900' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	} else if ( value == 'bold' ){
		jQuery("#customizer_email_preview").contents().find('h1' ).css( 'font-weight', value );
	}
});
jQuery(document).on("change", "#header_line_height", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('h1, .user_id' ).css( 'line-height', value+'px' );
});
jQuery(document).on("change", ".header_line_height .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#header_line_height').val(value);
	jQuery('.zoremmail-menu #header_line_height').trigger('change');
});
jQuery(document).on("change", "#address_table_font_family", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', '"Helvetica Neue", Helvetica, Roboto, Arial, sans-serif' );
	} else if ( value == 'Arial, Helvetica, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', 'Arial, Helvetica, sans-serif' );
	} else if ( value == '"Arial Black", Gadget, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', '"Arial Black", Gadget, sans-serif' );
	} else if ( value == '"Courier New", Courier, monospace' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', '"Courier New", Courier, monospace' );
	} else if ( value == 'Impact, Charcoal, sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', 'Impact, Charcoal, sans-serif' );
	} else if ( value == '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', '"Lucida Sans Unicode", "Lucida Grande", sans-serif' );
	} else if ( value == '"Palatino Linotype", "Book Antiqua", Palatino, serif' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', '"Palatino Linotype", "Book Antiqua", Palatino, serif' );
	} else if ( value == 'Georgia, serif' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-family', 'Georgia, serif' );
	}
});
jQuery(document).on("change", "#address_table_font_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table#addresses .address p' ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".address_table_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#address_table_font_size').val(value);
	jQuery('.zoremmail-menu #address_table_font_size').trigger('change');
});
jQuery(document).on("change", "#address_table_font_style", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	if( value == '100' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '200' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '300' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '400' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '500' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '600' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '700' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '800' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == '900' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	} else if ( value == 'bold' ){
		jQuery("#customizer_email_preview").contents().find('table#addresses .address p, a.address_email' ).css( 'font-weight', value );
	}
});
jQuery(document).on("change", "#address_table_line_height", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('table#addresses .address p' ).css( 'line-height', value+'px' );
});
jQuery(document).on("change", ".address_table_line_height .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#address_table_line_height').val(value);
	jQuery('.zoremmail-menu #address_table_line_height').trigger('change');
});
jQuery(document).on("change", "#variation_product_font_size", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery("#customizer_email_preview").contents().find('span.variation_product' ).css( 'font-size', value+'px' );
});
jQuery(document).on("change", ".variation_product_font_size .slider__value", function(){
	var value = jQuery( this ).val();
	setting_change_trigger();
	jQuery('#variation_product_font_size').val(value);
	jQuery('.zoremmail-menu #variation_product_font_size').trigger('change');
});

