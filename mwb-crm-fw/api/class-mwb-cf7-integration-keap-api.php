<?php
/**
 * Base Api Class
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Keap
 * @subpackage Mwb_Cf7_Integration_With_Keap/mwb-crm-fw
 */

/**
 * Base Api Class.
 *
 * This class defines all code necessary api communication.
 *
 * @since      1.0.0
 * @package    Mwb_Cf7_Integration_With_Keap
 * @subpackage Mwb_Cf7_Integration_With_Keap/mwb-crm-fw
 */
class Mwb_Cf7_Integration_Keap_Api extends Mwb_Cf7_Integration_Keap_Api_Base {

	/**
	 * Crm prefix
	 *
	 * @var    string   Crm prefix
	 * @since  1.0.0
	 */
	public static $crm_prefix;

	/**
	 * Keap Client Id
	 *
	 * @var     string  Client Id
	 * @since   1.0.0
	 */
	public static $client_id;

	/**
	 * Keap Secret Id
	 *
	 * @var     string Secret Id
	 * @since   1.0.0
	 */
	public static $client_secret;

	/**
	 * Kepa own app.
	 *
	 * @since    1.0.0
	 * @var      string Own app
	 */
	public static $own_app;

	/**
	 * Keap redirect uri
	 *
	 * @var      string  Redirect URI
	 * @since    1.0.0
	 */
	public static $redirect;

	/**
	 * Keap Access token data.
	 *
	 * @var     string   Stores access token data.
	 * @since   1.0.0
	 */
	public static $access_token;

	/**
	 * Keap Refresh token data
	 *
	 * @var     string   Stores refresh token data.
	 * @since   1.0.0
	 */
	public static $refresh_token;

	/**
	 * Google sheets token duration
	 *
	 * @var      string $expires_in
	 * @since    1.0.0
	 */
	private static $expires_in;

	/**
	 * Keap Email ID
	 *
	 * @var     string  Email ID.
	 * @since   1.0.0
	 */
	public static $email_id;

	/**
	 * Keap username.
	 *
	 * @var     string  Preferred Name.
	 * @since   1.0.0
	 */
	public static $name;

	/**
	 * Keap domain
	 *
	 * @var     string $domain
	 * @since   1.0.0
	 */
	public static $domain;

	/**
	 * Access token expiry data
	 *
	 * @var     integer   Stores access token expiry data.
	 * @since   1.0.0
	 */
	public static $expiry;

	/**
	 * Current instance URL
	 *
	 * @var     string    Current instance url.
	 * @since   1.0.0
	 */
	public static $instance_url;

	/**
	 * Issued at data
	 *
	 * @var     string     Issued at data by Keap
	 * @since   1.0.0
	 */
	public static $issued_at;

	/**
	 * Creates an instance of the class
	 *
	 * @var     object     An instance of the class.
	 * @since   1.0.0
	 */
	protected static $_instance = null; // phpcs:ignore

	/**
	 * Main Mwb_Cf7_Integration_Keap_Api Instance.
	 *
	 * Ensures only one instance of Mwb_Cf7_Integration_Keap_Api is loaded or can be loaded.
	 *
	 * @since   1.0.0
	 * @static
	 * @return  Mwb_Cf7_Integration_Keap_Api - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		self::initialize();
		return self::$_instance;
	}

	/**
	 * Initialize properties.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $token_data Saved token data.
	 */
	public static function initialize( $token_data = array() ) {

		self::$crm_prefix = Mwb_Cf7_Integration_With_Keap::get_current_crm( 'slug' );

		self::$own_app = get_option( 'mwb-cf7-' . self::$crm_prefix . '-own-app', false );

		if ( false != self::$own_app && 'yes' == self::$own_app ) { // phpcs:ignore
			self::$client_id     = get_option( 'mwb-cf7-' . self::$crm_prefix . '-client-id', '' );
			self::$client_secret = get_option( 'mwb-cf7-' . self::$crm_prefix . '-client-secret', '' );
			self::$redirect      = admin_url();
		} else {
			self::$client_id     = 'YdePUyAlW0PSIZBtLQZ63GpUIpmXq5tN';
			self::$client_secret = 'A5pDdbEYNXfGAbhg';
			self::$redirect      = 'https://auth.makewebbetter.com/integration/keap-auth/';
		}

		if ( empty( $token_data ) ) {
			$token_data = get_option( 'mwb-cf7-' . self::$crm_prefix . '-token-data', array() );
		}

		self::$access_token  = isset( $token_data['access_token'] ) ? $token_data['access_token'] : '';
		self::$refresh_token = isset( $token_data['refresh_token'] ) ? $token_data['refresh_token'] : '';
		self::$expires_in    = isset( $token_data['expires_in'] ) ? $token_data['expires_in'] : '';
		self::$expiry        = isset( $token_data['expires'] ) ? $token_data['expires'] : '';
		self::$domain        = isset( $token_data['scope'] ) ? $token_data['scope'] : '';

		// Get account info.
		$accountinfo = get_option( 'mwb-cf7-' . self::$crm_prefix . '-user-info', array() );

		self::$email_id = isset( $accountinfo['email'] ) ? $accountinfo['email'] : '';
		self::$name     = isset( $accountinfo['name'] ) ? $accountinfo['name'] : '';
	}

