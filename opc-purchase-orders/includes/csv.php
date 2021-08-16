<?php
/*
*   EXPORT ALL ORDERS TO CSV FILE
* 
*/
function admin_add_po_export_button( $which ) {
    global $typenow;
    if ( 'purchase_orders' === $typenow && 'top' === $which ) {
        ?>
        <input type="submit" name="export_all_po" class="button button-primary" value="<?php _e('Export All'); ?>" />
        <?php
    }
}
add_action( 'manage_posts_extra_tablenav', 'admin_add_po_export_button', 20, 1 );


function func_export_all_po() {
    if(isset($_GET['export_all_po'])) {
        $args = array(
            'post_type'      => 'purchase_orders',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        global $post;
        $arr_post = get_posts($args);
        if ($arr_post) {
  
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="purchase-orders-export-'.date('d-m-y').time().'.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
  
            $file = fopen('php://output', 'w');
            $field_name = [ 'OrderNo', 'Supplier', 'Account', 'Date', 'SKU', 'Description', 'Quantity', 'UnitCost', 'Totals' ];

            fputcsv($file, $field_name);
  
            foreach ($arr_post as $post) {
                setup_postdata($post);
                $post_id = get_the_ID();

                $purchase_order = get_post_meta( $post_id, 'purchase_order', true );
                $date = get_post_meta( $post_id, 'order_date', true );
                $post_date = date('d/m/Y', strtotime($date));
                $supplier_id = get_post_meta( $post_id, 'print_supplier', true );
                $supplier_account = get_post_meta( $supplier_id, 'supplier_account', true );
                $supplier_name = get_post_meta( $supplier_id, 'supplier_name', true );
                $products = get_post_meta( $post_id, 'products', true );

                for ($p = 0; $p < $products; ++$p){

                    $product_id = get_post_meta( $post_id, 'products_'.$p.'_product_code', true );
                    $sku = get_post_meta( $product_id, '_sku', true );
                    $product_name = get_post_meta( $post_id, 'products_'.$p.'_name', true );
                    $product_qty = get_post_meta( $post_id, 'products_'.$p.'_quantity_requested', true );
                    $product_unit_cost = get_post_meta( $post_id, 'products_'.$p.'_unit_cost', true );
                    $product_total = get_post_meta( $post_id, 'products_'.$p.'_total_cost', true );
        
        
                    $skus = $sku ? $sku : '';
                    $names = $product_name ? $product_name : '';
                    $qties = $product_qty ? $product_qty : '';
                    $units_cost = $product_unit_cost ? $product_unit_cost : '';
                    $totals = $product_total ? $product_total : '';


                    $field_data = [ $purchase_order, $supplier_name, $supplier_account, $post_date, $skus, $names, $qties, $units_cost, $totals ];
    
                    fputcsv($file, $field_data);
                }
            }
            exit();
        }
    }
}
add_action( 'init', 'func_export_all_po' );




/*
*   EXPORT SELECTED ORDERS TO CSV FILE
* 
*/
//Hooks
function po_bulk_hooks() {
    if( current_user_can( 'administrator' ) ) {
      add_filter( 'bulk_actions-edit-purchase_orders', 'register_po_bulk_actions' );
      add_filter( 'handle_bulk_actions-edit-purchase_orders', 'po_bulk_action_handler', 10, 3 );
    }
}
add_action( 'current_screen', 'po_bulk_hooks' );

//Register
function register_po_bulk_actions($bulk_actions) {
  $bulk_actions['export_selected'] = __( 'Export', 'text_domain');
  return $bulk_actions;
}

//Handle 
function po_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
    if ( $doaction !== 'export_selected' ) { return $redirect_to; }

    if ($post_ids) {
  
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="purchase-orders-export-'.date('d-m-y').time().'.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $file = fopen('php://output', 'w');
        $field_name = [ 'OrderNo', 'Supplier', 'Account', 'Date', 'SKU', 'Description', 'Quantity', 'UnitCost', 'Totals' ];

        fputcsv($file, $field_name);

        foreach ($post_ids as $post_id) {
            setup_postdata($post_id);

            $purchase_order = get_post_meta( $post_id, 'purchase_order', true );
            $date = get_post_meta( $post_id, 'order_date', true );
            $post_date = date('d/m/Y', strtotime($date));
            $supplier_id = get_post_meta( $post_id, 'print_supplier', true );
            $supplier_account = get_post_meta( $supplier_id, 'supplier_account', true );
            $supplier_name = get_post_meta( $supplier_id, 'supplier_name', true );
            $products = get_post_meta( $post_id, 'products', true );

            for ($p = 0; $p < $products; ++$p){

                $product_id = get_post_meta( $post_id, 'products_'.$p.'_product_code', true );
                $sku = get_post_meta( $product_id, '_sku', true );
                $product_name = get_post_meta( $post_id, 'products_'.$p.'_name', true );
                $product_qty = get_post_meta( $post_id, 'products_'.$p.'_quantity_requested', true );
                $product_unit_cost = get_post_meta( $post_id, 'products_'.$p.'_unit_cost', true );
                $product_total = get_post_meta( $post_id, 'products_'.$p.'_total_cost', true );
    
    
                $skus = $sku ? $sku : '';
                $names = $product_name ? $product_name : '';
                $qties = $product_qty ? $product_qty : '';
                $units_cost = $product_unit_cost ? $product_unit_cost : '';
                $totals = $product_total ? $product_total : '';


                $field_data = [ $purchase_order, $supplier_name, $supplier_account, $post_date, $skus, $names, $qties, $units_cost, $totals ];

                fputcsv($file, $field_data);
            }
        }
        exit();
    }
}