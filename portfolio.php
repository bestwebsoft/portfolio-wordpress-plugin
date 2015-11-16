<?php
/*
Plugin Name: Portfolio by BestWebSoft
Plugin URI: http://bestwebsoft.com/products/
Description: Plugin for portfolio.
Author: BestWebSoft
Text Domain: portfolio
Domain Path: /languages
Version: 2.35
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*
	@ Copyright 2015  BestWebSoft  ( http://support.bestwebsoft.com )

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $prtfl_filenames, $prtfl_filepath, $prtfl_themepath;
$prtfl_filepath = WP_PLUGIN_DIR . '/portfolio/template/';
$prtfl_themepath = get_stylesheet_directory() . '/';

$prtfl_filenames[]	=	'portfolio.php';
$prtfl_filenames[]	=	'portfolio-post.php';

$prtfl_boxes = array();

/* Function are using to add on admin-panel Wordpress page 'bws_plugins' and sub-page of this plugin */
if ( ! function_exists( 'add_prtfl_admin_menu' ) ) {
	function add_prtfl_admin_menu() {
		global $submenu;
		bws_add_general_menu( plugin_basename( __FILE__ ) );
		$settings = add_submenu_page( 'bws_plugins', __( 'Portfolio', 'portfolio' ), __( 'Portfolio', 'portfolio' ), 'manage_options', "portfolio.php", 'prtfl_settings_page' );

		if ( isset( $submenu['edit.php?post_type=portfolio'] ) )
			$submenu['edit.php?post_type=portfolio'][] = array( __( 'Settings', 'portfolio' ), 'manage_options', admin_url( 'admin.php?page=portfolio.php' ) );

		add_action( 'load-' . $settings, 'prtfl_add_tabs' );
		add_action( 'load-post.php', 'prtfl_add_tabs' );
		add_action( 'load-edit.php', 'prtfl_add_tabs' );
		add_action( 'load-post-new.php', 'prtfl_add_tabs' );
		add_action( 'load-edit-tags.php', 'prtfl_add_tabs' );
	}
}

/* Internationalization, first(!)  */
if ( ! function_exists( 'prtfl_plugins_loaded' ) ) {
	function prtfl_plugins_loaded() {		
		load_plugin_textdomain( 'portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

if ( ! function_exists ( 'prtfl_init' ) ) {
	function prtfl_init() {
		global $prtfl_boxes, $prtfl_plugin_info;		

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		if ( ! $prtfl_plugin_info ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$prtfl_plugin_info = get_plugin_data( __FILE__ );
		}
		/* Function check if plugin is compatible with current WP version  */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $prtfl_plugin_info, '3.8', '3.1' );

		$prtfl_boxes['Portfolio-Info'] = array(
			array( '_prtfl_short_descr', __( 'Short description', 'portfolio' ), __( 'A short description which you\'d like to be displayed on your portfolio page', 'portfolio' ), '', '' ),
			array( '_prtfl_date_compl', __( 'Date of completion', 'portfolio' ), __( 'The date when the task was completed', 'portfolio' ), '', '' ),
			array( '_prtfl_link', __( 'Link', 'portfolio' ), __( 'A link to the site', 'portfolio' ), '', '' ),
			array( '_prtfl_svn', __( 'SVN', 'portfolio' ), __( 'SVN URL', 'portfolio' ), '', '' ),
		);
		/* Call register settings function */
		register_prtfl_settings();
		/* Register post type */
		prtfl_post_type_portfolio();
		/* Register taxonomy for portfolio */
		prtfl_taxonomy_portfolio();
	}
}

if ( ! function_exists( 'prtfl_admin_init' ) ) {
	function prtfl_admin_init() {
		global $bws_plugin_info, $prtfl_plugin_info, $bws_shortcode_list;

		if ( ! isset( $bws_plugin_info ) || empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '74', 'version' => $prtfl_plugin_info["Version"] );

		prtfl_admin_error();
		/* add Portfolio to global $bws_shortcode_list  */
		$bws_shortcode_list['prtfl'] = array( 'name' => 'Portfolio', 'js_function' => 'prtfl_shortcode_init' );
	}
}

/* Register settings function */
if ( ! function_exists( 'register_prtfl_settings' ) ) {
	function register_prtfl_settings() {
		global $prtfl_options, $prtfl_plugin_info, $prtfl_option_defaults;

		$prtfl_option_defaults = array(
			'prtfl_custom_size_name'				=>	array( 'portfolio-thumb', 'portfolio-photo-thumb' ),
			'prtfl_custom_size_px'					=>	array( array( 280, 300 ), array( 240, 260 ) ),
			'prtfl_order_by' 						=>	'menu_order',
			'prtfl_order' 							=>	'ASC',
			'prtfl_custom_image_row_count'			=>	3,
			'prtfl_date_additional_field' 			=>	1,
			'prtfl_link_additional_field' 			=>	1,
			'prtfl_shrdescription_additional_field' =>	1,
			'prtfl_description_additional_field' 	=>	1,
			'prtfl_svn_additional_field' 			=>	1,
			'prtfl_executor_additional_field' 		=>	1,
			'prtfl_technologies_additional_field'	=>	1,
			'prtfl_link_additional_field_for_non_registered'	=>	1,
			'prtfl_date_text_field'					=>	__( 'Date of completion:', 'portfolio' ),
			'prtfl_link_text_field'					=>	__( 'Link:', 'portfolio' ),
			'prtfl_shrdescription_text_field'		=>	__( 'Short description:', 'portfolio' ),
			'prtfl_description_text_field'			=>	__( 'Description:', 'portfolio' ),
			'prtfl_svn_text_field'					=>	__( 'SVN:', 'portfolio' ),
			'prtfl_executor_text_field'				=>	__( 'Executor Profile:', 'portfolio' ),
			'prtfl_screenshot_text_field'			=>	__( 'More screenshots:', 'portfolio' ),
			'prtfl_technologies_text_field'			=>	__( 'Technologies:', 'portfolio' ),
			'prtfl_slug' 							=>	'portfolio',
			'prtfl_rewrite_template' 				=>	1,
			'prtfl_rename_file' 					=>	0,
			'plugin_option_version'					=> $prtfl_plugin_info["Version"],
			'widget_updated' 						=>	1, /* this option is for updating plugin was added in v2.29 */
			'display_settings_notice'				=>	1,
			'first_install'							=>	strtotime( "now" ),
		);

		/* Install the option defaults */
		if ( ! get_option( 'prtfl_options' ) )
			add_option( 'prtfl_options', $prtfl_option_defaults );

		/* Get options from the database */
		$prtfl_options = get_option( 'prtfl_options' );

		if ( isset( $prtfl_options['prtfl_prettyPhoto_style'] ) )
			unset( $prtfl_options['prtfl_prettyPhoto_style'] );

		/* Array merge incase this version has added new options */
		if ( ! isset( $prtfl_options['plugin_option_version'] ) || $prtfl_options['plugin_option_version'] != $prtfl_plugin_info["Version"] ) {
			if ( ! isset( $prtfl_options['plugin_option_version'] ) || $prtfl_options['plugin_option_version'] < '2.29' )
				$prtfl_option_defaults['widget_updated'] = 0;

			$prtfl_option_defaults['display_settings_notice'] = 0;
			$prtfl_options = array_merge( $prtfl_option_defaults, $prtfl_options );
			$prtfl_options['plugin_option_version'] = $prtfl_plugin_info["Version"];
			/* show pro features */
			$prtfl_options['hide_premium_options'] = array();
			update_option( 'prtfl_options', $prtfl_options );
			/* update templates when updating plugin */
			prtfl_plugin_install();
		}

		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'portfolio-thumb', $prtfl_options['prtfl_custom_size_px'][0][0], $prtfl_options['prtfl_custom_size_px'][0][1], true );
			add_image_size( 'portfolio-photo-thumb', $prtfl_options['prtfl_custom_size_px'][1][0], $prtfl_options['prtfl_custom_size_px'][1][1], true );
		}
	}
}


if ( ! function_exists( 'prtfl_plugin_install' ) ) {
	function prtfl_plugin_install() {
		global $prtfl_filenames, $prtfl_filepath, $prtfl_themepath, $prtfl_options;

		if ( empty( $prtfl_options ) )
			register_prtfl_settings();

		foreach ( $prtfl_filenames as $filename ) {
			if ( ! file_exists( $prtfl_themepath . $filename ) ) {
				$handle		=	@fopen( $prtfl_filepath . $filename, "r" );
				$contents	=	@fread( $handle, filesize( $prtfl_filepath . $filename ) );
				@fclose( $handle );
				if ( ! ( $handle = @fopen( $prtfl_themepath . $filename, 'w' ) ) )
					return false;
				@fwrite( $handle, $contents );
				@fclose( $handle );
				@chmod( $prtfl_themepath . $filename, octdec( 755 ) );
			} elseif ( ! isset( $prtfl_options['prtfl_rewrite_template'] ) || 1 == $prtfl_options['prtfl_rewrite_template'] ) {
				$handle		=	@fopen( $prtfl_themepath . $filename, "r" );
				$contents	=	@fread( $handle, filesize( $prtfl_themepath . $filename ) );
				@fclose( $handle );
				if ( ! ( $handle = @fopen( $prtfl_themepath . $filename . '.bak', 'w' ) ) )
					return false;
				@fwrite( $handle, $contents );
				@fclose( $handle );
				
				$handle		=	@fopen( $prtfl_filepath . $filename, "r" );
				$contents	=	@fread( $handle, filesize( $prtfl_filepath . $filename ) );
				@fclose( $handle );
				if ( ! ( $handle = @fopen( $prtfl_themepath . $filename, 'w' ) ) )
					return false;
				@fwrite( $handle, $contents );
				@fclose( $handle );
				@chmod( $prtfl_themepath . $filename, octdec( 755 ) );
			}
		}
	}
}

if ( ! function_exists ( 'prtfl_after_switch_theme' ) ) {
	function prtfl_after_switch_theme() {
		global $prtfl_filenames, $prtfl_themepath;
		$file_exists_flag = true;
		foreach ( $prtfl_filenames as $filename ) {
			if ( ! file_exists( $prtfl_themepath . $filename ) )
				$file_exists_flag = false;
		}
		if ( ! $file_exists_flag )
			prtfl_plugin_install();
	}
}

if ( ! function_exists( 'prtfl_admin_error' ) ) {
	function prtfl_admin_error() {
		global $prtfl_filenames, $prtfl_filepath, $prtfl_themepath;

		$post = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : "" ;
		$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : "" ;
		$file_exists_flag = true;
		if ( ( 'portfolio' == get_post_type( $post ) || 'portfolio' == $post_type ) || ( isset( $_REQUEST['page'] ) && 'portfolio.php' == $_REQUEST['page'] ) ) {
			foreach ( $prtfl_filenames as $filename ) {
				if ( ! file_exists( $prtfl_themepath . $filename ) )
					$file_exists_flag = false;
			}
		}
		if ( ! $file_exists_flag )
			echo '<div class="error"><p><strong>' . __( 'The files "portfolio.php" and "portfolio-post.php" are not found in your theme directory. Please copy them from the directory `wp-content/plugins/portfolio/template/` to your theme directory for correct work of the Portfolio plugin', 'portfolio' ) . '</strong></p></div>';
	}
}

