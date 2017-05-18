<?php
/**
 * Pods String & HTML
 */
$data = array(
	'label'       => __( 'Post Field Display (Dropdown)', 'pods-beaver-themer' ),
	'group'       => 'pods',
	'type'        => array(
		'string',
		'html',
		'custom_field',
	),
	'getter'      => 'PodsPageData::get_field_display',
	'placeholder' => __( 'Lorem Ipsum ...', 'pods-beaver-themer' ),
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Field Name (CPT)', 'pods-beaver-themer' ),
	),
);

// $form = PodsPageData::pods_settings_form();

FLPageData::add_post_property( 'pods_display', $data );
FLPageData::add_post_property_settings_fields( 'pods_display', $form );

/**
 * Pods Url
 */
$data = array(
	'label'       => __( 'Url Field (Dropdown)', 'pods-beaver-themer' ),
	'group'       => 'pods',
	'type'        => array(
		'url',
		'custom_field',
	),
	'getter'      => 'PodsPageData::get_field_display_url',
	'placeholder' => __( 'Lorem Ipsum ...', 'pods-beaver-themer' ),
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_url_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Field Name (CPT)', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_url', $data );
FLPageData::add_post_property_settings_fields( 'pods_url', $form );

/**
 * Pods Multiple Photos (Images)
 */
$data = array(
	'label'       => __( 'Multiple Images', 'pods-beaver-themer' ),
	'group'       => 'pods',
	'type'        => array(
		'multiple-photos',
	),
	'getter'      => 'PodsPageData::get_field_multiple_photos',
	'placeholder' => __( 'Lorem Ipsum ...', 'pods-beaver-themer' ),
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_multiple_images_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
	),
);

FLPageData::add_post_property( 'pods_multiple_photos', $data );
FLPageData::add_post_property_settings_fields( 'pods_multiple_photos', $form );

/**
 * Pods Photo (Image)
 */
$data = array(
	'label'       => __( 'Image', 'pods-beaver-themer' ),
	'group'       => 'pods',
	'type'        => array(
		'photo',
	),
	'getter'      => 'PodsPageData::get_field_photo',
	'placeholder' => __( 'Lorem Ipsum ...', 'pods-beaver-themer' ),
);

$form = array(
	'field'       => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_image_fields',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Field Name (CPT)', 'pods-beaver-themer' ),
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
 * Pods Templates for HTML & STRING
 */
$data = array(
	'label'       => __( 'Post Template (Dropdown)', 'pods-beaver-themer' ),
	'group'       => 'pods',
	'type'        => array(
		'string',
		'html',
	),
	'getter'      => 'PodsPageData::get_template',
	'placeholder' => __( 'Lorem Ipsum ...', 'pods-beaver-themer' ),
);

$form = array(
	'template' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_templates',
		'help'        => __( 'Fields filtered based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Fields based on Preview', 'pods-beaver-themer' ),
		'placeholder' => __( 'Field Name (CPT)', 'pods-beaver-themer' ),
	),
);

// $form = PodsPageData::pods_settings_form();

FLPageData::add_post_property( 'pods_template', $data );
FLPageData::add_post_property_settings_fields( 'pods_template', $form );

/**
 * Pods All using Magic Tag Syntax
 */
$data = array(
	'label'       => __( 'Advanced Magic Tag (improvements in a future release)', 'pods-beaver-themer' ),
	'group'       => 'pods',
	'type'        => array(
		'string',
		'html',
		'custom_field',
		'photo',
		'multiple-photos',
	),
	'getter'      => 'PodsPageData::get_field_display',
	'placeholder' => __( 'Lorem Ipsum ...', 'pods-beaver-themer' ),
);

$form = array(
	'field' => array(
		'type'        => 'text',
		'label'       => __( 'Field Name (CPT):', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_fields',
		'help'        => __( 'Fields based on current "preview" settings', 'pods-beaver-themer' ),
		'description' => __( 'Field Name', 'pods-beaver-themer' ),
		'placeholder' => __( 'Field Name (CPT)', 'pods-beaver-themer' ),
	),
);

// $form = PodsPageData::pods_settings_form();

FLPageData::add_post_property( 'pods', $data );
FLPageData::add_post_property_settings_fields( 'pods', $form );
