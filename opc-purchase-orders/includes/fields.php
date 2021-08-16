<?php
/*
*   AFC FIELDS CREATION
* 
*/
require_once plugin_dir_path( __DIR__ ) . 'acf-auto-increment/acf-auto-increment.php';

if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array(
        'key' => 'group_5fbbd04ad25fb',
        'title' => 'Purchase Order',
        'fields' => array(
            array(
                'key' => 'field_5fbe1fa246f72',
                'label' => 'Order No',
                'name' => 'purchase_order',
                'type' => 'ai',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                ),
            ),
            array(
                'key' => 'field_5fbbd04ad4dbb',
                'label' => 'Status',
                'name' => 'status',
                'type' => 'select',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                ),
                'choices' => array(
                    'Pending' => 'Pending',
                    'Order Placed' => 'Order Placed',
                    'Completed' => 'Completed',
                ),
                'default_value' => false,
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
            ),
            array(
                'key' => 'field_5fbbd04ad4dca',
                'label' => 'Order Date',
                'name' => 'order_date',
                'type' => 'date_picker',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                ),
                'acfe_permissions' => '',
                'display_format' => 'F j, Y',
                'return_format' => 'F j, Y',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_5fbbd04ad4dce',
                'label' => 'Delivery Date',
                'name' => 'delivery_date',
                'type' => 'date_picker',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                ),
                'display_format' => 'F j, Y',
                'return_format' => 'F j, Y',
                'first_day' => 1,
            ),
            array(
                'key' => 'field_5fbbce15e4655',
                'label' => 'Print Supplier',
                'name' => 'print_supplier',
                'type' => 'select',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'id' => 'print-address',
                ),
                'choices' => '',
                'default_value' => false,
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'return_format' => 'value',
                'ajax' => 0,
            ),
            array(
                'key' => 'field_5fbbce3d4176c',
                'label' => 'Delivery Address',
                'name' => 'delivery_address',
                'type' => 'select',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'id' => 'delivery-address',
                ),
                'choices' => '',
                'default_value' => false,
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'return_format' => 'value',
                'ajax' => 0,
            ),
            array(
                'key' => 'field_5fbbce574176d',
                'label' => 'Additional Information',
                'name' => 'additional_information',
                'type' => 'textarea',
                'required' => 0,
                'conditional_logic' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_5fc51218eda0f',
                'label' => 'Products',
                'name' => 'products',
                'type' => 'repeater',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'class' => 'products',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => 'Add',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5fc5122feda10',
                        'label' => 'Code',
                        'name' => 'product_code',
                        'type' => 'post_object',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'product_code',
                        ),
                        'post_type' => array(
                            0 => 'product',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'object',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_5fc51250eda12',
                        'label' => 'Description',
                        'name' => 'name',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'name',
                        ),
                        'prepend' => ' ',
                        'append' => ' ',
                    ),
                    array(
                        'key' => 'field_5fc51265eda13',
                        'label' => 'Per Pack',
                        'name' => 'per_pack',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'per_pack',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc5126eeda14',
                        'label' => 'Per Carton',
                        'name' => 'per_carton',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'per_carton',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51284eda17',
                        'label' => 'Flat Size',
                        'name' => 'flat_size',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'flat_size',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc5128ceda18',
                        'label' => 'Folded Size',
                        'name' => 'folded_size',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'folded_size',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51278xka19',
                        'label' => 'Face',
                        'name' => 'face',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'face',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51294eda19',
                        'label' => 'Finishes',
                        'name' => 'finishes',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'finishes',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51278eda19',
                        'label' => 'Packing',
                        'name' => 'packing',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'packing',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51278eka19',
                        'label' => 'Material',
                        'name' => 'material',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'material',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51278exa19',
                        'label' => 'Reverse',
                        'name' => 'reverse',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'reverse',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51278efa19',
                        'label' => 'Coating',
                        'name' => 'coating',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'coating',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51278eda15',
                        'label' => 'Unit Cost',
                        'name' => 'unit_cost',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'unit_cost',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc51242eda11',
                        'label' => 'Qty Req',
                        'name' => 'quantity_requested',
                        'type' => 'number',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'quantity_requested',
                        ),
                    ),
                    array(
                        'key' => 'field_5fc5127eeda16',
                        'label' => 'Total Cost',
                        'name' => 'total_cost',
                        'type' => 'text',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'class' => 'total_cost',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_5fbbd091de06c',
                'label' => 'Total Extra Cost',
                'name' => 'total_extra_cost',
                'type' => 'number',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'class' => 'total_extra_cost',
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'purchase_orders',
                ),
            ),
        ),
        'menu_order' => 1,
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
    acf_add_local_field_group(array(
        'key' => 'group_601a7a7c3c4fd',
        'title' => 'Email Details',
        'fields' => array(
            array(
                'key' => 'field_601a7bc9effc8',
                'name' => 'to_email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'to@example.com',
            ),
            array(
                'key' => 'field_601a7a9c11db5',
                'name' => 'cc1_email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'cc1@example.com',
            ),
            array(
                'key' => 'field_601a7bf555df8',
                'name' => 'cc2_email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'cc2@example.com',
            ),
            array(
                'key' => 'field_601a7ec5b186d',
                'name' => 'cc3_email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'cc3@example.com',
            ),
            array(
                'key' => 'field_601a7a8911db4',
                'name' => 'subject_email',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'Subject',
            ),
            array(
                'key' => 'field_601a7aae11db6',
                'name' => 'body_email',
                'type' => 'wysiwyg',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'tabs' => 'visual',
                'toolbar' => 'basic',
                'media_upload' => 1,
                'delay' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'purchase_orders',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_60198c7d1598c',
        'title' => 'Email Settings',
        'fields' => array(
            array(
                'key' => 'field_601a66a06164a',
                'label' => 'Full Name',
                'name' => 'from_email',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'John Doe',
            ),
            array(
                'key' => 'field_60198c99a1be7',
                'label' => 'Sender',
                'name' => 'sender_email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'johndoe@example.com',
            ),
            array(
                'key' => 'field_601a66ef6164b',
                'label' => 'Password',
                'name' => 'sender_password',
                'type' => 'password',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array(
                'key' => 'field_601a83c46550c',
                'label' => 'Host',
                'name' => 'smtp_host',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'default_value' => 'smtp.gmail.com',
            ),
            array(
                'key' => 'field_601a83e86550d',
                'label' => 'Port',
                'name' => 'port_email',
                'type' => 'number',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'default_value' => 587,
            ),
            array(
                'key' => 'field_601a930136d52',
                'label' => 'Encryption',
                'name' => 'tls_email',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'default_value' => 'tls',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-email-settings',
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


/*
*   WOOCOMMERCE PRODUCT DATA TAB EXPANDED TO INCLUDE META FIELDS
*
*/
add_filter( 'woocommerce_product_data_tabs', 'add_production_product_data_tab' , 99 , 1 );
function add_production_product_data_tab( $product_data_tabs ) {
    $product_data_tabs['production'] = array(
        'label' => __( 'Production', 'text_domain' ),
        'target' => 'production_product_data',
    );
    return $product_data_tabs;
}
add_action( 'woocommerce_product_data_panels', 'add_production_product_data_fields' );
function add_production_product_data_fields() {
    global $woocommerce, $post;
    ?>
    <div id="production_product_data" class="panel woocommerce_options_panel">
        <?php
        woocommerce_wp_text_input( array( 
            'id'            => 'unit_cost',  
            'label'         => __( 'Unit cost', 'text_domain' ),
            'type'          => 'number',
            'placeholder' => 'Cost per unit',
            'custom_attributes' => array(
                'step'  => 'any',
                'min'   => '0'
            )
        ) );woocommerce_wp_text_input( array( 
            'id'            => 'per_pack',  
            'label'         => __( 'Per pack', 'text_domain' ),
            'type'          => 'number',
            'placeholder' => 'Units per pack',
            'custom_attributes' => array(
                'step'  => 'any',
                'min'   => '0'
            )
        ) );woocommerce_wp_text_input( array( 
            'id'            => 'per_carton',  
            'label'         => __( 'Per carton', 'text_domain' ),
            'type'          => 'number',
            'placeholder' => 'Units per carton',
            'custom_attributes' => array(
                'step'  => 'any',
                'min'   => '0'
            )
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'flat_size',  
            'label'         => __( 'Flat size', 'text_domain' ),
            'placeholder'   => 'Flat size',
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'folded_size',  
            'label'         => __( 'Folded size', 'text_domain' ),
            'placeholder'   => 'Folded size',
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'face',  
            'label'         => __( 'Face', 'text_domain' ),
            'placeholder'   => 'Face',
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'finishes',  
            'label'         => __( 'Finishes', 'text_domain' ),
            'placeholder'   => 'Finishes',
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'packing',  
            'label'         => __( 'Packing', 'text_domain' ),
            'placeholder'   => 'Packing',
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'material',  
            'label'         => __( 'Material', 'text_domain' ),
            'placeholder'   => 'Material',
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'reverse',  
            'label'         => __( 'Reverse', 'text_domain' ),
            'placeholder'   => 'Reverse',
        ) );
        woocommerce_wp_text_input( array( 
            'id'            => 'coating',  
            'label'         => __( 'Coating', 'text_domain' ),
            'placeholder'   => 'Coating',
        ) );
        ?>
    </div>
    <?php
}
add_action( 'woocommerce_process_product_meta', 'woocommerce_process_product_meta_fields_save' );
function woocommerce_process_product_meta_fields_save( $post_id ){
    $unit_cost = isset( $_POST['unit_cost'] ) ? $_POST['unit_cost'] : '';
    $per_pack = isset( $_POST['per_pack'] ) ? $_POST['per_pack'] : '';
    $per_carton = isset( $_POST['per_carton'] ) ? $_POST['per_carton'] : '';
    $flat_size = isset( $_POST['flat_size'] ) ? $_POST['flat_size'] : '';
    $folded_size = isset( $_POST['folded_size'] ) ? $_POST['folded_size'] : '';
    $face = isset( $_POST['face'] ) ? $_POST['face'] : '';
    $finishes = isset( $_POST['finishes'] ) ? $_POST['finishes'] : '';
    $packing = isset( $_POST['packing'] ) ? $_POST['packing'] : '';
    $material = isset( $_POST['material'] ) ? $_POST['material'] : '';
    $reverse = isset( $_POST['reverse'] ) ? $_POST['reverse'] : '';
    $coating = isset( $_POST['coating'] ) ? $_POST['coating'] : '';

    update_post_meta( $post_id, 'unit_cost', $unit_cost );
    update_post_meta( $post_id, 'per_pack', $per_pack );
    update_post_meta( $post_id, 'per_carton', $per_carton );
    update_post_meta( $post_id, 'flat_size', $flat_size );
    update_post_meta( $post_id, 'folded_size', $folded_size );
    update_post_meta( $post_id, 'face', $face );
    update_post_meta( $post_id, 'finishes', $finishes );
    update_post_meta( $post_id, 'packing', $packing );
    update_post_meta( $post_id, 'material', $material );
    update_post_meta( $post_id, 'reverse', $reverse );
    update_post_meta( $post_id, 'coating', $coating );
}