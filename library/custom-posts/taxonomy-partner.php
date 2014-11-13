<?php
add_action('init', 'sp_tax_partner_type_init', 0);

function sp_tax_partner_type_init() {
	register_taxonomy(
		'partner-category',
		array( 'partner' ),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => __( 'Partner category', 'sptheme_admin' ),
				'singular_name' => __( 'Partner category', 'sptheme_admin' ),
				'search_items' =>  __( 'Search Partner category', 'sptheme_admin' ),
				'all_items' => __( 'All Partners', 'sptheme_admin' ),
				'parent_item' => __( 'Parent Partner category', 'sptheme_admin' ),
				'parent_item_colon' => __( 'Parent Partner category:', 'sptheme_admin' ),
				'edit_item' => __( 'Edit Partner category', 'sptheme_admin' ),
				'update_item' => __( 'Update Partner category', 'sptheme_admin' ),
				'add_new_item' => __( 'Add New Partner category', 'sptheme_admin' ),
				'new_item_name' => __( 'Partner category', 'sptheme_admin' )
			),
			'sort' => true,
			'rewrite' => array( 'slug' => 'partner-category' ),
			'show_in_nav_menus' => false
		)
	);
}