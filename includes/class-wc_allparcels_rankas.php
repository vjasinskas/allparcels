<?php
	class WC_allparcels_rankas extends WC_Shipping_Method {
		public function __construct($instance_id = 0) {
			$this->id                   = 'allparcels_rankas';
			$this->instance_id 			= absint( $instance_id );
			$this->method_title         = __( 'Allparcels į rankas', 'mancanweb' );
			$this->method_description   = __( 'Įgalina pristatymą kurjeriu.', 'mancanweb' );
			$this->supports             = array(
				'shipping-zones',
				'instance-settings',
				'instance-settings-modal'
			);
			$this->init();
		}

		public function init() {
			$this->init_form_fields();
			$this->init_settings();
			$this->title                = $this->get_option( 'title' );
			$this->tax_status           = $this->get_option( 'tax_status' );
			$this->cost                 = $this->get_option( 'cost' );
			$this->type                 = $this->get_option( 'type', 'class' );
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		public function init_form_fields() {
			$this->instance_form_fields = include( 'settings-rankas.php' );
		}

		protected function evaluate_cost( $sum, $args = array() ) {
			include_once( WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php' );
			$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
			$locale         = localeconv();
			$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
			$this->fee_cost = $args['cost'];
			add_shortcode( 'fee', array( $this, 'fee' ) );
			$sum = do_shortcode( str_replace(
				array(
					'[qty]',
					'[cost]',
				),
				array(
					$args['qty'],
					$args['cost'],
				),
				$sum
			) );
			remove_shortcode( 'fee', array( $this, 'fee' ) );
			$sum = preg_replace( '/\s+/', '', $sum );
			$sum = str_replace( $decimals, '.', $sum );
			$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );
			return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
		}

		public function fee( $atts ) {
			$atts = shortcode_atts( array(
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
			), $atts, 'fee' );
			$calculated_fee = 0;
			if ( $atts['percent'] )
				$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
			if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] )
				$calculated_fee = $atts['min_fee'];
			if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] )
				$calculated_fee = $atts['max_fee'];
			return $calculated_fee;
		}

		public function calculate_shipping( $package = array() ) {
			$rate = array(
				'id'      => $this->get_rate_id(),
				'label'   => $this->title,
				'cost'    => 0,
				'package' => $package,
			);
			$has_costs = false; // True when a cost is set. False if all costs are blank strings.
			$cost      = $this->get_option( 'cost' );
			if ( '' !== $cost ) {
				$has_costs    = true;
				$rate['cost'] = $this->evaluate_cost( $cost, array(
					'qty'  => $this->get_package_item_qty( $package ),
					'cost' => $package['contents_cost'],
				) );
			}
			$shipping_classes = WC()->shipping->get_shipping_classes();
			if ( ! empty( $shipping_classes ) ) {
				$found_shipping_classes = $this->find_shipping_classes( $package );
				$highest_class_cost     = 0;
				foreach ( $found_shipping_classes as $shipping_class => $products ) {
					$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
					$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );
					if ( '' === $class_cost_string )
						continue;
					$has_costs  = true;
					$class_cost = $this->evaluate_cost( $class_cost_string, array(
						'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
						'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
					) );
					if ( 'class' === $this->type )
						$rate['cost'] += $class_cost;
					else
						$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
				}
				if ( 'order' === $this->type && $highest_class_cost )
					$rate['cost'] += $highest_class_cost;
			}

			if ( ! WC()->cart->prices_include_tax )
				$amount = WC()->cart->cart_contents_total;
			else
				$amount = WC()->cart->cart_contents_total + WC()->cart->tax_total;

			$free_delivery_cost=$this->get_option( 'free_delivery_cost');

			if($amount >= $free_delivery_cost && $free_delivery_cost != '' && $free_delivery_cost != '0')
				$rate['cost'] = 0;

			// Add the rate
			if ( $has_costs )
				$this->add_rate( $rate );
			do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate );
		}

		public function get_package_item_qty( $package ) {
			$total_quantity = 0;
			foreach ( $package['contents'] as $item_id => $values )
				if ( $values['quantity'] > 0 && $values['data']->needs_shipping() )
					$total_quantity += $values['quantity'];
			return $total_quantity;
		}

		public function find_shipping_classes( $package ) {
			$found_shipping_classes = array();
			foreach ( $package['contents'] as $item_id => $values ) {
				if ( $values['data']->needs_shipping() ) {
					$found_class = $values['data']->get_shipping_class();
					if ( ! isset( $found_shipping_classes[ $found_class ] ) )
						$found_shipping_classes[ $found_class ] = array();

					$found_shipping_classes[ $found_class ][ $item_id ] = $values;
				}
			}
			return $found_shipping_classes;
		}
	}