<?php
/*
	Plugin Name: Stamps.com WooCommerce Extension (USPS) (Basic)
	Plugin URI: https://www.xadapter.com/product/woocommerce-stamps-com-shipping-plugin-with-usps-postage/
	Description: Using Stamps.com APIs, print USPS shipping labels with Postage & obtain USPS real time shipping rates.
	Version: 2.0.8
    WC requires at least: 3.0.0
    WC tested up to: 3.4
	Author: AdaptXY
    Text Domain: wf-usps-stamps-woocommerce
	Author URI: https://adaptxy.com/
*/
//Dev Version: 2.0.0

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Required functions
if ( ! function_exists( 'wf_is_woocommerce_active' ) ) {
	require_once( 'wf-includes/wf-functions.php' );
}

// WC active check
if ( ! wf_is_woocommerce_active() ) {
	return;
}

if ( ! defined( 'WF_USPS_STAMPS_ID' ) )
	define("WF_USPS_STAMPS_ID", "wf_usps_stamps");

if ( ! defined( 'WF_USPS_STAMPS_ACCESS_KEY' ) )
	define("WF_USPS_STAMPS_ACCESS_KEY", "570f77ac-5374-46f1-aee7-84375876174b");

if ( ! defined( 'WF_ADV_DEBUG_MODE' ) )
	define("WF_ADV_DEBUG_MODE", "on"); // Turn "on" to get more logs.

function wf_stamps_pre_activation_check(){
	// Check if basic version is there
	if ( is_plugin_active('usps-stamps-woocommerce-shipping/usps-stamps-woocommerce-shipping.php') ){
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __("Oops! You tried installing the basic version without deactivating the premium version. Kindly deactivate Stamps Woocommerce Extension and then try again", "wf-usps-stamps-woocommerce" ), "", array('back_link' => 1 ));
	}
}
register_activation_hook( __FILE__, 'wf_stamps_pre_activation_check' );

/**
 * WC_USPS class
 */
if(!class_exists('USPS_Stamps_WooCommerce_Shipping')){
	class USPS_Stamps_WooCommerce_Shipping {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
			add_action( 'woocommerce_shipping_init', array( $this, 'shipping_init' ) );
			add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			//for admin
			if( is_admin() ) {
				add_action('admin_footer', array( $this,'wf_stamps_usps_add_bulk_action_links') , 10); //to add bulk option to orders page
				add_action('woocommerce_admin_order_actions_end', array( $this, 'wf_stamps_usps_add_print_label_icon' )); //to add print option at the end of each orders in orders page
			}
			include_once ( 'includes/class-wf-common-options.php' );
		}

		/**
		 * Localisation
		 */
		public function init() {
			
			
			include_once ( 'includes/class-wf-soap.php' );
			if ( ! class_exists( 'wf_order' ) ) {
				include_once 'includes/class-wf-legacy.php';
			}		
			load_plugin_textdomain( 'wf-usps-stamps-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Plugin page links
		 */
		public function plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wf_usps_stamps' ) . '">' . __( 'Settings', 'wf-usps-stamps-woocommerce' ) . '</a>',
				'<a href="https://www.xadapter.com/product/woocommerce-stamps-com-shipping-plugin-with-usps-postage/" target="_blank">' . __('Premium Upgrade', 'wf-usps-stamps-woocommerce') . '</a>',
				'<a href="https://www.xadapter.com/category/product/stamps-com-shipping-plugin-with-usps-postage-for-woocommerce/" target="_blank">' . __('Documentation', 'wf-usps-stamps-woocommerce') . '</a>',
				'<a href="https://wordpress.org/support/plugin/woo-stamps-com-shipping-method" target="_blank">' . __('Support', 'wf-usps-stamps-woocommerce') . '</a>'
			);
			return array_merge( $plugin_links, $links );
		}

		/**
		 * Load gateway class
		 */
		public function shipping_init() {
			include_once( 'includes/class-wf-shipping-stamps.php' );
		}

		/**
		 * Add method to WC
		 */
		public function add_method( $methods ) {
			$methods[] = 'WF_USPS_Stamps';
			return $methods;
		}

		/**
		 * Enqueue scripts
		 */
		public function scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('wf-common-script',	plugins_url( '/resources/js/wf_common.js',	__FILE__ ), array( 'jquery' ) );
			wp_enqueue_style('wf-common-style',		plugins_url( '/resources/css/wf_common_style.css',	__FILE__ ));
		}
		
		/*
		 * Function to add bulk label print option to order bulk actions
		 *
		 * @ since	1.7.2
		 * @ access	public
		 */
		public function wf_stamps_usps_add_bulk_action_links()
		{
			global $post_type;
			if ('shop_order' == $post_type) {
			?>
				<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('<option>').val('create_shipment_stamps_usps').text('<?php
						_e('Create Stamps USPS Shipment', 'wf-usps-stamps-woocommerce') ?>'
					).appendTo("select[name='action']");

					jQuery('<option>').val('create_shipment_stamps_usps').text('<?php
						_e('Create Stamps USPS Shipment', 'wf-usps-stamps-woocommerce') ?>'
					).appendTo("select[name='action2']");
				});
				</script>
				<?php
			}
		}
		
		/*
		 * Function to add label print icons to the order details page.
		 *
		 * @ since	1.7.2
		 * @ access	public
		 * @ param order
		 */
		public function wf_stamps_usps_add_print_label_icon( $order ){
			global $post;
			$order = new wf_order( $order );
			$stamps_usps_shipment 	= get_post_meta( $order->id, 'wf_stamps_labels', true );
			if( !empty($stamps_usps_shipment) ){ 
				$i=0;
				foreach( $stamps_usps_shipment as $shipment_id => $stamps_usps_label_details ) {
					$download_url = $stamps_usps_label_details['url'];
					$i++;
					?>
					<a disabled class="button tips " 
						target="_blank" 
						data-tip="<?php esc_attr_e('Print Stamps USPS Label', 'wf-usps-stamps-woocommerce'); ?>" 
						href="<?php echo $download_url ?>">
							<img src="<?php echo untrailingslashit(plugins_url('/', __FILE__)) . '/resources/images/stamps.png'; ?>" 
								alt="<?php esc_attr_e('Print Shipping Label', 'wf-usps-stamps-woocommerce'); ?>" width="14"/>
					</a><?php
				}
			}
		}
	}
	new USPS_Stamps_WooCommerce_Shipping();
}