	/**
	 * Get redirect uri.
	 *
	 * @since    1.0.0
	 * @return   string   Site redirecrt Uri.
	 */
	public function get_redirect_uri() {
		return ! empty( self::$redirect ) ? self::$redirect : false;
	}

	/**
	 * Get access token.
	 *
	 * @since    1.0.0
	 * @return   string   Access token.
	 */
	public function get_access_token() {
		return ! empty( self::$access_token ) ? self::$access_token : false;
	}

	/**
	 * Get refresh token.
	 *
	 * @since     1.0.0
	 * @return    string    Refresh token.
	 */
	public function get_refresh_token() {
		return ! empty( self::$refresh_token ) ? self::$refresh_token : false;
	}

	/**
	 * Get token expiry.
	 *
	 * @since     1.0.0
	 * @return    string    Refresh token.
	 */
	public function get_access_token_expiry() {
		return ! empty( self::$expiry ) ? self::$expiry : false;
	}

	/**
	 * Check if access token is valid.
	 *
	 * @since    1.0.0
	 * @return   boolean
	 */
	public function is_access_token_valid() {
		return ! empty( self::$expiry ) ? ( self::$expiry > time() ) : false;
	}

	/**
	 * Get keap domain.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_keap_domain() {
		return ! empty( self::$domain ) ? self::$domain : false;
	}

	/**
	 * Get connected user email.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_keap_user_email() {
		return ! empty( self::$email_id ) ? self::$email_id : false;
	}

	/**
	 * Get connected username.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_keap_username() {
		return ! empty( self::$name ) ? self::$name : false;
	}

	/**
	 * Get Base Authorization url.
	 *
	 * @since    1.0.0
	 * @return   string   Base Authorization url.
	 */
	public function base_auth_url() {
		$url = 'https://accounts.infusionsoft.com/app/oauth/';
		return $url;
	}

	/**
	 * Get Google api url.
	 *
	 * @since    1.0.0
	 * @return   string
	 */
	public function base_crm_url() {
		$url = 'https://api.infusionsoft.com/';
		return $url;
	}

	/**
	 * Get authorization headers for getting token.
	 *
	 * @since   1.0.0
	 * @return  array   Headers.
	 */
	public function get_token_auth_header() {
		return array(
			'content-type' => 'application/x-www-form-urlencoded',
		);
	}

	/**
	 * Get Request headers.
	 *
	 * @since    1.0.0
	 * @return   array   Headers.
	 */
	public function get_auth_header() {

		$headers = array(
			'Authorization' => 'Bearer ' . $this->get_access_token(),
			'Content-type'  => 'application/json',
		);

		return $headers;
	}

	/**
	 * Get authorisation url.
	 *
	 * @since 1.0.0
	 * @return  string autherization url.
	 */
	public function get_auth_code_url() {

		$query_args = array(
			'response_type' => 'code',
			'state'         => urlencode( $this->get_oauth_state( self::$own_app ) ), // phpcs:ignore
			'client_id'     => urlencode( self::$client_id ), // phpcs:ignore
			'redirect_uri'  => urlencode( $this->get_redirect_uri() ), // phpcs:ignore
			'scope'         => urlencode( 'full' ), // phpcs:ignore
		);

		$auth_url = add_query_arg( $query_args, $this->base_auth_url() . 'authorize' );
		return $auth_url;
	}

