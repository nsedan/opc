<?php

/**
 *
 * @link              
 * @since             1.0.0
 * @package           Purchase_Orders
 *
 * @wordpress-plugin
 * Plugin Name:       Purchase Orders - OPC
 * Plugin URI:        
 * Description:       Create purchase orders that can be sent to suppliers.
 * Version:           1.0.0
 * Author:            C5 Digitals
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       purchase-orders
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

/**
* CURRENT PLUGIN VERSION
*/
define( 'PURCHASE_ORDERS_VERSION', '1.0.0' );


/*
*   LOAD DEPENDENCIES
*
*/
require_once plugin_dir_path( __FILE__ ) . 'advanced-custom-fields-pro-master/acf.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/csv.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/fields.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/ajax.php';

/*
*    CSS AND JS FILES
* 
*/
function purchase_order_scripts() {
	wp_enqueue_script( 'purchase-orders', plugin_dir_url( __FILE__ ) . 'static/js/purchase-orders.js', array( 'acf-input' ), '1.0', false );
	wp_enqueue_style( 'purchase-orders', plugin_dir_url( __FILE__ ) . 'static/css/purchase-orders.css', array(), '1.0', 'all' );
}
add_action( 'admin_enqueue_scripts', 'purchase_order_scripts' );


/*
*    REGISTER CUSTOM POST TYPE
*
*/
add_action( 'init', 'purchase_orders', 0 );
function purchase_orders() {

    $labels = array(
        'name'                  => _x( 'Purchase Orders', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Purchase Order', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Purchase Orders', 'text_domain' ),
        'name_admin_bar'        => __( 'Purchase Order', 'text_domain' ),
        'archives'              => __( 'Item Archives', 'text_domain' ),
        'attributes'            => __( 'Item Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Purchase Order:', 'text_domain' ),
        'all_items'             => __( 'All Purchase Orders', 'text_domain' ),
        'add_new_item'          => __( 'Add New Purchase Order', 'text_domain' ),
        'add_new'               => __( 'New Purchase Order', 'text_domain' ),
        'new_item'              => __( 'New Item', 'text_domain' ),
        'edit_item'             => __( 'Edit Purchase Order', 'text_domain' ),
        'update_item'           => __( 'Update Purchase Order', 'text_domain' ),
        'view_item'             => __( 'View Purchase Order', 'text_domain' ),
        'view_items'            => __( 'View Items', 'text_domain' ),
        'search_items'          => __( 'Search PO', 'text_domain' ),
        'not_found'             => __( 'No products found', 'text_domain' ),
        'not_found_in_trash'    => __( 'No products found in Trash', 'text_domain' ),
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
        
        'label'                 => __( 'Purchase Order', 'text_domain' ),
        'description'           => __( 'Purchase Order information pages.', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_rest'          => true,
        'menu_position'         => 57,
        'menu_icon'             => 'dashicons-image-filter',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );

    register_post_type( 'purchase_orders', $args );

}
