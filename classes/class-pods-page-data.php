<?php
/**
 * Handles logic for page data Pods properties.
 *
 * @since 1.0
 */
final class PodsPageData {

	/**
	 * Static cache for the Pods objects we need to call.
	 *
	 * @var array
	 *
	 * @since 1.0
	 */
	static $pods = array();

	/**
	 * Add Beaver Builder group for Pods.
	 *
	 * @since 1.0
	 */
	static public function init() {

		FLPageData::add_group( 'pods', array(
			'label' => __( 'Pods', 'pods-beaver-themer' ),
		) );

	}

	/**
	 * Get cached pod object.
	 *
	 * @param object $settings
	 *
	 *
	 * @return Pods|bool Pods object if pod is valid, false if pod or item ID are not valid.
	 *
	 * @since 1.0
	 */
	static public function get_pod( $settings = null ) {

		$item_id  = 0;
		$pod_name = null;

		if ( isset( $settings->name ) ) {
			$pod_name = $settings->name;
			if ( 'user' === $settings->name ) {
				$item_id = get_current_user_id();
			}
			if ( isset( $settings->$pod_name ) ) {
				$settings->field = $settings->$pod_name;
			}
		} else {
			$pod_name = get_post_type();
			$item_id  = get_the_ID();
		}



		if ( $item_id < 1 ) {
			$item_id = null;
		}

		if ( isset( self::$pods[ $pod_name ] ) ) {
			$pod = self::$pods[ $pod_name ];

			if ( $pod ) {
				if ( $item_id && $item_id !== (int) $pod->id() ) {
					if ( ! $pod->fetch( $item_id ) ) {
						$pod = false;
					}
				}
			}
		} else {
			$pod = pods( $pod_name, $item_id );

			if ( ! $pod || ! $pod->valid() ) {
				$pod = false;
			}

			self::$pods[ $pod_name ] = $pod;

			if ( $item_id && $pod && ! $pod->exists() ) {
				$pod = false;
			}
		}

		return $pod;

	}

	/**
	 * Just Basic Field Display.
	 *
	 * @todo  add settings/code for qutput_type ( e.g IMAGES as url, image-link, ...)
	 * @todo  add settings/code for image_size
	 *
	 * @param object $settings
	 * @param string $property
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	static public function get_field_display( $settings, $property ) {

		$content = '';
		$pod = self::get_pod( $settings );

		if (  ! $pod || ! $pod->valid() || ! $pod->exists() ) {
			return $content;
		}

		$content = $pod->display( $settings->field );

		return $content;

	}


	/**
	 * Basic URL.
	 *
	 * @param object $settings
	 * @param string $property
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	static public function get_field_display_url( $settings, $property ) {

		return esc_url( self::get_field_display( $settings, $property ) );

	}

	/**
	 * Multiple Photos - Returns an array of Image Gallery ID's
	 *
	 * @param object $settings
	 * @param string $property
	 *
	 * @return string[]
	 *
	 * @since 1.0
	 */
	static public function get_field_multiple_photos( $settings, $property ) {

		$pod     = self::get_pod( $settings );
		$content = array();

		if ( ! $pod ) {
			return $content;
		}


		$params  = array( 'output' => 'id', 'name' => $settings->field );
		$content = $pod->field( $params );

		if ( ! is_array( $content ) ) {
			if ( empty( $content ) ) {
				$content = array();
			} else {
				$content = array(
					$content,
				);
			}
		}

		return $content;

	}

