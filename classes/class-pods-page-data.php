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

		// return is_string( $content ) ? $content : '';
		return $content;
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

		$field_ID   = $settings->field . '.ID';
		$content = pods( get_post_type(), get_the_ID() )->field( $field_ID );

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

		$field_ID = $settings->field . '.ID';
		$field_url = $settings->field.'._src.'.$settings->image_size;

		$content = array(
			'id'  => pods( get_post_type(), get_the_ID() )->display( $field_ID ),
			'url' => pods( get_post_type(), get_the_ID() )->display( $field_url )
		);

		return $content;
	}

	/**
	 *
	 * Returns Pods Template Output
	 *
	 * @since 1.0
	 *
	 * @param $settings
	 * @param $property
	 *
	 * @return string
	 */
	static public function get_template( $settings, $property ) {
		$content = pods( get_post_type(), get_the_ID() )->template( $settings->template );

		return $content;
	}


	/**
	 *
	 * @todo: $option['file_type'] if a user chooses 'custom' check further if only image file type???
	 *
	 * @since 1.0
	 *
	 * @param array $field_options
	 *
	 * @return array
	 * @internal param $option
	 *
	 */
	static public function pods_get_fields( $field_options = array() ) {
		global $post;



		// change to just get_post_type() ?
		$fields = array();
		$pods   = pods_api()->load_pods( array( 'names' => true ) );

		$location = FLThemeBuilderRulesLocation::get_preview_location( $post->ID );
		$location = explode( ':', $location );

		$test_load = pods_api()->load_pod( array( 'name' => $location[1] ), false );

		$field_options['name'] = $location[1];


		$all_fields = self::recurse_pod_fields( $field_options );

		$pods = array( $location[1] => 'current' );
		// check for xxx_formate_type

		foreach ( $pods as $name => $label ) {
			$pod        = pods( $name );
			$pod_fields = $pod->fields();


			foreach ( $pod_fields as $field_name => $_field ) {
				if ( $field_options ) {
					if ( $field_options['type'] == $_field['type'] ) {
						foreach ( $field_options['options'] as $_option => $option_value ) {
							if ( $option_value !== pods_v( $_option, $_field['options'] ) ) { //$pod->fields( $field_name, 'file_type' )
								continue 2;  // if one option it not matched we don't need the field -> go check the next one
							}
						}

					} else {
						continue 1;
					}
				}
				$fields[ $field_name ] .= $_field['name'] . " (" . $name . ")  ";


			}

		}

		return $all_fields;
	}


	/**
	 *
	 *  Limit fields from pods to url fields ( -> file_format = 'url' )
	 *
	 * @since 1.0
	 * @return array
	 */

	static public function pods_get_url_fields() {
		$field_options['type'] = 'website';
		$fields                = self::pods_get_fields( $field_options );

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
		$field_options['type']                        = 'file';
		// $field_options['options']['file_type']        = 'images';
		// $field_options['options']['file_format_type'] = 'single';
		// $field_options['options']['file_uploader'] = 'attachment';
		$fields                                       = self::pods_get_fields( $field_options );


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
		$field_options['type']                        = 'file';
		$field_options['options']['file_type']        = 'images';
		$field_options['options']['file_format_type'] = 'multi';
		$fields                                       = self::pods_get_fields( $field_options );

		return $fields;
	}

	/**
	 *
	 *  Get Pods Templates
	 *
	 * @since 1.0
	 * @return array
	 */
	static public function pods_get_templates() {

		$all_templates = (array) pods_api()->load_templates( array() );
		$fields        = array();
		/*		$fields = array(
					'' => '- ' . __( 'Select Template', 'fl-theme-builder' ) . ' -'
				);*/

		foreach ( $all_templates as $template ) {
			$fields[ $template['slug'] ] = $template['name'];
		}


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
				'placeholder' => __( 'Some Placeholder', 'fl-theme-builder' ),
			),
			'image_size' => array(  // add only if selected field is an image field ?
				'type'    => 'photo-sizes',
				'label'   => __( 'Image Size', 'fl-theme-builder' ),
				'default' => 'thumbnail',
			),
		);

		$test = pq_recurse_pod_fields('pods_name');  // Pod Reference from Pods Template Admin!
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

	private function recurse_pod_fields( $params, $prefix = '', &$pods_visited = array() ) {

		$pod_name = $params['name'];

		$fields = array();
		if ( empty( $pod_name ) ) {
			return $fields;
		}
		$pod            = pods( $pod_name );
		$all_pod_fields = array();
		$recurse_queue  = array();


		foreach ( $pod->pod_data['object_fields'] as $name => $field ) {
			// Add WordPress object fields
/*			if ( isset( $params['type'] ) ) {
				if ( $params['type'] === $field['type'] && 'attachment' === $field['options']['file_uploader'] ) {
					$fields[ $prefix . $name ] = $prefix . $name;
				}
			} else {
				$fields[ $prefix . $name ] = $prefix . $name;
			}

			$all_pod_fields[ $name ] = $field;*/


			if ( 'taxonomy' === $field['type'] ) {
				$linked_pod = $name;
				if ( ! isset( $pods_visited[ $linked_pod ] ) || ! in_array( $name, $pods_visited[ $linked_pod ] ) ) {
					$pods_visited[ $linked_pod ][] = $name;
					$recurse_queue[ $linked_pod ]  = "{$prefix}{$name}.";
				}
			}

		}

		$all_pod_fields = array_merge( $pod->pod_data['object_fields'], $pod->fields() );
		foreach ( $all_pod_fields as $name => $field ) {
			// Add base field name
			if ( isset( $params['type'] ) ) {
				if ( $params['type'] === $field['type'] && 'attachment' === $field['options']['file_uploader'] ) {
					$fields[ $prefix . $name ] = $prefix . $name;
				}
			} else {
				$fields[ $prefix . $name ] = $prefix . $name;
			}

			// Field type specific handling
			if ( 'file' === $field['type'] && 'attachment' === $field['options']['file_uploader'] ) {
				/*				$fields[] = $prefix . $name . '._src';
								$fields[] = $prefix . $name . '._img';

								$sizes = get_intermediate_image_sizes();
								foreach ( $sizes as $size ) {
									$fields[] = "{$prefix}{$name}._src.{$size}";
									if ( 'multi' != $field['options']['file_format_type'] ) {
										$fields[] = "{$prefix}{$name}._src_relative.{$size}";
										$fields[] = "{$prefix}{$name}._src_schemeless.{$size}";
									}
									$fields[] = "{$prefix}{$name}._img.{$size}";
								}*/

			} elseif ( ! empty( $field['table_info'] ) && ! empty( $field['table_info']['pod'] ) ) {
				$linked_pod = $field['table_info']['pod']['name'];
				if ( ! isset( $pods_visited[ $linked_pod ] ) || ! in_array( $name, $pods_visited[ $linked_pod ] ) ) {
					$pods_visited[ $linked_pod ][] = $name;
					$recurse_queue[ $linked_pod ]  = "{$prefix}{$name}.";
				}

			}
		}
		foreach ( $recurse_queue as $recurse_name => $recurse_prefix ) {
			$params['name'] = $recurse_name;
			$fields         = array_merge( $fields, self::recurse_pod_fields( $params, $recurse_prefix, $pods_visited ) );
		}

		return $fields;
	}

}

PodsPageData::init();