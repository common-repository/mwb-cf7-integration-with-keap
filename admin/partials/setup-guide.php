<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Keap
 * @subpackage Mwb_Cf7_Integration_With_Keap/admin/partials
 */

?>
<div class="mwb-crm-setup-content-wrap">
	<div class="mwb-crm-setup-list-wrap">
		<ul class="mwb-crm-setup-list">
			<?php if ( 'yes' == $custom_app ) : // phpcs:ignore ?>
				<li>
					<a href="https://keys.developer.keap.com/"><?php esc_html_e( 'Login', 'mwb-cf7-integration-with-keap' ); ?></a>
					<?php esc_html_e( ' to your developer account.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Go to your apps by hovering on your account email.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Click on New app to create an app.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Fill up mandatory informations like "App Name" and enable the API.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'New app will be created alongwith its credentials.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Copy "Client ID" and "Client secret" and put it in Authentication form in the plugin.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>

			<?php else : ?>

				<li>
					<?php esc_html_e( 'Click on authorize button.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'It will redirect you to Keap login panel.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'After successful login, it will redirect you to consent page, where it will ask your permissions to access the data.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Click on allow, it should redirect back to your plugin admin page and your connection part is done.', 'mwb-cf7-integration-with-keap' ); ?>
				</li>
			<?php endif; ?>

			<li>
				<?php
				echo wp_kses(
					sprintf(
					/* translators: Feed object name */
						__( 'Still facing issue! Please check detailed app setup <a href="%s" target="_blank"  >documentation</a>.', 'mwb-cf7-integration-with-keap' ),
						$params['setup_guide_url']
					),
					$params['allowed_html']
				);
				?>
			</li>
		</ul>

		<?php if ( 'yes' == $custom_app ) : // phpcs:ignore ?>
			<img src="<?php echo esc_url( $params['api_key_image'] ); ?>" id="mwb-cf7-auth-popup-img">
		<?php endif; ?>
	</div>
</div>
