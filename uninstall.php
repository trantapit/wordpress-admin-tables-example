<?php
/**
 * uninstall
 *
 */

if( ! function_exists( 'sp_uninstall' ) ) {
    /**
     * Unstall
     *
     */
    function sp_uninstall() {
        global $wpdb;

        $tables = [
            $wpdb->prefix . 'sales_postings',
        ];

        /**
         * Remove db table
         */
        foreach ($tables as $tablename) {
            $wpdb->query( "DROP TABLE IF EXISTS $tablename" );
        }
    }

    register_uninstall_hook( __FILE__, 'sp_uninstall' );
}