	/**
	 * Get oauth state with current instance redirect url.
	 *
	 * @param  string $use_custom_app If custom app is used for authentication.
	 * @return string State.
	 */
	public function get_oauth_state( $use_custom_app = 'no' ) {

		$nonce = wp_create_nonce( 'mwb_' . self::$crm_prefix . '_cf7_state' );

		if ( 'yes' == $use_custom_app ) { // phpcs:ignore
			return $nonce;
		}

		$admin_redirect_url = admin_url();
		$args               = array(
			'mwb_nonce'  => $nonce,
			'mwb_source' => 'keap',
		);
		$admin_redirect_url = add_query_arg( $args, $admin_redirect_url );
		return $admin_redirect_url;
	}

	/**
	 * Check if response has expired access token message.
	 *
	 * @param  array $response Api response.
	 * @return bool            Access token status.
	 */
	public function if_access_token_expired( $response ) {
		if ( isset( $response['code'] ) && 401 == $response['code'] && 'Unauthorized' == $response['message'] ) { // phpcs:ignore
			return $this->renew_access_token();
		}
		return false;
	}

	/**
	 * Renew Access token.
	 *
	 * @return bool
	 */
	public function renew_access_token() {

		$refresh_token = $this->get_refresh_token();

		if ( ! empty( $refresh_token ) ) {
			$response = $this->process_access_token( false, $refresh_token );
		}

		return ! empty( $response['code'] ) && 200 == $response['code'] ? true : false; // phpcs:ignore
	}

	/**
	 * Save New token data into db.
	 *
	 * @since   1.0.0
	 * @param   string $code    Unique code to generate token.
	 */
	public function save_access_token( $code ) {
		$this->process_access_token( $code );
	}

	/**
	 * Get refresh token data from api.
	 *
	 * @since   1.0.0
	 * @param   string $code            Unique code to generate token.
	 * @param   string $refresh_token   Unique code to renew token.
	 * @return  array
	 */
	public function process_access_token( $code = '', $refresh_token = '' ) {

		$endpoint = 'token';

		$this->base_url = $this->base_crm_url();

		$params = array(
			'grant_type'    => 'authorization_code',
			'client_id'     => self::$client_id,
			'client_secret' => self::$client_secret,
			'redirect_uri'  => $this->get_redirect_uri(),
			'code'          => $code,
		);

		if ( empty( $code ) ) {
			$params['refresh_token'] = $refresh_token;
			$params['grant_type']    = 'refresh_token';
			unset( $params['code'] );
		}

		$response = $this->post( $endpoint, $params, $this->get_token_auth_header() );

		if ( isset( $response['code'] ) && 200 == $response['code'] ) { // phpcs:ignore

			// Save token.
			$token_data = ! empty( $response['data'] ) ? $response['data'] : array();
			$token_data = $this->merge_refresh_token( $token_data );

			$token_data['expires'] = time() + $token_data['expires_in'];
			update_option( 'mwb-cf7-' . self::$crm_prefix . '-token-data', $token_data );
			update_option( 'mwb-cf7-' . self::$crm_prefix . '-crm-active', true );
			self::initialize( $token_data );
		} else {
			// On failure add to log.
			delete_option( 'mwb-cf7-' . self::$crm_prefix . '-token-data' );
			delete_option( 'mwb-cf7-' . self::$crm_prefix . '-crm-active' );
			delete_option( 'mwb-cf7-' . self::$crm_prefix . '-authorised' );
		}

		return $response;
	}

	/**
	 * Get connected portal info.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_userinfo() {
		$endpoint       = 'crm/rest/v1/oauth/connect/userinfo';
		$this->base_url = $this->base_crm_url();
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, '', $headers );
		$userinfo       = array();

		if ( isset( $response['code'] ) && 200 == $response['code'] ) { // phpcs:ignore
			if ( isset( $response['data'] ) ) {
				$firstname = ! empty( $response['data']['given_name'] ) ? $response['data']['given_name'] : '';
				$lastname  = ! empty( $response['data']['family_name'] ) ? $response['data']['family_name'] : '';

				$userinfo['email'] = $response['data']['email'];
				$userinfo['name']  = $firstname . ' ' . $lastname;
			}
		}
		update_option( 'mwb-cf7-' . self::$crm_prefix . '-user-info', $userinfo );
		return $userinfo;
	}

	/**
	 * Merge refresh token with new access token data.
	 *
	 * @since   1.0.0
	 * @param   array $new_token_data   Latest token data.
	 * @return  array                   Token data.
	 */
	public function merge_refresh_token( $new_token_data ) {

		$old_token_data = get_option( 'mwb-cf7-' . self::$crm_prefix . '-token-data', array() );

		if ( empty( $old_token_data ) ) {
			return $new_token_data;
		}

		foreach ( $old_token_data as $key => $value ) {
			if ( isset( $new_token_data[ $key ] ) ) {
				$old_token_data[ $key ] = $new_token_data[ $key ];
			}
		}
		return $old_token_data;
	}

