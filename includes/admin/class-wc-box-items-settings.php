<?php
/**
 * WooCommerce Box-Items Admin
 *
 * @package   WC_Box_Items/Admin
 * @author    Vikas Sharma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Pre-Orders Admin class.
 */
class WC_Box_Items_Admin {

	/**
	 * Setup admin class.
	 */
	public function __construct() {

		// Add a custom product tab.
		// add_filter( 'woocommerce_product_data_tabs', array($this, 'add_box_item_product_tabs') );	

		// Contents of the box item tab.
		// add_filter( 'woocommerce_product_data_panels', array($this, 'box_items_data_product_tab_content') );

		// Save box items
		// add_action( 'woocommerce_process_product_meta_simple', array($this, 'save_box_items_options_fields') );	
		// add_action( 'woocommerce_process_product_meta_variable', array($this, 'save_box_items_options_fields') );	

		// add_filter( 'product_type_options', array($this, 'box_item_product_type_option') );
	}

	public function add_box_item_product_tabs( $tabs) {

	    $tabs['box_items'] = array(
	        'label'     => __( 'Add Box Items', 'woocommerce' ),
	        'target'    => 'box_items_data',
	        'class'     => array( 'show_if_simple', 'hide_if_variable', 'show_if_box_type' ),
	    );

	    return $tabs;
    }

    public function box_items_data_product_tab_content() {

	    global $post, $wpdb, $product_object; ?>
	    
	    <div id="box_items_data" class="panel woocommerce_options_panel hidden">
	        <div class="options_group">
	            <p class="form-field">
	                <label for="box_items_ids"><?php esc_html_e( 'Select Box items', 'woocommerce' ); ?></label>
	                <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="box_items_ids" name="box_items_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
	                    <?php

	                    // Get saved box items ids
	                    $product_ids = get_post_meta($product_object->get_id(), 'box_items_ids', true);

	                    foreach ( $product_ids as $product_id ) {
	                        $product = wc_get_product( $product_id );
	                        if ( is_object( $product ) ) {
	                            echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
	                        }
	                    }
	                    ?>
	                </select> <?php echo wc_help_tip( __( 'Box are products which you recommend for adding perticuler products into box.', 'woocommerce' ) ); // WPCS: XSS ok. ?>
	            </p>
	        </div>
	    </div><?php
	}

	public function save_box_items_options_fields( $post_id ) {    
	    if ( isset( $_POST['box_items_ids'] ) && sizeof($_POST['box_items_ids']) > 0 ) {
	        update_post_meta( $post_id, 'box_items_ids', $_POST['box_items_ids'] );
	    }

	    $box_type = isset( $_POST['_box'] ) ? 'yes' : 'no';
	    update_post_meta( $post_id, '_box', $box_type );
	}

	public function box_item_product_type_option($product_type_options){

		global $post, $wpdb, $product_object;

		$box_type = get_post_meta($product_object->get_id(), '_box', true);
		$default_value = ($box_type) ? $box_type : 'no';

	    $product_type_options["_box_type"] = [
	        "id"            => "_box",
	        "wrapper_class" => "show_if_simple ",
	        "label"         => "Box",
	        "description"   => "Check if this product will treat as a Box!",
          	'default'       => $default_value
	    ];

	    return $product_type_options;
	}
}

new WC_Box_Items_Admin();