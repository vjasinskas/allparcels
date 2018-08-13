<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="shipping">
    <th><?php echo wp_kses_post( $package_name ); ?></th>
    <td data-title="<?php echo esc_attr( $package_name ); ?>">
		<?php if ( 1 <= count( $available_methods ) ) : ?>
            <ul id="shipping_method">
				<?php foreach ( $available_methods as $method ) : ?>
					<?php
                        if(in_array($method->method_id, array('allparcels_rankas', 'allparcels_skyrius', 'allparcels_taskas', 'allparcels_pastomatas')) ){
                            if(get_option( 'allparcels_api') == '') {
                                if($chosen_method == $method->id)
                                    echo
                                        '<li><select name="'.$method->id.'" id="'.$method->id.'" required>
                                            <option value="">'.__('Klaida: įveskite API kodą', 'mancanweb').'</option>
                                        </select>';
                                continue;
                            }else{
                                switch ($method->method_id):
                                    case 'allparcels_rankas':
                                        $allparcels_obj = new WC_allparcels_rankas($method->instance_id);
                                        $type='*';
                                        break;
                                    case 'allparcels_skyrius':
                                        $allparcels_obj = new WC_allparcels_skyrius($method->instance_id);
                                        $type='2';
                                        break;
                                    case 'allparcels_taskas':
                                        $allparcels_obj = new WC_allparcels_taskas($method->instance_id);
                                        $type='3';
                                        break;
                                    case 'allparcels_pastomatas':
                                        $allparcels_obj = new WC_allparcels_pastomatas($method->instance_id);
                                        $type='1';
                                        break;
                                endswitch;

                                if( (empty($allparcels_obj->get_option('kurjeris')) && $method->method_id != 'allparcels_rankas' ) || ( empty($allparcels_obj->get_option('pagrindinis_kurjeris')) && $method->method_id == 'allparcels_rankas' ) )
                                    continue;

	                            printf( '<li><input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method allparcels_input" %4$s />
                                    <label for="shipping_method_%1$d_%2$s">%5$s</label>',
		                            $index, sanitize_title( $method->id ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ), wc_cart_totals_shipping_method_label( $method ) );

	                            do_action( 'woocommerce_after_shipping_rate', $method, $index );

                                $couriers = Terminals::carriersArray ();
                                $pastomatai = [];

                                if (WC ()->customer->get_shipping_city () !== '')
                                    $miestas = WC ()->customer->get_shipping_city ();
                                else
                                    $miestas = WC ()->customer->get_city ();

                                if( $allparcels_obj->get_option('kurjeris') != '')
                                    foreach ( $allparcels_obj->get_option('kurjeris') as $value ) {
                                        $terminals=Terminals::getListForSelect($value, $miestas, $type);
                                        foreach($terminals as $terminal)
                                            $pastomatai [ $terminal ['identifier'] ] = $terminal;
                                    }

                                if($chosen_method == $method->id) {
                                    $string='';
	                                if ( sizeof( $pastomatai ) > 0  && $type != '*') {
		                                $string .= '<select name="' . $method->method_id . '" id="input_' . $method->method_id . '" class="allparcels_select" required>';
		                                $string .= '<option value="">' . __( "Pasirinkite...", "mancanweb" ) . '</option>';
		                                foreach ( $pastomatai as $key => $value ) {
			                                $string .= '<option value="' . $key . '___' . $value['courierIdentifier'] . '">' .
			                                           $couriers [ $value ['courierIdentifier'] ] . ' \'' . $value ['name'] . '\' ' . $value ['address']
			                                           . ' ,' . $value ['postCode'] . ', ' . $value ['city'] . '</option>';
		                                }
		                                $string .= '</select>';
		                                printf( $string );
	                                }elseif ($type == '*')
	                                    echo '<input type="hidden" name="'.$method->method_id.'" id="input_'.$method->method_id.'" value="'.$allparcels_obj->get_option('pagrindinis_kurjeris').'" />';
                                    else{
                                        $string .='<select name="'.$method->method_id.'" id="input_'.$method->method_id.'"  required>';
                                        $string .='<option value="">'.__('Nurodytame mieste šis pristatymas negalimas.', 'mancanweb').'</option>';
                                        $string .= '</select>';
                                        printf($string);
                                    }
                                }


                            }
                        }else{
	                        printf( '<li><input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />
                                    <label for="shipping_method_%1$d_%2$s">%5$s</label>',
		                        $index, sanitize_title( $method->id ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ), wc_cart_totals_shipping_method_label( $method ) );
	                        do_action( 'woocommerce_after_shipping_rate', $method, $index );
                        }
					?>
                    </li>
				<?php endforeach; ?>
            </ul>
		<?php
            elseif ( WC()->customer->has_calculated_shipping() ) :
                echo apply_filters( is_cart() ? 'woocommerce_cart_no_shipping_available_html' : 'woocommerce_no_shipping_available_html', wpautop( __( 'There are no shipping methods available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
            elseif ( ! is_cart() ) :
                echo wpautop( __( 'Enter your full address to see shipping costs.', 'woocommerce' ) );
            endif;

            if ( $show_package_details )
                echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>';
            if ( ! empty( $show_shipping_calculator ) )
                woocommerce_shipping_calculator();
        ?>
    </td>
</tr>