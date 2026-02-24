<?php

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// phpcs:ignoreFile WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

/**
 * ***************************Documentation****************************
 * string, html, Properties
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current location" )
 */

/**
 * Pods CPT / TAX / …
 */
$data = [
	'label'  => __( 'Post, Page, Term…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'string',
		'html',
		'custom_field',
	],
	'getter' => 'PodsBeaverPageData::get_field_display',
];

$form = [
	'field' => [
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_fields',
		'help'        => __( 'Field list is based on current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Based on preview location', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_display', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_display', $form );

/**
 * Pods Templates / Magic Tag
 */
$data = [
	'label'  => __( 'Template, Magic Tag', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'string',
		'html',
		'custom_field',
		'url',
	],
	'getter' => 'PodsBeaverPageData::get_template',
];

$form = [
	'template'        => [
		'type'        => 'select',
		'label'       => __( 'Template', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_templates',
		'toggle'      => [
			'custom' => [
				'fields' => [
					'custom_template',
				],
			],
		],
		'help'        => __( 'Create Templates in Pods Admin under Templates.', 'pods-beaver-builder-themer-add-on' ),
		'description' => '<br />' . __( '<a href="http://pods.io/docs/build/using-magic-tags/" target="_blank">See Documentation &raquo;</a>', 'pods-beaver-builder-themer-add-on' ),
	],
	'custom_template' => [
		'type'        => 'text',
		'label'       => __( 'Magic Tags:', 'pods-beaver-builder-themer-add-on' ),
		'help'        => __( 'Full suppoort for Magic Tags & HTML but no further shortcodes!', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( '<a href="http://pods.io/docs/build/using-magic-tags/" target="_blank">See Documentation &raquo;</a>', 'pods-beaver-builder-themer-add-on' ),
		'default'     => '{@your_field}',
		'placeholder' => __( 'HTML & Magic Tags only', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_template', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_template', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings
 */
$data = [
	'label'  => __( 'Settings, Author, User…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'string',
		'html',
		'custom_field',
	],
	'getter' => 'PodsBeaverPageData::get_field_display',
];

$form = [
	// @phpstan-ignore-next-line
	'fields' => PodsBeaverPageData::pods_get_settings_fields(),
];

// @phpstan-ignore-next-line
FLPageData::add_site_property( 'pods_settings', $data );
// @phpstan-ignore-next-line
FLPageData::add_site_property_settings_fields( 'pods_settings', $form );

/**
 * *******************************************************************
 * Photo (image) Properties
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current post" )
 */

/**
 * Pods Photo (Image)
 */
$data = [
	'label'  => __( 'Image: Post, Page, Term…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'photo',
	],
	'getter' => 'PodsBeaverPageData::get_field_photo',
];

$form = [
	'field'       => [
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_image_fields',
		'help'        => __( 'Field list is based on current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Based on preview location', 'pods-beaver-builder-themer-add-on' ),
	],
	'image_size'  => [
		'type'    => 'photo-sizes',
		'label'   => __( 'Image Size', 'pods-beaver-builder-themer-add-on' ),
		'default' => 'full-size',
	],
	'default_img' => [
		'type'  => 'photo',
		'label' => __( 'Default Image', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_photo', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_photo', $form );

/**
 * Manual Photo Field
 */
$data = [
	'label'  => __( 'Image: Magic Tag', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'photo',
	],
	'getter' => 'PodsBeaverPageData::get_field_photo',
];

$form = [
	'field'       => [
		'type'        => 'text',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Only works for image fields', 'pods-beaver-builder-themer-add-on' ),
		'help'        => __( 'Enter field name, traversal is supported.', 'pods-beaver-builder-themer-add-on' ),
		'placeholder' => __( 'Example: category.image_field', 'pods-beaver-builder-themer-add-on' ),
	],
	'image_size'  => [
		'type'    => 'photo-sizes',
		'label'   => __( 'Image Size', 'pods-beaver-builder-themer-add-on' ),
		'default' => 'full-size',
	],
	'default_img' => [
		'type'  => 'photo',
		'label' => __( 'Default Image', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_photo_manual', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_photo_manual', $form );

/**
 * Pods Multiple Photos (Images)
 */
$data = [
	'label'  => __( 'Images: Post, Page, Term…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'multiple-photos',
	],
	'getter' => 'PodsBeaverPageData::get_field_multiple_photos',
];

$form = [
	'field' => [
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_multiple_images_fields',
		'help'        => __( 'Field list is based on current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Based on preview location', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_multiple_photos', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_multiple_photos', $form );


/**
 * Manual Multiple Photo Field
 */
$data = [
	'label'  => __( 'Images: Magic Tag', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'multiple-photos',
	],
	'getter' => 'PodsBeaverPageData::get_field_multiple_photos',
];

$form = [
	'field' => [
		'type'        => 'text',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Only works for image fields', 'pods-beaver-builder-themer-add-on' ),
		'help'        => __( 'Enter field name, traversal is supported.', 'pods-beaver-builder-themer-add-on' ),
		'placeholder' => __( 'Example: category.image_field', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_multiple_photos_manual', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_multiple_photos_manual', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings Photo (Image)
 */
$data = [
	'label'  => __( 'Image: Settings, Author, User…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'photo',
	],
	'getter' => 'PodsBeaverPageData::get_field_photo',
];

$setting_field_args = [
	'type'    => 'file',
	'options' => [
		'file_format_type' => 'single',
	],
];

$form = [
	// @phpstan-ignore-next-line
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
];

$form['fields']['image_size'] = [
	'type'    => 'photo-sizes',
	'label'   => __( 'Image Size', 'pods-beaver-builder-themer-add-on' ),
	'default' => 'full-size',
];

$form['fields']['default_img'] = [
	'type'  => 'photo',
	'label' => __( 'Default Image', 'pods-beaver-builder-themer-add-on' ),
];

// @phpstan-ignore-next-line
FLPageData::add_site_property( 'pods_settings_photo', $data );
// @phpstan-ignore-next-line
FLPageData::add_site_property_settings_fields( 'pods_settings_photo', $form );

/**
 * Pods Settings Multiple Photos (Images)
 */
$data = [
	'label'  => __( 'Images: Settings, Author, User…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'multiple-photos',
	],
	'getter' => 'PodsBeaverPageData::get_field_multiple_photos',
];

$setting_field_args = [
	'type'    => 'file',
	'options' => [
		'file_format_type' => 'multi',
	],
];

$form = [
	// @phpstan-ignore-next-line
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
];

// @phpstan-ignore-next-line
FLPageData::add_site_property( 'pods_settings_multiple_photos', $data );
// @phpstan-ignore-next-line
FLPageData::add_site_property_settings_fields( 'pods_settings_multiple_photos', $form );

/**
 * *******************************************************************
 * url Properties
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current post" )
 */

/**
 * Pods CPT
 */
$data = [
	'label'  => __( 'URL: Post, Page, Term…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'url',
		'custom_field',
	],
	'getter' => 'PodsBeaverPageData::get_field_display_url',
];

$form = [
	'field' => [
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_url_fields',
		'help'        => __( 'Field list is based on current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Based on preview location', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_url', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_url', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings / User
 */
$data = [
	'label'  => __( 'URL: Settings, Author, User…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'url',
		'custom_field',
	],
	'getter' => 'PodsBeaverPageData::get_field_display_url',
];

$setting_field_args = [
	'type' => 'website',
];

$form = [
	// @phpstan-ignore-next-line
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
];

// @phpstan-ignore-next-line
FLPageData::add_site_property( 'pods_settings_url', $data );
// @phpstan-ignore-next-line
FLPageData::add_site_property_settings_fields( 'pods_settings_url', $form );

/**
 * *******************************************************************
 * color
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current location" )
 */

/**
 * Pods CPT / TAX / …
 */
$data = [
	'label'  => __( 'Color: Post, Page, Term…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'color',
	],
	'getter' => 'PodsBeaverPageData::get_field_color',
];

$form = [
	'field' => [
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_color_fields',
		'help'        => __( 'Field list is based on current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Based on preview location', 'pods-beaver-builder-themer-add-on' ),
	],
];

// @phpstan-ignore-next-line
FLPageData::add_post_property( 'pods_color', $data );
// @phpstan-ignore-next-line
FLPageData::add_post_property_settings_fields( 'pods_color', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings
 */
$data = [
	'label'  => __( 'Color: Settings, Author, User…', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => [
		'color',
	],
	'getter' => 'PodsBeaverPageData::get_field_color',
];

$setting_field_args = [
	'type' => 'color',
];

$form = [
	// @phpstan-ignore-next-line
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
];

// @phpstan-ignore-next-line
FLPageData::add_site_property( 'pods_settings_color', $data );
// @phpstan-ignore-next-line
FLPageData::add_site_property_settings_fields( 'pods_settings_color', $form );
