<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://startandgrow.in
 * @since      1.0.0
 *
 * @package    Booking_Management
 * @subpackage Booking_Management/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Booking_Management
 * @subpackage Booking_Management/admin
 * @author     Start and Grow <laravel6@startandgrow.in>
 */
class Booking_Management_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Booking_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Booking_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/booking-management-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Booking_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Booking_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/booking-management-admin.js', array('jquery'), $this->version, false);
	}

	public function booking_admin_menu()
	{
		add_menu_page(__('Dashboard', 'service-booking'), __('Manage Booking', 'service-booking'), 'manage_options', 'bm_home', array($this, 'bm_home'), 'dashicons-groups', 26);
		add_submenu_page('bm_home', __('Booking Dashboard', 'service-booking'), __('Dashboard', 'service-booking'), 'manage_options', 'bm_home', array($this, 'bm_home'));
		add_submenu_page('bm_home', __('All Services', 'service-booking'), __('All Services', 'service-booking'), 'manage_options', 'bm_all_services', array($this, 'bm_all_services'));
		add_submenu_page('bm_home', __('Add Service', 'service-booking'), __('Add Service', 'service-booking'), 'manage_options', 'bm_add_service', array($this, 'bm_add_service'));
		add_submenu_page('bm_home', __('All Categories', 'service-booking'), __('All Categories', 'service-booking'), 'manage_options', 'bm_all_categories', array($this, 'bm_all_categories'));
		add_submenu_page('bm_home', __('Add Category', 'service-booking'), __('Add Category', 'service-booking'), 'manage_options', 'bm_add_category', array($this, 'bm_add_category'));
		add_submenu_page('bm_home', __('Global Settings', 'service-booking'), __('Global Settings', 'service-booking'), 'manage_options', 'bm_global', array($this, 'bm_global'));
	}

	public function bm_home() {
		include 'partials/booking-management-dashboard-form.php';
	}

	public function bm_all_services() {
		include 'partials/booking-management-service-listing.php';
	}

	public function bm_add_service() {
		include 'partials/booking-management-add-service.php';
	}

	public function bm_all_categories() {
		include 'partials/booking-management-category-listing.php';
	}

	public function bm_add_category() {
		include 'partials/booking-management-add-category.php';
	}

	public function bm_global() {
		include 'partials/booking-management-global-settings.php';
	}
}
