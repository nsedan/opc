<?php
/*
*   EXPORT ALL ORDERS TO CSV FILE
*
*/
function admin_add_so_export_button($which)
{
    global $typenow;
    if ('shop_order' === $typenow && 'top' === $which) {
?>
        <input type="submit" name="export_all_so" class="button button-primary" value="<?php _e('Export All'); ?>" />
<?php
    }
}
add_action('manage_posts_extra_tablenav', 'admin_add_so_export_button', 20, 1);


function func_export_all_so()
{
    if (isset($_GET['export_all_so'])) {
        global $wpdb;
        $orders_query = 'SELECT * FROM wp_wc_order_stats';
        $order_array = $wpdb->get_results($orders_query);

        if ($order_array) {

            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="sale-orders-export-' . date('d-m-y') . time() . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $file = fopen('php://output', 'w');
            $field_name = ['OrderNo', 'Customer', 'Account', 'Date', 'SKU', 'Description', 'Quantity', 'UnitPrice', 'Total'];

            fputcsv($file, $field_name);

            foreach ($order_array as $order) {
                $post_id = $order->order_id;

                $user_id = get_post_meta($post_id, '_customer_user', true);
                $user_name = $user_id != 0 ? get_userdata($user_id)->first_name . ' ' . get_userdata($user_id)->last_name : '';
                $user_acc = $user_id != 0 ? get_userdata($user_id)->nickname  : '';

                $order_number = get_post_meta($post_id, '_order_number', true);

                $order_date = get_the_date('d/m/Y', $post_id);

                $products_query = 'SELECT * FROM wp_wc_order_product_lookup WHERE order_id=' . $post_id . '';
                $order_products = $wpdb->get_results($products_query);

                foreach ($order_products as $product) {
                    $product_id = $product->product_id;
                    $product_qty = $product->product_qty;
                    $sku = get_post_meta($product_id, '_sku', true);
                    $product_name = get_the_title($product_id);
                    $total = $product->product_net_revenue;
                    $unit_price = $total / $product_qty;

                    $field_data = [$order_number, $user_name, $user_acc, $order_date, $sku, $product_name, $product_qty, $unit_price, $total];

                    fputcsv($file, $field_data);
                }
            }
            exit();
        }
    }
}
add_action('init', 'func_export_all_so');
