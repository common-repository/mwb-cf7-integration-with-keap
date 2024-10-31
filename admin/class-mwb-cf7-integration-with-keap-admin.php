<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Keap
 * @subpackage Mwb_Cf7_Integration_With_Keap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Cf7_Integration_With_Keap
 * @subpackage Mwb_Cf7_Integration_With_Keap/admin
 * @author     MakeWebBetter <https://makewebbetter.com>
 */
class Mwb_Cf7_Integration_With_Keap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Current crm slug.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $crm_slug    The current crm slug.
	 */
	public $crm_slug;

	/**
	 * Current crm name.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @var       string   $crm_name    The current crm name.
	 */
	public $crm_name;

	/**
	 * Current CRM API class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $crm_class   Name of the current CRM API class.
	 */
	public $crm_class;

	/**
	 * Instance of the plugin main class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object   $core_class    Name of the plugin core class.
	 */
	public $core_class = 'Mwb_Cf7_Integration_With_Keap';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Initialise CRM name and slug.
		$this->crm_slug = $this->core_class::get_current_crm( 'slug' );
		$this->crm_name = $this->core_class::get_current_crm();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Cf7_Integration_With_Keap_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Cf7_Integration_With_Keap_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( $this->is_valid_screen() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb-cf7-integration-with-keap-admin.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/select2/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-tooltip', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/jq-tiptip/tooltip.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-datatable-css', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-animate', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/animate/animate.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Cf7_Integration_With_Keap_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Cf7_Integration_With_Keap_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( $this->is_valid_screen() ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-cf7-integration-with-keap-admin.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/select2/select2.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-swal2', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/sweet-alert2/sweet-alert2.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-tooltip', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/jq-tiptip/jquery.tipTip.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-datatable-js', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/datatables/media/js/jquery.dataTables.min.js', array(), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-datatable-responsive-js', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/datatables.net-responsive/js/dataTables.responsive.min.js', array(), $this->version, false );

			$ajax_data = array(
				'crm'           => $this->crm_slug,
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'ajaxNonce'     => wp_create_nonce( 'mwb_cf7_' . $this->crm_slug . '_nonce' ),
				'ajaxAction'    => 'mwb_cf7_' . $this->crm_slug . '_ajax_request',
				'feedBackLink'  => admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page&tab=feeds' ),
				'feedBackText'  => esc_html__( 'Back to feeds', 'mwb-cf7-integration-with-keap' ),
				'isPage'        => isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '', // phpcs:ignore
				'criticalError' => esc_html__( 'Internal server error', 'mwb-cf7-integration-with-keap' ),
				'trashIcon'     => MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'admin/images/trash.svg',
				'api_key_image' => MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'admin/images/api.png',
			);

			wp_localize_script( $this->plugin_name, 'mwb_cf7_integration_ajax_data', $ajax_data );
		}
	}

	/**
	 * Check for the screens provided by the plugin.
	 *
	 * @since     1.0.0
	 * @return    bool
	 */
	public function is_valid_screen() {

		$result = false;

		$valid_screens = array(
			'mwb_' . $this->crm_slug . '_cf7_page',
			'mwb_' . $this->crm_slug . '_cf7',
		);

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) ) {

			$pages = $screen->id;

			foreach ( $valid_screens as $screen ) {
				if ( false !== strpos( $pages, $screen ) ) { // phpcs:ignore
					$result = true;
				}
			}
		}

		return $result;
	}

	/**
	 * Add CRM submenu to Contact menu.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_submenu() {
		add_submenu_page(
			'wpcf7',
			'Keap',
			'Keap',
			'manage_options',
			'mwb_' . $this->crm_slug . '_cf7_page',
			array( $this, 'mwb_cf7_integration_submenu_cb' ),
			4
		);
	}

	/**
	 * CRM sub-menu callback function.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_submenu_cb() {
		require_once MWB_CF7_INTEGRATION_WITH_KEAP_DIRPATH . 'admin/partials/mwb-cf7-integration-with-' . $this->crm_slug . '-admin-display.php';
	}

	/**
	 * Function to run at admin intitialization.
	 *
	 * @since     1.0.0
	 * @return    bool
	 */
	public function mwb_cf7_integration_admin_init_process() {

		if ( 'keap' != $this->crm_slug ) { // phpcs:ignore
			return;
		}

		if ( ! empty( $_GET['mwb-cf7-integration-perform-auth'] ) ) {
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {

				$method  = ! empty( $_GET['method'] ) ? sanitize_text_field( wp_unslash( $_GET['method'] ) ) : false;
				$own_app = 'true' == $method ? 'yes' : 'no'; // phpcs:ignore

				if ( 'yes' == $own_app ) { // phpcs:ignore
					$client_id     = ! empty( $_GET['client_id'] ) ? sanitize_text_field( wp_unslash( $_GET['client_id'] ) ) : false;
					$client_secret = ! empty( $_GET['client_secret'] ) ? sanitize_text_field( wp_unslash( $_GET['client_secret'] ) ) : false;

					if ( ! $client_id || ! $client_secret ) {
						return false;
					}

					update_option( 'mwb-cf7-' . $this->crm_slug . '-client-id', $client_id );
					update_option( 'mwb-cf7-' . $this->crm_slug . '-client-secret', $client_secret );
				}
				update_option( 'mwb-cf7-' . $this->crm_slug . '-own-app', $own_app );

				$crm_class      = 'Mwb_Cf7_Integration_' . $this->crm_name . '_Api';
				$crm_api_module = $crm_class::get_instance();
				$auth_url       = $crm_api_module->get_auth_code_url();

				wp_redirect( $auth_url ); // phpcs:ignore
				exit;

			}
		} elseif ( ! empty( $_GET['code'] ) ) {

			if ( ! isset( $_GET['state'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['state'] ) ), 'mwb_' . $this->crm_slug . '_cf7_state' ) ) {
				wp_die( 'The state is not correct from Keap Server. Try again.' );
			}

			$crm_class      = 'Mwb_Cf7_Integration_' . $this->crm_name . '_Api';
			$auth_code      = ! empty( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : false;
			$crm_api_module = $crm_class::get_instance();
			$crm_api_module->save_access_token( $auth_code );
			$crm_api_module->get_userinfo();
			wp_redirect( admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page' ) ); // phpcs:ignore
			exit;

		} elseif ( ! empty( $_GET['mwb-cf7-perform-refresh'] ) ) { // Perform refresh token.

			$crm_class      = 'Mwb_Cf7_Integration_' . $this->crm_name . '_Api';
			$crm_api_module = $crm_class::get_instance();
			$crm_api_module->renew_access_token();
			wp_redirect( admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page' ) ); // phpcs:ignore
			exit;

		} elseif ( ! empty( $_GET['mwb-cf7-integration-perform-reauth'] ) ) { // Perform reauthorization.
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
				$crm_class      = 'Mwb_Cf7_Integration_' . $this->crm_name . '_Api';
				$crm_api_module = $crm_class::get_instance();
				$auth_url       = $crm_api_module->get_auth_code_url();
				if ( ! $auth_url ) {
					return;
				}
				wp_redirect( $auth_url ); // phpcs:ignore
				exit;
			}
		}

		/* Download log file */
		if ( ! empty( $_GET['mwb_download'] ) ) { // Perform log file download.
			$filename = WP_CONTENT_DIR . '/uploads/mwb-' . $this->crm_slug . '-cf7-logs/mwb-' . $this->crm_slug . '-cf7-sync-log.log';
			header( 'Content-type: text/plain' );
			header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
			readfile( $filename ); // phpcs:ignore
			exit;
		}

	}

	/**
	 * Get plugin name and version.
	 *
	 * @since    1.0.0
	 * @return   array
	 */
	public function add_plugin_headings() {

		$headings = array(
			'name'    => esc_html__( 'MWB CF7 Integration with Keap', 'mwb-cf7-integration-with-keap' ),
			'version' => MWB_CF7_INTEGRATION_WITH_KEAP_VERSION,
		);

		return apply_filters( 'mwb_' . $this->crm_slug . '_cf7_plugin_headings', $headings );
	}

	/**
	 * Tooltip icon and tooltip data.
	 *
	 * @param     string $tip Tip to display.
	 * @since     1.0.0
	 * @return    void
	 */
	public static function mwb_cf7_integration_tooltip( $tip ) {
		$crm_slug = Mwb_Cf7_Integration_With_Keap::get_current_crm( 'slug' );
		?>
			<i class="mwb_<?php echo esc_attr( $crm_slug ); ?>_cf7_tips" data-tip="<?php echo esc_html( $tip ); ?>"><span class="dashicons dashicons-editor-help"></span></i> 
		<?php

	}

	/**
	 * Clear sync log callback.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_clear_sync_log() {

		$connect          = 'Mwb_Cf7_Integration_Connect_' . $this->crm_slug . '_Framework';
		$connect_manager  = $connect::get_instance();
		$delete_duration  = $connect_manager->get_settings_details( 'delete_logs' );
		$delete_timestamp = time() - ( $delete_duration * 24 * 60 * 60 );
		$connect_manager->clear_n_days_log( $delete_timestamp );

	}

	/**
	 * Get all valid screens to add scripts and templates.
	 *
	 * @param     array $valid_screens An array of plugin scrrens.
	 * @since     1.0.0
	 * @return    array
	 */
	public function mwb_cf7_integration_add_frontend_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {

			// Push your screen here.
			array_push( $valid_screens, 'contact_page_mwb_' . $this->crm_slug . '_cf7_page' );
		}

		return $valid_screens;
	}

	/**
	 * Get all valid slugs to add deactivate popup.
	 *
	 * @param     array $valid_screens An array of plugin scrrens.
	 * @since     1.0.0
	 * @return    array
	 */
	public function mwb_cf7_integration_add_deactivation_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {

			// Push your screen here.
			array_push( $valid_screens, 'mwb-cf7-integration-with-' . $this->crm_slug );
		}

		return $valid_screens;
	}

	/**
	 * Returns if pro plugin is active or not.
	 *
	 * @since      1.0.0
	 * @return     bool
	 */
	public static function pro_dependency_check() {

		// Check if pro plugin exists.
		if ( mwb_keap_cf7_is_plugin_active( 'cf7-integration-with-keap/cf7-integration-with-keap.php' ) ) {

			if ( class_exists( 'Cf7_Integration_With_Keap_Admin' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks Whether Pro version is compatible or not.
	 *
	 * @since      1.0.0
	 * @return     bool|string
	 */
	public static function version_compatibility_check() {

		if ( self::pro_dependency_check() ) {

			// When Pro plugin is outdated.
			if ( defined( 'CF7_INTEGRATION_WITH_KEAP_VERSION' ) && version_compare( CF7_INTEGRATION_WITH_KEAP_VERSION, '1.0.0' ) < 0 ) {

				return 'incompatible';
			} else {

				return 'compatible';
			}
		}

		return false;
	}

	/**
	 * Validate Pro version compatibility.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_version_compatibility() {

		// When Pro version in incompatible.
		if ( 'incompatible' == self::version_compatibility_check() ) { // phpcs:ignore

			set_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible', 'true' );

			// Deactivate Pro Plugin.
			add_action( 'admin_init', array( $this, 'mwb_cf7_integration_deactivate_pro_plugin' ) );

		} elseif ( 'compatible' == self::version_compatibility_check() && 'true' == get_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible' ) ) {  // phpcs:ignore
			// When Pro version in compatible and transient is set.
			delete_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible' );
		}

		if ( 'true' == get_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible' ) ) { // phpcs:ignore

			// Deactivate Pro Plugin admin notice.
			add_action( 'admin_notices', array( $this, 'mwb_cf7_integration_deactivate_pro_admin_notice' ) );
		}
	}

	/**
	 * Deactivate Pro Plugin.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_deactivate_pro_plugin() {

		// To hide Plugin activated notice.
		if ( ! empty( $_GET['activate'] ) ) { //phpcs:ignore

			unset( $_GET['activate'] ); //phpcs:ignore
		}

		deactivate_plugins( 'cf7-integration-with-keap/cf7-integration-with-keap.php' );
	}

	/**
	 * Deactivate Pro Plugin admin notice.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_deactivate_pro_admin_notice() {

		$screen = get_current_screen();

		$valid_screens = array(
			'mwb_' . $this->crm_slug . '_cf7_page',
			'plugins',
		);

		$pro = esc_html__( 'CF7 Integration with Keap', 'mwb-cf7-integration-with-keap' );
		$org = esc_html__( 'MWB CF7 Integration with Keap', 'mwb-cf7-integration-with-keap' );

		if ( ! empty( $screen->id ) && in_array( $screen->id, $valid_screens ) ) { // phpcs:ignore
			?>

			<div class="notice notice-error is-dismissible mwb-notice">
				<p>
					<?php
					echo sprintf(
						/* translators: %1$s: Pro plugin, %2$s: Org plugin. */
						esc_html__( '%1$s is deactivated, Please Update the PRO version as this version is outdated and will not work with the current %2$s Org version', 'mwb-cf7-integration-with-keap' ),
						'<strong>' . esc_html( $pro ) . '</strong>',
						'<strong>' . esc_html( $org ) . '</strong>'
					);
					?>
				</p>
			</div>

			<?php
		}
	}

	/**
	 * Check if pro plugin active and trail not expired.
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	public static function is_pro_available_and_active() {
		$result   = false;
		$crm_name = Mwb_Cf7_Integration_With_Keap::get_current_crm();
		$pro_main = 'Cf7_Integration_With_' . $crm_name;
		if ( self::pro_dependency_check() ) {

			$license    = $pro_main::$mwb_cf7_pro_license_cb;
			$ini_days   = $pro_main::$mwb_cf7_pro_ini_license_cb;
			$days_count = $pro_main::$ini_days();

			if ( ! $pro_main::$license() && 0 > $days_count ) {
				$result = true;
			}
		} elseif ( false === self::pro_dependency_check() ) {
			$result = true;
		}
		return $result;
	}


}
