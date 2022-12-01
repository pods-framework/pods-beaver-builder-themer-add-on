<?php
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
$data = array(
	'label'        => __( 'Post, Page, Term…', 'pods-beaver-builder-themer-add-on' ),
	'group'        => 'pods',
	'type'         => array(
		'string',
		'html',
		'custom_field',
	),
	'getter'       => 'PodsBeaverPageData::get_field_display',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_fields',
		'help'        => __( 'Field list options are based on the current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'List options are based on your current preview location. <br /><br />Not sure what a display value is? Check out the <a style="text-decoration: underline" href="https://docs.pods.io/code/pods/display/">Pods display() docs</a>.', 'pods-beaver-builder-themer-add-on' ),
	),
);

FLPageData::add_post_property( 'pods_display', $data );
FLPageData::add_post_property_settings_fields( 'pods_display', $form );

/**
 * Pods CPT / Taxonomy / Term as a $pod->field() value
 */
$data = array(
	'label'        => __( 'Post, Page, or Term (Field Value)', 'pods-beaver-builder-themer-add-on' ),
	'group'        => 'pods',
	'type'         => array(
		'string',
		'html',
		'custom_field',
	),
	'getter'       => 'PodsBeaverPageData::get_field_value',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_fields',
		'help'        => __( 'Field list options are based on the current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'List options are based on your current preview location. <br /><br />Not sure what a field value is? Check out the <a style="text-decoration: underline" href="https://docs.pods.io/code/pods/field/">Pods field() docs</a>.', 'pods-beaver-builder-themer-add-on' ),
	),
);

FLPageData::add_post_property( 'pods_field', $data );
FLPageData::add_post_property_settings_fields( 'pods_field', $form );

/**
 * Pods Templates / Magic Tag
 */
$data = array(
	'label'  => __( 'Template, Magic Tag', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field',
		'url',
	),
	'getter' => 'PodsBeaverPageData::get_template',
);

