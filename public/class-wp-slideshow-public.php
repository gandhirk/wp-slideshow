<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/public
 * @author     Rahul Gandhi <rahulgandhi1010@gmail.​com>
 */
class Wp_Slideshow_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Slideshow_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Slideshow_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'bootstrap-min-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css', array(),'3.4.0'  );
		wp_enqueue_style( 'fancybox-min-style', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', array(),'3.5.7'  );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-slideshow-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Slideshow_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Slideshow_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_script( 'bootstrap-min-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js', array( 'jquery' ), '3.4.0', true );
        wp_enqueue_script( 'fancybox-min-script', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-slideshow-public.js', array( 'jquery' ), $this->version, true );

	}

	public function widgets_init() {
        register_widget("WP_Slideshow_Lists");
    }

}
