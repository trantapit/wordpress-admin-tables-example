<?php
/**
 * All functions for Sales Postings
 */

// Insert when submission sales postings
add_action('ninja_forms_after_submission', 'submission_sales_postings_func');
function submission_sales_postings_func( $form_data ){
    global $wpdb;
    $table_name_sp = $wpdb->prefix . 'sales_postings';

    $title = $product_price = $type_of_product = $detail_content = $name = $email = $phone = $address = "";
    foreach( $form_data[ 'fields' ] as $field ) {

         if( 'title' == $field[ 'key' ] )
                 $title = $field[ 'value' ];

         if( 'product_price' == $field[ 'key' ] )
                 $product_price = $field[ 'value' ];

         if( 'type_of_product' == $field[ 'key' ] )
                 $type_of_product = $field[ 'value' ];

         if( 'detail_content' == $field[ 'key' ] )
                 $detail_content = $field[ 'value' ];

         if( 'name' == $field[ 'key' ] )
                 $name = $field[ 'value' ];

         if( 'email' == $field[ 'key' ] )
                 $email = $field[ 'value' ];

         if( 'phone' == $field[ 'key' ] )
                 $phone = $field[ 'value' ];

         if( 'address' == $field[ 'key' ] )
                 $address = $field[ 'value' ];
    }

    // Log for order completed
    $wpdb->insert( $table_name_sp, array(
        'title'           => $title,
        'product_price'   => $product_price,
        'product_type'    => $type_of_product,
        'detail_content'  => $detail_content,
        'saler_name'      => $name,
        'saler_email'     => $email,
        'saler_phone'     => $phone,
        'saler_address'   => $address,
        'date'            => current_time( 'mysql', 1 ),
        'status'          => '0',
      )
    );
}
