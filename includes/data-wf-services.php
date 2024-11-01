<?php

/**
 * USPS Services and subservices
 */
return array(
	// Domestic
	'US-FC' => array(
		// Name of the service shown to the user
		'name'  => 'First-Class Mail&#0174;',
		'package_types'  =>  array(
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
            'Postcard'                  => __( 'Postcard.', 'wf-usps-stamps-woocommerce' ),
            'Large Envelope or Flat'    => __( 'Large Envelope or Flat.', 'wf-usps-stamps-woocommerce' ),
            'Thick Envelope'            => __( 'Thick Envelope.', 'wf-usps-stamps-woocommerce' ),
            'Large Package'             => __( 'Large Package.', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-XM' => array(
		// Name of the service shown to the user
		'name'  => 'Priority Mail Express&#8482;',
		'package_types'  =>  array(	
			'Flat Rate Envelope'        => __( 'USPS flat rate envelope. A special cardboard envelope provided by the USPS that clearly indicates "Flat Rate".', 'wf-usps-stamps-woocommerce' ),
			'Flat Rate Padded Envelope' => __( 'USPS flat rate padded envelope.', 'wf-usps-stamps-woocommerce' ),
			'Letter'                    => __( 'Letter', 'wf-usps-stamps-woocommerce' ),
			'Large Envelope or Flat'    => __( 'Large envelope or flat. Has one dimension that is between 11 1/2" and 15" long, 6 1/8" and 12" high, or 1/4" and 3/4 thick.', 'wf-usps-stamps-woocommerce' ),
			'Large Package'             => __( 'Large package. Longest side plus the distance around the thickest part is over 84" and less than or equal to 108".', 'wf-usps-stamps-woocommerce' ),
			'Legal Flat Rate Envelope'  => __( 'USPS Legal Flat Rate Envelope.', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),			
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-MM' => array(
		// Name of the service shown to the user
		'name'  => 'Media Mail Parcel',
		'package_types'  => array(
			'Large Envelope or Flat'    => __( 'Large envelope or flat. Has one dimension that is between 11 1/2" and 15" long, 6 1/8" and 12" high, or 1/4" and 3/4 thick.', 'wf-usps-stamps-woocommerce' ),
			'Large Package'             => __( 'Large package. Longest side plus the distance around the thickest part is over 84" and less than or equal to 108".', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-LM' => array(
		// Name of the service shown to the user
		'name'  => "Library Mail",
		'package_types'  =>  array(
			'Large Envelope or Flat'    => __( 'Large envelope or flat. Has one dimension that is between 11 1/2" and 15" long, 6 1/8" and 12" high, or 1/4" and 3/4 thick.', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-PP' => array(
		// Name of the service shown to the user
		'name'  => "USPS Parcel Post",
		'package_types'  =>  array(
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-PS' => array(
		// Name of the service shown to the user
		'name'  => "USPS Parcel Select",
		'package_types'  =>  array(			
			'Large Package'             => __( 'Large package. Longest side plus the distance around the thickest part is over 84" and less than or equal to 108".', 'wf-usps-stamps-woocommerce' ),
			'Oversized Package'         => __( 'Oversized package. Longest side plus the distance around the thickest part is over 108" and less than or equal to 130".', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-CM' => array(
		// Name of the service shown to the user
		'name'  => "USPS Critical Mail",
		'package_types'  =>  array(
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-PM' => array(
		// Name of the service shown to the user
		'name'  => "Priority Mail&#0174;",
		'package_types'  =>array(
			'Flat Rate Box'             => __( 'USPS medium flat rate box. A special 11" x 8 1/2" x 5 1/2" or 14" x 3.5" x 12" USPS box that clearly indicates "Medium Flat Rate Box"', 'wf-usps-stamps-woocommerce' ),
			'Flat Rate Envelope'        => __( 'USPS flat rate envelope. A special cardboard envelope provided by the USPS that clearly indicates "Flat Rate".', 'wf-usps-stamps-woocommerce' ),
			'Flat Rate Padded Envelope' => __( 'USPS flat rate padded envelope.', 'wf-usps-stamps-woocommerce' ),
			'Letter'                    => __( 'Letter', 'wf-usps-stamps-woocommerce' ),
			'Large Envelope or Flat'    => __( 'Large envelope or flat. Has one dimension that is between 11 1/2" and 15" long, 6 1/8" and 12" high, or 1/4" and 3/4 thick.', 'wf-usps-stamps-woocommerce' ),
		        'Large Flat Rate Box'   => __( 'USPS large flat rate box. A special 12" x 12" x 6" USPS box that clearly indicates "Large Flat Rate Box".', 'wf-usps-stamps-woocommerce' ),	
			'Large Package'             => __( 'Large package. Longest side plus the distance around the thickest part is over 84" and less than or equal to 108".', 'wf-usps-stamps-woocommerce' ),
			'Legal Flat Rate Envelope'  => __( 'Legal Flat Rate Envelope. USPS flat rate padded envelope.', 'wf-usps-stamps-woocommerce' ),
			
			'Oversized Package'         => __( 'Oversized package. Longest side plus the distance around the thickest part is over 108" and less than or equal to 130".', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),	
			'Regional Rate Box A'       => __( 'USPS regional rate box A. A special 10 15/16" x 2 3/8" x 12 13/ 16" or 10" x 7" x 4 3/4" USPS box that clearly indicates "Regional Rate Box A". 15 lbs maximum weight.', 'wf-usps-stamps-woocommerce' ),
			'Regional Rate Box B'       => __( 'USPS regional rate box B. A special 14 3/8" x 2 2/8" x 15 7/8" or 12" x 10 1/4" x 5" USPS box that clearly indicates "Regional Rate Box B". 20 lbs maximum weight.', 'wf-usps-stamps-woocommerce' ),
			'Small Flat Rate Box'       => __( 'USPS small flat rate box. A special 8-5/8" x 5-3/8" x 1-5/8" USPS box that clearly indicates "Small Flat Rate Box".', 'wf-usps-stamps-woocommerce' ),
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	),

	// International
	'US-EMI' => array(
		// Name of the service shown to the user
		'name'  => "Priority Mail Express International&#8482;",
		'package_types'  =>  array(
			'Flat Rate Envelope'        => __( 'USPS flat rate envelope. A special cardboard envelope provided by the USPS that clearly indicates "Flat Rate".', 'wf-usps-stamps-woocommerce' ),
			'Flat Rate Padded Envelope' => __( 'USPS flat rate padded envelope.', 'wf-usps-stamps-woocommerce' ),
			'Large Envelope or Flat'    => __( 'Large envelope or flat. Has one dimension that is between 11 1/2" and 15" long, 6 1/8" and 12" high, or 1/4" and 3/4 thick.', 'wf-usps-stamps-woocommerce' ),
			'Large Package'             => __( 'Large package. Longest side plus the distance around the thickest part is over 84" and less than or equal to 108".', 'wf-usps-stamps-woocommerce' ),
			'Legal Flat Rate Envelope'  => __( 'USPS flat rate padded envelope.', 'wf-usps-stamps-woocommerce' ),
			'Oversized Package'         => __( 'Oversized package. Longest side plus the distance around the thickest part is over 108" and less than or equal to 130".', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),	
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-PMI' => array(
		// Name of the service shown to the user
		'name'  => "Priority Mail International&#0174;",
		'package_types'  =>  array(
			
			'Flat Rate Box'             => __( 'USPS medium flat rate box. A special 11" x 8 1/2" x 5 1/2" or 14" x 3.5" x 12" USPS box that clearly indicates "Medium Flat Rate Box"', 'wf-usps-stamps-woocommerce' ),
			'Flat Rate Envelope'        => __( 'USPS flat rate envelope. A special cardboard envelope provided by the USPS that clearly indicates "Flat Rate".', 'wf-usps-stamps-woocommerce' ),
			'Flat Rate Padded Envelope' => __( 'USPS flat rate padded envelope.', 'wf-usps-stamps-woocommerce' ),
			'Large Envelope or Flat'    => __( 'Large envelope or flat. Has one dimension that is between 11 1/2" and 15" long, 6 1/8" and 12" high, or 1/4" and 3/4 thick.', 'wf-usps-stamps-woocommerce' ),
			'Large Flat Rate Box'       => __( 'USPS large flat rate box. A special 12" x 12" x 6" USPS box that clearly indicates "Large Flat Rate Box".', 'wf-usps-stamps-woocommerce' ),
			'Large Package'             => __( 'Large package. Longest side plus the distance around the thickest part is over 84" and less than or equal to 108".', 'wf-usps-stamps-woocommerce' ),
			'Legal Flat Rate Envelope'  => __( 'USPS flat rate padded envelope.', 'wf-usps-stamps-woocommerce' ),
			'Oversized Package'         => __( 'Oversized package. Longest side plus the distance around the thickest part is over 108" and less than or equal to 130".', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
			'Small Flat Rate Box'       => __( 'USPS small flat rate box. A special 8-5/8" x 5-3/8" x 1-5/8" USPS box that clearly indicates "Small Flat Rate Box".', 'wf-usps-stamps-woocommerce' ),
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	),
	'US-FCI' => array(
		// Name of the service shown to the user
		'name'  => "First Class Package Service&#8482; International",
		'package_types'  =>  array(
			'Letter'                    => __( 'Letter', 'wf-usps-stamps-woocommerce' ),
			'Large Envelope or Flat'    => __( 'Large envelope or flat. Has one dimension that is between 11 1/2" and 15" long, 6 1/8" and 12" high, or 1/4" and 3/4 thick.', 'wf-usps-stamps-woocommerce' ),
			'Large Package'             => __( 'Large package. Longest side plus the distance around the thickest part is over 84" and less than or equal to 108".', 'wf-usps-stamps-woocommerce' ),
		        'Oversized Package'         => __( 'Oversized package. Longest side plus the distance around the thickest part is over 108" and less than or equal to 130".', 'wf-usps-stamps-woocommerce' ),
			'Package'                   => __( 'Package. Longest side plus the distance around the thickest part is less than or equal to 84"', 'wf-usps-stamps-woocommerce' ),
			'Thick Envelope'            => __( 'Thick envelope. Envelopes or flats greater than 3/4" at the thickest point.', 'wf-usps-stamps-woocommerce' ),
		)
	)
);