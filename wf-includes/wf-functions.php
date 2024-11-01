<?php
/**
 * Functions used by plugins
 */
if ( ! class_exists( 'Wf_Dependencies' ) )
	require_once 'class-wf-dependencies.php';

/**
 * WC Detection
 */
if ( ! function_exists( 'wf_is_woocommerce_active' ) ) {
	function wf_is_woocommerce_active() {
		return Wf_Dependencies::woocommerce_active_check();
	}
}
if (!function_exists('wf_get_settings_url')){
	function wf_get_settings_url(){
		return version_compare(WC()->version, '2.1', '>=') ? "wc-settings" : "woocommerce_settings";
	}
}
if(!function_exists('wf_get_order_id'))
{
function wf_get_order_id( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->id : $order->get_id();
}
}
if(!function_exists('wf_get_order_currency'))
{
function wf_get_order_currency($order){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->get_order_currency() : $order->get_currency();
}
}
if(!function_exists('wf_get_order_shipping_country'))
{
function wf_get_order_shipping_country( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_country : $order->get_shipping_country();
}
}

if(!function_exists('wf_get_order_shipping_first_name'))
{
function wf_get_order_shipping_first_name( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_first_name : $order->get_shipping_first_name();
}
}

if(!function_exists('wf_get_order_shipping_last_name'))
{
function wf_get_order_shipping_last_name( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_last_name : $order->get_shipping_last_name();
}
}
if(!function_exists('wf_get_order_shipping_company'))
{
function wf_get_order_shipping_company( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_company : $order->get_shipping_company();
}
}

if(!function_exists('wf_get_order_shipping_address_1'))
{
function wf_get_order_shipping_address_1( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_address_1 : $order->get_shipping_address_1();
}
}
if(!function_exists('wf_get_order_shipping_address_2'))
{
function wf_get_order_shipping_address_2( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_address_2 : $order->get_shipping_address_2();
}
}
if(!function_exists('wf_get_order_shipping_city'))
{
function wf_get_order_shipping_city( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_city : $order->get_shipping_city();
}
}
if(!function_exists('wf_get_order_shipping_state'))
{
function wf_get_order_shipping_state( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_state : $order->get_shipping_state();
}
}

if(!function_exists('wf_get_order_shipping_postcode'))
{
function wf_get_order_shipping_postcode( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->shipping_postcode : $order->get_shipping_postcode();
}
}

if(!function_exists('wf_get_order_billing_email'))
{
function wf_get_order_billing_email( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->billing_email : $order->get_billing_email();
}
}

if(!function_exists('wf_get_order_billing_phone'))
{
function wf_get_order_billing_phone( $order ){
	global $woocommerce;
	return ( WC()->version < '2.7.0' ) ? $order->billing_phone : $order->get_billing_phone();
}
}