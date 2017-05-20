<?php
/**
 * *******************************************************************
 *
 * string, html, Properties
 *********************************************************************/


/**
 * POST PROPERTY'S ( based on "current location" )
 */

/**
 * Pods CPT / TAX / ...
 */
$data = array(
	'label'  => __( 'Any Field', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field',
	),
	'getter' => 'PodsPageData::get_field_display',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Selection based on Preview', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_display', $data );
FLPageData::add_post_property_settings_fields( 'pods_display', $form );

/**
 * Pods All using Magic Tag Syntax
 */
$data = array(
	'label'  => __( 'Advanced: Magic Tag / Traversal', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field',
	),
	'getter' => 'PodsPageData::get_field_display',
);

$form = array(
	'field' => array(
		'type'        => 'text',
		'label'       => __( 'Field Name:', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_fields',
		'help'        => __( 'Fields based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Field Name', 'pods-beaver-themer' ),
		'placeholder' => __( 'Advanced Connection', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods', $data );
FLPageData::add_post_property_settings_fields( 'pods', $form );

/**
 * Pods Templates
 */
$data = array(
	'label'  => __( 'Post Template', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field'
	),
	'getter' => 'PodsPageData::get_template',
);

$form = array(
	'template' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_templates',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Template Connection', 'pods-beaver-themer' ),
	),
);

FLPageData::add_site_property( 'pods_template', $data );
FLPageData::add_site_property_settings_fields( 'pods_template', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings
 */
$data = array(
	'label'       => __( 'Settings Pod', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'string', 'html', 'custom_field' ),
	'getter'      => 'PodsPageData::get_settings_field_display',
);

$form = array(
	'title'  => __( 'Awesome', 'fl-builder' ),
	'fields' => PodsPageData::pods_get_settings_fields(),
);


FLPageData::add_site_property( 'pods_settings', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings', $form );




/**
 * *******************************************************************
 *
 * Photo (image) Properties
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current post" )
 */

/**
 * Pods Photo (Image)
 */
$data = array(
	'label'  => __( 'Image', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'photo',
	),
	'getter' => 'PodsPageData::get_field_photo',
);

$form = array(
	'field'       => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_image_fields',
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

FLPageData::add_post_property( 'pods_photos', $data );
FLPageData::add_post_property_settings_fields( 'pods_photos', $form );

/**
 * Pods Multiple Photos (Images)
 */
$data = array(
	'label'  => __( 'Multiple Photos (Images)', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'multiple-photos',
	),
	'getter' => 'PodsPageData::get_field_multiple_photos',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_multiple_images_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Multiple Photo Connection', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_multiple_photos', $data );
FLPageData::add_post_property_settings_fields( 'pods_multiple_photos', $form );


/**
 * *******************************************************************
 *
 * url Properties
 *********************************************************************/

/**
 * POST PROPERTY'S ( based on "current post" )
 */

/**
 * Pods CPT
 */
$data = array(
	'label'       => __( 'Url Field', 'pods-beaver-themer' ),
	'group'       => 'pods',
	'type'        => array(
		'url',
		'custom_field',
	),
	'getter'      => 'PodsPageData::get_field_display_url',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_url_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Selection based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Url Connection', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_url', $data );
FLPageData::add_post_property_settings_fields( 'pods_url', $form );
