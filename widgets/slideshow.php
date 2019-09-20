<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Slideshow_Lists extends WP_Super_Duper {

    public function __construct() {

        $options = array(
            'textdomain'    => 'wp-slideshow',
            'block-icon'    => 'admin-site',
            'block-category'=> 'widgets',
            'block-keywords'=> "['wp-slideshow']",
            'class_name'     => __CLASS__,
            'base_id'       => 'wp_slideshow',
            'name'          => __('WP > Slideshow','wp-slideshow'),
            'widget_ops'    => array(
                'classname'   => 'wp-slideshow-class',
                'description' => esc_html__('Display wp slider in frontend. using widget or shortcode. Use shortcode [wp_slideshow] for display slider.','wp-slideshow'),
            ),
            'arguments'     => array(
                'title'  => array(
                    'title'       => __( 'Widget title', 'wp-slideshow' ),
                    'desc'        => __( 'Enter widget title', 'wp-slideshow' ),
                    'type'        => 'text',
                    'desc_tip'    => true,
                    'default'     => '',
                    'advanced'    => false
                ),
                'column_number'  => array(
                    'title'       => __( 'Number of Column', 'wp-slideshow' ),
                    'desc'        => __( 'Enter number of column display in slider.', 'wp-slideshow' ),
                    'type'        => 'select',
                    'desc_tip'    => true,
                    'default'     => '1',
                    'advanced'    => false,
                    'options' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ),
                ),
                'column_spacing'  => array(
                    'title'       => __( 'Column Spacing', 'wp-slideshow' ),
                    'desc'        => __( 'Enter spacing number for slider.', 'wp-slideshow' ),
                    'type'        => 'text',
                    'desc_tip'    => true,
                    'default'     => '',
                    'advanced'    => false
                ),
                'slide_link_setting'  => array(
                    'title'       => __( 'Link Settings', 'wp-slideshow' ),
                    'desc'        => __( 'Select slider link option.', 'wp-slideshow' ),
                    'type'        => 'select',
                    'desc_tip'    => true,
                    'default'     => 'none',
                    'advanced'    => false,
                    'options' => array(
                        'none' => 'None',
                        'media_file' => 'Media File',
                        'lightbox' => 'Lightbox',
                    ),
                ),
            )
        );

        parent::__construct( $options );
    }

    public function output($args = array(), $widget_args = array(),$content = ''){

        ob_start();

        $column_number = !empty( $args['column_number'] ) ? $args['column_number'] : 1;
        $column_spacing = !empty( $args['column_spacing'] ) ? $args['column_spacing'] : 0;
        $slide_link_setting = !empty( $args['slide_link_setting'] ) ? $args['slide_link_setting'] : 'none';

        $get_slides = get_option(WP_SLIDER_SLIDE_KEY);

        $slide_column = $this->get_coulmun($column_number);

        $column_slide = array();

        if( !empty($get_slides) && is_array($get_slides)) {
            $total_slide = count($get_slides);

            $loop_count = ceil($total_slide / $column_number);

            $temp_offset = 0;
            for ( $i= 1; $i <= $loop_count; $i++ ) {
                $column_slide[] = array_slice($get_slides,$temp_offset,$column_number);
                $temp_offset = $temp_offset + $column_number;
            }

        }

        $col_margin = '';

        if( !empty( $column_spacing ) && $column_spacing > 0 ) {
            $col_margin = 'margin: 0 '.$column_spacing.'px"';
        }

        if( !empty( $column_slide ) && is_array($column_slide) ) {
            ?>
            <div id="wp_slider" class="carousel slide" data-ride="carousel">
                <?php if( !empty( $column_slide ) && is_array($column_slide)) {
                    $total_count = count($column_slide);
                    ?>
                <ol class="carousel-indicators">
                    <?php for ( $tc = 0; $tc < $total_count; $tc++ ) {
                        ?>
                        <li data-target="#wp_slider" data-slide-to="<?php echo $tc; ?>" class="<?php echo ( 0 == $tc) ? 'active': '';?>"></li>
                        <?php
                    } ?>

                </ol>
                <?php } ?>
                <div class="carousel-inner">
                    <?php
                    $count = 1;
                    foreach ( $column_slide as $slide ) {
                        ?>
                        <div class="item <?php echo ( 1 == $count ) ? 'active':'';?>">
                            <?php
                            if( !empty($slide) && is_array($slide)) {
                                ?><div class="row"><?php
                                    $slide_count = 1;
                                    foreach ( $slide as $slide_item ) {
                                        ?>
                                        <div style="padding: 0px;<?php echo $col_margin; ?>" class="col-sm-<?php echo $slide_column; ?>">
                                            <?php
                                            if( !empty( $slide_link_setting ) && 'media_file' == $slide_link_setting ) {
                                                ?>
                                                <a href="<?php echo $slide_item; ?>" target="_blank">
                                                    <img src="<?php echo $slide_item; ?>" alt="<?php echo 'wp_slide_count_'.$slide_count; ?>>">
                                                </a>
                                                <?php
                                            } elseif ( !empty( $slide_link_setting ) && 'lightbox' == $slide_link_setting ) {
                                                ?>
                                                <a href="<?php echo $slide_item; ?>" class="fancybox">
                                                    <img src="<?php echo $slide_item; ?>" alt="<?php echo 'wp_slide_count_'.$slide_count; ?>>">
                                                </a>
                                                <?php
                                            } else{
                                                ?>
                                                <img src="<?php echo $slide_item; ?>" alt="<?php echo 'wp_slide_count_'.$slide_count; ?>>">
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                ?></div><?php
                            }
                            ?>
                        </div>
                        <?php
                        $count++;
                    }
                    ?>
                </div>
                <a class="left carousel-control" href="#wp_slider" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only"><?php _e('Previous','wp-slideshow'); ?></span>
                </a>
                <a class="right carousel-control" href="#wp_slider" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only"><?php _e('Next','wp-slideshow'); ?></span>
                </a>
            </div>

            <?php
        }
        $output = ob_get_clean();

        return $output;

    }

    public function get_coulmun( $column_number = 1 ) {

        switch ($column_number) {
            case 1:
                $column = 12;
                break;
            case 2:
                $column = 6;
                break;
            case 3:
                $column = 4;
                break;
            case 4:
                $column = 3;
                break;
            case 5:
            case 6:
                $column = 2;
                break;
            default:
                $column = 12;
        }

        return $column;

    }

}