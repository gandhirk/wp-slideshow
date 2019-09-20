<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/
 * @since      1.0.0
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/admin
 * @author     Rahul Gandhi <rahulgandhi1010@gmail.​com>
 */
class Wp_Slideshow_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

        wp_enqueue_style( 'jquery-ui-min-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', array(), '1.12.1');
        wp_enqueue_style( 'bootstrap-min-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css', array(),'3.4.0'  );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-slideshow-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

        wp_enqueue_media();
        wp_enqueue_script( 'bootstrap-min-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js', array( 'jquery' ), '3.4.0', true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-slideshow-admin.js', array( 'jquery','jquery-ui-sortable' ), $this->version, false );

	}

	public function admin_menu() {
        add_menu_page(
            __( 'WP SlideShow', 'wp-slideshow' ),
            'WP SlideShow',
            'manage_options',
            'wp-slideshow',
            array( $this,'wp_slideshow_cb' ),
            'dashicons-slides'
        );
    }

    public function wp_slideshow_cb() {
        ?>
        <div class="wrap">
            <h2><?php _e('Slide Show settings','wp-slideshow'); ?></h2>
            <div class="wp-slider-container">
                <div class="wp-slider-add-btn">
                    <button class="wp-slider-add button button-primary"><?php _e('Add New Slide','wp-slideshow'); ?></button>
                    <button class="wp-slider-order-save button button-primary"><?php _e('Save Order','wp-slideshow'); ?></button>
                </div>
                <div class="notification-wrap"><p class="notification"></p></div>
                <div class="slide-counts"><p class="slide-count"><?php _e('Total slides:','wp-slideshow'); ?> <span><?php echo $this->total_num_slides(); ?></span></p></div>
                <div class="wp-slider-lists">
                    <?php echo $this->slide_html(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function wp_add_new_slide() {

	    $attachment_url = !empty( $_POST['attachment_url'] ) ? $_POST['attachment_url'] :'';

        $this->update_slide($attachment_url);

        $attachment_html = $this->slide_html();

	    $response = array(
            'html' => $attachment_html,
            'count' => $this->total_num_slides(),
            'message' => __('New slide added successfully!','wp-slideshow'),
        );

	    echo json_encode($response);
	    wp_die();
	    
    }

    public function wp_change_slide_order() {

        $slides = !empty( $_POST['slides'] ) ? $_POST['slides'] :'';
        update_option(WP_SLIDER_SLIDE_KEY,'');
        update_option(WP_SLIDER_SLIDE_KEY,$slides);

        $slide_html = $this->slide_html();

        $response = array(
            'html' => $slide_html,
            'count' => $this->total_num_slides(),
            'message' => __('Slides order changed successfully!','wp-slideshow'),
        );

        echo json_encode($response);
        wp_die();

    }

    public function wp_remove_slide() {

        $slide_id = !empty( $_POST['slide_id'] ) ? $_POST['slide_id'] :'';

        $slide_items = get_option(WP_SLIDER_SLIDE_KEY);
        $remove_slide = $slide_id - 1;

        unset($slide_items[$remove_slide]);
        $reorder_slide = array_values($slide_items);

        update_option(WP_SLIDER_SLIDE_KEY,'');
        update_option(WP_SLIDER_SLIDE_KEY,$reorder_slide);

        $slide_html = $this->slide_html();

        $response = array(
            'html' => $slide_html,
            'count' => $this->total_num_slides(),
            'message' => __('Slide deleted successfully!','wp-slideshow'),
        );

        echo json_encode($response);
        wp_die();

    }

    public function update_slide( $image_url ) {

        $slide_items = get_option(WP_SLIDER_SLIDE_KEY);

        $slides = array();
        if( empty($slide_items) ) {

            $slides[] = $image_url;

        } else{
            $slides = $slide_items;
            $slides[] = $image_url;
        }

        update_option(WP_SLIDER_SLIDE_KEY,$slides);
    }

    public function slide_html(){

	    $slide_items = get_option(WP_SLIDER_SLIDE_KEY);

	    $html = '';

	    if( !empty( $slide_items ) && is_array($slide_items)) {

	        $html .= '<ul id="wp_slider" class="sortable">';

	        foreach ( $slide_items as $s_key => $values ){
	            $data_id = $s_key+1;
                $html .= '<li>';
                $html .= '<img src="'.$values.'" alt="'.$data_id.'">';
                $html .= '<a class="remove-slide" href="javascript:void(0);" data-id="'.$data_id.'"><span class="dashicons dashicons-no"></span></a>';
                $html .= '</li>';
            }

            $html .= '</ul>';

        }

	    return $html;
    }

    public function total_num_slides() {
        $slide_items = get_option(WP_SLIDER_SLIDE_KEY);

        $total_slide = 0;

        if( !empty( $slide_items ) && is_array($slide_items)) {
            $total_slide = count($slide_items );
        }

        return $total_slide;
    }


}
