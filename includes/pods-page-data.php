<?php
// @todo Revisit label and description text

/**
 * *******************************************************************
 * string, html, Properties
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current location" )
 */

/**
 * Pods CPT / TAX / ...
 */
$data = array(
	'label'        => __( 'Field / Related Field', 'pods-beaver-themer' ),
	'preview_text' => 'label',
	'group'        => 'pods',
	'type'         => array(
		'string',
		'html',
		'custom_field',
	),
	'getter'       => 'PodsBeaverPageData::get_field_display',
	'js'           => '',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsBeaverPageData::pods_get_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Selection based on Preview', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_display', $data );
FLPageData::add_post_property_settings_fields( 'pods_display', $form );

/**
 * Pods Templates / Magic Tag
 */
$data = array(
	'label'  => __( 'Template or Magic Tag', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field',
	),
	'getter' => 'PodsBeaverPageData::get_template',
);

$form = array(
	'template'        => array(
		'type'        => 'select',
		'label'       => __( 'Template:', 'pods-beaver-themer' ),
		'options'     => 'PodsBeaverPageData::pods_get_templates',
		'toggle'      => array(
			'custom' => array(
				'fields' => array(
					'custom_template',
				),
			),
		),
		'help'        => __( 'Select Template', 'pods-beaver-themer' ),
		'description' => __( 'Select Template', 'pods-beaver-themer' ),
		'placeholder' => __( 'Template Connection', 'pods-beaver-themer' ),
	),
	'custom_template' => array(
		'type'        => 'text',
		'label'       => __( 'Magic Tags:', 'pods-beaver-themer' ),
		'default'     => '',
		'placeholder' => __( 'HTML & Magic Tags only - no [each, if,...]', 'pods-beaver-themer' ),
		'rows'        => '6',
	),
);

FLPageData::add_post_property( 'pods_template', $data );
FLPageData::add_post_property_settings_fields( 'pods_template', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings
 */
$data = array(
	'label'  => __( 'User and SettingsPod Fields', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field',
	),
	'getter' => 'PodsBeaverPageData::get_field_display',
);

$form = array(
	'title'  => __( 'Awesome', 'pods-beaver-themer' ),
	'fields' => PodsBeaverPageData::pods_get_settings_fields(),
);

FLPageData::add_site_property( 'pods_settings', $data );
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
$data = array(
	'label'  => __( 'Post Field', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'photo',
	),
	'getter' => 'PodsBeaverPageData::get_field_photo',
);

$form = array(
	'field'       => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsBeaverPageData::pods_get_image_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Photo (image) Connection', 'pods-beaver-themer' ),
	),
	'image_size'  => array(
		'type'    => 'photo-sizes',
		'label'   => __( 'Image Size', 'pods-beaver-themer' ),
		'default' => 'full-size',
	),
	'default_img' => array(
		'type'  => 'photo',
		'label' => __( 'Default Image', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_photo', $data );
FLPageData::add_post_property_settings_fields( 'pods_photo', $form );

/**
 * Manual Photo Field
 */
$data = array(
	'label'  => __( 'Manual (for Taxonomy)', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'photo'
	),
	'getter' => 'PodsBeaverPageData::get_field_photo',
);

$form = array(
	'field'       => array(
		'type'        => 'text',
		'label'       => __( 'Field Name:', 'pods-beaver-themer' ),
		'help'        => __( 'Enter the field name, Relationship works', 'pods-beaver-themer' ),
		'description' => __( '', 'pods-beaver-themer' ), // @todo This doesn't seem right
		'placeholder' => __( 'e.g category.image', 'pods-beaver-themer' ),
	),
	'image_size'  => array(
		'type'    => 'photo-sizes',
		'label'   => __( 'Image Size', 'pods-beaver-themer' ),
		'default' => 'full-size',
	),
	'default_img' => array(
		'type'  => 'photo',
		'label' => __( 'Default Image', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_photo_manual', $data );
FLPageData::add_post_property_settings_fields( 'pods_photo_manual', $form );

/**
 * Pods Multiple Photos (Images)
 */
$data = array(
	'label'  => __( 'Multiple Photos (Images)', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'multiple-photos',
	),
	'getter' => 'PodsBeaverPageData::get_field_multiple_photos',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsBeaverPageData::pods_get_multiple_images_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ), // @todo This doesn't seem right
		'placeholder' => __( 'Multiple Photo Connection', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_multiple_photos', $data );
FLPageData::add_post_property_settings_fields( 'pods_multiple_photos', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings Photo (Image)
 */
$data = array(
	'label'  => __( 'User and SettingsPod Fields', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'photo',
	),
	'getter' => 'PodsBeaverPageData::get_field_photo',
);

$setting_field_args = array(
	'type'    => 'file',
	'options' => array(
		'file_format_type' => 'single',
	),
);

$form = array(
	'title'  => __( 'Awesome', 'pods-beaver-themer' ),
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
);

FLPageData::add_site_property( 'pods_settings_photo', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings_photo', $form );

/**
 * Pods Settings Multiple Photos (Images)
 */
$data = array(
	'label'  => __( 'User and SettingsPod Fields', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'multiple-photos',
	),
	'getter' => 'PodsBeaverPageData::get_field_multiple_photos',
);

$setting_field_args = array(
	'type'    => 'file',
	'options' => array(
		'file_format_type' => 'multi',
	),
);

$form = array(
	'title'  => __( 'Awesome', 'pods-beaver-themer' ),
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
);

FLPageData::add_site_property( 'pods_settings_multiple_photos', $data );
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
$data = array(
	'label'  => __( 'Url Field', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'url',
		'custom_field',
	),
	'getter' => 'PodsBeaverPageData::get_field_display_url',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsBeaverPageData::pods_get_url_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Selection based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Url Connection', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_url', $data );
FLPageData::add_post_property_settings_fields( 'pods_url', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings / User
 */
$data = array(
	'label'  => __( 'User and SettingsPod Fields', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'url',
	),
	'getter' => 'PodsBeaverPageData::get_field_display_url',
);

$setting_field_args = array(
	'type' => 'website',
);

$form = array(
	'title'  => __( 'Awesome', 'pods-beaver-themer' ),
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
);

FLPageData::add_site_property( 'pods_settings_url', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings_url', $form );

/**
 * *******************************************************************
 * color
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current location" )
 */

/**
 * Pods CPT / TAX / ...
 */
$data = array(
	'label'        => __( 'Color: Field / Related Field', 'pods-beaver-themer' ),
	'preview_text' => 'label',
	'group'        => 'pods',
	'type'         => array(
		'color',
	),
	'getter'       => 'PodsBeaverPageData::get_field_color',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsBeaverPageData::pods_get_color_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Selection based on Preview', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_color', $data );
FLPageData::add_post_property_settings_fields( 'pods_color', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings
 */
$data = array(
	'label'  => __( 'Color: User and SettingsPod Fields', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'color',
	),
	'getter' => 'PodsBeaverPageData::get_field_color',
);

$setting_field_args = array(
	'type' => 'color',
);

$form = array(
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
);

FLPageData::add_site_property( 'pods_settings_color', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings_color', $form );
