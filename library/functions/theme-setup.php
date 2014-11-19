<?php

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;
	
/* ---------------------------------------------------------------------- */
/*	Setup wordpress theme support
/* ---------------------------------------------------------------------- */
	
add_action( 'after_setup_theme', 'sp_theme_setup' );
if( !function_exists('sp_theme_setup') )
{
	function sp_theme_setup(){
		
		// Makes theme available for translation.
		load_theme_textdomain( SP_TEXT_DOMAIN, get_template_directory() . '/languages' );

		// Add navigation menus
		register_nav_menus( array(
			'mobile'	=> __( 'Mobile Navigation', SP_TEXT_DOMAIN ),
			'primary'	=> __( 'Main Navigation', SP_TEXT_DOMAIN ),
			'footer'  	=> __( 'Footer Navigation', SP_TEXT_DOMAIN )
		) );
		
		// Add visual editor stylesheet support
		add_editor_style( SP_ASSETS_THEME . 'css/base.css');
	
		// Adds RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		// Add post formats
		add_theme_support( 'post-formats', array( 'audio', 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	
		// Add suport for post thumbnails and set default sizes
		add_theme_support( 'post-thumbnails' );
		//add_image_size('post-slider', 960, 425, true ); // base size 1280x768
		//add_image_size('thumb-medium', 200, 120, true ); // base size 1240x768

		// And HTML5 support
		add_theme_support( 'html5' );
		
	}

}

/* ---------------------------------------------------------------------- */
/*	Register and add styles and scripts for fontend
/* ---------------------------------------------------------------------- */
if( !function_exists('sp_frontend_scripts_styles') )
{
	if(!is_admin()){
		add_action('wp_enqueue_scripts', 'sp_frontend_scripts_styles'); //print Script and CSS
	}

	function sp_frontend_scripts_styles() {
		
		//Register CSS style
		wp_enqueue_style('gfont-opensans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('theme-info', SP_BASE_URL . 'style.css', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('fontello', SP_ASSETS_THEME . 'css/fontello.css', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('normalize', SP_ASSETS_THEME . 'css/normalize.css', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('base', SP_ASSETS_THEME . 'css/base.css', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('flexslider', SP_ASSETS_THEME . 'css/flexslider.css', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('flexslider-custom', SP_ASSETS_THEME . 'css/flexslider-custom.css', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('magnific-popup', SP_ASSETS_THEME . 'css/magnific-popup.css', false, SP_SCRIPTS_VERSION);
		wp_enqueue_style('layout', SP_ASSETS_THEME . 'css/layout.css', false, SP_SCRIPTS_VERSION);
		
		//Register scripts
		wp_enqueue_script('modernizr', SP_ASSETS_THEME . 'js/modernizr.js', array('jquery'), SP_SCRIPTS_VERSION, false);
		wp_enqueue_script('flexslider', SP_ASSETS_THEME . 'js/jquery.flexslider.js', array('jquery'), SP_SCRIPTS_VERSION, true);
		wp_enqueue_script('fitvideos', SP_ASSETS_THEME . 'js/jquery.fitvids.js', array('jquery'), SP_SCRIPTS_VERSION, true);
		wp_enqueue_script('magnific-popup', SP_ASSETS_THEME . 'js/jquery.magnific-popup.min.js', array('jquery'), SP_SCRIPTS_VERSION, false);
		//wp_enqueue_script('hammer', SP_ASSETS_THEME . 'js/hammer.js', array('jquery'), SP_SCRIPTS_VERSION, true);
		wp_enqueue_script('custom', SP_ASSETS_THEME . 'js/custom.js', array('jquery'), SP_SCRIPTS_VERSION, true);

		if ( is_singular() ) { wp_enqueue_script('sharrre', SP_ASSETS_THEME . 'js/jquery.sharrre.min.js', array('jquery'), SP_SCRIPTS_VERSION, true); }
		if ( is_singular() && comments_open() ) { wp_enqueue_script( 'comment-reply' ); }

		if ( ot_get_option('responsive') != 'off' ) {
			wp_enqueue_style('menu-mobile', SP_ASSETS_THEME . 'css/menu-mobile.css', false, SP_SCRIPTS_VERSION);
			wp_enqueue_style('responsive', SP_ASSETS_THEME . 'css/responsive.css', false, SP_SCRIPTS_VERSION);
			wp_enqueue_script('mobile-menu', SP_ASSETS_THEME . 'js/mobile-menu.js', array('jquery'), SP_SCRIPTS_VERSION, true);
		}

		wp_localize_script(
			'custom', 
			'theme_objects',
			array(
				'base' => get_template_directory_uri(),
				'commentProcess' => __('Processing your comment...', SP_TEXT_DOMAIN),
				'commentError' => __('You might have left one of the fields blank, or be posting too quickly.', SP_TEXT_DOMAIN),
				'commentSuccess' => __('Thanks for your response. Your comment will be published shortly after it\'ll be moderated.', SP_TEXT_DOMAIN)
			)
		);
	}
}

/* ---------------------------------------------------------------------- */
/*	Print customs css
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_print_ie_script') ){

	add_action('wp_head', 'sp_print_ie_script');
	
	function sp_print_ie_script(){
		echo '<!--[if lt IE 9]>'. "\n";
		echo '<script src="' . esc_url( SP_ASSETS_THEME . 'js/ie/html5.js' ) . '"></script>'. "\n";
		echo '<![endif]-->'. "\n";
	}
}

/* ---------------------------------------------------------------------- */
/*	Print customs css
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_print_custom_css_script') ){

	add_action('wp_head', 'sp_print_custom_css_script');
	
	function sp_print_custom_css_script(){
?>
	<style type="text/css">
		/* custom style */
	</style>

	<?php if ( is_page() || is_singular() ) : ?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
	    $('a[href*=".jpg"], a[href*=".jpeg"], a[href*=".png"], a[href*=".gif"]').each(function(){
	        if ($(this).parents('.gallery').length == 0) {
	            $(this).magnificPopup({
	                type:'image',
	                mainClass: 'mfp-with-zoom', // this class is for CSS animation below

					zoom: {
						enabled: true, // By default it's false, so don't forget to enable it

						duration: 300, // duration of the effect, in milliseconds
						easing: 'ease-in-out', // CSS transition easing function 

						// The "opener" function should return the element from which popup will be zoomed in
						// and to which popup will be scaled down
						// By defailt it looks for an image tag:
						opener: function(openerElement) {
						  // openerElement is the element on which popup was initialized, in this case its <a> tag
						  // you don't need to add "opener" option if this code matches your needs, it's defailt one.
						  return openerElement.is('img') ? openerElement : openerElement.find('img');
						}
					},
	                closeOnContentClick: true
	                });
	            }
	        });
	    	
	    	$('.entry-content .gallery').each(function() {
		        $(this).magnificPopup({
		            delegate: 'a',
		            type: 'image',
		            gallery: {enabled: true}
		            });
	        });
	    });

	</script>
	<?php endif; ?>
<?php		
	}

}

