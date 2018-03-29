<?php
if (! defined ( 'ABSPATH' ))
	exit ();
/**
*Plugin Name: Allparcels.com
*Description: One place - all couriers
* Text Domain: mancanweb
* Domain Path: /languages
*@version: 2.1.0
*@author: mancanweb
*/

if (version_compare(PHP_VERSION, '5.5', '<'))
    exit(sprintf('Plugin requires PHP 5.5 or higher. You’re still on %s.',PHP_VERSION));

/**
 * Check if WooCommerce is active
 */

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	include(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/functions.php');
	include_once(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/includes/help_buttons.php');

	function allparcels_init() {

		if ( ! class_exists( 'WC_allparcels_rankas' ) ) {
			class WC_allparcels_rankas extends WC_Shipping_Method {
				/**
				 * Constructor. The instance ID is passed to this.
				 */
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
					$this->cost                 = $this->get_option( 'cost' );
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				public function init_form_fields() {
					$this->instance_form_fields = include( 'includes/settings-rankas.php' );
				}

				/**
				 * calculate_shipping function
				 * @param array $package (default: array())
				 */
				public function calculate_shipping( $package = array() ) {
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => $this->get_option( 'cost' ),
						'calc_tax' => 'per_item'
					);
					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}

		if ( ! class_exists( 'WC_allparcels_pastomatas' ) ) {
			class WC_allparcels_pastomatas extends WC_Shipping_Method {
				/**
				 * Constructor. The instance ID is passed to this.
				 */

				public function __construct($instance_id = 0) {
					$this->id                    = 'allparcels_pastomatas';
					$this->instance_id 			= absint( $instance_id );
					$this->method_title          = __( 'Allparcels į paštomatą', 'mancanweb' );
					$this->method_description    = __( 'Įgalina pristatymą į paštomatą', 'mancanweb' );
					$this->supports              = array(
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
					$this->cost                 = $this->get_option( 'cost' );

					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				public function init_form_fields() {
					$this->instance_form_fields = include( 'includes/settings-pastomatas.php' );
				}

				/**
				 * calculate_shipping function.
				 * @param array $package (default: array())
				 */
				public function calculate_shipping( $package = array() ) {
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => $this->get_option( 'cost' ),
						'calc_tax' => 'per_item'
					);
					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}

		if ( ! class_exists( 'WC_allparcels_skyrius' ) ) {
			class WC_allparcels_skyrius extends WC_Shipping_Method {
				/**
				 * Constructor. The instance ID is passed to this.
				 */
				public function __construct($instance_id = 0) {
					$this->id                    = 'allparcels_skyrius';
					$this->instance_id 			= absint( $instance_id );
					$this->method_title          = __( 'Allparcels į pašto skyrių', 'mancanweb' );
					$this->method_description    = __( 'Įgalina pristatymą į pašto skyrių', 'mancanweb' );
					$this->supports              = array(
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
					$this->cost                 = $this->get_option( 'cost' );
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				public function init_form_fields() {
					$this->instance_form_fields = include( 'includes/settings-skyrius.php' );
				}

				/**
				 * calculate_shipping function.
				 * @param array $package (default: array())
				 */
				public function calculate_shipping( $package = array() ) {
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => $this->get_option( 'cost' ),
						'calc_tax' => 'per_item'
					);
					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}

		if ( ! class_exists( 'WC_allparcels_taskas' ) ) {
			class WC_allparcels_taskas extends WC_Shipping_Method {
				/**
				 * Constructor. The instance ID is passed to this.
				 */
				public function __construct($instance_id = 0) {
					$this->id                    = 'allparcels_taskas';
					$this->instance_id 			 = absint( $instance_id );
					$this->method_title          = __( 'Allparcels į siuntų atsiėmimo tašką', 'mancanweb' );
					$this->method_description    = __( 'Įgalina pristatymą į siuntų atsiėmimo tašką', 'mancanweb' );
					$this->supports              = array(
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
					$this->cost                 = $this->get_option( 'cost' );
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				public function init_form_fields() {
					$this->instance_form_fields = include( 'includes/settings-taskas.php' );
				}

				/**
				 * calculate_shipping function.
				 * @param array $package (default: array())
				 */
				public function calculate_shipping( $package = array() ) {
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => $this->get_option( 'cost' ),
						'calc_tax' => 'per_item'
					);
					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}
	}
	add_action( 'woocommerce_shipping_init', 'allparcels_init' );

	function ap_add_allparcels( $methods ) {
		$methods['allparcels_rankas'] = 'WC_allparcels_rankas';
		$methods['allparcels_pastomatas'] = 'WC_allparcels_pastomatas';
		$methods['allparcels_skyrius'] = 'WC_allparcels_skyrius';
		$methods['allparcels_taskas'] = 'WC_allparcels_taskas';

		return $methods;
	}
	add_filter( 'woocommerce_shipping_methods', 'ap_add_allparcels' );



}