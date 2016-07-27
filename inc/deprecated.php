<?php
/**
* Includes deprecated functions
 * @deprecated since 2.38
 * @todo remove after 01.02.2017
*/
if ( ! function_exists( 'prtfl_rename_options' ) ) {
	function prtfl_rename_options() {
		global $prtfl_options, $prtfl_plugin_info;
		if ( $prtfl_options['plugin_option_version'] != $prtfl_plugin_info["Version"] && $prtfl_options['plugin_option_version'] < '2.38' ) {
			$prefix_options = array(
				'prtfl_custom_size_name',
				'prtfl_custom_size_px',
				'prtfl_order_by',
				'prtfl_order',
				'prtfl_custom_image_row_count',
				'prtfl_date_additional_field',
				'prtfl_link_additional_field',
				'prtfl_shrdescription_additional_field',
				'prtfl_description_additional_field',
				'prtfl_svn_additional_field',
				'prtfl_executor_additional_field',
				'prtfl_technologies_additional_field',
				'prtfl_link_additional_field_for_non_registered',
				'prtfl_date_text_field',
				'prtfl_link_text_field',
				'prtfl_shrdescription_text_field',
				'prtfl_description_text_field',
				'prtfl_svn_text_field',
				'prtfl_executor_text_field',
				'prtfl_screenshot_text_field',
				'prtfl_technologies_text_field',
				'prtfl_slug',
				'prtfl_rewrite_template',
			);
			foreach ( $prefix_options as $old_option ) {
				$new_option = str_replace( 'prtfl_', '', $old_option );
				if ( isset( $prtfl_options[ $old_option ] ) ) {
					$prtfl_options[ $new_option ] = $prtfl_options[ $old_option ];
					unset( $prtfl_options[ $old_option ] );
				}
			}
		}
	}
}