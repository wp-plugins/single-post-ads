<?php
/*
 * Plugin Name: TBP Single Post Ads
 * Plugin URI: http://techbasedplugins.com/22/single-post-ads/
 * Description: Lets you enter ads for each individual post failing which a default ad will be displayed.
 * Version: 0.0.1
 * Author: Lynette Chandler
 * Author URI: http://TechBasedMarketing.com
 */

/* Load required files */
require_once( dirname( __FILE__ ) . '/includes/functions.php');

// Loads admin links and admin panel if user is in wp-admin
if (is_admin()) {
  require_once( dirname( __FILE__ ) . '/includes/admin.php' );
}

add_action('admin_init', 'tbp_spa_admin_init');
function tbp_spa_admin_init() {
/* Register settings */
register_setting('tbp_spa_options', 'tbp_spa_options', 'tbp_spa_validate_options');
}
?>