<?php
/**
 * 404 pages.
 */
?>

<?php get_header(); ?>

	<?php do_action( 'sp_start_content_wrap_html' ); ?>


	<?php get_template_part( 'library/contents/no-results' ); ?>		

    
    <?php do_action( 'sp_end_content_wrap_html' ); ?>

<?php get_footer(); ?>
