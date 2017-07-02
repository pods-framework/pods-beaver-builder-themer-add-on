<?php
/**
 * Plugin Name: Pods Beaver Themer Add-On
 * Plugin URI: http://pods.io/
 * Description: Integration with Beaver Builder Themer (https://www.wpbeaverbuilder.com). Provides a UI for mapping Field Connections with Pods
 * Version: 1.1.1
 * Author: Quasel, Pods Framework Team
 * Author URI: http://pods.io/about/
 * Text Domain: pods-beaver-builder-themer-add-on
 * GitHub Plugin URI: https://github.com/pods-framework/pods-beaver-builder-themer-add-on
 * GitHub Branch: 1.x
 *
 * Copyright 2017  Pods Foundation, Inc  (email : contact@podsfoundation.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * @package Pods\Beaver Themer
 */

define( 'PODS_BEAVER_VERSION', '1.1.1' );
define( 'PODS_BEAVER_FILE', __FILE__ );
define( 'PODS_BEAVER_DIR', plugin_dir_path( PODS_BEAVER_FILE ) );
define( 'PODS_BEAVER_URL', plugin_dir_url( PODS_BEAVER_FILE ) );

/**
 * Include main functions and class.
 *
 * @since 1.0
 */
function pods_beaver_init() {

	if ( ! function_exists( 'pods' ) || ! class_exists( 'FLBuilder' ) ) {
		return;
	}

	// Include main functions
	require_once( PODS_BEAVER_DIR . 'classes/class-pods-beaver-page-data.php' );
	require_once( PODS_BEAVER_DIR . 'includes/pods-page-data.php' );

	PodsBeaverPageData::init();

	// Beaver Themer sets up a "virtual reality" fake being in the Loop #15 for any module using FLBuilderLoop::query()
	add_action( 'fl_builder_loop_before_query', 'pods_beaver_fake_loop_add_actions');

	// fore data_source: custom_query for posts modules
	add_action( 'wp_enqueue_scripts', 'pods_beaver_enqueue_assets' ); //remove once 1.10.6 has been widely adopted
	add_filter( 'fl_builder_render_module_settings_assets', 'pods_beaver_add_settings_form_assets', 10, 2 );

	// add additional pods settings to any posts module
	add_action( 'fl_builder_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );
	add_action( 'uabb_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );
	add_action( 'pp_cg_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );
	add_action( 'pp_ct_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );


	add_filter( 'fl_builder_loop_before_query_settings', 'pods_beaver_loop_before_query_settings', 99, 2 );

}

add_action( 'fl_page_data_add_properties', 'pods_beaver_init' );

/**
 * Admin nag if Pods or Beaver Builder are not activated.
 *
 * @since 1.0
 */
function pods_beaver_admin_nag() {

	if ( is_admin() && ( ! class_exists( 'FLBuilder' ) || ! defined( 'PODS_VERSION' ) ) ) {
		printf(
			'<div id="message" class="error"><p>%s</p></div>',
			esc_html__( 'Pods Beaver Themer requires that the Pods and Beaver Builder Themer plugins be installed and activated.', 'pods-beaver-builder-themer-add-on' )
		);
	}

}

add_action( 'plugins_loaded', 'pods_beaver_admin_nag' );

/**
 * Enqueue assets for BB version 1.10.5 and earlier
 *
 * @return void
 *
 * @since 1.1.1
 */
function pods_beaver_enqueue_assets() {

	if ( FLBuilderModel::is_builder_active() && version_compare( FL_BUILDER_VERSION, '1.10.6', '<' ) ) {
		wp_enqueue_script( 'pods-beaver-settings-form', PODS_BEAVER_URL . 'assets/js/settings-form.js', array(), null, false );
	}

}

/**
 * Add assets for BB version 1.10.6 and later
 *
 * @param string $assets
 * @param object $module
 *
 * @return string $assets
 *
 * @since 1.1.1
 */
function pods_beaver_add_settings_form_assets( $assets, $module ) {
	
	$supported_modules = array( 'post-grid', 'post-slider', 'post-carousel', 'pp-content-grid', 'pp-custom-grid', 'pp-content-tiles', 'blog-posts' );
	
	if ( in_array( $module->slug, $supported_modules, true ) ) {
		$assets .= '<script type="text/javascript" src="' . esc_url( PODS_BEAVER_URL . 'assets/js/settings-form.js' ) . '" class="fl-builder-settings-js-custom-query"></script>';
	}

	return $assets;
	
}

/**
 * Register functions to fake the loop.
 *
 * @since 1.1.1
 */
function pods_beaver_fake_loop_add_actions() {

	add_action( 'loop_start', 'pods_beaver_fake_loop_true');
	add_action( 'loop_end', 'pods_beaver_fake_loop_false');

}

/**
 * Set $wp_query->in_the_loop to true before rendering content.
 *
 * Example:
 * add_action( 'loop_start', 'pods_beaver_fake_loop_true' );
 *
 * @since 1.0
 */
function pods_beaver_fake_loop_true() {

	global $wp_query;

    // Fake being in the loop.
	$wp_query->in_the_loop = true;

}

/**
 * Set $wp_query->in_the_loop to false after rendering content.
 *
 * Example:
 * add_action( 'loop_end', 'pods_beaver_fake_loop_false' );
 *
 * @since 1.0
 */
function pods_beaver_fake_loop_false() {

	global $wp_query;

	// Stop faking being in the loop.
	$wp_query->in_the_loop = false;

	// cleanup - keep fake as close to beaver as possible
	remove_action( 'loop_start', 'pods_beaver_fake_loop_true');
	remove_action( 'loop_end', 'pods_beaver_fake_loop_false');

}

/**
 * Adds the custom code settings for custom post  module layouts.
 *
 * @param null|object $settings
 *
 * @since 1.0
 */
function pods_beaver_loop_settings_before_form( $settings ) {

	$source_settings_relation = array();

	$fields = PodsBeaverPageData::pods_get_settings_fields( array( 'type' => 'pick' ) );

	if ( $fields && ! empty( $fields['settings_field'] ) ) {
		$source_settings_relation = $fields['settings_field'];
	}

	$setting_fields = array(
		'use_pods'                      => array(
			'type'        => 'select',
			'label'       => __( 'Pods Content Source', 'pods-beaver-builder-themer-add-on' ),
			'default'     => 'no',
			'help'        => __( 'Modify the custom query to use data from a pods relationship field', 'pods-beaver-builder-themer-add-on' ),
			'description' => '<br />' . __( 'Set "Source" to "Custom Query" in content tab first.', 'pods-beaver-builder-themer-add-on' ),
			'options'     => array(
				'no'                     => __( 'None', 'pods-beaver-builder-themer-add-on' ),
				'pods_relation'          => __( 'Relation from Current Item', 'pods-beaver-builder-themer-add-on' ),
				'pods_settings_relation' => __( 'Relation from Settings / Current User', 'pods-beaver-builder-themer-add-on' ),
				// 'pods_advanced'          => __( 'Advanced (pods)', 'pods-beaver-builder-themer-add-on' ),
			),
			'toggle'      => array(
				'no'                     => array(
					'fields'   => array(
						'post_type',
						'data_source',
						'pagination',
					),
					'sections' => array(
						'filter',
					),
				),
				'pods_relation'          => array(
					'fields' => array(
						'pods_source_relation',
					),
				),
				'pods_settings_relation' => array(
					'fields' => array(
						'pods_source_settings_relation',
					),
				),
				/*'pods_advanced'          => array(
					'fields' => array(
						'pods_where',
                        'post_type',
					),
				),*/
			),
		),
		'pods_source_relation'          => array(
			'type'    => 'select',
			'label'   => __( 'Field from Current Post Type', 'pods-beaver-builder-themer-add-on' ),
			'help'    => __( 'Only Relationship fields that connect to a custom post type work.', 'pods-beaver-builder-themer-add-on' ),
			'options' => PodsBeaverPageData::pods_get_fields( array( 'type' => 'pick' ) ),
		),
		'pods_source_settings_relation' => $source_settings_relation,
		/*'pods_where'                    => array(
			'type'        => 'text',
			'label'       => __( 'Customized WHERE Query', 'pods-beaver-builder-themer-add-on' ),
			'help'        => __( 'SQL WHERE to use, example: "t.my_field = \'test\'" - This field also supports tableless traversal like "my_relationship_field.id = 3" with unlimited depth.', 'pods-beaver-builder-themer-add-on' ),
			'description' => __( '<a href="http://pods.io/docs/code/pods/find/" target="_blank">See Documentation &raquo;</a>', 'pods-beaver-builder-themer-add-on' ),
			// @todo: error handling for incorrect where!
		),*/
	);
?>
	<div id="fl-builder-settings-section-pods" class="fl-builder-settings-section">
		<table class="fl-form-table">
			<?php
			foreach ( $setting_fields as $setting_name => $setting_data ) {
				if ( $setting_data ) {
					FLBuilder::render_settings_field( $setting_name, $setting_data, $settings );
				}
			}
			?>
		</table>
	</div>
<?php

	add_filter( 'fl_builder_render_settings_field', 'pods_beaver_render_settings_field_order_by', 10, 3 );

}

/**
 * Handle query integration.
 *
 * @param object $settings
 *
 * @return object
 *
 * @since 1.0
 */
function pods_beaver_loop_before_query_settings( $settings ) {

	if ( empty( $settings->use_pods ) || 'no' === $settings->use_pods ) {
		return $settings;
	}

	/*global $wp_query, $wp_the_query, $paged;

	$flpaged = $wp_the_query->get( 'flpaged'. FLBuilderLoop::$loop_counter );
	$page_qv = $wp_the_query->get( 'page' );
	$paged_qv = $wp_the_query->get( 'paged' );
	$loop_counter = FLBuilderLoop::$loop_counter;
	$paged_beaver = FLBuilderLoop::get_paged();
	$max_page = $wp_query->max_num_pages;*/

	$ids = array();

	$find_params  = array();
	$field_params = array();

	$pod = PodsBeaverPageData::get_pod( $settings );

	if ( 'pods_relation' === $settings->use_pods && ! empty( $settings->pods_source_relation ) ) {
		$field_params = array(
			'name'   => trim( $settings->pods_source_relation ),
		);
	} elseif ( 'pods_settings_relation' === $settings->use_pods ) {
		$field_params = array(
			'name'   => trim( $settings->field ),
		);
	}/*elseif ( 'pods_advanced' === $settings->use_pods && ! empty( $settings->pods_where ) ) {
		$find_params = array(
			'where' => trim( $settings->pods_where ),
			'limit' => - 1,
		);
	}*/

	/**
	 * Change the pods query for customized where.
	 *
	 * @since 1.1
	 *
	 * @param array     $find_params Array to pass to pods()->find()
	 * @param object    $settings    Beaver Builder Settings
	 * @param Pods|bool $pod         Pods object if pod is valid, false if pod or item ID are not valid.
	 */
	$find_params = apply_filters( 'pods_beaver_loop_settings_find_params', $find_params, $settings, $pod );

	/**
	 * Change the pods query for related items.
	 *
	 * @since 1.1
	 *
	 * @param array     $field_params Array to pass to pods()->field()
	 * @param object    $settings     Beaver Builder Settings
	 * @param Pods|bool $pod          Pods object if pod is valid, false if pod or item ID are not valid.
	 */
	$field_params = apply_filters( 'pods_beaver_loop_settings_field_params', $field_params, $settings, $pod );

	if ( $pod ) {
		if ( $find_params ) {
			// Optimized select only gets the ID
			$find_params['select']     = 't.' . $pod->pod_data['field_id'];
			$find_params['pagination'] = false;
			$find_params['search']     = false;

			// @todo: catch error for wrong "where"
			$pod->find( $find_params );

			if ( 0 < $pod->total() ) {
				while ( $pod->fetch() ) {
					$ids[] = $pod->id();
				}
			}
		} elseif ( $field_params && $pod->exists() ) {
		    $field_params['output'] = 'id';
			$ids = $pod->field( $field_params );
		}
	}

	if ( empty( $ids ) ) {
	    // No Fields found make sure the end result is an empty WP_Query
		add_filter( 'fl_builder_loop_query', 'pods_beaver_empty_query' );
	}

	// we have id's no need to specify the type
	$settings->post_type = 'any';

	add_filter( 'uabb_blog_posts_query_args', 'pods_beaver_uabb_blog_posts', 10, 2 );

	$setting_id_field = 'posts_' . $settings->post_type;

	// get comma separated list to power post__in for the BB Custom Query
	if ( is_array( $ids ) ) {
		$ids = implode( ', ', $ids );
	}

	$settings->{$setting_id_field} = $ids;

	return $settings;

}

/**
 * Return empty WP_Query.
 *
 * @return WP_Query
 *
 * @since 1.0
 */
function pods_beaver_empty_query() {

    remove_filter('fl_builder_loop_query', 'pods_beaver_empty_query');

	return new WP_Query;

}

/**
 * Add Option to order_by settings field
 *
 * @param array  $field
 * @param string $name
 * @param object $settings
 *
 * @return array
 *
 * @since 1.1
 */
function pods_beaver_render_settings_field_order_by( $field, $name, $settings ) {

	if ( 'order_by' === $name ) {
		$field['options']['post__in'] = __( 'Preserve Relationship (pick) Order', 'pods-beaver-builder-themer-add-on' );
	}

	return $field;

}

/**
 * Work around UABB overly aggressive setting of post_type
 *
 * @param array  $args
 * @param object $settings
 *
 * @return array
 *
 * @since 1.1
 */
function pods_beaver_uabb_blog_posts( $args, $settings ) {

	if ( empty( $settings->use_pods ) || 'no' === $settings->use_pods ) {
		return $args;
	}

	$args['post_type'] = 'any';

	return $args;

}