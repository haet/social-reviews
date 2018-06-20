<?php

class HaetReviewsFrontend{
    private static $instance;
    
    public function __construct(){
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts_and_styles' ) );
    }

    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof HaetReviewsFrontend)) {
            self::$instance = new HaetReviewsFrontend();
        }
        return self::$instance;
    }



    /**
     *  Load Frontent JS and CSS
     */
    public function load_scripts_and_styles($page){

        if( apply_filters( 'haet_reviews_load_css', true ) )
            wp_enqueue_style('haet_reviews_style',  HAET_REVIEWS_URL.'/assets/css/social-reviews.css');
    }


    public function print( $channels = array() ){
        ?>
        <div class="social-reviews clearfix cf">
            <?php 
            $available_channels = array(
                    'holidaycheck'  => 'HaetReviewsHolidaycheck',
                    'tripadvisor'   => 'HaetReviewsTripadvisor',
                    'google'        => 'HaetReviewsGoogle',
                    'facebook'      => 'HaetReviewsFacebook',
                );

            $selected_channels = array();
            // use all channels if none selected
            if( !is_array( $channels ) || count( $channels ) == 0 )
                $selected_channels = $available_channels;
            else{
                foreach ($available_channels as $channel_name => $channel_class) {
                    if( in_array( $channel_name, $channels ) )
                        $selected_channels[$channel_name] =  $channel_class;
                }
            }

            foreach ($selected_channels as $channel_name => $channel_class):
                if( class_exists($channel_class) ):
                    $rating = $channel_class()->get_rating();
                    if( $rating ): ?>
                        <a class="rating-box" href="<?php echo $channel_class()->get_reviews_url(); ?>" target="_blank">
                            <div class="rating-image-col">
                                <img src="<?php echo HAET_REVIEWS_URL . '/assets/images/' . $channel_name . '.svg'; ?>">
                            </div>
                            <div class="rating-text-col">
                                <div class="rating-value">
                                    <?php echo number_format_i18n( $rating['rating'], 1 ) . ' / ' . $rating['max_rating']; ?>
                                </div>
                                <?php if( $rating['count'] ): ?>
                                    <div class="num-ratings">
                                        <?php echo $rating['count'] . ' ' . __('Reviews','social-reviews'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="cf"></div>
                        </a>
                        <?php 
                    endif;
                endif;
            endforeach;
            ?>
        </div>
        <?php
    }
}

function HaetReviewsFrontend(){
    return HaetReviewsFrontend::instance();
}
HaetReviewsFrontend();