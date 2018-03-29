<?php 

	add_filter( 'plugin_row_meta', 'allparcels_plugin_row_meta', 10, 2 );

	function allparcels_plugin_row_meta( $links, $file ) {

		if ( dirname( dirname( plugin_basename(__FILE__) )) == dirname( $file ) ) {
			$new_links = array(
					'doc' => '<a href="" target="_blank">'.__('Dokumentacija', 'mancanweb').'</a>'
					);
			$links = array_merge( $links, $new_links );
		}
		
		return $links;
	}

	add_filter( 'plugin_action_links_'.dirname( dirname( plugin_basename(__FILE__) )).'/main.php', 'ap_add_plugin_action_links' );
	function ap_add_plugin_action_links( $links ) {
		return array_merge(
			array(
				'settings1' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=allparcels-settings">' .__('Nustatymai', 'mancanweb').'</a>'
			),
			$links
		);
	}

	function ap_activation_redirect( $plugin ) {
	    if( $plugin == dirname( dirname( plugin_basename(__FILE__) )).'/main.php' ) {
	        exit( wp_redirect( get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=allparcels-settings' ) );
	    }
	}
	add_action( 'activated_plugin', 'ap_activation_redirect' );

	function ap_register_plugin_settings() {
	//register our settings
	register_setting( 'ap-plugin-settings-group', 'allparcels_api' );
	register_setting( 'ap-plugin-settings-group', 'allparcels_terminals' );
	register_setting( 'ap-plugin-settings-group', 'allparcels_kurjeriai' );
	register_setting( 'ap-plugin-settings-group', 'allparcels_pickup_point' );
	register_setting( 'ap-plugin-settings-group', 'allparcels_post_office' );
	
	}
	function ap_theme_options_panel(){
		add_submenu_page( 'woocommerce', 'Allparcels', 'Allparcels', 'manage_options', 'allparcels-settings', 'ap_theme_func_settings');
		add_action( 'admin_init', 'ap_register_plugin_settings' );
	}
	add_action('admin_menu', 'ap_theme_options_panel');

	function ap_theme_func_settings(){
	?>
	<div class="wrap">
	<form method="post" action="options.php">
    <?php settings_fields( 'ap-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'ap-plugin-settings-group' ); ?>
     	<h2><?php _e('Allparcels nustatymai', 'mancanweb')?></h2>
     	<hr style="margin-bottom:70px">
     	<p><?php _e('Kurjerius ir pristatymo būdus reikia nurodyti redaguojant pristatymo zonas', 'mancanweb') ?>
     	<h3><?php _e('Įveskite API kodą', 'mancanweb') ?></h3>
     	<input type="text" style="margin-bottom:30px" name="allparcels_api"value="<?php echo esc_attr( get_option('allparcels_api') ); ?>"><br>
    <?php submit_button(); ?>
	</form>
	</div>
	<?php }

?>