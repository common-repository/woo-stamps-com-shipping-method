<?php
/**
 * Box Sizes for Stamps(USPS) in array format
 */
/*******************************************************************************     
 * WHILE UPDATING/ADDING A BOX DIMENTION PLEASE UPDATE THE BOX DIMENTION IN 
 * "stamps_ignore_invalid_box_rates" function of "class-wf-shipping-stamps.php"
 * TODO: Merge these one day.
 *******************************************************************************
 */
return array(
    'usps'=>array(
        array(
            "name"     => "Priority Mail Express Flat Rate Envelope",
            "max_weight"   => "70",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "0.75",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "0.75",
            "type"     => "envelope",
            "box_type" => "express",
        ),
        array(
            "name"     => "Priority Mail Express Legal Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "9.5",
            "outer_width"    => "15",
            "outer_height"   => "0.75",
            "inner_length"   => "9.5",
            "inner_width"    => "15",
            "inner_height"   => "0.75",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "express"
        ),
        array(
            "name"     => "Priority Mail Express Padded Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "1",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "1",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "express"
        ),

        // Priority Mail
        array(
            "name"     => "Priority Mail Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "0.75",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "0.75",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Medium Flat Rate Box - 2",
            "box_weight" => 0,
            "outer_length"   => "13.625",
            "outer_width"    => "11.875",
            "outer_height"   => "3.375",
            "inner_length"   => "13.625",
            "inner_width"    => "11.875",
            "inner_height"   => "3.375",
            "max_weight"   => "70",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Medium Flat Rate Box - 1",
            "box_weight" => 0,
            "outer_length"   => "11",
            "outer_width"    => "8.5",
            "outer_height"   => "5.5",
            "inner_length"   => "11",
            "inner_width"    => "8.5",
            "inner_height"   => "5.5",
            "max_weight"   => "70",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Large Flat Rate Box",
            "box_weight" => 0,
            "outer_length"   => "12",
            "outer_width"    => "12",
            "outer_height"   => "5.5",
            "inner_length"   => "12",
            "inner_width"    => "12",
            "inner_height"   => "5.5",
            "max_weight"   => "70",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Large Flat Rate Board Game Box",
            "box_weight" => 0,
            "outer_length"   => "23.69",
            "outer_width"    => "11.75",
            "outer_height"   => "3",
            "max_weight"   => "70",
            "inner_length"   => "23.69",
            "inner_width"    => "11.75",
            "inner_height"   => "3",
            "max_weight"   => "70",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Small Flat Rate Box",
            "box_weight" => 0,
            "outer_length"   => "5.375",
            "outer_width"    => "8.625",
            "outer_height"   => "1.625",
            "inner_length"   => "5.375",
            "inner_width"    => "8.625",
            "inner_height"   => "1.625",
            "max_weight"   => "70",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Padded Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "1",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "1",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Gift Card Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "10",
            "outer_width"    => "7",
            "outer_height"   => "0.75",
            "inner_length"   => "10",
            "inner_width"    => "7",
            "inner_height"   => "0.75",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Window Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "5",
            "outer_width"    => "10",
            "outer_height"   => "0.75",
            "inner_length"   => "5",
            "inner_width"    => "10",
            "inner_height"   => "0.75",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Small Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "6",
            "outer_width"    => "10",
            "outer_height"   => "0.75",
            "inner_length"   => "6",
            "inner_width"    => "10",
            "inner_height"   => "0.75",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Legal Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "9.5",
            "outer_width"    => "15",
            "outer_height"   => "0.75",
            "inner_length"   => "9.5",
            "inner_width"    => "15",
            "inner_height"   => "0.75",
            "max_weight"   => "70",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
                array(
            "name"     => "Priority Mail Regional Rate Box A",
            "box_weight" => 0,
            "outer_length"   => "10",
            "outer_width"    => "7",
            "outer_height"   => "4.75",
            "inner_length"   => "10",
            "inner_width"    => "7",
            "inner_height"   => "4.75",
            "max_weight"   => "15",
            "box_type" => "priority"
        ),
                array(
            "name"     => "Priority Mail Regional Rate Box B",
            "box_weight" => 0,
            "outer_length"   => "12",
            "outer_width"    => "10.25",
            "outer_height"   => "5",
            "inner_length"   => "12",
            "inner_width"    => "10.25",
            "inner_height"   => "5",
            "max_weight"   => "20",
            "box_type" => "priority"
        ),

        // International Priority Mail Express
        array(
            "name"     => "Priority Mail Express International Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "0.75",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "0.75",
            "max_weight"   => "4",
            "type"     => "envelope",
            "box_type" => "express"
        ),
        array(
            "name"     => "Priority Mail Express International Legal Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "9.5",
            "outer_width"    => "15",
            "outer_height"   => "0.75",
            "inner_length"   => "9.5",
            "inner_width"    => "15",
            "inner_height"   => "0.75",
            "max_weight"   => "4",
            "type"     => "envelope",
            "box_type" => "express"
        ),
        array(
            "name"     => "Priority Mail Express International Padded Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "1",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "1",
            "max_weight"   => "4",
            "type"     => "envelope",
            "box_type" => "express"
        ),

        // International Priority Mail
        array(
            "name"     => "Priority Mail International Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "0.75",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "0.75",
            "max_weight"   => "4",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail Express International Padded Flat Rate Envelope",
            "box_weight" => 0,
            "outer_length"   => "12.5",
            "outer_width"    => "9.5",
            "outer_height"   => "1",
            "inner_length"   => "12.5",
            "inner_width"    => "9.5",
            "inner_height"   => "1",
            "max_weight"   => "4",
            "type"     => "envelope",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail International Small Flat Rate Box",
            "box_weight" => 0,
            "outer_length"   => "5.375",
            "outer_width"    => "8.625",
            "outer_height"   => "1.625",
            "inner_length"   => "5.375",
            "inner_width"    => "8.625",
            "inner_height"   => "1.625",
            "max_weight"   => "4",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail International Medium Flat Rate Box - 2",
            "box_weight" => 0,
            "outer_length"   => "11.875",
            "outer_width"    => "13.625",
            "outer_height"   => "3.375",
            "inner_length"   => "11.875",
            "inner_width"    => "13.625",
            "inner_height"   => "3.375",
            "max_weight"   => "20",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail International Medium Flat Rate Box - 1",
            "box_weight" => 0,
            "outer_length"   => "11",
            "outer_width"    => "8.5",
            "outer_height"   => "5.5",
            "inner_length"   => "11",
            "inner_width"    => "8.5",
            "inner_height"   => "5.5",
            "max_weight"   => "70",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail International Large Flat Rate Box",
            "box_weight" => 0,
            "outer_length"   => "12",
            "outer_width"    => "12",
            "outer_height"   => "5.5",
            "inner_length"   => "12",
            "inner_width"    => "12",
            "inner_height"   => "5.5",
            "max_weight"   => "20",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail International DVD Flat Rate priced box",
            "box_weight" => 0,
            "outer_length"   => "7.56",
            "outer_width"    => "5.43",
            "outer_height"   => "0.75",
            "inner_length"   => "7.56",
            "inner_width"    => "5.43",
            "inner_height"   => "0.75",
            "max_weight"   => "4",
            "box_type" => "priority"
        ),
        array(
            "name"     => "Priority Mail International Large Video Flat Rate priced box",
            "box_weight" => 0,
            "outer_length"   => "9.25",
            "outer_width"    => "6.25",
            "outer_height"   => "2",
            "inner_length"   => "9.25",
            "inner_width"    => "6.25",
            "inner_height"   => "2",
            "max_weight"   => "4",
            "box_type" => "priority"
        ),
    )
);