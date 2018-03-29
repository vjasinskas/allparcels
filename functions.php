<?php function ap_plugin_path() {
 
  return untrailingslashit( plugin_dir_path( __FILE__ ) );
 
}
/**
 * Change default woocommerce cart-shipping template
 */
function ap_change_wc_template($template, $template_name, $template_path) {
    if ($template_name == 'cart/cart-shipping.php') {
        $template = ap_plugin_path().'/template/cart-shipping.php';
    }
    return $template;
}

add_filter('woocommerce_locate_template', 'ap_change_wc_template', 20, 3);


add_action('woocommerce_checkout_process', 'validate_extra_selection');
function validate_extra_selection() {

	switch ($_POST['shipping_method'][0]):
        case 'allparcels_pastomatas':
            if($_POST['allparcels_pastomatas'] == '' )
	            wc_add_notice( __('Pasirinkite paštomatą.', 'mancanweb'), 'error' );
            break;
		case 'allparcels_skyrius':
			if($_POST['allparcels_skyrius'] == '' )
				wc_add_notice( __('Pasirinkite pašto skyrių.', 'mancanweb'), 'error' );
			break;
		case 'allparcels_taskas':
			if($_POST['allparcels_taskas'] == '' )
				wc_add_notice( __('Pasirinkite atsiėmimo tašką.', 'mancanweb'), 'error' );
			break;
    endswitch;

}

add_action( 'woocommerce_checkout_update_order_meta', 'ap_custom_checkout_field_update_order_meta' );

function ap_custom_checkout_field_update_order_meta( $order_id ) {
	$packages = WC()->shipping->get_packages();
	$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    if ( isset($_POST['allparcels_pastomatas']) ):
        $obj = new WC_allparcels_pastomatas($packages[0]['rates'][$chosen_methods[0]]->instance_id);
        update_post_meta( $order_id, 'terminalui', sanitize_text_field( $_POST['allparcels_pastomatas'] ) );
	    update_post_meta( $order_id, 'express_paslauga', $obj->get_option('express_paslauga') );
	    update_post_meta( $order_id, 'pristatymas_sestadieni', $obj->get_option('pristatymas_sestadieni') );
	    update_post_meta( $order_id, 'dokumentu_grazinimas', $obj->get_option('dokumentu_grazinimas') );
	    update_post_meta( $order_id, 'informuoti_pastu', $obj->get_option('informuoti_pastu') );
	    update_post_meta( $order_id, 'informuoti_pastug', $obj->get_option('informuoti_pastug') );
	    update_post_meta( $order_id, 'informuoti_sms', $obj->get_option('informuoti_sms') );
	    update_post_meta( $order_id, 'informuoti_smsg', $obj->get_option('informuoti_smsg') );
	    update_post_meta( $order_id, 'savarankiskas', $obj->get_option('savarankiskas') );
    endif;

    if (  isset($_POST['allparcels_skyrius']) ):
	    $obj = new WC_allparcels_skyrius($packages[0]['rates'][$chosen_methods[0]]->instance_id);
        update_post_meta( $order_id, 'skyriui', sanitize_text_field( $_POST['allparcels_skyrius'] ) );
	    update_post_meta( $order_id, 'express_paslauga', $obj->get_option('express_paslauga') );
	    update_post_meta( $order_id, 'pristatymas_sestadieni', $obj->get_option('pristatymas_sestadieni') );
	    update_post_meta( $order_id, 'dokumentu_grazinimas', $obj->get_option('dokumentu_grazinimas') );
	    update_post_meta( $order_id, 'informuoti_pastu', $obj->get_option('informuoti_pastu') );
	    update_post_meta( $order_id, 'informuoti_pastug', $obj->get_option('informuoti_pastug') );
	    update_post_meta( $order_id, 'informuoti_sms', $obj->get_option('informuoti_sms') );
	    update_post_meta( $order_id, 'informuoti_smsg', $obj->get_option('informuoti_smsg') );
	    update_post_meta( $order_id, 'savarankiskas', $obj->get_option('savarankiskas') );
    endif;

    if (  isset($_POST['allparcels_taskas']) ):
	    $obj = new WC_allparcels_taskas($packages[0]['rates'][$chosen_methods[0]]->instance_id);
        update_post_meta( $order_id, 'taskui', sanitize_text_field( $_POST['allparcels_taskas'] ) );
	    update_post_meta( $order_id, 'express_paslauga', $obj->get_option('express_paslauga') );
	    update_post_meta( $order_id, 'pristatymas_sestadieni', $obj->get_option('pristatymas_sestadieni') );
	    update_post_meta( $order_id, 'dokumentu_grazinimas', $obj->get_option('dokumentu_grazinimas') );
	    update_post_meta( $order_id, 'informuoti_pastu', $obj->get_option('informuoti_pastu') );
	    update_post_meta( $order_id, 'informuoti_pastug', $obj->get_option('informuoti_pastug') );
	    update_post_meta( $order_id, 'informuoti_sms', $obj->get_option('informuoti_sms') );
	    update_post_meta( $order_id, 'informuoti_smsg', $obj->get_option('informuoti_smsg') );
	    update_post_meta( $order_id, 'savarankiskas', $obj->get_option('savarankiskas') );
	endif;

    if (  isset($_POST['allparcels_rankas']) ):
	    $obj = new WC_allparcels_rankas($packages[0]['rates'][$chosen_methods[0]]->instance_id);
        update_post_meta( $order_id, 'kurjeriui', sanitize_text_field( $_POST['allparcels_rankas'] ) );
	    update_post_meta( $order_id, 'express_paslauga', $obj->get_option('express_paslauga') );
	    update_post_meta( $order_id, 'pristatymas_sestadieni', $obj->get_option('pristatymas_sestadieni') );
	    update_post_meta( $order_id, 'dokumentu_grazinimas', $obj->get_option('dokumentu_grazinimas') );
	    update_post_meta( $order_id, 'informuoti_pastu', $obj->get_option('informuoti_pastu') );
	    update_post_meta( $order_id, 'informuoti_pastug', $obj->get_option('informuoti_pastug') );
	    update_post_meta( $order_id, 'informuoti_sms', $obj->get_option('informuoti_sms') );
	    update_post_meta( $order_id, 'informuoti_smsg', $obj->get_option('informuoti_smsg') );
	    update_post_meta( $order_id, 'savarankiskas', $obj->get_option('savarankiskas') );
	endif;
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'ap_custom_checkout_field_display_admin_order_meta', 10, 1 );

