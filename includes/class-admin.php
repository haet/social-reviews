<?php
require_once( 'class-reviews.php' );

class HaetReviewsAdmin{
    private static $instance;
    
    public function __construct(){
        if ( is_admin() ){
            add_action( 'admin_menu', array( $this, 'admin_page'),20);
            add_action( 'admin_init', array( $this, 'register_settings') );
        }
    }

    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof HaetReviewsAdmin)) {
            self::$instance = new HaetReviewsAdmin();
        }
        return self::$instance;
    }


    public function admin_page() {
        add_options_page( __('Social Reviews','social-reviews'), __('Social Reviews','social-reviews'), 'manage_options', 'social_reviews', array($this, 'print_admin_page') );
    }


    public function register_settings(){
        // simple channels
        add_settings_section(
                'social_reviews_channels',
                __('Channels','social-reviews'),
                array( $this, 'print_channels_settings_section' ),
                'social_reviews'
            );

        add_settings_field(
                'reviews_url_facebook',
                __('Facebook Reviews Page','social-reviews'),
                array( $this, 'print_channel_url_field' ),
                'social_reviews',
                'social_reviews_channels',
                array(
                    'key'           => 'reviews_url_facebook',
                    'description'   => __('URL to the reviews page of your Facebook corporate site.','social-reviews'),
                )
            );

        register_setting( 'social_reviews', 'reviews_url_facebook' );



        add_settings_field(
                'reviews_url_tripadvisor',
                __('Tripadvisor Reviews Page','social-reviews'),
                array( $this, 'print_channel_url_field' ),
                'social_reviews',
                'social_reviews_channels',
                array(
                    'key'           => 'reviews_url_tripadvisor',
                    'description'   => __('URL to the reviews page of your Tripadvisor site.','social-reviews'),
                )
            );

        register_setting( 'social_reviews', 'reviews_url_tripadvisor' );


        add_settings_field(
                'reviews_url_holidaycheck',
                __('HolidayCheck Reviews Page','social-reviews'),
                array( $this, 'print_channel_url_field' ),
                'social_reviews',
                'social_reviews_channels',
                array(
                    'key'           => 'reviews_url_holidaycheck',
                    'description'   => __('URL to the reviews page of your HolidayCheck site.','social-reviews'),
                )
            );

        register_setting( 'social_reviews', 'reviews_url_holidaycheck' );

        // Google
        add_settings_section(
                'social_reviews_google_api',
                __('Google API','social-reviews'),
                function(){
                    
                },
                'social_reviews'
            );

        add_settings_field(
                'reviews_google_api_key',
                __('Google Places API key','social-reviews'),
                array( $this, 'print_input_field' ),
                'social_reviews',
                'social_reviews_google_api',
                array(
                    'key'           => 'reviews_google_api_key',
                    'description'   => 'Get your API key <a href="https://developers.google.com/places/web-service/get-api-key" target="_blank">here</a>',
                )
            );

        register_setting( 'social_reviews', 'reviews_google_api_key' );


        add_settings_field(
                'reviews_google_place_id',
                __('Google Place ID','social-reviews'),
                array( $this, 'print_input_field' ),
                'social_reviews',
                'social_reviews_google_api',
                array(
                    'key'           => 'reviews_google_place_id',
                    'description'   => 'Get your place id <a href="https://developers.google.com/places/place-id" target="_blank">here</a>',
                )
            );

        register_setting( 'social_reviews', 'reviews_google_place_id' );


        add_settings_field(
                'reviews_url_google',
                __('Google Reviews Page','social-reviews'),
                array( $this, 'print_channel_url_field' ),
                'social_reviews',
                'social_reviews_google_api',
                array(
                    'key'           => 'reviews_url_google',
                    'description'   => __('URL to the reviews page of your Google my business page (just google for your business name, click the profile on the right and then click on reviews).','social-reviews'),
                )
            );

        register_setting( 'social_reviews', 'reviews_url_google' );


        // Debug
        add_settings_section(
                'social_reviews_debug',
                __('Debug','social-reviews'),
                function(){
                    
                },
                'social_reviews'
            );

        add_settings_field(
                'reviews_debug_mail',
                __('Debug Mail','social-reviews'),
                array( $this, 'print_input_field' ),
                'social_reviews',
                'social_reviews_debug',
                array(
                    'key'           => 'reviews_debug_mail',
                    'description'   => __( 'Some of the review platforms do not provide an official API. So some of the data is quite experimental. Just in case the data can\'t be refreshed anymore you can add an email here to get an automatic notification from your website.', 'social-reviews' ),
                )
            );

        register_setting( 'social_reviews', 'reviews_debug_mail' );
    }


    public function print_admin_page(){
        ?>
        <div class="wrap">
            <h1><?php _e('Social Reviews','social-reviews'); ?></h1>
            <form method="post" action="options.php"> 
                <?php 
                settings_fields( 'social_reviews' ); 
                do_settings_sections( 'social_reviews' );
                ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    function print_channels_settings_section() {
        echo '<p></p>';
    }

    function print_channel_url_field($args) {
        $value = get_option( $args['key'] );
        ?>
        <input name="<?php echo $args['key']; ?>" id="<?php echo $args['key']; ?>" type="url" value="<?php echo $value; ?>" class="widefat"/>
        <p class="description"><?php echo $args['description']; ?></p>
        <?php
    }



    function print_input_field($args) {
        $value = get_option( $args['key'] );
        ?>
        <input name="<?php echo $args['key']; ?>" id="<?php echo $args['key']; ?>" type="text" value="<?php echo $value; ?>" class="widefat"/>
        <p class="description"><?php echo $args['description']; ?></p>
        <?php
    }
}

function HaetReviewsAdmin(){
    return HaetReviewsAdmin::instance();
}
HaetReviewsAdmin();