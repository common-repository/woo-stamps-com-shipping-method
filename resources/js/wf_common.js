jQuery(document).ready(function(){
	
	// Toggle Packing Methods
	wf_load_packing_method_options();
	jQuery('.packing_method').change(function(){
		wf_load_packing_method_options();
	});
	
	// Advance settings tab
	jQuery('.wf_settings_heading_tab').next('table').hide();
	jQuery('.wf_settings_heading_tab').click(function(){
		jQuery(this).next('table').toggle();
	});
                //hiding the paper size pdf field when paper label not pdfs
	jQuery('#woocommerce_wf_usps_stamps_printLabelType').change(function(){
		var value=document.getElementById('woocommerce_wf_usps_stamps_printLabelType').value;
		if(value=='Pdf')
		{
            jQuery('#woocommerce_wf_usps_stamps_paperSize').closest('tr').show();
		}	
		else
		{
            jQuery('#woocommerce_wf_usps_stamps_paperSize').closest('tr').hide();
		}
	});
	
	jQuery('#woocommerce_wf_usps_stamps_availability').change(function(){
		var value=document.getElementById('woocommerce_wf_usps_stamps_availability').value;
		if(value=='specific')
		{
            jQuery('#woocommerce_wf_usps_stamps_countries').closest('tr').show();
		}	
		else
		{
            jQuery('#woocommerce_wf_usps_stamps_countries').closest('tr').hide();
		}
	});
});

function wf_load_packing_method_options(){
	pack_method	=	jQuery('.packing_method').val();
	jQuery('#packing_options').hide();
	jQuery('.weight_based_option').closest('tr').hide();
        jQuery('#woocommerce_wf_usps_stamps_packing_algorithm').closest('tr').hide();
	switch(pack_method){
		
		case 'box_packing':
			jQuery('#packing_options').show();
                        jQuery('#woocommerce_wf_usps_stamps_packing_algorithm').closest('tr').show();
			break;
			
		case 'weight_based':
			jQuery('.weight_based_option').closest('tr').show();
			break;
			
		case 'per_item':
		
		default:
			break;
			
		
	}
}