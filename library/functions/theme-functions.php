<?php


/* ---------------------------------------------------------------------- */
/* Show language list on header
/* ---------------------------------------------------------------------- */
if( !function_exists('languages_list_header')) {

	function languages_list_header(){
		if(function_exists('icl_get_languages')) {
			$languages = icl_get_languages('skip_missing=0&orderby=code');
			if(!empty($languages)){
				echo '<div class="language"><ul>';
				echo '<li>' . __('Language: ', 'sptheme') . '</li>';
				foreach($languages as $l){
					echo '<li class="'.$l['language_code'].'">';

					if(!$l['active']) echo '<a href="'.$l['url'].'" title="' . $l['native_name'] . '">';
					echo '<img src="' . $l['country_flag_url'] . '" alt="' . $l['native_name'] . '" />';
					if(!$l['active']) echo '</a>';

					echo '</li>';
				}
				echo '</ul></div>';
			}
		} else {
			return null; // Activate WMPL plugin
		}
	}

}

/* ---------------------------------------------------------------------- */
/*	Get images attached to post
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_post_images' ) ) {

	function sp_post_images( $args=array() ) {
		global $post;

		$defaults = array(
			'numberposts'		=> -1,
			'order'				=> 'ASC',
			'orderby'			=> 'menu_order',
			'post_mime_type'	=> 'image',
			'post_parent'		=>  $post->ID,
			'post_type'			=> 'attachment',
		);

		$args = wp_parse_args( $args, $defaults );

		return get_posts( $args );
	}
	
}

/* ---------------------------------------------------------------------- */
/*	Get images attached info by attached id
/* ---------------------------------------------------------------------- */
function wp_get_attachment( $attachment_id ) {

	$attachment = get_post( $attachment_id );
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
}

/* ---------------------------------------------------------------------- */
/*	Get thumbnail post
/* ---------------------------------------------------------------------- */
if( !function_exists('sp_post_thumbnail') ) {

	function sp_post_thumbnail( $size = 'thumbnail'){
			global $post;
			$thumb = '';

			//get the post thumbnail;
			$thumb_id = get_post_thumbnail_id($post->ID);
			$thumb_url = wp_get_attachment_image_src($thumb_id, $size);
			$thumb = $thumb_url[0];
			if ($thumb) return $thumb;
	}		

}

/* ---------------------------------------------------------------------- */
/*	Start content wrap
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_start_content_wrap') ) {

	add_action( 'sp_start_content_wrap_html', 'sp_start_content_wrap' );

	function sp_start_content_wrap() {
		echo '<section id="content"><div class="container clearfix">';
	}
	
}

/* ---------------------------------------------------------------------- */
/*	End content wrap
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_end_content_wrap') ) {

	add_action( 'sp_end_content_wrap_html', 'sp_end_content_wrap' );

	function sp_end_content_wrap() {
		echo '</div></section> <!-- #content .container .clearfix -->';
	}

}

/* ---------------------------------------------------------------------- */
/*	Thumnail for social share
/* ---------------------------------------------------------------------- */

if ( !function_exists('sp_facebook_thumb') ) {

	function sp_facebook_thumb() {
		if ( is_singular( 'sp_work' ) ) {
			global $post;

			$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
			echo '<meta property="og:image" content="' . esc_attr($thumbnail_src[0]) . '" />';
		}
	}

	add_action('wp_head', 'sp_facebook_thumb');
}


/* ---------------------------------------------------------------------- */               							
/*  Retrieve the terms list and return array
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_get_terms_list') ) {

	function sp_get_terms_list($taxonomy){
		$args = array(
				'hide_empty'	=> 0
			);
		$taxonomies = get_terms($taxonomy, $args);
		return $taxonomies;
	}

}


/* ---------------------------------------------------------------------- */               							
/*  Get related post by Taxonomy
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_get_posts_related_by_taxonomy') ) {

	function sp_get_posts_related_by_taxonomy($post_id, $taxonomy, $args=array()) {

		//$query = new WP_Query();
		$terms = wp_get_object_terms($post_id, $taxonomy);
		if (count($terms)) {
		
		// Assumes only one term for per post in this taxonomy
		$post_ids = get_objects_in_term($terms[0]->term_id,$taxonomy);
		$post = get_post($post_id);
		$args = wp_parse_args($args,array(
		  'post_type' => $post->post_type, // The assumes the post types match
		  //'post__in' => $post_ids,
		  'post__not_in' => array($post_id),
		  'tax_query' => array(
		  			array(
						'taxonomy' => $taxonomy,
						'field' => 'term_id',
		  				'terms' => $terms[0]->term_id
					)),
		  'orderby' => 'rand',
		  'posts_per_page' => -1
		  
		));
		$query = new WP_Query($args);
		}
		return $query;
	}

}

/* ---------------------------------------------------------------------- */               							
/*  Taxonomy has children and has parent
/* ---------------------------------------------------------------------- */
function has_children($cat_id, $taxonomy) {
    $children = get_terms(
        $taxonomy,
        array( 'parent' => $cat_id, 'hide_empty' => false )
    );
    if ($children){
        return true;
    }
    return false;
}

function category_has_parent($catid){
    $category = get_category($catid);
    if ($category->category_parent > 0){
        return true;
    }
    return false;
}

