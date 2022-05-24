<?php
/**
 * Install
 *
 */

define( 'SP_DB_VERSION', '1.0.0' );

if( ! function_exists( 'sp_install' ) ) {
    /**
     * Install plugin
     *
     */
    function sp_install() {
        global $wpdb;
        $table_name_sp = $wpdb->prefix . 'sales_postings';
        $charset_collate = $wpdb->get_charset_collate();

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        # sales postings
        $sql =
  			"CREATE TABLE {$table_name_sp} (
  			id bigint(20) NOT NULL AUTO_INCREMENT,
        title longtext DEFAULT NULL,
  			product_price bigint(20) DEFAULT NULL,
        product_type varchar(255) DEFAULT NULL,
        detail_content longtext DEFAULT NULL,
  			saler_name varchar(255) DEFAULT NULL,
  			saler_email varchar(255) DEFAULT NULL,
        saler_phone varchar(255) DEFAULT NULL,
        saler_address varchar(255) DEFAULT NULL,
  			date datetime NOT NULL,
  			PRIMARY KEY  (id)
  			) " . $charset_collate;
  			dbDelta( $sql );

    	update_option( "sp_db_version", SP_DB_VERSION );
    }
}

if( ! function_exists( 'sp_update_db_check' ) ) {
    /**
     * Check and install database and others
     */
    function sp_update_db_check() {
        if ( get_site_option( 'sp_db_version' ) != SP_DB_VERSION ) {
            sp_install();
        }
    }
    add_action( 'plugins_loaded', 'sp_update_db_check' );
}
