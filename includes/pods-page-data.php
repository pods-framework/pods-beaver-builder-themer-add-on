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
	'label'  => __( 'Field / Related Field', 'pods-beaver-themer' ),
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
 * Pods Templates / Magic Tag
 */
$data = array(
	'label'  => __( 'Template or Magic Tag', 'pods-beaver-themer' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field'
	),
	'getter' => 'PodsPageData::get_template',
);

$form = array(
	'template'       => array(
		'type'        => 'select',
		'label'       => __( 'Template:', 'pods-beaver-themer' ),
		'options'     => 'PodsPageData::pods_get_templates',
		'toggle'      => array(
			'custom' => array(
				'fields' => array( 'custom_template' )
			)
		),
		'help'        => __( 'Select Template', 'pods-beaver-themer' ),
		'description' => __( 'Select Template', 'pods-beaver-themer' ),
		'placeholder' => __( 'Template Connection', 'pods-beaver-themer' ),
	),
	'custom_template' => array(
		'type'        => 'text',
		'label'       => __( 'Magic Tag:', 'pods-beaver-themer' ),
		'default'     => '',
		'placeholder' => __( 'Magic Tag only - no [each, if,...]', 'fl-builder' ),
		'rows'        => '6'
	)
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
	'label'       => __( 'User and SettingsPod Fields', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'string', 'html', 'custom_field' ),
	'getter'      => 'PodsPageData::get_field_display',
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
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings Photo (Image)
 */
$data = array(
	'label'       => __( 'User and SettingsPod Fields', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'photo' ),
	'getter'      => 'PodsPageData::get_field_photo',
);

$form = array(
	'title'  => __( 'Awesome', 'fl-builder' ),
	'fields' => PodsPageData::pods_get_settings_fields( array(
			'type'    => 'file',
			'options' => array( 'file_format_type' => 'single' )
		)
	),
);


FLPageData::add_site_property( 'pods_settings_photo', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings_photo', $form );


/**
 * Pods Settings Multiple Photos (Images)
 */
$data = array(
	'label'  => __( 'User and SettingsPod Fields', 'fl-theme-builder' ),
	'group'  => 'pods',
	'type'   => array( 'multiple-photos' ),
	'getter' => 'PodsPageData::get_field_multiple_photos',
);

$form = array(
	'title'  => __( 'Awesome', 'fl-builder' ),
	'fields' => PodsPageData::pods_get_settings_fields( array(
			'type'    => 'file',
			'options' => array( 'file_format_type' => 'single' )
		)
	),
);


FLPageData::add_site_property( 'pods_settings_multiple_photos', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings_multiple_photos', $form );


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

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings / User
 */
$data = array(
	'label'       => __( 'User and SettingsPod Fields', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'url' ),
	'getter'      => 'PodsPageData::get_field_display_url',
);

$form = array(
	'title'  => __( 'Awesome', 'fl-builder' ),
	'fields' => PodsPageData::pods_get_settings_fields( array( 'type' => 'website' ) ),
);


FLPageData::add_site_property( 'pods_settings_url', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings_url', $form );
