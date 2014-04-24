<?php
/*
Plugin Name: Portfolio
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin for portfolio.
Author: BestWebSoft
Version: 2.24
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*
	@ Copyright 2014  BestWebSoft  ( http://support.bestwebsoft.com )

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
$prtfl_boxes = array();

if ( ! function_exists( 'prtfl_plugin_install' ) ) {
	function prtfl_plugin_install() {
		global $wpmu, $prtfl_options;
		$filename_1			=	WP_PLUGIN_DIR . '/portfolio/template/portfolio.php';
		$filename_2			=	WP_PLUGIN_DIR . '/portfolio/template/portfolio-post.php';
		$filename_theme_1	=	get_stylesheet_directory() . '/portfolio.php';
		$filename_theme_2	=	get_stylesheet_directory() . '/portfolio-post.php';
		
		register_prtfl_settings();
		
		if ( ! file_exists( $filename_theme_1 ) ) {
			$handle = @fopen( $filename_1, "r" );
			$contents = @fread( $handle, filesize( $filename_1 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_1, 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			chmod( $filename_theme_1, octdec(755) );
		} else {
			if ( isset( $prtfl_options['prtfl_rewrite_template'] ) && 0 == $prtfl_options['prtfl_rewrite_template'] ) {
				/**/
			} else {
				$handle = @fopen( $filename_theme_1, "r" );
				$contents = @fread( $handle, filesize( $filename_theme_1 ) );
				@fclose( $handle );
				if ( ! ( $handle = @fopen( $filename_theme_1.'.bak', 'w' ) ) )
					return false;
				@fwrite( $handle, $contents );
				@fclose( $handle );

				$handle = @fopen( $filename_1, "r" );
				$contents = @fread( $handle, filesize( $filename_1 ) );
				@fclose( $handle );
				if ( ! ( $handle = @fopen( $filename_theme_1, 'w' ) ) )
					return false;
				@fwrite( $handle, $contents );
				@fclose( $handle );
				chmod( $filename_theme_1, octdec(755) );
			}
		}
		if ( ! file_exists( $filename_theme_2 ) ) {
			$handle = @fopen( $filename_2, "r" );
			$contents = @fread( $handle, filesize( $filename_2 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_2, 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			chmod( $filename_theme_2, octdec(755) );
		} else {
			if ( isset( $prtfl_options['prtfl_rewrite_template'] ) && 0 == $prtfl_options['prtfl_rewrite_template'] ) {
				/**/
			} else {
				$handle = @fopen( $filename_theme_2, "r" );
				$contents = @fread( $handle, filesize( $filename_theme_2 ) );
				@fclose( $handle );
				if ( ! ( $handle = @fopen( $filename_theme_2.'.bak', 'w' ) ) )
					return false;
				@fwrite( $handle, $contents );
				@fclose( $handle );

				$handle = @fopen( $filename_2, "r" );
				$contents = @fread( $handle, filesize( $filename_2 ) );
				@fclose( $handle );
				if ( ! ( $handle = @fopen( $filename_theme_2, 'w' ) ) )
					return false;
				@fwrite( $handle, $contents );
				@fclose( $handle );
				chmod( $filename_theme_2, octdec(755) );
			}
		}
	}
}

/* Function are using to add on admin-panel Wordpress page 'bws_plugins' and sub-page of this plugin */
if ( ! function_exists( 'add_prtfl_admin_menu' ) ) {
	function add_prtfl_admin_menu() {
		global $bstwbsftwppdtplgns_options, $wpmu, $bstwbsftwppdtplgns_added_menu;
		$bws_menu_version = '1.2.7';
		$base = plugin_basename( __FILE__ );

		if ( ! isset( $bstwbsftwppdtplgns_options ) ) {
			if ( 1 == $wpmu ) {
				if ( ! get_site_option( 'bstwbsftwppdtplgns_options' ) )
					add_site_option( 'bstwbsftwppdtplgns_options', array(), '', 'yes' );
				$bstwbsftwppdtplgns_options = get_site_option( 'bstwbsftwppdtplgns_options' );
			} else {
				if ( ! get_option( 'bstwbsftwppdtplgns_options' ) )
					add_option( 'bstwbsftwppdtplgns_options', array(), '', 'yes' );
				$bstwbsftwppdtplgns_options = get_option( 'bstwbsftwppdtplgns_options' );
			}
		}

		if ( isset( $bstwbsftwppdtplgns_options['bws_menu_version'] ) ) {
			$bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] = $bws_menu_version;
			unset( $bstwbsftwppdtplgns_options['bws_menu_version'] );
			update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options, '', 'yes' );
			require_once( dirname( __FILE__ ) . '/bws_menu/bws_menu.php' );
		} else if ( ! isset( $bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] ) || $bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] < $bws_menu_version ) {
			$bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] = $bws_menu_version;
			update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options, '', 'yes' );
			require_once( dirname( __FILE__ ) . '/bws_menu/bws_menu.php' );
		} else if ( ! isset( $bstwbsftwppdtplgns_added_menu ) ) {
			$plugin_with_newer_menu = $base;
			foreach ( $bstwbsftwppdtplgns_options['bws_menu']['version'] as $key => $value ) {
				if ( $bws_menu_version < $value && is_plugin_active( $base ) ) {
					$plugin_with_newer_menu = $key;
				}
			}
			$plugin_with_newer_menu = explode( '/', $plugin_with_newer_menu );
			$wp_content_dir = defined( 'WP_CONTENT_DIR' ) ? basename( WP_CONTENT_DIR ) : 'wp-content';
			if ( file_exists( ABSPATH . $wp_content_dir . '/plugins/' . $plugin_with_newer_menu[0] . '/bws_menu/bws_menu.php' ) )
				require_once( ABSPATH . $wp_content_dir . '/plugins/' . $plugin_with_newer_menu[0] . '/bws_menu/bws_menu.php' );
			else
				require_once( dirname( __FILE__ ) . '/bws_menu/bws_menu.php' );
			$bstwbsftwppdtplgns_added_menu = true;
		}

		add_menu_page( 'BWS Plugins', 'BWS Plugins', 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( "images/px.png", __FILE__ ), 1001 );
		add_submenu_page( 'bws_plugins', __( 'Portfolio', 'portfolio' ), __( 'Portfolio', 'portfolio' ), 'manage_options', "portfolio.php", 'prtfl_settings_page' );
	}
}

if ( ! function_exists( 'prtfl_admin_init' ) ) {
	function prtfl_admin_init() {
		global  $bws_plugin_info, $prtfl_plugin_info;

		$prtfl_plugin_info	=	get_plugin_data( __FILE__, false );

		if ( ! isset( $bws_plugin_info ) || empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '74', 'version' => $prtfl_plugin_info["Version"] );

		/* Function check if plugin is compatible with current WP version  */
		prtfl_version_check();
		/* Call register settings function */
		if ( ( isset( $_GET['page'] ) && "portfolio.php" == $_GET['page'] ) || ( isset( $_GET['post_type'] ) && 'portfolio' == $_GET['post_type'] )  )
			register_prtfl_settings();

		prtfl_admin_error();
	}
}

