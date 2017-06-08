<?php
/**
 * Plugin Name: Pods Beaver Themer
 * Plugin URI: http://pods.io/
 * Description: Integration with Beaver Builder Themer (https://www.wpbeaverbuilder.com). Provides a UI for mapping Field Connections with Pods
 * Version: 1.0
 * Author: Quasel, Pods Framework Team
 * Author URI: http://pods.io/about/
 * Text Domain: pods-beaver-themer
 * GitHub Plugin URI: https://github.com/pods-framework/pods-beaver-themer
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

define( 'PODS_BEAVER_VERSION', '1.0' );
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

	// Fake being in the Loop #15
	add_action( 'loop_start', 'pods_beaver_fake_loop_true' );
	add_action( 'loop_end', 'pods_beaver_fake_loop_false' );

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
			esc_html__( 'Pods Beaver Themer requires that the Pods and Beaver Builder Themer plugins be installed and activated.', 'pods-beaver-themer' )
		);
	}

}

add_action( 'plugins_loaded', 'pods_beaver_admin_nag' );

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

	if ( is_pod() ) {
		// Fake being in the loop.
		$wp_query->in_the_loop = true;
	}

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

	if ( is_pod() ) {
		// Stop faking being in the loop.
		$wp_query->in_the_loop = false;
	}

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
			'label'       => __( 'Pods Content Source', 'pods-beaver-themer' ),
			'default'     => 'no',
			'help'        => __( 'Modify the custom query to use data from a pods relationship field', 'pods-beaver-themer' ),
			'description' => __( 'Set Source to Custom Query in content Tab first! ', 'pods-beaver-themer' ),
			'options'     => array(
				'no'                     => __( 'No', 'pods-beaver-themer' ),
				'pods_relation'          => __( 'Relation from current item', 'pods-beaver-themer' ),
				'pods_settings_relation' => __( 'Relation from settings / current user', 'pods-beaver-themer' ),
				'pods_advanced'          => __( 'Advanced (pods)', 'pods-beaver-themer' ),
			),
			'toggle'      => array(
				'no'                     => array(
					'fields'   => array(
						'post_type',
						'data_source',
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
				'pods_advanced'          => array(
					'fields' => array(
						'pods_where',
					),
				),
			),
		),
		'pods_source_relation'          => array(
			'type'    => 'select',
			'label'   => __( 'Field from current post type', 'pods-beaver-themer' ),
			'help'    => __( 'Only Relationship fields that connect to a custom post type (CPT) work ', 'pods-beaver-themer' ),
			'options' => PodsBeaverPageData::pods_get_fields( array( 'type' => 'pick' ) ),
		),
		'pods_source_settings_relation' => $source_settings_relation,
		'pods_where'                    => array(
			'type'        => 'text',
			'label'       => __( 'custom where', 'pods-beaver-themer' ),
			'help'        => __( 'SQL WHERE to use, ie "t.my_field = \'test\'" - This field also supports tableless traversal like "my_relationship_field.id = 3" with unlimited depth', 'pods-beaver-themer' ),
			'description' => __( 'see: <a href="http://pods.io/docs/code/pods/find/" target="_blank">Documentation</a> ', 'pods-beaver-themer' ),
			// @todo: error handling for incorrect where!
		),
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

}

add_action( 'fl_builder_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );
// Possibly need to hook into uabb_loop_settings_before_form?

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

	$ids = array();

	$find_params  = array();
	$field_params = array();

	$pod = null;

	if ( 'pods_relation' === $settings->use_pods && ! empty( $settings->pods_source_relation ) ) {
		$field_params = array(
			'output' => 'id',
			'name'   => trim( $settings->pods_source_relation ),
		);

		$pod = PodsBeaverPageData::get_pod();
	} elseif ( 'pods_settings_relation' === $settings->use_pods ) {
		$pod = PodsBeaverPageData::get_pod( $settings );
	} elseif ( 'pods_advanced' === $settings->use_pods && ! empty( $settings->pods_where ) ) {
		$find_params = array(
			'where' => trim( $settings->pods_where ),
			'limit' => - 1,
		);

		$pod = PodsBeaverPageData::get_pod();
	}

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
			$ids = $pod->field( $field_params );
		}
	}

	if ( empty( $ids ) ) {
		add_filter( 'fl_builder_loop_query', 'pods_beaver_empty_query' );
	}

	// we have id's no need to specify the type
	$settings->post_type = 'any';

	$setting_id_field = 'posts_' . $settings->post_type;

	// get comma separated list to power post__in for the BB Custom Query
	$settings->{$setting_id_field} = implode( ', ', $ids );

	return $settings;
}

add_filter( 'fl_builder_loop_before_query_settings', 'pods_beaver_loop_before_query_settings', 99, 2 );

/**
 * Return empty WP_Query.
 *
 * @return WP_Query
 *
 * @since 1.0
 */
function pods_beaver_empty_query() {

	return new WP_Query;

}
