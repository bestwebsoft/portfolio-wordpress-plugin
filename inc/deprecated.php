<?php
/**
* Includes deprecated functions
*/


/**
* @deprecated since 2.42
 * @todo remove after 20.01.2018
*/
if ( ! function_exists( 'prtfl_old_template_options' ) ) {
	function prtfl_old_template_options() {
		global $prtfl_options, $prtfl_plugin_info, $wpdb;
		if ( isset( $prtfl_options['plugin_option_version'] ) && $prtfl_plugin_info["Version"] <= '2.42' ) {
			/* get template attribute 'portfolio.php' for pages */
			$template_pages = $wpdb->get_var( "SELECT $wpdb->posts.ID FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'portfolio.php' AND ( post_status = 'publish' OR post_status = 'private' ) AND $wpdb->posts.ID = $wpdb->postmeta.post_id" );
			if ( ! empty( $template_pages ) ) {
				$prtfl_options['page_id_portfolio_template'] = $template_pages;
			}						
		}
		if ( isset( $prtfl_options['rewrite_template'] ) ) {				
			$themepath = get_stylesheet_directory() . '/';
			foreach ( array( 'portfolio-post.php', 'portfolio.php' ) as $filename ) {
				if ( file_exists( $themepath . $filename ) ) {
					if ( 0 == $prtfl_options['rewrite_template'] ) {
						if ( ! is_dir( $themepath  . 'bws-templates/' ) )
						 	@mkdir( $themepath  . 'bws-templates/', 0755 );
						if ( rename( $themepath . $filename, $themepath . 'bws-templates/' . $filename ) ) {
							@unlink( $themepath  . $filename );
						}
					} else {
						@unlink( $themepath  . $filename );
					}
				}
			}
			unset( $prtfl_options['rewrite_template'] );
		}
	}
}

/**
* @deprecated since 2.42
 * @todo remove after 20.01.2018
*/
if ( ! function_exists( 'prtfl_update_options_after_redesign' ) ) {
	function prtfl_update_options_after_redesign() {
		global $prtfl_options;
		
		if ( isset( $prtfl_options['custom_size_name'] ) ) {
			$prtfl_options['custom_size_px']['portfolio-thumb'] = $prtfl_options['custom_size_px'][0];
			$prtfl_options['custom_size_px']['portfolio-photo-thumb'] = $prtfl_options['custom_size_px'][1];
			unset( $prtfl_options['custom_size_name'], $prtfl_options['custom_size_px'][0], $prtfl_options['custom_size_px'][1] );
		}
		if ( ! isset( $prtfl_options['image_size_photo'] ) )
			$prtfl_options['image_size_photo'] = 'portfolio-photo-thumb';
		if ( ! isset( $prtfl_options['image_size_album'] ) )
			$prtfl_options['image_size_album'] = 'portfolio-thumb';
	}
}