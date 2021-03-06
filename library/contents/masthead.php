<?php $is_masthead = get_post_meta( $post->ID, 'sp_is_masthead', true );

	if ( $is_masthead == 'on' ) : ?>

	<?php
		$custom_masthead = get_post_meta( $post->ID, 'sp_custom_masthead', true );
		$mastheads = array(
				SP_ASSETS_THEME . 'images/masthead-1.jpg',
				SP_ASSETS_THEME . 'images/masthead-2.jpg',
				SP_ASSETS_THEME . 'images/masthead-3.jpg',
				SP_ASSETS_THEME . 'images/masthead-4.jpg',
				SP_ASSETS_THEME . 'images/masthead-5.jpg',
				SP_ASSETS_THEME . 'images/masthead-6.jpg',
			);

		( $custom_masthead ) ? $bg_masthead = $custom_masthead : $bg_masthead = $mastheads[(rand(1, count($mastheads))-1)];
		
	?>

	<div class="masthead" style="background-image:url(<?php echo $bg_masthead; ?>);">
	    <div class="subtitle">
	    <?php if ( is_page() ) : ?>
	        <h1><?php the_title(); ?></h1>
	        <span class="separator"></span>
	    <?php endif; ?>    
	    </div>
	</div>

<?php endif; ?>