if ( ! function_exists( 'prtfl_settings_page' ) ) {
	function prtfl_settings_page() {
		global $prtfl_options, $wpdb, $wp_version, $prtfl_plugin_info, $prtfl_option_defaults;

		$error = $message = $cstmsrch_options_name = "";
		$plugin_basename  = plugin_basename( __FILE__ );

		if ( false !== get_option( 'cstmsrchpr_options' ) )
			$cstmsrch_options_name = "cstmsrchpr_options";
		elseif ( false !== get_option( 'cstmsrch_options' ) )
			$cstmsrch_options_name = "cstmsrch_options";
		elseif ( false !== get_option( 'bws_custom_search' ) )
			$cstmsrch_options_name = "bws_custom_search";

		$all_plugins = get_plugins();
		if ( isset( $cstmsrch_options_name ) && "" != $cstmsrch_options_name )
			$cstmsrch_options = get_option( $cstmsrch_options_name );

		/* Save data for settings page */
		if ( isset( $_REQUEST['prtfl_form_submit'] ) && check_admin_referer( $plugin_basename, 'prtfl_nonce_name' ) ) {
			$prtfl_request_options = array();

			if ( isset( $_POST['bws_hide_premium_options'] ) ) {
				$hide_result = bws_hide_premium_options( $prtfl_request_options );
				$prtfl_request_options = $hide_result['options'];
			}

			$prtfl_request_options["prtfl_custom_size_name"] = $prtfl_options["prtfl_custom_size_name"];

			$prtfl_request_options["prtfl_custom_size_px"] = array(
				array( intval( trim( $_REQUEST['prtfl_custom_image_size_w_album'] ) ), intval( trim( $_REQUEST['prtfl_custom_image_size_h_album'] ) ) ),
				array( intval( trim( $_REQUEST['prtfl_custom_image_size_w_photo'] ) ), intval( trim( $_REQUEST['prtfl_custom_image_size_h_photo'] ) ) )
			);
			$prtfl_request_options["prtfl_custom_image_row_count"] =  intval( $_REQUEST['prtfl_custom_image_row_count'] );
			if ( "" == $prtfl_request_options["prtfl_custom_image_row_count"] || 1 > $prtfl_request_options["prtfl_custom_image_row_count"] )
				$prtfl_request_options["prtfl_custom_image_row_count"] = 1;

			$prtfl_request_options["prtfl_order_by"]	=	$_REQUEST['prtfl_order_by'];
			$prtfl_request_options["prtfl_order"]		=	$_REQUEST['prtfl_order'];

			$prtfl_request_options["prtfl_date_additional_field"]			=	isset( $_REQUEST["prtfl_date_additional_field"] ) ? $_REQUEST["prtfl_date_additional_field"] : 0;
			$prtfl_request_options["prtfl_link_additional_field"]			=	isset( $_REQUEST["prtfl_link_additional_field"] ) ? $_REQUEST["prtfl_link_additional_field"] : 0;
			$prtfl_request_options["prtfl_shrdescription_additional_field"] =	isset( $_REQUEST["prtfl_shrdescription_additional_field"] ) ? $_REQUEST["prtfl_shrdescription_additional_field"] : 0;
			$prtfl_request_options["prtfl_description_additional_field"]	=	isset( $_REQUEST["prtfl_description_additional_field"] ) ? $_REQUEST["prtfl_description_additional_field"] : 0;
			$prtfl_request_options["prtfl_svn_additional_field"]			=	isset( $_REQUEST["prtfl_svn_additional_field"] ) ? $_REQUEST["prtfl_svn_additional_field"] : 0;
			$prtfl_request_options["prtfl_executor_additional_field"]		=	isset( $_REQUEST["prtfl_executor_additional_field"] ) ? $_REQUEST["prtfl_executor_additional_field"] : 0;
			$prtfl_request_options["prtfl_technologies_additional_field"]	=	isset( $_REQUEST["prtfl_technologies_additional_field"] ) ? $_REQUEST["prtfl_technologies_additional_field"] : 0;

			$prtfl_request_options["prtfl_link_additional_field_for_non_registered"] = isset( $_REQUEST["prtfl_link_additional_field_for_non_registered"] ) ? $_REQUEST["prtfl_link_additional_field_for_non_registered"] : 0;

			$prtfl_request_options["prtfl_date_text_field"] 			=	stripslashes( esc_html( $_REQUEST["prtfl_date_text_field"] ) );
			$prtfl_request_options["prtfl_link_text_field"]				=	stripslashes( esc_html( $_REQUEST["prtfl_link_text_field"] ) );
			$prtfl_request_options["prtfl_shrdescription_text_field"] 	=	stripslashes( esc_html( $_REQUEST["prtfl_shrdescription_text_field"] ) );
			$prtfl_request_options["prtfl_description_text_field"]		=	stripslashes( esc_html( $_REQUEST["prtfl_description_text_field"] ) );
			$prtfl_request_options["prtfl_svn_text_field"]				=	stripslashes( esc_html( $_REQUEST["prtfl_svn_text_field"] ) );
			$prtfl_request_options["prtfl_executor_text_field"]			=	stripslashes( esc_html( $_REQUEST["prtfl_executor_text_field"] ) );
			$prtfl_request_options["prtfl_screenshot_text_field"]		=	stripslashes( esc_html( $_REQUEST["prtfl_screenshot_text_field"] ) );
			$prtfl_request_options["prtfl_technologies_text_field"]		=	stripslashes( esc_html( $_REQUEST["prtfl_technologies_text_field"] ) );

			$prtfl_request_options["prtfl_slug"]	=	trim( $_REQUEST['prtfl_slug'] );
			$prtfl_request_options["prtfl_slug"]	=	strtolower( $prtfl_request_options["prtfl_slug"] );
			$prtfl_request_options["prtfl_slug"]	=	preg_replace( "/[^a-z0-9\s-]/", "", $prtfl_request_options["prtfl_slug"] );
			$prtfl_request_options["prtfl_slug"]	=	trim( preg_replace( "/[\s-]+/", " ", $prtfl_request_options["prtfl_slug"] ) );
			$prtfl_request_options["prtfl_slug"]	=	preg_replace( "/\s/", "-", $prtfl_request_options["prtfl_slug"] );

			$prtfl_request_options["prtfl_rewrite_template"] = isset( $_REQUEST["prtfl_rewrite_template"] ) ? 1 : 0;
			$prtfl_request_options["prtfl_rename_file"] = isset( $_REQUEST["prtfl_rename_file"] ) ? 1 : 0;

			if ( isset( $_REQUEST['prtfl_add_to_search'] ) && "" != $cstmsrch_options_name ) {
				if ( false !== get_option( $cstmsrch_options_name ) ) {
					$cstmsrch_options = get_option( $cstmsrch_options_name );
					if ( ! in_array( 'portfolio', $cstmsrch_options ) ) {
						array_push( $cstmsrch_options, 'portfolio' );
						update_option( $cstmsrch_options_name, $cstmsrch_options );
					}
				}
			} else {
				if ( false !== get_option( $cstmsrch_options_name ) ) {
					$cstmsrch_options = get_option( $cstmsrch_options_name );
					if ( in_array( 'portfolio', $cstmsrch_options ) ) {
						$key = array_search( 'portfolio', $cstmsrch_options );
						unset( $cstmsrch_options[ $key ] );
						update_option( $cstmsrch_options_name, $cstmsrch_options );
					}
				}
			}

			/* For revrite prtfl_slug */
			global $wp_rewrite;
			$rules = get_option( 'rewrite_rules' );
			prtfl_custom_permalinks( $rules );
			$wp_rewrite->flush_rules();

			/* Array merge incase this version has added new options */
			$prtfl_options = array_merge( $prtfl_options, $prtfl_request_options );

			/* Check select one point in the blocks Arithmetic actions and Difficulty on settings page */
			update_option( 'prtfl_options', $prtfl_options );
			$message = __( "Settings saved.", 'portfolio' );
		}

		$bws_hide_premium_options_check = bws_hide_premium_options_check( $prtfl_options );

		if ( isset( $_POST['bws_restore_confirm'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'bws_settings_nonce_name' ) ) {
			$prtfl_options = $prtfl_option_defaults;
			update_option( 'prtfl_options', $prtfl_options );
			$message =  __( 'All plugin settings were restored.', 'portfolio' );
		}

		/* GO PRO */
		if ( isset( $_GET['action'] ) && 'go_pro' == $_GET['action'] ) {
			$go_pro_result = bws_go_pro_tab_check( $plugin_basename, 'prtfl_options' );
			if ( ! empty( $go_pro_result['error'] ) )
				$error = $go_pro_result['error'];
			elseif ( ! empty( $go_pro_result['message'] ) )
				$message = $go_pro_result['message'];
		}
		/* Display form on the setting page */ ?>
		<div class="wrap">
			<h2><?php _e( 'Portfolio Settings', 'portfolio' ); ?></h2>
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab<?php echo ! isset( $_GET['action'] ) ? ' nav-tab-active': ''; ?>" href="admin.php?page=portfolio.php"><?php _e( 'Settings', 'portfolio' ); ?></a>
				<a class="nav-tab bws_go_pro_tab<?php if ( isset( $_GET['action'] ) && 'go_pro' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=portfolio.php&amp;action=go_pro"><?php _e( 'Go PRO', 'portfolio' ); ?></a>
			</h2>
			<div class="updated fade" <?php if ( '' == $message || "" != $error ) echo 'style="display:none"'; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<div class="error" <?php if ( "" == $error ) echo 'style="display:none"'; ?>><p><strong><?php echo $error; ?></strong></p></div>
			<?php bws_show_settings_notice();
			if ( ! empty( $hide_result['message'] ) ) { ?>
				<div class="updated fade"><p><strong><?php echo $hide_result['message']; ?></strong></p></div>
			<?php }
			if ( isset( $_POST['bws_restore_default'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'bws_settings_nonce_name' ) ) {
				bws_form_restore_default_confirm( plugin_basename( __FILE__ ) );
			} else if ( ! ( isset( $_GET['action'] ) && 'go_pro' == $_GET['action'] ) ) { ?>
				<noscript><div class="error"><p><?php _e( 'Please enable JavaScript to use the option to renew images.', 'portfolio' ); ?></p></div></noscript> 
				<br/>
				<div><?php printf( 
					__( "If you would like to add the Latest Portfolio Items to your page or post, please use %s button", 'portfolio' ), 
					'<span class="bws_code"><img style="vertical-align: sub;" src="' . plugins_url( 'bws_menu/images/shortcode-icon.png', __FILE__ ) . '" alt=""/></span>' ); ?> 
					<div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help">
						<div class="bws_hidden_help_text" style="min-width: 180px;">
							<?php printf( 
								__( "You can add the Latest Portfolio Items to your page or post by clicking on %s button in the content edit block using the Visual mode. If the button isn't displayed, please use the shortcode %s, where * is a number of portfolio to display", 'portfolio' ), 
								'<code><img style="vertical-align: sub;" src="' . plugins_url( 'bws_menu/images/shortcode-icon.png', __FILE__ ) . '" alt="" /></code>',
								'<code>[latest_portfolio_items count=*]</code>'
							); ?>
						</div>
					</div>
				</div>
				<table class="form-table hide-if-no-js">
					<tr valign="top">
						<th scope="row"><?php _e( 'Update images for portfolio', 'portfolio' ); ?> </th>
						<td>
							<input type="button" value="<?php _e( 'Update images', 'portfolio' ); ?>" id="prtfl_ajax_update_images" name="prtfl_ajax_update_images" class="button" /> 
							<div id="prtfl_img_loader"><img src="<?php echo plugins_url( 'images/ajax-loader.gif', __FILE__ ); ?>" alt="loader" /></div>
						</td>
					</tr>
				</table>
				<br />
				<form method="post" action="admin.php?page=portfolio.php" class="bws_form">
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e( 'Image size for the album cover', 'portfolio' ); ?> </th>
							<td>
								<label><?php _e( 'Image size name', 'portfolio' ); ?></label>&nbsp;<?php echo $prtfl_options["prtfl_custom_size_name"][0]; ?><br />
								<label><input type="number" name="prtfl_custom_image_size_w_album" min="1" max="10000" value="<?php echo $prtfl_options["prtfl_custom_size_px"][0][0]; ?>" /> <?php _e( 'Width (in px)', 'portfolio' ); ?></label><br />
								<label><input type="number" name="prtfl_custom_image_size_h_album" min="1" max="10000" value="<?php echo $prtfl_options["prtfl_custom_size_px"][0][1]; ?>" /> <?php _e( 'Height (in px)', 'portfolio' ); ?></label><br />
								<span class="bws_info"><?php _e( 'WordPress will create a new thumbnail with the specified dimensions when you upload a new photo. It is necessary to click the Update images button at the top of this page in order to generate new images and set new dimensions for the old photo.', 'portfolio' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Image size for thumbnails', 'portfolio' ); ?> </th>
							<td>
								<label><?php _e( 'Image size name', 'portfolio' ); ?></label>&nbsp;<?php echo $prtfl_options["prtfl_custom_size_name"][1]; ?><br />
								<label><input type="number" name="prtfl_custom_image_size_w_photo" min="1" max="10000" value="<?php echo $prtfl_options["prtfl_custom_size_px"][1][0]; ?>" /> <?php _e( 'Width (in px)', 'portfolio' ); ?></label><br />
								<label><input type="number" name="prtfl_custom_image_size_h_photo" min="1" max="10000" value="<?php echo $prtfl_options["prtfl_custom_size_px"][1][1]; ?>" /> <?php _e( 'Height (in px)', 'portfolio' ); ?></label><br />
								<span class="bws_info"><?php _e( 'WordPress will create a new thumbnail with the specified dimensions when you upload a new photo. It is necessary to click the Update images button at the top of this page in order to generate new images and set new dimensions for the old photo.', 'portfolio' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Sort portfolio by', 'portfolio' ); ?> </th>
							<td><fieldset>
								<label><input type="radio" name="prtfl_order_by" value="ID" <?php if ( 'ID' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'portfolio id', 'portfolio' ); ?></label><br />
								<label><input type="radio" name="prtfl_order_by" value="title" <?php if ( 'title' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'portfolio title', 'portfolio' ); ?></label><br />
								<label><input type="radio" name="prtfl_order_by" value="date" <?php if ( 'date' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'date', 'portfolio' ); ?></label><br />
								<label><input type="radio" name="prtfl_order_by" value="menu_order" <?php if ( 'menu_order' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'menu order', 'portfolio' ); ?></label><br />
								<label><input type="radio" name="prtfl_order_by" value="rand" <?php if ( 'rand' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'random', 'portfolio' ); ?></label>
							</fieldset></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Portfolio sorting', 'portfolio' ); ?> </th>
							<td>
								<label><input type="radio" name="prtfl_order" value="ASC" <?php if ( 'ASC' == $prtfl_options["prtfl_order"] ) echo 'checked="checked"'; ?> /> <?php _e( 'ASC (ascending order from lowest to highest values - 1, 2, 3; a, b, c)', 'portfolio' ); ?></label><br />
								<label><input type="radio" name="prtfl_order" value="DESC" <?php if ( 'DESC' == $prtfl_options["prtfl_order"] ) echo 'checked="checked"'; ?> /> <?php _e( 'DESC (descending order from highest to lowest values - 3, 2, 1; c, b, a)', 'portfolio' ); ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Number of images in the row', 'portfolio' ); ?> </th>
							<td>
								<input type="number" name="prtfl_custom_image_row_count" min="1" max="1000" value="<?php echo $prtfl_options["prtfl_custom_image_row_count"]; ?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Display additional fields', 'portfolio' ); ?> </th>
							<td>
								<label><input type="checkbox" name="prtfl_date_additional_field" value="1" <?php if ( 1 == $prtfl_options['prtfl_date_additional_field'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Date', 'portfolio' ); ?></label><br />
								<label><input type="checkbox" name="prtfl_link_additional_field" value="1" <?php if ( 1 == $prtfl_options['prtfl_link_additional_field'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Link', 'portfolio' ); ?></label><br />
								<label><input type="checkbox" name="prtfl_shrdescription_additional_field" value="1" <?php if ( 1 == $prtfl_options['prtfl_shrdescription_additional_field'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Short Description', 'portfolio' ); ?></label><br />
								<label><input type="checkbox" name="prtfl_description_additional_field" value="1" <?php if ( 1 == $prtfl_options['prtfl_description_additional_field'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Description', 'portfolio' ); ?></label><br />
								<label><input type="checkbox" name="prtfl_svn_additional_field" value="1" <?php if ( 1 == $prtfl_options['prtfl_svn_additional_field'] ) echo 'checked="checked"'; ?> /> <?php _e( 'SVN', 'portfolio' ); ?></label><br />
								<label><input type="checkbox" name="prtfl_executor_additional_field" value="1" <?php if ( 1 == $prtfl_options['prtfl_executor_additional_field'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Executor', 'portfolio' ); ?></label><br />
								<label><input type="checkbox" name="prtfl_technologies_additional_field" value="1" <?php if ( 1 == $prtfl_options['prtfl_technologies_additional_field'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Technologies', 'portfolio' ); ?></label>
							</td>
						</tr>
					</table>
					<?php if ( ! $bws_hide_premium_options_check ) { ?>
						<div class="bws_pro_version_bloc">
							<div class="bws_pro_version_table_bloc">
								<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php _e( 'Close', 'portfolio' ); ?>"></button>
								<div class="bws_table_bg"></div>
								<table class="form-table bws_pro_version">
									<tr valign="top">
										<th scope="row"><?php _e( 'Display additional fields', 'portfolio' ); ?> </th>
										<td>
											<label><input type="checkbox" name="prtflpr_categories_additional_field" value="1" disabled="disabled" /> <?php _e( 'Categories', 'portfolio' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><?php _e( 'Hide "More screenshots" block', 'portfolio' ); ?></th>
										<td>
											<input type="checkbox" name="prtflpr_disbable_screenshot_block" value="1" disabled="disabled" />
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><?php _e( 'Selectbox of sorting portfolio by date or title in the front end', 'portfolio' ); ?> </th>
										<td>
											<input type="checkbox" name="prtflpr_sorting_selectbox" value="1" disabled="disabled" />
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><?php _e( 'The lightbox helper', 'portfolio' ); ?> </th>
										<td>
											<label><input disabled type="radio" name="prtflpr_fancybox_helper" value="none" checked="checked" /> <?php _e( 'Do not use', 'portfolio' ); ?></label><br />
											<label><input disabled type="radio" name="prtflpr_fancybox_helper" value="button" /> <?php _e( 'Button helper', 'portfolio' ); ?></label><br />
											<label><input disabled type="radio" name="prtflpr_fancybox_helper" value="thumbnail" /> <?php _e( 'Thumbnail helper', 'portfolio' ); ?></label><br />
											<div class="prtfl_fancybox_thumb_helper_options">
												<label><input disabled type="number" min="1" max="10000" name="prtflpr_fancybox_thumb_helper_width" value="50" /> <?php _e( 'Width (in px)', 'portfolio' ); ?></label><br />
												<label><input disabled type="number" min="1" max="10000" name="prtflpr_fancybox_thumb_helper_height" value="50" /> <?php _e( 'Height (in px)', 'portfolio' ); ?></label><br />
												<label>
													<select disabled name="prtflpr_fancybox_thumb_helper_position">
														<option value="top"><?php _e( 'top', 'portfolio' ); ?></option>
														<option value="bottom"><?php _e( 'bottom', 'portfolio' ); ?></option>
													</select>
													<?php _e( 'Position', 'portfolio' ); ?>
												</label>
											</div>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row" colspan="2">
											* <?php _e( 'If you upgrade to Pro version all your settings and portfolios will be saved.', 'portfolio' ); ?>
										</th>
									</tr>
								</table>
							</div>
							<div class="bws_pro_version_tooltip">
								<div class="bws_info">
									<?php _e( 'Unlock premium options by upgrading to Pro version', 'portfolio' ); ?>
								</div>
								<a class="bws_button" href="http://bestwebsoft.com/products/portfolio/?k=f047e20c92c972c398187a4f70240285&pn=74&v=<?php echo $prtfl_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Portfolio Pro"><?php _e( 'Learn More', 'portfolio' ); ?></a>
								<div class="clear"></div>
							</div>
						</div>
					<?php } ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e( 'Display the link field as a text for non-registered users', 'portfolio' ); ?></th>
							<td>
								<input type="checkbox" name="prtfl_link_additional_field_for_non_registered" value="1" id="prtfl_link_additional_field_for_non_registered" <?php if ( 1 == $prtfl_options['prtfl_link_additional_field_for_non_registered'] ) echo 'checked="checked"'; ?> />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Text for additional fields', 'portfolio' ); ?> </th>
							<td>
								<label><input type="text" name="prtfl_date_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_date_text_field"]; ?>" /> <?php _e( 'Date of completion', 'portfolio' ); ?></label><br />
								<label><input type="text" name="prtfl_link_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_link_text_field"]; ?>" /> <?php _e( 'Link', 'portfolio' ); ?></label><br />
								<label><input type="text" name="prtfl_shrdescription_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_shrdescription_text_field"]; ?>" /> <?php _e( 'Short description', 'portfolio' ); ?></label><br />
								<label><input type="text" name="prtfl_description_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_description_text_field"]; ?>" /> <?php _e( 'Description', 'portfolio' ); ?></label><br />
								<label><input type="text" name="prtfl_svn_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_svn_text_field"]; ?>" /> <?php _e( 'SVN', 'portfolio' ); ?></label><br />
								<label><input type="text" name="prtfl_executor_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_executor_text_field"]; ?>" /> <?php _e( 'Executor Profile', 'portfolio' ); ?></label><br />
								<label><input type="text" name="prtfl_screenshot_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_screenshot_text_field"]; ?>" /> <?php _e( 'More screenshots', 'portfolio' ); ?></label><br />
								<label><input type="text" name="prtfl_technologies_text_field" maxlength="250" value="<?php echo $prtfl_options["prtfl_technologies_text_field"]; ?>" /> <?php _e( 'Technologies', 'portfolio' ); ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Slug for portfolio item', 'portfolio' ); ?></th>
							<td>
								<input type="text" name="prtfl_slug" maxlength="250" value="<?php echo $prtfl_options["prtfl_slug"]; ?>" /> <span class="bws_info"><?php _e( 'for any structure of permalinks except the default structure', 'portfolio' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Rewrite templates after update', 'portfolio' ); ?></th>
							<td>
								<input type="checkbox" name="prtfl_rewrite_template" value="1" <?php if ( 1 == $prtfl_options['prtfl_rewrite_template'] ) echo 'checked="checked"'; ?> /> <span class="bws_info"><?php _e( "Turn off the checkbox, if You edited the file 'portfolio.php' or 'portfolio-post.php' file in your theme folder and You don't want to rewrite them", 'portfolio' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Rename uploaded images', 'portfolio' ); ?></th>
							<td>
								<input type="checkbox" name="prtfl_rename_file" value="1" <?php if ( 1 == $prtfl_options['prtfl_rename_file'] ) echo 'checked="checked"'; ?> /> <span class="bws_info"><?php _e( "To avoid conflicts, all the symbols will be excluded, except numbers, the Roman letters,  _ and - symbols.", 'portfolio' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Add portfolio to the search', 'portfolio' ); ?></th>
							<td>
								<?php if ( array_key_exists( 'custom-search-plugin/custom-search-plugin.php', $all_plugins ) || array_key_exists( 'custom-search-pro/custom-search-pro.php', $all_plugins ) ) {
									if ( is_plugin_active( 'custom-search-plugin/custom-search-plugin.php' ) || is_plugin_active( 'custom-search-pro/custom-search-pro.php' ) ) { ?>
										<input type="checkbox" name="prtfl_add_to_search" value="1" <?php if ( isset( $cstmsrch_options ) && in_array( 'portfolio', $cstmsrch_options ) ) echo 'checked="checked"'; ?> />
										<span class="bws_info"> (<?php _e( 'Using Custom Search powered by', 'portfolio' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>)</span>
									<?php } else { ?>
										<input disabled="disabled" type="checkbox" name="prtfl_add_to_search" value="1" <?php if ( isset( $cstmsrch_options ) && in_array( 'portfolio', $cstmsrch_options ) ) echo 'checked="checked"'; ?> />
										<span class="bws_info">(<?php _e( 'Using Custom Search powered by', 'portfolio' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>) <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'Activate Custom Search', 'portfolio' ); ?></a></span>
									<?php }
								} else { ?>
									<input disabled="disabled" type="checkbox" name="prtfl_add_to_search" value="1" />
									<span class="bws_info">(<?php _e( 'Using Custom Search powered by', 'portfolio' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>) <a href="http://bestwebsoft.com/products/custom-search/"><?php _e( 'Download Custom Search', 'portfolio' ); ?></a></span>
								<?php } ?>
							</td>
						</tr>
					</table>					
					<p class="submit">
						<input id="bws-submit-button" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'portfolio' ); ?>" />
						<input type="hidden" name="prtfl_form_submit" value="submit" />
						<?php wp_nonce_field( plugin_basename( __FILE__ ), 'prtfl_nonce_name' ); ?>
					</p>					
				</form>
				<?php bws_form_restore_default_settings( plugin_basename( __FILE__ ) );				
			} elseif ( 'go_pro' == $_GET['action'] ) {
				bws_go_pro_tab_show( $bws_hide_premium_options_check, $prtfl_plugin_info, $plugin_basename, 'portfolio.php', 'portfolio-pro.php', 'portfolio-pro/portfolio-pro.php', 'portfolio', 'f047e20c92c972c398187a4f70240285', '74', isset( $go_pro_result['pro_plugin_is_activated'] ) );
			} 
			bws_plugin_reviews_block( $prtfl_plugin_info['Name'], 'portfolio' ); ?>
		</div>
	<?php }
}

/* Create post type for portfolio */
if ( ! function_exists( 'prtfl_post_type_portfolio' ) ) {
	function prtfl_post_type_portfolio() {
		global $wpdb, $prtfl_options;
		$slug		=	isset( $prtfl_options['prtfl_slug'] ) && ! empty( $prtfl_options['prtfl_slug'] ) ? $prtfl_options['prtfl_slug'] : 'portfolio';
		register_post_type(
			'portfolio',
			array(
				'labels' => array(
					'name'					=>	__( 'Portfolio', 'portfolio' ),
					'singular_name'			=>	__( 'Portfolio', 'portfolio' ),
					'add_new'				=>	__( 'Add New', 'portfolio' ),
					'add_new_item'			=>	__( 'Add New Portfolio', 'portfolio' ),
					'edit'					=>	__( 'Edit', 'portfolio' ),
					'edit_item'				=>	__( 'Edit Portfolio', 'portfolio' ),
					'new_item'				=>	__( 'New Portfolio', 'portfolio' ),
					'view'					=>	__( 'View Portfolio', 'portfolio' ),
					'view_item'				=>	__( 'View Portfolio', 'portfolio' ),
					'search_items'			=>	__( 'Search Portfolio', 'portfolio' ),
					'not_found'				=>	__( 'No portfolio found', 'portfolio' ),
					'not_found_in_trash'	=>	__( 'No portfolio found in Trash', 'portfolio' ),
					'parent'				=>	__( 'Parent Portfolio', 'portfolio' ),
				),
				'description'			=>	__( 'Create a portfolio item', 'portfolio' ),
				'public'				=>	true,
				'show_ui'				=>	true,
				'publicly_queryable'	=>	true,
				'exclude_from_search'	=>	true,
				'hierarchical'			=>	true,
				'query_var'				=>	true,
				'register_meta_box_cb'	=>	'prtfl_init_metaboxes',
				'rewrite'				=>	array( 'slug' => $slug ),
				'supports'				=>	array(
					'title', /* Text input field to create a post title. */
					'editor',
					'custom-fields',
					'comments', /* Ability to turn on/off comments. */
					'thumbnail', /* Displays a box for featured image. */
					'author',
					'page-attributes'
				)
			)
		);
	}
}

/* Create taxonomy for portfolio - Technologies and Executors Profile */
if ( ! function_exists( 'prtfl_taxonomy_portfolio' ) ) {
	function prtfl_taxonomy_portfolio() {
		register_taxonomy(
			'portfolio_executor_profile',
			'portfolio',
			array(
				'hierarchical'			=>	false,
				'update_count_callback' =>	'_update_post_term_count',
				'labels'				=>	array(
					'name'							=>	__( 'Executor Profiles', 'portfolio' ),
					'singular_name'					=>	__( 'Executor Profile', 'portfolio' ),
					'search_items'					=>	__( 'Search Executor Profiles', 'portfolio' ),
					'popular_items'					=>	__( 'Popular Executor Profiles', 'portfolio' ),
					'all_items'						=>	__( 'All Executor Profiles', 'portfolio' ),
					'parent_item'					=>	__( 'Parent Executor Profile', 'portfolio' ),
					'parent_item_colon'				=>	__( 'Parent Executor Profile:', 'portfolio' ),
					'edit_item'						=>	__( 'Edit Executor Profile', 'portfolio' ),
					'update_item'					=>	__( 'Update Executor Profile', 'portfolio' ),
					'add_new_item'					=>	__( 'Add New Executor Profile', 'portfolio' ),
					'new_item_name'					=>	__( 'New Executor Name', 'portfolio' ),
					'separate_items_with_commas'	=>	__( 'Separate Executor Profiles with commas', 'portfolio' ),
					'add_or_remove_items'			=>	__( 'Add or remove Executor Profile', 'portfolio' ),
					'choose_from_most_used'			=>	__( 'Choose from the most used Executor Profiles', 'portfolio' ),
					'menu_name'						=>	__( 'Executors', 'portfolio' )
				),
				'sort'					=>	true,
				'args'					=>	array( 'orderby' => 'term_order' ),
				'rewrite'				=>	array( 'slug' => 'executor_profile' ),
				'show_tagcloud'			=>	false
			)
		);

		register_taxonomy(
			'portfolio_technologies',
			'portfolio',
			array(
				'hierarchical'			=>	false,
				'update_count_callback'	=>	'_update_post_term_count',
				'labels'				=>	array(
					'name'							=>	__( 'Technologies', 'portfolio' ),
					'singular_name'					=>	__( 'Technology', 'portfolio'),
					'search_items'					=>	__( 'Search Technologies', 'portfolio' ),
					'popular_items'					=>	__( 'Popular Technologies', 'portfolio' ),
					'all_items'						=>	__( 'All Technologies', 'portfolio' ),
					'parent_item'					=>	__( 'Parent Technology', 'portfolio' ),
					'parent_item_colon'				=>	__( 'Parent Technology:', 'portfolio' ),
					'edit_item'						=>	__( 'Edit Technology', 'portfolio' ),
					'update_item'					=>	__( 'Update Technology', 'portfolio' ),
					'add_new_item'					=>	__( 'Add New Technology', 'portfolio' ),
					'new_item_name'					=>	__( 'New Technology Name', 'portfolio' ),
					'separate_items_with_commas'	=>	__( 'Separate Technologies with commas', 'portfolio' ),
					'add_or_remove_items' 			=>	__( 'Add or remove Technology', 'portfolio' ),
					'choose_from_most_used' 		=>	__( 'Choose from the most used technologies', 'portfolio' ),
					'menu_name'						=>	__( 'Technologies', 'portfolio' )
				),
				'query_var'				=>	'technologies',
				'rewrite'				=>	array( 'slug' => 'technologies' ),
				'public'				=>	true,
				'show_ui'				=>	true,
				'_builtin'				=>	true,
				'show_tagcloud' 		=>	false
			)
		);
	}
}

/* add query_var "post_type" in case we have another custom post type with query_var 'portfolio' (example: jetpack portfolio) */
if ( ! function_exists( 'prtfl_request_filter' ) ) {
	function prtfl_request_filter( $query_vars ) {
		if ( isset( $query_vars["post_type"] ) && $query_vars["post_type"] == 'jetpack-portfolio' ) {
			if ( ! get_posts( $query_vars ) )
				$query_vars["post_type"] = 'portfolio';
		}
		return $query_vars;
	}
}

if ( ! function_exists( 'prtfl_technologies_get_posts' ) ) {
	function prtfl_technologies_get_posts( $query ) {
		if ( ( isset( $query->query_vars["technologies"] ) || isset( $query->query_vars["portfolio_executor_profile"] ) ) && ( ! is_admin() ) )
			$query->set( 'post_type', array( 'portfolio' ) );
		return $query;
	}
}

/**
 * Class extends WP class WP_Widget, and create new widget
 */
if ( ! class_exists( 'portfolio_technologies_widget' ) ) {
	class portfolio_technologies_widget extends WP_Widget {
		/* constructor of class */
		function __construct() {
			parent::__construct(
					'portfolio_technologies_widget',
					__( 'Technologies', 'portfolio' ),
					array( 'description' => __( 'Your most used portfolio technologies as a tag cloud', 'portfolio' ) )
				);
		}
		/* Function to displaying widget in front end */
		function widget( $args, $instance ) {
			$widget_title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : null;
			$widget_title = apply_filters( 'widget_title', $widget_title, '', 'portfolio_technologies_widget' );
			echo $args['before_widget'];
			if ( $widget_title )
				echo $args['before_title'] . $widget_title . $args['after_title'];
			echo '<div class="tagcloud">';
			wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array( 'taxonomy' => 'portfolio_technologies', 'number' => 0 ) ) );
			echo "</div>\n";
			echo $args['after_widget'];
		}
		/* Function to save widget settings */
		function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['widget_title'] = ( ! empty( $new_instance['widget_title'] ) ) ? strip_tags( $new_instance['widget_title'] ) : null;		
			return $instance;
		}
		/* Function to displaying widget settings in back end */
		function form( $instance ) {
			$widget_title = isset( $instance['widget_title'] ) ? stripslashes( esc_html( $instance['widget_title'] ) ) : null; ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Title', 'portfolio' ); ?>:</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>"/>
			</p>
		<?php }
	}
}
if ( ! function_exists( 'prtfl_register_widget' ) ) {
	function prtfl_register_widget() {
		register_widget( 'portfolio_technologies_widget' );
	}
}

/* Create custom permalinks for portfolio post type */
if ( ! function_exists( 'prtfl_custom_permalinks' ) ) {
	function prtfl_custom_permalinks( $rules ) {
		$newrules = array();
		$newrules['portfolio/page/([^/]+)/?$']	=	'index.php?pagename=portfolio&paged=$matches[1]';
		$newrules['portfolio/page/([^/]+)?$']	=	'index.php?pagename=portfolio&paged=$matches[1]';
		/* return $newrules + $rules; */
		if ( $rules )
			return array_merge( $newrules, $rules );
	}
}

/* flush_rules() if our rules are not yet included */
if ( ! function_exists( 'prtfl_flush_rules' ) ) {
	function prtfl_flush_rules() {
		$rules = get_option( 'rewrite_rules' );
		if ( ! isset( $rules['portfolio/page/([^/]+)/?$'] ) ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}
}

/* Initialization of all metaboxes on the 'Add Portfolio' and Edit Portfolio pages */
if ( ! function_exists( 'prtfl_init_metaboxes' ) ) {
	function prtfl_init_metaboxes() {
		add_meta_box( 'Portfolio-Info', __( 'Portfolio Info', 'portfolio' ), 'prtfl_post_custom_box', 'portfolio', 'normal', 'high' ); /* Description metaboxe */
		add_meta_box( 'prtfl_categories_meta_box', __( 'Categories', 'portfolio' ), 'prtfl_categories_meta_box', 'portfolio', 'side', 'low' );
		if ( ! ( function_exists( 'rttchr_metabox_content_in_post' ) || function_exists( 'rttchrpr_metabox_content_in_post' ) ) ) {
			add_meta_box( 'prtfl_rttchr_metabox_ad', __( 'Already attached', 'portfolio' ), 'prtfl_rttchr_attach_box', 'portfolio', 'side', 'low' );
		}		
	}
}

/* Create custom meta box for portfolio post type */
if ( ! function_exists( 'prtfl_post_custom_box' ) ) {
	function prtfl_post_custom_box( $obj = '', $box = '' ) {
		global $prtfl_boxes;
		/* Generate box contents */
		foreach ( $prtfl_boxes[ $box[ 'id' ] ] as $prtfl_box ) {
			echo prtfl_text_field( $prtfl_box );
		}
	}
}

/* Create custom meta box for re-attacher ad in portfolio */
if ( ! function_exists( 'prtfl_rttchr_attach_box' ) ) {
	function prtfl_rttchr_attach_box() { 
		global $prtfl_plugin_info, $wp_version;

		if ( ! function_exists( 'get_plugins' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$all_plugins = get_plugins();
		if ( isset( $all_plugins['re-attacher/re-attacher.php'] ) || isset( $all_plugins['re-attacher-pro/re-attacher-pro.php'] ) ) {
			/* if re-attacher is installed */
			$link = "plugins.php";
			$text = __( 'Activate', 'portfolio' );
		} else {
			if ( function_exists( 'is_multisite' ) )
				$link = ( ! is_multisite() ) ? admin_url( '/' ) : network_admin_url( '/' );
			else
				$link = admin_url( '/' );
			$link = $link . 'plugin-install.php?tab=search&type=term&s=Re-attacher+BestWebSoft&plugin-search-input=Search+Plugins';
			$text = __( 'Install now', 'portfolio' );			
		} ?>
		<p>
			<?php _e( "If you'd like to attach the files, which are already uploaded, please use Re-attacher plugin.", 'portfolio' ); ?>
		</p>
		<p>
			<a target="_blank" class="button-secondary" href="http://bestwebsoft.com/products/re-attacher/?k=a9c95424ed55d41fd762ce8aad52a519&pn=74&v=<?php echo $prtfl_plugin_info["Version"] . '&wp_v=' . $wp_version ?>" style="margin:0px 5px 2px;"><?php _e( 'Learn more', 'portfolio' ); ?></a>
			<a class="button-primary" href="<?php echo $link; ?>" target="_blank" style="margin-right: 5px;"><?php echo $text; ?></a>
		</p>
	<?php }
}

/**
 * Banner on Portfolio Edit Page
 */
if ( ! function_exists( 'prtfl_categories_meta_box' ) ) {
	function prtfl_categories_meta_box() {
		global $prtfl_plugin_info, $wp_version; ?>
		<div class="bws_pro_version_bloc">
			<div class="bws_pro_version_table_bloc">
				<div class="bws_table_bg" style="top: 0px;"></div>
				<div class="prtfl_portfolio_categoriesdiv">
					<div class="inside">
						<div class="">
							<ul class="category-tabs">
								<li class="tabs"><a href="#"><?php _e( 'All Categories', 'portfolio' ); ?></a></li>
								<li><a href="#"><?php _e( 'Most Used', 'portfolio' ); ?></a></li>
							</ul>
							<div class="tabs-panel" style="display: none;">
								<ul class="categorychecklist form-no-clear">				
									<li class="popular-category">
										<label class="selectit"><input checked="checked" disabled="disabled" value="236" type="checkbox" /><?php _e( 'Uncatgorized', 'portfolio' ); ?></label>
									</li>
								</ul>
							</div>
							<div class="tabs-panel">		
								<ul class="categorychecklist form-no-clear">				
									<li class="popular-category"><label class="selectit"><input value="236" name="tax_input[portfolio_categories][]" checked="checked" disabled="disabled" type="checkbox" /> <?php _e( 'Uncatgorized', 'portfolio' ); ?></label></li>
								</ul>
							</div>
							<div class="wp-hidden-children">
								<h4><a href="#">+ <?php _e( 'Add New Category', 'portfolio' ); ?></a></h4>
								<p class="category-add wp-hidden-child">
									<label class="screen-reader-text"><?php _e( 'Add New Category', 'portfolio' ); ?></label>
									<input name="newportfolio_categories" class="form-required form-input-tip" value="<?php _e( 'New Category Name', 'portfolio' ); ?>" type="text" disabled="disabled" /><label class="screen-reader-text"><?php _e( 'Parent Category', 'portfolio' ); ?>:</label>
									<select name="newportfolio_categories_parent" class="postform">
										<option value="-1">— <?php _e( 'Parent Category', 'portfolio' ); ?> —</option>
										<option class="level-0" value="236"><?php _e( 'Uncatgorized', 'portfolio' ); ?></option>
									</select>
									<input class="button category-add-submit" value="<?php _e( 'Add New Category', 'portfolio' ); ?>" type="button" disabled="disabled" />
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="bws_pro_version_tooltip">
					<div class="bws_info">
						<?php _e( 'Unlock premium options by upgrading to Pro version', 'portfolio' ); ?> 
					</div>
					<a class="bws_button" href="http://bestwebsoft.com/products/portfolio/?k=f047e20c92c972c398187a4f70240285&pn=74&v=<?php echo $prtfl_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Portfolio Pro Plugin"><?php _e( 'Learn More', 'portfolio' ); ?></a>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	<?php }
}

/* This is the default text field meta box */
if ( ! function_exists( 'prtfl_text_field' ) ) {
	function prtfl_text_field( $args ) {
		global $post;
		$description  = ( ! empty( $args[2] ) ) ? $args[2]: '';
		$post_meta    = get_post_meta( $post->ID, 'prtfl_information', true );
		if ( ! empty( $post_meta ) && is_array( $post_meta ) )		
			$args[2] = esc_html( $post_meta[ $args[0] ] );
		elseif ( '1' != get_option( 'prtfl_postmeta_update' ) && $old_meta = get_post_meta( $post->ID, $args[0], true ) )
			$args[2] = esc_html( $old_meta );
		else
			$args[2] = '';

		$label_format =
			'<div class="portfolio_admin_box">' .
			'<p><label for="%1$s"><strong>%2$s</strong></label></p>' .
			'<p><input style="width: 80%%;" type="text" name="%1$s" id="%1$s" value="%3$s" /></p>' .
			'<p><em>' . $description .'</em></p>' .
			'</div>';
		if ( '_prtfl_date_compl' == $args[0] ) { ?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery( '#_prtfl_date_compl' ).datepicker({
						dateFormat : 'dd.mm.yy'
					});
				});
			</script>
		<?php }

		return vsprintf( $label_format, $args );
	}
}

/* When the post is saved, saves our custom data */
if ( ! function_exists ( 'prtfl_save_postdata' ) ) {
	function prtfl_save_postdata( $post_id, $post ) {
		global $prtfl_boxes;

		register_prtfl_settings();

		if ( "portfolio" == $post->post_type && ! wp_is_post_revision( $post_id ) && ! empty( $_POST ) ) { /* Don't store custom data twice */
			/* Verify this came from the our screen and with proper authorization, because save_post can be triggered at other times */
			if ( ! current_user_can ( 'edit_page', $post->ID ) ) {
				return $post->ID;
			}

			/* We'll put it into an array to make it easier to loop though. The data is already in $prtfl_boxes, but we need to flatten it out. */
			foreach ( $prtfl_boxes as $prtfl_boxe ) {
				foreach ( $prtfl_boxe as $prtfl_fields ) {
					if ( $prtfl_fields[0] == '_prtfl_link' || $prtfl_fields[0] == '_prtfl_svn' )
						$my_data[ $prtfl_fields[0] ] = esc_url( $_POST[ $prtfl_fields[0] ] );
					else
						$my_data[ $prtfl_fields[0] ] = stripslashes( esc_html( $_POST[ $prtfl_fields[0] ] ) );
				}
			}
			/*	Add values of $my_data as custom fields. Let's cycle through the $my_data array! */
			if ( get_post_meta( $post->ID, 'prtfl_information', FALSE ) ) {
				/* Custom field has a value and this custom field exists in database */
				update_post_meta( $post->ID, 'prtfl_information', $my_data );
			} elseif ( $value ) {
				/* Custom field has a value, but this custom field does not exist in database */
				add_post_meta( $post->ID, 'prtfl_information', $my_data );
			} else {
				/* Custom field does not have a value, but this custom field exists in database */
				update_post_meta( $post->ID, 'prtfl_information', $my_data );
			}
		}
	}
}

/**
 * Replace shortcode [latest_portfolio_items] from portfolio content before portfolio saving
 */
if ( ! function_exists ( 'prtfl_content_save_pre' ) ) {
	function prtfl_content_save_pre( $content ) {
		global $post;
		if ( isset( $post ) && "portfolio" == $post->post_type && ! wp_is_post_revision( $post->ID ) && ! empty( $_POST ) ) {
			/* remove shortcode */
			$content = preg_replace( '/\[latest_portfolio_items count=[\d]*\]/', '', $content );
		}
		return $content;
	}
}

if ( ! function_exists( 'prtfl_template_redirect' ) ) {
	function prtfl_template_redirect() {
		global $wp_query, $post, $posts, $prtfl_filenames, $prtfl_themepath;
		if ( 'portfolio' == get_post_type() && "" == $wp_query->query_vars["s"] && ! isset( $wp_query->query_vars["technologies"] ) && ! isset( $wp_query->query_vars["portfolio_executor_profile"] ) ) {
			$file_exists_flag = true;
			foreach ( $prtfl_filenames as $filename ) {
				if ( ! file_exists( $prtfl_themepath . $filename ) )
					$file_exists_flag = false;
			}
			if ( $file_exists_flag ) {
				include( get_stylesheet_directory() . '/portfolio-post.php' );
				exit();
			}
		} elseif ( 'portfolio' == get_post_type() && ( isset( $wp_query->query_vars["technologies"] ) || isset( $wp_query->query_vars["portfolio_executor_profile"] ) ) ) {
			$file_exists_flag = true;
			foreach ( $prtfl_filenames as $filename ) {
				if ( ! file_exists( $prtfl_themepath . $filename ) )
					$file_exists_flag = false;
			}
			if ( $file_exists_flag ) {
				include( get_stylesheet_directory() . '/portfolio.php' );
				exit();
			}
		}
	}
}

/* this function add custom fields and images for PDF&Print plugin in Portfolio post */
if ( ! function_exists( 'prtfl_add_pdf_print_content' ) ) {
	function prtfl_add_pdf_print_content( $content ) {
		global $post;
		$current_post_type = get_post_type();
		$custom_content = '';

		if ( 'portfolio' == $current_post_type && ! empty( $post ) ) {
			global $prtfl_options;
			if ( ! $prtfl_options )
				$prtfl_options = get_option( 'prtfl_options' );

			$post_meta	= get_post_meta( $post->ID, 'prtfl_information', true );
			$user_id 	= get_current_user_id();
			
			if ( 1 == $prtfl_options['prtfl_date_additional_field'] ) {	
				$date_compl		=	isset( $post_meta['_prtfl_date_compl'] ) ? $post_meta['_prtfl_date_compl'] : '';
				if ( ! empty( $date_compl ) )
					$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_date_text_field'] .' </span> ' . $date_compl . '</p>';
			}
			
			if ( 1 == $prtfl_options['prtfl_link_additional_field'] && ! empty( $post_meta['_prtfl_link'] ) ) {

				if ( false !== parse_url( $post_meta['_prtfl_link'] ) ) {
					if ( ( 0 == $user_id && 0 == $prtfl_options['prtfl_link_additional_field_for_non_registered'] ) || 0 != $user_id )
						$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_link_text_field'] . '</span> <a href="' . $post_meta['_prtfl_link'] . '">' . $post_meta['_prtfl_link'] . '</a></p>';
					else
						$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_link_text_field'] . '</span> ' . $post_meta['_prtfl_link'] . '</p>';
				} else
					$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_link_text_field'] . '</span> ' . $post_meta['_prtfl_link'] . '</p>';
			}
			if ( 0 != $user_id ) {
				if ( 1 == $prtfl_options['prtfl_svn_additional_field'] && ! empty( $post_meta['_prtfl_svn'] ) )
					$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_svn_text_field'] . '</span> ' . $post_meta['_prtfl_svn'] . '</p>';
				
				if ( 1 == $prtfl_options['prtfl_executor_additional_field'] ) {
					$executors_profile = wp_get_object_terms( $post->ID, 'portfolio_executor_profile' );
					if ( ! empty( $executors_profile ) ) {
						$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_executor_text_field'] . '</span>';
						$count = 0;
						foreach ( $executors_profile as $profile ) {
							if ( $count > 0 )
								$custom_content .= ', ';
							$custom_content .= '<a href="' . get_term_link( $profile->slug, 'portfolio_executor_profile' ) . '" title="' . $profile->name . ' profile" target="_blank">' . $profile->name . '</a>';
							$count++;
						}
						$custom_content .= '</p>';
					}
				}
			}

		} elseif ( 'portfolio.php' == basename( get_page_template() ) ) {
			global $wp_query, $request, $prtfl_options, $pdfprnt_options_array, $pdfprntpr_options;
			
			if ( ! $prtfl_options )
				$prtfl_options = get_option( 'prtfl_options' );

			$count = 0;
			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} else {
				$paged = 1;
			}
			$per_page = $showitems = get_option( 'posts_per_page' ); 
			$technologies = isset( $wp_query->query_vars["technologies"] ) ? $wp_query->query_vars["technologies"] : "";
			$executor_profile = isset( $wp_query->query_vars["portfolio_executor_profile"] ) ? $wp_query->query_vars["portfolio_executor_profile"] : "";
			if ( "" != $technologies ) {
				$args = array(
					'post_type' 		=> 'portfolio',
					'post_status' 		=> 'publish',
					'orderby' 			=> $prtfl_options['prtfl_order_by'],
					'order'			 	=> $prtfl_options['prtfl_order'],
					'posts_per_page'	=> $per_page,
					'paged' 			=> $paged,
					'tax_query' 		=> array(
						array(
							'taxonomy' 	=> 'portfolio_technologies',
							'field' 	=> 'slug',
							'terms' 	=> $technologies
						)
					)
				);
			} else if ( "" != $executor_profile ) {
				$args = array(
					'post_type' 		=> 'portfolio',
					'post_status' 		=> 'publish',
					'orderby'			=> $prtfl_options['prtfl_order_by'],
					'order' 			=> $prtfl_options['prtfl_order'],
					'posts_per_page' 	=> $per_page,
					'paged' 			=> $paged,
					'tax_query' 		=> array(
						array(
							'taxonomy' 	=> 'portfolio_executor_profile',
							'field' 	=> 'slug',
							'terms' 	=> $executor_profile
						)
					)
				);
			} else {
				$args = array(
					'post_type'			=>	'portfolio',
					'post_status'		=>	'publish',
					'orderby'			=>	$prtfl_options['prtfl_order_by'],
					'order'				=>	$prtfl_options['prtfl_order'],
					'posts_per_page'	=>	$per_page,
					'paged'				=>	$paged
				);
			}

			$second_query = new WP_Query( $args );				
			$request = $second_query->request;

			if ( $second_query->have_posts() ) { 
				while ( $second_query->have_posts() ) {
					$second_query->the_post();
					$custom_content .= '<div class="portfolio_content entry-content">
						<div class="entry">';

					$post_meta = get_post_meta( $post->ID, 'prtfl_information', true );
					$user_id = get_current_user_id();
					
					$short_descr = isset( $post_meta['_prtfl_short_descr'] ) ? $post_meta['_prtfl_short_descr'] : '';
					if ( empty( $short_descr ) )
						$short_descr = get_the_excerpt();
					
					$title = get_the_title();
					if ( empty( $title ) )
						$title = '(' . __( 'No title', 'portfolio-pro' ) . ')';

					$post_thumbnail_id	=	get_post_thumbnail_id( $post->ID );
					if ( empty( $post_thumbnail_id ) ) {
						$args = array(
							'post_parent'		=>	$post->ID,
							'post_type'			=>	'attachment',
							'post_mime_type'	=>	'image',
							'numberposts'		=>	1
						);
						$attachments		= get_children( $args );
						$post_thumbnail_id	= key( $attachments );
					}

					if ( ( isset( $pdfprnt_options_array['show_featured_image'] ) && 1 == $pdfprnt_options_array['show_featured_image'] )
						|| ( isset( $pdfprntpr_options['show_featured_image'] ) && 1 == $pdfprntpr_options['show_featured_image'] ) ) {
						$image		= wp_get_attachment_image_src( $post_thumbnail_id, 'portfolio-thumb' );
						$image_alt 	= get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
						if ( ! empty( $image[0] ) )
							$custom_content .= '<div class="portfolio_thumb"><img src="' . $image[0] . '" width="' . $prtfl_options['prtfl_custom_size_px'][0][0] . '" height="' . $prtfl_options['prtfl_custom_size_px'][0][1] . '" alt="' . $image_alt . '" /></div>';
					}
					
					$custom_content .= '<div class="portfolio_short_content">
								<div class="item_title">
									<p><a href="' . get_permalink() . '" rel="bookmark">' . $title . '</a></p>
								</div>';
					if ( 1 == $prtfl_options['prtfl_date_additional_field'] ) {
						$date_compl	= isset( $post_meta['_prtfl_date_compl'] ) ? $post_meta['_prtfl_date_compl'] : '';
						if ( ! empty( $date_compl ) )
							$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_date_text_field'] . '</span> ' . $date_compl . '</p>';
					}
								
					if ( 1 == $prtfl_options['prtfl_link_additional_field'] && ! empty( $post_meta['_prtfl_link'] ) ) {
						if ( false !== parse_url( $post_meta['_prtfl_link'] ) ) {
							if ( ( 0 == $user_id && 0 == $prtfl_options['prtfl_link_additional_field_for_non_registered'] ) || 0 != $user_id )
								$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_link_text_field'] . '</span> <a href="' . $post_meta['_prtfl_link'] . '">' . $post_meta['_prtfl_link'] . '</a></p>';
							else
								$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_link_text_field'] . '</span> ' . $post_meta['_prtfl_link'] . '</p>';
						} else
							$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_link_text_field'] . '</span> ' . $post_meta['_prtfl_link'] . '</p>';
					}
					if ( 1 == $prtfl_options['prtfl_shrdescription_additional_field'] ) {
						$custom_content .= '<p><span class="lable">' . $prtfl_options['prtfl_shrdescription_text_field'] . '</span> ' . $short_descr . '</p>';
					}
					$custom_content .= '</div>
						</div>
					</div>';
				}
			}			
		}
		return $content . $custom_content;
	}
}

if ( ! function_exists( 'prtfl_add_portfolio_ancestor_to_menu' ) ) {
	function prtfl_add_portfolio_ancestor_to_menu( $classes, $item ) {
		if ( is_singular( 'portfolio' ) ) {
			global $wpdb, $post;
			$parent = $wpdb->get_var( "SELECT $wpdb->posts.post_name FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'portfolio.php' AND (post_status = 'publish' OR post_status = 'private') AND $wpdb->posts.ID = $wpdb->postmeta.post_id" );

			if ( in_array( 'menu-item-' . $item->ID, $classes ) && $parent == strtolower( $item->title ) )
				$classes[] = 'current-page-ancestor';
		}
		return $classes;
	}
}

if ( ! function_exists( 'prtfl_latest_items' ) ) {
	function prtfl_latest_items( $atts ) {
		$content	=	'<div class="prtfl_portfolio_block">';
		if ( empty( $atts['count'] ) )
			$atts['count'] = 3;
		$args		=	array(
			'post_type'			=>	'portfolio',
			'post_status'		=>	'publish',
			'orderby'			=>	'date',
			'order'				=>	'DESC',
			'posts_per_page'	=>	$atts['count'],
			);
		query_posts( $args );

		while ( have_posts() ) : the_post();
			$content .= '
			<div class="portfolio_content">
				<div class="entry">';
					global $post;
					$post_thumbnail_id	=	get_post_thumbnail_id( $post->ID );
					if( empty ( $post_thumbnail_id ) ) {
						$args = array(
							'post_parent'		=>	$post->ID,
							'post_type'			=>	'attachment',
							'post_mime_type'	=>	'image',
							'numberposts'		=>	1
						);
						$attachments		=	get_children( $args );
						$post_thumbnail_id	=	key($attachments);
					}
					$image		=	wp_get_attachment_image_src( $post_thumbnail_id, 'portfolio-thumb' );
					$image_alt	=	get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
					$image_desc	=	get_post( $post_thumbnail_id );
					$image_desc	=	$image_desc->post_content;
					$post_meta	=	get_post_meta( $post->ID, 'prtfl_information', true );
					
					$date_compl  =	isset( $post_meta['_prtfl_date_compl'] ) ? $post_meta['_prtfl_date_compl'] : '';
					$link			=	isset( $post_meta['_prtfl_link'] ) ? $post_meta['_prtfl_link'] : '';
					$short_descr	=	isset( $post_meta['_prtfl_short_descr'] ) ? $post_meta['_prtfl_short_descr'] : '';
					if ( empty( $short_descr ) )
						$short_descr = get_the_excerpt();
					$title = get_the_title();
					if ( empty( $title ) )
						$title = '(' . __( 'No title', 'portfolio-pro' ) . ')';
					$permalink = get_permalink();
					
					$content .= '<div class="portfolio_thumb" style="width:165px">
							<img src="' . $image[0] . '" width="' . $image[1] . '" alt="' . $image_alt . '" />
					</div>
					<div class="portfolio_short_content">
						<div class="item_title">
							<p>
								<a href="' . $permalink . '" rel="bookmark">' . $title . '</a>
							</p>
						</div> <!-- .item_title -->';
						$content .= '<p>' . $short_descr . '</p>
					</div> <!-- .portfolio_short_content -->
				</div> <!-- .entry -->
				<div class="read_more">
					<a href="' . $permalink . '" rel="bookmark">' . __( 'Read more', 'portfolio' ) . '</a>
				</div> <!-- .read_more -->
				<div class="portfolio_terms">';
				$terms = wp_get_object_terms( $post->ID, 'portfolio_technologies' );
				if ( is_array( $terms ) && 0 < count( $terms ) ) {
					$content .= __( 'Technologies', 'portfolio' ) . ':';
					$count = 0;
					foreach ( $terms as $term ) {
						if ( $count > 0 )
							$content .= ', ';
						$content .= '<a href="' . get_term_link( $term->slug, 'portfolio_technologies') . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name . '</a>';
						$count++;
					}
				} else {
					$content .= '&nbsp;';
				}
				$content .= '</div><!-- .portfolio_terms -->';
			$content .= '<div class="prtfl_clear"></div></div> <!-- .portfolio_content -->';
		endwhile;
		$content .= '</div> <!-- .prtfl_portfolio_block -->';
		wp_reset_query();
		return $content;
	}
}

/* Register style and script files */
if ( ! function_exists ( 'prtfl_admin_head' ) ) {
	function prtfl_admin_head() {
		global $prtfl_plugin_info, $post_type;
		wp_enqueue_style( 'prtfl_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		wp_enqueue_style( 'prtfl_jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

		if ( isset( $_GET['page'] ) && "portfolio.php" == $_GET['page'] ) {
			wp_enqueue_script( 'prtfl_script', plugins_url( 'js/script.js', __FILE__ ) );
			wp_localize_script( 'prtfl_script', 'prtfl_var', array(
				'prtfl_nonce' 			=> wp_create_nonce( plugin_basename( __FILE__ ), 'prtfl_ajax_nonce_field' ),
				'update_img_message'	=> __( 'Updating images...', 'portfolio' ),
				'not_found_img_info'	=> __( 'No image found', 'portfolio'),
				'img_success'			=> __( 'All images are updated', 'portfolio' ),
				'img_error'				=> __( 'Error.', 'portfolio' ) ) );
		}
		wp_enqueue_script( 'jquery-ui-datepicker' );
	}
}

if ( ! function_exists ( 'prtfl_wp_head' ) ) {
	function prtfl_wp_head() {
		wp_enqueue_style( 'prtfl_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		
		if ( ! function_exists( 'is_plugin_active' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		
		$all_plugins = get_plugins();
		
		if ( ! is_plugin_active( 'gallery-plugin-pro/gallery-plugin-pro.php' ) || ( isset( $all_plugins["gallery-plugin-pro/gallery-plugin-pro.php"]["Version"] ) && "1.3.0" >= $all_plugins["gallery-plugin-pro/gallery-plugin-pro.php"]["Version"] ) ) { 
			wp_enqueue_style( 'prtfl_lightbox_stylesheet', plugins_url( 'fancybox/jquery.fancybox-1.3.4.css', __FILE__ ) );
			wp_enqueue_script( 'prtfl_fancybox_mousewheelJs', plugins_url( 'fancybox/jquery.mousewheel-3.0.4.pack.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'prtfl_fancyboxJs', plugins_url( 'fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__ ), array( 'jquery' ) );
		}
	}
}

if ( ! function_exists ( 'prtfl_update_image' ) ) {
	function prtfl_update_image() {
		global $wpdb;
		check_ajax_referer( plugin_basename( __FILE__ ), 'prtfl_ajax_nonce_field' );
		$action	=	isset( $_REQUEST['action1'] ) ? $_REQUEST['action1'] : "";
		$id		=	isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : "";
		switch ( $action ) {
			case 'get_all_attachment':
				$result_parent_id	=	$wpdb->get_results( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = %s", 'portfolio' ) , ARRAY_N );
				$array_parent_id	=	array();

				while ( list( $key, $val ) = each( $result_parent_id ) )
					$array_parent_id[] = $val[0];

				$string_parent_id = implode( ",", $array_parent_id );

				$result_attachment_id = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'attachment' AND post_mime_type LIKE 'image%' AND post_parent IN (" . $string_parent_id . ")" );
				echo json_encode( $result_attachment_id );
				break;
			case 'update_image':
				$metadata	=	wp_get_attachment_metadata( $id );
				$uploads	=	wp_upload_dir();
				$path		=	$uploads['basedir'] . "/" . $metadata['file'];
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$metadata_new = prtfl_wp_generate_attachment_metadata( $id, $path, $metadata );
				wp_update_attachment_metadata( $id, array_merge( $metadata, $metadata_new ) );
				break;
			case 'update_options':
				add_option( 'prtfl_images_update', '1', '', 'no' );
				break;
		}
		die();
	}
}

if ( ! function_exists ( 'prtfl_wp_generate_attachment_metadata' ) ) {
	function prtfl_wp_generate_attachment_metadata( $attachment_id, $file, $metadata ) {
		global $prtfl_options;
		$attachment		=	get_post( $attachment_id );
		add_image_size( 'portfolio-thumb', $prtfl_options['prtfl_custom_size_px'][0][0], $prtfl_options['prtfl_custom_size_px'][0][1], true );
		add_image_size( 'portfolio-photo-thumb', $prtfl_options['prtfl_custom_size_px'][1][0], $prtfl_options['prtfl_custom_size_px'][1][1], true );

		$metadata = array();
		if ( preg_match('!^image/!', get_post_mime_type( $attachment ) ) && file_is_displayable_image( $file ) ) {
			$imagesize					=	getimagesize( $file );
			$metadata['width']			=	$imagesize[0];
			$metadata['height']			=	$imagesize[1];
			list($uwidth, $uheight)		=	wp_constrain_dimensions( $metadata['width'], $metadata['height'], 128, 96 );
			$metadata['hwstring_small']	=	"height='$uheight' width='$uwidth'";

			/* Make the file path relative to the upload dir */
			$metadata['file']= _wp_relative_upload_path( $file );

			/* Make thumbnails and other intermediate sizes */
			global $_wp_additional_image_sizes;

			$image_size = array( 'portfolio-thumb', 'portfolio-photo-thumb' );/* get_intermediate_image_sizes(); */

			foreach ( $image_size as $s ) {
				$sizes[ $s ] = array( 'width' => '', 'height' => '', 'crop' => FALSE );
				if ( isset( $_wp_additional_image_sizes[ $s ]['width'] ) )
					$sizes[ $s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); /* For theme-added sizes */
				else
					$sizes[ $s ]['width'] = get_option( "{$s}_size_w" ); /* For default sizes set in options */
				if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
					$sizes[ $s ]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] ); /* For theme-added sizes */
				else
					$sizes[ $s ]['height'] = get_option( "{$s}_size_h" ); /* For default sizes set in options */
				if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
					$sizes[ $s ]['crop'] = intval( $_wp_additional_image_sizes[$s]['crop'] ); /* For theme-added sizes */
				else
					$sizes[ $s ]['crop'] = get_option( "{$s}_crop" ); /* For default sizes set in options */
			}

			$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );
			foreach ( $sizes as $size => $size_data ) {
				$resized = prtfl_image_make_intermediate_size( $file, $size_data['width'], $size_data['height'], $size_data['crop'] );
				if ( $resized )
					$metadata['sizes'][$size] = $resized;
			}

			/* Fetch additional metadata from exif/iptc */
			$image_meta = wp_read_image_metadata( $file );
			if ( $image_meta )
				$metadata['image_meta'] = $image_meta;
		}
		return apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
	}
}

if ( ! function_exists ( 'prtfl_image_make_intermediate_size' ) ) {
	function prtfl_image_make_intermediate_size( $file, $width, $height, $crop=false ) {
		if ( $width || $height ) {
			$resized_file = prtfl_image_resize( $file, $width, $height, $crop );
			if ( ! is_wp_error( $resized_file ) && $resized_file && $info = getimagesize( $resized_file ) ) {
				$resized_file = apply_filters( 'image_make_intermediate_size', $resized_file );
				return array(
					'file'		=>	wp_basename( $resized_file ),
					'width'		=>	$info[0],
					'height'	=>	$info[1],
				);
			}
		}
		return false;
	}
}

if ( ! function_exists ( 'prtfl_image_resize' ) ) {
	function prtfl_image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		$size = @getimagesize( $file );
		if ( !$size )
			return new WP_Error( 'invalid_image', __( 'Image size not defined', 'portfolio' ), $file );
		$type = $size[2];

		if ( 3 == $type )
			$image = imagecreatefrompng( $file );
		else if ( 2 == $type )
			$image = imagecreatefromjpeg( $file );
		else if ( 1 == $type )
			$image = imagecreatefromgif( $file );
		else if ( 15 == $type )
			$image = imagecreatefromwbmp( $file );
		else if ( 16 == $type )
			$image = imagecreatefromxbm( $file );
		else
			return new WP_Error( 'invalid_image', __( 'We can update only PNG, JPEG, GIF, WPMP or XBM filetype. For other, please, manually reload image.', 'portfolio' ), $file );

		if ( ! is_resource( $image ) )
			return new WP_Error( 'error_loading_image', $image, $file );

		/* $size = @getimagesize( $file ); */
		list( $orig_w, $orig_h, $orig_type ) = $size;
		$dims = prtfl_image_resize_dimensions($orig_w, $orig_h, $max_w, $max_h, $crop);

		if ( ! $dims )
			return new WP_Error( 'error_getting_dimensions', __( 'Image size changes not defined', 'portfolio' ) );
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;
		$newimage = wp_imagecreatetruecolor( $dst_w, $dst_h );
		imagecopyresampled( $newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );
		/* Convert from full colors to index colors, like original PNG. */
		if ( IMAGETYPE_PNG == $orig_type && function_exists( 'imageistruecolor' ) && ! imageistruecolor( $image ) )
			imagetruecolortopalette( $newimage, false, imagecolorstotal( $image ) );
		/* We don't need the original in memory anymore */
		imagedestroy( $image );

		/* $suffix will be appended to the destination filename, just before the extension */
		if ( ! $suffix )
			$suffix = "{$dst_w}x{$dst_h}";

		$info	=	pathinfo( $file );
		$dir	=	$info['dirname'];
		$ext	=	$info['extension'];
		$name	=	wp_basename( $file, ".$ext" );

		if ( ! is_null( $dest_path ) and $_dest_path = realpath( $dest_path ) )
			$dir = $_dest_path;
		$destfilename = "{$dir}/{$name}-{$suffix}.{$ext}";

		if ( IMAGETYPE_GIF == $orig_type ) {
			if ( ! imagegif( $newimage, $destfilename ) )
				return new WP_Error( 'resize_path_invalid', __( 'Invalid path', 'portfolio' ) );
		} elseif ( IMAGETYPE_PNG == $orig_type ) {
			if ( ! imagepng( $newimage, $destfilename ) )
				return new WP_Error( 'resize_path_invalid', __( 'Invalid path', 'portfolio' ) );
		} else {
			/* All other formats are converted to jpg */
			$destfilename = "{$dir}/{$name}-{$suffix}.jpg";
			if ( ! imagejpeg( $newimage, $destfilename, apply_filters( 'jpeg_quality', $jpeg_quality, 'image_resize' ) ) )
				return new WP_Error( 'resize_path_invalid', __( 'Invalid path', 'portfolio' ) );
		}

		imagedestroy( $newimage );
		/* Set correct file permissions */
		$stat	=	stat( dirname( $destfilename ) );
		$perms	=	$stat['mode'] & 0000666; /* Same permissions as parent folder, strip off the executable bits */
		@chmod( $destfilename, $perms );
		return $destfilename;
	}
}

if ( ! function_exists ( 'prtfl_image_resize_dimensions' ) ) {
	function prtfl_image_resize_dimensions( $orig_w, $orig_h, $dest_w, $dest_h, $crop = false ) {

		if ( 0 >= $orig_w || 0 >= $orig_h )
			return false;
		/* At least one of dest_w or dest_h must be specific */
		if ( 0 >= $dest_w && 0 >= $dest_h )
			return false;

		if ( $crop ) {
			/* Crop the largest possible portion of the original image that we can size to $dest_w x $dest_h */
			$aspect_ratio	=	$orig_w / $orig_h;
			$new_w			=	min( $dest_w, $orig_w );
			$new_h			=	min( $dest_h, $orig_h );

			if ( ! $new_w ) {
				$new_w = intval( $new_h * $aspect_ratio );
			}

			if ( ! $new_h ) {
				$new_h = intval( $new_w / $aspect_ratio );
			}

			$size_ratio	=	max( $new_w / $orig_w, $new_h / $orig_h );
			$crop_w		=	round( $new_w / $size_ratio );
			$crop_h		=	round( $new_h / $size_ratio );
			$s_x		=	floor( ( $orig_w - $crop_w ) / 2 );
			$s_y		=	0;
		} else {
			/* Don't crop, just resize using $dest_w x $dest_h as a maximum bounding box */
			$crop_w	=	$orig_w;
			$crop_h	=	$orig_h;
			$s_x	=	0;
			$s_y	=	0;
			list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
		}

		/* If the resulting image would be the same size or larger we don't want to resize it */
		if ( $new_w >= $orig_w && $new_h >= $orig_h )
			return false;
		/* The return array matches the parameters to imagecopyresampled() */
		/* Int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h */
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}
}


if ( ! function_exists( 'prtfl_sanitize_file_name' ) ) {
	function prtfl_sanitize_file_name( $file_name ) {
		global $prtfl_options;
		if ( isset( $_REQUEST['post_id'] ) && 'portfolio' == get_post_type( $_REQUEST['post_id'] )
			&& isset( $prtfl_options['prtfl_rename_file'] ) && $prtfl_options['prtfl_rename_file'] == 1 ) {	
			$file_name_old = explode( '.', $file_name );
			$file_name_new = preg_replace( '/--+/', '-', preg_replace( '/[^a-zA-Z0-9_-]/', '', $file_name_old[0] ) );

			if ( $file_name_new == '' || $file_name_new == '-' ) {
				$slug = isset( $prtfl_options['prtfl_slug'] ) && ! empty( $prtfl_options['prtfl_slug'] ) ? $prtfl_options['prtfl_slug'] : 'portfolio';
				$file_name_new = $slug . '-' . time();
			}
			$file_name = $file_name_new . '.' . $file_name_old[1];
		}
		return $file_name;
	}
}

if ( ! function_exists( 'prtfl_filter_image_sizes' ) ) {
	function prtfl_filter_image_sizes( $sizes ) {
		if ( isset( $_REQUEST['post_id'] ) && 'portfolio' == get_post_type( $_REQUEST['post_id'] ) ) {
			$prtfl_image_size = array( 'portfolio-thumb', 'portfolio-photo-thumb', 'large' );
			foreach ( $sizes as $key => $value ) {
				if ( ! in_array( $key, $prtfl_image_size ) ) {
					unset( $sizes[ $key ] );
				}
			}
		}
		return $sizes;
	}
}

if ( ! function_exists ( 'prtfl_theme_body_classes' ) ) {
	function prtfl_theme_body_classes( $classes ) {
		if ( function_exists( 'wp_get_theme' ) ) {
			$current_theme = wp_get_theme();
			$classes[] = 'prtfl_' . basename( $current_theme->get( 'ThemeURI' ) );
		}
		return $classes;
	}
}

if ( ! function_exists ( 'prtfl_register_plugin_links' ) ) {
	function prtfl_register_plugin_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[]	=	'<a href="admin.php?page=portfolio.php">' . __( 'Settings', 'portfolio' ) . '</a>';
			$links[]	=	'<a href="http://wordpress.org/plugins/portfolio/faq/" target="_blank">' . __( 'FAQ', 'portfolio' ) . '</a>';
			$links[]	=	'<a href="http://support.bestwebsoft.com">' . __( 'Support', 'portfolio' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists ( 'prtfl_plugin_action_links' ) ) {
	function prtfl_plugin_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			/* Static so we don't call plugin_basename on every plugin row. */
			static $this_plugin;
			if ( ! $this_plugin ) $this_plugin = plugin_basename( __FILE__ );

			if ( $file == $this_plugin ) {
				$settings_link = '<a href="admin.php?page=portfolio.php">' . __( 'Settings', 'portfolio' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}

if ( ! function_exists ( 'prtfl_admin_notices' ) ) {
	function prtfl_admin_notices() {
		global $hook_suffix, $prtfl_plugin_info, $prtfl_options;
		
		if ( 'plugins.php' == $hook_suffix ) {
			/* Get options from the database */
			if ( ! $prtfl_options )
				$prtfl_options = get_option( 'prtfl_options' );

			if ( isset( $prtfl_options['first_install'] ) && strtotime( '-1 week' ) > $prtfl_options['first_install'] )
				bws_plugin_banner( $prtfl_plugin_info, 'prtfl', 'portfolio', '56e6c97d1bca3199fb16cb817793a8f6', '74', '//ps.w.org/portfolio/assets/icon-128x128.png' );

			if ( ! is_network_admin() )
				bws_plugin_banner_to_settings( $prtfl_plugin_info, 'prtfl_options', 'portfolio', 'admin.php?page=portfolio.php', 'post-new.php?post_type=portfolio', 'Portfolio' );

			if ( $prtfl_options['widget_updated'] == 0 ) {
				/* Save data for settings page */
				if ( isset( $_REQUEST['prtfl_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'prtfl_nonce_name' ) ) {
					$prtfl_options['widget_updated'] = 1;
					update_option( 'prtfl_options', $prtfl_options );
				} else { ?>
					<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
						<div class="prtfl_admin_notices bws_banner_on_plugin_page">
							<form method="post" action="<?php echo $hook_suffix; ?>">
								<div class="text">
									<p>
										<strong><?php _e( "ATTENTION!", 'portfolio' ); ?></strong>
										<?php _e( "In the current version of Portfolio plugin we updated the Technologies widget. If it was added to the sidebar, it will disappear and you will have to add it again.", 'portfolio' ); ?>
									</p>
									<input type="hidden" name="prtfl_form_submit" value="submit" />
									<p class="submit">
										<input type="submit" class="button-primary" value="<?php _e( 'Read and Understood', 'portfolio' ); ?>" />
									</p>
									<?php wp_nonce_field( plugin_basename( __FILE__ ), 'prtfl_nonce_name' ); ?>
								</div>
							</form>
						</div>
					</div>
				<?php }
			}
		}
	}
}

/* add shortcode content  */
if ( ! function_exists( 'prtfl_shortcode_button_content' ) ) {
	function prtfl_shortcode_button_content( $content ) { 
		global $wp_version; ?>
		<div id="prtfl" style="display:none;">
			<fieldset>
				<label>
					<input type="number" value="3" min="0" max="1000" name="prtfl_display_count" id="prtfl_display_count" class="small-text" /> 
					<span>
						<?php _e( 'The number of portfolio to display', 'portfolio' ); ?>
					</span>				
				</label>
			</fieldset>
			<input class="bws_default_shortcode" type="hidden" name="default" value="[latest_portfolio_items count=3]" />
			<script type="text/javascript">
				function prtfl_shortcode_init() {
					(function($) {	
						<?php if ( $wp_version < '3.9' ) { ?>	
							var current_object = '#TB_ajaxContent';
						<?php } else { ?>
							var current_object = '.mce-reset';
						<?php } ?>			

						$( current_object + ' #prtfl_display_count' ).on( 'change', function() {
							var count = $( current_object + ' #prtfl_display_count' ).val();
							var shortcode = '[latest_portfolio_items count=' + count + ']';
							$( current_object + ' #bws_shortcode_display' ).text( shortcode );
						});	         
					})(jQuery);
				}
			</script>
			<div class="clear"></div>
		</div>
	<?php }
}

/* add help tab  */
if ( ! function_exists( 'prtfl_add_tabs' ) ) {
	function prtfl_add_tabs() {
		$screen = get_current_screen();
		if ( ( ! empty( $screen->post_type ) && 'portfolio' == $screen->post_type ) ||
			( ! empty( $screen->taxonomy ) && 'portfolio_executor_profile' == $screen->taxonomy ) ||
			( ! empty( $screen->taxonomy ) && 'portfolio_technologies' == $screen->taxonomy ) ||
			( isset( $_GET['page'] ) && $_GET['page'] == 'portfolio.php' ) ) {
			$args = array(
				'id' 			=> 'prtfl',
				'section' 		=> '200538929'
			);
			bws_help_tab( $screen, $args );
		}
	}
}

if ( ! function_exists( 'prtfl_plugin_uninstall' ) ) {
	function prtfl_plugin_uninstall() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( file_exists( get_stylesheet_directory() . '/portfolio.php' ) && ! unlink( get_stylesheet_directory() . '/portfolio.php' ) )
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );
		if ( file_exists( get_stylesheet_directory() . '/portfolio-post.php' ) && ! unlink( get_stylesheet_directory() . '/portfolio-post.php' ) )
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );

		$plugins_list = get_plugins();		

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$old_blog = $wpdb->blogid;
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				if ( ! array_key_exists( 'portfolio-pro/portfolio-pro.php', $plugins_list ) ) 
					delete_option( 'widget_portfolio_technologies_widget' );	
				delete_option( 'prtfl_options' );
				delete_option( 'prtfl_tag_update' );
			}
			switch_to_blog( $old_blog );
		} else {
			if ( ! array_key_exists( 'portfolio-pro/portfolio-pro.php', $plugins_list ) ) 
				delete_option( 'widget_portfolio_technologies_widget' );	
			delete_option( 'prtfl_options' );
			delete_option( 'prtfl_tag_update' );
		}
	}
}

register_activation_hook( __FILE__, 'prtfl_plugin_install' ); /* Activate plugin */
/* Add portfolio settings page in admin menu */
add_action( 'admin_menu', 'add_prtfl_admin_menu' );
add_action( 'admin_init', 'prtfl_admin_init' );
add_action( 'init', 'prtfl_init' );
add_action( 'plugins_loaded', 'prtfl_plugins_loaded' );
add_action( 'wp_loaded', 'prtfl_flush_rules' );
/* Save custom data from admin  */
add_action( 'save_post', 'prtfl_save_postdata', 1, 2 );
add_filter( 'content_save_pre', 'prtfl_content_save_pre', 10, 1 );

/* Add template for single portfolio page */
add_action( 'template_redirect', 'prtfl_template_redirect' );

/* this function add custom fields and images for PDF&Print plugin in Portfolio post */
add_filter( 'bwsplgns_get_pdf_print_content', 'prtfl_add_pdf_print_content' );

/* Add template in theme after activate new theme */
add_action( 'after_switch_theme', 'prtfl_after_switch_theme', 10, 2 );

add_action( 'admin_enqueue_scripts', 'prtfl_admin_head' );
add_action( 'wp_enqueue_scripts', 'prtfl_wp_head' );

/* add theme name as class to body tag */
add_filter( 'body_class', 'prtfl_theme_body_classes' );

/* Add widget for portfolio technologies */
add_action( 'widgets_init', 'prtfl_register_widget' );

add_action( 'wp_ajax_prtfl_update_image', 'prtfl_update_image' );

add_shortcode( 'latest_portfolio_items', 'prtfl_latest_items' );
/* custom filter for bws button in tinyMCE */
add_filter( 'bws_shortcode_button_content', 'prtfl_shortcode_button_content' );

add_filter( 'request', 'prtfl_request_filter' );
/* Display tachnologies taxonomy */
add_filter( 'pre_get_posts', 'prtfl_technologies_get_posts' );
add_filter( 'rewrite_rules_array', 'prtfl_custom_permalinks' );
/* Additional links on the plugin page */
add_filter( 'plugin_row_meta', 'prtfl_register_plugin_links', 10, 2 );
add_filter( 'plugin_action_links', 'prtfl_plugin_action_links', 10, 2 );

add_filter( 'nav_menu_css_class', 'prtfl_add_portfolio_ancestor_to_menu', 10, 2 );

add_filter( 'sanitize_file_name', 'prtfl_sanitize_file_name' );
add_filter( 'intermediate_image_sizes_advanced', 'prtfl_filter_image_sizes' );

add_action( 'admin_notices', 'prtfl_admin_notices');

register_uninstall_hook( __FILE__, 'prtfl_plugin_uninstall' ); /* Deactivate plugin */