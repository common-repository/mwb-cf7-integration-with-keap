<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select fields section of feeds.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Keap
 * @subpackage Mwb_Cf7_Integration_With_Keap/mwb-crm-fw/framework/templates/meta-boxes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}
?>
<div id="mwb-fields-form-section-wrapper" class="mwb-feeds__content  mwb-content-wrap row-hide">
	<a class="mwb-feeds__header-link">
		<?php esc_html_e( 'Map Fields', 'mwb-cf7-integration-with-keap' ); ?>
	</a>
	<div id="mwb-fields-form-section" class="mwb-feeds__meta-box-main-wrapper">
	<?php
	$mapping_exists = ! empty( $params['mapping_data'] );

	foreach ( $params['crm_fields'] as $key => $fields_data ) {

		if ( empty( $fields_data['name'] ) ) {
			$fields_data['name'] = $key;
		}
		$option_data  = $params['field_options'];
		$default_data = array(
			'field_type'  => 'standard_field',
			'field_value' => '',
		);

		if ( $mapping_exists ) {
			if ( ! array_key_exists( $fields_data['name'], $params['mapping_data'] ) ) {
				continue;
			}
			$default_data = $params['mapping_data'][ $fields_data['name'] ];

		} else {
			if ( isset( $fields_data['required'] ) && ! $fields_data['required'] ) {
				continue;
			}
		}
		$template_class   = 'Mwb_Cf7_Integration_' . Mwb_Cf7_Integration_With_Keap::get_current_crm() . '_Template_Manager';
		$template_manager = $template_class::get_instance();
		$template_class::get_field_section_html( $option_data, $fields_data, $default_data );
	}
	?>
	</div>
</div>
