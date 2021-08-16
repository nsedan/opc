<?php
/*
*   EXPORT YESTERDAYS ORDERS TO CSV FILE VIA CRONJOB
* 
*/
require_once( dirname(__FILE__, 5) . "/wp-load.php");

global $wpdb;
$orders_query = 'SELECT * FROM wp_wc_order_stats 
                    WHERE date_created BETWEEN CURDATE() - INTERVAL 1 DAY 
                        AND CURDATE() - INTERVAL 1 SECOND;';
$order_array = $wpdb->get_results($orders_query);

if ($order_array) {

    $file = fopen( plugin_dir_path(__DIR__) . 'csv-exports/sorders.csv', 'w' );
    $field_name = [ 'Rec', 'Ref', 'AC', 'Date', 'YourRef', 'StkCode', 'SaleNL', 'Qty', 'NetValue' ];

    fputcsv($file, $field_name);

    foreach ($order_array as $order){

        $rec = 'RL';
        $post_id = $order->order_id;

        $user_id = get_post_meta( $post_id, '_customer_user', true );
        $user_acc = $user_id != 0 ? get_userdata( $user_id )->nickname  : '';

        $order_number = get_post_meta( $post_id, '_order_number', true );

        $order_date = get_the_date( 'd/m/Y', $post_id );

        $products_query = 'SELECT * FROM wp_wc_order_product_lookup WHERE order_id='.$post_id.'';
        $order_products = $wpdb->get_results($products_query);
        
        foreach ($order_products as $product){
            $product_id = $product->product_id;
            $product_qty = $product->product_qty;
            $sku = get_post_meta( $product_id, '_sku', true );
            $total = $product->product_net_revenue;
            $unit_price = $total / $product_qty;
            $sales_nl = get_post_meta( $product_id, 'sales_nl', true );

            $field_data = [ $rec, $order_number, $user_acc, $order_date, $order_number, $sku, $sales_nl, $product_qty, $unit_price ];

            fputcsv($file, $field_data);
        }
    }
    exit();
}

