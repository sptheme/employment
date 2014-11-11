<?php

/* ---------------------------------------------------------------------- */
/*	Register sidebars
/* ---------------------------------------------------------------------- */
function sp_widgets_init() {
	
	// Default Sidebar
	register_sidebar( array(
		'name' 			=> __( 'Default Sidebar', 'sptheme_admin' ),
		'id' 			=> 'default-sidebar',
		'description' 	=> __( 'Drag widgets into this sidebar', 'sptheme_admin' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<div class="widget-title"><h4>',
		'after_title' 	=> '</h4></div>',
	) );

	// Footer Sidebar 1
	register_sidebar( array(
		'name' 			=> __( 'Footer Sidebar 1', 'sptheme_admin' ),
		'id' 			=> 'footer-sidebar-1',
		'description' 	=> __( 'Drag widgets into this sidebar', 'sptheme_admin' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "</div>",
		'before_title' 	=> '<div class="widget-title"><h4>',
		'after_title' 	=> '</h4></div>',
	) );

	// Footer Sidebar 2
	register_sidebar( array(
		'name' 			=> __( 'Footer Sidebar 2', 'sptheme_admin' ),
		'id' 			=> 'footer-sidebar-2',
		'description' 	=> __( 'Drag widgets into this sidebar', 'sptheme_admin' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "</div>",
		'before_title' 	=> '<div class="widget-title"><h4>',
		'after_title' 	=> '</h4></div>',
	) );

	// Footer Sidebar 3
	register_sidebar( array(
		'name' 			=> __( 'Footer Sidebar 3', 'sptheme_admin' ),
		'id' 			=> 'footer-sidebar-3',
		'description' 	=> __( 'Drag widgets into this sidebar', 'sptheme_admin' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' 	=> "</div>",
		'before_title' 	=> '<div class="widget-title"><h4>',
		'after_title' 	=> '</h4></div>',
	) );

}
add_action('widgets_init', 'sp_widgets_init');

// Addon widgets		
load_template ( SP_BASE_DIR . 'library/widgets/widget-social-networks.php' );
load_template ( SP_BASE_DIR . 'library/widgets/widget-enewsletter.php' );
load_template ( SP_BASE_DIR . 'library/widgets/widget-quick-contact.php' );
load_template ( SP_BASE_DIR . 'library/widgets/widget-post-category.php' );
load_template ( SP_BASE_DIR . 'library/widgets/widget-fb-likebox.php' );
load_template ( SP_BASE_DIR . 'library/widgets/widget-video.php' );
load_template ( SP_BASE_DIR . 'library/widgets/widget-photogallery.php' );

/* ---------------------------------------------------------------------- */
/*	Register custom sidebars
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_custom_sidebars' ) ) {

	function sp_custom_sidebars() {
		if ( !ot_get_option('sidebar-areas') =='' ) {
			
			$sidebars = ot_get_option('sidebar-areas', array());
			
			if ( !empty( $sidebars ) ) {
				foreach( $sidebars as $sidebar ) {
					if ( isset($sidebar['title']) && !empty($sidebar['title']) && isset($sidebar['id']) && !empty($sidebar['id']) && ($sidebar['id'] !='sidebar-') ) {
						register_sidebar(array('name' => ''.$sidebar['title'].'','id' => ''.strtolower($sidebar['id']).'','before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<div class="widget-title"><h4>','after_title' => '</h4></div>'));
					}
				}
			}
		}
	}
	
}
add_action( 'widgets_init', 'sp_custom_sidebars' );

/* ---------------------------------------------------------------------- */
/*	Layout choice
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_layout_class' ) ) {
	
	function sp_layout_class() {
		// Default layout
		$layout = 'col-2cr';
		$default = 'col-2cr';

		// Check for page/post specific layout
		if ( is_page() || is_single() ) {
			// Reset post data
			wp_reset_postdata();
			global $post;
			// Get meta
			$meta = get_post_meta($post->ID,'sp_layout',true);
			// Get if set and not set to inherit
			if ( isset($meta) && !empty($meta) && $meta != 'inherit' ) { $layout = $meta; }
			// Else check for page-global / single-global
			elseif ( is_single() && ( ot_get_option('layout-single') !='inherit' ) ) $layout = ot_get_option('layout-single',''.$default.'');
			elseif ( is_page() && ( ot_get_option('layout-page') !='inherit' ) ) $layout = ot_get_option('layout-page',''.$default.'');
			// Else check for custom post
			elseif ( is_singular('team') && ( ot_get_option('layout-team') !='inherit' ) ) $layout = ot_get_option('layout-team',''.$default.'');
			elseif ( is_singular('gallery') && ( ot_get_option('layout-gallery') !='inherit' ) ) $layout = ot_get_option('layout-gallery',''.$default.'');
			// Else get global option
			else $layout = ot_get_option('layout-global',''.$default.'');
		}
		
		// Set layout based on page
		elseif ( is_home() && ( ot_get_option('layout-home') !='inherit' ) ) $layout = ot_get_option('layout-home',''.$default.'');
		elseif ( is_category() && ( ot_get_option('layout-archive-category') !='inherit' ) ) $layout = ot_get_option('layout-archive-category',''.$default.'');
		elseif ( is_archive() && ( ot_get_option('layout-archive') !='inherit' ) ) $layout = ot_get_option('layout-archive',''.$default.'');
		elseif ( is_search() && ( ot_get_option('layout-search') !='inherit' ) ) $layout = ot_get_option('layout-search',''.$default.'');
		elseif ( is_404() && ( ot_get_option('layout-404') !='inherit' ) ) $layout = ot_get_option('layout-404',''.$default.'');
		
		// Global option
		else $layout = ot_get_option('layout-global',''.$default.'');
		
		// Return layout class
		return $layout;
	}
	
}

/* ---------------------------------------------------------------------- */
/*	Sidebar choice
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_sidebar_primary' ) ) {
	
	function sp_sidebar_primary() {
		// Default sidebar
		$sidebar = 'default-sidebar';

		// Set sidebar based on page
		if ( is_home() && ot_get_option('s1-home') ) $sidebar = ot_get_option('s1-home');
		if ( is_single() && ot_get_option('s1-single') ) $sidebar = ot_get_option('s1-single');
		if ( is_singular('team') && ot_get_option('s1-team') ) $sidebar = ot_get_option('s1-team');
		if ( is_singular('gallery') && ot_get_option('s1-gallery') ) $sidebar = ot_get_option('s1-gallery');
		if ( is_archive() && ot_get_option('s1-archive') ) $sidebar = ot_get_option('s1-archive');
		if ( is_category() && ot_get_option('s1-archive-category') ) $sidebar = ot_get_option('s1-archive-category');
		if ( is_search() && ot_get_option('s1-search') ) $sidebar = ot_get_option('s1-search');
		if ( is_404() && ot_get_option('s1-404') ) $sidebar = ot_get_option('s1-404');
		if ( is_page() && ot_get_option('s1-page') ) $sidebar = ot_get_option('s1-page');

		// Check for page/post specific sidebar
		if ( is_page() || is_single() ) {
			// Reset post data
			wp_reset_postdata();
			global $post;
			// Get meta
			$meta = get_post_meta($post->ID,'sp_sidebar_primary',true);
			if ( $meta ) { $sidebar = $meta; }
		}

		// Return sidebar
		return $sidebar;
	}
	
}

/* ---------------------------------------------------------------------- */
/*	Add layout option in body class
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_layout_option_body_class' ) ) {

	function sp_layout_option_body_class( $classes ) {
		$classes[] = sp_layout_class();
		return $classes;
	}
	
}
add_filter( 'body_class', 'sp_layout_option_body_class' );