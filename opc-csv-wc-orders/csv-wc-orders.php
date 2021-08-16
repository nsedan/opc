<?php

/**
 *
 * @link              
 * @since             1.0.0
 * @package           CSV-Woocommerce-Orders
 *
 * @wordpress-plugin
 * Plugin Name:       CSV WooCommerce Orders - OPC
 * Plugin URI:        
 * Description:       Create CSV files from WooCommerce sales orders.
 * Version:           1.0.0
 * Author:            C5 Digitals
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       text-domain
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 */
define('CSV_WOOCOMMERCE_VERSION', '1.0.0');


/*
*   WOOCOMMERCE PRODUCT DATA TAB EXPANDED TO INCLUDE EXCHEQUER META FIELDS
*
*/
add_filter('woocommerce_product_data_tabs', 'add_exchequer_product_data_tab', 99, 1);
function add_exchequer_product_data_tab($product_data_tabs)
{
    $product_data_tabs['exchequer'] = array(
        'label' => __('Integration', 'text_domain'),
        'target' => 'exchequer_product_data',
    );
    return $product_data_tabs;
}
add_action('woocommerce_product_data_panels', 'add_exchequer_product_data_fields');
function add_exchequer_product_data_fields()
{
    global $woocommerce, $post;
?>
    <style>
        #woocommerce-coupon-data ul.wc-tabs li.exchequer_options a::before,
        #woocommerce-product-data ul.wc-tabs li.exchequer_options a::before,
        .woocommerce ul.wc-tabs li.exchequer_options a::before {
            font-family: Dashicons;
            content: "\f237";
        }
    </style>
    <div id="exchequer_product_data" class="panel woocommerce_options_panel">
        <?php
        woocommerce_wp_text_input(array(
            'id'            => 'sales_nl',
            'wrapper_class' => 'show_if_simple',
            'label'         => __('SALES NL', 'text_domain'),
        ));
        woocommerce_wp_text_input(array(
            'id'            => 'cost_nl',
            'wrapper_class' => 'show_if_simple',
            'label'         => __('COST NL', 'text_domain'),
        ));
        woocommerce_wp_text_input(array(
            'id'            => 'clos_nl',
            'wrapper_class' => 'show_if_simple',
            'label'         => __('CLOSING ST/WO NL', 'text_domain'),
        ));
        woocommerce_wp_text_input(array(
            'id'            => 'stk_val_nl',
            'wrapper_class' => 'show_if_simple',
            'label'         => __('STOCK VAL NL', 'text_domain'),
        ));
        woocommerce_wp_text_input(array(
            'id'            => 'bom_nl',
            'wrapper_class' => 'show_if_simple',
            'label'         => __('BOM NL', 'text_domain'),
        ));
        ?>
    </div>
    <?php
}
add_action('woocommerce_process_product_meta', 'woocommerce_exchequer_product_meta_fields_save');
function woocommerce_exchequer_product_meta_fields_save($post_id)
{
    $sales_nl = isset($_POST['sales_nl']) ? $_POST['sales_nl'] : '';
    $cost_nl = isset($_POST['cost_nl']) ? $_POST['cost_nl'] : '';
    $clos_nl = isset($_POST['clos_nl']) ? $_POST['clos_nl'] : '';
    $stk_val_nl = isset($_POST['stk_val_nl']) ? $_POST['stk_val_nl'] : '';
    $bom_nl = isset($_POST['bom_nl']) ? $_POST['bom_nl'] : '';

    update_post_meta($post_id, 'sales_nl', $sales_nl);
    update_post_meta($post_id, 'cost_nl', $cost_nl);
    update_post_meta($post_id, 'clos_nl', $clos_nl);
    update_post_meta($post_id, 'stk_val_nl', $stk_val_nl);
    update_post_meta($post_id, 'bom_nl', $bom_nl);
}



/*
*   EXPORT ORDER TO A SEPARETE FILE READY TO IMPORT TO EXCHEQUER
*   CHANGES THE POST STATUS TO APPROVED
*
*/

add_filter('manage_edit-shop_order_columns', 'c5_add_exchequer_order_admin_list_column');
function c5_add_exchequer_order_admin_list_column($columns)
{
    $columns['exchequer'] = 'Exchequer';
    $columns['country'] = 'Billing Country';
    return $columns;
}

add_action('manage_shop_order_posts_custom_column', 'c5_add_exchequer_order_admin_list_column_content');
function c5_add_exchequer_order_admin_list_column_content($column)
{

    global $post;
    $order = wc_get_order($post->ID);
    $order_status  = $order->get_status();

    if ('country' === $column) {
        $user_id = get_post_meta($post->ID, '_customer_user', true);
        $billing_country = get_user_meta($user_id, 'billing_country', true);
        echo $billing_country;
    }

    if ('exchequer' === $column) {
        if ($order_status === 'processing') {
    ?>
            <button id="button-exchequer-<?= $post->ID ?>" class="c5-button">
                <span class="dashicons dashicons-update"></span>
            </button>
            <input id="send-to-exchequer-<?= $post->ID ?>" class="hidden" type="submit" name="export_order" value="<?= $post->ID ?>" />
    <?php
        } elseif ($order_status === 'completed' || $order_status === 'approved') {
            echo '<span class="dashicons dashicons-yes" style="color:#2271b1;"></span>';
        } else {
            echo '<span class="dashicons dashicons-no-alt" style="color:#2271b1;"></span>';
        }
    }

    ?>
    <style>
        .c5-button {
            color: #2271b1;
            border: 0;
            padding: 0;
            background: inherit;
            cursor: pointer;
        }

        .c5-button:hover {
            text-shadow: 1px 1px #2271b18c;
        }

        .c5-button:focus {
            outline: none;
        }

        .order-status.status-approved {
            background: #9bd2f7;
            color: #2e4453;
        }
    </style>
    <script>
        jQuery('#button-exchequer-<?= $post->ID ?>').on('click', function(e) {
            e.preventDefault();
            jQuery('#send-to-exchequer-<?= $post->ID ?>').click()
            jQuery('.wc-action-button-complete').closest().click()
            jQuery('#button-exchequer-<?= $post->ID ?>').css({
                'animation-name': 'spin',
                'animation-duration': '2s',
                'animation-iteration-count': 'infinite',
                'animation-timing-function': 'linear'
            })
        })
    </script>
<?php
}

