<?php
final class HaetReviewsGoogle extends HaetReviews{
    private static $instance;
    private $google_places_api_url = 'https://maps.googleapis.com/maps/api/place/';


    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof HaetReviewsGoogle)) {
            self::$instance = new HaetReviewsGoogle();
        }
        return self::$instance;
    }

    public function __construct(){
        $this->name = 'google';
    }


    public function get_reviews_url(){
        return get_option( 'reviews_url_' . $this->name, false );
    }


    private function refresh_rating(){
        try{
            $google_places_api_key = get_option( 'reviews_google_api_key', null);
            $google_place_id = get_option( 'reviews_google_place_id', null);

            $url = $this->google_places_api_url . 'details/json?placeid=' . $google_place_id . '&key=' . $google_places_api_key;
            $response = wp_remote_get( $url );
            $api_response = json_decode( wp_remote_retrieve_body( $response ), true );
            if( is_array( $api_response ) && is_array( $api_response['result'] ) && isset( $api_response['result']['rating'] ) ){
                $rating = array(
                        'rating'    =>  round( floatval( $api_response['result']['rating'] ), 1 ),
                        'max_rating'=>  5,
                        'count'     =>  false
                    );
                update_option( 'rating_' . $this->name, $rating );
                set_transient( 'rating_' . $this->name . '_valid', true, DAY_IN_SECONDS );
                return $rating;
            }else{
                return false;
            }
        }catch( Exception $e ){
            return false;
        }
        return false;
    }



    public function get_rating(){
        $reviews_url = get_option( 'reviews_url_' . $this->name, null );
        $google_places_api_key = get_option( 'reviews_google_api_key', null);
        $google_place_id = get_option( 'reviews_google_place_id', null);
        if( !$reviews_url || !$google_places_api_key || !$google_place_id )
            return false;

        $rating = get_option( 'rating_' . $this->name, null );
        // $rating = false; // force refresh
        if( !get_transient( 'rating_' . $this->name . '_valid' ) || !$rating ) {
            $rating = $this->refresh_rating();
            if( !$rating ){
                $debug_mail = get_option( 'reviews_debug_mail', null );
                if( $debug_mail )
                    wp_mail( $debug_mail, 'Social Reviews Error ' . $this->name, 'Could not refresh reviews for ' . $this->name . ' on ' . home_url() );
            }
        }
        return $rating;
    }
}

function HaetReviewsGoogle(){
    return HaetReviewsGoogle::instance();
}
HaetReviewsGoogle();