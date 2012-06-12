<?php
/* Calls functions to create admin menus */
add_action( 'admin_menu', 'tbp_spa_admin_panel' );
function tbp_spa_admin_panel() {
add_options_page( 'Single Post Ads Settings', 'Single Post Ads', 'manage_options', 'tbp_spa_settings', 'tbp_spa_settings_page'  );
}

/* Draws options panel */
function tbp_spa_settings_page () {
  require_once( dirname( __FILE__ ) . '/options-settings.php' );
  ?>
  <div class="wrap">
  <?php screen_icon(); ?>
  <h2>Single Post Ads Settings</h2>
  <div class="widget-liquid-left">
  <div id="widgets-left">
	  <form action="options.php" method="post">

		<?php
		settings_fields('tbp_spa_options');
		do_settings_sections('tbp_spa');
		?>

		<input name="Submit" type="submit" value="Save Changes" class="button-primary" />
	  </form>
  </div>
  </div>
  <div class="widget-liquid-right">
  <div id="widgets-right">
	<h3>Like This Plugin?</h3>
	<p>We have more. Check them out.</p>
	<ul>
		<li><a href="" target="_blank">RSSBrander</a> &mdash; Let your affiliates re-brand your RSS feeds</li>
		<li><a href="" target="_blank">MobiPages</a> &mdash; Create a mobile version of your site without switching themes.</li>
	</ul>
	<h3>Don't Miss Our New Plugins</h3>
	<div id="inapp-subscription"><script type="text/javascript" src="http://forms.aweber.com/form/94/1882260594.js"></script></div>
  </div>
  </div>
  </div>
  <?php
}
?>