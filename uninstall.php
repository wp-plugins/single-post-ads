<?php
/*
 * Uninstalls the plugin options and Pages
 */
// If uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
  exit;

// Delete options from option table
delete_option('tbp_spa_options');
?>