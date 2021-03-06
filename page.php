<?php
/**
 * The template for displaying all pages.
 */
?>

<?php get_header(); ?>

<?php get_template_part('library/contents/masthead'); ?>

<?php do_action( 'sp_start_content_wrap_html' ); ?>
    <div class="main">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
				<?php $is_page_title = get_post_meta( $post->ID, 'sp_is_page_title', true ); ?>

				<?php if ( $is_page_title == 'on' ) : ?>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<?php endif; ?>
				
				<div class="entry-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->

			</article><!-- #post -->
		<?php endwhile;
		else : 
			get_template_part('library/contents/error404');
		endif; ?>
	</div><!-- #main -->
	<?php get_sidebar();?>
<?php do_action( 'sp_end_content_wrap_html' ); ?>
	
<?php get_footer(); ?>