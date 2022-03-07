<?php
/**
 * Plugin Name: WooCommerce Box-items
 * Plugin URI: https://veggiefruit.com
 * Description: Add product box items.
 * Author: Vikas Sharma
 * Author URI: https://woocommerce.com
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce fallback notice.
 *
 * @since 1.5.25
 */
function woocommerce_box_items_missing_wc_notice() {
	/* translators: %s WC download URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Pre Orders requires WooCommerce to be installed and active. You can download %s here.', 'wc-pre-orders' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

if ( ! class_exists( 'WC_Pre_Orders' ) ) :
	/**
	 * Main Plugin Class
	 *
	 * @since 1.0
	 */
	class WC_Box_Items {
		/**
		 * The single instance of the class.
		 *
		 * @var $_instance
		 * @since 1.13.0
		 */
		protected static $_instance = null;

		/**
		 * Plugin file path without trailing slash
		 *
		 * @var string
		 */
		private $plugin_path;

		/**
		 * Plugin url without trailing slash
		 *
		 * @var string
		 */
		private $plugin_url;

		/**
		 * Plugin url without trailing slash
		 *
		 * @var string
		 */
		private $plugin_version = '1.0.0';

		/**
		 * Setup main plugin class
		 *
		 * @since  1.0
		 * @return \WC_Pre_Orders
		 */
		public function __construct() {

			// Set plugin url path
			$this->get_plugin_url();

			// load classes that require WC to be loaded
			add_action( 'woocommerce_init', array( $this, 'init' ) );
			add_action( 'wp_enqueue_scripts', array($this,'box_items_register_plugin_styles'));
			add_action( 'admin_enqueue_scripts', array( $this, 'box_items_setup_admin_scripts' ) );
		}

		/**
		* Register style sheet.
		*/
		public function box_items_register_plugin_styles() {

			wp_enqueue_script('box-items-js', $this->plugin_url.'/assets/js/box_item.js', array('jquery'), $this->plugin_version, true);
		    wp_localize_script('box-items-js', 'box_item_ajax',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		    wp_register_style( 'box-items-css', $this->plugin_url .'/assets/css/wc-box-items.css', array(), $this->plugin_version );	
		    wp_enqueue_style( 'box-items-css' );
		}	

		/**
		* Register style sheet.
		*/
		public function box_items_setup_admin_scripts() {
			wp_register_style( 'box-items-admin-css', $this->plugin_url .'/assets/css/wc-admin-box-items.css', array(), $this->plugin_version );	
		    wp_enqueue_style( 'box-items-admin-css' );

            wp_enqueue_script('box-items-admin-js', $this->plugin_url.'/assets/js/admin/box_item_admin.js', array('jquery'), $this->plugin_version, true);
		    wp_localize_script('box-items-admin-js', 'box_item_ajax',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}	

		/**
		 * Main Instance.
		 *
		 * Ensures only one instance is loaded or can be loaded.
		 *
		 * @since 1.5.25
		 * @return WC_Pre_Orders
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Load actions and filters that require WC to be loaded
		 *
		 * @since 1.0
		 */
		public function init() {

			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {

				// Load admin.
				require( 'includes/admin/class-wc-box-items-settings.php' );

			} else {

				// Load admin.
				require( 'includes/class-wc-box-items.php' );
			}
		}

		/**
		 * Returns the plugin's path without a trailing slash
		 *
		 * @since  1.0
		 *
		 * @return string the plugin path
		 */
		public function get_plugin_path() {
			if ( $this->plugin_path ) {
				return $this->plugin_path;
			}

			return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}


		/**
		 * Returns the plugin's url without a trailing slash
		 *
		 * @since  1.0
		 *
		 * @return string the plugin url
		 */
		public function get_plugin_url() {
			if ( $this->plugin_url ) {
				return $this->plugin_url;
			}

			return $this->plugin_url = plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) );
		}
	}
	
	endif;

add_action( 'plugins_loaded', 'woocommerce_box_items_init' );

/**
 * Initializes the extension.
 *
 * @since 1.5.25
 * @return Object Instance of the extension.
 */
function woocommerce_box_items_init() {

	load_plugin_textdomain( 'wc-box-items', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'woocommerce_box_items_missing_wc_notice' );
		return;
	}

	$GLOBALS['wc_box_items'] = new WC_Box_Items();
}