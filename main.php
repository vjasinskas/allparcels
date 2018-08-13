<?php
/**
*Plugin Name: Allparcels.com
*Description: One place - all couriers
* Text Domain: mancanweb
* Domain Path: /languages
*@version: 2.4.5
*@author: mancanweb
*/
if (! defined ( 'ABSPATH' ))
	exit ();

if (version_compare(PHP_VERSION, '5.5', '<'))
    exit(sprintf('Plugin requires PHP 5.5 or higher. You’re still on %s.',PHP_VERSION));

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	if ( ! class_exists( 'Terminals' ) )
		require(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/includes/terminals.php');

	include(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/functions.php');
	include_once(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/includes/help_buttons.php');

	function allparcels_init() {
		if ( ! class_exists( 'WC_allparcels_rankas' ) )
			require(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/includes/class-wc_allparcels_rankas.php');

		if ( ! class_exists( 'WC_allparcels_pastomatas' ) )
			require(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/includes/class-wc_allparcels_pastomatas.php');

		if ( ! class_exists( 'WC_allparcels_skyrius' ) )
			require(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/includes/class-wc_allparcels_skyrius.php');

		if ( ! class_exists( 'WC_allparcels_taskas' ) )
			require(untrailingslashit( plugin_dir_path( __FILE__ ) ).'/includes/class-wc_allparcels_taskas.php');
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