	/**
	 * Get country code.
	 *
	 * @param   string $country    Form country value.
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_country_code( $country ) {

		if ( empty( $country ) ) {
			return;
		}

		$alpha2 = MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'mwb-crm-fw/framework/jsons/Country2.json';
		$alpha3 = MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'mwb-crm-fw/framework/jsons/Country3.json';

		$result = '';

		if ( 2 == strlen( $country ) ) { // phpcs:ignore
			$country     = strtoupper( $country );
			$country_arr = wp_remote_get( $alpha2 );

			if ( ! empty( $country_arr['response']['code'] ) && ( 200 === $country_arr['response']['code'] || '200' === $country_arr['response']['code'] ) ) {
				$country2 = json_decode( $country_arr['body'], true );
			}

			if ( isset( $country2[ $country ] ) ) {
				$result = $country2[ $country ];
			}
		} else {

			$country_arr = wp_remote_get( $alpha3 );

			if ( ! empty( $country_arr['response']['code'] ) && ( 200 === $country_arr['response']['code'] || '200' === $country_arr['response']['code'] ) ) {
				$country3 = json_decode( $country_arr['body'], true );
			}

			if ( ! empty( $country ) ) {
				foreach ( $country3 as $key => $val ) {
					if ( false !== strpos( $val, $country ) ) {
						$result = $key;
						break;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Get state code.
	 *
	 * @param   string $country_code    Country code.
	 * @param   string $state_code      State code.
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_state( $country_code, $state_code ) {

		if ( empty( $state_code ) || empty( $country_code ) ) {
			return;
		}

		$state      = '';
		$state_json = MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'mwb-crm-fw/framework/jsons/States.json';
		$state_arr  = wp_remote_get( $state_json );

		if ( ! empty( $state_arr['response']['code'] ) && ( 200 === $state_arr['response']['code'] || '200' === $state_arr['response']['code'] ) ) {
			$states = json_decode( $state_arr['body'], true );
		}

		if ( ! empty( $states ) && is_array( $states ) ) {
			foreach ( $states as $key => $value ) {
				if ( $key == $country_code ) { // phpcs:ignore
					foreach ( $value as $k => $v ) {
						if ( $state_code == $k ) { // phpcs:ignore
							$state = $v;
						}
					}
				}
			}
		}
		return $state;
	}

	/**
	 * Get records data.
	 *
	 * @param  string  $module  Module name.
	 * @param  boolean $force  Fetch from api.
	 * @return array           Record data.
	 */
	public function get_records_data( $module, $force = false ) {
		$data = array();

		$data = get_transient( 'mwb_woo_keap_' . $module . '_data' );
		if ( ! $force && false !== $data ) {
			return $data;
		}

		$response = $this->get_records( $module );
		if ( $this->is_success( $response ) ) {
			$data = $response;
			set_transient( 'mwb_woo_keap_' . $module . '_data', $data );
		}

		return $data;
	}