// Creates CSV file for the chosen order
add_action('init', 'func_export_order');
function func_export_order()
{
    if (isset($_GET['export_order'])) {
        global $wpdb;
        $orders_query = 'SELECT * FROM wp_wc_order_stats WHERE order_id=' . $_GET['export_order'] . '';
        $order_array = $wpdb->get_results($orders_query);

        if ($order_array) {

            $file = fopen(plugin_dir_path(__FILE__) . 'csv-exports/sale-orders-export-' . $_GET['export_order'] . '.csv', 'w');
            $field_name = ['Rec', 'Ref', 'AC', 'Date', 'DueDate', 'YourRef', 'Currency', 'StkCode', 'VAT', 'Description', 'SaleNL', 'Qty', 'NetValue'];

            fputcsv($file, $field_name);

            foreach ($order_array as $order) {
                $rec = 'RL';
                $post_id = $order->order_id;

                // Change order status to approved
                $wpdb->query(
                    $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'wc-approved' WHERE ID = %d", $post_id)
                );

                $user_id = get_post_meta($post_id, '_customer_user', true);
                $user_acc = $user_id != 0 ? get_userdata($user_id)->nickname  : '';

                $order_number = get_post_meta($post_id, '_order_number', true);

                $order_date = get_the_date('d/m/Y', $post_id);
                $delivery_date = get_post_meta($post_id, 'delivery_date', true);
                $delivery_date = $delivery_date ? date("d/m/Y", strtotime($delivery_date)) : $order_date;

                $products_query = 'SELECT * FROM wp_wc_order_product_lookup WHERE order_id=' . $post_id . '';
                $order_products = $wpdb->get_results($products_query);

                $order_currency = get_post_meta($post_id, '_order_currency', true);
                if ($order_currency === 'EUR') {
                    $order_currency = '2';
                } elseif ($order_currency === 'USD') {
                    $order_currency = '3';
                } elseif ($order_currency === 'DEK') {
                    $order_currency = '4';
                } else {
                    $order_currency = '1';
                }

                foreach ($order_products as $product) {
                    $product_id = $product->product_id;
                    $pack_qty = get_post_meta($product_id, 'per_pack', true);
                    $pack_qty = $pack_qty ? $pack_qty : 1;
                    $product_qty = $product->product_qty * $pack_qty;
                    $sku = get_post_meta($product_id, '_sku', true);
                    $product_name = (get_post($product_id))->post_title;
                    $total = $product->product_net_revenue;
                    $unit_price = $total / $product_qty;
                    $sales_nl = get_post_meta($product_id, 'sales_nl', true);

                    $billing_country = get_user_meta($user_id, 'billing_country', true);
                    $vat = '4';
                    if ($billing_country == 'UKEN' || $billing_country == 'UKSL' || $billing_country == 'UKWS' || $billing_country == 'UKNI') {
                        $vat = 'S';
                    }


                    $field_data = [$rec, $order_number, $user_acc, $order_date, $delivery_date, $order_number, $order_currency, $sku, $vat, $product_name, $sales_nl, $product_qty, $unit_price];

                    fputcsv($file, $field_data);
                }
                // Gets customer notes and print them in a new row.
                $order = wc_get_order($post_id);
                $note = $order->get_customer_note();
                $note = trim(preg_replace('/\s+/', ' ', $note));
                $field_data = [$rec, $order_number, $user_acc, $order_date, $delivery_date, $order_number, $order_currency, '', $vat, $note, '', '', ''];
                if (!empty($note)) {
                    fputcsv($file, $field_data);
                }

                $current_user = get_userdata(get_current_user_id())->display_name;
                $order->add_order_note("Order Approved by {$current_user}");
            }
            // Redirect to orders page
            wp_redirect(admin_url('/edit.php?post_type=shop_order'));
            exit();
        }
    }
}

// Disable row onClick event on the orders table
function add_no_link_to_post_class($classes)
{
    if (is_admin()) {
        $current_screen = get_current_screen();
        if ($current_screen->base == 'edit' && $current_screen->post_type == 'shop_order') $classes[] = 'no-link';
    }
    return $classes;
}
add_filter('post_class', 'add_no_link_to_post_class');


// Add Approved post status for orders
function c5_wc_register_post_statuses()
{
    register_post_status(
        'wc-approved',
        array(
            'label' => _x('Approved', 'WooCommerce Order status', 'text_domain'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Approved (%s)', 'Approved (%s)', 'text_domain')
        )
    );
}
add_filter('init', 'c5_wc_register_post_statuses');

function c5_wc_add_order_statuses($order_statuses)
{
    $order_statuses['wc-approved'] = _x('Approved', 'WooCommerce Order status', 'text_domain');
    return $order_statuses;
}
add_filter('wc_order_statuses', 'c5_wc_add_order_statuses');

// Change Nickname field label from User form
function c5_user_nickname_gettext($translation, $original)
{
    if ('Nickname' == $original) {
        return 'Account Code';
    }
    return $translation;
}
add_filter('gettext', 'c5_user_nickname_gettext', 10, 2);
