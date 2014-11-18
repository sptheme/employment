<?php

/*  Initialize the meta boxes.
/* ------------------------------------ */
add_action( 'admin_init', '_custom_meta_boxes' );

function _custom_meta_boxes() {

	$prefix = 'sp_';
  
/*  Custom meta boxes
/* ------------------------------------ */
$page_layout_options = array(
	'id'          => 'page-options',
	'title'       => 'Page Options',
	'desc'        => '',
	'pages'       => array( 'page', 'post', 'team', 'gallery' ),
	'context'     => 'normal',
	'priority'    => 'default',
	'fields'      => array(
		array(
			'label'		=> 'Primary Sidebar',
			'id'		=> $prefix . 'sidebar_primary',
			'type'		=> 'sidebar-select',
			'desc'		=> 'Overrides default'
		),
		array(
			'label'		=> 'Layout',
			'id'		=> $prefix . 'layout',
			'type'		=> 'radio-image',
			'desc'		=> 'Overrides the default layout option',
			'std'		=> 'inherit',
			'choices'	=> array(
				array(
					'value'		=> 'inherit',
					'label'		=> 'Inherit Layout',
					'src'		=> SP_ASSETS_ADMIN . 'images/layout-off.png'
				),
				array(
					'value'		=> 'col-1c',
					'label'		=> '1 Column',
					'src'		=> SP_ASSETS_ADMIN . 'images/col-1c.png'
				),
				array(
					'value'		=> 'col-2cl',
					'label'		=> '2 Column Left',
					'src'		=> SP_ASSETS_ADMIN . 'images/col-2cl.png'
				),
				array(
					'value'		=> 'col-2cr',
					'label'		=> '2 Column Right',
					'src'		=> SP_ASSETS_ADMIN . 'images/col-2cr.png'
				)
			)
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Post and page meta
/* ---------------------------------------------------------------------- */

$masthead_options = array(
	'id'          => 'masthead-setting',
	'title'       => 'Masthead meta',
	'desc'        => '',
	'pages'       => array( 'post', 'page' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Show page title',
			'id'		=> $prefix . 'is_page_title',
			'desc'		=> 'Option to enable and disable page title',
			'std'		=> 'on',
			'type'		=> 'on-off'
		),
		array(
			'label'		=> 'Show masthead',
			'id'		=> $prefix . 'is_masthead',
			'desc'		=> 'Option to enable and disable masthead',
			'std'		=> 'on',
			'type'		=> 'on-off'
		),
		array(
			'label'		=> 'Custom masthead',
			'id'		=> $prefix . 'is_custom',
			'desc'		=> 'On: will upload new custom masthead, if Off: will use random 7 masthead images.',
			'std'		=> 'off',
			'type'		=> 'on-off',
			'condition' => 'sp_is_masthead:is(on)'
		),
		array(
			'label'		=> 'Upload masthead image',
			'id'		=> $prefix . 'custom_masthead',
			'type'		=> 'upload',
			'desc'		=> 'Image size would be 1024px by 214px',
			'condition' => 'sp_is_custom:is(on)'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Home Sliders post type
/* ---------------------------------------------------------------------- */
$post_type_home_slider = array(
	'id'          => 'home-slide-setting',
	'title'       => 'Slide meta',
	'desc'        => '',
	'pages'       => array( 'home_slider' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Link button',
			'id'		=> $prefix . 'slide_btn_name',
			'type'		=> 'text',
			'std'		=> '',
			'desc'		=> 'Name of button link e.g: Learn more'
		),
		array(
			'label'		=> 'Slide URL/Link',
			'id'		=> $prefix . 'slide_btn_url',
			'type'		=> 'text',
			'std'		=> '',
			'desc'		=> 'Enter slide URL'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Team post type
/* ---------------------------------------------------------------------- */
$post_type_team = array(
	'id'          => 'team-setting',
	'title'       => 'Team Member meta',
	'desc'        => '',
	'pages'       => array( 'team' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Position',
			'id'		=> $prefix . 'team_position',
			'type'		=> 'text',
			'desc'		=> 'Enter the team member\'s position within the team.'
		),
		array(
			'label'		=> 'Email address',
			'id'		=> $prefix . 'team_email',
			'type'		=> 'text',
			'desc'		=> 'Enter the team member\'s email address.'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Partner post type
/* ---------------------------------------------------------------------- */
$post_type_partner = array(
	'id'          => 'partner-setting',
	'title'       => 'Partner meta',
	'desc'        => '',
	'pages'       => array( 'partner' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Logo link',
			'id'		=> $prefix . 'partner_link',
			'type'		=> 'text',
			'desc'		=> 'Enter website address of parnter\' logo'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Branch
/* ---------------------------------------------------------------------- */
$post_type_branch = array(
	'id'          => 'branch-meta',
	'title'       => 'Branch meta',
	'desc'        => '',
	'pages'       => array( 'branch' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Latitude and Longitude',
			'id'		=> $prefix . 'lat_long',
			'type'		=> 'text',
			'desc'		=> 'e.g. 11.544873,104.892167. You can get this value from <a href="http://itouchmap.com/latlong.html" target="_blank">itouchmap</a>'
		),
		array(
			'label'		=> 'Adress',
			'id'		=> $prefix . 'branch_address',
			'type'		=> 'textarea',
			'rows'		=> '2'
		),
		array(
			'label'		=> 'Tel',
			'id'		=> $prefix . 'branch_tel',
			'type'		=> 'text',
			'desc'		=> 'e.g. (855)-23-990 225/ 10 8888 76'
		),
		array(
			'label'		=> 'E-mail',
			'id'		=> $prefix . 'branch_email',
			'type'		=> 'text',
			'desc'		=> 'e.g. info@seilanithih.com.kh'
		),
	)
);

/* ---------------------------------------------------------------------- */
/*	Post Format: video
/* ---------------------------------------------------------------------- */
$post_format_video = array(
	'id'          => 'format-video',
	'title'       => 'Format: Video',
	'desc'        => 'These settings enable you to embed videos into your posts.',
	'pages'       => array( 'post' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Video URL',
			'id'		=> $prefix . 'video_url',
			'type'		=> 'text',
			'desc'		=> 'Recommended to use.'
		),
		array(
			'label'		=> 'Video Embed Code',
			'id'		=> $prefix . 'video_embed_code',
			'type'		=> 'textarea',
			'rows'		=> '2'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Post Format: Audio
/* ---------------------------------------------------------------------- */
$post_format_audio = array(
	'id'          => 'format-audio',
	'title'       => 'Format: Audio',
	'desc'        => 'These settings enable you to embed audio into your posts. You must provide both .mp3 and .ogg/.oga file formats in order for self hosted audio to function accross all browsers.',
	'pages'       => array( 'post' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'MP3 File URL',
			'id'		=> $prefix . 'audio_mp3_url',
			'type'		=> 'upload',
			'desc'		=> 'The URL to the .mp3 or .m4a audio file'
		),
		array(
			'label'		=> 'OGA File URL',
			'id'		=> $prefix . 'audio_ogg_url',
			'type'		=> 'upload',
			'desc'		=> 'The URL to the .oga, .ogg audio file'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Post Format: Gallery
/* ---------------------------------------------------------------------- */
$post_format_gallery = array(
	'id'          => 'format-gallery',
	'title'       => 'Format: Gallery',
	'desc'        => 'Standard post galleries.</i>',
	'pages'       => array( 'post' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Upload photo',
			'id'		=> $prefix . 'post_gallery',
			'type'		=> 'gallery',
			'desc'		=> 'Upload photos'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Post Format: Chat
/* ---------------------------------------------------------------------- */
$post_format_chat = array(
	'id'          => 'format-chat',
	'title'       => 'Format: Chat',
	'desc'        => 'Input chat dialogue.',
	'pages'       => array( 'post' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Chat Text',
			'id'		=> $prefix . 'chat',
			'type'		=> 'textarea',
			'rows'		=> '2'
		)
	)
);
/* ---------------------------------------------------------------------- */
/*	Post Format: Link
/* ---------------------------------------------------------------------- */
$post_format_link = array(
	'id'          => 'format-link',
	'title'       => 'Format: Link',
	'desc'        => 'Input your link.',
	'pages'       => array( 'post' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Link Title',
			'id'		=> $prefix . 'link_title',
			'type'		=> 'text'
		),
		array(
			'label'		=> 'Link URL',
			'id'		=> $prefix . 'link_url',
			'type'		=> 'text'
		)
	)
);

/* ---------------------------------------------------------------------- */
/*	Post Format: quote
/* ---------------------------------------------------------------------- */
$post_format_quote = array(
	'id'          => 'format-quote',
	'title'       => 'Format: Quote',
	'desc'        => 'Input your quote.',
	'pages'       => array( 'post' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		array(
			'label'		=> 'Quote',
			'id'		=> $prefix . 'quote',
			'type'		=> 'textarea',
			'rows'		=> '2'
		),
		array(
			'label'		=> 'Quote Author',
			'id'		=> $prefix . 'quote_author',
			'type'		=> 'text'
		)
	)
);

/*  Register meta boxes
/* ------------------------------------ */
	ot_register_meta_box( $page_layout_options );
	ot_register_meta_box( $post_format_audio );
	ot_register_meta_box( $post_format_chat );
	ot_register_meta_box( $post_format_gallery );
	ot_register_meta_box( $post_format_link );
	ot_register_meta_box( $post_format_quote );
	ot_register_meta_box( $post_format_video );
	ot_register_meta_box( $masthead_options );
	ot_register_meta_box( $post_type_home_slider );
	ot_register_meta_box( $post_type_team );
	ot_register_meta_box( $post_type_partner );
	ot_register_meta_box( $post_type_branch );
}