	/**
	 * Create of update record data.
	 *
	 * @param  string  $object       Module name.
	 * @param  array   $record_data  Module data.
	 * @param  array   $log_data     Data to create log.
	 * @param  boolean $is_bulk      Is a bulk request.
	 * @param  array   $manual_sync  Is manual sync.
	 * @return array                 Response data.
	 */
	public function create_or_update_record( $object, $record_data, $log_data = array(), $is_bulk = false, $manual_sync = false ) {

		$response_data = array(
			'success' => false,
			'msg'     => __( 'Something went wrong', 'mwb-cf7-integration-with-keap' ),
		);

		$record_id     = false;
		$primary_field = '';
		$primary_value = '';

		if ( $manual_sync && ! empty( $log_data['method'] ) ) {
			$event = $log_data['method'];
		} else {
			$event = __FUNCTION__;
		}

		// Check for the existing record based on selected primary field.
		if ( ! empty( $record_data['primary_key'] ) ) {
			$primary_field = $record_data['primary_key']['key'];
			$primary_value = $record_data['primary_key']['value'];
			unset( $record_data['primary_key'] );
		}

		if ( $primary_field && $primary_value ) {
			$search_response = $this->check_for_existing_record( $object, $record_data, $primary_field, $primary_value );
			if ( $this->if_access_token_expired( $search_response ) ) {
				$search_response = $this->check_for_existing_record( $object, $record_data, $primary_field, $primary_value );
			}

			// Get record id from search query result.
			$record_id = $this->may_be_get_record_id_from_search( $search_response, $record_data, $primary_field );
		}

		if ( ! $record_id ) {

			$response = $this->create_record( $object, $record_data, $is_bulk, $log_data );
			if ( $this->if_access_token_expired( $response ) ) {
				$response = $this->create_record( $object, $record_data, $is_bulk, $log_data );
			}
			if ( $this->is_success( $response ) ) {
				$response_data['success']  = true;
				$response_data['msg']      = 'Create_Record';
				$response_data['response'] = $response;
				$response_data['id']       = $this->get_object_id_from_response( $response );
			} else {
				$response_data['success']  = false;
				$response_data['msg']      = esc_html__( 'Error posting to CRM', 'mwb-cf7-integration-with-keap' );
				$response_data['response'] = $response;
			}
		} else {

			// Update an existing record based on record_id.
			$response = $this->update_record( $record_id, $object, $record_data, $is_bulk, $log_data );
			if ( $this->if_access_token_expired( $response ) ) {
				$response = $this->update_record( $record_id, $object, $record_data, $is_bulk, $log_data );
			}

			if ( $this->is_success( $response ) ) {

				// Insert record id and message to response.
				if ( isset( $response['message'] ) ) {
					$response['message'] = 'Updated';
				}

				if ( empty( $response['data'] ) ) {
					$response['data'] = array(
						'id' => $record_id,
					);
				}

				$response_data['success']  = true;
				$response_data['msg']      = 'Update_Record';
				$response_data['response'] = $response;
				$response_data['id']       = $record_id;
			}
		}

		// Insert log in db.
		$this->log_request_in_db( $event, $object, $record_data, $response, $log_data );

		return $response_data;
	}

	/**
	 * Check for existing record using parameterizedSearch.
	 *
	 * @param string $object        Target object name.
	 * @param array  $record_data   Record data.
	 * @param string $primary_field Primary field.
	 * @param string $primary_value PRimary value.
	 *
	 * @return array                Response data array.
	 */
	public function check_for_existing_record( $object, $record_data, $primary_field, $primary_value ) {

		$optional_prop = '?optional_properties=custom_fields,notes';
		$field         = $primary_field;
		$search        = $primary_value;

		if ( 'Opportunities' == $object ) { // phpcs:ignore
			$field         = 'search_term';
			$optional_prop = '';
		} elseif ( 'Contacts' == $object ) { // phpcs:ignore
			$optional_prop .= ',job_title';
		}

		$this->base_url = $this->base_crm_url();
		$params         = ! empty( $optional_prop ) ? $optional_prop : '';
		$endpoint       = 'crm/rest/v1/' . strtolower( $object );

		if ( ! empty( $params ) ) {
			$endpoint .= '/' . $params;
		}

		$request = array(
			$field => $search,
		);

		$headers  = $this->get_auth_header();
		$response = $this->get( $endpoint, $request, $headers );

		return $response;
	}

	/**
	 * Check for exsiting record in search query response.
	 *
	 * @param array  $response      Search query response.
	 * @param array  $record_data   Request data of searched record.
	 * @param string $primary_field Primary field name.
	 *
	 * @return string|bool          Id of existing record or false.
	 */
	public function may_be_get_record_id_from_search( $response, $record_data, $primary_field ) {
		$record_id     = false;
		$found_records = array();
		if ( isset( $response['code'] ) && 200 == $response['code'] && 'OK' == $response['message'] ) { // phpcs:ignore
			if ( ! empty( $response['data'] ) ) {
				$found_records = reset( $response['data'] );

				if ( is_array( $found_records ) && ! empty( $found_records[0]['id'] ) ) {
					$record_id = $found_records[0]['id'];
				}
			}
		}
		return $record_id;
	}