if ( ! function_exists ( 'prtfl_init' ) ) {
	function prtfl_init() {
		/* Add language file */
		global $prtfl_boxes;
		/* Internationalization, first(!) */
		load_plugin_textdomain( 'portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		$prtfl_boxes['Portfolio-Info'] = array(
			array( '_prtfl_short_descr', __( 'Short description', 'portfolio' ), __( 'A short description which you\'d like to be displayed on your portfolio page', 'portfolio' ), '', '' ),
			array( '_prtfl_date_compl', __( 'Date of completion', 'portfolio' ), __( 'The date when the task was completed', 'portfolio' ), '', '' ),
			array( '_prtfl_link', __( 'Link', 'portfolio' ), __( 'A link to the site', 'portfolio' ), '', '' ),
			array( '_prtfl_svn', __( 'SVN', 'portfolio' ), __( 'SVN URL', 'portfolio' ), '', '' ),
		);
		/* Register post type */
		prtfl_post_type_portfolio();
		/* Register taxonomy for portfolio */
		prtfl_taxonomy_portfolio();
		/* Add widget for portfolio technologies */
		prtfl_register_widget();
	}
}

/* Register settings function */
if ( ! function_exists( 'register_prtfl_settings' ) ) {
	function register_prtfl_settings() {
		global $wpmu, $prtfl_options, $prtfl_plugin_info;

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
			'plugin_option_version'					=> $prtfl_plugin_info["Version"]
		);

		/* Install the option defaults */
		if ( 1 == $wpmu ) {
			if ( ! get_site_option( 'prtfl_options' ) )
				add_site_option( 'prtfl_options', $prtfl_option_defaults, '', 'yes' );
		} else {
			if ( ! get_option( 'prtfl_options' ) )
				add_option( 'prtfl_options', $prtfl_option_defaults, '', 'yes' );
		}

		/* Get options from the database */
		if ( 1 == $wpmu )
			$prtfl_options = get_site_option( 'prtfl_options' ); /* Get options from the database */
		else
			$prtfl_options = get_option( 'prtfl_options' ); /* Get options from the database */

		if ( isset( $prtfl_options['prtfl_prettyPhoto_style'] ) )
			unset( $prtfl_options['prtfl_prettyPhoto_style'] );

		/* Array merge incase this version has added new options */
		if ( ! isset( $prtfl_options['plugin_option_version'] ) || $prtfl_options['plugin_option_version'] != $prtfl_plugin_info["Version"] ) {
			$prtfl_options = array_merge( $prtfl_option_defaults, $prtfl_options );
			$prtfl_options['plugin_option_version'] = $prtfl_plugin_info["Version"];
			update_option( 'prtfl_options', $prtfl_options );
		}

		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'portfolio-thumb', $prtfl_options['prtfl_custom_size_px'][0][0], $prtfl_options['prtfl_custom_size_px'][0][1], true );
			add_image_size( 'portfolio-photo-thumb', $prtfl_options['prtfl_custom_size_px'][1][0], $prtfl_options['prtfl_custom_size_px'][1][1], true );
		}
	}
}

/* Function check if plugin is compatible with current WP version  */
if ( ! function_exists ( 'prtfl_version_check' ) ) {
	function prtfl_version_check() {
		global $wp_version, $prtfl_plugin_info;
		$require_wp			=	"3.1"; /* Wordpress at least requires version */
		$plugin				=	plugin_basename( __FILE__ );
	 	if ( version_compare( $wp_version, $require_wp, "<" ) ) {
			if ( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
				wp_die( "<strong>" . $prtfl_plugin_info['Name'] . " </strong> " . __( 'requires', 'portfolio' ) . " <strong>WordPress " . $require_wp . "</strong> " . __( 'or higher, that is why it has been deactivated! Please upgrade WordPress and try again.', 'portfolio') . "<br /><br />" . __( 'Back to the WordPress', 'portfolio') . " <a href='" . get_admin_url( null, 'plugins.php' ) . "'>" . __( 'Plugins page', 'portfolio') . "</a>." );
			}
		}
	}
}

if ( ! function_exists( 'prtfl_admin_error' ) ) {
	function prtfl_admin_error() {
		$post = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : "" ;
		$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : "" ;
		if ( ( 'portfolio' == get_post_type( $post )  || 'portfolio' == $post_type ) && ( ! file_exists( get_stylesheet_directory() . '/portfolio.php' ) || ! file_exists( get_stylesheet_directory() . '/portfolio-post.php' ) ) )
			echo '<div class="error"><p><strong>' . __( 'The files "portfolio.php" and "portfolio-post.php" are not found in your theme directory. Please copy them from the directory `wp-content/plugins/portfolio/template/` to your theme directory for correct work of the Portfolio plugin', 'portfolio' ) . '</strong></p></div>';
	}
}

