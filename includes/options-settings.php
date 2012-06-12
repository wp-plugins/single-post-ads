<?php
/*
 * Holds the settings fields stuff
 */
// Option parameters for all sections
function tbp_spa_admin_sectionfields() {
  $tabs = array(
	'tbp_spa' => array(
		'name' => 'tbp_spa',
        'title' => 'Ad Options',
        'sections' => array(
			'default_ad' => array(
				'name' => 'default_ad',
				'title' => 'Default Ad',
				'description' => 'If the post has no ads, this ad will be displayed'
			)                        
		)
	)
    );
    return $tabs;
}

/**
 * Call add_settings_section() for each Settings
 *
 * Loop through each Theme Settings page tab, and add
 * a new section to the Theme Settings page for each
 * section specified for each tab.
 *
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_section	Codex Reference: add_settings_section()
 *
 * @param	string		$sectionid	Unique Settings API identifier; passed to add_settings_field() call
 * @param	string		$title		Title of the Settings page section
 * @param	callback	$callback	Name of the callback function in which section text is output
 * @param	string		$pageid		Name of the Settings page to which to add the section; passed to do_settings_sections()
 */
$tbp_spa_admin_sectionfields = tbp_spa_admin_sectionfields();
foreach ( $tbp_spa_admin_sectionfields as $tab ) {
  $tabname = $tab['name'];
  $tabsections = $tab['sections'];
  foreach ($tabsections as $section) {
    if( isset($section['name']))
      $sectionname = $section['name'];
    if( isset ($section['title']))
      $sectiontitle = $section['title'];
    if( isset ($section['description']))
      $sectiondesc = $section['description'];
    // Add settings section
    add_settings_section(
      //id
      'tbp_spa'.$sectionname,
      //title
      $sectiontitle,
      //callback
      'tbp_spa_sections_callback',
      //pageid
      $tabname
    );
  }
}

/**
 * Callback for add_settings_section()
 *
 * Generic callback to output the section text
 * for each Plugin settings section.
 *
 * @param	array	$section_passed	Array passed from add_settings_section()
 */
function tbp_spa_sections_callback($section_passed) {
  $tbp_spa_admin_sectionfields = tbp_spa_admin_sectionfields();
  foreach ($tbp_spa_admin_sectionfields as $tabname => $tab) {
    $tabsections = $tab['sections'];
    foreach ($tabsections as $sectionname => $section) {
      if ('tbp_spa'.$sectionname == $section_passed['id']) {
      ?>
      <p><?php if( isset( $section['description'] )) {
          echo $section['description'];
        } ?></p>
      <?php
      }
    }
  }
}

/**
 * Single Post Ads Options
 * This array holds parameters for all options of this plugin.
 * The 'type' key is used to generate proper form field markup
 * The 'tab' key determinds the Settings Page on which the
 * options appears and the 'section' tab determines the section
 * of the Settings Page tab which the options appears
 *
 * @return array $options array of arrays of option parameters
 */
function tbp_spa_get_option_parameters() {
  $options = array(
		'fallback_ad' => array(
          'name' => 'fallback_ad',
          'title' => 'Fallback to this ad',
          'description' => 'Enter ad code here',
          'section' => 'default_ad',
          'tab' => 'tbp_spa',
          'default' => '',
          'type' => 'tarea'
          ),
		'placement' => array(
          'name' => 'placement',
          'title' => 'Ad placement',
          'description' => 'You can let the plugin automatically place the ad or do it manually using the [tbpspa] shortcode',
          'section' => 'default_ad',
          'tab' => 'tbp_spa',
          'default' => 'bottom',
		  'type' => 'radio',
		  'valid_options' => array(
			'top' => array(
				'name' => 'top',
				'title' => 'Before content'
			),
			'bottom' => array(
				'name' => 'bottom',
				'title' => 'After content'
			),
			'manual' => array(
				'name' => 'manual',
				'title' => 'Manual'
			)
			)
		),
  );
  return $options;
}

/**
 * Call add_settings_field() for each Setting Field
 *
 * Loop through each option, and add a new
 * setting field to the Settings page for each
 * setting.
 *
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_field	Codex Reference: add_settings_field()
 *
 * @param	string		$settingid	Unique Settings API identifier; passed to the callback function
 * @param	string		$title		Title of the setting field
 * @param	callback	$callback	Name of the callback function in which setting field markup is output
 * @param	string		$pageid		Name of the Settings page to which to add the setting field; passed from add_settings_section()
 * @param	string		$sectionid	ID of the Settings page section to which to add the setting field; passed from add_settings_section()
 * @param	array		$args		Array of arguments to pass to the callback function
 */
$option_parameters = tbp_spa_get_option_parameters();
$callback = 'tbp_spa_setting_callback';

foreach ($option_parameters as $option) {
  $optionname = $option['name'];
	$optiontitle = $option['title'];
	$optiontab = $option['tab'];
	$optionsection = $option['section'];
	$optiontype = $option['type'];
  // Add settings field
  	add_settings_field(
  	   //id
  	   'tbp_spa' . $optionname,
  	   // title
  	   $optiontitle,
  	   // callback
  	     $callback,
  	   // page id
  	   $optiontab,
  	   // section id
  	   'tbp_spa'.$optionsection,
  	   // args
  	   $option
  	);
}

/**
 * Callback for get_settings_field - outputs form fields
 */
function tbp_spa_setting_callback($option) {
  $opt = get_option('tbp_spa_options');
  $option_parameters = tbp_spa_get_option_parameters();
  $optionname = $option['name'];
  $optiontitle = $option['title'];
  $optiondescription = $option['description'];
  $opt2 = $option['2ndopt'];
  $opt2name = $option['2ndopt']['name'];
	$fieldtype = $option['type'];
	$fieldsize = $option['size'];
	$fieldname = 'tbp_spa_options[' . $optionname . ']';

	// Output text area field markup
	if ( 'tarea' == $fieldtype ) {
    ?>
    <textarea name="<?php echo $fieldname; ?>" cols="60" rows="7"><?php echo stripslashes( $opt[$optionname] ); ?></textarea>
    <?php
	}
	// Output radio button form field markup
	else if ( 'radio' == $fieldtype ) {
		$valid_options = array();
		$valid_options = $option['valid_options'];
		foreach ( $valid_options as $valid_option ) {
			?>
			<input type="radio" name="<?php echo $fieldname; ?>" <?php checked($opt[$optionname], $valid_option['name'] ); ?> value="<?php echo $valid_option['name']; ?>" />
			<span>
			<?php echo $valid_option['title']; ?>
			<?php if ( $valid_option['description'] ) { ?>
				<span style="padding-left:5px;"><em><?php echo $valid_option['description']; ?></em></span>
			<?php } ?>
			</span>
			<?php
		}
	}
  
	// Output the setting description
	?>
	<br /><span class="description"><?php echo $optiondescription; ?></span>
	<?php
}
?>