	/**
	 * Create a new record.
	 *
	 * @param  string  $object     Object name.
	 * @param  array   $record_data Record data.
	 * @param  boolean $is_bulk    Is a bulk request.
	 * @param  array   $log_data   Data to create log.
	 * @return array               Response data.
	 */
	public function create_record( $object, $record_data, $is_bulk, $log_data ) {

		$this->base_url = $this->base_crm_url();
		$endpoint       = 'crm/rest/v1/' . strtolower( $object );
		$params         = wp_json_encode( $record_data );
		$headers        = $this->get_auth_header();
		$response       = $this->post( $endpoint, $params, $headers );
		return $response;
	}

	/**
	 * Update an existing record.
	 *
	 * @param  string  $record_id   Record id to be updated.
	 * @param  string  $object      Object name.
	 * @param  array   $record_data Record data.
	 * @param  boolean $is_bulk     Is a bulk request.
	 * @param  array   $log_data    Data to create log.
	 * @return array                Response data.
	 */
	public function update_record( $record_id, $object, $record_data, $is_bulk, $log_data ) {

		$this->base_url = $this->base_crm_url();
		$endpoint       = 'crm/rest/v1/' . strtolower( $object ) . '/' . $record_id;
		$params         = wp_json_encode( $record_data );
		$headers        = $this->get_auth_header();
		$response       = $this->patch( $endpoint, $params, $headers );
		return $response;
	}

	/**
	 * Insert log data in db.
	 *
	 * @param     string $event                Trigger event/ Feed .
	 * @param     string $sf_object            Name of zoho module.
	 * @param     array  $request              An array of request data.
	 * @param     array  $response             An array of response data.
	 * @param     array  $log_data             Data to log.
	 * @return    void
	 */
	public function log_request_in_db( $event, $sf_object, $request, $response, $log_data ) {

		$record_id = $this->get_object_id_from_response( $response );

		if ( '-' == $record_id ) { // phpcs:ignore
			if ( ! empty( $log_data['id'] ) ) {
				$record_id = $log_data['id'];
			}
		}

		$request  = serialize( $request ); // @codingStandardsIgnoreLine
		$response = serialize( $response ); // @codingStandardsIgnoreLine

		$feed          = ! empty( $log_data['feed_name'] ) ? $log_data['feed_name'] : false;
		$feed_id       = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		$event         = ! empty( $event ) ? $event : false;
		$record_object = ! empty( $log_data['record_object'] ) ? $log_data['record_object'] : false;

		$time     = time();
		$log_data = array(
			'event'                       => $event,
			self::$crm_prefix . '_object' => $record_object,
			self::$crm_prefix . '_id'     => $record_id,
			'request'                     => $request,
			'response'                    => $response,
			'feed_id'                     => $feed_id,
			'feed'                        => $feed,
			'time'                        => $time,
		);
		$this->insert_log_data( $log_data );

	}

	/**
	 * Retrieve object ID from crm response.
	 *
	 * @param     array $response     An array of response data from crm.
	 * @since     1.0.0
	 * @return    integer
	 */
	public function get_object_id_from_response( $response ) {
		$id = '-';
		if ( isset( $response['data'] ) && isset( $response['data']['id'] ) ) {
			return ! empty( $response['data']['id'] ) ? $response['data']['id'] : $id;
		}
		return $id;
	}

