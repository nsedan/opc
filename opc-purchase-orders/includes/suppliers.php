<?php
/*
*    CSS AND JS FILES
* 
*/
function suppliers_scripts() {
	wp_enqueue_script( 'suppliers', plugin_dir_url( __DIR__ ) . 'static/js/suppliers.js', array( 'acf-input' ), '1.0', false  );
}
add_action( 'admin_enqueue_scripts', 'suppliers_scripts' );


/*
*    REGISTER CUSTOM POST TYPE
*
*/
add_action( 'init', 'suppliers_post_type', 0 );
function suppliers_post_type() {

	$labels = array(
		'name'                  => _x( 'Suppliers', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Supplier', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Suppliers', 'text_domain' ),
		'name_admin_bar'        => __( 'Supplier', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Supplier:', 'text_domain' ),
		'all_items'             => __( 'All Suppliers', 'text_domain' ),
		'add_new_item'          => __( 'Add New Supplier', 'text_domain' ),
		'add_new'               => __( 'New Supplier', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Supplier', 'text_domain' ),
		'update_item'           => __( 'Update Supplier', 'text_domain' ),
		'view_item'             => __( 'View Supplier', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search suppliers', 'text_domain' ),
		'not_found'             => __( 'No suppliers found', 'text_domain' ),
		'not_found_in_trash'    => __( 'No suppliers found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(

		'label'                 => __( 'Supplier', 'text_domain' ),
		'description'           => __( 'Supplier information pages.', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'custom-fields' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_rest'          => true,
		'menu_position'         => 58,
		'menu_icon'             => 'dashicons-groups',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
    );
    
	register_post_type( 'suppliers', $args );

}


/*
*   ADMIN COLUMNS SORTERS AND FILTERS
*
*/
// Remove default months filter only from this custom post type
function sup_remove_date_filter( $months ) {
    global $typenow;
    if ( $typenow == 'suppliers' ) {
        return array();
    }
    return $months;
}
add_filter('months_dropdown_results', 'sup_remove_date_filter');

// Render this columns Suppliers -> All Suppliers
add_filter( 'manage_suppliers_posts_columns', 'smashing_suppliers_columns' );
function smashing_suppliers_columns( $columns ) {
    $columns = array(
        'cb' => $columns['cb'],
        'supplier_name'     => __( 'Company' ),
        'supplier_type'     => __( 'Type' ),
        'supplier_email'    => __( 'Email' ),
        'supplier_account'  => __( 'Code' ),
    );
    return $columns;
}

// Gets values to populate row
add_action( 'manage_suppliers_posts_custom_column', 'smashing_suppliers_column', 10, 2);
function smashing_suppliers_column( $column, $post_id ) {
    $supplier_name = get_post_meta( $post_id, 'supplier_name', true );
    $supplier_type = get_post_meta( $post_id, 'supplier_type', true );
    $supplier_email = get_post_meta( $post_id, 'supplier_email', true );
    $supplier_account = get_post_meta( $post_id, 'supplier_account', true );
    
    if ( 'supplier_name' === $column ) {
        ?><a href="post.php?post=<?php echo $post_id ?>&action=edit">
			<strong><?php echo $supplier_name ?></strong>
		</a><?php
    }
    if ( 'supplier_type' === $column ) {
		echo $supplier_type;
    }
    if ( 'supplier_email' === $column ) {
		echo $supplier_email;
    }
    if ( 'supplier_account' === $column ) {
		echo $supplier_account;
	}
}

// Allow filters to be applied to this fields
add_filter( 'manage_edit-suppliers_sortable_columns', 'smashing_suppliers_sortable_columns');
function smashing_suppliers_sortable_columns( $columns ) {
	$columns['supplier_name'] = 'supplier_name';
	$columns['supplier_type'] = 'supplier_type';
	return $columns;
}

// Sort in numerical or alphabetical order depending on the field
add_action( 'pre_get_posts', 'smashing_suppliers_orderby' );
function smashing_suppliers_orderby( $query ) {
	if( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'supplier_name' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'supplier_name' );
	}
	if ( 'supplier_type' === $query->get( 'orderby') ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'supplier_type' );
	}
}


/*
*   AFC FIELDS CREATION
* 
*/
if( function_exists('acf_add_local_field_group') ):
	acf_add_local_field_group(array(
		'key' => 'group_5ff833ce0afa7',
		'title' => 'Suppliers',
		'fields' => array(
			array(
				'key' => 'field_5ff833dd27ddb',
				'label' => 'Supplier Name',
				'name' => 'supplier_name',
				'type' => 'text',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
				),
			),
			array(
				'key' => 'field_5ff8340327ddc',
				'label' => 'Email',
				'name' => 'supplier_email',
				'type' => 'email',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
				),
			),
			array(
				'key' => 'field_5ff8343b27ddd',
				'label' => 'Account No',
				'name' => 'supplier_account',
				'type' => 'text',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
				),
			),
			array(
				'key' => 'field_5ff8348227dde',
				'label' => 'Type',
				'name' => 'supplier_type',
				'type' => 'select',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '25',
				),
				'choices' => array(
					'Printer' => 'Printer',
					'Stationery Supplier' => 'Stationery Supplier',
					'Sundry Supplier' => 'Sundry Supplier',
					'Image Supplier' => 'Image Supplier',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
			),
			array(
				'key' => 'field_5ff8360b6f4d2',
				'label' => 'Address 1',
				'name' => 'supplier_address_1',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
				),
			),
			array(
				'key' => 'field_5ff836486f4d3',
				'label' => 'County',
				'name' => 'supplier_address_3',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
				),
			),
			array(
				'key' => 'field_5ff83878b9166',
				'label' => 'Address 2',
				'name' => 'supplier_address_2',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
				),
			),
			array(
				'key' => 'field_5ff8365e10e25',
				'label' => 'City',
				'name' => 'supplier_address_4',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
				),
			),
			array(
				'key' => 'field_5ff837db96aaa',
				'label' => 'Postcode',
				'name' => 'supplier_postcode',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33.33',
				),
			),
			array(
				'key' => 'field_5ff8368810e26',
				'label' => 'Country',
				'name' => 'supplier_country',
				'type' => 'select',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33.33',
				),
				'choices' => array(
					'Australia' => 'Australia',
					'Benelux' => 'Benelux',
					'Brazil' => 'Brazil',
					'Canada' => 'Canada',
					'Chile' => 'Chile',
					'Eire' => 'Eire',
					'France' => 'France',
					'Germany' => 'Germany',
					'Italy' => 'Italy',
					'Mexico' => 'Mexico',
					'Portugal' => 'Portugal',
					'South Africa' => 'South Africa',
					'Spain' => 'Spain',
					'United Kingdom' => 'United Kingdom',
					'USA' => 'USA',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
			),
			array(
				'key' => 'field_5ff83968b9167',
				'label' => 'Address Type',
				'name' => 'supplier_address_type',
				'type' => 'select',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33.33',
				),
				'choices' => array(
					'Billing' => 'Billing',
					'Delivery' => 'Delivery',
					'Home' => 'Home',
					'Trading' => 'Trading',
				),
				'default_value' => false,
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
			),
			array(
				'key' => 'field_5ff83a9252d42',
				'label' => 'Telephone',
				'name' => 'supplier_telephone',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33.33',
				),
			),
			array(
				'key' => 'field_5ff83ab452d43',
				'label' => 'Fax',
				'name' => 'supplier_fax',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33.33',
				),
			),
			array(
				'key' => 'field_5ff83ac652d44',
				'label' => 'Mobile',
				'name' => 'supplier_mobile',
				'type' => 'text',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '33.33',
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'suppliers',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
endif;