/* ---------------------------------------------------------------------- */
/*	Excerpt ending
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_excerpt_more' ) ) {

	add_filter( 'excerpt_more', 'sp_excerpt_more' );

	function sp_excerpt_more( $more ) {
		return '&#46;&#46;&#46;';
	}
	
}

/* ---------------------------------------------------------------------- */
/*	Excerpt length
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_excerpt_length' ) ) {

	add_filter( 'excerpt_length', 'sp_excerpt_length', 999 );

	function sp_excerpt_length( $length ) {
		return ot_get_option('excerpt-length',$length);
	}
	
}

function sp_gallery_atts( $out, $pairs, $atts ) {
	$atts = shortcode_atts( array(
	'columns' => '2',
	'size' => 'post-gallery',
	), $atts );
	 
	//$out['columns'] = $atts['columns'];
	$out['size'] = $atts['size'];
	 
	return $out;
 
}
add_filter( 'shortcode_atts_gallery', 'sp_gallery_atts', 10, 3 );

/* ---------------------------------------------------------------------- */
/*	Add User Browser and OS Classes in Body Class
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_browser_body_class') ) {
	function sp_browser_body_class($classes) {
	        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	        if($is_lynx) $classes[] = 'lynx';
	        elseif($is_gecko) $classes[] = 'gecko';
	        elseif($is_opera) $classes[] = 'opera';
	        elseif($is_NS4) $classes[] = 'ns4';
	        elseif($is_safari) $classes[] = 'safari';
	        elseif($is_chrome) $classes[] = 'chrome';
	        elseif($is_IE) {
	                $classes[] = 'ie';
	                if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
	                $classes[] = 'ie'.$browser_version[1];
	        } else $classes[] = 'unknown';
	        if($is_iphone) $classes[] = 'iphone';
	        if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
	                 $classes[] = 'osx';
	           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
	                 $classes[] = 'linux';
	           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
	                 $classes[] = 'windows';
	           }
	        return $classes;
	}
	add_filter('body_class','sp_browser_body_class');
}

/* ---------------------------------------------------------------------- */
/*	Show Mobile, Main and Footer navigation
/* ---------------------------------------------------------------------- */

