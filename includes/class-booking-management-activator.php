<?php

/**
 * Fired during plugin activation
 *
 * @link       https://startandgrow.in
 * @since      1.0.0
 *
 * @package    Booking_Management
 * @subpackage Booking_Management/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Booking_Management
 * @subpackage Booking_Management/includes
 * @author     Start and Grow <laravel6@startandgrow.in>
 */
class Booking_Management_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		$this->create_table();
	}

	public function create_table() {
		global $wpdb;
		if ( version_compare( get_bloginfo( 'version' ), '6.1' ) < 0 ) {
			require_once ABSPATH . 'wp-includes/wp-db.php';
		} else {
			require_once ABSPATH . 'wp-includes/class-wpdb.php';
		}
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		// Ensures proper charset support. Also limits support for WP v3.5+.
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $this->get_db_table_name( 'SERVICE' );
		$sql             = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`service_name` varchar(255) NOT NULL UNIQUE,
		`service_category` int(11) NOT NULL,
		`service_duration` float(24) NOT NULL comment '(in hr)',
		`service_max_cap` int(100) NOT NULL DEFAULT 1 comment '(no.s)',
		`service_min_cap` int(100) NOT NULL DEFAULT 1 comment '(no.s)',
		`is_service_front` int(11) NOT NULL DEFAULT 1 comment '(1=Yes, 0=No)',
		`service_desc` longtext DEFAULT NULL,
		`service_price` float(50) NOT NULL,
		`service_status` int(11) NOT NULL DEFAULT 1 comment '(1=Publish, 0=Draft, Default 1)',
		`service_extra_field` longtext DEFAULT NULL,
		`service_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`service_updated_at` datetime DEFAULT NULL,
		PRIMARY KEY (`id`)
		)$charset_collate;";
		dbDelta( $sql );

		$table_name = $this->get_db_table_name( 'CATEGORY' );
		$sql        = "CREATE TABLE IF NOT EXISTS $table_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `cat_name` varchar(255) NOT NULL UNIQUE,
		`cat_in_front` int(11) NOT NULL comment '(1=Yes, 0=No)',
		`cat_status` int(11) NOT NULL DEFAULT 1 comment '(1=Publish, 0=Draft, Default 1)',
		`cat_extra_field` longtext DEFAULT NULL,
		`cat_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`cat_updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
		)$charset_collate;";
		dbDelta( $sql );
	}

	public function get_db_table_name( $identifier ) {
		global $wpdb;
		$plugin_prefix    = $wpdb->prefix . 'bm_';

		switch ( $identifier ) {
			case 'CATEGORY':
				$table_name = $plugin_prefix . 'categories';
				break;
			case 'SERVICE':
				$table_name = $plugin_prefix . 'services';
				break;
			case 'GLOBAL':
				$table_name = $wpdb->prefix . 'options';
				break;

			default:
				$classname = "BM_Helper_$identifier";
				if ( class_exists( $classname ) ) {
					$externalclass = new $classname();
					$table_name    = $externalclass->get_db_table_name( $identifier );
				} else {
					return false; 
				}
		}
		return $table_name;
	}

	public function get_db_table_unique_field_name( $identifier ) {

		switch ( $identifier ) {
			case 'CATEGORY':
				$unique_field_name = 'id';
				break;
			case 'SERVICE':
				$unique_field_name = 'id';
				break;
			case 'GLOBAL':
				$unique_field_name = 'option_id';
				break;

			default:
				$classname = "BM_Helper_$identifier";
				if ( class_exists( $classname ) ) {
					$externalclass     = new $classname();
					$unique_field_name = $externalclass->get_db_table_unique_field_name( $identifier );
				} else {
					return false; }
		}
		return $unique_field_name;
	}

	public function get_db_table_field_type( $identifier, $field ) {
		$functionname = 'get_field_format_type_' . $identifier;
		if ( method_exists( 'Booking_Management_Activator', $functionname ) ) {
			$format = $this->$functionname( $field );
		} else {
			$classname = "BM_Helper_$identifier";
			if ( class_exists( $classname ) ) {
				$externalclass = new $classname();
				$format        = $externalclass->get_db_table_field_type( $identifier, $field );
			} else {
				return false; }
		}
		return $format;
	}

	public function get_field_format_type_CATEGORY( $field ) {
		switch ( $field ) {
			case 'id':
				$format = '%d';
				break;
			case 'cat_name':
				$format = '%s';
				break;
			case 'cat_in_front':
				$format = '%d';
				break;
			default:
				$format = '%s';
		}
		return $format;
	}

}
