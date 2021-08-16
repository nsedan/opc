<?php
/*
*   EXPORT ALL ORDERS TO CSV FILE VIA CRONJOB
* 
*/
require_once( dirname(__FILE__, 5) . "/wp-load.php");

$yesterday = strtotime("yesterday");
$yesterday = date("Y-m-d", $yesterday);
$yesterday = explode('-',$yesterday);
$args = array(
    'post_type'      => 'purchase_orders',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'year' => $yesterday[0],
    'monthnum' => $yesterday[1],
    'day' => $yesterday[2],
);
global $post;
$arr_post = get_posts($args);


if (!empty($arr_post)) {

    $file = fopen( plugin_dir_path(__DIR__) . 'csv-exports/porders.csv', 'w' );
    $field_name = [ 'Rec', 'Ref', 'AC', 'Date', 'YourRef', 'StkCode', 'Loc', 'Qty', 'NetValue' ];

    fputcsv($file, $field_name);

    foreach ($arr_post as $post) {

        $post_id = get_the_ID();
        
        $rec = 'RL';
        $loc = 'WH1';
        $purchase_order = get_post_meta( $post_id, 'purchase_order', true );
        $date = get_post_meta( $post_id, 'order_date', true );
        $post_date = date('d/m/Y', strtotime($date));
        $supplier_id = get_post_meta( $post_id, 'print_supplier', true );
        $supplier_account = get_post_meta( $supplier_id, 'supplier_account', true );
        $products = get_post_meta( $post_id, 'products', true );
        
        for ($p = 0; $p < $products; ++$p){

            $product_id = get_post_meta( $post_id, 'products_'.$p.'_product_code', true );
            $sku = get_post_meta( $product_id, '_sku', true );

            $product_qty = get_post_meta( $post_id, 'products_'.$p.'_quantity_requested', true );

            $product_unit_cost = get_post_meta( $post_id, 'products_'.$p.'_unit_cost', true );


            $skus = $sku ? $sku : '';
            $qties = $product_qty ? $product_qty : '';
            $units_cost = $product_unit_cost ? $product_unit_cost : '';

            $field_data = [ $rec , $purchase_order, $supplier_account, $post_date, $purchase_order, $skus, $loc, $qties, $units_cost ];

            fputcsv($file, $field_data);
        }
    }
    exit();
}