	/**
	 * Single Image / Photo - Returns Image Gallery ID && URL
	 *
	 * @param object $settings
	 * @param string $property
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	static public function get_field_photo( $settings, $property ) {

		$pod = self::get_pod( $settings );

		$content = array(
			'id'  => '',
			'url' => '',
		);

		if ( ! $pod ) {
			return $content;
		}

		$field_name = $settings->field . '.ID';
		$content['id']  = $pod->display( $field_name );

		$field_url  = $settings->field . '._src.' . $settings->image_size;
		$content['url'] = $pod->display( $field_url );

		if ( ! isset( $content['url'] ) && isset( $settings['default_img_src'] ) ) {
			$content['id']  = $settings->default_img;
			$content['url'] = $settings->default_img_src;
		}

		return $content;

	}

	/**
	 * Returns Pods Template Output
	 *
	 * @param $settings
	 * @param $property
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	static public function get_template( $settings, $property ) {

		$pod = self::get_pod( $settings );

		$content = '';

		if ( ! $pod || empty( $settings->template ) ) {
			return $content;
		}

		if ( 'custom' === $settings->template ) {
			$content = $pod->template( null, $settings->custom_template, $pod );
		} else {
			$content = $pod->template( $settings->template );
		}

		return $content;


	}

	/**
	 * @todo     : $option['file_type'] if a user chooses 'custom' check further if only image file type???
	 *
	 * @param array $field_options
	 *
	 * @return string[]
	 *
	 * @since 1.0
	 */
	static public function pods_get_fields( $field_options = array() ) {

		$location = FLThemeBuilderRulesLocation::get_preview_location( get_the_ID() );
		$location = explode( ':', $location );

		$fields = array();

		if ( ! empty( $location[1] ) ) {
			$pod_name = $location[1];

			$fields = self::recurse_pod_fields( $pod_name, $field_options );
		}

		if ( empty( $fields ) ) {
			$fields = array( "" => __('No fields found (Check Preview/Location)', 'pods-beaver-themer'));
		}

		return $fields;

	}

	/**
	 * Limit fields from pods to url fields ( -> file_format = 'url' )
	 *
	 * @return string[]
	 *
	 * @since 1.0
	 */
	static public function pods_get_url_fields() {

		$field_options['type'] = 'website';

		$fields = self::pods_get_fields( $field_options );

		return $fields;

	}

	/**
	 * Limit fields from pods to image fields ( -> file_format = 'images' )
	 *
	 * @return string[]
	 *
	 * @since 1.0
	 */

	static public function pods_get_image_fields() {

		$field_options['type'] = 'file';
		$field_options['options']['file_format_type'] = 'single';

		// $field_options['options']['file_type']     = 'images';
		// $field_options['options']['file_uploader'] = 'attachment';

		$fields = self::pods_get_fields( $field_options );

		return $fields;

	}

	/**
	 * Limit fields from pods to image fields ( -> file_format = 'images', file_format_type = 'multi' )
	 *
	 * @return string[]
	 *
	 * @since 1.0
	 */
	static public function pods_get_multiple_images_fields() {

		$field_options['type'] = 'file';
		$field_options['options']['file_format_type'] = 'multi';

		// $field_options['options']['file_type']        = 'images';

		$fields = self::pods_get_fields( $field_options );

		return $fields;

	}

	/**
	 * Get list of Pods Templates.
	 *
	 * @return string[]
	 *
	 * @since 1.0
	 */
	static public function pods_get_templates() {

		$all_templates = (array) pods_api()->load_templates( array() );

		$fields = array( 'custom' => __( 'Magic Tag', 'pods-beaver-themer' ) );

		foreach ( $all_templates as $template ) {
			$fields[ $template['name'] ] = $template['name'];
		}

		if ( empty( $fields ) ) {
			$fields = array( "" => __('No fields found (Check Preview/Location)', 'pods-beaver-themer'));
		}

		return $fields;

	}


