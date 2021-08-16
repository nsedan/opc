<?php
/*
*   PRODUCT REQUEST FOR FIELD POPULATION
* 
*/

// Where $priority is 10, $accepted_args is 4.
add_filter( 'acf/fields/post_object/result', 'product_callback', 10, 4 );

// Callback function used in filter hook.
// Filter Hook: apply_filters('acf/fields/post_object/result', $title, $post, $field, $post_id);
function product_callback($title, $post, $field, $post_id) {
	if( $post->post_type == 'product' ) {
		//  Nice to use for degbugging the object. error_log( json_encode( $post ) );

		$product = wc_get_product( $post->ID );
        $title = $product->get_sku();
	}
    return $title;
}
 
add_action( 'wp_ajax_productRequest', 'productRequest' );
function productRequest() {
	// This function executes when the Ajax request is made.
	// Use this function to display message in debug.log: error_log();

	$id = $_POST['id'];
	$product = wc_get_product( $id );
	$res;
	$res['product'] = json_decode($product);

	// Makes easier to access metadata by expanding the response
	$post_meta = get_post_meta( $id );
	foreach( $post_meta as $key => $val ) {
		$val = get_metadata( 'post', $id, $key, true );
		$res['product_meta'][ $key ] = $val;
	}
	die( json_encode( $res ) ); 
}


/*
*	FIELD CHOICES FOR SUPPLIERS
*
*/

// LOAD SUPPLIERS CPT FILE
require_once plugin_dir_path( __FILE__ ) . 'suppliers.php';

add_filter('acf/prepare_field/name=print_supplier', 'print_supplier');
function print_supplier( $field ) {
	$value = $field['value'];
	$choices = $field['choices'];
	
	$suppliers = get_posts(array(
        'posts_per_page'	=> -1,
        'post_type'			=> 'suppliers'
    ));
	$suppliers_array;
	foreach ($suppliers as $supplier){
		// Suppliers query
		$supplier_id = $supplier->ID;
		$supplier_name = get_post_meta( $supplier_id, 'supplier_name', true );
		$supplier_type = get_post_meta( $supplier_id, 'supplier_type', true );

		$supplier_choice = $supplier_type.' - '.$supplier_name;
		// Push to array
		$suppliers_array[ $supplier_id ] = $supplier_choice;
	}

	$field['choices'] = $suppliers_array;
  
	// The current value of the field is required to be in the list!
	// Also avoids adding it as a duplicate
	if( !empty( $value ) && !array_key_exists($value, $field['choices']) ){
	  $field['choices'][ $value ] = $choices[ $value ];
	}	
	return $field;
}

add_filter('acf/prepare_field/name=delivery_address', 'delivery_address');
function delivery_address( $field ) {
	$value = $field['value'];
	$choices = $field['choices'];
  
	$suppliers = get_posts(array(
        'posts_per_page'	=> -1,
        'post_type'			=> 'suppliers'
    ));
	$suppliers_array;
	foreach ($suppliers as $supplier){
		// Suppliers query
		$supplier_id = $supplier->ID;
		$supplier_name = get_post_meta( $supplier_id, 'supplier_name', true );
		$supplier_type = get_post_meta( $supplier_id, 'supplier_type', true );

		$supplier_choice = $supplier_type.' - '.$supplier_name;
		
		// Push to array with ID as the key
		$suppliers_array[ $supplier_id ] = $supplier_choice;
	}

	$field['choices'] = $suppliers_array;
  
	// The current value of the field is required to be in the list!
	// Also avoids adding it as a duplicate
	if( !empty( $value ) && !array_key_exists($value, $field['choices']) ){
	  $field['choices'][ $value ] = $choices[ $value ];
	}	
	return $field;
}

// Ajax request to get suppliers addresses
add_action( 'wp_ajax_supplierRequest', 'supplierRequest' );
function supplierRequest() {

	global $wpdb;
	$SupplierName = $_POST['pSupplierName'];
	$results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = '$SupplierName'" );
	$supplier_id = $results[0]->post_id;

	$s_array = array();
	$s_1 = get_post_meta( $supplier_id, 'supplier_address_1', true );
	$s_2 = get_post_meta( $supplier_id, 'supplier_address_2', true );
	$s_3 = get_post_meta( $supplier_id, 'supplier_address_3', true );
	$s_4 = get_post_meta( $supplier_id, 'supplier_address_4', true );
	$s_postcode = get_post_meta( $supplier_id, 'supplier_postcode', true );
	
	array_push( $s_array, $s_1, $s_2, $s_3, $s_4, $s_postcode );

	die( json_encode( $s_array ) ); 
}

add_action( 'wp_ajax_deliveryRequest', 'deliveryRequest' );
function deliveryRequest() {

	global $wpdb;
	$SupplierName = $_POST['dSupplierName'];
	$results = $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = '$SupplierName'" );
	$supplier_id = $results[0]->post_id;

	$s_array = array();
	$s_1 = get_post_meta( $supplier_id, 'supplier_address_1', true );
	$s_2 = get_post_meta( $supplier_id, 'supplier_address_2', true );
	$s_3 = get_post_meta( $supplier_id, 'supplier_address_3', true );
	$s_4 = get_post_meta( $supplier_id, 'supplier_address_4', true );
	$s_postcode = get_post_meta( $supplier_id, 'supplier_postcode', true );
	
	array_push( $s_array, $s_1, $s_2, $s_3, $s_4, $s_postcode );

	die( json_encode( $s_array ) ); 
}