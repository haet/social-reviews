<?php
require_once( 'class-reviews-google.php' );
require_once( 'class-reviews-facebook.php' );
require_once( 'class-reviews-holidaycheck.php' );
require_once( 'class-reviews-tripadvisor.php' );

class HaetReviews{
    protected $name;

    
    public function __construct(){
                
    }


    public function get_rating(){}


    public function get_reviews_url(){}


    /*
    public function print_rich_snippets(){
        $ratings_facebook = HaetReviewsFacebook()->get_rating();
        $ratings_google = HaetReviewsGoogle()->get_rating();
        if( $ratings_facebook && $ratings_google )
            $num_ratings = $ratings_facebook['count'] + $ratings_google['count'];
        if( $num_ratings ){
            $average_rating = ( $ratings_facebook['rating'] * $ratings_facebook['count'] + $ratings_google['rating'] * $ratings_google['count'] ) / $num_ratings;
            ?>
            <div style="display:none;" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                <span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing" >Golden Hill</span>    
                <strong itemprop="ratingValue"><?php echo $average_rating; ?></strong>
                <span itemprop="worstRating">1</span>
                <span itemprop="bestRating">5</span>
                <span itemprop="ratingCount"><?php echo $num_ratings; ?></span>
            </div>
            <?php
        }
    }
    */
}
