<?php

/**
 * Pods String & HTML
 */
$data = array(
	'label'       => __( 'Display Field (Dropdown)', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'string', 'html', 'url' ),
	'getter'      => 'PodsPageData::get_field_display',
	'placeholder' => __( 'Lorem Ipsum ...', 'fl-theme-builder' )
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'fl-theme-builder' ),
		'options'     => 'PodsPageData::pods_get_fields',
		'help'        => __( 'If two CPT have a field with the same name - the field is only listed once - this way you could use the same Template for diffrent CPT if they use the same field names.)', 'fl-theme-builder' ),
		'description' => __( 'Select one', 'fl-theme-builder' ),
		'placeholder' => __( 'Field Name (CPT)', 'fl-theme-builder' )

	)
);

// $form = PodsPageData::pods_settings_form();

FLPageData::add_post_property( 'pods', $data );
FLPageData::add_post_property_settings_fields( 'pods', $form );


/**
 * Pods Url
 */
$data = array(
	'label'       => __( 'Display Url (Dropdown)', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'url' ),
	'getter'      => 'PodsPageData::get_field_display_url',
	'placeholder' => __( 'Lorem Ipsum ...', 'fl-theme-builder' )
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'fl-theme-builder' ),
		'options'     => 'PodsPageData::pods_get_url_fields',
		'help'        => __( 'If two CPT have a field with the same name - the field is only listed once - this way you could use the same Template for diffrent CPT if they use the same field names.)', 'fl-theme-builder' ),
		'description' => __( 'Select one', 'fl-theme-builder' ),
		'placeholder' => __( 'Field Name (CPT)', 'fl-theme-builder' )

	)
);


FLPageData::add_post_property( 'pods_url', $data );
FLPageData::add_post_property_settings_fields( 'pods_url', $form );

/**
 * Pods Multiple Photos (Images)
 */
$data = array(
	'label'       => __( 'Display Multiple Images', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'multiple-photos' ),
	'getter'      => 'PodsPageData::get_field_multiple_photos',
	'placeholder' => __( 'Lorem Ipsum ...', 'fl-theme-builder' )
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'fl-theme-builder' ),
		'options'     => 'PodsPageData::pods_get_multiple_images_fields',
		'help'        => __( 'If two CPT have a field with the same name - the field is only listed once - this way you could use the same Template for diffrent CPT if they use the same field names.)', 'fl-theme-builder' ),
		'description' => __( 'Select one', 'fl-theme-builder' ),
	)
);


FLPageData::add_post_property( 'pods-multiple-photos', $data );
FLPageData::add_post_property_settings_fields( 'pods-multiple-photos', $form );

/**
 * Pods Photos (Images)
 */
$data = array(
	'label'       => __( 'Display Image', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'photo' ),
	'getter'      => 'PodsPageData::get_field_photo',
	'placeholder' => __( 'Lorem Ipsum ...', 'fl-theme-builder' )
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'fl-theme-builder' ),
		'options'     => 'PodsPageData::pods_get_multiple_images_fields',
		'help'        => __( 'If two CPT have a field with the same name - the field is only listed once - this way you could use the same Template for diffrent CPT if they use the same field names.)', 'fl-theme-builder' ),
		'description' => __( 'Select one', 'fl-theme-builder' ),
		'placeholder' => __( 'Field Name (CPT)', 'fl-theme-builder' )

	)
);

FLPageData::add_post_property( 'pods-photos', $data );
FLPageData::add_post_property_settings_fields( 'pods-photos', $form );

/**
 * Pods String & HTML
 */
$data = array(
	'label'       => __( 'Display Field (Dropdown)', 'fl-theme-builder' ),
	'group'       => 'pods',
	'type'        => array( 'string', 'html', 'url' ),
	'getter'      => 'PodsPageData::get_field_display',
	'placeholder' => __( 'Lorem Ipsum ...', 'fl-theme-builder' )
);

$form = array(
	'field' => array(
		'type'        => 'select',
		'label'       => __( 'Field Name (CPT):', 'fl-theme-builder' ),
		'options'     => 'PodsPageData::pods_get_fields',
		'help'        => __( 'If two CPT have a field with the same name - the field is only listed once - this way you could use the same Template for diffrent CPT if they use the same field names.)', 'fl-theme-builder' ),
		'description' => __( 'Select one', 'fl-theme-builder' ),
		'placeholder' => __( 'Field Name (CPT)', 'fl-theme-builder' )

	)
);

// $form = PodsPageData::pods_settings_form();

FLPageData::add_post_property( 'pods', $data );
FLPageData::add_post_property_settings_fields( 'pods', $form );
