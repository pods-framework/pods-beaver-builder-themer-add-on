<?php

/**
 * Handles logic for page data Pods properties.
 *
 * @since 1.0
 */
final class PodsBeaverPageData {

	/**
	 * Static cache for the Pods objects we need to call.
	 *
	 * @var array
	 *
	 * @since 1.0
	 */
	static $pods = array();

	/**
	* Track the state similar to $query->fl_builder_loop, in_the_loop().
	*
	* @var array
	*
	* @since 1.3.5
	*/
	static private $pods_beaver_loop;
	
	/**
	 * Add Beaver Builder group for Pods.
	 *
	 * @since 1.0
	 */
	public static function init() {

		FLPageData::add_group( 'pods', array(
			'label' => __( 'Pods Field from:', 'pods-beaver-builder-themer-add-on' ),
		) );
		
		self::pods_beaver_loop_false();
	}

	/**
	 * Set $pods_beaver_loop
	 *
	 * @since 1.3.5
	 */
	public static function pods_beaver_loop_true() {
		self::$pods_beaver_loop = true;
	}

	/**
	 * Set $pods_beaver_loop
	 *
	 * @since 1.3.5
	 */
	public static function pods_beaver_loop_false() {
		self::$pods_beaver_loop = false;
	}	

	/**
	 * Get current pod info.
	 *
	 * @todo  Bring this into Pods core, it's used in Pods::__construct and needs abstraction.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public static function get_current_pod_info() {

		$info = array(
			'pod' => null,
			'id'  => null,
		);
		
		if ( self::$pods_beaver_loop ) {
			// We are in a loop not caused by FLThemeBuilderFieldConnections::connect_all_layout_settings to trigger connect_settings()
			$info = array(
				'pod' => get_post_type(),
				'id'  => get_the_ID(),
			);
			return $info;
		}		

		$queried_object = get_queried_object();

		if ( $queried_object ) {
			$id_lookup = true;

			if ( $queried_object instanceof WP_Post ) {
				// Post Type Singular
				$info['pod'] = $queried_object->post_type;
			} elseif ( $queried_object instanceof WP_Term ) {
				// Term Archive
				$info['pod'] = $queried_object->taxonomy;
			} elseif ( $queried_object instanceof WP_User ) {
				// Author Archive
				$info['pod'] = 'user';
			} elseif ( $queried_object instanceof WP_Post_Type ) {
				// Post Type Archive
				$info['pod'] = $queried_object->name;

				$id_lookup = false;
			}

			if ( $id_lookup ) {
				$info['id'] = get_queried_object_id();
			}
		}

		return $info;

	}

	/**
	 * Get cached pod object.
	 *
	 * @param array|object $settings {
	 *      Options for getting the pod or BB settings object.
	 *
	 *      @type string $pod     Pod name
	 *      @type string $item_id Item ID
	 * }
	 *
	 * @return Pods|bool Pods object if pod is valid, false if pod or item ID are not valid.
	 *
	 * @since 1.0
	 */
	public static function get_pod( $settings = array() ) {

		$item_id  = 0;
		$pod_name = null;

		if ( is_array( $settings ) && ! empty( $settings['pod'] ) ) {
			$pod_name = $settings['pod'];

			if ( ! empty( $settings['item_id'] ) ) {
				$item_id = absint( $settings['item_id'] );
			}

			$settings = null;
		} elseif ( is_object( $settings ) ) {

			if ( ! empty( $settings->item_id ) && ! empty( $settings->pod_name ) ) {
				$item_id  = $settings->item_id;
				$pod_name = $settings->pod_name;
			} else {
				$location = array();

				if ( ! empty( $settings->data_source ) && 'pods_relationship' === $settings->data_source
				     && ! empty( $settings->pods_source_type ) && 'pods_settings_relation' === $settings->pods_source_type ) {

					if ( ! empty( $settings->pods_source_settings_relation ) ) {
						$location = explode( ':', $settings->pods_source_settings_relation );
					}
				} elseif ( ! empty( $settings->settings_field ) ) {
					$location = explode( ':', $settings->settings_field );
				}

				if ( 2 <= count( $location ) ) {
					$settings->pod_name = $location[0];
					$settings->field    = $location[1];
				}

				if ( ! empty( $settings->pod_name ) ) {
					$pod_name = $settings->pod_name;

					// Backwards compatibility ( user moved to separate property )
					if ( isset( $settings->pods_user_type ) ) {
						$settings->type = $settings->pods_user_type;
					}
					if ( 'user' === $pod_name && isset( $settings->type )) {
						switch ( $settings->type ) {
							case 'author':
								if ( ! is_archive() && post_type_supports( get_post_type(), 'author' ) ) {
									$item_id = get_the_author_meta( 'ID' );
								}
								break;
							case 'modified':
								if ( ! is_archive() && post_type_supports( get_post_type(), 'author' ) ) {
									$item_id = get_post_meta( get_post()->ID, '_edit_last', true );
								}
								break;
							case 'logged_in':
							case '': // For backwards compatibility
								if ( is_user_logged_in() ) {
									$item_id = get_current_user_id();
								} else {
									// User is not logged in, cannot return data
									return false;
								};
								break;
						}
					}
				} else {
					$info = self::get_current_pod_info();

					if ( ! empty( $info['pod'] ) ) {
						$pod_name = $info['pod'];
						$item_id  = $info['id'];
					}
				}
			}
		}

		if ( $item_id < 1 ) {
			$item_id = null;
		}

		if ( isset( self::$pods[ $pod_name ] ) ) {
			// Clone object to avoid mucking up things
			$pod = clone self::$pods[ $pod_name ];

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
			} elseif ( $item_id && $pod && ! $pod->exists() ) {
				$pod = false;
			} else {
				self::$pods[ $pod_name ] = $pod;
			}
		}

