<?php
/**
 * WooCommerce Box-Items 
 * @package   WC_Box_Items
 * @author    Vikas Sharma
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Pre-Orders Admin class.
 */
class WC_Box_Items_View {

	/**
	 * Setup admin class.
	 */
	public function __construct() {

		// Add some extra hidden fields for weight calculation
		add_action( 'woocommerce_before_add_to_cart_button', array($this, 'box_items_hidden_fields'), 10);
	}
	
	public function box_items_hidden_fields() {
		
		global $product, $post;
		$post_id = $post->ID;

		if( $product->get_type() != 'simple' && $product->get_type() != 'simple_booking' ) {
			return;
		}

		$product_extra_groups = pewc_get_extra_fields($post_id);
		if( !empty($product_extra_groups) && sizeof($product_extra_groups) > 0 ) {
			foreach ($product_extra_groups as $key => $value) {
				if ( isset($value['items']) && !empty($value['items']) && sizeof($value['items']) > 0 ) {
					foreach ($value['items'] as $item_key => $item_value) { 
						if( isset($item_value['child_products']) && !empty($item_value['child_products']) && sizeof($item_value['child_products']) > 0 ) {

							// Get weight Unit
							$weight_unit = get_option('woocommerce_weight_unit' );

							// ##Add custome input fields
							echo '<input type="hidden" id="pewc_box_weight" class="pewc_box_weight" value="'.$product->get_weight().'">';
							echo '<input type="hidden" id="pewc_box_weight_unit" class="pewc_box_weight_unit" value="'.$weight_unit.'">';
							echo '<input type="hidden" id="pewc_group_id" class="pewc_group_id" value="'.$item_value['group_id'].'">';
							echo '<input type="hidden" id="pewc_field_id" class="pewc_field_id" value="'.$item_value['field_id'].'">';

							$child_products_total_weight = 0;

							foreach ($item_value['child_products'] as $child_products_id) {
								$product_obj = new WC_Product($child_products_id);
								$product_weight = ( $product_obj->get_weight() > 0 ) ? $product_obj->get_weight() : 0;

								echo '<input type="hidden" id="pewc_item_'.$child_products_id.'_weight" class="pewc_product_weight" value="'.$product_weight.'">';
								$child_products_total_weight = $child_products_total_weight + $product_weight;
							}	

							echo '<input type="hidden" id="pewc_child_product_ids" class="pewc_child_product_ids" value="'.implode(',', $item_value['child_products']).'">';
							echo '<input type="hidden" id="child_products_total_weight" class="child_products_total_weight" value="'.$child_products_total_weight.'">';
						}
					}
				}
			}
		}
	}

	
}

new WC_Box_Items_View();