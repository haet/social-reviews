<?php
/*
Plugin Name: Social Reviews
Plugin URI: https://etzelstorfer.com/en/
Description: Social Media Reviews
Version: 0.2
Text Domain: social-reviews
Domain Path: /translations
Author: Hannes Etzelstorfer
Author URI: https://etzelstorfer.com/en/
License: GPLv2 or later
*/

/*  Copyright 2018 Hannes Etzelstorfer (email : hannes@etzelstorfer.com) */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'HAET_REVIEWS_PATH', plugin_dir_path(__FILE__) );
define( 'HAET_REVIEWS_URL', plugin_dir_url(__FILE__) );


require HAET_REVIEWS_PATH . 'includes/class-reviews.php';
require HAET_REVIEWS_PATH . 'includes/class-admin.php';
require HAET_REVIEWS_PATH . 'includes/class-frontend.php';

load_plugin_textdomain('social-reviews', false, dirname( plugin_basename( __FILE__ ) ) . '/translations' );
