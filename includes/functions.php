<?php
// Validates options
function tbp_spa_validate_options( $input ) {
  $valid = array();

	// Validate textarea
    $valid['fallback_ad'] = addslashes($input['fallback_ad']);
	
	// Validate radio options
	if( isset( $input['placement'] ) ) {
	  switch( $input['placement'] ) {
			case "top" :
			$valid['placement'] = "top";
			break;
			
			case "bottom" :
			$valid['placement'] = "bottom";
			break;
			
			case "manual" :
			$valid['placement'] = "manual";
			break;
		}
	}

  return $valid;
}

//hook to add a meta box
add_action( 'add_meta_boxes', 'tbp_spa_mbe_create' );

function tbp_spa_mbe_create() {

	//create a custom meta box
	add_meta_box( 'tbp-spa-meta', 'Single Post Ad', 'tbp_spa_mbe_function', 'post', 'normal', 'high' );

}

function tbp_spa_mbe_function( $post ) {

	//retrieve the meta data values if they exist
	$tbp_spa_mbe_ad = get_post_meta( $post->ID, '_tbp_spa_mbe_ad', true );
	?>
	<p>Want to show an ad just for this post? Paste ad code here</p>
	<textarea type="text" name="tbp_spa_mbe_ad" style="height: 4em; width: 98%"><?php echo stripslashes( $tbp_spa_mbe_ad ); ?></textarea>
	<?php
}

//hook to save the meta box data
add_action( 'save_post', 'tbp_spa_mbe_save_meta' );

function tbp_spa_mbe_save_meta( $post_id ) {

	//verify the meta data is set
	if ( isset( $_POST['tbp_spa_mbe_ad'] ) ) {
	
		//save the meta data
		update_post_meta( $post_id, '_tbp_spa_mbe_ad', addslashes( $_POST['tbp_spa_mbe_ad'] ) );
	
	}
}

/*
 * Widget to display single post ad
 */
// use widgets_init action hook
add_action( 'widgets_init', 'tbp_spa_register_widget' );

// register our widget
function tbp_spa_register_widget() {
	register_widget( 'tbp_spa_widg_display' );
}

// setup widget class
class tbp_spa_widg_display extends WP_Widget{
	// process the new widget
	function tbp_spa_widg_display() {
		$widget_ops = array(
			'classname' => 'tbp_spa_widg_class',
			'description' => 'Display single post ad'
		);
		$this->WP_Widget( 'tbp_spa_widg_display', 'Single Post Ad Widget', $widget_ops );
	}
	
	// build widget settings form
	function form( $instance ) {
		$defaults = array( 'title' => 'Ad' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		?>
		<p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		<?php
	}
	
	// save widget settings
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}
	
	// display the ad widget
	function widget( $args, $instance ) {
		extract( $args );
		global $post;
		$singlepostad = stripslashes( get_post_meta( $post->ID, '_tbp_spa_mbe_ad', true ) );
		$opt = get_option('tbp_spa_options');
		$fallback_ad = stripslashes( $opt['fallback_ad'] );
		
		// if ad placement is manual, then setup widget
		if( $opt['placement'] == 'manual' || empty( $opt['placement'] ) ) {
		if ( !empty( $singlepostad ) || !empty( $fallback_ad ) ) {
		
			echo $before_widget;
			$title = apply_filters( 'widget_title', $instance['title'] );
			
			if( !empty( $title ) && !empty( $singlepostad ) ) {
				echo $before_title . $title . $after_title;
			} elseif ( !empty( $title ) && !empty( $fallback_ad ) ) {
				echo $before_title . $title . $after_title;
			}
			if( !empty( $singlepostad ) ) {
				echo $singlepostad;
			} elseif ( empty( $singlepostad ) ) {
				echo $fallback_ad;
			}
			
			echo $after_widget;
		}
	}
}
}

/*
 * Display add automatically before or after content
 */
// Trigger ad before content
add_filter( 'the_content', 'tbp_spa_before_content' );
// Function to display ad before content
function tbp_spa_before_content( $content ) {
	// Get post data
	global $post;
	$singlepostad = stripslashes( get_post_meta( $post->ID, '_tbp_spa_mbe_ad', true ) );	
	// Get plugin options
	$opt = get_option('tbp_spa_options');	
	$fallback_ad = stripslashes($opt['fallback_ad']);
	$placement = $opt['placement'];
	
	// Define ad
	$ad = ( !empty($singlepostad) ? $singlepostad : $fallback_ad );
	
	if( is_single() && $placement == 'top' ) {
		return "<div id='single-post-ads'>". $ad ."</div>". $content;
	} elseif( is_single() && $placement == 'bottom' ) {
		return $content . "<div id='single-post-ads'>". $ad ."</div>";
	} else {
		return $content;
	}
}

/*
 * Display single post ad using shortcode tbpspa
 */
 function tbp_spa_shortcode( $atts ) {
	global $post;
	$singlepostad = stripslashes( get_post_meta( $post->ID, '_tbp_spa_mbe_ad', true ) );	
	// Get plugin options
	$opt = get_option('tbp_spa_options');	
	$fallback_ad = stripslashes($opt['fallback_ad']);
	
	// Define ad
	$ad = ( !empty($singlepostad) ? $singlepostad : $fallback_ad );
	
	if( is_single() ) {
		return "<div id='single-post-ads'>". $ad ."</div>";
	}
 }
add_shortcode( 'tbpspa', 'tbp_spa_shortcode' );
?>