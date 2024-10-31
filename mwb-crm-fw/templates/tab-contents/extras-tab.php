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
 * @subpackage Mwb_Cf7_Integration_With_Keap/mwb-crm-fw/framework/templates/tab-contents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$client_id     = get_option( 'mwb-cf7-' . $this->crm_slug . '-client-id', '' );
$client_secret = get_option( 'mwb-cf7-' . $this->crm_slug . '-client-secret', '' );
?>
<div class="mwb-reauth__body row-hide">
	<div class="mwb-crm-reauth-wrap">
		<div class="mwb-reauth__body-close">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'admin/images/cancel.png' ); ?>" alt="Close">
		</div>
		<!-- Login form start -->
		<form method="post" id="mwb_cf7_integration_account_form">

			<div class="mwb_cf7_integration_table_wrapper">
				<div class="mwb_cf7_integration_account_setup">
					<h2>
						<?php esc_html_e( 'Enter your api credentials here', 'mwb-cf7-integration-with-keap' ); ?>
					</h2>
				</div>

				<table class="mwb_cf7_integration_table">
					<tbody>

						<!-- Client ID start  -->
						<tr class="mwb-api-fields">
							<th>							
								<label><?php esc_html_e( 'Client ID', 'mwb-cf7-integration-with-keap' ); ?></label>
							</th>

							<td>
								<input type="text" readonly name="mwb_account[client_id]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-client-id" value="<?php echo esc_html( $client_id ); ?>" required placeholder="<?php esc_html_e( 'Enter your Client ID here', 'mwb-cf7-integration-with-keap' ); ?>">
							</td>
						</tr>
						<!-- Client ID end -->

						<!-- Client Secret start  -->
						<tr class="mwb-api-fields">
							<th>							
								<label><?php esc_html_e( 'Client Secret', 'mwb-cf7-integration-with-keap' ); ?></label>
							</th>

							<td>
								<input type="text" readonly name="mwb_account[client_secret]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-client-secret" value="<?php echo esc_html( $client_secret ); ?>" required placeholder="<?php esc_html_e( 'Enter your Client Secret here', 'mwb-cf7-integration-with-keap' ); ?>">	
							</td>
						</tr>
						<!-- Client Secret end -->

						<!-- Save & connect account start -->
						<tr>
							<th>
							</th>
							<td>
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( '?mwb-cf7-integration-perform-reauth=1' ) ) ); ?>" class="mwb-btn mwb-btn--filled mwb_cf7_integration_submit_account"  ><?php esc_html_e( 'Reauthorize', 'mwb-cf7-integration-with-keap' ); ?></a>
							</td>
						</tr>
						<!-- Save & connect account end -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- Login form end -->

	</div>
</div>
