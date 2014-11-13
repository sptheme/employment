<?php
/*
*****************************************************
* Partner custom post
*
* CONTENT:
* - 1) Actions and filters
* - 2) Creating a custom post
* - 3) Custom post list in admin
*****************************************************
*/





/*
*****************************************************
*      1) ACTIONS AND FILTERS
*****************************************************
*/
	//ACTIONS
		//Registering CP
		add_action( 'init', 'sp_partner_cp_init' );
		//CP list table columns
		add_action( 'manage_posts_custom_column', 'sp_partner_cp_custom_column' );

	//FILTERS
		//CP list table columns
		add_filter( 'manage_edit-partner_columns', 'sp_partner_cp_columns' );




/*
*****************************************************
*      2) CREATING A CUSTOM POST
*****************************************************
*/
	/*
	* Custom post registration
	*/
	if ( ! function_exists( 'sp_partner_cp_init' ) ) {
		function sp_partner_cp_init() {
			global $cp_menu_position;

			/*if ( $smof_data['sp_newsticker_revisions'] )
				$supports[] = 'revisions';*/
			$labels = array(
				'name'               => __( 'Partners', 'sptheme_admin' ),
				'singular_name'      => __( 'Partner', 'sptheme_admin' ),
				'add_new'            => __( 'Add New', 'sptheme_admin' ),
				'all_items'          => __( 'All Partners', 'sptheme_admin' ),
				'add_new_item'       => __( 'Add New Partner', 'sptheme_admin' ),
				'new_item'           => __( 'Add New Partner', 'sptheme_admin' ),
				'edit_item'          => __( 'Edit Partner', 'sptheme_admin' ),
				'view_item'          => __( 'View Partner', 'sptheme_admin' ),
				'search_items'       => __( 'Search Partner', 'sptheme_admin' ),
				'not_found'          => __( 'No Partner found', 'sptheme_admin' ),
				'not_found_in_trash' => __( 'No Partner found in trash', 'sptheme_admin' ),
				'parent_item_colon'  => __( 'Parent Partner', 'sptheme_admin' ),
			);	

			$role     = 'post'; // page
			$slug     = 'partner';
			$supports = array('title', 'thumbnail'); // 'title', 'editor', 'thumbnail'

			$args = array(
				'labels' 				=> $labels,
				'rewrite'               => array( 'slug' => $slug ),
				'menu_position'         => $cp_menu_position['menu_partner'],
				'menu_icon'           	=> 'dashicons-awards',
				'supports'              => $supports,
				'capability_type'     	=> $role,
				'query_var'           	=> true,
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_nav_menus'	    => false,
				'publicly_queryable'	=> true,
				'exclude_from_search'   => false,
				'has_archive'			=> false,
				'can_export'			=> true
			);
			register_post_type( 'partner' , $args );
		}
	} 


/*
*****************************************************
*      3) CUSTOM POST LIST IN ADMIN
*****************************************************
*/
	/*
	* Registration of the table columns
	*
	* $Cols = ARRAY [array of columns]
	*/
	if ( ! function_exists( 'sp_partner_cp_columns' ) ) {
		function sp_partner_cp_columns( $columns ) {
			
			$columns['cb']                  = '<input type="checkbox" />';
			$columns['partner_thumbnail']	= __( 'Thumbnail', 'sptheme_admin' );
			$columns['title']               = __( 'Company Name', 'sptheme_admin' );
			$columns['partner_category']	= __( 'Category Type', 'sptheme_admin' );
			$columns['date']		 		= __( 'Date', 'sptheme_admin' );
			
			return $columns;
		}
	}

	/*
	* Outputting values for the custom columns in the table
	*
	* $Col = TEXT [column id for switch]
	*/
	if ( ! function_exists( 'sp_partner_cp_custom_column' ) ) {
		function sp_partner_cp_custom_column( $column ) {
			global $post;
			
			switch ( $column ) {
				case "partner_thumbnail":
					echo get_the_post_thumbnail( $post->ID, array(50, 50) );
				break;

				case "partner_category":
					$terms = get_the_terms( $post->ID, 'partner-category' );

					if ( empty( $terms ) )
					break;
	
					$output = array();
	
					foreach ( $terms as $term ) {
						
						$output[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'partner-category' => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'partner-category', 'display' ) )
						);
	
					}
	
					echo join( ', ', $output );

				break;
				
				default:
				break;
			}
		}
	} // /sp_partner_cp_custom_column

	
	