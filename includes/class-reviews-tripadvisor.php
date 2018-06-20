<?php
final class HaetReviewsTripadvisor extends HaetReviews{
    private static $instance;

    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof HaetReviewsTripadvisor)) {
            self::$instance = new HaetReviewsTripadvisor();
        }
        return self::$instance;
    }

    public function __construct(){
        $this->name = 'tripadvisor';
    }


    public function get_reviews_url(){
        return get_option( 'reviews_url_' . $this->name, false );
    }


    private function refresh_rating(){
        try{
            $reviews_url = get_option( 'reviews_url_' . $this->name, null );
            if( !$reviews_url )
                return false;

            $response = wp_remote_get( $reviews_url );
            $html_response = wp_remote_retrieve_body( $response );
            $html_response = stripslashes( $html_response );
            if( !preg_match_all('/<script type=\"application\/ld\+json\">(.*)<\/script>/U', $html_response, $schema_match) )
                return false;

            if( !is_array($schema_match[1]) || !isset( $schema_match[1][0] ) )
                return false;

            $schema = json_decode( $schema_match[1][0] );

            if( !$schema || !isset( $schema->aggregateRating ) || !isset( $schema->aggregateRating->ratingValue ) || !isset( $schema->aggregateRating->reviewCount ) )
                return false;

            $rating = array(
                    'rating'    =>  round( floatval( $schema->aggregateRating->ratingValue ), 1 ),
                    'max_rating'=>  5,
                    'count'     =>  $schema->aggregateRating->reviewCount,
                );

            update_option( 'rating_' . $this->name, $rating );
            set_transient( 'rating_' . $this->name . '_valid', true, DAY_IN_SECONDS );
            return $rating;
        }catch( Exception $e ){
            return false;
        }
        return false;
    }



    public function get_rating(){
        $reviews_url = get_option( 'reviews_url_' . $this->name, null );
        if( !$reviews_url )
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

function HaetReviewsTripadvisor(){
    return HaetReviewsTripadvisor::instance();
}
HaetReviewsTripadvisor();