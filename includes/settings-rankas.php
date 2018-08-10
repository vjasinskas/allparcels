<?php
if (! defined ( 'ABSPATH' ))
    exit ();
$temp = Terminals::carriersArray ();
/**
 * Settings for allparcels courriers
 */
if(get_option( 'allparcels_api' )!==''){
	$terminalList = json_decode ( Terminals::getTerminalsList (), true );
	$terminalArray = [ ];
	if(isset($terminalList))
		foreach ( $terminalList  as $masyvas )
		    if (! in_array ( $masyvas ['courierIdentifier'], $terminalArray ))
		        array_push ( $terminalArray, $masyvas ['courierIdentifier'] );

	$kurjeriaiArray = [ ];
	foreach ( $terminalArray as $value )
	    $kurjeriaiArray[$value]=$temp[$value];
}
$settings = array (
        'title' => array (
                'title' => __ ( 'Pavadinimas', 'mancanweb' ),
                'type' => 'text',
                'description' => __ ( 'Pavadinimas matosi atsiskaitymo puslapyje.', 'mancanweb' ),
                'default' => __ ( 'Kurjeris', 'mancanweb' ),
                'desc_tip' => true 
        ),
        'tax_status' => array(
	        'title' 		=> __( 'Tax status', 'woocommerce' ),
	        'type' 			=> 'select',
	        'class'         => 'wc-enhanced-select',
	        'default' 		=> 'taxable',
	        'options'		=> array(
		        'taxable' 	=> __( 'Taxable', 'woocommerce' ),
		        'none' 		=> _x( 'None', 'Tax status', 'woocommerce' ),
	        ),
        ),
        'cost' => array (
                'title' => __ ( 'Kaina', 'mancanweb' ),
                'type' => 'text',
                'placeholder' => '',
                'description' => $cost_desc,
                'default' => '',
                'desc_tip' => true 
        ),
        'free_delivery_cost' => array(
	        'title' => __ ( 'Nemokamas pristatymas', 'mancanweb' ),
	        'type' => 'text',
	        'description' => __ ( 'Nemokamas pristatymas bus pritaikytas nuo įvestos sumos. (Laukelį palikti tuščią, norint nenaudoti šios funkcijos)', 'mancanweb' ),
	        'default' => '',
	        'desc_tip' => true
        )
);

$shipping_classes = WC()->shipping->get_shipping_classes();
if ( ! empty( $shipping_classes ) ) {
	$settings['class_costs'] = array(
		'title'			 => __( 'Shipping class costs', 'woocommerce' ),
		'type'			 => 'title',
		'default'        => '',
		'description'    => sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
	);
	foreach ( $shipping_classes as $shipping_class ) {
		if ( ! isset( $shipping_class->term_id ) )
			continue;

		$settings[ 'class_cost_' . $shipping_class->term_id ] = array(
			/* translators: %s: shipping class name */
			'title'       => sprintf( __( '"%s" shipping class cost', 'woocommerce' ), esc_html( $shipping_class->name ) ),
			'type'        => 'text',
			'placeholder' => __( 'N/A', 'woocommerce' ),
			'description' => $cost_desc,
			'default'     => $this->get_option( 'class_cost_' . $shipping_class->slug ), // Before 2.5.0, we used slug here which caused issues with long setting names
			'desc_tip'    => true,
		);
	}
	$settings['no_class_cost'] = array(
		'title'       => __( 'No shipping class cost', 'woocommerce' ),
		'type'        => 'text',
		'placeholder' => __( 'N/A', 'woocommerce' ),
		'description' => $cost_desc,
		'default'     => '',
		'desc_tip'    => true,
	);
	$settings['type'] = array(
		'title' 		=> __( 'Calculation type', 'woocommerce' ),
		'type' 			=> 'select',
		'class'         => 'wc-enhanced-select',
		'default' 		=> 'class',
		'options' 		=> array(
			'class' 	=> __( 'Per class: Charge shipping for each shipping class individually', 'woocommerce' ),
			'order' 	=> __( 'Per order: Charge shipping for the most expensive shipping class', 'woocommerce' ),
		),
	);
}

if(get_option( 'allparcels_api' )!=='')
    $settings['pagrindinis_kurjeris'] = array (
            'title' => __ ( 'Kurjeris', 'mancanweb' ),
            'type' => 'select',
            'default' => '',
            'class' => '',
            'css' => '',
            'label' => '',
            'options' => $kurjeriaiArray
    );
else
    $settings['pagrindinis_kurjeris'] = array (
            'title' => __ ( 'Kurjeris', 'mancanweb' ),
            'type' => 'select',
            'default' => '',
            'class' => '',
            'css' => 'width:200px;',
            'label' => '',
            'options' => array(
                'error'=> __('Klaida: įveskite API kodą','mancanweb')
                )
    );

$settings['express_paslauga'] = array (
        'title' => __ ( 'Express paslauga', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);
$settings['pristatymas_sestadieni'] = array (
        'title' => __ ( 'Pristatymas šeštadienį', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);
$settings['dokumentu_grazinimas'] = array (
        'title' => __ ( 'Dokumentų grąžinimas', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);
$settings['savarankiskas'] = array (
        'title' => __ ( 'Savarankiškas siuntos padėjimas į terminalą', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);
$settings['informuoti_pastu'] = array (
        'title' => __ ( 'Informuoti siuntėją apie siuntą el.paštu', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);
$settings['informuoti_sms'] = array (
        'title' => __ ( 'Informuoti siuntėją apie siuntą sms', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);
$settings['informuoti_pastug'] = array (
        'title' => __ ( 'Informuoti gavėją apie siuntą el.paštu', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);
$settings['informuoti_smsg'] = array (
        'title' => __ ( 'Informuoti gavėją apie siuntą sms', 'mancanweb' ),
        'type' => 'select',
        'default' => 'false',
        'class' => '',
        'css' => '',
        'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
        'desc_tip' => true ,
        'label' => '',
        'options' => array (
                'true' => __('Taip','mancanweb'),
                'false' => __('Ne','mancanweb')
        )
);

return $settings;