/* Mobile navigation */ 
if( !function_exists('sp_mobile_navigation')) {

	function sp_mobile_navigation() {
		
		// set default main menu if wp_nav_menu not active
		if ( function_exists ( 'wp_nav_menu' ) ):
			$menu = wp_nav_menu( array(
					'container'      => false,
					'menu_id'		 => 'menu-mobile',
					'menu_class'	 => 'mobile-nav',
					'theme_location' => 'mobile',
					'fallback_cb' 	 => 'sp_mobile_nav_fallback',
					'echo'           => false,
					) );
			/* Adding "+" buttons for dropdown menus */
			$search = '<ul class="sub-menu">';
			$replace = '<span class="nav-child-container"><span class="nav-child-trigger">+</span></span>
						<ul class="sub-menu" style="height: 0;">';
			/*if ( wp_is_mobile() )						
				return str_replace($search, $replace, $menu);
			else
				return $menu;*/
			return str_replace($search, $replace, $menu);		
		else:
			sp_mobile_nav_fallback();	
		endif;
	}
}

if (!function_exists('sp_mobile_nav_fallback')) {
	
	function sp_mobile_nav_fallback() {
    	
		$menu_html = '<ul id="menu-mobile" class="mobile-nav">';
		$menu_html .= '<li><a href="'.admin_url('nav-menus.php').'">'.esc_html__('Add Mobile menu', SP_TEXT_DOMAIN).'</a></li>';
		$menu_html .= '</ul>';
		echo $menu_html;
		
	}
	
}

/* Main Navigation */
if( !function_exists('sp_main_navigation')) {

	function sp_main_navigation() {
		
		// set default main menu if wp_nav_menu not active
		if ( function_exists ( 'wp_nav_menu' ) )
			wp_nav_menu( array(
				'container'      => false,
				'menu_id'	 	 => 'menu-primary',
				'menu_class'	 => 'primary-nav',
				'theme_location' => 'primary',
				'fallback_cb' 	 => 'sp_main_nav_fallback'
				) );
		else
			sp_main_nav_fallback();	
	}
}

if (!function_exists('sp_main_nav_fallback')) {
	
	function sp_main_nav_fallback() {
    	
		$menu_html = '<ul class="primary-nav">';
		$menu_html .= '<li><a href="'.admin_url('nav-menus.php').'">'.esc_html__('Add Main menu', SP_TEXT_DOMAIN).'</a></li>';
		$menu_html .= '</ul>';
		echo $menu_html;
		
	}
	
}

/* Footer navigation */
if( !function_exists('sp_footer_navigation')) {

	function sp_footer_navigation() {
		
		// set default footer menu if wp_nav_menu not active
		if ( function_exists ( 'wp_nav_menu' ) )
			wp_nav_menu( array(
				'container'      => false,
				'menu_id'		 => 'menu-footer',
				'menu_class'	 => 'footer-nav',
				'theme_location' => 'footer',
				'fallback_cb' => 'sp_footer_nav_fallback'
				) );
		else
			sp_footer_nav_fallback();	
	}
}

if (!function_exists('sp_footer_nav_fallback')) {
	
	function sp_footer_nav_fallback() {
    	
		$menu_html = '<ul class="footer-nav">';
		$menu_html .= '<li><a href="'.admin_url('nav-menus.php').'">'.esc_html__('Add footer menu', SP_TEXT_DOMAIN).'</a></li>';
		$menu_html .= '</ul>';
		echo $menu_html;
		
	}
	
}

/* ---------------------------------------------------------------------- */
/*	Makes some changes to the <title> tag, by filtering the output of wp_title()
/* ---------------------------------------------------------------------- */

if( !function_exists('sp_filter_wp_title')) {

	add_filter('wp_title', 'sp_filter_wp_title', 10, 2);

	function sp_filter_wp_title( $title, $separator ) {

		if ( is_feed() ) return $title;

		global $paged, $page;

		if ( is_search() ) {
			$title = sprintf(__('Search results for %s', SP_TEXT_DOMAIN), '"' . get_search_query() . '"');

			if ( $paged >= 2 )
				$title .= " $separator " . sprintf(__('Page %s', SP_TEXT_DOMAIN), $paged);

			$title .= " $separator " . get_bloginfo('name', 'display');

			return $title;
		}

		$title .= get_bloginfo('name', 'display');
		$site_description = get_bloginfo('description', 'display');

		if ( $site_description && ( is_home() || is_front_page() ) )
			$title .= " $separator " . $site_description;

		if ( $paged >= 2 || $page >= 2)
			$title .= " $separator " . sprintf(__('Page %s', SP_TEXT_DOMAIN), max($paged, $page) );

		return $title;

	}

}		

/* ---------------------------------------------------------------------- */
/*	Visual editor improvment
/* ---------------------------------------------------------------------- */

if ( is_admin() ) {
	add_filter( 'mce_buttons', 'sp_add_buttons_row1' );
	add_filter( 'mce_buttons_2', 'sp_add_buttons_row2' );
}
	
