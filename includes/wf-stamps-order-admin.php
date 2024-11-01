<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of wf_stamps_order_admin
 *
 * @author Akshay
 */
class wf_stamps_order_admin {

    public $package;
    public $order;
    public function __construct($order) {
        $this->order=$order;       
    }
    public function wf_get_package_from_order() {
        $order=$this->order;
        $orderItems = $order->get_items();
        $items = array();
        foreach ($orderItems as $orderItem) {
            $product_data = wc_get_product($orderItem['variation_id'] ? $orderItem['variation_id'] : $orderItem['product_id']);  
            if(WC()->version < '2.7.0'){
                $data = $orderItem;
            }else{
                $data = $orderItem->get_meta_data();
            }
            $mesured_weight = 0;
            if (isset($data[1]->value['weight']['value'])) {
                $mesured_weight = (wc_get_weight($data[1]->value['weight']['value'], $this->weight_unit, $data[1]->value['weight']['unit']));
            }
            $items[] = array('data' => $product_data, 'quantity' => $orderItem['qty'], 'mesured_weight' => $mesured_weight);
        }
        $package['contents'] = $items;
        $package['destination']['country'] = wf_get_order_shipping_country($order);
        $package['destination']['first_name'] = wf_get_order_shipping_first_name($order);
        $package['destination']['last_name'] = wf_get_order_shipping_last_name($order);
        $package['destination']['company'] = wf_get_order_shipping_company($order);
        $package['destination']['address_1'] = wf_get_order_shipping_address_1($order);
        $package['destination']['address_2'] = wf_get_order_shipping_address_2($order);
        $package['destination']['city'] = wf_get_order_shipping_city($order);
        $package['destination']['state'] = wf_get_order_shipping_state($order);
        $package['destination']['postcode'] = wf_get_order_shipping_postcode($order);
        $this->package = $package;
        return $package;
    }

    public function get_rates() {
        $this->wf_get_package_from_order();
        $stamps_obj= new WF_USPS_Stamps();
        $stamps_obj->tax_status='';
        error_log('get_ragtes');
        $stamps_obj->calculate_shipping($this->package);
        return $stamps_obj->rates;
    }

}
