<?php
final class HaetReviewsHolidaycheck extends HaetReviews
{
    private static $instance;

    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof HaetReviewsHolidaycheck)) {
            self::$instance = new HaetReviewsHolidaycheck();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->name = 'holidaycheck';
    }


    public function get_reviews_url()
    {
        return get_option('reviews_url_' . $this->name, false);
    }


    private function refresh_rating()
    {
        try {
            $reviews_url = get_option('reviews_url_' . $this->name, null);
            if (!$reviews_url)
                return false;

            $response = wp_remote_get($reviews_url);
            $html_response = wp_remote_retrieve_body($response);
            // if (is_user_logged_in()) {
            //     echo '<pre>';
            //     var_dump($html_response);
            //     echo '</pre>';
            // }
            //$html_response = stripslashes( $html_response );


            if ( strpos($html_response, 'Access Denied') !== false || strpos($html_response, "You don't have permission to access") !== false) {

                set_transient( 'rating_' . $this->name . '_valid', true, 5 * DAY_IN_SECONDS );
                // Handle the case where access is denied
                return false;

            } else {
                if (!preg_match_all('/<script type=application\/ld\+json>(.*)<\/script>/U', $html_response, $schema_match))
                    return false;
                if (!is_array($schema_match[1]) || !isset($schema_match[1][0]))
                    return false;
            }



            $schema = json_decode($schema_match[1][0]);
            // if (is_user_logged_in()) {
            //     echo '<pre>';
            //     var_dump($schema_match);
            //     echo '</pre>';
            // }
            // echo '<pre>';
            // var_dump($schema_match);
            // echo '</pre>';
            if (!$schema || !isset($schema->aggregateRating) || !isset($schema->aggregateRating->ratingValue) || !isset($schema->aggregateRating->ratingCount))
                return false;

            $rating = array(
                'rating'    =>  round(floatval($schema->aggregateRating->ratingValue), 1),
                'max_rating' =>  $schema->aggregateRating->bestRating,
                'count'     =>  $schema->aggregateRating->ratingCount,
            );

            update_option('rating_' . $this->name, $rating);
            set_transient( 'rating_' . $this->name . '_valid', true, 5 * DAY_IN_SECONDS );
            return $rating;
        } catch (Exception $e) {
            return false;
        }
        return false;
    }



    public function get_rating()
    {
        $reviews_url = get_option('reviews_url_' . $this->name, null);

        if (!$reviews_url)
            return false;

        $rating = get_option('rating_' . $this->name, null);

        // $rating = false; // force refresh

        if (!get_transient('rating_' . $this->name . '_valid') || !$rating) {
            $rating = $this->refresh_rating();

            if (!$rating) {
                $debug_mail = get_option('reviews_debug_mail', null);
                if ($debug_mail)
                    wp_mail($debug_mail, 'Social Reviews Error ' . $this->name, 'Could not refresh reviews for ' . $this->name . ' on ' . home_url());
            }
        }

        return $rating;
    }
}

function HaetReviewsHolidaycheck()
{
    return HaetReviewsHolidaycheck::instance();
}
HaetReviewsHolidaycheck();
