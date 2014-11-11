<?php

add_action( 'widgets_init', 'sp_quick_contact_widget' );
function sp_quick_contact_widget() {
	register_widget( 'sp_widget_quick_contact' );
}

/*
*****************************************************
*      WIDGET CLASS
*****************************************************
*/

class sp_widget_quick_contact extends WP_Widget {
	/*
	*****************************************************
	* widget constructor
	*****************************************************
	*/
	function __construct() {
		$id     = 'sp-widget-quick-contact';
		$prefix = SP_THEME_NAME . ': ';
		$name   = '<span>' . $prefix . __( 'Quick Contact', 'sptheme_widget' ) . '</span>';
		$widget_ops = array(
			'classname'   => 'sp-widget-quick-contact',
			'description' => __( 'A widget to present quick contact information','sptheme_widget' )
			);
		$control_ops = array();

		//$this->WP_Widget( $id, $name, $widget_ops, $control_ops );
		parent::__construct( $id, $name, $widget_ops, $control_ops );
		
	}
		
		
	function widget( $args, $instance) {
		extract ($args);
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title']);
		$address = $instance['address'];
		$email = $instance['email'];
		$tel = $instance['tel'];
		$phone = $instance['phone'];
		
		/* Before widget (defined by themes). */
		$out = $before_widget;
		
		/* Title of widget (before and after define by theme). */
		if ( $title )
			$out .= $before_title . $title . $after_title;

		$out .= '<ul>';
		$out .= '<li class="address">' . esc_attr( $address ) . '</li>';
		if ( $tel )
			$out .= '<li class="tel">' . esc_attr( $tel ) . '</li>';
		if ( $phone )
			$out .= '<li class="phone">' . esc_attr( $phone ) . '</li>';
		$out .= '<li class="email"><a href="mailto:' . antispambot($email) . '">' . antispambot($email) . '</a></li>';
		$out .= '</ul>';
	
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
		$instance['address'] = strip_tags( $new_instance['address'] );
		$instance['email'] = strip_tags( $new_instance['email'] );
		$instance['tel'] = strip_tags( $new_instance['tel'] );
		$instance['phone'] = strip_tags( $new_instance['phone'] );
		
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */	
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => 'Office address', 
			'address' => 'No. 29B, Mao Tse Toung Blvd (245), Sangkat Boeung Keng Kang I, Khan Chamkar Morn, 12302, Phnom Penh, CAMBODIA.', 
			'email' => 'info@novacambodia.com',
			'tel' => '+855 (0) 23 223 577',
			'phone' => '+855 (0) 12 825 646');
		$instance = wp_parse_args( (array) $instance, $defaults); ?>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'sptheme_widget') ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"  class="widefat">
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e('Address:', 'sptheme_widget') ?></label>
		<textarea id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" class="widefat" rows="5"><?php echo $instance['address']; ?></textarea> 
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('Email:', 'sptheme_widget') ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" class="widefat">
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'tel' ); ?>"><?php _e('Telephone:', 'sptheme_widget') ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'tel' ); ?>" name="<?php echo $this->get_field_name( 'tel' ); ?>" value="<?php echo $instance['tel']; ?>" class="widefat">
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e('Phone:', 'sptheme_widget') ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $instance['phone']; ?>" class="widefat">
		</p>

        
	   <?php 
    }
} //end class
?>