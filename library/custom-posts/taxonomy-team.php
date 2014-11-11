<?php
add_action('init', 'sp_tax_team_category_init', 0);

function sp_tax_team_category_init() {
	register_taxonomy(
		'team-category',
		array( 'team' ),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => __( 'Team Categories', 'sptheme_admin' ),
				'singular_name' => __( 'Team Categories', 'sptheme_admin' )
			),
			'sort' => true,
			'rewrite' => array( 'slug' => 'team-category' ),
			'show_in_nav_menus' => false
		)
	);
}