/* ---------------------------------------------------------------------- */
/*  Get related pages
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_get_related_pages') ) {

	function sp_get_related_pages() {

		$orig_post = $post;
		global $post;
		$tags = wp_get_post_tags($post->ID);
		if ($tags) {
			$tag_ids = array();
			foreach($tags as $individual_tag)
			$tag_ids[] = $individual_tag->term_id;
			$args=array(
			'post_type' => 'page',
			'tag__in' => $tag_ids,
			'post__not_in' => array($post->ID),
			'posts_per_page'=>5
			);
			$pages_query = new WP_Query( $args );
			if( $pages_query->have_posts() ) {
				echo '<div id="relatedpages"><h3>Related Pages</h3><ul>';
				while( $pages_query->have_posts() ) {
				$pages_query->the_post(); ?>
				<li><div class="relatedthumb"><a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumb'); ?></a></div>
				<div class="relatedcontent">
				<h3><a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
				<?php the_time('M j, Y') ?>
				</div>
				</li>
			<?php }
				echo '</ul></div>';
			} else { 
				echo "No Related Pages Found:";
			}
		}
		$post = $orig_post;
		wp_reset_postdata(); 

	}
	
}

/* ---------------------------------------------------------------------- */
/*  Get related post
/* ---------------------------------------------------------------------- */ 
if ( ! function_exists( 'sp_related_posts' ) ) {

	function sp_related_posts() {
		wp_reset_postdata();
		global $post;

		// Define shared post arguments
		$args = array(
			'no_found_rows'				=> true,
			'update_post_meta_cache'	=> false,
			'update_post_term_cache'	=> false,
			'ignore_sticky_posts'		=> 1,
			'orderby'					=> 'rand',
			'post__not_in'				=> array($post->ID),
			'posts_per_page'			=> 3
		);
		// Related by categories
		if ( ot_get_option('related-posts') == 'categories' ) {
			
			$cats = get_post_meta($post->ID, 'related-cat', true);
			
			if ( !$cats ) {
				$cats = wp_get_post_categories($post->ID, array('fields'=>'ids'));
				$args['category__in'] = $cats;
			} else {
				$args['cat'] = $cats;
			}
		}
		// Related by tags
		if ( ot_get_option('related-posts') == 'tags' ) {
		
			$tags = get_post_meta($post->ID, 'related-tag', true);
			
			if ( !$tags ) {
				$tags = wp_get_post_tags($post->ID, array('fields'=>'ids'));
				$args['tag__in'] = $tags;
			} else {
				$args['tag_slug__in'] = explode(',', $tags);
			}
			if ( !$tags ) { $break = true; }
		}
		
		$query = !isset($break) ? new WP_Query($args) : new WP_Query;
		return $query;
	}
	
}

/* ---------------------------------------------------------------------- */
/*	Displays a page pagination
/* ---------------------------------------------------------------------- */

if ( !function_exists('sp_pagination') ) {

	function sp_pagination( $pages = '', $range = 2 ) {

		$showitems = ( $range * 2 ) + 1;

		global $paged, $wp_query;

		if( empty( $paged ) )
			$paged = 1;

		if( $pages == '' ) {

			$pages = $wp_query->max_num_pages;

			if( !$pages )
				$pages = 1;

		}

		if( 1 != $pages ) {

			$output = '<nav class="pagination">';

			// if( $paged > 2 && $paged >= $range + 1 /*&& $showitems < $pages*/ )
				// $output .= '<a href="' . get_pagenum_link( 1 ) . '" class="next">&laquo; ' . __('First', 'sptheme_admin') . '</a>';

			if( $paged > 1 /*&& $showitems < $pages*/ )
				$output .= '<a href="' . get_pagenum_link( $paged - 1 ) . '" class="next">&larr; ' . __('Previous', SP_TEXT_DOMAIN) . '</a>';

			for ( $i = 1; $i <= $pages; $i++ )  {

				if ( 1 != $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems ) )
					$output .= ( $paged == $i ) ? '<span class="current">' . $i . '</span>' : '<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>';

			}

			if ( $paged < $pages /*&& $showitems < $pages*/ )
				$output .= '<a href="' . get_pagenum_link( $paged + 1 ) . '" class="prev">' . __('Next', SP_TEXT_DOMAIN) . ' &rarr;</a>';

			// if ( $paged < $pages - 1 && $paged + $range - 1 <= $pages /*&& $showitems < $pages*/ )
				// $output .= '<a href="' . get_pagenum_link( $pages ) . '" class="prev">' . __('Last', 'sptheme_admin') . ' &raquo;</a>';

			$output .= '</nav>';

			return $output;

		}

	}

}

/* ---------------------------------------------------------------------- */
/*	Comment Template
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_comment_template' ) ) {

	function sp_comment_template( $comment, $args, $depth ) {
		global $retina;
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case '' :
		?>

		<li id="comment-<?php comment_ID(); ?>" class="comment clearfix">

			<?php $av_size = isset($retina) && $retina === 'true' ? 96 : 48; ?>
			
			<div class="user"><?php echo get_avatar( $comment, $av_size, $default=''); ?></div>

			<div class="message">
				
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => 3 ) ) ); ?>

				<div class="info">
					<h4><?php echo (get_comment_author_url() != '' ? comment_author_link() : comment_author()); ?></h4>
					<span class="meta"><?php echo comment_date('F jS, Y \a\t g:i A'); ?></span>
				</div>

				<?php comment_text(); ?>
				
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="await"><?php _e( 'Your comment is awaiting moderation.', 'goodwork' ); ?></em>
				<?php endif; ?>

			</div>

		</li>

		<?php
			break;
			case 'pingback'  :
			case 'trackback' :
		?>
		
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'goodwork' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'goodwork'), ' ' ); ?></p></li>
		<?php
				break;
		endswitch;
	}
	
}

/* ---------------------------------------------------------------------- */
/*	Ajaxify Comments
/* ---------------------------------------------------------------------- */