	/**
	 * Insert data to db.
	 *
	 * @param      array $data    Data to log.
	 * @since      1.0.0
	 * @return     void
	 */
	public function insert_log_data( $data ) {

		$connect         = 'Mwb_Cf7_Integration_Connect_' . self::$crm_prefix . '_Framework';
		$connect_manager = $connect::get_instance();

		if ( 'yes' != $connect_manager->get_settings_details( 'logs' ) ) { // phpcs:ignore
			return;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'mwb_' . self::$crm_prefix . '_cf7_log';
		$wpdb->insert( $table, $data ); // phpcs:ignore
	}

	/**
	 * Update single product.
	 *
	 * @param string  $module          Crm object.
	 * @param array   $record_data     Request data.
	 * @param boolean $is_bulk         Is bulk action.
	 * @param array   $log_data        Log data.
	 * @param string  $endpoint        Endpoint.
	 * @param string  $woo_id          Woo id.
	 * @return array
	 */
	public function update_single_products( $module, $record_data, $is_bulk = false, $log_data = array(), $endpoint, $woo_id ) {

		$data = array();

		$response = $this->update_products_records( $module, $record_data, $is_bulk, $log_data, $endpoint, $woo_id );

		if ( $this->is_success( $response ) ) {

			$data = $response;
		}

		return $data;
	}

	/**
	 * Update record data.
	 *
	 * @param  string  $module        Module name.
	 * @param  array   $record_data   Module data.
	 * @param  boolean $is_bulk       Is a bulk request.
	 * @param  array   $log_data      Data to create log.
	 * @param  string  $endpoint      Endpoint.
	 * @param  string  $woo_id        Woo id.
	 * @return array               Response data.
	 */
	private function update_products_records( $module, $record_data, $is_bulk, $log_data, $endpoint, $woo_id ) {

		$feed_id  = ! empty( $log_data['feed_id'] ) ? $log_data['feed_id'] : false;
		$headers  = $this->get_auth_header();
		$response = $this->patch( $endpoint, $record_data, $headers );

		$this->log_request_in_db(
			__FUNCTION__,
			$module,
			$record_data,
			$response,
			$log_data,
			$woo_id
		);

		return $response;

	}

	/**
	 * Create order record data.
	 *
	 * @param  string  $module          Module name.
	 * @param  array   $record_data     Module data.
	 * @param  boolean $is_bulk         Is a bulk request.
	 * @param  array   $log_data        Data to create log.
	 * @param  string  $endpoint        Endpoint.
	 * @param  string  $woo_id          Woo id.
	 * @return array                    Response data.
	 */
	public function create_order_record( $module, $record_data, $is_bulk = false, $log_data = array(), $endpoint, $woo_id = '' ) {
		$data = array();

		$response = $this->create_keap_orders( $module, $record_data, $is_bulk, $log_data, $endpoint, $woo_id );
		if ( $this->is_success( $response ) ) {
			$data = $response;
		}

		return $data;
	}

	/**
	 * Update record data.
	 *
	 * @param  string  $module         Module name.
	 * @param  array   $record_data    Module data.
	 * @param  boolean $is_bulk        Is a bulk request.
	 * @param  array   $log_data       Data to create log.
	 * @param  string  $endpoint       Endpoint.
	 * @param  string  $woo_id         Woo id.
	 * @return array                   Response data.
	 */
	private function create_keap_orders( $module, $record_data, $is_bulk, $log_data, $endpoint, $woo_id = '' ) {

		$headers = $this->get_auth_header();

		$response = $this->post( $endpoint, $record_data, $headers );

		$this->log_request_in_db(
			__FUNCTION__,
			$module,
			$record_data,
			$response,
			$log_data,
			$woo_id
		);

		return $response;

	}

	/**
	 * Create payment record.
	 *
	 * @param  string  $module         Module name.
	 * @param  array   $record_data    Module data.
	 * @param  boolean $is_bulk        Is a bulk request.
	 * @param  array   $log_data       Data to create log.
	 * @param  string  $endpoint       Endpoint.
	 * @param  string  $woo_id         Woo id.
	 * @return array                   Response data.
	 */
	public function create_order_payment_record( $module, $record_data, $is_bulk = false, $log_data = array(), $endpoint, $woo_id = '' ) {

		$data = array();

		$response = $this->create_payment_record( $module, $record_data, $is_bulk, $log_data, $endpoint, $woo_id );
		if ( $this->is_success( $response ) ) {
			$data = $response;
		}

		return $data;

	}

	/**
	 * Update record data.
	 *
	 * @param  string  $module         Module name.
	 * @param  array   $record_data    Module data.
	 * @param  boolean $is_bulk        Is a bulk request.
	 * @param  array   $log_data       Data to create log.
	 * @param  string  $endpoint       Endpoint.
	 * @param  string  $woo_id         Woo id.
	 * @return array                   Response data.
	 */
	private function create_payment_record( $module, $record_data, $is_bulk, $log_data, $endpoint, $woo_id = '' ) {

		$headers = $this->get_auth_header();

		$response = $this->post( $endpoint, $record_data, $headers );

		$this->log_request_in_db(
			__FUNCTION__,
			$module,
			$record_data,
			$response,
			$log_data,
			$woo_id
		);

		return $response;

	}

	/**
	 * Get all records for a specific module.
	 *
	 * @param  string $module Module name.
	 * @return array          Response.
	 */
	public function get_records( $module ) {
		$this->base_url = $this->base_crm_url();
		$endpoint       = 'crm/rest/v1/' . $module;
		$data           = array();
		$headers        = $this->get_auth_header();
		$response       = $this->get( $endpoint, $data, $headers );

		if ( $this->if_access_token_expired( $response ) ) {
			$headers  = $this->get_auth_header();
			$response = $this->get( $endpoint, $data, $headers );

		}
		return $response;
	}

	/**
	 * Get modules.
	 *
	 * @return array Response containing all modules.
	 */
	private function get_modules() {

		$objects = apply_filters(
			'mwb_cf7_' . self::$crm_prefix . '_objects_list',
			array(
				'Appointments' => 'Appointment',
				'Contacts'     => 'Contact',
				'Companies'    => 'Company',
				'Tasks'        => 'Task',
			)
		);

		return $objects;
	}

	/**
	 * Check if resposne has success code.
	 *
	 * @param  array $response  Response data.
	 * @return boolean           Success.
	 */
	private function is_success( $response ) {
		if ( isset( $response['code'] ) ) {
			return in_array( $response['code'], array( 200, 201, 204, 202 ) ); // phpcs:ignore
		}
		return false;
	}

	/**
	 * Check if resposne has success code.
	 *
	 * @param  array $response  Response data.
	 * @return boolean           Success.
	 */
	private function is_success_response( $response ) {

		if ( ! empty( $response['code'] ) && ( 200 == $response['code'] ) ) { // phpcs:ignore
			return true;
		} elseif ( ! empty( $response['code'] ) && ( 201 == $response['code'] ) ) { // phpcs:ignore
			return true;
		} elseif ( ! empty( $response['message'] ) && ( 'OK' == $response['message'] ) ) { // phpcs:ignore
			return true;
		} elseif ( ! empty( $response['message'] ) && ( 'OK' == $response['Created'] ) ) { // phpcs:ignore
			return true;
		}
		return false;
	}

	/**
	 * Get fields for specific module.
	 *
	 * @param  string  $module Module name.
	 * @param  boolean $force  Fetch from api.
	 * @return array           Fields data.
	 */
	public function get_module_fields( $module, $force = false ) {

		$module_json = $this->get_module_request( $module );
		if ( empty( $module_json ) ) {
			$arr = array();
		} else {
			$arr = json_decode( $module_json, 1 );
		}

		if ( Mwb_Cf7_Integration_With_Keap_Admin::is_pro_available_and_active() ) { // phpcs:ignore
			if ( 'Tasks' == $module ) { // phpcS:ignore
				$arr['Contact ID'] = array(
					'group'    => 'contact',
					'id'       => 'id',
					'type'     => 'Integer',
					'required' => true,
				);
			}
		}

		if ( empty( $arr ) ) {
			return array();
		}

		return $arr;

	}

	/**
	 * Get all module data.
	 *
	 * @param  boolean $force Fetch from api.
	 * @return array          Module data.
	 */
	public function get_modules_data( $force = false ) {
		$modules = $this->get_modules();
		return $modules;
	}

	/**
	 * Get the keap api request format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $module The module of crm.
	 *
	 * @return string|bool The request json for module.
	 */
	public function get_module_request( $module = false ) {

		$json = array();

		$json_url = MWB_CF7_INTEGRATION_WITH_KEAP_URL . 'mwb-crm-fw/framework/jsons/' . $module . '.json';

		$response = wp_remote_get( $json_url );

		if ( ! empty( $response['response']['code'] ) && ( 200 === $response['response']['code'] || '200' === $response['response']['code'] ) ) {
			$json[ $module ] = $response['body'];
		}

		if ( ! empty( $module ) && ! empty( $json[ $module ] ) ) {
			return $json[ $module ];
		} else {
			return array();
		}
	}

	// End of class.
}
