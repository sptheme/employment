<?php

add_action( 'widgets_init', 'sp_photogallery_widget' );
function sp_photogallery_widget() {
	register_widget( 'sp_widget_photogallery' );
}

/*
*****************************************************
*      WIDGET CLASS
*****************************************************
*/

class sp_widget_photogallery extends WP_Widget {
	/*
	*****************************************************
	* widget constructor
	*****************************************************
	*/
	function __construct() {
		$id     = 'sp-widget-photogallery';
		$prefix = SP_THEME_NAME . ': ';
		$name   = '<span>' . $prefix . __( 'Photo Gallery', 'sptheme_widget' ) . '</span>';
		$widget_ops = array(
			'classname'   => 'sp-widget-photogallery',
			'description' => __( 'A widget to present photo gallery','sptheme_widget' )
			);
		$control_ops = array();

		//$this->WP_Widget( $id, $name, $widget_ops, $control_ops );
		parent::__construct( $id, $name, $widget_ops, $control_ops );
		
	}
		
		
	function widget( $args, $instance) {
		extract ($args);
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title']);
		$album = $instance['album'];
		$photo_num = $instance['photo_num'];
		
		/* Before widget (defined by themes). */
		$out = $before_widget;
		
		/* Title of widget (before and after define by theme). */
		if ( $title )
			$out .= $before_title . $title . $after_title;

		$out .= sp_get_album_gallery( $album, $photo_num );
	
		/* After widget (defined by themes). */		
		$out .= $after_widget;

		echo $out;
	}	
	
	/**
	 * Update the widget settings.
	 */	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['album'] = strip_tags( $new_instance['album'] );
		$instance['photo_num'] = strip_tags( $new_instance['photo_num'] );

		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */	
	function form( $instance ) {
		global $post;
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => 'Photo Gallery', 
			'photo_num' => '10');
		$instance = wp_parse_args( (array) $instance, $defaults); 

		$albums = get_posts( array('posts_per_page' => -1, 'post_type' => 'gallery') );

		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'sptheme_widget') ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"  class="widefat">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'album' ); ?>">Select album: </label>
			<select id="<?php echo $this->get_field_id( 'album' ); ?>" name="<?php echo $this->get_field_name( 'album' ); ?>">
				<?php foreach ( $albums as $image ) : setup_postdata( $image ); ?>
				<option value="<?php echo $image->ID; ?>" <?php if ( $instance['album'] == $image->ID ) { echo ' selected="selected"' ; } ?>><?php echo $image->post_title; ?></option>
				<?php endforeach; wp_reset_postdata(); ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'photo_num' ); ?>">Number of photos: </label>
			<input id="<?php echo $this->get_field_id( 'photo_num' ); ?>" name="<?php echo $this->get_field_name( 'photo_num' ); ?>" value="<?php echo $instance['photo_num']; ?>" type="text" size="3" />
		</p>
        
	   <?php 
    }
} //end class
?>