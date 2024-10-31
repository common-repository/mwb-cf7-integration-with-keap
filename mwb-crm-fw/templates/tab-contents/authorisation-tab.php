<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the accounts creation page.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Keap
 * @subpackage Mwb_Cf7_Integration_With_Keap/mwb-crm-fw/framework/templates/tab-contents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php if ( '1' !== get_option( 'mwb-cf7-' . $this->crm_slug . '-crm-active', false ) ) : ?>
	<section class="mwb-intro">
		<div class="mwb-content-wrap">
			<div class="mwb-intro__header">
				<h2 class="mwb-section__heading">
					<?php echo sprintf( 'Getting started with CF7 and %s', esc_html( $this->crm_name ) ); ?>
				</h2>
			</div>
			<div class="mwb-intro__body mwb-intro__content">
				<p>
				<?php
				echo sprintf(
					/* translators: %1$s: CRM name, %2$s: CRM name, %3$s: CRM modules, %4$s: CRM name  */
					esc_html__( 'With this CF7 %1$s Integration you can easily sync all your CF7 Form Submissions data over %2$s. It will create %3$s over %4$s CRM, based on your CF7 Form Feed data.', 'mwb-cf7-integration-with-keap' ),
					esc_html( $this->crm_name ),
					esc_html( $this->crm_name ),
					esc_html( $params['api_modules'] ),
					esc_html( $this->crm_name )
				);
				?>
				</p>
				<ul class="mwb-intro__list">
					<li class="mwb-intro__list-item">
						<?php
						echo sprintf(
							/* translators: %s: CRM name */
							esc_html__( 'Connect your %s CRM account with CF7.', 'mwb-cf7-integration-with-keap' ),
							esc_html( $this->crm_name )
						);
						?>
					</li>
					<li class="mwb-intro__list-item">
						<?php
						echo sprintf(
							/* translators: %s: CRM name */
							esc_html__( 'Sync your data over %s CRM.', 'mwb-cf7-integration-with-keap' ),
							esc_html( $this->crm_name )
						);
						?>
					</li>
				</ul>
				<div class="mwb-intro__button">
					<a href="javascript:void(0)" class="mwb-btn mwb-btn--filled" id="mwb-showauth-form">
						<?php esc_html_e( 'Connect your Account.', 'mwb-cf7-integration-with-keap' ); ?>
					</a>
				</div>
			</div> 
		</div>
	</section>

	<!--============================================================================================
											Authorization form start.
	================================================================================================-->

	<div class="mwb_cf7_integration_account-wrap row-hide" id="mwb-cf7-auth_wrap">
		<!-- Logo section start -->
		<div class="mwb-cf7_integration_logo-wrap">
			<div class="mwb-cf7_integration_logo-crm">
				<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'admin/images/crm.png' ); ?>" alt="<?php esc_html_e( 'Keap', 'mwb-cf7-integration-with-keap' ); ?>">
			</div>
			<div class="mwb-cf7_integration_logo-contact">
				<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'admin/images/contact-form.svg' ); ?>" alt="<?php esc_html_e( 'CF7', 'mwb-cf7-integration-with-keap' ); ?>">
			</div>
		</div>
		<!-- Logo section end -->

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

						<!-- Own app start -->
						<tr>
							<th>
								<label>
									<?php esc_html_e( 'Use own app', 'mwb-cf7-integration-with-keap' ); ?>
								</label>

								<?php
								$desc = esc_html__( 'Enable this to use your own Keap app credentials to authorize with Keap.', 'mwb-cf7-integration-with-keap' );
								echo esc_html( $params['admin_class']::mwb_cf7_integration_tooltip( $desc ) );
								?>
							</th>

							<td>
								<input type="checkbox" name="mwb_account[own_app]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-own-app" value="yes" <?php checked( 'yes', $params['own_app'] ); ?> >
							</td>
						</tr>
						<!-- Own app end -->

						<!-- Client ID start  -->
						<tr class="mwb-api-fields">
							<th>							
								<label>
									<?php esc_html_e( 'Client ID', 'mwb-cf7-integration-with-keap' ); ?>
								</label>

								<?php
								$desc = esc_html__( 'Enter Client ID of your own app.', 'mwb-cf7-integration-with-keap' );
								echo esc_html( $params['admin_class']::mwb_cf7_integration_tooltip( $desc ) );
								?>
							</th>

							<td>
								<?php
								$client_id = ! empty( $params['client_id'] ) ? sanitize_text_field( wp_unslash( $params['client_id'] ) ) : '';
								?>
								<div class="mwb-cf7-integration__secure-field">
									<input type="password"  name="mwb_account[client_id]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-client-id" value="<?php echo esc_html( $client_id ); ?>" required placeholder="<?php esc_html_e( 'Enter your Client ID here', 'mwb-cf7-integration-with-keap' ); ?>">
									<div class="mwb-cf7-integration__trailing-icon">
										<span class="dashicons dashicons-visibility mwb-toggle-view"></span>
									</div>
								</div>
							</td>
						</tr>
						<!-- Client ID end -->

						<!-- Client Secret start  -->
						<tr class="mwb-api-fields">
							<th>							
								<label>
									<?php esc_html_e( 'Client Secret', 'mwb-cf7-integration-with-keap' ); ?>
								</label>

								<?php
								$desc = esc_html__( 'Enter Client Secret of your own app.', 'mwb-cf7-integration-with-keap' );
								echo esc_html( $params['admin_class']::mwb_cf7_integration_tooltip( $desc ) );
								?>
							</th>

							<td>
								<?php
								$client_secret = ! empty( $params['client_secret'] ) ? sanitize_text_field( wp_unslash( $params['client_secret'] ) ) : '';
								?>
								<div class="mwb-cf7-integration__secure-field">
									<input type="password"  name="mwb_account[client_secret]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-client-secret" value="<?php echo esc_html( $client_secret ); ?>" required placeholder="<?php esc_html_e( 'Enter your Client Secret here', 'mwb-cf7-integration-with-keap' ); ?>">
									<div class="mwb-cf7-integration__trailing-icon">
										<span class="dashicons dashicons-visibility mwb-toggle-view"></span>
									</div>
								</div>
							</td>
						</tr>
						<!-- Client Secret end -->

						<!-- Save & connect account start -->
						<tr>
							<th>
							</th>
							<td>
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( '?mwb-cf7-integration-perform-auth=1' ) ) ); ?>" class="mwb-btn mwb-btn--filled mwb_cf7_integration_submit_account" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-authorize-button" ><?php esc_html_e( 'Authorize', 'mwb-cf7-integration-with-keap' ); ?></a>
							</td>
						</tr>
						<!-- Save & connect account end -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- Login form end -->

		<!-- Info section start -->
		<div class="mwb-intro__bottom-text-wrap ">
			<p>
				<?php esc_html_e( 'Don’t have an account yet . ', 'mwb-cf7-integration-with-keap' ); ?>
				<a href="https://keap.com/" target="_blank" class="mwb-btn__bottom-text">
					<?php esc_html_e( 'Create A Free Account', 'mwb-cf7-integration-with-keap' ); ?>
				</a>
			</p>
			<p class="mwb-api-fields">
				<?php esc_html_e( 'Get Your Api Key here.', 'mwb-cf7-integration-with-keap' ); ?>
				<a href="https://keys.developer.keap.com/" target="_blank" class="mwb-btn__bottom-text"><?php esc_html_e( 'Get Api Keys', 'mwb-cf7-integration-with-keap' ); ?></a>
			</p>
			<p>
				<?php esc_html_e( 'Check app setup guide . ', 'mwb-cf7-integration-with-keap' ); ?>
				<a href="javascript:void(0)" class="mwb-btn__bottom-text trigger-setup-guide"><?php esc_html_e( 'Show Me How', 'mwb-cf7-integration-with-keap' ); ?></a>
			</p>
		</div>
		<!-- Info section end -->
	</div>

