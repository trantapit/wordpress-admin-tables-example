<?php
/**
 * Creates the menu page for the plugin.
 *
 */

class SP_Setting_Submenu {

    public function init() {
         add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
    }

    public function add_submenu_page() {
        add_menu_page(
           'Sales Postings',
           'Sales Postings',
           'manage_options',
           'sales_postings',
           array( $this, 'render' ),
           'dashicons-clipboard',
           21
       );
    }

    public function render() {
      ?>
        <div class="wrap">
            <style>#the-list .row-actions{left:0;}</style>

            <h1>Sales Postings</h1>
            <?php
            // Creating an instance
            $sp_list_table = new Sales_Postings_List_Table();

            // Call prepare_items() – which handles the data prep prior to rendering the table
            $sp_list_table->prepare_items();

            // Call views() – which handles to show status links on the top
            $sp_list_table->views();

            // Call display() – which does the actual rendering of the table
            // $sp_list_table->display();
            ?>
            <form method="get">
                  <input type="hidden" name="page" value="sales_postings" />
                  <?php
                  // Render all fields of search box
                  $sp_list_table->search_box('search', 'search_id');
                  ?>

                  <?php
                  // Call display() – which does the actual rendering of the table
                  $sp_list_table->display();
                  ?>
            </form>
        </div>
      <?php
    }
}