$form = array(
	'template'        => array(
		'type'        => 'select',
		'label'       => __( 'Template', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_templates',
		'toggle'      => array(
			'custom' => array(
				'fields' => array(
					'custom_template',
				),
			),
		),
		'help'        => __( 'Create Templates in Pods Admin under Templates.', 'pods-beaver-builder-themer-add-on' ),
		'description' => '<br />' . __( '<a href="http://pods.io/docs/build/using-magic-tags/" target="_blank">See Documentation &raquo;</a>', 'pods-beaver-builder-themer-add-on' ),
	),
	'custom_template' => array(
		'type'        => 'text',
		'label'       => __( 'Magic Tags:', 'pods-beaver-builder-themer-add-on' ),
		'help'        => __( 'Full suppoort for Magic Tags & HTML but no further shortcodes!', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( '<a href="http://pods.io/docs/build/using-magic-tags/" target="_blank">See Documentation &raquo;</a>', 'pods-beaver-builder-themer-add-on' ),
		'default'     => '{@your_field}',
		'placeholder' => __( 'HTML & Magic Tags only', 'pods-beaver-builder-themer-add-on' ),
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
	'label'  => __( 'Settings, Author, User', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => array(
		'string',
		'html',
		'custom_field',
	),
	'getter' => 'PodsBeaverPageData::get_field_display',
);

$form = array(
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
	'label'  => __( 'Image: Post, Page, Term', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => array(
		'photo',
	),
	'getter' => 'PodsBeaverPageData::get_field_photo',
);

$form = array(
	'field'       => array(
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_image_fields',
		'help'        => __( 'Field list options are based on the current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'List options are based on your current preview location.', 'pods-beaver-builder-themer-add-on' ),
	),
	'image_size'  => array(
		'type'    => 'photo-sizes',
		'label'   => __( 'Image Size', 'pods-beaver-builder-themer-add-on' ),
		'default' => 'full-size',
	),
	'default_img' => array(
		'type'  => 'photo',
		'label' => __( 'Default Image', 'pods-beaver-builder-themer-add-on' ),
	),
);

FLPageData::add_post_property( 'pods_photo', $data );
FLPageData::add_post_property_settings_fields( 'pods_photo', $form );

/**
 * Manual Photo Field
 */
$data = array(
	'label'  => __( 'Image: Magic Tag', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => array(
		'photo'
	),
	'getter' => 'PodsBeaverPageData::get_field_photo',
);

$form = array(
	'field'       => array(
		'type'        => 'text',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Only works for image fields', 'pods-beaver-builder-themer-add-on' ),
		'help'        => __( 'Enter field name, traversal is supported.', 'pods-beaver-builder-themer-add-on' ),
		'placeholder' => __( 'Example: category.image_field', 'pods-beaver-builder-themer-add-on' ),
	),
	'image_size'  => array(
		'type'    => 'photo-sizes',
		'label'   => __( 'Image Size', 'pods-beaver-builder-themer-add-on' ),
		'default' => 'full-size',
	),
	'default_img' => array(
		'type'  => 'photo',
		'label' => __( 'Default Image', 'pods-beaver-builder-themer-add-on' ),
	),
);

FLPageData::add_post_property( 'pods_photo_manual', $data );
FLPageData::add_post_property_settings_fields( 'pods_photo_manual', $form );

/**
 * Pods Multiple Photos (Images)
 */
$data = array(
	'label'  => __( 'Images: Post, Page, Term', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => array(
		'multiple-photos',
	),
	'getter' => 'PodsBeaverPageData::get_field_multiple_photos',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_multiple_images_fields',
		'help'        => __( 'Field list options are based on the current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'List options are based on your current preview location.', 'pods-beaver-builder-themer-add-on' ),
	),
);

FLPageData::add_post_property( 'pods_multiple_photos', $data );
FLPageData::add_post_property_settings_fields( 'pods_multiple_photos', $form );


/**
 * Manual Multiple Photo Field
 */
$data = array(
	'label'  => __( 'Images: Magic Tag', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => array(
		'multiple-photos'
	),
	'getter' => 'PodsBeaverPageData::get_field_multiple_photos',
);

$form = array(
	'field'       => array(
		'type'        => 'text',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'Only works for image fields', 'pods-beaver-builder-themer-add-on' ),
		'help'        => __( 'Enter field name, traversal is supported.', 'pods-beaver-builder-themer-add-on' ),
		'placeholder' => __( 'Example: category.image_field', 'pods-beaver-builder-themer-add-on' ),
	),
);

FLPageData::add_post_property( 'pods_multiple_photos_manual', $data );
FLPageData::add_post_property_settings_fields( 'pods_multiple_photos_manual', $form );

/**
 * SITE WIDE PROPERTY'S
 */

/**
 * Pods Settings Photo (Image)
 */
$data = array(
	'label'  => __( 'Image: Settings, Author, User', 'pods-beaver-builder-themer-add-on' ),
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
	'fields' => PodsBeaverPageData::pods_get_settings_fields( $setting_field_args ),
);

$form['fields']['image_size'] = array(
	'type'    => 'photo-sizes',
	'label'   => __( 'Image Size', 'pods-beaver-builder-themer-add-on' ),
	'default' => 'full-size',
);

$form['fields']['default_img'] = array(
	'type'  => 'photo',
	'label' => __( 'Default Image', 'pods-beaver-builder-themer-add-on' ),
);

FLPageData::add_site_property( 'pods_settings_photo', $data );
FLPageData::add_site_property_settings_fields( 'pods_settings_photo', $form );

/**
 * Pods Settings Multiple Photos (Images)
 */
$data = array(
	'label'  => __( 'Images: Settings, Author, User', 'pods-beaver-builder-themer-add-on' ),
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
	'label'  => __( 'URL: Post, Page, Term', 'pods-beaver-builder-themer-add-on' ),
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
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_url_fields',
		'help'        => __( 'Field list options are based on the current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'List options are based on your current preview location.', 'pods-beaver-builder-themer-add-on' ),
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
	'label'  => __( 'URL: Settings, Author, User', 'pods-beaver-builder-themer-add-on' ),
	'group'  => 'pods',
	'type'   => array(
		'url',
		'custom_field',
	),
	'getter' => 'PodsBeaverPageData::get_field_display_url',
);

$setting_field_args = array(
	'type' => 'website',
);

$form = array(
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
 * Pods CPT / Taxonomy
 */
$data = array(
	'label'        => __( 'Color: Post, Page, Term', 'pods-beaver-builder-themer-add-on' ),
	'group'        => 'pods',
	'type'         => array(
		'color',
	),
	'getter'       => 'PodsBeaverPageData::get_field_color',
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name', 'pods-beaver-builder-themer-add-on' ),
		'options'     => 'PodsBeaverPageData::pods_get_color_fields',
		'help'        => __( 'Field list options are based on the current "Preview as:" settings in the top left.', 'pods-beaver-builder-themer-add-on' ),
		'description' => __( 'List options are based on your current preview location.', 'pods-beaver-builder-themer-add-on' ),
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
	'label'  => __( 'Color: Settings, Author, User', 'pods-beaver-builder-themer-add-on' ),
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