<?php else : ?>

	<!-- Successfull connection start -->
	<section class="mwb-sync">
		<div class="mwb-content-wrap">
			<div class="mwb-sync__header">
				<h2 class="mwb-section__heading">
					<?php
					echo sprintf(
						/* translators: %s: CRM name */
						esc_html__( 'Congrats! You’ve successfully set up the MWB CF7 Integration with %s Plugin.', 'mwb-cf7-integration-with-keap' ),
						esc_html( $this->crm_name )
					);
					?>
				</h2>
			</div>
			<div class="mwb-sync__body mwb-sync__content-wrap">            
				<div class="mwb-sync__image">    
					<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'admin/images/congo.jpg' ); ?>" >
				</div>       
				<div class="mwb-sync__content">            
					<p> 
						<?php
						echo sprintf(
							/* translators: %s: CRM name */
							esc_html__( 'Now you can go to the dashboard and check connection data. You can create your feeds, edit them in the feeds tab. If you do not see your data over %S, you can check the logs for any possible error.', 'mwb-cf7-integration-with-keap' ),
							esc_html( $this->crm_name )
						);
						?>
					</p>
					<div class="mwb-sync__button">
						<a href="javascript:void(0)" class="mwb-btn mwb-btn--filled mwb-onboarding-complete">
							<?php esc_html_e( 'View Dashboard', 'mwb-cf7-integration-with-keap' ); ?>
						</a>
					</div>
				</div>             
			</div>       
		</div>
	</section>
	<!-- Successfull connection end -->

<?php endif; ?>