	/**
	 *
	 * Get Settings Pod Fields
	 *
	 * @param array $field_options
	 *
	 * @return string[]
	 *
	 * @since 1.0
	 */
	static public function pods_get_settings_fields( $field_options = array() ) {

		$settings_pod_names = (array) pods_api()->load_pods( array( 'type' => array('settings','user'), 'names' => true ) );
		$fields             = array( 'name' => array(), 'field' => array() );

		if ( $settings_pod_names ) {
			$fields = array(
				'name' => array(
					'type'    => 'select',
					'label'   => __( 'Pod Name:', 'pods-beaver-themer' ),
					'default' => 'grid',
					'options' => array(),
				)

			);

			foreach ( $settings_pod_names as $pod_name => $label ) {
				$fields['name']['toggle'][ $pod_name ]['fields'][] = $pod_name;
				$fields['name']['options'][ $pod_name ]            = $label;
				$fields[ $pod_name ]                              = array(
					'options'     => self::recurse_pod_fields( $pod_name, $field_options ),
					'type'        => 'select',
					'label'       => __( 'Field Name:', 'pods-beaver-themer' ),
					'description' => __( 'Select a Field', 'pods-beaver-themer' ),
				);
			}
		}

		if ( empty( $fields ) ) {
			$fields = array( "" => __('No fields found (Check Preview/Location)', 'pods-beaver-themer'));
		}

		return $fields;
	}




	/**
	 * Recurse pod fields to build a list of available fields.
	 *
	 * @param string $pod_name
	 * @param array  $field_options Field options based on pod_data array.
	 * @param string $prefix
	 * @param array  $pods_visited
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	private static function recurse_pod_fields( $pod_name, $field_options = array(), $prefix = '', &$pods_visited = array() ) {

		$fields = array();

		if ( empty( $pod_name ) ) {
			return $fields;
		}

		$settings       = (object) null;
		$settings->name = $pod_name;

		$pod = self::get_pod( $settings );

		if ( $pod ) {
			$recurse_queue = array();

			$all_pod_fields = $pod->fields();

			/*			if ( isset( $pod->name_data['object_fields'] ) ) {
							$all_pod_fields = array_merge( $all_pod_fields, $pod->pod_data['object_fields'] );
						}*/

			foreach ( $all_pod_fields as $field_name => $field ) {
				$linked_pod = null;

				if ( isset( $field['type'] ) && in_array( $field['type'], PodsForm::tableless_field_types() ) ) {


					if ( ! empty( $field['table_info'] ) && ! empty( $field['table_info']['pod'] ) ) { // Related item is a pod
						if ( 'single' === $field['options']['pick_format_type'] ) {// recursion only wanted if single Issue #16
							$linked_pod = $field['table_info']['pod']['name'];
						}
					} elseif ( 'taxonomy' === $field['type']) {
						// $linked_pod = $field_name;
						// removed Media Traversal -> use default BB field connections or Templates
					} elseif ( 'attachment' === $field['options']['file_uploader']) {
						if ( 'single' === $field['options']['file_format_type'] ) {// recursion not wanted Issue #16
							$linked_pod = 'media';
						}
					}
					// maybe add check for comments and ???
				}

				if ( $linked_pod ) {
					if ( ! isset( $pods_visited[ $linked_pod ] ) || ! in_array( $field_name, $pods_visited[ $linked_pod ], true ) ) {
						$pods_visited[ $linked_pod ][] = $field_name;
						$recurse_queue[ $linked_pod ] = "{$prefix}{$field_name}.";
					}
				}

				if ( $field_options ) {
					if ( isset( $field_options['type'] ) && $field_options['type'] === $field['type'] ) {
						if ( ! empty( $field_options['options'] ) ) {
							foreach ( $field_options['options'] as $_option => $option_value ) {
								if ( pods_v( $_option, $field['options'] ) !== $option_value ) {
									continue 2;  // don't check further if one option is not matched
								}
							}
						}
					} else {
						continue 1; // don't add to $fields if type doesn't match
					}
				}

				$fields[$pod_name]['label'] = sprintf( '%s (%s)', $pod_name,  $pod->pod_data[ 'type' ] );
				$fields[$pod_name]['options'][ $prefix . $field_name ] = sprintf( '%s%s (%s)', $prefix, $field_name, $field['type'] );
			}

			foreach ( $recurse_queue as $recurse_name => $recurse_prefix ) {
				$fields = array_merge( $fields, self::recurse_pod_fields( $recurse_name, $field_options, $recurse_prefix, $pods_visited ) );
			}
		}

		return $fields;

	}
}


