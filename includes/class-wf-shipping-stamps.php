<?php

/**
 * WF_USPS_Stamps class.
 *
 * @extends WC_Shipping_Method
 */
class WF_USPS_Stamps extends WC_Shipping_Method {
	private $domestic		= array( "US", "PR", "VI" );
	private $found_rates;
	public $mode='volume_based';
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id				 = WF_USPS_STAMPS_ID;
		$this->method_title	   = __( 'Stamps.com - USPS', 'wf-usps-stamps-woocommerce' );
		$this->method_description = __( 'The <strong>Stamps.com USPS</strong> plugin obtains rates dynamically from the Stamps.com API during cart/checkout.', 'wf-usps-stamps-woocommerce' );
		$this->services		   = include( 'data-wf-services.php' );
		//$this->flat_rate_boxes	= include( 'data-wf-flat-rate-boxes.php' );
		//$this->flat_rate_pricing  = include( 'data-wf-flat-rate-box-pricing.php' );
		$this->init();
	}

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	private function init() {
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->mode						= isset( $this->settings['packing_algorithm'] ) ? $this->settings['packing_algorithm'] : 'volume_based';
		$this->enabled					= isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : $this->enabled;
		$this->stamps_admin_enabled		= isset( $this->settings['stamps_admin_enabled'] ) ? $this->settings['stamps_admin_enabled'] : 'yes';
		$this->title					= isset( $this->settings['title'] ) ? $this->settings['title'] : $this->method_title;
		$this->availability				= isset( $this->settings['availability'] ) ? $this->settings['availability'] : 'all';
		$this->countries				= isset( $this->settings['countries'] ) ? $this->settings['countries'] : array();
		$this->origin					= isset( $this->settings['origin'] ) ? $this->settings['origin'] : '';
		// WF Shipping Label: New fields - START
		$this->disbleShipmentTracking	= isset( $this->settings['disbleShipmentTracking'] ) ? $this->settings['disbleShipmentTracking'] : 'TrueForCustomer';
		$this->fillShipmentTracking		= isset( $this->settings['fillShipmentTracking'] ) ? $this->settings['fillShipmentTracking'] : 'Manual';
		$this->disblePrintLabel			= isset( $this->settings['disblePrintLabel'] ) ? $this->settings['disblePrintLabel'] : '';
		$this->stamps_hidden_postage	= isset( $this->settings['stamps_hidden_postage'] ) ? $this->settings['stamps_hidden_postage'] : 'no';
		$this->stamps_insure_contents	= isset( $this->settings['stamps_insure_contents'] ) ? $this->settings['stamps_insure_contents'] : 'no';
		$this->defaultPrintService		= isset( $this->settings['defaultPrintService'] ) ? $this->settings['defaultPrintService'] : 'None';
		$this->printLabelSize			= isset( $this->settings['printLabelSize'] ) ? $this->settings['printLabelSize'] : 'Default';
		$this->printLabelType			= isset( $this->settings['printLabelType'] ) ? $this->settings['printLabelType'] : 'Pdf';
		$this->paperSize				= isset( $this->settings['paperSize'] ) ? $this->settings['paperSize'] : 'Default';
		$this->printLayout				= isset( $this->settings['printLayout'] ) ? $this->settings['printLayout'] : 'Default';
		$this->senderName				= isset( $this->settings['senderName'] ) ? $this->settings['senderName'] : '';
		$this->senderCompanyName		= isset( $this->settings['senderCompanyName'] ) ? $this->settings['senderCompanyName'] : '';
		$this->senderAddressLine1		= isset( $this->settings['senderAddressLine1'] ) ? $this->settings['senderAddressLine1'] : '';
		$this->senderAddressLine2		= isset( $this->settings['senderAddressLine2'] ) ? $this->settings['senderAddressLine2'] : '';
		$this->senderCity				= isset( $this->settings['senderCity'] ) ? $this->settings['senderCity'] : '';
		$this->senderState				= isset( $this->settings['senderState'] ) ? $this->settings['senderState'] : '';
		$this->senderEmail				= isset( $this->settings['senderEmail'] ) ? $this->settings['senderEmail'] : '';
		$this->senderPhone				= isset( $this->settings['senderPhone'] ) ? $this->settings['senderPhone'] : '';
		// WF Shipping Label: New fields - END.
		$this->user_id					= isset( $this->settings['user_id'] ) ? $this->settings['user_id'] : '';
		$this->password					= isset( $this->settings['password'] ) ? $this->settings['password'] : '';
		$this->access_key				= WF_USPS_STAMPS_ACCESS_KEY;
		$this->packing_method			= 'per_item';
		$this->boxes					= isset( $this->settings['boxes'] ) ? $this->settings['boxes'] : array();
		$this->custom_services			= isset( $this->settings['services'] ) ? $this->settings['services'] : array();
		$this->offer_rates				= isset( $this->settings['offer_rates'] ) ? $this->settings['offer_rates'] : 'all';
		$this->fallback					= !empty( $this->settings['fallback'] ) ? $this->settings['fallback'] : '';
		//$this->flat_rate_fee			= ! empty( $this->settings['flat_rate_fee'] ) ? $this->settings['flat_rate_fee'] : '';
		$this->mediamail_restriction	= isset( $this->settings['mediamail_restriction'] ) ? $this->settings['mediamail_restriction'] : array();
		$this->mediamail_restriction	= array_filter( (array) $this->mediamail_restriction );
		$this->unpacked_item_handling	= ! empty( $this->settings['unpacked_item_handling'] ) ? $this->settings['unpacked_item_handling'] : '';
		//$this->enable_standard_services = isset( $this->settings['enable_standard_services'] ) && $this->settings['enable_standard_services'] == 'yes' ? true : false;
		$this->enable_standard_services = true;
		//$this->enable_flat_rate_boxes   = isset( $this->settings['enable_flat_rate_boxes'] ) ? $this->settings['enable_flat_rate_boxes'] : 'yes';
		$this->debug					= isset( $this->settings['debug_mode'] ) && $this->settings['debug_mode'] == 'yes' ? true : false;
		$this->api_mode					= isset( $this->settings['api_mode'] ) ? $this->settings['api_mode'] : 'Live';
		//$this->flat_rate_boxes		  = apply_filters( 'usps_flat_rate_boxes', $this->flat_rate_boxes );
		
		$this->wc_box_unit				=	(isset ( $this->settings['wc_box_unit'] ) && $this->settings['wc_box_unit']=='yes') ? true : false;
		
		$this->box_max_weight			=	isset($this->settings['box_max_weight']) ? $this->settings['box_max_weight'] : 10;
		$this->weight_packing_process	=	isset($this->settings['weight_packing_process']) ? $this->settings['weight_packing_process'] : 'pack_descending';
		$this->weight_unit				=	$this->wc_box_unit ? strtolower(get_option('woocommerce_weight_unit')) : 'lbs'; //'lbs'; // change this according
		$this->dimension_unit			=	$this->wc_box_unit ? strtolower(get_option('woocommerce_dimension_unit')) : 'in';
		
		//Time zone adjustment, which was configured in minutes to avoid time diff with server. Convert that in seconds to apply in date() functions.
		$this->timezone_offset			= !empty($this->settings['timezone_offset']) ? intval($this->settings['timezone_offset']) * 60 : 0;
		
		$this->itn						= !empty($this->settings['itn']) ? $this->settings['itn'] : '';
		
		$this->default_boxes			= include( 'data-wf-box-sizes.php' );
		$this->prioritize_flate_rate	= isset( $this->settings['prioritize_flate_rate'] ) && $this->settings['prioritize_flate_rate'] == 'yes' ? true : false;
		
                
                
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'clear_transients' ) );
	}

	/**
	 * environment_check function.
	 *
	 * @access public
	 * @return void
	 */
	private function environment_check() {
		global $woocommerce;
		$admin_page = version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ? 'wc-settings' : 'woocommerce_settings';

		if ( get_woocommerce_currency() != "USD" ) {
			echo '<div class="error">
				<p>' . sprintf( __( 'Stamps.com requires that the <a href="%s">currency</a> is set to US Dollars.', 'wf-usps-stamps-woocommerce' ), admin_url( 'admin.php?page=' . $admin_page . '&tab=general' ) ) . '</p>
			</div>';
		}
		elseif ( ! in_array( $woocommerce->countries->get_base_country(), $this->domestic ) ) {
			echo '<div class="error">
				<p>' . sprintf( __( 'Stamps.com requires that the <a href="%s">base country/region</a> is the United States.', 'wf-usps-stamps-woocommerce' ), admin_url( 'admin.php?page=' . $admin_page . '&tab=general' ) ) . '</p>
			</div>';
		}
		elseif ( ! $this->origin && $this->enabled == 'yes' ) {
			echo '<div class="error">
				<p>' . __( 'Stamps.com is enabled, but the origin postcode has not been set.', 'wf-usps-stamps-woocommerce' ) . '</p>
			</div>';
		}
		
		$error_message = '';
		
		// Check for Stamps.com User ID
		if ( ! $this->user_id && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'Stamps.com is enabled, but the Stamps.com User ID has not been set.', 'wf-usps-stamps-woocommerce' ) . '</p>';
		}

		// Check for Stamps.com Password
		if ( ! $this->password && $this->enabled == 'yes' ) {
			$error_message .= '<p>' . __( 'Stamps.com is enabled, but the Stamps.com Password has not been set.', 'wf-usps-stamps-woocommerce' ) . '</p>';
		}
		
		if ( ! $error_message == '' ) {
			echo '<div class="error">';
			echo $error_message;
			echo '</div>';
		}
	}

	/**
	 * admin_options function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_options() {
		// Check users environment supports this method
		$this->environment_check();

		// Show settings
		parent::admin_options();
	}

	/**
	 * generate_services_html function.
	 */
	public function generate_services_html() {
		ob_start();
		include( 'html-wf-services.php' );
		return ob_get_clean();
	}

	/**
	 * generate_box_packing_html function.
	 */
	public function generate_box_packing_html() {
		ob_start();
		include( 'html-wf-box-packing.php' );
		return ob_get_clean();
	}

	/**
	 * validate_box_packing_field function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function validate_box_packing_field( $key ) {
		$boxes = array();

		if ( isset( $_POST['boxes_outer_length'] ) ) {
			$boxes_id			= isset( $_POST['boxes_id'] ) ? $_POST['boxes_id'] : array();
			$boxes_name		 = isset( $_POST['boxes_name'] ) ? $_POST['boxes_name'] : array();
			$boxes_outer_length = $_POST['boxes_outer_length'];
			$boxes_outer_width  = $_POST['boxes_outer_width'];
			$boxes_outer_height = $_POST['boxes_outer_height'];
			$boxes_inner_length = $_POST['boxes_inner_length'];
			$boxes_inner_width  = $_POST['boxes_inner_width'];
			$boxes_inner_height = $_POST['boxes_inner_height'];
			$boxes_box_weight   = $_POST['boxes_box_weight'];
			$boxes_max_weight   = $_POST['boxes_max_weight'];
			$boxes_is_letter	= isset( $_POST['boxes_is_letter'] ) ? $_POST['boxes_is_letter'] : array();
			$boxes_is_enabled	= isset( $_POST['boxes_is_enabled'] ) ? $_POST['boxes_is_enabled'] : array();

			for ( $i = 0; $i < sizeof( $boxes_outer_length ); $i++ ) {

				if ( !empty($boxes_outer_length[ $i ]) && !empty($boxes_outer_width[ $i ]) && !empty($boxes_outer_height[ $i ]) && !empty($boxes_inner_length[ $i ]) && !empty($boxes_inner_width[ $i ]) && !empty($boxes_inner_height[ $i ]) ) {

					$boxes[] = array(
						'id'			=> isset($boxes_id[ $i ]) ? wc_clean( $boxes_id[ $i ] ) : '',
						'name'			=> wc_clean( $boxes_name[ $i ] ),
						'outer_length'	=> floatval( $boxes_outer_length[ $i ] ),
						'outer_width'	=> floatval( $boxes_outer_width[ $i ] ),
						'outer_height'	=> floatval( $boxes_outer_height[ $i ] ),
						'inner_length'	=> floatval( $boxes_inner_length[ $i ] ),
						'inner_width'	=> floatval( $boxes_inner_width[ $i ] ),
						'inner_height'	=> floatval( $boxes_inner_height[ $i ] ),
						'box_weight'	=> floatval( $boxes_box_weight[ $i ] ),
						'max_weight'	=> floatval( $boxes_max_weight[ $i ] ),
						'is_letter'		=> isset( $boxes_is_letter[ $i ] ) ? true : false,
						'is_enabled'	=> isset( $boxes_is_enabled[ $i ] ) ? true : false,
					);
				}
			}
		}

		return $boxes;
	}

	/**
	 * validate_services_field function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function validate_services_field( $key ) {
		$services		 = array();
		$posted_services  = $_POST['usps_service'];

		foreach ( $posted_services as $code => $settings ) {

			$services[ $code ] = array(
				'name'			   => wc_clean( $settings['name'] ),
				'order'			  => wc_clean( $settings['order'] )
			);

			foreach ( $this->services[$code]['package_types'] as $key => $name ) {
				$services[ $code ][ $key ]['enabled'] = isset( $settings[ $key ]['enabled'] ) ? true : false;
				$services[ $code ][ $key ]['adjustment'] = 0;//wc_clean( $settings[ $key ]['adjustment'] );
				$services[ $code ][ $key ]['adjustment_percent'] = 0;//wc_clean( $settings[ $key ]['adjustment_percent'] );
			}

		}

		return $services;
	}

	/**
	 * clear_transients function.
	 *
	 * @access public
	 * @return void
	 */
	public function clear_transients() {
		global $wpdb;

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_usps_quote_%') OR `option_name` LIKE ('_transient_timeout_usps_quote_%')" );
	}

	public function generate_activate_box_html() {
		ob_start();
		$plugin_name = 'stamps';
		include( 'html-wf-market-content.php' );
		return ob_get_clean();
	}

        public function generate_stamps_tabs_html()
        {
            $current_tab = (!empty($_GET['subtab'])) ? esc_attr($_GET['subtab']) : 'general';

                echo '
                <div class="wrap">
                    <script>
                    jQuery(function($){
                    show_selected_tab($(".tab_general"),"general");
                    $(".tab_general").on("click",function(){
                                                return show_selected_tab($(this),"general");
                                        });
                    $(".tab_rates").on("click",function(){
                                                return show_selected_tab($(this),"rates");
                                        });
                    $(".tab_labels").on("click",function(){
                    							$( ".tab_gopremium" ).trigger( "click" );
                                        });
                    $(".tab_packing").on("click",function(){
                    							$( ".tab_gopremium" ).trigger( "click" );
                                        });
                    $(".tab_gopremium").on("click",function(){                                                
                                                return show_selected_tab($(this),"gopremium");
                                        });
                    function show_selected_tab($element,$tab)
                    {
                        $(".nav-tab").removeClass("nav-tab-active");
                        $element.addClass("nav-tab-active");
                        $(".general_tab_field").closest("tr,h3").hide();
                        $(".general_tab_field").next("p").hide();
                                         
                        $(".rates_tab_field").closest("tr,h3").hide();
                        $(".rates_tab_field").next("p").hide();

                        $(".label_tab_field").closest("tr,h3").hide();
                        $(".label_tab_field").next("p").hide();

                        $(".package_tab_field").closest("tr,h3").hide();
                        $(".package_tab_field").next("p").hide();

                        $(".gopremium_tab_field").closest("tr,h3").hide();
                        $(".gopremium_tab_field").next("p").hide();
                        if($tab=="gopremium")
                        {   
                            $(".marketing_content").show();
                        }else{
                            $(".marketing_content").hide();
                        }

                        $("."+$tab+"_tab_field").closest("tr,h3").show();
                        
                        $("."+$tab+"_tab_field").next("p").show();
                        $("#woocommerce_wf_usps_stamps_availability").trigger("change");
                        if($tab=="package")
                        {
                            $("#woocommerce_wf_usps_stamps_packing_method").change();
                        }
                        if($tab=="rates")
                        {
                        	if(document.getElementById("woocommerce_wf_usps_stamps_availability").value=="specific")
							{
					            $("#woocommerce_wf_usps_stamps_countries").closest("tr").show();
							}	
							else
							{
					            $("#woocommerce_wf_usps_stamps_countries").closest("tr").hide();
							}
                        }
                        else
                        {
                        	$("#woocommerce_wf_usps_stamps_countries").closest("tr").hide();
                        }
                        if($tab=="gopremium")
                        {
                            $(".woocommerce-save-button").hide();
                        }else
                        {
                            $(".woocommerce-save-button").show();
                        }
                        return false;
                    }   

                    });
                    </script>
                    <style>
                    .wrap {
                                min-height: 800px;
                            }
                    a.nav-tab{
                                cursor: default;
                    }
                    </style>
                    <hr class="wp-header-end">';
                    $tabs = array(
                        'general' => __("General<span class='wf-super'></span>", 'wf-usps-stamps-woocommerce'),
                        'rates' => __("Rates & Services<span class='wf-super'></span>", 'wf-usps-stamps-woocommerce'),
                        'labels' => __("Label Generation <span class='wf-super'>Premium</span>", 'wf-usps-stamps-woocommerce'),
                        'packing' => __("Packaging <span class='wf-super'>Premium</span>", 'wf-usps-stamps-woocommerce'),
                        'gopremium' => __("Go Premium<span class='wf-super'></span>", 'wf-usps-stamps-woocommerce')
                    );
                    $html = '<h2 class="nav-tab-wrapper">';
                    foreach ($tabs as $stab => $name) {
                        $class = ($stab == $current_tab) ? 'nav-tab-active' : '';
                        $style = ($stab == $current_tab) ? 'border-bottom: 1px solid transparent !important;' : '';
                        $style = ($stab == 'gopremium')? $style.'color:red; !important;':'';
                        $html .= '<a style="text-decoration:none !important;' . $style . '" class="nav-tab ' . $class." tab_".$stab . '" >' . $name . '</a>';
                    }
                    $html .= '</h2>';
                    echo $html;

        }
	/**
	 * init_form_fields function.
	 *
	 * @access public
	 * @return void
	 */
	public function init_form_fields() {
		global $woocommerce;

		$shipping_classes = array();
		$classes = ( $classes = get_terms( 'product_shipping_class', array( 'hide_empty' => '0' ) ) ) ? $classes : array();
                
                if(is_array($classes)){
                    foreach ( $classes as $class ){
                	$shipping_classes[ $class->term_id ] = $class->name;
                    }
                }	
		if ( WF_ADV_DEBUG_MODE == "on" ) { // Test mode is only for development purpose.
			$api_mode_options = array(
				'Live'			=> __( 'Live', 'wf-usps-stamps-woocommerce' ),
				'Test'			=> __( 'Test', 'wf-usps-stamps-woocommerce' ), 
			);
		}
		else {
			$api_mode_options = array(
				'Live'			=> __( 'Live', 'wf-usps-stamps-woocommerce' ),
			);
		}
		
		$this->form_fields  = array(
                        'stamps_wrapper'=>array(
                            'type'=>'stamps_tabs'
                        ),
                        'gopremium'  => array(
				'type'			=> 'activate_box',
                                'class'                             =>'gopremium_tab_field'
                            
			),
			'enabled'				=> array(
				'title'					=> __( 'Real-time Rates', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'checkbox',
				'label'					=> __( 'Enable', 'wf-usps-stamps-woocommerce' ),
				'default'				=> 'no',
				'description'			=> __( 'Enable realtime rates on Cart/Checkout page.', 'wf-usps-stamps-woocommerce' ),
				'desc_tip'			 	 => true,
                                'class'                             =>'general_tab_field'
			),
			'title'					=> array(
				'title'					=> __( 'Method Title', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'text',
				'description'			=> __( 'This controls the title which the user sees during checkout.', 'wf-usps-stamps-woocommerce' ),
				'default'				=> __( $this->method_title, 'wf-usps-stamps-woocommerce' ),
				'placeholder'			=> __( $this->method_title, 'wf-usps-stamps-woocommerce' ),
				'desc_tip'				=> true,
                                'class'                             =>'rates_tab_field'
			),
			'availability'			=> array(
				'title'					=> __( 'Method Available to', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'select',
                                'css'                                   => 'padding: 0px;',
				'default'				=> 'all',
				'class'					=> 'rates_tab_field',
				'options'				=> array(
					'all'				=> __( 'All Countries', 'wf-usps-stamps-woocommerce' ),
					'specific'			=> __( 'Specific Countries', 'wf-usps-stamps-woocommerce' ),
				),
			),
			'countries'				=> array(
				'title'					=> __( 'Specific Countries', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'multiselect',
				'class'					=> 'chosen_select rates_tab_field',
				'css'					=> 'width: 450px;',
				'default'				=> '',
				'options'				=> $woocommerce->countries->get_allowed_countries(),
			),
			'origin'					=> array(
				'title'					=> __( 'Origin Postcode', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'text',
				'description'			=> __( 'Enter the postcode for the <strong>sender</strong>.', 'wf-usps-stamps-woocommerce' ),
				'default'				=> '',
				'desc_tip'				=> true,
                                'class'                             =>'rates_tab_field'
			),
			'api'					=> array(
				'title'					=> __( 'Common API Settings:', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'title',
                                'class'                             =>'general_tab_field',
				'description'			=> sprintf( __( 'Obtain a Stamps.com User ID and Password by signing up on the %s.', 'wf-usps-stamps-woocommerce' ), '<a href="http://www.stamps.com/xadapter" target="_blank">' . __( 'Stamps.com website', 'wf-usps-stamps-woocommerce' ) . '</a>' ),
			),
			'user_id'				=> array(
				'title'					=> __( 'User ID', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'text',
				'description'			=> __( 'Obtained from <a href="http://www.stamps.com/wooforce" target="_blank">Stamps.com</a> after getting an account.', 'wf-usps-stamps-woocommerce' ),
				'default'				=> '',
				'desc_tip'				=> true,
                                'class'                             =>'general_tab_field'
			),
			'password'					=> array(
				'title'					=> __( 'Password', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'password',
				'description'			=> __( 'Obtained from <a href="http://www.stamps.com/wooforce" target="_blank">Stamps.com</a> after getting an account.', 'wf-usps-stamps-woocommerce' ),
				'default'				=> '',
				'desc_tip'				=> true,
                                'class'                             =>'general_tab_field'
			),
			'debug_mode'				=> array(
				'title'					=> __( 'Debug Mode', 'wf-usps-stamps-woocommerce' ),
				'label'					=> __( 'Enable', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'checkbox',
				'default'				=> 'no',
				'description'			=> __( 'Enable debug mode to show debugging information on your cart/checkout. Not recommended to enable this in live site with traffic.', 'wf-usps-stamps-woocommerce' ),
				'desc_tip'			  => true,
                                'class'                             =>'general_tab_field'
			),
			'api_mode'				=> array(
				'title'					=> __( 'API Mode', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'select',
                                'css'                                   => 'padding: 0px;',
				'default'				=> 'Live',
				'options'				=> $api_mode_options,
				'description'			=> __( 'Live mode is the strict choice for Customers as Test mode is strictly restricted for development purpose by Stamps.com.', 'wf-usps-stamps-woocommerce' ),
				'desc_tip'			  => true,
                                'class'                             =>'general_tab_field'
			),
			'rates'					=> array(
				'title'				=> __( 'Rates:', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'title',
                                'class'                             =>'rates_tab_field',
				'description'			=> __( 'The following settings determine the rates you offer your customers.', 'wf-usps-stamps-woocommerce' )
			),
			'fallback'					=> array(
				'title'				=> __( 'Fallback', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'text',
				'description'			=> __( 'If Stamps.com returns no matching rates, offer this amount for shipping so that the user can still checkout. Leave blank to disable.', 'wf-usps-stamps-woocommerce' ),
				'default'				=> '',
				'desc_tip'			  => true,
                                'class'                             =>'rates_tab_field'
			),
			'prioritize_flate_rate' => array(
				'title'				=> __( 'Prioritize flat rate', 'wf-usps-stamps-woocommerce' ),
				'label'				=> __( 'Enable', 'wf-usps-stamps-woocommerce' ),
				'type'					=> 'checkbox',
				'default'				=> 'no',
				'description'			=> __( 'Enable this to prioritize flat rate in the case of package rates and flate rates are available together.', 'wf-usps-stamps-woocommerce' ),
				'desc_tip'			  => true,
                                'class'                             =>'rates_tab_field'
			),
			'boxes'				=> array(
				'type'					=> 'box_packing',
                                'class'                             =>'package_tab_field'
			),
			'services'				=> array(
				'type'					=> 'services',
                                'class'                             =>'rates_tab_field'
			),
		);
	}

	public function get_stamps_authenticate_response() {
		$stamps_settings	= get_option( 'woocommerce_'.WF_USPS_STAMPS_ID.'_settings', null );
		$stamps_user_id	 = isset( $stamps_settings['user_id'] ) ? $stamps_settings['user_id'] : '';
		$stamps_password	= isset( $stamps_settings['password'] ) ? $stamps_settings['password'] : '';
		$stamps_access_key  = WF_USPS_STAMPS_ACCESS_KEY;

		$request['Credentials'] =	array(
			'IntegrationID' => $stamps_access_key,
			'Username' => $stamps_user_id,
			'Password' => $stamps_password,
		);
		$this->debug( 'Stamps.com AUTH REQUEST: <pre>' . print_r( $request, true ) . '</pre>' );
		
		$client	=	new WF_Soap(plugin_dir_path( dirname( __FILE__ ) ) . $this->get_stamps_endpoint(), array( 'trace' => 1, 'connection_timeout' => 10 ));
		$result	=	$client->call('AuthenticateUser', $request);

		$this->debug( 'Stamps.com AUTH RESPONSE: <pre>' . print_r( $result, true ) . '</pre>' );

		return $result;
	}

	public function wf_build_obj_from_xml( $input_xml ) {
		$response_simple_xml	= str_replace( "soap:Body", "soapBody", $input_xml );
		$response_simple_xml	= str_replace( "soap:Envelope", "soapEnvelope", $response_simple_xml );
		$response_simple_xml	= str_replace( "soap:Fault", "soapFault", $response_simple_xml );
		
		$response_obj			= simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response_simple_xml ) . '</root>' );
		return $response_obj;
	}

	public function get_stamps_get_rates_response( $package_request, $stamps_authenticator ) {
		
		$request					= array();
		$request['Authenticator']	= $stamps_authenticator;
		$request['Rate']			= $package_request['Rate'];
		
		$this->debug( 'Stamps.com RATES REQUEST: <pre>' . print_r( $request, true ) . '</pre>' );
		
		$client = new WF_Soap( plugin_dir_path( dirname( __FILE__ ) ) . $this->get_stamps_endpoint(), array( 'trace' => 1 ) );
		$result = $client->call('GetRates', $request);

		$this->debug( 'Stamps.com RATES RESPONSE: <pre>' . print_r( $result, true ) . '</pre>' );

		return $result;
	}

	/**
	 * calculate_shipping function.
	 *
	 * @access public
	 * @param mixed $package
	 * @return void
	 */
	public function calculate_shipping( $package=array() ) {
		global $woocommerce;
                
		$this->rates			   = array();
		$this->unpacked_item_costs = 0;
		$domestic				  = in_array( $package['destination']['country'], $this->domestic ) ? true : false;

		$this->debug( __( 'Stamps.com debug mode is on - to hide these messages, turn debug mode off in the settings.', 'wf-usps-stamps-woocommerce' ) );

		if ( $this->enable_standard_services ) {
			// Get cart package details and proceed with GetRates.
			$package_requests = $this->get_package_requests( $package );

			libxml_use_internal_errors( true );

			if ( $package_requests ) {
				$responses	= array();
				foreach ( $package_requests as $key => $package_request ) {
					// Authenticate with Stamps.com.
					$stamps_authenticator	= '';
					try { 
						$response_obj			= $this->get_stamps_authenticate_response();
						if(!$response_obj)
						{
							//fallback when no response from stamps.com
							if ( $this->fallback ) {
								$this->add_rate( array(
									'id'	=> $this->id . '_fallback',
									'label' => $this->title,
									'cost'	=> $this->fallback,
									'sort'  => 0
								) );
							}
						}
					} catch ( Exception $e ) {
						if($e->getMessage()=='Could not connect to host')
						{
							if ( $this->fallback ) {
								$this->add_rate( array(
									'id'	=> $this->id . '_fallback',
									'label' => $this->title,
									'cost'	=> $this->fallback,
									'sort'  => 0
								) );
							}
						}
						$this->debug( __('Stamps.com - Unable to Get Auth: ', 'wf-usps-stamps-woocommerce').$e->getMessage());
						if ( WF_ADV_DEBUG_MODE == "on" ) { $this->debug( print_r( $e, true ) ); }
						return;
					}
					
					//$response_obj			= $this->wf_build_obj_from_xml( $auth_response );
					
					if( isset( $response_obj->Authenticator ) ) {
						$stamps_authenticator	= $response_obj->Authenticator;
					}
					else {

						$this->debug( __('Stamps.com Unknown error while Auth.', 'wf-usps-stamps-woocommerce') );
						return;
					}
					
					// Get rates.
					try {
						$response = $this->get_stamps_get_rates_response( $package_request['request'], $stamps_authenticator );
						/*Currently Stamps returns rates for all services. Now the rates contains inapropriate rates also(the services which are not possible for the current package). In orcer to remove inapropriate rates we have the below 2 lines. Once stamps make returning rate for only the valid services for the package details we have to remove the below 2 lines.*/
						$response = apply_filters('wf_rate_for_package', $response, $package_request['request']);
						$response = $this->stamps_ignore_invalid_box_rates($response, $package_request['request']);
						
						$response_ele				= array();
						$response_ele['response']	= $response;
						$response_ele['quantity']	= $package_request['quantity'];
						$responses[]				= $response_ele;
					} catch ( Exception $e ) {						
						$this->debug( __('Stamps.com - Unable to Get Rates: ', 'wf-usps-stamps-woocommerce').$e->getMessage() );
						if ( WF_ADV_DEBUG_MODE == "on" ) { $this->debug( print_r( $e, true ) ); }
						return false;
					}
				}                               
				$this->found_rates = array();
				
				$request_number	=	0;
				foreach ( $responses as $response_ele ) {
					$response_obj = $response_ele['response'];
					if( isset( $response_obj->Rates ) ) {
                                            if(!empty($response_obj->Rates->Rate)){
						$stamps_rates	= $response_obj->Rates->Rate;
						
						$this->min_rate	=	array();
						$process_ok	= false;
						$is_found_rates	= false;
						foreach ( $stamps_rates as $stamps_rate ) {
							$service_type = (string) $stamps_rate->ServiceType;
							$package_type = (string) $stamps_rate->PackageType;
							//$service_name = (string) $this->services["$service_type"]["name"];

							// if given service is not listed in our array or it is desabled 
							if ( !isset( $this->custom_services[ $service_type ][ $package_type ] ) || ( isset( $this->custom_services[ $service_type ][ $package_type ] ) && empty( $this->custom_services[ $service_type ][ $package_type ]['enabled'] ) ) ){
								continue;
							}
							
							if( $this->prioritize_flate_rate  && $package_type == 'Package' ){
								$this->kept_package_for_process_last = $stamps_rate;
								continue;
							}
							
							$process_ok = $this->process_stamps_rate( $stamps_rate, $response_ele, $service_type, $package_type, $request_number );
							if( $process_ok ){
								$is_found_rates =true;
							}
						}
                                        }

						//process 'package' at last if prioritize_flate_rate is enabled
						if( !$is_found_rates && !empty($this->kept_package_for_process_last) ){
							$service_type = (string) $this->kept_package_for_process_last->ServiceType;
							$package_type = (string) $this->kept_package_for_process_last->PackageType;
							$this->process_stamps_rate( $stamps_rate, $response_ele, $service_type, $package_type, $request_number );
						}			
						
						$request_number++;
					}
					else {
						$this->debug( __('Stamps.com - Unknown error while processing Rates.', 'wf-usps-stamps-woocommerce') );
						return;
					}
				}
				
				$this->debug( __('ALL Valid Rates : <pre>'.print_r($this->found_rates, true).'</pre>', 'wf-usps-stamps-woocommerce') );
				
				apply_filters('wf_stamps_alter_rate_response',	$this->found_rates,	$request_number);
				
				$this->found_rates	=	$this->sanitize_rates($this->found_rates, $request_number);
				
				$final_rates	=	array();
				foreach($this->found_rates as $service_type => $service_rate_responses){
					$max_rate	=	0;
					foreach($service_rate_responses as $response_number => $rate_response){
						// As this have minimum rated package (with package type), so 1 element present only
						$package_type	=	key($rate_response);
						$rate			=	current($rate_response);
						
						if(!isset($final_rates[$service_type])){
							$final_rates[$service_type][$package_type]	=	$rate;
							$max_rate	=	$rate['cost'];
						}else{
							$prev_rate_package_type	=	key($final_rates[$service_type]);
							$prev_rate	=	current($final_rates[$service_type]);
							
							$new_package_type	=	$prev_rate_package_type;
							if($rate['cost'] > $max_rate){
								$max_rate	=	$rate['cost'];
								$new_package_type	=	$package_type;
							}						
							
							$rate['cost']	=	$prev_rate['cost'] + $rate['cost'];
							$final_rates[$service_type]	=	array(
								$new_package_type	=>	$rate,
								
							);
						}
					}			
				}				
				
				$this->debug( __('Final Added Rates : <pre>'.print_r($final_rates, true).'</pre>', 'wf-usps-stamps-woocommerce') );
				if( $final_rates ) {
					
					$this->found_rates = apply_filters('wf_rate_for_service', $final_rates, $package_requests);
					
					$prev_rate  = array(
									'service_type' =>'', 
									'cost' => ''
								);
					$new_found_rates=array();
                    foreach($this->custom_services as $key=>$values)
                    {
                        if(isset($this->found_rates[$key]))
                        $new_found_rates[$key]=$this->found_rates[$key];
                    }
					foreach ( $new_found_rates as $service_type => $found_rate ) {
						foreach ($found_rate as $package_type => $value) {
								
							if( $prev_rate['service_type'] == $service_type && $prev_rate['cost'] < $value['cost'] )
								continue;
							
							$total_amount = $value['cost'];
							// Cost adjustment %
							if ( ! empty( $this->custom_services[ $service_type ][ $package_type ]['adjustment_percent'] ) ) {
								$total_amount = $total_amount + ( $total_amount * ( floatval( $this->custom_services[ $service_type ][ $package_type ]['adjustment_percent'] ) / 100 ) );
							}

							// Cost adjustment
							if ( ! empty( $this->custom_services[ $service_type ][ $package_type ]['adjustment'] ) ) {
								$total_amount = $total_amount + floatval( $this->custom_services[ $service_type ][ $package_type ]['adjustment'] );
							}

															
							$rate = array(
								'id'		=> (string)$this->id.':'.$service_type,
								'label'	=> (string) $value['label']." ($this->title)",
								'cost'		=> (string) $total_amount,
							);

							$prev_rate['service_type'] = $service_type;
							$prev_rate['cost'] = $value['cost'];
							// Register the rate
							$this->add_rate( $rate );
						}
					
					}
						
				}
                                
			}
		}
	}

	private function process_stamps_rate( $stamps_rate, $response_ele, $service_type, $package_type, $request_number ){
		$service_name = (string) ( isset( $this->custom_services[ $service_type ]['name'] ) && !empty( $this->custom_services[ $service_type ]['name'] ) ) ? $this->custom_services[ $service_type ]['name'] : $this->services[$service_type]["name"];
		$total_amount = $response_ele['quantity'] * $stamps_rate->Amount;

                
		if( isset( $this->found_rates[$service_type][$package_type] ) ) {
			$this->found_rates[$service_type][$package_type]['cost']		= $this->found_rates[$service_type][$package_type]['cost'] + $total_amount;
		}else{
			if(!isset($this->min_rate[$service_type][$request_number])){
				$this->min_rate[$service_type][$request_number]['price']	=	$total_amount;
				$this->min_rate[$service_type][$request_number]['type']	=	$package_type;								
			}else if($this->min_rate[$service_type][$request_number]['price']>$total_amount){
				$this->min_rate[$service_type][$request_number]['price']	=	$total_amount;
				$this->min_rate[$service_type][$request_number]['type']	=	$package_type;
			}else{
				return false;
			}
			$this->found_rates[$service_type][$request_number]	=	array(
				$this->min_rate[$service_type][$request_number]['type']	=>	array(
					'label'	=>	$service_name,
					'cost'	=>	$this->min_rate[$service_type][$request_number]['price'],										
				),
			);
		}
		return true;
	}

	/**
	 * prepare_rate function.
	 *
	 * @access private
	 * @param mixed $rate_code
	 * @param mixed $rate_id
	 * @param mixed $rate_name
	 * @param mixed $rate_cost
	 * @return void
	 */
	private function prepare_rate( $rate_code, $rate_id, $rate_name, $rate_cost ) {

		// Name adjustment
		if ( ! empty( $this->custom_services[ $rate_code ]['name'] ) )
			$rate_name = $this->custom_services[ $rate_code ]['name'];

		// Merging
		if ( isset( $this->found_rates[ $rate_id ] ) ) {
			$rate_cost = $rate_cost + $this->found_rates[ $rate_id ]['cost'];
			$packages  = 1 + $this->found_rates[ $rate_id ]['packages'];
		} else {
			$packages = 1;
		}

		// Sort
		if ( isset( $this->custom_services[ $rate_code ]['order'] ) ) {
			$sort = $this->custom_services[ $rate_code ]['order'];
		} else {
			$sort = 999;
		}

		$this->found_rates[ $rate_id ] = array(
			'id'	   => $rate_id,
			'label'	=> $rate_name,
			'cost'	 => $rate_cost,
			'sort'	 => $sort,
			'packages' => $packages
		);
	}

	/**
	 * sort_rates function.
	 *
	 * @access public
	 * @param mixed $a
	 * @param mixed $b
	 * @return void
	 */
	public function sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) return 0;
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}

	/**
	 * get_request function.
	 *
	 * @access private
	 * @return void
	 */
	// WF - Changing function to public.
	public function get_package_requests( $package ) {
		$requests = $this->per_item_shipping( $package );
		
		return $requests;
	}
	
	/**
	 * per_item_shipping function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return void
	 */
	private function per_item_shipping( $package ) {
		global $woocommerce;

		$requests = array();
		$domestic = in_array( $package['destination']['country'], $this->domestic ) ? true : false;
        $count = 0;

		// Get weight of order
		foreach ( $package['contents'] as $item_id => $values ) {
			$values['data'] = $this->wf_load_product( $values['data'] );

            $package_weight = 0;
            $package_weight = $values['data']->get_weight();
            $package_weight = !empty($package_weight)? $package_weight: 0;

            $stamps_max_weight = round(wc_get_weight(70, $this->weight_unit, 'lbs'),2);
            /*Validating the package weight and sending an error notice if the weight is more than 70lbs*/
            if($this->weight_unit != 'lbs'){
                if($package_weight > 0){
                    $package_weight_in_lbs = wc_get_weight($package_weight, 'lbs', $this->weight_unit);
                    if($package_weight_in_lbs > 70){
                        $this->debug( sprintf( 'Package #%d exceeds the maximum weight limit of '.$stamps_max_weight.$this->weight_unit.'(70lbs). So, unable to display the rates', ++$count ), 'error' );
                        return;       
                    }
                }
            }else{
                if($package_weight > 70){
                    $this->debug( sprintf( 'Package #%d exceeds the maximum weight limit of 70 lbs. So, unable to display the rates', ++$count ), 'error' );
                    return;
                }
            }

			if (empty($values['data']) || ! $values['data']->needs_shipping() ) {
				$this->debug( sprintf( __( 'Product # is virtual. Skipping.', 'wf-usps-stamps-woocommerce' ), $item_id ) );
				continue;
			}

			if ( ! $values['data']->get_weight() ) {
				$this->debug( sprintf( __( 'Product # is missing weight. Using 1lb.', 'wf-usps-stamps-woocommerce' ), $item_id ) );

				$weight = 1;
			} else {
				$weight = wc_get_weight( $values['data']->get_weight(), 'lbs' );
			}

			$size   = 'REGULAR';

			if ( $values['data']->length && $values['data']->height && $values['data']->width ) {

				$dimensions = array( wc_get_dimension( $values['data']->length, 'in' ), wc_get_dimension( $values['data']->height, 'in' ), wc_get_dimension( $values['data']->width, 'in' ) );

				sort( $dimensions );

				if ( max( $dimensions ) > 12 ) {
					$size   = 'LARGE';
				}

				$girth = $dimensions[0] + $dimensions[0] + $dimensions[1] + $dimensions[1];
			} else {
				$dimensions = array( 0, 0, 0 );
				$girth	  = 0;
			}

			$quantity = $values['quantity'];
			
			if ( 'LARGE' === $size ) {
				$rectangular_shaped  = 'true';
			} else {
				$rectangular_shaped  = 'false';
			}

			if ( $domestic ) {
				$request['Rate'] = array(
					'FromZIPCode'			=> str_replace( ' ', '', strtoupper( $this->origin ) ),
					'ToZIPCode'				=> strtoupper( substr( $package['destination']['postcode'], 0, 5 ) ),
					'WeightLb'				=> floor( $weight ),
					'WeightOz'				=> number_format( ( $weight - floor( $weight ) ) * 16, 2 ),
					'PackageType'			=> '',
					'Length'				=> $dimensions[2],
					'Width'					=> $dimensions[1],
					'Height'				=> $dimensions[0],
					'ShipDate'				=> date( "Y-m-d", ( current_time('timestamp') + $this->timezone_offset ) ),
					'InsuredValue'			=> 'yes' == $this->stamps_insure_contents ? $values['data']->get_price() : 0,
					'RectangularShaped'		=> $rectangular_shaped
				);

			} else {
				$request['Rate'] = array(
					'FromZIPCode'			=> str_replace( ' ', '', strtoupper( $this->origin ) ),
					'ToZIPCode'				=> strtoupper( substr( $package['destination']['postcode'], 0, 5 ) ),
					'ToCountry'				=> $package['destination']['country'],
					'Amount'				=> $values['data']->get_price(),
					'WeightLb'				=> floor( $weight ),
					'WeightOz'				=> number_format( ( $weight - floor( $weight ) ) * 16, 2 ),
					'PackageType'			=> '',
					'Length'				=> $dimensions[2],
					'Width'					=> $dimensions[1],
					'Height'				=> $dimensions[0],
					'ShipDate'				=> date( "Y-m-d", ( current_time('timestamp') + $this->timezone_offset ) ),
					'InsuredValue'			=> 'yes' == $this->stamps_insure_contents ? $values['data']->get_price() : 0,
					'RectangularShaped'		=> $rectangular_shaped
				);
			}

			$request_ele				= array();
			$request_ele['request']	= $request;
			$request_ele['quantity']	= $quantity;
			$request_ele['line_items']	= $this->create_line_items( array($values['data']) );
                        			
			$requests[] = $request_ele;

            $count++;                        
		}

		return $requests;
	}

	/**
	 * Generate a package ID for the request
	 *
	 * Contains qty and dimension info so we can look at it again later when it comes back from USPS if needed
	 *
	 * @return string
	 */
	public function generate_package_id( $id, $qty, $length, $width, $height, $weight ) {
		return implode( ':', array( $id, $qty, $length, $width, $height, $weight ) );
	}

	/**
	 * box_shipping function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return void
	 */
	
	
	/**
	 * weight_based_shipping function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return void
	 */
	
	
	function create_line_items($items, $line_item_options = array()){

		$custom_line_array = array();
		foreach( $items as $item ) {
			$product_data		= $item;
			$title				= $product_data->get_title();
			$weight			= wc_get_weight( $product_data->get_weight(), 'lbs' );
			$shipment_description	= $title;
			$shipment_description = ( strlen( $shipment_description ) >= 50 ) ? substr( $shipment_description, 0, 45 ).'...' : $shipment_description;
			$quantity				= property_exists($product_data, 'qty') ? $product_data->qty : 1;
			$value					= $item->get_price();
			
			$item_line_weight		=	(float)$quantity * $weight;
			
			$item_line_weight_lb	=	floor($item_line_weight);
			$item_line_weight_oz	=	( $item_line_weight - floor( $item_line_weight ) ) * 16;
			
			$custom_line			= array();
			$custom_line['Description']		= $shipment_description;
			$custom_line['Quantity']		= $quantity;
			$custom_line['Value']			= $value;
			
			$custom_line['WeightLb']		= (string)$item_line_weight_lb;
			$custom_line['WeightOz']		= (string)$item_line_weight_oz;
			
			$par_id	= wp_get_post_parent_id( $item->get_id() );
			$post_id	= $par_id ? $par_id : $item->get_id();
                        
			$hst						= get_post_meta( $post_id, '_wf_stamps_hs_code', true);
			$country_of_manufacture		= get_post_meta( $post_id, '_wf_stamps_manufacture_country', true);
			$signature					= get_post_meta( $post_id, '_wf_stamps_signature', true);
                        
			if( !empty($hst) ){
				$custom_line['HSTariffNumber'] = $hst;
			}
			if( !empty($country_of_manufacture) ){
				$custom_line['CountryOfOrigin'] = $country_of_manufacture;
			}
			if( !empty($signature) ){
				$custom_line['signature'] = $signature;
			}
			
			if(is_array($line_item_options)){
				foreach($line_item_options as $option_key	=>	$option_val){
					$custom_line[$option_key]	=	$option_val;
				}
			}
			$custom_line_array[] = $custom_line;
			
		}
                
		return $custom_line_array;
	}

	public function debug( $message, $type = 'notice' ) {
		if ( $this->debug && !is_admin()) { //WF: is_admin check added.
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
				wc_add_notice( $message, $type );
			} else {
				global $woocommerce;
				$woocommerce->add_message( $message );
			}
		}
	}

	/**
	 * wf_get_api_rate_box_data function.
	 *
	 * @access public
	 */
	public function wf_get_api_rate_box_data( $package, $packing_method ) {
		$this->packing_method	= $packing_method;
		$requests				= $this->get_package_requests( $package );
		$package_data_array	= array();
		if ( $requests ) {
			foreach ( $requests as $key => $request ) {
				$package_data		= array();
				$request_data		=  $request['request']['Rate'];
				
				// PS: Some of PHP versions doesn't allow to combining below two line of code as one. 
				// id_array must have value at this point. Force setting it to 1 if it is not.
				$package_data[ 'BoxCount' ]		= isset( $request['quantity'] ) ? $request['quantity'] : 1;
				$package_data[ 'WeightLb' ]		= isset( $request_data[ 'WeightLb' ] ) ? $request_data[ 'WeightLb' ] : '';
				$package_data[ 'WeightOz' ]		= isset( $request_data[ 'WeightOz' ] ) ? $request_data[ 'WeightOz' ] : '';
				$package_data[ 'FromZIPCode' ]		= isset( $request_data[ 'FromZIPCode' ] ) ? $request_data[ 'FromZIPCode' ] : '';
				$package_data[ 'ToZIPCode' ]		= isset( $request_data[ 'ToZIPCode' ] ) ? $request_data[ 'ToZIPCode' ] : '';
				$package_data[ 'RectangularShaped' ]= isset( $request_data[ 'RectangularShaped' ] ) ? $request_data[ 'RectangularShaped' ] : '';
				$package_data[ 'InsuredValue' ]		= isset( $request_data[ 'InsuredValue' ] ) ? $request_data[ 'InsuredValue' ] : '';
				$package_data[ 'ShipDate' ]			= isset( $request_data[ 'ShipDate' ] ) ? $request_data[ 'ShipDate' ] : '';
				$package_data[ 'Width' ]			= isset( $request_data[ 'Width' ] ) ? $request_data[ 'Width' ] : '';
				$package_data[ 'Length' ]			= isset( $request_data[ 'Length' ] ) ? $request_data[ 'Length' ] : '';
				$package_data[ 'Height' ]			= isset( $request_data[ 'Height' ] ) ? $request_data[ 'Height' ] : '';
				$package_data[ 'Girth' ]			= isset( $request_data[ 'Girth' ] ) ? $request_data[ 'Girth' ] : '';
				
				$package_data[ 'LineItems' ]		= (isset( $request['line_items'] ) && is_array($request[ 'line_items' ])) ? $request[ 'line_items' ] : array();

				$package_data_array[]				= $package_data; 
			}
		}

		return $package_data_array;
	}
	
	/**
	 * wf_get_api_rate_box_data_manual function.
	 *
	 * @access public
	 * @return package_data_array
	 */
	public function wf_get_api_rate_box_data_manual( $package, $weight, $dimensions ) {
		$stamps_settings		= get_option( 'woocommerce_'.WF_USPS_STAMPS_ID.'_settings', null ); 
		$stamps_origin			= isset( $stamps_settings['origin'] ) ? $stamps_settings['origin'] : '';
		
		$weight		= isset( $_GET['weight'] )	? $_GET['weight']	: false;
		$height		= isset( $_GET['height'] )	? $_GET['height']	: false;
		$width		= isset( $_GET['width'] )	? $_GET['width']	: false;
		$length		= isset( $_GET['length'] )	? $_GET['length']	: false;
		//$size	= 'REGULAR';
		$rectangular_shaped = true;
		
		if($weight){
			$weight	=	wc_get_weight($weight,'lbs');
		}
		if ( $height && $width && $length ) {
			
			$height	=	wc_get_dimension($height, 'in');
			$width	=	wc_get_dimension($width, 'in');
			$length	=	wc_get_dimension($length, 'in');
			
			$dimensions = array( $height, $width, $length );
			sort( $dimensions );
			
			if ( max( $dimensions ) > 12 ) {
				//$size   = 'LARGE';
				$rectangular_shaped = false;
			}
			$girth		= $dimensions[0] + $dimensions[0] + $dimensions[1] + $dimensions[1];
		}
		else {
			$dimensions = array( 0, 0, 0 );
			$girth	  = 0;
		}
		
		$package_data_array	= array();
		$package_data = array();
		
		$package_data[ 'BoxCount' ]				= 1;
		$package_data[ 'WeightLb']				= floor( $weight );
		$package_data[ 'WeightOz' ]			= number_format( ( $weight - floor( $weight ) ) * 16, 2 );
		$package_data[ 'FromZIPCode']			= $stamps_origin;
		$package_data[ 'ToZIPCode' ]			= $package['destination']['postcode'];
		$package_data[ 'RectangularShaped' ]	= $rectangular_shaped; 
		$package_data[ 'InsuredValue' ]			= '';
		$package_data[ 'ShipDate' ]				= date( "Y-m-d", ( current_time('timestamp') + $this->timezone_offset ) );
		$package_data[ 'Length' ]				= $dimensions[2];
		$package_data[ 'Width' ]				= $dimensions[1];
		$package_data[ 'Height' ]				= $dimensions[0];
		$package_data[ 'Girth' ]				= $girth;
		$package_data_array[] = $package_data;
		return $package_data_array;
	}
	
	// Clean rate response
	function sanitize_rates($rates, $request_number=0){
		if(!$request_number){
			foreach($rates as $service){
				if(	count($service)	>	$request_number	)
					$request_number	=	count($service);
			}
		}
		
		foreach($rates as $service_type	=>	$service){
			if(	count($service)	<	$request_number	){ // If service doen't exists for all requests, then ignore that service
				unset($rates[$service_type]);
			}
		}
		return $rates;
	}

	function get_stamps_endpoint () {
		$stamps_settings	= get_option( 'woocommerce_'.WF_USPS_STAMPS_ID.'_settings', null ); 
		$api_mode			= isset( $stamps_settings['api_mode'] ) ? $stamps_settings['api_mode'] : 'Live';
		
		if( 'Test' == $api_mode ) { 
			//$stamps_uri = 'includes/wsdl/testing-swsimv36.wsdl';
			//$stamps_uri = 'includes/wsdl/testing-swsimv45.wsdl';
			$stamps_uri = 'includes/wsdl/testing-swsimv50.wsdl';
		}
		else {
			//$stamps_uri = 'includes/wsdl/swsimv36.wsdl';
			//$stamps_uri = 'includes/wsdl/swsimv45.wsdl';
			$stamps_uri = 'includes/wsdl/swsimv50.wsdl';
		}

		return $stamps_uri;
	}
	
	/*
	 * function to remove inapropriate rates for the package requested
	 *
	 * @ since 1.7.3
	 * @ access Private
	 * @ params response
	 * @ params package_request
	 * @ return response
	 */
	 private function stamps_ignore_invalid_box_rates($response, $package_request)
	 {
		// stamps package types and sizes
		$stamps_box_sizes	=	array(
			// Domestic
			'US-FC' => array(
				// Name of the service shown to the user
				'name'  => 'First-Class Mail&#0174;',
				'package_types'  =>  array(
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Postcard'				  => array(
						'length'	=>	6,
						'width'		=>	4.25,
						'height'	=>	0.016,
					),
					'Large Envelope or Flat'	=> array(
						'length'	=>	15,
						'width'		=>	12,
						'height'	=>	0.75,
					),
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
					'Large Package' => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
				)
			),
			'US-XM' => array(
				// Name of the service shown to the user
				'name'  => 'Priority Mail Express&#8482;',
				'package_types'  =>  array(	
					'Flat Rate Envelope'		=> array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 0.25,
						"weight"   => 70,
					),
					'Flat Rate Padded Envelope' => array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 1,
						"weight"   => 70,
					),
					'Letter'					=> array(
						'length'	=>	11.5,
						'width'		=>	6.125,
						'height'	=>	0.25,
					),
					'Large Envelope or Flat'	=> array(
						'length'	=>	15,
						'width'		=>	12,
						'height'	=>	0.75,
					),
					'Large Package' => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
					'Legal Flat Rate Envelope'  => array(
						"length"   => 9.5,
						"width"	=> 15,
						"height"   => 0.25,
						"weight"   => 70,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			),
			'US-MM' => array(
				// Name of the service shown to the user
				'name'  => 'Media Mail Parcel',
				'package_types'  => array(
					'Large Envelope or Flat'	=> array(
						'length'	=>	15,
						'width'		=>	12,
						'height'	=>	0.75,
					),
					'Large Package' => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			),
			'US-LM' => array(
				// Name of the service shown to the user
				'name'  => "Library Mail",
				'package_types'  =>  array(
					'Large Envelope or Flat'	=> array(
						'length'	=>	15,
						'width'		=>	12,
						'height'	=>	0.75,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			),
			'US-PP' => array(
				// Name of the service shown to the user
				'name'  => "USPS Parcel Post",
				'package_types'  =>  array(
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
				)
			),
			'US-PS' => array(
				// Name of the service shown to the user
				'name'  => "USPS Parcel Select",
				'package_types'  =>  array(			
					'Large Package' => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
					'Oversized Package'		 => array(
						"length"   => 130,
						"width"	=> 130,
						"height"   => 130,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			),
			'US-CM' => array(
				// Name of the service shown to the user
				'name'  => "USPS Critical Mail",
				'package_types'  =>  array(
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
				)
			),
			'US-PM' => array(
				// Name of the service shown to the user
				'name'  => "Priority Mail&#0174;",
				'package_types'  =>array(
					'Flat Rate Box'			 => array(
						"length"   => 11,
						"width"	=> 8.5,
						"height"   => 5.5,
						"weight"   => 70,
					),
					'Flat Rate Envelope'		=> array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 0.25,
						"weight"   => 70,					
					),
					'Flat Rate Padded Envelope' => array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 1,
						"weight"   => 70,
					),
					'Letter'					=> array(
						'length'	=>	11.5,
						'width'		=>	6.125,
						'height'	=>	0.25,
					),
					'Large Envelope or Flat'	=> array(
						"length"   => 10,
						"width"	=> 6,
						"height"   => 0.25,
					),
					'Large Flat Rate Box'	=> array(
						"length"   => 12,
						"width"	=> 12,
						"height"   => 5.5,
						"weight"   => 70,
					),	
					'Large Package' => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
					'Legal Flat Rate Envelope'  => array(
						"length"   => 15,
						"width"	=> 9.5,
						"height"   => 0.5,
						"weight"   => 70,
					),
					
					'Oversized Package'		 => array(
						"length"   => 130,
						"width"	=> 130,
						"height"   => 130,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Regional Rate Box A'	   => array(
						"length"   => 10,
						"width"	=> 7,
						"height"   => 4.75,
					),
					'Regional Rate Box B'	   => array(
						"length"   => 12,
						"width"	=> 10.25,
						"height"   => 5,
					),
					'Small Flat Rate Box'	   => array(
						"length"   => 8.625,
						"width"	=> 5.375,
						"height"   => 1.625,
						"weight"   => 70,
					),
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			),

			// International
			'US-EMI' => array(
				// Name of the service shown to the user
				'name'  => "Priority Mail Express International&#8482;",
				'package_types'  =>  array(
					'Flat Rate Envelope'		=> array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 0.25,
						"weight"   => 4,
					),
					'Flat Rate Padded Envelope' => array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 1,
						"weight"   => 4,
					),
					'Large Envelope or Flat'	=> array(
						'length'	=>	15,
						'width'		=>	12,
						'height'	=>	0.75,
					),
					'Large Package' => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
					'Legal Flat Rate Envelope'  => array(
						"length"   => 15,
						"width"	=> 9.5,
						"height"   => 0.25,
						"weight"   => 4,
					),
					'Oversized Package'		 => array(
						"length"   => 130,
						"width"	=> 130,
						"height"   => 130,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			),
			'US-PMI' => array(
				// Name of the service shown to the user
				'name'  => "Priority Mail International&#0174;",
				'package_types'  =>  array(
					
					'Flat Rate Box'			 => array(
						"length"   => 14,
						"width"	=> 12,
						"height"   => 5.5,
						"weight"   => 70,
					),
					'Flat Rate Envelope'		=> array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 0.25,
						"weight"   => 4,
					),
					'Flat Rate Padded Envelope' => array(
						"length"   => 12.5,
						"width"	=> 9.5,
						"height"   => 1,
						"weight"   => 4,
					),
					'Large Envelope or Flat'	=> array(
						"length"   => 15,
						"width"	=> 12,
						"height"   => 0.75,
					),
					'Large Flat Rate Box'	   => array(
						"length"   => 12,
						"width"	=> 12,
						"height"   => 5.5,
					),
					'Large Package'			 => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
					'Legal Flat Rate Envelope'  => array(
						"length"   => 15,
						"width"	=> 9.5,
						"height"   => 0.5,
					),
					'Oversized Package'		 => array(
						"length"   => 130,
						"width"	=> 130,
						"height"   => 130,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Small Flat Rate Box'	   => array(
						"length"   => 8.625,
						"width"	=> 5.375,
						"height"   => 1.625,
						"weight"   => 4,
					),
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			),
			'US-FCI' => array(
				// Name of the service shown to the user
				'name'  => "First Class Package Service&#8482; International",
				'package_types'  =>  array(
					'Letter'					=> array(
						'length'	=>	11.5,
						'width'		=>	6.125,
						'height'	=>	0.25,
					),
					'Large Envelope or Flat'	=> array(
						'length'	=>	15,
						'width'		=>	12,
						'height'	=>	0.75,
					),
					'Large Package' => array(
						"length"   => 108,
						"width"	=> 108,
						"height"   => 108,
					),
					'Oversized Package'		 => array(
						"length"   => 130,
						"width"	=> 130,
						"height"   => 130,
					),
					'Package'				   => array(
						"length"   => 84,
						"width"	=> 84,
						"height"   => 84,
					),	
					'Thick Envelope'			=> array(
						"length"   => 34,
						"width"	=> 17,
						"height"   => 17,
					),
				)
			)
		);
		$pkg	=	$package_request['Rate'];
		if(!isset($pkg['Length'])){ // package dimension not found
			return $response;
		}
		
		if(isset($package_request['Rate'])){
			foreach($stamps_box_sizes as $service_key => $stamps_box_size){
				foreach($stamps_box_size['package_types'] as $package_type => $package_type_dimension){
					//check for fittable box
					if($pkg['Length']>$package_type_dimension['length'] || $pkg['Width']>$package_type_dimension['width'] || $pkg['Height']>$package_type_dimension['height']){
						unset($stamps_box_sizes[$service_key]['package_types'][$package_type]);
					}
				}
			}
		}
		if(isset($response->Rates)){
                    if(!empty($response->Rates->Rate)){
			$stamps_rates	= $response->Rates->Rate;
			foreach ( $stamps_rates as $stamps_rate_key => $stamps_rate ) {
				$service_type = (string) $stamps_rate->ServiceType;
				$package_type = (string) $stamps_rate->PackageType;
				if(!isset($stamps_box_sizes[$service_type]['package_types'][$package_type])){
					unset($stamps_rates[$stamps_rate_key]);
				}
			}
                    }
		}
		if(isset($stamps_rates))
		$response->Rates->Rate	=	$stamps_rates;
		if(isset($response->Rates)){
                    if(!empty($response->Rates->Rate)){
			$stamps_rates	= $response->Rates->Rate;
			foreach ( $stamps_rates as $stamps_rate ) {
				$service_type = (string) $stamps_rate->ServiceType;
				$package_type = (string) $stamps_rate->PackageType;
			}
                    }
		}
		return $response;
	}

	private function wf_load_product( $product ){
		if( !$product ){
			return false;
		}
		return ( WC()->version < '2.7.0' ) ? $product : new wf_product( $product );
	}
}