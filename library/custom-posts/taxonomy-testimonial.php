<?php
add_action('init', 'sp_tax_testimonial_category_init', 0);

function sp_tax_testimonial_category_init() {
	register_taxonomy(
		'testimonials-category',
		array( 'testimonial' ),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => __( 'Testimonial Categories', 'sptheme_admin' ),
				'singular_name' => __( 'Testimonial Categories', 'sptheme_admin' )
			),
			'sort' => true,
			'rewrite' => array( 'slug' => 'testimonial-category' ),
			'show_in_nav_menus' => false
		)
	);
}