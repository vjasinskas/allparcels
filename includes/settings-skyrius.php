<?php
if (! defined ( 'ABSPATH' )) {
    exit ();
}
include_once ('terminals.php');
$temp = Terminals::carriersArray ();

/**
 * Settings for allparcels terminals
 */
if(get_option( 'allparcels_api' )!==''){
$terminalList = json_decode ( Terminals::getTerminalsList (), true );
$terminalArray = [ ];

foreach ( $terminalList  as $masyvas ) {
    if (! in_array ( $masyvas ['courierIdentifier'], $terminalArray )) {
      if($masyvas['type']==2)  array_push ( $terminalArray, $masyvas ['courierIdentifier'] );
    }
}

$kurjeriaiArray = [ ];
foreach ( $terminalArray as $value ) {

    $kurjeriaiArray[$value]=$temp[$value];
}
}
$settings = array (

        'title' => array (
                'title' => __ ( 'Pavadinimas', 'mancanweb' ),
                'type' => 'text',
                'description' => __ ( 'Pavadinimas matosi atsiskaitymo puslapyje.', 'mancanweb' ),
                'default' => __ ( 'Pašto skyriai', 'mancanweb' ),
                'desc_tip' => true 
        ),
        'cost' => array (
                'title' => __ ( 'Kaina', 'mancanweb' ),
                'type' => 'text',
                'placeholder' => '',
                'description' => $cost_desc,
                'default' => '',
                'desc_tip' => true 
        ) 
);
if(get_option( 'allparcels_api' )!==''){
$ab = array (
        'kurjeris' => array (
                'title' => __ ( 'Kurjeris', 'mancanweb' ),
                'type' => 'multiselect',
                'description' => __ ( 'Norėdami pasirinkti daugiau nei vieną kurjerį, laikykite įspaudę CTRL.', 'mancanweb' ),
                'default' => '',
                'class' => '',
                'css' => 'width:200px;',
                'label' => '',
                'options' => $kurjeriaiArray 
        ) 
);
$settings = array_merge ( $settings, $ab );
}
else {

    $ab = array (
        'kurjeris' => array (
                'title' => __ ( 'Kurjeris', 'mancanweb' ),
                'type' => 'multiselect',
                'default' => '',
                'class' => '',
                'css' => 'width:200px;',
                'label' => '',
                'options' => array(
                    'error'=> __('Klaida: įveskite API kodą','mancanweb')
                    )
        ) 
);
$settings = array_merge ( $settings, $ab );
}

$ab = array (
        'express_paslauga' => array (
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
        ) 
);
$settings = array_merge ( $settings, $ab );
$ab = array (
        'pristatymas_sestadieni' => array (
                'title' => __ ( 'Pristatymas šeštadienį', 'mancanweb' ),
                'type' => 'select',
                'default' => 'false',
                'class' => '',
                'css' => '',
                'label' => '',
                'description' => __ ( 'Paslauga gali papildomai kainuoti.', 'mancanweb' ),
                'desc_tip' => true ,
                'options' => array (
                        'true' => __('Taip','mancanweb'),
                        'false' => __('Ne','mancanweb')  
                ) 
        ) 
);
$settings = array_merge ( $settings, $ab );
$ab = array (
        'dokumentu_grazinimas' => array (
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
        ) 
);
$settings = array_merge ( $settings, $ab );
$ab = array (
        'savarankiskas' => array (
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
        ) 
);
$settings = array_merge ( $settings, $ab );
$ab = array (
        'informuoti_pastu' => array (
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
        ) 
);
$settings = array_merge ( $settings, $ab );
$ab = array (
        'informuoti_sms' => array (
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
        ) 
);
$settings = array_merge ( $settings, $ab );
$ab = array (
        'informuoti_pastug' => array (
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
        ) 
);
$settings = array_merge ( $settings, $ab );
$ab = array (
        'informuoti_smsg' => array (
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
        ) 
);
$settings = array_merge ( $settings, $ab );


return $settings;
