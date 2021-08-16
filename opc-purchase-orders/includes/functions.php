<?php
/*
*   ADMIN COLUMNS SORTERS AND FILTERS
*
*/
// Remove default months filter only from this custom post type
function po_remove_date_filter( $months ) {
    global $typenow;
    if ( $typenow == 'purchase_orders' ) {
        return array();
    }
    return $months;
}
add_filter('months_dropdown_results', 'po_remove_date_filter');

// Render this columns Purchase Orders -> All Purchase Orders
add_filter( 'manage_purchase_orders_posts_columns', 'smashing_purchase_orders_columns' );
function smashing_purchase_orders_columns( $columns ) {
	$columns = array(
		'cb' => $columns['cb'],
		'purchase_order' => __( 'Order No' ),
		'status'         => __( 'Status' ),
		'order_date'     => __( 'Order Date' ),
		'delivery_date'  => __( 'Delivery Date' ),
		'total'        	 => __( 'Total' ),
		'actions'        => __( 'Actions' ),
	);
	return $columns;
}
  
// Gets values to populate row
add_action( 'manage_purchase_orders_posts_custom_column', 'smashing_purchase_orders_column', 10, 2);
function smashing_purchase_orders_column( $column, $post_id ) {
	$purchase_order = get_post_meta( $post_id, 'purchase_order', true );
	$status = get_post_meta( $post_id, 'status', true );
	$get_order_date = get_post_meta( $post_id, 'order_date', true );
	$get_delivery_date = get_post_meta( $post_id, 'delivery_date', true );
	// Total sum for each PO
	$products = get_metadata( 'post', $post_id, 'products', true );
	$sum_cost = 0;
	for ($p = 0; $p < $products; ++$p){
		$product_total_cost = get_metadata( 'post', $post_id, 'products_'.$p.'_total_cost', true );
		$sum_cost += $product_total_cost;
	}

	
	if ( 'purchase_order' === $column ) {
		?><a href="post.php?post=<?php echo $post_id ?>&action=edit">
			<strong>&#35;<?php echo $purchase_order ?></strong>
		</a><?php
	}
	if ( 'status' === $column ) {
		echo $status;
	}
	if ( 'order_date' === $column ) {
		$order_date = date('F j, Y', strtotime($get_order_date));
		echo $order_date;
	}
	if ( 'delivery_date' === $column ) {
		$delivery_date = date('F j, Y', strtotime($get_delivery_date));
		echo $delivery_date;
	}
	if ( 'total' === $column ) {
		echo '£'.number_format ((float)$sum_cost, 2 , '.' , ',' );
	}
	if ( 'actions' === $column ) {
		?>
			<style>
				.c5-atag{font-size:1.6rem;color:#2271b1;}
				.c5-atag:hover{text-shadow:1px 1px #2271b18c;}
			</style>
		<?php
		
        // Render PDF button
        $href = plugin_dir_url(__DIR__) . 'mpdf/makepdf.php';
        $pdf_btn = '<a rel="nofollow" href="'.$href.'?'.$post_id.'" target="_blank"
            class="dashicons dashicons-pdf c5-atag"></a>';
        echo $pdf_btn;
        
        // Render email button
        $href_email = plugin_dir_url(__DIR__) . 'mpdf/makepdf_email.php';
        $email_pdf = '<a rel="nofollow" href="'.$href_email.'?'.$post_id.'" class="dashicons dashicons-email c5-atag"
            style="padding-left:10px"></a>';
        $has_receiver = get_metadata( 'post', $post_id, 'to_email', true );
        $has_body = get_metadata( 'post', $post_id, 'body_email', true );
        if( $has_receiver && $has_body ){ echo $email_pdf; }
	} 
}

// Allow filters to be applied to this fields
add_filter( 'manage_edit-purchase_orders_sortable_columns', 'smashing_purchase_orders_sortable_columns');
function smashing_purchase_orders_sortable_columns( $columns ) {
	$columns['purchase_order'] = 'purchase_order';
	$columns['status'] = 'status';
	$columns['order_date'] = 'order_date';
	$columns['delivery_date'] = 'delivery_date';
	return $columns;
}

// Sort in numerical or alphabetical order depending on the field
add_action( 'pre_get_posts', 'smashing_purchase_orders_orderby' );
function smashing_purchase_orders_orderby( $query ) {
	if( ! is_admin() || ! $query->is_main_query() ) { return; }

	if ( 'purchase_order' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'purchase_order' );
		$query->set( 'meta_type', 'numeric' );
	}
	if ( 'status' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'status' );
	}
	if ( 'order_date' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'order_date' );
	}
	if ( 'delivery_date' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'delivery_date' );
	}
}


/*
*	EXTENDS PRODUCT SEARCH ON REPEATER FIELD, ALLOWS TO SEARCH BY SKU
*
*/
add_filter('acf/fields/post_object/query/name=product_code', 'product_field_args', 10, 3);
function product_field_args( $args, $field, $post_id ) {
    if ($args['s'] == '') {
        return $args;
    }
    // check for posts using $args
	$result = new WP_Query($args);
	
    if ($result->found_posts == 0) {
        $args['meta_query'] = array(
            array(
                'key' => '_sku',
                'value' => $args['s'],
                'compare' => 'like'
            )
        );
        $args['posts_per_page'] = -1;
        $args['s'] = '';
    }
    
    return $args;
}

/*
*   ADD EMAIL SETTINGS OPTIONS SUBPAGE
*
*/
if( function_exists('acf_add_options_page') ) {
    acf_add_options_sub_page(array(
        'page_title'     => 'Email Settings',
        'menu_title'     => 'Email Settings',
        'parent_slug'    => 'edit.php?post_type=purchase_orders',
    ));
}

// Remove wysiwyg media button
function remove_media_button() {
    global $current_screen;
    if( 'purchase_orders' == $current_screen->post_type ) remove_action('media_buttons', 'media_buttons');
}
add_action('admin_head','remove_media_button');