		return $pod;

	}

	/**
	 * Just Basic Field Display.
	 *
	 * @todo  add settings/code for output_type ( e.g IMAGES as url, image-link, …)
	 * @todo  add settings/code for image_size
	 *
	 * @param object $settings
	 * @param string $property
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	public static function get_field_display( $settings, $property ) {

		$content = '';

		$pod = self::get_pod( $settings );

		if ( ! $pod || ! $pod->exists() ) {
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
	public static function get_field_display_url( $settings, $property ) {

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
	public static function get_field_multiple_photos( $settings, $property ) {

		$pod     = self::get_pod( $settings );
		$content = array();

		if ( ! $pod ) {
			return $content;
		}

		$params = array(
			'output' => 'id',
			'name'   => $settings->field,
		);

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
	public static function get_field_photo( $settings, $property ) {

		$pod = self::get_pod( $settings );

		$content = array(
			'id'  => '',
			'url' => '',
		);

		if ( ! $pod ) {
			return $content;
		}

		$field_name    = $settings->field . '.ID';
		$content['id'] = $pod->display( $field_name );

		$field_url      = $settings->field . '._src.' . pods_v( 'image_size', $settings, '');
		$content['url'] = $pod->display( $field_url );

		if ( empty( $content['url'] ) && isset( $settings->default_img_src ) ) {
			$content['id']  = $settings->default_img;
			$content['url'] = $settings->default_img_src;
		}

		return $content;

	}

	/**
	 * Single Image / Photo - Returns Image Gallery ID && URL
	 *
	 * @param object $settings
	 * @param string $property
	 *
	 * @return int $content  color without hash
	 *
	 * @since 1.0
	 */
	public static function get_field_color( $settings, $property ) {

		$pod = self::get_pod( $settings );

		$content = '';

		if ( ! $pod ) {
			return $content;
		}

		$content = $pod->display( $settings->field );

		$content = ltrim( $content, '#' ); // remove # as BB only expects numbers!

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
	public static function get_template( $settings, $property ) {

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
	 * @since    1.0
	 */
	public static function pods_get_fields( $field_options = array() ) {

		$info = self::get_current_pod_info();

		$pod_name = 'fl-theme-layout';

		if ( ! empty( $info['pod'] ) ) {
			$pod_name = $info['pod'];
		}

		if ( 'fl-theme-layout' === $pod_name ) {
			$location = FLThemeBuilderRulesLocation::get_preview_location( get_the_ID() );
			$location = explode( ':', $location );

			if ( ! empty( $location[1] ) ) {
				$pod_name = $location[1];
			}
		}

		if ( 'fl-theme-layout' === $pod_name ) {
			$fields = array(
				'' => __( 'No fields found (Check Preview / Location)', 'pods-beaver-builder-themer-add-on' ),
			);
		} else {
			$fields = self::recurse_pod_fields( $pod_name, $field_options );

			if ( empty( $fields ) ) {
				$fields = array(
					'' => sprintf( __( 'No fields found for pod "%s"', 'pods-beaver-builder-themer-add-on' ), $pod_name ),
				);
			}
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
	public static function pods_get_url_fields() {

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

	public static function pods_get_image_fields() {

		$field_options['type']                        = 'file';
		$field_options['options']['file_format_type'] = 'single';

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
	public static function pods_get_multiple_images_fields() {

		$field_options['type']                        = 'file';
		$field_options['options']['file_format_type'] = 'multi';

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
	public static function pods_get_color_fields() {

		$field_options['type'] = 'color';

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
	public static function pods_get_templates() {

		$all_templates = (array) pods_api()->load_templates( array() );

		$templates = array(
			'custom' => __( 'Magic Tag', 'pods-beaver-builder-themer-add-on' ),
		);

		foreach ( $all_templates as $template ) {
			$templates[ $template['name'] ] = $template['name'];
		}

		if ( empty( $templates ) ) {
			$templates = array(
				'' => __( 'No templates found', 'pods-beaver-builder-themer-add-on' ),
			);
		}

		return $templates;

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
	public static function pods_get_settings_fields( $field_options = array() ) {

		$pod_names = (array) pods_api()->load_pods( array( 'type' => array( 'user', 'settings' ), 'names' => true ) );

		$field_options['add_pod_name'] = 'true';

		$fields = array(
			'settings_field' => array(
				'type'    => 'select',
				'label'   => __( 'Settings, Author, User…', 'pods-beaver-builder-themer-add-on' ),
				'options' => array(
					'' => __( 'No fields found', 'pods-beaver-builder-themer-add-on' ),
				)
			),
			'type' => array(
				'type'        => 'select',
				'label'       => __( 'User "type"', 'pods-beaver-builder-themer-add-on' ),
				'options'     => array(
					'author'            => __( 'Author (post_author)', 'pods-beaver-builder-themer-add-on' ),
					'modified'   => __( 'Author (last modified) ', 'pods-beaver-builder-themer-add-on' ),
					'logged_in'   => __( 'Logged in User', 'pods-beaver-builder-themer-add-on' ),
				),
				'description' =>  __( 'Only affects user fields', 'pods-beaver-builder-themer-add-on' ),
			),
		);

		if ( $pod_names ) {
			$options = array();

			foreach ( $pod_names as $pod_name => $label ) {
				$field_options['base_pod_name'] = $pod_name;

				$options = array_replace_recursive( $options, self::recurse_pod_fields( $pod_name, $field_options ) );
			}

			if ( ! empty( $options ) ) {
				$fields['settings_field']['options'] = $options;
			}

		}

		return $fields;

	}

	/**
	 * Recurse pod fields to build a list of available fields.
	 *
	 * @param string $pod_name
	 * @param array  $field_options Field options based on pod_data array.
	 * @param string $prefix
	 * @param array  $pods_fields_visited Keep track of visited fields to avoid loops
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	private static function recurse_pod_fields( $pod_name, $field_options = array(), $prefix = '', $pods_fields_visited = array() ) {
		
		$fields = array();
		if ( ! isset( $field_options['base_pod_name'] ) ) {
			$field_options['base_pod_name'] = $pod_name;
		}

		if ( empty( $pod_name ) ) {
			return $fields;
		}

		$args = array(
			'pod' => $pod_name,
		);

		$pod = self::get_pod( $args );


		if ( $pod ) {

			$all_pod_fields = $pod->fields();

			/*if ( isset( $pod->pod_data['object_fields']['post_author'] ) ) {
				$all_pod_fields['post_author'] = $pod->pod_data['object_fields']['post_author'];
			}*/

			foreach ( $all_pod_fields as $field_name => $field ) {
				$linked_pod = null;

				if ( isset( $field['type'] ) && in_array( $field['type'], PodsForm::tableless_field_types(), true ) ) {
					if ( ! empty( $field['table_info'] ) && ! empty( $field['table_info']['pod'] ) ) { // Related item is a pod
						if ( 'single' === pods_v( 'pick_format_type', $field['options'] ) ) {// recursion only wanted if single Issue #16
							$linked_pod = $field['table_info']['pod']['name'];
						}
					} elseif ( 'taxonomy' === $field['type'] ) {
						// $linked_pod = $field_name; @todo Remove this?
						// removed Media Traversal -> use default BB field connections or Templates
					} elseif ( 'attachment' === pods_v( 'file_uploader', $field['options'] ) ) {
						if ( 'single' === pods_v( 'file_format_type', $field['options'] ) ) {// recursion not wanted Issue #16
							$linked_pod = 'media';
						}
					} elseif ( 'user' === $field['pick_object'] ) {
						// $linked_pod = 'user';  until post_author traversal is fixed!
					}

					// @todo maybe add check for comments and ???
				}

				if ( $linked_pod ) {
					$recurse_prefix = $prefix . $field_name . '.';

					// stopp recursion - only travers a field of a pod once - fixes #51
					if ( ! isset( $pods_fields_visited[ $pod_name . $field_name ] ) ) {

						$pods_fields_visited[ $pod_name . $field_name ] = true;
						$visited_fields = self::recurse_pod_fields( $linked_pod, $field_options, $recurse_prefix, $pods_fields_visited );

						$fields = array_merge( $fields, $visited_fields );
					}
				}

				if ( isset( $field_options['type'] ) ) {
					if ( $field_options['type'] === $field['type'] ) {
						if ( ! empty( $field_options['options'] ) ) {
							foreach ( $field_options['options'] as $_option => $option_value ) {
								if ( pods_v( $_option, $field['options'] ) !== $option_value ) {
									// don't check further if one option is not matched
									continue 2;
								}
							}
						}
					} else {
						// don't add to $fields if type doesn't match
						continue 1;
					}
				}

				$base_pod_name = $field_options['base_pod_name'];
				$option_name = $prefix . $field_name;

				if ( isset( $field_options['add_pod_name'] ) ) {
					$option_name = $base_pod_name . ':' . $option_name;
				}

				$fields[ $prefix . $pod_name ]['label'] = sprintf( '%s -> %s', $base_pod_name, $pod_name );
				$fields[ $prefix . $pod_name ]['options'][ $option_name ] = sprintf( '%s%s (%s)', $prefix, $field_name, $field['type'] );
			}


		}

		return $fields;

	}

}