if ( ! function_exists( 'prtfl_settings_page' ) ) {
	function prtfl_settings_page() {
		global $prtfl_options, $wpmu, $wpdb;
		$error = "";
		$cstmsrch_options_name = "";

		if ( 1 == $wpmu ){
			if ( false !== get_site_option( 'bws_custom_search' ) )
				$cstmsrch_options_name = "bws_custom_search";
			elseif ( false !== get_site_option( 'cstmsrch_options' ) )
				$cstmsrch_options_name = "cstmsrch_options";
		} else {
			if ( false !== get_option( 'bws_custom_search' ) )
				$cstmsrch_options_name = "bws_custom_search";
			elseif ( false !== get_option( 'cstmsrch_options' ) )
				$cstmsrch_options_name = "cstmsrch_options";
		}

		/* Save data for settings page */
		if ( isset( $_REQUEST['prtfl_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'prtfl_nonce_name' ) ) {
			$prtfl_request_options = array();
			$prtfl_request_options["prtfl_custom_size_name"] = $prtfl_options["prtfl_custom_size_name"];

			$prtfl_request_options["prtfl_custom_size_px"] = array(
				array( intval( trim( $_REQUEST['prtfl_custom_image_size_w_album'] ) ), intval( trim( $_REQUEST['prtfl_custom_image_size_h_album'] ) ) ),
				array( intval( trim( $_REQUEST['prtfl_custom_image_size_w_photo'] ) ), intval( trim( $_REQUEST['prtfl_custom_image_size_h_photo'] ) ) )
			);
			$prtfl_request_options["prtfl_custom_image_row_count"] =  intval( trim( $_REQUEST['prtfl_custom_image_row_count'] ) );
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

			$prtfl_request_options["prtfl_date_text_field"] 			=	$_REQUEST["prtfl_date_text_field"];
			$prtfl_request_options["prtfl_link_text_field"]				=	$_REQUEST["prtfl_link_text_field"];
			$prtfl_request_options["prtfl_shrdescription_text_field"] 	=	$_REQUEST["prtfl_shrdescription_text_field"];
			$prtfl_request_options["prtfl_description_text_field"]		=	$_REQUEST["prtfl_description_text_field"];
			$prtfl_request_options["prtfl_svn_text_field"]				=	$_REQUEST["prtfl_svn_text_field"];
			$prtfl_request_options["prtfl_executor_text_field"]			=	$_REQUEST["prtfl_executor_text_field"];
			$prtfl_request_options["prtfl_screenshot_text_field"]		=	$_REQUEST["prtfl_screenshot_text_field"];
			$prtfl_request_options["prtfl_technologies_text_field"]		=	$_REQUEST["prtfl_technologies_text_field"];

			$prtfl_request_options["prtfl_slug"]	=	trim( $_REQUEST['prtfl_slug'] );
			$prtfl_request_options["prtfl_slug"]	=	strtolower( $prtfl_request_options["prtfl_slug"] );
			$prtfl_request_options["prtfl_slug"]	=	preg_replace( "/[^a-z0-9\s-]/", "", $prtfl_request_options["prtfl_slug"] );
			$prtfl_request_options["prtfl_slug"]	=	trim( preg_replace( "/[\s-]+/", " ", $prtfl_request_options["prtfl_slug"] ) );
			$prtfl_request_options["prtfl_slug"]	=	preg_replace( "/\s/", "-", $prtfl_request_options["prtfl_slug"] );

			$prtfl_request_options["prtfl_rewrite_template"] = isset( $_REQUEST["prtfl_rewrite_template"] ) ? 1 : 0;

			if ( isset( $_REQUEST['prtfl_add_to_search'] ) && "" != $cstmsrch_options_name ) {
				if ( 0 == $wpmu && false !== get_option( $cstmsrch_options_name ) ) {
					$cstmsrch_options = get_option( $cstmsrch_options_name );
					if ( ! in_array( 'portfolio', $cstmsrch_options ) ) {
						array_push( $cstmsrch_options, 'portfolio' );
						update_option( $cstmsrch_options_name, $cstmsrch_options, '', 'yes' );
					}
				} elseif ( 1 == $wpmu && false !== get_site_option( $cstmsrch_options_name ) ) {
					$cstmsrch_options = get_site_option( $cstmsrch_options_name );
					if ( ! in_array( 'portfolio', $cstmsrch_options ) ) {
						array_push( $cstmsrch_options, 'portfolio' );
						update_option( $cstmsrch_options_name, $cstmsrch_options, '', 'yes' );
					}
				}
			} else {
				if ( 0 == $wpmu && false !== get_option( $cstmsrch_options_name ) ) {
					$cstmsrch_options = get_option( $cstmsrch_options_name );
					if ( in_array( 'portfolio', $cstmsrch_options ) ) {
						$key = array_search( 'portfolio', $cstmsrch_options );
						unset( $cstmsrch_options[ $key ] );
						update_option( $cstmsrch_options_name, $cstmsrch_options, '', 'yes' );
					}
				} elseif ( 1 == $wpmu && false !== get_site_option( $cstmsrch_options_name ) ) {
					$cstmsrch_options = get_site_option( $cstmsrch_options_name );
					if ( ! in_array( 'portfolio', $cstmsrch_options ) ) {
						$key = array_search( 'portfolio', $cstmsrch_options );
						unset( $cstmsrch_options[ $key ] );
						update_option( $cstmsrch_options_name, $cstmsrch_options, '', 'yes' );
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
			update_option( 'prtfl_options', $prtfl_options, '', 'yes' );
			$message = __( "Settings saved.", 'portfolio' );
		}

		if ( ! file_exists( get_stylesheet_directory() . '/portfolio.php' ) || ! file_exists( get_stylesheet_directory() . '/portfolio-post.php' ) ) {
			$error .= __( 'The files "portfolio.php" and "portfolio-post.php" are not found in your theme directory. Please copy them from the directory `wp-content/plugins/portfolio/template/` to your theme directory for correct work of the Portfolio plugin', 'portfolio' );
		}
		/* Display form on the setting page */
	?>
	<div class="wrap">
		<div class="icon32 icon32-bws" id="icon-options-general"></div>
		<h2><?php _e( 'Portfolio Settings', 'portfolio' ); ?></h2>
		<div class="updated fade" <?php if ( ! isset( $_REQUEST['prtfl_form_submit'] ) || "" != $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
		<div class="error" <?php if ( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
		<div id="prtfl_settings_notice" class="updated fade" style="display:none"><p><strong><?php _e( "Notice:", 'portfolio' ); ?></strong> <?php _e( "The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'portfolio' ); ?></p></div>
		<p><?php _e( "If you would like to add the Latest Portfolio Items to your page or post, just copy and paste this shortcode into your post or page:", 'portfolio' ); ?> [latest_portfolio_items count=3], <?php _e( 'where count=3 is a number of posts to show up in the portfolio.', 'portfolio' ); ?></p>

		<?php
		if ( '1' == get_option( 'prtfl_tag_update' ) )
			$prefix = '_prtfl';
		else
			$prefix = '_prtf';
		if ( NULL != $wpdb->get_var( "SELECT meta_id FROM " . $wpdb->postmeta . " WHERE meta_key = '" . $prefix . "_short_descr' LIMIT 1" ) ) { ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'Change the way to store your post_meta information for portfolio', 'portfolio' ); ?> </th>
					<td style="position:relative">
						<input type="button" value="<?php _e( 'Update All Info' ); ?>" id="ajax_update_postmeta" name="ajax_update_postmeta" class="button" onclick="javascript:update_postmeta();"> <div id="prtfl_loader"><img src="<?php echo plugins_url( 'images/ajax-loader.gif', __FILE__ ); ?>" alt="loader" /></div>
					</td>
				</tr>
			</table>
			<script type="text/javascript">
				var update_message	=	"<?php _e( 'Updating post_meta information...', 'portfolio' ) ?>",
					not_found_info	=	"<?php _e( 'No portfolio item found', 'portfolio'); ?>",
					success			=	"<?php _e( 'All info is updated', 'portfolio' ); ?>",
					error			=	"<?php _e( 'Error.', 'portfolio' ); ?>";
			</script>
		<?php } ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Update images for portfolio', 'portfolio' ); ?> </th>
				<td style="position:relative">
					<input type="button" value="<?php _e( 'Update images' ); ?>" id="ajax_update_images" name="ajax_update_images" class="button" onclick="javascript:update_images();"> <div id="prtfl_img_loader"><img src="<?php echo plugins_url( 'images/ajax-loader.gif', __FILE__ ); ?>" alt="loader" /></div>
				</td>
			</tr>
			<tr valign="top">
			</tr>
		</table>
		<br />
		<script type="text/javascript">
			var update_img_message	=	"<?php _e( 'Updating images...', 'portfolio' ) ?>",
				not_found_img_info	=	"<?php _e( 'No image found', 'portfolio'); ?>",
				img_success			=	"<?php _e( 'All images are updated', 'portfolio' ); ?>",
				img_error			=	"<?php _e( 'Error.', 'portfolio' ); ?>";
		</script>
		<form method="post" action="admin.php?page=portfolio.php" id="prtfl_form_image_size">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'The album cover size', 'portfolio' ); ?> </th>
					<td>
						<label><?php _e( 'Image size name', 'portfolio' ); ?></label> <?php echo $prtfl_options["prtfl_custom_size_name"][0]; ?><br />
						<label><?php _e( 'Width (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_w_album" value="<?php echo $prtfl_options["prtfl_custom_size_px"][0][0]; ?>" /><br />
						<label><?php _e( 'Height (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_h_album" value="<?php echo $prtfl_options["prtfl_custom_size_px"][0][1]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Size of portfolio images', 'portfolio' ); ?> </th>
					<td>
						<label><?php _e( 'Image size name', 'portfolio' ); ?></label> <?php echo $prtfl_options["prtfl_custom_size_name"][1]; ?><br />
						<label><?php _e( 'Width (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_w_photo" value="<?php echo $prtfl_options["prtfl_custom_size_px"][1][0]; ?>" /><br />
						<label><?php _e( 'Height (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_h_photo" value="<?php echo $prtfl_options["prtfl_custom_size_px"][1][1]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2"><span style="color: #888888;font-size: 10px;"><?php _e( 'WordPress will copy thumbnails with the specified dimensions when you upload a new image. It is necessary to click  the Update images button at the bottom of this page in order to generate new images and set new dimensions', 'portfolio' ); ?></span></th>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Sort portfolio by', 'portfolio' ); ?> </th>
					<td>
						<label class="label_radio"><input type="radio" name="prtfl_order_by" value="ID" <?php if ( 'ID' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'portfolio id', 'gallery' ); ?></label><br />
						<label class="label_radio"><input type="radio" name="prtfl_order_by" value="title" <?php if ( 'title' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'portfolio title', 'gallery' ); ?></label><br />
						<label class="label_radio"><input type="radio" name="prtfl_order_by" value="date" <?php if ( 'date' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'date', 'gallery' ); ?></label><br />
						<label class="label_radio"><input type="radio" name="prtfl_order_by" value="menu_order" <?php if ( 'menu_order' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'menu order', 'gallery' ); ?></label><br />
						<label class="label_radio"><input type="radio" name="prtfl_order_by" value="rand" <?php if ( 'rand' == $prtfl_options["prtfl_order_by"] ) echo 'checked="checked"'; ?> /> <?php _e( 'random', 'gallery' ); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Portfolio sorting', 'portfolio' ); ?> </th>
					<td>
						<label class="label_radio" style="width: auto;"><input type="radio" name="prtfl_order" value="ASC" <?php if ( 'ASC' == $prtfl_options["prtfl_order"] ) echo 'checked="checked"'; ?> /> <?php _e( 'ASC (ascending order from lowest to highest values - 1, 2, 3; a, b, c)', 'gallery' ); ?></label><br />
						<label class="label_radio" style="width: auto;"><input type="radio" name="prtfl_order" value="DESC" <?php if ( 'DESC' == $prtfl_options["prtfl_order"] ) echo 'checked="checked"'; ?> /> <?php _e( 'DESC (descending order from highest to lowest values - 3, 2, 1; c, b, a)', 'gallery' ); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Number of images in the row', 'portfolio' ); ?> </th>
					<td>
						<input type="text" name="prtfl_custom_image_row_count" value="<?php echo $prtfl_options["prtfl_custom_image_row_count"]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Display additional fields', 'portfolio' ); ?> </th>
					<td>
						<input type="checkbox" name="prtfl_date_additional_field" value="1" id="prtfl_date_additional_field" <?php if ( 1 == $prtfl_options['prtfl_date_additional_field'] ) echo "checked=\"checked\""; ?> /> <label for="prtfl_date_additional_field" style="float:none;"><?php _e( 'Date', 'portfolio' ); ?></label>
						<input type="checkbox" name="prtfl_link_additional_field" value="1" id="prtfl_link_additional_field" <?php if ( 1 == $prtfl_options['prtfl_link_additional_field'] ) echo "checked=\"checked\""; ?> /> <label for="prtfl_link_additional_field" style="float:none;"><?php _e( 'Link', 'portfolio' ); ?></label>
						<input type="checkbox" name="prtfl_shrdescription_additional_field" value="1" id="prtfl_shrdescription_additional_field" <?php if ( 1 == $prtfl_options['prtfl_shrdescription_additional_field'] ) echo "checked=\"checked\""; ?> /> <label for="prtfl_shrdescription_additional_field" style="float:none;"><?php _e( 'Short Description', 'portfolio' ); ?></label>
						<input type="checkbox" name="prtfl_description_additional_field" value="1" id="prtfl_description_additional_field" <?php if ( 1 == $prtfl_options['prtfl_description_additional_field'] ) echo "checked=\"checked\""; ?> /> <label for="prtfl_description_additional_field" style="float:none;"><?php _e( 'Description', 'portfolio' ); ?></label>
						<input type="checkbox" name="prtfl_svn_additional_field" value="1" id="prtfl_svn_additional_field" <?php if ( 1 == $prtfl_options['prtfl_svn_additional_field'] ) echo "checked=\"checked\""; ?> /> <label for="prtfl_svn_additional_field" style="float:none;"><?php _e( 'SVN', 'portfolio' ); ?></label>
						<input type="checkbox" name="prtfl_executor_additional_field" value="1" id="prtfl_executor_additional_field" <?php if ( 1 == $prtfl_options['prtfl_executor_additional_field'] ) echo "checked=\"checked\""; ?> /> <label for="prtfl_executor_additional_field" style="float:none;"><?php _e( 'Executor', 'portfolio' ); ?></label>
						<input type="checkbox" name="prtfl_technologies_additional_field" value="1" id="prtfl_technologies_additional_field" <?php if ( 1 == $prtfl_options['prtfl_technologies_additional_field'] ) echo "checked=\"checked\""; ?> /> <label for="prtfl_technologies_additional_field" style="float:none;"><?php _e( 'Technologies', 'portfolio' ); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Display the link field as a text for non-registered users', 'portfolio' ); ?></th>
					<td>
						<input type="checkbox" name="prtfl_link_additional_field_for_non_registered" value="1" id="prtfl_link_additional_field_for_non_registered" <?php if ( 1 == $prtfl_options['prtfl_link_additional_field_for_non_registered'] ) echo "checked=\"checked\""; ?> />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Text for additional fields', 'portfolio' ); ?> </th>
					<td>
					 	<label><?php _e( 'Date of completion:', 'portfolio' ); ?></label> <input type="text" name="prtfl_date_text_field" value="<?php echo $prtfl_options["prtfl_date_text_field"]; ?>" /><br />
						<label><?php _e( 'Link:', 'portfolio' ); ?></label> <input type="text" name="prtfl_link_text_field" value="<?php echo $prtfl_options["prtfl_link_text_field"]; ?>" /><br />
						<label><?php _e( 'Short description:', 'portfolio' ); ?></label> <input type="text" name="prtfl_shrdescription_text_field" value="<?php echo $prtfl_options["prtfl_shrdescription_text_field"]; ?>" /><br />
						<label><?php _e( 'Description:', 'portfolio' ); ?></label> <input type="text" name="prtfl_description_text_field" value="<?php echo $prtfl_options["prtfl_description_text_field"]; ?>" /><br />
						<label><?php _e( 'SVN:', 'portfolio' ); ?></label> <input type="text" name="prtfl_svn_text_field" value="<?php echo $prtfl_options["prtfl_svn_text_field"]; ?>" /><br />
						<label><?php _e( 'Executor Profile:', 'portfolio' ); ?></label> <input type="text" name="prtfl_executor_text_field" value="<?php echo $prtfl_options["prtfl_executor_text_field"]; ?>" /><br />
						<label><?php _e( 'More screenshots:', 'portfolio' ); ?></label> <input type="text" name="prtfl_screenshot_text_field" value="<?php echo $prtfl_options["prtfl_screenshot_text_field"]; ?>" /><br />
						<label><?php _e( 'Technologies:', 'portfolio' ); ?></label> <input type="text" name="prtfl_technologies_text_field" value="<?php echo $prtfl_options["prtfl_technologies_text_field"]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Slug for portfolio item', 'portfolio' ); ?></th>
					<td>
						<input type="text" name="prtfl_slug" value="<?php echo $prtfl_options["prtfl_slug"]; ?>" /> <span style="color: #888888;font-size: 10px;"><?php _e( 'for any structure of permalinks except the default structure', 'portfolio' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Rewrite templates after update', 'portfolio' ); ?></th>
					<td>
						<input type="checkbox" name="prtfl_rewrite_template" value="1" <?php if ( 1 == $prtfl_options['prtfl_rewrite_template'] ) echo "checked=\"checked\""; ?> /> <span style="color: #888888;font-size: 10px;"><?php _e( "Turn off the checkbox, if You edited the file 'portfolio.php' or 'portfolio-post.php' file in your theme folder and You don't want to rewrite them", 'portfolio' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Add portfolio to the search', 'portfolio' ); ?></th>
					<td>
						<?php $all_plugins = get_plugins();
						$active_plugins = get_option( 'active_plugins' );
						if ( ! function_exists( 'is_plugin_active_for_network' ) )
							require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
						if ( isset( $cstmsrch_options_name ) && "" != $cstmsrch_options_name ) {
							if ( 1 == $wpmu ) {
								$cstmsrch_options = get_site_option( $cstmsrch_options_name );
							} else {
								$cstmsrch_options = get_option( $cstmsrch_options_name );
							}
						}
						if ( array_key_exists( 'custom-search-plugin/custom-search-plugin.php', $all_plugins ) ) {
							if ( 0 < count( preg_grep( '/custom-search-plugin\/custom-search-plugin.php/', $active_plugins ) ) || is_plugin_active_for_network( 'custom-search-plugin/custom-search-plugin.php' ) ) { ?>
								<input type="checkbox" name="prtfl_add_to_search" value="1" <?php if ( isset( $cstmsrch_options ) && in_array( 'portfolio', $cstmsrch_options ) ) echo "checked=\"checked\""; ?> />
								<span style="color: #888888;font-size: 10px;"> (<?php _e( 'Using Custom Search powered by', 'portfolio' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>)</span>
							<?php } else { ?>
								<input disabled="disabled" type="checkbox" name="prtfl_add_to_search" value="1" <?php if ( isset( $cstmsrch_options ) && in_array( 'portfolio', $cstmsrch_options ) ) echo "checked=\"checked\""; ?> />
								<span style="color: #888888;font-size: 10px;">(<?php _e( 'Using Custom Search powered by', 'portfolio' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>) <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'Activate Custom Search', 'gallery' ); ?></a></span>
							<?php }
						} else { ?>
							<input disabled="disabled" type="checkbox" name="prtfl_add_to_search" value="1" />
							<span style="color: #888888;font-size: 10px;">(<?php _e( 'Using Custom Search powered by', 'portfolio' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>) <a href="http://bestwebsoft.com/plugin/custom-search-plugin/"><?php _e( 'Download Custom Search', 'gallery' ); ?></a></span>
						<?php } ?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="prtfl_form_submit" value="submit" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />
			</p>
			<?php wp_nonce_field( plugin_basename( __FILE__ ), 'prtfl_nonce_name' ); ?>
		</form>
		<br />
		<div class="bws-plugin-reviews">
			<div class="bws-plugin-reviews-rate">
				<?php _e( 'If you enjoy our plugin, please give it 5 stars on WordPress', 'portfolio' ); ?>: 
				<a href="http://wordpress.org/support/view/plugin-reviews/portfolio" target="_blank" title="Portfolio reviews"><?php _e( 'Rate the plugin', 'portfolio' ); ?></a><br/>
			</div>
			<div class="bws-plugin-reviews-support">
				<?php _e( 'If there is something wrong about it, please contact us', 'portfolio' ); ?>: 
				<a href="http://support.bestwebsoft.com">http://support.bestwebsoft.com</a>
			</div>
		</div>
	</div>
	<?php }
}

/* Create post type for portfolio */
if ( ! function_exists( 'prtfl_post_type_portfolio' ) ) {
	function prtfl_post_type_portfolio() {
		global $wpdb, $prtfl_options;
		register_prtfl_settings();
		prtfl_replace_old_post_tag();
		/*$options	=	get_site_option( 'prtfl_options' );*/
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
				'menu_position'			=>	6,
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
					'author'
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

if ( ! function_exists( 'prtfl_technologies_get_posts' ) ) {
	function prtfl_technologies_get_posts( $query ) {
		if ( isset( $query->query_vars["technologies"] ) )
			$query->set( 'post_type', array( 'portfolio' ) );
		return $query;
	}
}

if ( ! function_exists( 'prtfl_register_widget' ) ) {
	function prtfl_register_widget() {
		$control_ops = array( 'width' => 200, 'height' => 200, 'id_base' => 'portfolio_technologies_widget' );

		wp_register_sidebar_widget(
			'portfolio_technologies_widget',
			__( 'Technologies', 'portfolio' ),
			'prtfl_widget_display',
			array(
				'description' => __( 'Your most used portfolio technologies as a tag cloud', 'portfolio' )
			)
		);
		wp_register_widget_control(
			'portfolio_technologies_widget', /* Your unique widget id */
			__( 'Technologies', 'portfolio' ), /* Widget name */
			'prtfl_widget_display_control', /* Callback function */
			$control_ops,
			array( 'number' => 1 )
		);
	}
}

if ( ! function_exists( 'prtfl_widget_display' ) ) {
	function prtfl_widget_display( $args, $vars = array() ) {
		/* Print some HTML for the widget to display here */
		extract( $args );
		$options = get_option( 'widget-portfolio_technologies_widget' );
		if ( ! empty( $options[1]['title'] ) )
			$title = $options[1]['title'];
		else
			$title = $widget_name;
		$title = apply_filters( 'widget_title', $title, '', 'portfolio_technologies_widget' );

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div class="tagcloud">';
		wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array( 'taxonomy' => 'portfolio_technologies', 'number' => 0 ) ) );
		echo "</div>\n";
		echo $after_widget;
	}
}

if ( ! function_exists( 'prtfl_widget_display_control' ) ) {
	function prtfl_widget_display_control( $args ) {
		$options = get_option( 'widget-portfolio_technologies_widget' );
		if ( empty( $options ) ) $options = array();
		if ( isset( $options[0] ) ) unset( $options[0] );

		/* Update options array */
		if ( ! empty( $_POST['widget-portfolio_technologies_widget'] ) && is_array( $_POST ) ) {
			foreach( $_POST['widget-portfolio_technologies_widget'] as $widget_number => $values ) {
				if ( empty( $values ) && isset( $options[$widget_number] ) ) /* User clicked cancel */
					continue;

				if ( ! isset( $options[$widget_number] ) && -1 == $args['number'] )
					$args['number'] = $widget_number;

				$options[$widget_number] = $values;
			}
			/* Clear unused options and update options in DB. return actual options array */
			$options = prtfl_widget_portfolio_technologies_update( 'widget-portfolio_technologies_widget', $options, $_POST['widget-portfolio_technologies_widget'], $_POST['sidebar'], 'widget-portfolio_technologies_widget' );
		}
		/*	$number - is dynamic number for multi widget, gived by WP
			by default $number = -1 (if no widgets activated). In this case we should use %i% for inputs
			to allow WP generate number automatically */
		$number = ( $args['number'] == -1 ) ? '%i%' : $args['number'];

		/* Now we can output control */
		$title = $options[$number]['title'];
	?>
		<p><label for="widget-portfolio_technologies_widget-<?php echo $number; ?>-title"><?php _e( 'Title', 'portfolio' ); ?>:</label>
		<input type="text" value="<?php echo $title; ?>" name="widget-portfolio_technologies_widget[<?php echo $number; ?>][title]" id="widget-portfolio_technologies_widget-<?php echo $number; ?>-title" class="widefat"></p>
	<?php
	}
}

if ( ! function_exists( 'prtfl_widget_portfolio_technologies_update' ) ) {
	function prtfl_widget_portfolio_technologies_update( $id_prefix, $options, $post, $sidebar, $option_name = '' ) {
		global $wp_registered_widgets;
		static $updated = false;
 
		/* Get active sidebar */
		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset( $sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();
 
		/* Search unused options */
		foreach ( $this_sidebar as $_widget_id ) {
			if ( preg_match( '/' . $id_prefix . '-([0-9]+)/i', $_widget_id, $match ) ) {
				$widget_number = $match[1];
 
				/* $_POST['widget-id'] contain current widgets set for current sidebar */
				/* $this_sidebar is not updated yet, so we can determine which was deleted */
				if ( ! in_array( $match[0], $_POST['widget-id'] ) ) {
					unset( $options[$widget_number] );
				}
			}
		}
		/* Update database */
		if ( ! empty( $option_name ) ) {
			update_option( $option_name, $options );
			$updated = true;
		}
		/* Return updated array */
		return $options;
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
	}
}

/* Create custom meta box for portfolio post type */
if ( ! function_exists( 'prtfl_post_custom_box' ) ) {
	function prtfl_post_custom_box( $obj = '', $box = '' ) {
		global $prtfl_boxes;
		/* Generate box contents */
		foreach( $prtfl_boxes[ $box[ 'id' ] ] as $prtfl_box ) {
			echo prtfl_text_field( $prtfl_box );
		}
	}
}

/* This is the default text field meta box */
if ( ! function_exists( 'prtfl_text_field' ) ) {
	function prtfl_text_field( $args ) {
		global $post;
		$description = $args[2];
		if ( '1' == get_option( 'prtfl_postmeta_update' ) ) {
			$post_meta = get_post_meta( $post->ID, 'prtfl_information', true);
			$args[2] = is_array( $post_meta ) ? esc_html( $post_meta[ $args[0] ] ) : "" ;
		} else {
			$args[2] = esc_html( get_post_meta( $post->ID, $args[0], true ) );
		}
		$label_format =
			'<div class="portfolio_admin_box">' .
			'<p><label for="%1$s"><strong>%2$s</strong></label></p>' .
			'<p><input style="width: 80%%;" type="text" name="%1$s" id="%1$s" value="%3$s" /></p>' .
			'<p><em>' . $description .'</em></p>' .
			'</div>';
		if ( '_prtfl_date_compl' == $args[0] )
			echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#_prtfl_date_compl").simpleDatepicker({ startdate: new Date().getFullYear()-3, enddate: new Date().getFullYear()+3 });});</script>';
		return vsprintf( $label_format, $args );
	}
}

/* This is the text area meta box */
if ( ! function_exists( 'prtfl_prtfl_text_area' ) ) {
	function prtfl_text_area( $args ) {
		global $post;
		$description	= $args[2];
		$args[2]		= esc_html( get_post_meta( $post->ID, $args[0], true ) );
		$label_format	=
			'<div class="portfolio_admin_box">' .
			'<p><label for="%1$s"><strong>%2$s</strong></label></p>' .
			'<p><textarea class="theEditor" id="theEditor" style="width: 90%%;color:#000;" name="%1$s">%3$s</textarea></p>' .
			'<p><em>' . $description . '</em></p>' .
			'</div>';
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
			foreach( $prtfl_boxes as $prtfl_boxe ) {
				foreach( $prtfl_boxe as $prtfl_fields ) {
					$my_data[ $prtfl_fields[0] ] = $_POST[ $prtfl_fields[0] ];
				}
			}

			/*	Add values of $my_data as custom fields. Let's cycle through the $my_data array! */
			if ( '1' == get_option( 'prtfl_postmeta_update' ) ) {
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
			} else {
				foreach( $my_data as $key => $value ) {
					/* If $value is an array, make it a CSV (unlikely) */
					$value = implode( ',', ( array ) $value );
					if ( get_post_meta( $post->ID, $key, FALSE ) && $value ) {
						/* Custom field has a value and this custom field exists in database */
						update_post_meta( $post->ID, $key, $value );
					} elseif ( $value ) {
						/* Custom field has a value, but this custom field does not exist in database */
						add_post_meta( $post->ID, $key, $value );
					} else {
						/* Custom field does not have a value, but this custom field exists in database */
						update_post_meta( $post->ID, $key, $value );
					}
				}
			}
		}
	}
}

if ( ! function_exists ( 'prtfl_register_plugin_links' ) ) {
	function prtfl_register_plugin_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			$links[]	=	'<a href="admin.php?page=portfolio.php">' . __( 'Settings', 'portfolio' ) . '</a>';
			$links[]	=	'<a href="http://wordpress.org/plugins/portfolio/faq/" target="_blank">' . __( 'FAQ', 'portfolio' ) . '</a>';
			$links[]	=	'<a href="http://support.bestwebsoft.com">' . __( 'Support', 'portfolio' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists ( 'prtfl_replace_old_post_tag' ) ) {
	function prtfl_replace_old_post_tag() {
		global $wpdb;
		if ( false === get_option( 'prtfl_tag_update' ) ) {
			$tag_id_array = $wpdb->get_results( "SELECT term_taxonomy_id FROM $wpdb->posts, $wpdb->term_relationships WHERE post_type = 'portfolio' AND $wpdb->posts.ID = $wpdb->term_relationships.object_id" );
			while( list( $key, $val ) = each( $tag_id_array ) ) {
				$wpdb->update(
					$wpdb->term_taxonomy,
					array(
						'taxonomy' => 'portfolio_technologies'
					),
					array(
						'taxonomy' => 'post_tag' ,
						'term_taxonomy_id' => $val->term_taxonomy_id
					),
					array(
						'%s'
					),
					array(
						'%s',
						'%d'
					)
				);
			}
			$wpdb->query( "UPDATE $wpdb->posts, $wpdb->postmeta SET $wpdb->posts.post_content = $wpdb->postmeta.meta_value WHERE $wpdb->postmeta.post_id = $wpdb->posts.ID AND $wpdb->posts.post_type = 'portfolio' AND	$wpdb->postmeta.meta_key = '_prtfl_short_descr' " );
			$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = '_prtfl_short_descr'"	);
			$wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key, 'prtf_', 'prtfl_') WHERE meta_key LIKE '_prtf_%'" );
			add_option( 'prtfl_tag_update', '1', '', 'no' );
		}
		$postmeta = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE '%_short_descr%' LIMIT 0 , 1" );
		if ( empty( $postmeta ) && ! get_option( 'prtfl_postmeta_update' ) ) {
			add_option( 'prtfl_postmeta_update', 1 );
		}
	}
}

if ( ! function_exists( 'prtfl_template_redirect' ) ) {
	function prtfl_template_redirect() {
		global $wp_query, $post, $posts;
		if ( 'portfolio' == get_post_type() && "" == $wp_query->query_vars["s"] && ! isset( $wp_query->query_vars["technologies"] ) ) {
			include( get_stylesheet_directory() . '/portfolio-post.php' );
			exit();
		} elseif ( 'portfolio' == get_post_type() && isset( $wp_query->query_vars["technologies"] ) ) {
			include( get_stylesheet_directory() . '/portfolio.php' );
			exit();
		}
	}
}

if ( ! function_exists ( 'prtfl_add_template_in_new_theme' ) ) {
	function prtfl_add_template_in_new_theme() {
		if ( ! file_exists( get_stylesheet_directory() . '/portfolio.php' ) || ! file_exists( get_stylesheet_directory() . '/portfolio-post.php' ) )
			prtfl_plugin_install();
	}
}

if ( ! function_exists ( 'prtfl_plugin_action_links' ) ) {
	function prtfl_plugin_action_links( $links, $file ) {
		/* Static so we don't call plugin_basename on every plugin row. */
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename( __FILE__ );

		if ( $file == $this_plugin ) {
			$settings_link = '<a href="admin.php?page=portfolio.php">' . __( 'Settings', 'portfolio' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
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
					$meta_values		=	get_post_custom($post->ID);
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
					$image_desc	=	get_post($post_thumbnail_id);
					$image_desc	=	$image_desc->post_content;
					if ( '1' == get_option( 'prtfl_postmeta_update' ) ) {
						$post_meta	=	get_post_meta( $post->ID, 'prtfl_information', true);
						$date_compl	=	$post_meta['_prtfl_date_compl'];
						if ( ! empty( $date_compl ) && 'in progress' != $date_compl) {
							$date_compl		=	explode( "/", $date_compl );
							$date_compl		=	date( get_option( 'date_format' ), strtotime( $date_compl[1]."-".$date_compl[0].'-'.$date_compl[2] ) );
						}
						$link			=	$post_meta['_prtfl_link'];
						$short_descr	=	$post_meta['_prtfl_short_descr'];
					} else {
						$date_compl		=	get_post_meta( $post->ID, '_prtfl_date_compl', true );
						if( ! empty( $date_compl ) && 'in progress' != $date_compl) {
							$date_compl		=	explode( "/", $date_compl );
							$date_compl		=	date( get_option( 'date_format' ), strtotime( $date_compl[1]."-".$date_compl[0].'-'.$date_compl[2] ) );
						}
						$link			=	get_post_meta($post->ID, '_prtfl_link', true);
						$short_descr	=	get_post_meta($post->ID, '_prtfl_short_descr', true);
					}

					$content .= '<div class="portfolio_thumb" style="width:165px">
							<img src="' . $image[0] . '" width="' . $image[1] . '" alt="' . $image_alt . '" />
					</div>
					<div class="portfolio_short_content">
						<div class="item_title">
							<p>
								<a href="' . get_permalink() . '" rel="bookmark">' . get_the_title() . '</a>
							</p>
						</div> <!-- .item_title -->';
						$content .= '<p>' . $short_descr . '</p>
					</div> <!-- .portfolio_short_content -->
				</div> <!-- .entry -->
				<div class="read_more">
					<a href="' . get_permalink() . '" rel="bookmark">' . __( 'Read more', 'portfolio' ) . '</a>
				</div> <!-- .read_more -->
				<div class="portfolio_terms">';
				$terms = wp_get_object_terms( $post->ID, 'portfolio_technologies' );
				if ( is_array( $terms ) && 0 < count( $terms ) ) {
					$content .= __( 'Technologies', 'portfolio' ) . ':';
					$count = 0;
					foreach ( $terms as $term ) {
						if( $count > 0 )
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
		global $wp_version;
		if ( $wp_version < 3.8 )
			wp_enqueue_style( 'prtfl_stylesheet', plugins_url( 'css/style_wp_before_3.8.css', __FILE__ ) );	
		else
			wp_enqueue_style( 'prtfl_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		wp_enqueue_style( 'prtfl_datepicker_stylesheet', plugins_url( 'datepicker/datepicker.css', __FILE__ ) );

		if ( isset( $_GET['page'] ) && "portfolio.php" == $_GET['page'] )
			wp_enqueue_script( 'prtfl_script', plugins_url( 'js/script.js', __FILE__ ) );
		wp_enqueue_script( 'prtfl_datepicker_script', plugins_url( 'datepicker/datepicker.js', __FILE__ ) );
	}
}

if ( ! function_exists ( 'prtfl_wp_head' ) ) {
	function prtfl_wp_head() {
		wp_enqueue_style( 'prtfl_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		
		if ( ! function_exists( 'is_plugin_active_for_network' ) || ! function_exists( 'is_plugin_active' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		
		$all_plugins = get_plugins();
		
		if ( ( ! is_plugin_active( 'gallery-plugin-pro/gallery-plugin-pro.php' ) && ! is_plugin_active_for_network( 'gallery-plugin-pro/gallery-plugin-pro.php' ) ) || ( isset( $all_plugins["gallery-plugin-pro/gallery-plugin-pro.php"]["Version"] ) && "1.3.0" >= $all_plugins["gallery-plugin-pro/gallery-plugin-pro.php"]["Version"] ) ) { 
			wp_enqueue_style( 'prtfl_lightbox_stylesheet', plugins_url( 'fancybox/jquery.fancybox-1.3.4.css', __FILE__ ) );
			wp_enqueue_script( 'prtfl_fancybox_mousewheelJs', plugins_url( 'fancybox/jquery.mousewheel-3.0.4.pack.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'prtfl_fancyboxJs', plugins_url( 'fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__ ), array( 'jquery' ) );
		}
	}
}

if ( ! function_exists ( 'prtfl_update_info' ) ) {
	function prtfl_update_info() {
		global $wpdb;
		$action	=	isset( $_REQUEST['action1'] ) ? $_REQUEST['action1'] : "";
		$id		=	isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : "";
		switch ( $action ) {
			case 'get_portfolio_id':
				$result = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'portfolio'" );
				echo json_encode( $result );
				break;
			case 'update_info':
				if ( "" != $id && is_numeric( $id ) ) {
					$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key LIKE '_prtf%' AND post_id = " . $id );
					if ( ! empty( $result ) ) {
						$new_post_meta = array();
						foreach( $result as $value ) {
							$new_post_meta[ $value->meta_key ] = $value->meta_value;
						}
						add_post_meta( $id, 'prtfl_information', $new_post_meta );
						$wpdb->query( "DELETE FROM " . $wpdb->postmeta . " WHERE meta_key LIKE '_prtf%' AND post_id = " . $id );
					}
					$post_meta = get_post_meta($id, 'prtfl_information', true );
					$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->posts . " SET post_content = '%s' WHERE ID = " . $id, $post_meta["_prtfl_short_descr"] ) );
				}
				break;
			case 'update_options':
				add_option( 'prtfl_postmeta_update', '1', '', 'no' );
				break;
		}
		die();
	}
}

if ( ! function_exists ( 'prtfl_update_image' ) ) {
	function prtfl_update_image() {
		global $wpdb;
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
            return new WP_Error( 'invalid_image', __( 'Image size not defined' ), $file );
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
        	return new WP_Error( 'invalid_image', __('We can update only PNG, JPEG, GIF, WPMP or XBM filetype. For other, please, manually reload image.' ), $file );

        if ( ! is_resource( $image ) )
            return new WP_Error( 'error_loading_image', $image, $file );

        /* $size = @getimagesize( $file ); */
        list( $orig_w, $orig_h, $orig_type ) = $size;
		$dims = prtfl_image_resize_dimensions($orig_w, $orig_h, $max_w, $max_h, $crop);

		if ( ! $dims )
			return new WP_Error( 'error_getting_dimensions', __( 'Image size changes not defined' ) );
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
				return new WP_Error( 'resize_path_invalid', __( 'Invalid path' ) );
		} elseif ( IMAGETYPE_PNG == $orig_type ) {
			if ( ! imagepng( $newimage, $destfilename ) )
				return new WP_Error( 'resize_path_invalid', __( 'Invalid path' ) );
		} else {
			/* All other formats are converted to jpg */
			$destfilename = "{$dir}/{$name}-{$suffix}.jpg";
			if ( ! imagejpeg( $newimage, $destfilename, apply_filters( 'jpeg_quality', $jpeg_quality, 'image_resize' ) ) )
				return new WP_Error( 'resize_path_invalid', __( 'Invalid path' ) );
		}

		imagedestroy( $newimage );
		/* Set correct file permissions */
		$stat	=	stat( dirname( $destfilename ) );
		$perms	=	$stat['mode'] & 0000666; /* Same permissions as parent folder, strip off the executable bits */
		@ chmod( $destfilename, $perms );
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

if ( ! function_exists( 'prtfl_plugin_uninstall' ) ) {
	function prtfl_plugin_uninstall() {
		if ( file_exists( get_stylesheet_directory() . '/portfolio.php' ) && ! unlink( get_stylesheet_directory() . '/portfolio.php' ) )
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );
		if ( file_exists( get_stylesheet_directory() . '/portfolio-post.php' ) && ! unlink( get_stylesheet_directory() . '/portfolio-post.php' ) )
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );

		delete_option( 'prtfl_options' );
		delete_option( 'prtfl_tag_update' );
		delete_option( 'prtfl_postmeta_update' );
		
		delete_site_option( 'prtfl_options' );
		delete_site_option( 'prtfl_tag_update' );
		delete_site_option( 'prtfl_postmeta_update' );
	}
}

register_activation_hook( __FILE__, 'prtfl_plugin_install' ); /* Activate plugin */
/* Add portfolio settings page in admin menu */
add_action( 'admin_menu', 'add_prtfl_admin_menu' );
add_action( 'admin_init', 'prtfl_admin_init' );
add_action( 'init', 'prtfl_init' );
add_action( 'wp_loaded', 'prtfl_flush_rules' );
/* Save custom data from admin  */
add_action( 'save_post', 'prtfl_save_postdata', 1, 2 );
/* Add template for single gallery page */
add_action( 'template_redirect', 'prtfl_template_redirect' );
/* Add template in theme after activate new theme */
add_action( 'after_setup_theme', 'prtfl_add_template_in_new_theme' );
add_action( 'admin_enqueue_scripts', 'prtfl_admin_head' );
add_action( 'wp_enqueue_scripts', 'prtfl_wp_head' );
add_action( 'wp_ajax_prtfl_update_info', 'prtfl_update_info' );
add_action( 'wp_ajax_prtfl_update_image', 'prtfl_update_image' );
/* Add custom permalink for portfolio */
/* add_action( 'init', 'prtfl_custom_permalinks' ); */

add_shortcode('latest_portfolio_items', 'prtfl_latest_items');

/* Display tachnologies taxonomy */
add_filter( 'pre_get_posts', 'prtfl_technologies_get_posts' );
add_filter( 'rewrite_rules_array', 'prtfl_custom_permalinks' );
/* Additional links on the plugin page */
add_filter( 'plugin_row_meta', 'prtfl_register_plugin_links', 10, 2 );
/* Adds "Settings" link to the plugin action page */
add_filter( 'plugin_action_links', 'prtfl_plugin_action_links', 10, 2 );
add_filter( 'nav_menu_css_class', 'prtfl_add_portfolio_ancestor_to_menu', 10, 2 );

register_uninstall_hook( __FILE__, 'prtfl_plugin_uninstall' ); /* Deactivate plugin */

?>