/*
* Add buttons to visual editor first row
*
* $buttons = ARRAY [default WordPress visual editor buttons array]
*/
if ( ! function_exists( 'sp_add_buttons_row1' ) ) {
	function sp_add_buttons_row1( $buttons ) {
		//inserting buttons after "italic" button
		$pos = array_search( 'italic', $buttons, true );
		if ( $pos != false ) {
			$add = array_slice( $buttons, 0, $pos + 1 );
			$add[] = 'underline';
			$buttons = array_merge( $add, array_slice( $buttons, $pos + 1 ) );
		}

		//inserting buttons after "justifyright" button
		$pos = array_search( 'justifyright', $buttons, true );
		if ( $pos != false ) {
			$add = array_slice( $buttons, 0, $pos + 1 );
			$add[] = 'justifyfull';
			$buttons = array_merge( $add, array_slice( $buttons, $pos + 1 ) );
		}
		
		return $buttons;
	}
} // /sp_add_buttons_row1

/*
* Add buttons to visual editor second row
*
* $buttons = ARRAY [default WordPress visual editor buttons array]
*/
if ( ! function_exists( 'sp_add_buttons_row2' ) ) {
	function sp_add_buttons_row2( $buttons ) {
		//inserting buttons before "underline" button
		$pos = array_search( 'underline', $buttons, true );
		if ( $pos != false ) {
			$add = array_slice( $buttons, 0, $pos );
			$add[] = 'removeformat';
			$add[] = '|';
			$buttons = array_merge( $add, array_slice( $buttons, $pos + 1 ) );
		}

		//remove "justify full" button from second row
		$pos = array_search( 'justifyfull', $buttons, true );
		if ( $pos != false ) {
			unset( $buttons[$pos] );
			$add = array_slice( $buttons, 0, $pos + 1 );
			$add[] = '|';
			$add[] = 'sub';
			$add[] = 'sup';
			$add[] = '|';
			$buttons = array_merge( $add, array_slice( $buttons, $pos + 1 ) );
		}

		return $buttons;
	}
} // sp_add_buttons_row2

/* ---------------------------------------------------------------------- */
/*	Customizable login screen and WordPress admin area
/* ---------------------------------------------------------------------- */

// Custom logo login
add_action('login_head', 'sp_custom_login_logo');
function sp_custom_login_logo() {
	
	$custom_logo = '';
	$out = '';
	if (ot_get_option('custom-logo')) {
		$custom_logo = ot_get_option('custom-logo');
	}
	$out .='<style type="text/css">';
	$out .='body.login{ background-color:#ffffff; }';
	if ($custom_logo) {	
	    $out .='.login h1 a { background-image:url('.ot_get_option('custom-logo').') !important; height: 95px!important; width: 100%!important; background-size: auto!important;}';
	} else {
		$out .='.login h1 a { background-image:url('.get_template_directory_uri().'/assets/images/logo.png) !important; height: 95px!important; width: 100%!important; background-size: auto!important;}';
	}
	$out .='</style>';
	echo $out;
}

// Remove wordpress link on admin login logo
add_filter('login_headerurl', 'sp_remove_link_on_admin_login_info');
function sp_remove_link_on_admin_login_info() {
     return  get_bloginfo('url');
}

// Change login logo title
add_filter('login_headertitle', 'sp_change_loging_logo_title');
function sp_change_loging_logo_title(){
	return 'Go to '.get_bloginfo('name').' Homepage';
}

//	Remove logo and other items in Admin menu bar
add_action( 'wp_before_admin_bar_render', 'sp_remove_admin_bar_links' );
function sp_remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('wp-logo');
}

//  Remove wordpress version generation
add_filter('the_generator', 'sp_remove_version_info');
function sp_remove_version_info() {
     return '';
}

//  Clean up wp_head()
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'start_post_rel_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

//  Set favicons for backend code
add_action( 'admin_head', 'sp_adminfavicon' );
function sp_adminfavicon() {
	echo '<link rel="shortcut icon" type="image/x-icon" href="'.ot_get_option('favicon').'" />'."\n";
}

//
add_filter( 'wp_head', 'sp_head_meta', 0 );
function sp_head_meta() { 
	// Vars
	$output = '';
	$favicon = ot_get_option('custom-favicon');
	$iphone_icon = ot_get_option('custom-iphone-icon57');
	$ipad_icon = ot_get_option('custom-ipad-icon72');
	
	// Favicon
	if ( $favicon ) {
		$output .= '<!-- Favicon -->';
		$output .= '<link rel="shortcut icon" href="'. $favicon .'">';
	}
	
	// Apple iPhone Icon
	if ( $iphone_icon ) {
		$output .= '<!-- Apple iPhone Icon -->';
		$output .= '<link rel="apple-touch-icon-precomposed" href="'. $iphone_icon .'">';
	}
	
	// Apple iPad Icon
	if ( $ipad_icon ) {
		$output .= '<!-- Apple iPhone Icon -->';
		$output .= '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="'. $ipad_icon .'">';
	}
	
	echo $output;	
}

