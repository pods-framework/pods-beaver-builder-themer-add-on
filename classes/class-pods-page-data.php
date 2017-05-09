<?php

/**
 * Handles logic for page data Pods properties.
 *
 * @since 1.0
 */
final class PodsPageData {
	/**
	 * @since 1.0
	 *
	 */
	static public function init() {
		FLPageData::add_group( 'pods', array(
			'label' => __( 'Pods', 'fl-theme-builder' )
		) );
	}

	/**
	 *
	 * Just Basic Field Display
	 * @todo add settings/code for qutput_type ( e.g IMAGES as url, image-link, ...)
	 * @todo add settings/code for image_size
	 *
	 *
	 * @since 1.0
	 * @return string
	 */
	static public function get_field_display( $settings, $property ) {

		$content = pods( get_post_type(), get_the_ID() )->display( $settings->field );

		return is_string( $content ) ? $content : '';
	}

	/**
	 *
	 * Basic URL
	 *
	 * @since 1.0
	 * @return string
	 */
	static public function get_field_display_url( $settings, $property ) {

		return esc_url( self::get_field_display( $settings, $property ) );
	}


	/**
	 *
	 * Multiple Photos - Returns an array of Image Gallery ID's
	 *
	 * @since 1.0
	 * @return array
	 */
	static public function get_field_multiple_photos( $settings, $property ) {

		// $content = pods( get_post_type(), get_the_ID() )->display($settings->field)."<hr><pre>".print_r( pods( get_post_type(), get_the_ID() )->field($settings->field) )."<hr><hr></pre>";

		$field   = $settings->field . '.ID';
		$content = pods( get_post_type(), get_the_ID() )->field( $field );

		return $content;
		// return is_string($content) ? $content : '';
	}

	/**
	 *
	 * Single Image / Photo - Returns Image Gallery ID && URL
	 *
	 * @since 1.0
	 *
	 * @param $settings
	 * @param $property
	 *
	 * @return array
	 */
	static public function get_field_photo( $settings, $property ) {

		$field_id = $settings->field . '.ID';

		$content = array(
			'id'  => pods( get_post_type(), get_the_ID() )->display( $field_id ),
			'url' => pods( get_post_type(), get_the_ID() )->display( $settings->field )
		);

		return $content;
	}


	/**
	 *
	 * @todo: $option['file_type'] if a user chooses 'custom' check further if only image file type???
	 *
	 * @since 1.0
	 *
	 * @param array $options
	 *
	 * @return array
	 * @internal param $option
	 *
	 */
	static public function pods_get_fields( $options = array() ) {

		$fields = array();
		$pods   = pods_api()->load_pods( array( 'names' => true ) );

		// check for xxx_formate_type

		foreach ( $pods as $name => $label ) {
			$pod        = pods( $name );
			$pod_fields = $pod->fields();


			foreach ( $pod_fields as $field_name => $_field ) {
				if ( $options ) {
					foreach ( $options as $_option => $option_value ) {
						if ( $option_value !== pods_v( $_option, $_field['options'] ) ) { //$pod->fields( $field_name, 'file_type' )
							continue 2;  // if one option it not matched we don't need the field -> go check the next one
						}
					}
				}

				$fields[ $field_name ] .= $_field['name'] . " (" . $name . ")  ";
			}

		}

		return $fields;
	}


	/**
	 *
	 *  Limit fields from pods to url fields ( -> file_format = 'url' )
	 *
	 * @since 1.0
	 * @return array
	 */

	static public function pods_get_url_fields() {
		$options['file_format'] = 'url';
		$fields                 = self::pods_get_fields( $options );

		return $fields;
	}

	/**
	 *
	 *  Limit fields from pods to image fields ( -> file_format = 'images' )
	 *
	 * @since 1.0
	 * @return array
	 */

	static public function pods_get_image_fields() {
		$options['file_format'] = 'images';
		$fields                 = self::pods_get_fields( $options );

		return $fields;
	}

	/**
	 *
	 *  Limit fields from pods to image fields ( -> file_format = 'images', file_format_type = 'multi' )
	 *
	 * @since 1.0
	 * @return array
	 */
	static public function pods_get_multiple_images_fields() {
		$options['file_type']        = 'images';
		$options['file_format_type'] = 'multi';
		$fields                      = self::pods_get_fields( $options );

		return $fields;
	}





	/**
	 *
	 * Experimental in Development not used yet -> goall full dynamic settings to allow e.g. for image fields to select output size & Style (url, wp, gallery,...)
	 *
	 */


	/**
	 * @since 1.0
	 * @return array
	 */
	static public function pods_get_option_fields() {

		// $pods = Pods_Templates_Auto_Template_Front_End::the_pods();
		$pods   = pods_api()->load_pods( array( 'names' => true ) );
		$fields = array();

		foreach ( $pods as $name => $label ) {
			$fields[ $name ]['fields'] = array( $name );
		}

		return $fields;
	}

	/**
	 *
	 * Helper to get a list of configured pods, maybe limit to the "location" the template is used (Filter fom Themer)
	 *
	 * @since 1.0
	 * @return array
	 */
	static public function pods_get_pods() {
		//$pods = array();
		// $pods = Pods_Templates_Auto_Template_Front_End::the_pods();
		$api = pods_api();

		/*		$_pods = $api->load_pods();
				foreach ( $_pods as $pod ) {
					$pods[ $pod['name'] ] = $pod['label'];
				}*/

		return $pods = $api->load_pods( array( 'names' => true ) );
	}


	/**
	 *
	 * Generates Settings Form -
	 *
	 * @since 1.0
	 * @return array
	 */
	static public function pods_settings_form() {
		$form = array(
			'pods_pod' => array(
				'type'        => 'select',
				'label'       => __( 'Configured Pod Fields', 'fl-theme-builder' ),
				'options'     => self::pods_get_pods(),
				'toggle'      => self::pods_get_option_fields(),
				'help'        => __( 'Some Help Text', 'fl-theme-builder' ),
				'description' => __( 'Some Description', 'fl-theme-builder' ),
				'placeholder' => __( 'Some Placeholder', 'fl-theme-builder' )

			)
		);

		$pods = self::pods_get_pods();

		foreach ( $pods as $name => $label ) {
			$pod        = pods( $name );
			$pod_fields = $pod->fields();

			$form[ $name ] = array(
				'type'  => 'select',
				'label' => $label
			);

			foreach ( $pod_fields as $field_name => $data ) {
				$form[ $name ]['options'][ $field_name ] = $data['label'];
			}
		}

		return $form;
	}

}

PodsPageData::init();