function ap_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Terminalas').':</strong> ' . get_post_meta( $order->id, 'terminalui', true ) . '</p>';
}

function ap_woo_custom_order_formatted_shipping_address  ($address, $wc_order) {
	$terminalui=get_post_meta ($wc_order->id, 'terminalui', true);
	$skyriui=get_post_meta ($wc_order->id, 'skyriui', true);
	$taskui=get_post_meta ($wc_order->id, 'taskui', true);
	$string='';
	if(!$terminalui and !$skyriui and !$taskui)
		return $address;
	else{
		global $wpdb;

		if($terminalui){
			$terminals=$wpdb->get_results( "SELECT * FROM wp_allparcels_terminals WHERE identifier='$terminalui';",ARRAY_A );
			$string=$terminals[0]['name'].' '.$terminals[0]['address'].' '.$terminals[0]['city'].' '.$terminals[0]['postCode'];

			$address['postcode'] = $address['postcode']."\n".__('Paštomatas: ','mancanweb').$string."\n";
		}else if($skyriui){
			$terminals=$wpdb->get_results( "SELECT * FROM wp_allparcels_terminals WHERE identifier='$skyriui';",ARRAY_A );
			$string.=$terminals[0]['name'].' '.$terminals[0]['address'].' '.$terminals[0]['city'].' '.$terminals[0]['postCode'];

			$address['postcode'] = $address['postcode']."\n".__('Pašto skyrius: ','mancanweb').$string."\n";
		}else if($taskui){
			$terminals=$wpdb->get_results( "SELECT * FROM wp_allparcels_terminals WHERE identifier='$taskui';",ARRAY_A );
			$string.=$terminals[0]['name'].' '.$terminals[0]['address'].' '.$terminals[0]['city'].' '.$terminals[0]['postCode'];

			$address['postcode'] = $address['postcode']."\n".__('Pristatymo punktas: ','mancanweb').$string."\n";
		}
		return $address;
	}
}
add_filter ( 'woocommerce_order_formatted_shipping_address', 'ap_woo_custom_order_formatted_shipping_address', 20,20);

function ap_custom_address_formats( $formats ) {
	$formats[ 'default' ]  = "{name}\n{company}\n{address_1} {address_2}\n{postcode} {city}";
	return $formats;
}
add_filter('woocommerce_localisation_address_formats', 'ap_custom_address_formats');

function ap_my_enqueue($hook) {
	wp_enqueue_script( 'ap_my_custom_script', plugin_dir_url( __FILE__ ) . 'js/admin-script.js' );
}
add_action( 'admin_enqueue_scripts', 'ap_my_enqueue' );

/**
 * Add bulk action to woocommerce orders list
 */

add_action('admin_footer-edit.php', 'ap_order_export_as_xml');

function ap_order_export_as_xml() {

    global $post_type;

    if($post_type == 'shop_order') {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('<option>').val('export').text('<?php _e('Export XML')?>').appendTo("select[name='action']");
            jQuery('<option>').val('export').text('<?php _e('Export XML')?>').appendTo("select[name='action2']");
        });
    </script>
    <?php
    }
}

add_action('load-edit.php', 'ap_custom_bulk_action_export');

