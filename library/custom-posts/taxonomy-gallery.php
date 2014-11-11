<?php
add_action('init', 'sp_tax_gallery_type_init', 0);

function sp_tax_gallery_type_init() {
	register_taxonomy(
		'gallery-type',
		array( 'gallery' ),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => __( 'Gallery type', 'sptheme_admin' ),
				'singular_name' => __( 'Gallery type', 'sptheme_admin' ),
				'search_items' =>  __( 'Search gallery type', 'sptheme_admin' ),
				'all_items' => __( 'All gallery types', 'sptheme_admin' ),
				'parent_item' => __( 'Parent gallery type', 'sptheme_admin' ),
				'parent_item_colon' => __( 'Parent gallery type:', 'sptheme_admin' ),
				'edit_item' => __( 'Edit gallery type', 'sptheme_admin' ),
				'update_item' => __( 'Update gallery type', 'sptheme_admin' ),
				'add_new_item' => __( 'Add New gallery type', 'sptheme_admin' ),
				'new_item_name' => __( 'gallery type', 'sptheme_admin' )
			),
			'sort' => true,
			'rewrite' => array( 'slug' => 'gallery-type' ),
			'show_in_nav_menus' => false
		)
	);
}