add_action('comment_post', 'ajaxify_comments',20, 2);
function ajaxify_comments($comment_ID, $comment_status){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	//If AJAX Request Then
		switch($comment_status){
			case '0':
				//notify moderator of unapproved comment
				wp_notify_moderator($comment_ID);
			case '1': //Approved comment
				echo "success";
				$commentdata=&get_comment($comment_ID, ARRAY_A);
				$post=&get_post($commentdata['comment_post_ID']); 
				wp_notify_postauthor($comment_ID, $commentdata['comment_type']);
			break;
			default:
				echo "error";
		}
		exit;
	}
}

/* ---------------------------------------------------------------------- */
/*	Full Meta post entry
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_post_meta' ) ) {
	function sp_post_meta() {
		printf( __( '<i class="icon icon-calendar-1"></i><a href="%1$s" title="%2$s"><time class="entry-date" datetime="%3$s"> %4$s</time></a><span class="by-author"> by </span><span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span><span class="posted-in"> in </span><i class="icon icon-tag"> </i> %8$s ', SP_TEXT_DOMAIN ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', SP_TEXT_DOMAIN ), get_the_author() ) ),
			get_the_author(),
			get_the_category_list( ', ' )
		);
		if ( comments_open() ) : ?>
				<span class="with-comments"><?php _e( ' with ', SP_TEXT_DOMAIN ); ?></span>
				<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( '0 Comments', SP_TEXT_DOMAIN ) . '</span>', __( '1 Comment', SP_TEXT_DOMAIN ), __( '<i class="icon icon-comment-1"></i> % Comments', SP_TEXT_DOMAIN ) ); ?></span>
		<?php endif; // End if comments_open() ?>
		<?php edit_post_link( __( 'Edit', SP_TEXT_DOMAIN ), '<span class="sep"> | </span><span class="edit-link">', '</span>' );
	}
};

/* ---------------------------------------------------------------------- */
/*	Mini Meta post entry
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_meta_mini' ) ) :
	function sp_meta_mini() {
		printf( __( '<a href="%1$s" title="%2$s"><time class="entry-date" datetime="%3$s">%4$s</time></a>', SP_TEXT_DOMAIN ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
			//get_the_category_list( ', ' )
		);
		if ( comments_open() ) : ?>
				<span class="sep"><?php _e( ' | ', SP_TEXT_DOMAIN ); ?></span>
				<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( '0 Comments', SP_TEXT_DOMAIN ) . '</span>', __( '1 Comment', SP_TEXT_DOMAIN ), __( '% Comments', SP_TEXT_DOMAIN ) ); ?></span>
		<?php endif; // End if comments_open()
	}
endif;

/* ---------------------------------------------------------------------- */
/*	Embeded add video from youtube, vimeo and dailymotion
/* ---------------------------------------------------------------------- */
function sp_get_video_img($url) {
	
	$video_url = @parse_url($url);
	$output = '';

	if ( $video_url['host'] == 'www.youtube.com' || $video_url['host']  == 'youtube.com' ) {
		parse_str( @parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		$video_id =  $my_array_of_vars['v'] ;
		$output .= 'http://img.youtube.com/vi/'.$video_id.'/0.jpg';
	}elseif( $video_url['host'] == 'www.youtu.be' || $video_url['host']  == 'youtu.be' ){
		$video_id = substr(@parse_url($url, PHP_URL_PATH), 1);
		$output .= 'http://img.youtube.com/vi/'.$video_id.'/0.jpg';
	}
	elseif( $video_url['host'] == 'www.vimeo.com' || $video_url['host']  == 'vimeo.com' ){
		$video_id = (int) substr(@parse_url($url, PHP_URL_PATH), 1);
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php"));
		$output .=$hash[0]['thumbnail_large'];
	}
	elseif( $video_url['host'] == 'www.dailymotion.com' || $video_url['host']  == 'dailymotion.com' ){
		$video = substr(@parse_url($url, PHP_URL_PATH), 7);
		$video_id = strtok($video, '_');
		$output .='http://www.dailymotion.com/thumbnail/video/'.$video_id;
	}

	return $output;
	
}

/* ---------------------------------------------------------------------- */
/*	Embeded add video from youtube, vimeo and dailymotion
/* ---------------------------------------------------------------------- */
function sp_add_video ($url, $width = 620, $height = 349) {

	$video_url = @parse_url($url);
	$output = '';

	if ( $video_url['host'] == 'www.youtube.com' || $video_url['host']  == 'youtube.com' ) {
		parse_str( @parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		$video =  $my_array_of_vars['v'] ;
		$output .='<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video.'?rel=0" frameborder="0" allowfullscreen></iframe>';
	}
	elseif( $video_url['host'] == 'www.youtu.be' || $video_url['host']  == 'youtu.be' ){
		$video = substr(@parse_url($url, PHP_URL_PATH), 1);
		$output .='<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video.'?rel=0" frameborder="0" allowfullscreen></iframe>';
	}
	elseif( $video_url['host'] == 'www.vimeo.com' || $video_url['host']  == 'vimeo.com' ){
		$video = (int) substr(@parse_url($url, PHP_URL_PATH), 1);
		$output .='<iframe src="http://player.vimeo.com/video/'.$video.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
	}
	elseif( $video_url['host'] == 'www.dailymotion.com' || $video_url['host']  == 'dailymotion.com' ){
		$video = substr(@parse_url($url, PHP_URL_PATH), 7);
		$video_id = strtok($video, '_');
		$output .='<iframe frameborder="0" width="'.$width.'" height="'.$height.'" src="http://www.dailymotion.com/embed/video/'.$video_id.'"></iframe>';
	}

	return $output;
}

/* ---------------------------------------------------------------------- */
/*	Embeded soundcloud
/* ---------------------------------------------------------------------- */

function sp_soundcloud($url , $autoplay = 'false' ) {
	return '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.$url.'&amp;auto_play='.$autoplay.'&amp;show_artwork=true"></iframe>';
}

function sp_portfolio_grid( $col = 'list', $posts_per_page = 5 ) {
	
	$temp ='';
	$output = '';
	
	$args = array(
			'posts_per_page' => (int) $posts_per_page,
			'post_type' => 'portfolio',
			);
			
	$post_list = new WP_Query($args);
		
	ob_start();
	if ($post_list && $post_list->have_posts()) {
		
		$output .= '<ul class="portfolio ' . $col . '">';
		
		while ($post_list->have_posts()) : $post_list->the_post();
		
		$output .= '<li>';
		$output .= '<div class="two-fourth"><div class="post-thumbnail">';
		$output .= '<a href="'.get_permalink().'"><img src="' . sp_post_thumbnail('portfolio-2col') . '" /></a>';
		$output .= '</div></div>';
		
		$output .= '<div class="two-fourth last">';
		$output .= '<a href="'.get_permalink().'" class="port-'. $col .'-title">' . get_the_title() .'</a>';
		$output .= '</div>';	
		
		$output .= '</li>';	
		endwhile;
		
		$output .= '</ul>';
		
	}
	$temp = ob_get_clean();
	$output .= $temp;
	
	wp_reset_postdata();
	
	return $output;
	
}

/* ---------------------------------------------------------------------- */
/*	Get Most Racent posts from Category
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_last_posts_cat' ) ) {
	function sp_last_posts_cat( $post_num = 5 , $thumb = true , $category = 1 ) {

		global $post;
		
		$out = '';
		if ( is_singular() ) :
			$args = array( 'cat' => $category, 'posts_per_page' => (int) $post_num, 'post__not_in' => array($post->ID) );	
		else : 
			$args = array( 'cat' => $category, 'posts_per_page' => (int) $post_num, 'post__not_in' => get_option( 'sticky_posts' ) );
		endif;
		

		$custom_query = new WP_Query( $args );

		$out .= '<section class="custom-posts clearfix">';
		if( $custom_query->have_posts() ) :
			while ( $custom_query->have_posts() ) : $custom_query->the_post();

			$out .= '<article>';
			$out .= '<a href="' . get_permalink() . '" class="clearfix">';
			if ( $thumb ) :
				if ( has_post_thumbnail() ) {
					$out .= get_the_post_thumbnail();
				} else {
					$out .= '<img class="wp-image-placeholder" src="' . SP_ASSETS_THEME .'images/placeholder/thumbnail-300x225.gif">';	
				}
			endif;
			$out .= '<h5>' . get_the_title() . '</h5>';
			$out .= '<span class="time">' . get_the_time('j M, Y') . '</span>';
			$out .= '</a>';
			$out .= '</article>';

			endwhile; wp_reset_postdata();
		endif;
		$out .= '<a href="' . esc_url(get_category_link( $category )) . '" class="learn-more">' . __('More news', SP_TEXT_DOMAIN) .'</a>';
		$out .= '</section>';

		return $out;
	}
}

/* ---------------------------------------------------------------------- */
/*	Get cover album
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_get_album_gallery' ) ) {
	function sp_get_album_gallery( $album_id = '', $photo_num = 10, $size = 'thumbnail' ) {

		global $post;

		$gallery = explode( ',', get_post_meta( $album_id, 'sp_gallery', true ) );
		
		$out = '<div class="gallery clearfix">';
		
		if ( $gallery[0] != '' ) :
			foreach ( $gallery as $image ) :
			$imageid = wp_get_attachment_image_src($image, $size);
			$out .= '<div class="one-third">';
			$out .= '<a href="' . wp_get_attachment_url($image) . '">';
			$out .= '<img class="attachment-medium wp-post-image" src="' . $imageid[0] . '">';
			$out .= '</a>';
			$out .= '</div><!-- .one-third -->';
			endforeach; 
		else : 
			$out .= __( 'Sorry there is no image for this album.', SP_TEXT_DOMAIN );
		endif;

		$out .= '</div>';

		return $out;
	}
}

/* ---------------------------------------------------------------------- */
/*	Get photos of Album
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_get_cover_album' ) ) {
	function sp_get_cover_album( $photo_num = 10, $size = 'thumbnail' ) {

		global $post;

		$args = array(
			'post_type' 		=>	'gallery',
			'posts_per_page'	=>	$photo_num,
		);

		$custom_query = new WP_Query( $args );

		if( $custom_query->have_posts() ) :
			$out = '<div class="album-cover clearfix">';
			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				$out .= '<div class="two-fourth">';
				$out .= '<a href="'.get_permalink().'"><img src="' . sp_post_thumbnail( $size ) . '" /></a>';
                $out .= '<h5><a href="'.get_permalink().'">' . get_the_title() . '</a></h5>';
                $out .= '</a>';
                $out .= '</div><!-- .two-fourth -->';

			endwhile; wp_reset_postdata();
			$out .= '</div><!-- .album-cover -->';
		endif;

		return $out;
	}
}

/* ---------------------------------------------------------------------- */
/*	Display sliders
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_sliders' ) ) {
	function sp_sliders( $slide_id, $size = 'thumbnail' ){
		
		$sliders = explode( ',', get_post_meta( $slide_id, 'sp_sliders', true ) );
		$out = '';
		$out .='<script type="text/javascript">
				jQuery(document).ready(function($){
					$("#slideshow").flexslider({
						animation: "slide",
						pauseOnHover: true,
						controlNav: false
					});
				});		
				</script>';
		$out .= '<div id="slideshow" class="flexslider">';
		$out .= '<ul class="slides">';

		foreach ( $sliders as $image ){
			
			$imageid = wp_get_attachment_image_src($image, $size);

			$out .= '<li>';
			$out .= '<img src="' . $imageid[0] . '">';
			$out .= '</li>';
		
		}

		$out .= '</ul>';
		$out .= '</div>';	

		return $out;	
	}
}

/* ---------------------------------------------------------------------- */
/*	Social icons - Widget
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_show_social_icons' ) ) {
	function sp_show_social_icons() {

		$social_icons = ot_get_option( 'social-links' );

		$out = '<section class="social-btn clearfix round">';
		$out .= '<ul>';
		
		foreach ($social_icons as $icons) {
			if ( $icons['social-icon'] == 'icon-facebook' )	
				$out .= '<li class="i-square icon-facebook-squared"><a href="#" target="_self"></a></li>';
			
			if ( $icons['social-icon'] == 'icon-twitter' )
				$out .= '<li class="i-square icon-twitter"><a href="#" target="_self"></a></li>';
			
			if ( $icons['social-icon'] == 'icon-gplus' )
				$out .= '<li class="i-square icon-gplus"><a href="#" target="_self"></a></li>';
			
			if ( $icons['social-icon'] == 'icon-youtube' )	
				$out .= '<li class="i-square icon-youtube"><a href="#" target="_self"></a></li>';
		}

		$out .= '</ul>';
		$out .= '</section>';

		return $out;

	}
}


/* ---------------------------------------------------------------------- */               							
/*  Get post type and render content style
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_get_posts_type') ) {
	function sp_get_posts_type( $post_type = 'post', $args=array() ) {

		$defaults = array(
				'post_type' => $post_type,
				'posts_per_page' => -1
			);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		$custom_query = new WP_Query($args);

		if ( $custom_query->have_posts() ):
			$out = '<div class="sp-post-' . $post_type . ' col-3">';
			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				$out .= sp_switch_posttype_content( get_the_ID(), $post_type );
			endwhile;
			wp_reset_postdata();
			$out .= '</div>';
		endif;

		return $out;
	}	
}

/* ---------------------------------------------------------------------- */               							
/*  Get post related by post type
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_get_related_posts') ) {
	function sp_get_related_posts( $post_id, $args=array() ) {

		$post = get_post($post_id);
		$post_type = $post->post_type;

		$taxonomy = get_object_taxonomies( $post_type );
		$terms = wp_get_post_terms($post_id, $taxonomy[0], array("fields" => "ids"));
		
		$defaults = array(
				'post_type' => $post_type, 
				'post__not_in' => array($post_id),
				'orderby' => 'rand',
				'posts_per_page' => 3,
				'tax_query' => array(
		  			array(
						'taxonomy' => $taxonomy[0],
						'field' => 'term_id',
		  				'terms' => $terms
					))
			);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		$custom_query = new WP_Query($args);

		if ( $custom_query->have_posts() ):
			$out = '<section class="related-posts sp-post-' . $post_type . '">';
			$out .= '<h4 class="heading">' . __('Related post...', SP_TEXT_DOMAIN) . '</h4>';
			$out .= '<div class="sp-post-' . $post_type . ' col-3 clearfix">';
			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				$out .= sp_switch_posttype_content( get_the_ID(), $post_type );
			endwhile;
			$out .= '</div>';
			$out .= '</section>';
			wp_reset_query();
		else :
			$out = 'There is no related post.';
		endif; 

		return $out;
	}	
}

/* ---------------------------------------------------------------------- */               							
/*  Switch post type content
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_switch_posttype_content') ) {
	function sp_switch_posttype_content( $post_id, $post_type ) {

		if ( $post_type == 'team' ) {
			$out = sp_render_team_post( $post_id, 'medium' );
		} elseif ( $post_type == 'partner' ) {
			$out = sp_render_partner_post( $post_id, 'medium' );	
		} elseif ( $post_type == 'gallery' ) {
			$out = sp_render_photogallery_post( $post_id, 'medium' );
		} else { // for blog 
			$out = sp_render_blog_post($post_id, 'medium', 'hover-1');
		}
		return $out;
		
	}
}

/* ---------------------------------------------------------------------- */               							
/* Render HTML Video
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_render_video_post') ) {
	function sp_render_video_post($post_id, $size = 'thumbnail') {

		$video_url = get_post_meta($post_id, 'sp_video_url', true);
		$video_cover = sp_get_video_img( $video_url );

    	$out = '<article id="post-' . $post_id . '">';
    	$out .= '<div class="thumb-effect">';
    	if ( has_post_thumbnail() ) :
			$out .= '<img class="attachment-medium wp-post-image" src="' . sp_post_thumbnail( $size ) . '" />';
		else :
			$out .= '<img class="attachment-medium wp-post-image" src="' . $video_cover . '" />';
		endif; 
		$out .= '<div class="thumb-caption">';
		$out .= '<h5>' . get_the_title() . '</h5>';
		$out .= '<span class="entry-meta">' . get_the_date() . '</span>';
		$out .= '<a href="' . get_permalink() . '">' . __('Take a look', SP_TEXT_DOMAIN) . '</a>';
		$out .= '</div>';
		$out .= '</div>';
	    $out .= '</article>';
		return $out;
	}
}

/* ---------------------------------------------------------------------- */               							
/* Render HTML Audio
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_render_sound_post') ) {
	function sp_render_sound_post($post_id, $size = 'thumbnail') {

		$sound_url = get_post_meta($post->ID, 'sp_soundcloud_url', true);
		$sound_cover = SP_ASSETS_THEME . 'images/placeholder/thumbnail-300x225.gif';

    	$out = '<article id="post-' . $post_id . '">';
    	$out .= '<div class="thumb-effect">';
    	if ( has_post_thumbnail() ) :
			$out .= '<img class="attachment-medium wp-post-image" src="' . sp_post_thumbnail( $size ) . '" />';
		else :
			$out .= '<img class="attachment-medium wp-post-image" src="' . $sound_cover . '" />';
		endif; 
		$out .= '<div class="thumb-caption">';
		$out .= '<h5>' . get_the_title() . '</h5>';
		$out .= '<span class="entry-meta">' . get_the_date() . '</span>';
		$out .= '<a href="' . get_permalink() . '">' . __('Listen', SP_TEXT_DOMAIN) . '</a>';
		$out .= '</div>';
		$out .= '</div>';
	    $out .= '</article>';
		return $out;
	}
}

/* ---------------------------------------------------------------------- */               							
/* Render HTML blog
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_render_blog_post') ) {
	function sp_render_blog_post($post_id, $size = 'thumbnail', $style='') {

		$placeholder = SP_ASSETS_THEME . 'images/placeholder/thumbnail-300x225.gif';
		$image_url = aq_resize( sp_post_thumbnail( $size ), '172', '132', true);

    	$out = '<article id="post-' . $post_id . '">';
    	$out .= '<div class="thumb-effect ' . $style .'">';
    	if ( has_post_thumbnail() ) :
			$out .= '<img class="attachment-medium wp-post-image" src="' . $image_url . '" />';
		else :
			$out .= '<img class="attachment-medium wp-post-image" src="' . $placeholder . '" />';
		endif; 
		$out .= '<div class="caption-wrapper">';
		$out .= '<div class="caption-inner">';
		$out .= '<div class="caption-holder">';
		$out .= '<h5><a href="' . get_permalink() . '">' . get_the_title() . '</a></h5>';
		$out .= '<span class="entry-meta">' . get_the_date() . '</span>';
		if ( !empty($style) ) {
			$out .= '<a href="' . get_permalink() . '" class="btn-preview">' . __('Take a look', SP_TEXT_DOMAIN) . '</a>';
		}
		$out .= '</div>';
		$out .= '</div>';
		$out .= '</div>';
		$out .= '</div>';
	    $out .= '</article>';
		return $out;
	}
}

/* ---------------------------------------------------------------------- */               							
/* Render HTML Albums
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_render_photogallery_post') ) {
	function sp_render_photogallery_post($post_id, $size = 'thumbnail') {

		$album_location = get_post_meta($post_id, 'sp_album_location', true);
		$placeholder = SP_ASSETS_THEME . 'images/placeholder/thumbnail-300x225.gif';

    	$out = '<article id="post-' . $post_id . '">';
    	$out .= '<div class="thumb-effect">';
    	if ( has_post_thumbnail() ) :
			$out .= '<img class="attachment-medium wp-post-image" src="' . sp_post_thumbnail( $size ) . '" />';
		else :
			$out .= '<img class="attachment-medium wp-post-image" src="' . $placeholder . '" />';
		endif; 
		$out .= '<div class="thumb-caption">';
		$out .= '<h5>' . get_the_title() . '</h5>';
		$out .= '<span class="entry-meta">' . $album_location . ' - ' . get_the_date() . '</span>';
		$out .= '<a href="' . get_permalink() . '">' . __('Take a look', SP_TEXT_DOMAIN) . '</a>';
		$out .= '</div>';
		$out .= '</div>';
	    $out .= '</article>';

		return $out;
	}
}


/* ---------------------------------------------------------------------- */
/*	Get gallery/photos detail
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_get_album_gallery' ) ) {
	function sp_get_album_gallery( $post_id, $post_num = 10, $size = 'thumbnail' ) {

		$album_location = get_post_meta($post_id, 'sp_album_location', true);
		$photos = explode( ',', get_post_meta( $post_id, 'sp_gallery', true ) );
		$out = '';

    	if ( $photos[0] != '' ) :
    		$out = '<div class="gallery clearfix">';
    		foreach ( $photos as $image ) :
				$imageid = wp_get_attachment_image_src($image, $size);
				$out .= '<article id="post-' . $post_id . '">';
    			$out .= '<div class="thumb-effect">';
				$out .= '<img class="attachment-medium wp-post-image" src="' . $imageid[0] . '">';
				$out .= '<div class="thumb-caption">';
				$out .= '<h5>' . get_the_title() . '</h5>';
				$out .= '<span class="entry-meta">' . $album_location . ' - ' . get_the_date() . '</span>';
				$out .= '<a href="' . wp_get_attachment_url($image) . '">' . __('View photo', SP_TEXT_DOMAIN) . '</a>';
				$out .= '</div>';
				$out .= '</div>';
			    $out .= '</article>';
			endforeach; 
			$out .= '</div>';
		else : 
			$out .= '<h4>' . __( 'Sorry there is no image for this album.', SP_TEXT_DOMAIN ) . '</h4>';	
    	endif;

		return $out;
	}
}

/* ---------------------------------------------------------------------- */               							
/* Render HTML Team
/* ---------------------------------------------------------------------- */
if ( !function_exists('sp_render_team_post') ) {
	function sp_render_team_post($post_id, $size = 'thumbnail', $style='') {
		
		$out = '<article id="post-' . $post_id . '">';
    	$out .= sp_single_team_html( $post_id, $size, $style );
	    $out .= '</article>';

		return $out;
	}
}

/* ---------------------------------------------------------------------- */
/*	Render HTML Team style 1
/* ---------------------------------------------------------------------- */ 

if ( ! function_exists( 'sp_single_team_html' ) ) {
	function sp_single_team_html( $post_id, $size = 'thumbnail', $style='' ){
		
		$team_position = get_post_meta($post_id, 'sp_team_position', true);
		$placeholder = SP_ASSETS_THEME . 'images/placeholder/thumbnail-300x225.gif';

		$out = '<div class="thumb-effect ' . $style .'">';
    	if ( has_post_thumbnail() ) :
			$out .= '<a href="'.sp_post_thumbnail( 'large' ).'"><img class="attachment-medium wp-post-image" src="' . sp_post_thumbnail( $size ) . '" /></a>';
		else :
			$out .= '<img class="attachment-medium wp-post-image" src="' . $placeholder . '" />';
		endif; 
		$out .= '<div class="caption-wrapper">';
		$out .= '<div class="caption-inner">';
		$out .= '<div class="caption-holder">';
		$out .= '<h5><a href="' . get_permalink() . '">' . get_the_title() . '</a></h5>';
		$out .= '<span class="entry-meta">' . $team_position . '</span>';
		if ( !empty($style) ) {
			$out .= '<a href="' . get_permalink() . '" class="btn-preview">' . __('Take a look', SP_TEXT_DOMAIN) . '</a>';
		}
		$out .= '</div>';
		$out .= '</div>';
		$out .= '</div>';
		$out .= '</div>';

		return $out;	
	}
}

/* ---------------------------------------------------------------------- */
/*	Render HTML Partner logo
/* ---------------------------------------------------------------------- */ 

if ( ! function_exists( 'sp_render_partner_post' ) ) {
	function sp_render_partner_post( $post_id, $size = 'thumbnail' ){
		
		$partner_url = get_post_meta( $post_id, 'sp_partner_link', true );
		$thumb_url = sp_post_thumbnail($size);
        $image_url = aq_resize( $thumb_url, '132');
		
		$out = '<article id="post-' . $post_id . '">';
		if ( $partner_url ) {
			$out .= '<a href="'.$partner_url.'" target="_blank"><img src="' . $image_url . '" /></a>';
		} else {
			$out .= '<img src="' . $image_url . '" />';
		}
		$out .= '</article>';
		
		return $out;	
	}
}

/* ---------------------------------------------------------------------- */
/*	Render HTML Testimonial
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_get_testimonial' ) ) {
	function sp_get_testimonial( $style = 'light', $numberposts = '10' ){
		global $post;

		$out = '';

		$args = array(
			'post_type' => 'testimonial',
			'post_status' => 'publish',
			'posts_per_page' => $numberposts
			);
		$custom_query = new WP_Query( $args );

		while ( $custom_query->have_posts() ) : $custom_query->the_post();

			$testimonial_text = get_the_content();
        	$testimonial_cite = get_post_meta($post->ID, 'sp_testimonial_cite', true);
        	$testimonial_cite_subtext = get_post_meta($post->ID, 'sp_testimonial_cite_subtext', true);

        	$out .= '<figure class="testimonial ' . $style . '">';
			$out .= '<blockquote>';
			$out .= $testimonial_text;
			$out .= '</blockquote>';
			$out .= '<figcaption>';
			$out .= '<p>' . $testimonial_cite . '</p>';
			if ( $testimonial_cite_subtext )
				$out .= '<span>' . do_shortcode($testimonial_cite_subtext) . '</span>';
			$out .= '</figcaption>';
			$out .= '</figure>';
		
		endwhile;
		wp_reset_postdata();

		return $out;	
	}
}

/* ---------------------------------------------------------------------- */
/*	Render HTML Featured Page
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_get_featured_page' ) ) {
	function sp_get_featured_page( $args ){
		global $post;

		$out = '<div class="featured-page clearfix">';

		$featured_pages = get_pages( $args );
		foreach ( $featured_pages as $page ) {
			$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'large' );
            $image_url = aq_resize( $thumb_url[0], '300', '180', true);

			$out .= '<div class="one-third">';
			$out .= '<div class="thumb-icon">';
			$out .= '<img src="' . $image_url . '">';
			$out .= '<div class="bg-mask"><a class="icon icon-search" href="'.get_page_link( $page->ID ).'"></a></div>';
			$out .= '</div>';
			$out .= '<h6><a href="'.get_page_link( $page->ID ).'">' . $page->post_title . '</a></h6>';
			$out .= '</div>';
		}

		$out .= '</div>';

		return $out;	
	}
}


/* ---------------------------------------------------------------------- */
/*	Render HTML Home slider post
/* ---------------------------------------------------------------------- */
if ( ! function_exists( 'sp_get_homeslider_post' ) ) {
	function sp_get_homeslider_post( $args ){
		global $post;

		$post_slides = get_posts( $args );
	    $out = '';
	    $out .='<script type="text/javascript">
					jQuery(document).ready(function($){
						$("#home-slider").flexslider({
							animation: "slide",
							slideshowSpeed: 5000,
							animationDuration: 200,
							animationLoop: true,
							pauseOnAction: true,
							pauseOnHover: true,
							controlNav: false,
							before: function(slider) {
	                        $(".flex-caption").delay(100).fadeOut(100);
		                    },
		                    after: function(slider) {
		                      $(".flex-active-slide").find(".flex-caption").delay(200).fadeIn(400);
		                    }	
						});
					});		
					</script>';
	    $out .= '<div id="home-slider" class="flexslider">';
	    $out .= '<ul class="slides">';
	    foreach ($post_slides as $post ) : setup_postdata( $post ); 
	        $learn_more_btn = get_post_meta( $post->ID, 'sp_slide_btn_name', true);
	        $learn_more_link = get_post_meta( $post->ID, 'sp_slide_btn_url', true);
	        $thumb_url = sp_post_thumbnail('large');
	        $image_url = aq_resize( $thumb_url, '960', '425', true);
	        $caption = get_the_content();

	        $out .= '<li>';
	        $out .= '<img src="' . $image_url . '">';
	        if ( !empty($caption) ) {
	        $out .= '<div class="flex-caption clearfix">';
	        $out .= wpautop(get_the_content());
	        $out .= '<a class="learn-more" href="' . $learn_more_link . '">' . $learn_more_btn . '</a>';
	        $out .= '</div>';
	    	}
	        $out .= '</li>';
	    endforeach;
	    wp_reset_postdata();

	    $out .= '</ul>';
	    $out .= '</div>';

		return $out;	
	}
}

/* ---------------------------------------------------------------------- */
/*	Branch
/* ---------------------------------------------------------------------- */

if ( ! function_exists( 'map_branch_by_location' ) ) {
	function map_branch_by_location ( $term_id, $postnum, $zoom = 12 ){
		global $post;
		?>

	    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript">					
		  jQuery(document).ready(function ($)
			{
				var locations = [ 
			<?php
			$args = array(
				 'post_type' =>	'branch',
	             'posts_per_page' => $postnum,
	             'post_status' => 'publish',
	             'tax_query' => array(
	                    array(
	                        'taxonomy' => 'branch-location',
	                        'field' => 'id',
	                        'terms' => array($term_id)
	                    )
	                )
	        );
	        $custom_query = new WP_Query( $args );

			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
        		$branch_photo = aq_resize( $thumb_url[0], 135, 90 );

				echo '[';
				echo '\'<div class="map-item-info clearfix">';
				if ( has_post_thumbnail() ) :
					echo '<img class="left wp-image-placeholder" src="' . $branch_photo . '">';
				else :
					echo '<img class="wp-image-placeholder" src="' . SP_ASSETS_THEME .'images/placeholder/thumbnail-300x225.gif" width="135" height="90">';	
				endif;
				echo '<ul class="branch-info">';
				echo '<li class="name"><h5>' . get_the_title() . '</h5></li>';
				echo '<li class="address">' . get_post_meta( get_the_ID(), 'sp_branch_address', true) . '</li>';
				echo '<li>';
				echo '<span class="left">' . __('Tel:', SP_TEXT_DOMAIN ) . '</span><span class="right">' . get_post_meta( get_the_ID(), 'sp_branch_tel', true) . '</span>';
				echo '</li>';
				echo '<li>';
				echo '<span class="left">' . __('E-mail:', SP_TEXT_DOMAIN ) . '</span><span class="right"><a href="mailto:' . antispambot(get_post_meta( get_the_ID(), 'sp_branch_email', true)) . '">' . antispambot(get_post_meta( get_the_ID(), 'sp_branch_email', true)) . '</a></span>';
				echo '</li>';
				echo '</ul></div>\'';
				echo ', ' . get_post_meta( get_the_ID(), 'sp_lat_long', true);
				echo '],';
			endwhile; wp_reset_postdata();
			?>	
		        ];
				
				var map = new google.maps.Map(document.getElementById('branch-map'), {
					  mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				
				var infowindow = new google.maps.InfoWindow();
				var bounds = new google.maps.LatLngBounds();
				var marker, i;

				for (i = 0; i < locations.length; i++) {  
				  marker = new google.maps.Marker({
					position: new google.maps.LatLng(locations[i][1], locations[i][2]),
					map: map,
					travelMode: google.maps.TravelMode["Driving"], //Driving or Walking or Bicycling or Transit
					animation: google.maps.Animation.DROP,
				  });

				  bounds.extend(marker.position);

				  google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
					  map.panTo(marker.getPosition());	
					  infowindow.setContent(locations[i][0]);
					  infowindow.open(map, marker);
					}
				  })(marker, i));
				
				    google.maps.event.addListener(map, "click", function(){
					  infowindow.close();
					});
				};

				map.fitBounds(bounds);

				//(optional) restore the zoom level after the map is done scaling
				var listener = google.maps.event.addListener(map, "idle", function () {
				    map.setZoom(<?php echo $zoom; ?>);
				    google.maps.event.removeListener(listener);
				});
			});
		</script>
		<div id="branch-map"></div>

	<?php
	}
}
