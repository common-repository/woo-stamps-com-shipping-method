<?php
if( !class_exists('WF_Common_Options') ){
    class WF_Common_Options{
        function __construct(){
            $this->init();
        }

        function init(){
            if( is_admin() ){
                add_action( 'woocommerce_product_options_shipping', array($this,'wf_additional_product_shipping_options'), 1 );
                add_action( 'woocommerce_process_product_meta', array( $this, 'wf_save_additional_product_shipping_options' ), 1 );
            }
        }

        function wf_additional_product_shipping_options() {
            //HS code field
            woocommerce_wp_text_input( array(
                'id' => '_wf_stamps_hs_code',
                'label' => __('HS Tariff Number (Stamps)', 'wf-usps-stamps-woocommerce'),
                'description' => __('HS is a standardized system of names and numbers to classify products.', 'wf-usps-stamps-woocommerce' ),
                'desc_tip' => 'true',
                'placeholder' => 'HTS Code'
                ) 
            );

            //Country of manufacture
            woocommerce_wp_text_input( array(
                'id' => '_wf_stamps_manufacture_country',
                'label' => __('Country of manufacture (Stamps)', 'wf-usps-stamps-woocommerce'),
                'description' => __('Country of manufacture', 'wf-usps-stamps-woocommerce'),
                'desc_tip' => 'true',
                'placeholder' => 'Country of manufacture'
                ) 
            );


            woocommerce_wp_select(array(
                'id'            =>     '_wf_stamps_signature',
                'label'         =>   __('Stamps Delivery Signature','wf-usps-stamps-woocommerce'),
                'options'       => array(
                    0   => __('None','wf-usps-stamps-woocommerce'),
                    1   => __('Delivery Confirmation', 'wf-usps-stamps-woocommerce'),                    
                    2   => __('Signature Required','wf-usps-stamps-woocommerce'),
                    3   => __('Signature Confirmation', 'wf-usps-stamps-woocommerce'),
                    4   => __('Adult Signature Required','wf-usps-stamps-woocommerce'),
                    5   => __('Adult Signature Restricted Delivery','wf-usps-stamps-woocommerce'),
                ),
                'description'   => __('Choose you delivery confirmation options here. keep it blank for service default','wf-usps-stamps-woocommerce'),
                'desc_tip'      => 'true',
            ));
        }
        function wf_save_additional_product_shipping_options( $post_id ) {
            //HS code value
            if ( isset( $_POST['_wf_stamps_hs_code'] ) ) {
                update_post_meta( $post_id, '_wf_stamps_hs_code', esc_attr( $_POST['_wf_stamps_hs_code'] ) );
            }

            //Country of manufacture
            if ( isset( $_POST['_wf_stamps_manufacture_country'] ) ) {
                update_post_meta( $post_id, '_wf_stamps_manufacture_country', esc_attr( $_POST['_wf_stamps_manufacture_country'] ) );
            }
            
            if ( isset( $_POST['_wf_stamps_signature'] ) ) {
                update_post_meta( $post_id, '_wf_stamps_signature', esc_attr( $_POST['_wf_stamps_signature'] ) );
            }
        }

    }
    new WF_Common_Options();
}
