<?php
/**
 * The template for displaying all pages.
 */
?>

<?php get_header(); ?>

<?php get_template_part('library/contents/masthead'); ?>

<?php do_action( 'sp_start_content_wrap_html' ); ?>
    <div id="main" class="main">
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post(); 
		?>

				<article id="post-<?php the_ID(); ?>" class="post">
					<div class="clearfix">
						<div class="one-third sp-post-team-thumb">
						<?php echo sp_single_team_html( $post->ID, 'large' ); ?>
						</div>
						
						<div class="two-third last">
							<header class="entry-header">
								<h1 class="entry-title">
									<?php the_title(); ?>
								</h1>
							</header>
							<div class="entry-content">
							<?php the_content(); ?>
							</div><!-- .entry-content -->
						</div>	
					</div> <!-- .clearfix -->
				</article><!-- #post -->

				<?php if ( ot_get_option('social_share') != 'off' ) { get_template_part('library/contents/social-share'); } ?>

				<div class="clear"></div>

				<?php if ( ot_get_option( 'related-posts' ) != '1' ) { 
					echo sp_get_related_posts( $post->ID, array('posts_per_page' => 3) ); 
				} ?>

		<?php		
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
		?>
		
	</div><!-- #main -->
	<?php get_sidebar();?>
<?php do_action( 'sp_end_content_wrap_html' ); ?>
<?php get_footer(); ?>