function ap_custom_bulk_action_export() {
    global $typenow;
    $post_type = $typenow;

    if($post_type == 'shop_order') {

        $wp_list_table = _get_list_table('WP_Posts_List_Table');
        $action = $wp_list_table->current_action();

        $allowed_actions = array("export");
        if(!in_array($action, $allowed_actions)) return;

        check_admin_referer('bulk-posts');

        if(isset($_REQUEST['post']))
            $post_ids = array_map('intval', $_REQUEST['post']);

        if(empty($post_ids))
            return;

        $xml='<?xml version="1.0" encoding="UTF-8"?><shipments version="woocommerce_1.4">';
        foreach($post_ids as $id){
             $order = new WC_Order($id );

             if (get_post_meta( $id, '_payment_method', true )=='cod'){
                $value=$order->get_total();
                $currency=get_woocommerce_currency();
                $cod = '<cash_on_delivery> <value>'. $value .'</value><reference>'. $id .'</reference><currency>'. $currency .'</currency></cash_on_delivery>';
             } else {
                $cod = '';
             }

             if(get_post_meta( $id, 'terminalui', true ) != '')
	             $parcelIdentifier=get_post_meta( $id, 'terminalui', true );
             elseif(get_post_meta( $id, 'skyriui', true ) != '')
	             $parcelIdentifier=get_post_meta( $id, 'skyriui', true );
             elseif(get_post_meta( $id, 'skyriui', true ) != '')
	             $parcelIdentifier=get_post_meta( $id, 'skyriui', true );
             else
                 $parcelIdentifier='';

            $express=get_post_meta( $id, 'express_paslauga', true );
            $satdeliv=get_post_meta( $id, 'pristatymas_sestadieni', true );
            $dokgraz=get_post_meta( $id, 'dokumentu_grazinimas', true );
            $infsendemail=get_post_meta( $id, 'informuoti_pastu', true );
            $infsendsms=get_post_meta( $id, 'informuoti_pastug', true );
            $infrecemail=get_post_meta( $id, 'informuoti_sms', true );
            $infrecsms=get_post_meta( $id, 'informuoti_smsg', true );
            $dropoff=get_post_meta( $id, 'savarankiskas', true );

                $items = $order->get_items();
                $total_weight = 0;
                    foreach( $items as $item ) {
                        $item_metas = get_post_meta( $item['product_id'] );
                        $weight = $item_metas['_weight']['0'];
                        $quantity = $item['qty'];
                        $item_weight = ( $weight * $quantity );
                        $total_weight += $item_weight;
                    }
                    if($total_weight == 0){
                        $total_weight = 1;
                    }
             $xml .='<shipment>'.'<reference>'.$id.'</reference>'
             .'<weight>'.$total_weight.'</weight>'.
             '<remark>'.'</remark>'.
             '<additional_information>'.$order->customer_note.'</additional_information>'.
             '<number_of_parcels>1</number_of_parcels>'.
             '<courier_identifier>'.get_post_meta( $id, 'kurjeriui', true ).'</courier_identifier>'.
             '<receiver>'.'<name>'.$order->shipping_first_name.' '.$order->shipping_last_name.'</name>'.
             '<street>'.$order->shipping_address_1.'</street>'.
             '<postal_code>'.$order->shipping_postcode.'</postal_code>'.
             '<city>'.$order->shipping_city.'</city>'.
             '<phone>'.$order->billing_phone.'</phone>'.
             '<email>'.$order->billing_email.'</email>'.
             '<parcel_terminal_identifier>'.$parcelIdentifier.'</parcel_terminal_identifier>'.
             '<country_code>'.$order->shipping_country.'</country_code>'.'</receiver>'.
             '<services>'.$cod.'<express_delivery>'.$express.'</express_delivery>'.'<drop_off>'. $dropoff .'</drop_off>'.'<saturday_delivery>'.$satdeliv.
             '</saturday_delivery>'.'<document_return>'.$dokgraz.'</document_return>'.'<inform_sender_email>'.$infsendemail.'</inform_sender_email>'.
             '<inform_sender_sms>'.$infsendsms.'</inform_sender_sms>'.'<inform_receiver_email>'.$infrecemail.'</inform_receiver_email>'.'<inform_receiver_sms>'.$infrecsms.
             '</inform_receiver_sms>'.'</services></shipment>';
        }
        $xml .='</shipments>';
        header('Content-disposition: attachment; filename=export_file.xml');
        header ("Content-Type:text/xml");
        ob_clean();
        print($xml);
        exit();
    }
}

add_action('admin_notices', 'ap_custom_bulk_admin_notices_export');
function ap_custom_bulk_admin_notices_export() {
    global $post_type, $pagenow;

    if($pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_REQUEST['exported']) && (int) $_REQUEST['exported']) {
        $message = sprintf( _n( 'Post exported.', '%s posts exported.', $_REQUEST['exported'] ), number_format_i18n( $_REQUEST['exported'] ) );
        echo "<div class=\"updated\"><p>{$message}</p></div>";
    }
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'ap_register_plugin_styles' );
function ap_register_plugin_styles() {
    wp_register_style( 'allparcels', plugins_url().'/'.dirname( plugin_basename(__FILE__) ).'/css/style.css' );
    wp_enqueue_style( 'allparcels' );
}

add_action('plugins_loaded', 'ap_wan_load_textdomain');
function ap_wan_load_textdomain() {
    load_plugin_textdomain( 'mancanweb', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

?>