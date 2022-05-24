<?php
/**
 * Creates table for society rewards log
 */

class Sales_Postings_List_Table extends WP_List_Table {
      private $sp_data;

      // Get data from database
      private function get_all_sales_postings_data($search = "") {
           global $wpdb;

           if (!empty($search)) {
               return $wpdb->get_results(
                     "SELECT id, title, status, product_price, product_type, detail_content, saler_name, saler_email, saler_phone, saler_address, date
                      FROM {$wpdb->prefix}sales_postings
                      WHERE id Like '%{$search}%' OR title LIKE '%{$search}%' OR product_price LIKE '%{$search}%' OR product_type Like '%{$search}%'
                      ORDER BY date DESC ",
                     ARRAY_A
               );
          }else{
              return $wpdb->get_results(
                    "SELECT id, title, status, product_price, product_type, detail_content, saler_name, saler_email, saler_phone, saler_address, date
                     FROM {$wpdb->prefix}sales_postings
                     WHERE title <> ''
                     ORDER BY date DESC ",
                    ARRAY_A
              );
          }
      }

     // Override function of parent class
     // to define table columns
     function get_columns() {
           $columns = array(
                 'cb'              => '<input type="checkbox" />',
                 'title'           => 'Title',
                 'status'          => 'Status',
                 'product_price'   => 'Product price',
                 'product_type'    => 'Product type',
                 'detail_content'  => 'Detail content',
                 'saler_name'      => 'Saler name',
                 'saler_email'     => 'Saler email',
                 'saler_phone'     => 'Saler phone',
                 'saler_address'   => 'Saler address',
                 'date'            => 'Date'
           );
           return $columns;
     }

     // Override function of parent class
     // to show message when no items
     function no_items() {
        echo 'No sales posting avaliable.';
     }

     // Override function of parent class
     // to Bind table with columns, data and all
     function prepare_items() {

           // Handle row actions before load page
           $this->row_actions_handler();

           // Handle bulk actions before load page
           $this->bulk_actions_handler();

           // Get data for table
           if (isset($_GET['page']) && isset($_GET['s'])) {
               $this->sp_data = $this->get_all_sales_postings_data( $_GET['s'] );
            } else {
               $this->sp_data = $this->get_all_sales_postings_data();
            }

           // Pagination start
           $per_page = 5;
           $current_page = $this->get_pagenum();
           $total_items = count($this->sp_data);
           $this->sp_data = array_slice($this->sp_data, (($current_page - 1) * $per_page), $per_page);
           $this->set_pagination_args(array(
                 'total_items' => $total_items,
                 'per_page'    => $per_page
           ));
           // Pagination end

           // Define table columns
           $columns = $this->get_columns();

           // Add sorting to columns
           $sortable = $this->get_sortable_columns(); //array();

           // Add columns will hidden
           $hidden = array();

           // Set columns info
           $this->_column_headers = array($columns, $hidden, $sortable);

           // Sorting function
           usort($this->sp_data, array(&$this, 'usort_reorder'));

           // Add data into current list of items after handled
           $this->items = $this->sp_data;
     }

     // Override function of parent class
     // to show checkbox with each row
     function column_cb($item) {
           return sprintf(
                 '<input type="checkbox" name="product_ids[]" value="%s" />',
                 $item['id']
           );
     }

     // Override function of parent class
     // to bind data with column
     function column_default($item, $column_name) {
           switch ($column_name) {
              case 'title':
              case 'product_type':
              case 'detail_content':
              case 'saler_name':
              case 'saler_phone':
              case 'saler_address':
                  return $item[$column_name];

              case 'status':
                  return ( $item['status'] > 0 ) ? '<b style="color: blueviolet;">Published</b>' : 'Pending';

              case 'saler_email':
                  return '<a href="mailto:'. $item['saler_email'] .'">' . $item['saler_email'] . '</a>';

              case 'product_price':
                  return "<b>" . number_format($item['product_price']) . "</b> VND";

             case 'date':
                  return date('M j Y g:i A', strtotime( $item['date'] ) );

             default:
                   return print_r($item, true); //Show the whole array for troubleshooting purposes
           }
     }

     // Override function of parent class
     // to add sorting to columns
     protected function get_sortable_columns() {
           $sortable_columns = array(
                 'title' => array('title', true),
                 'status' => array('status', true),
                 'product_price' => array('product_price', true),
           );
           return $sortable_columns;
     }

     // Sorting function
     function usort_reorder($a, $b) {
           $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'date';
           $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
           $result = strcmp($a[$orderby], $b[$orderby]);

           // Send final sort direction to usort
           return ($order === 'asc') ? $result : -$result;
     }

     // Adding action buttons to column ( column_$custom( $item ) )
    function column_title($item) {
        $actions = array(
            'edit'   => sprintf('<a target="_blank" href="http://seminarproject.local/?product=%s">View</a>', $_REQUEST['page'], $item['id']),
            'publish' => sprintf('<a href="?page=%s&action=%s&product=%s">Publish</a>', $_REQUEST['page'], 'publish', $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&product=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
        );
        return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
    }

     // Row action handler
     function row_actions_handler() {
         global $wpdb;

         // publish
         if (isset($_GET['action']) && $_GET['page'] == "sales_postings" && $_GET['action'] == "publish") {
                $productID = intval($_GET['product']);
                $wpdb->update(
                  $wpdb->prefix . 'sales_postings',
                  array( 'status' => '1' ),
                  array( 'ID' => $productID ),
                  array( '%s' ),
                  array( '%d' )
                );
         }

         // delete
         if (isset($_GET['action']) && $_GET['page'] == "sales_postings" && $_GET['action'] == "delete") {
                $productID = intval($_GET['product']);
                $wpdb->delete(
                    $wpdb->prefix . 'sales_postings',
                    array( 'id' => $productID ),
                    array( '%d' ),
                );
         }
     }

     // Override function of parent class
     // to show bulk action dropdown
     function get_bulk_actions() {
            $actions = array(
                  'publish_all'    => 'Publish',
                  'delete_all' => "Delete",
                  'unpublish_all' => "Un publish"
            );
            return $actions;
      }

      // Bulk action handler
      function bulk_actions_handler() {
            global $wpdb;

            // Publish action
            if (isset($_GET['action']) && $_GET['page'] == "sales_postings" && $_GET['action'] == "publish_all") {
                  $productIDs = $_GET['product_ids'] ;

                  foreach ( $productIDs as $key => $p_id ) {
                      $wpdb->update(
                          $wpdb->prefix . 'sales_postings',
                          array( 'status' => '1' ),
                          array( 'ID' => $p_id ),
                          array( '%s' ),
                          array( '%d' )
                      );
                  }
            }

            // Delete action
            if (isset($_GET['action']) && $_GET['page'] == "sales_postings" && $_GET['action'] == "delete_all") {
                  $productIDs = $_GET['product_ids'] ;

                  foreach ( $productIDs as $key => $p_id ) {
                      $wpdb->delete(
                          $wpdb->prefix . 'sales_postings',
                          array( 'id' => $p_id ),
                          array( '%d' ),
                      );
                  }
            }
      }

      // Override function of parent class
      // to show the list of views available on this table
      function get_views() {
          $publish_count = 3;
          $status_links = array(
              "all"       => "<a href='#'>All</a>",
              "published" => "<a href='#'>Published".$publish_count."</a>",
              "pending"   => "<a href='#'>Pending</a>",
          );
          return $status_links;
      }

}
