<?php
/**
 * Plugin Name: Pods Beaver Builder Themer Add-On
 * Plugin URI: http://pods.io/
 * Description: Integration with Beaver Builder Themer (https://www.wpbeaverbuilder.com). Provides a UI for mapping Field Connections with Pods
 * Version: 1.0
 * Author: Quasel, Pods Framework Team
 * Author URI: http://pods.io/about/
 * Text Domain: pods-beaver-themer
 * GitHub Plugin URI: https://github.com/pods-framework/pods-beaver-themer
 * GitHub Branch: master
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
		return false;
	}

	// Include main functions
	require_once( PODS_BEAVER_DIR . 'classes/class-pods-page-data.php' );
	require_once( PODS_BEAVER_DIR . 'includes/pods-page-data.php' );

	PodsPageData::init();

	// Fake beeing in the Loop #15
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

// TEST
/**
 * Adds the custom code settings for custom post
 * module layouts.
 *
 * @since 1.0
 *
 * @param array $form
 * @param string $slug
 *
 * @return array
 */
function pods_loop_settings( $form, $slug ) {
	if ( ! in_array( $slug, array( 'post-grid', 'post-slider', 'post-carousel', 'pp-content-grid' ) ) ) {
		return $form;
	}

	$form_first_tab = array(
		'pods' => array(
			'title'    => __( 'Pods', 'fl-builder' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'Data Source', 'fl-builder' ),
					'fields' => array(
						'use_pods'         => array(
							'type'        => 'select',
							'label'       => __( 'Relationship as Content Source', 'pods-beaver-themer' ),
							'default'     => 'no',
							'help'        => __( 'Modify the custom query to use data from a pods relationship field', 'pods-beaver-themer' ),
							'description' => __( 'Set Source to Custom Query in content Tab first! ', 'pods-beaver-themer' ),
							'options'     => array(
								'yes' => __( 'Yes', 'pods-beaver-themer' ),
								'no'  => __( 'No', 'pods-beaver-themer' ),
							),
							'toggle'      => array(
								'no'  => array(
									'fields'   => array( 'post_type', 'data_source' ),
									'sections' => array( 'filter' ),
								),
								'yes' => array(
									'fields' => array( 'pods_query_field' ),
								),
							),
							/*				'trigger'     => array(
												'yes' => array(
													'fields' => array( 'data_source' ),
												),
											),*/
						),
						'pods_query_field' => array(
							'type'  => 'text',
							'label' => __( 'CPT Relationship field', 'pods-beaver-themer' ),
							'help'  => __( 'Only Relationship fields that connect to a custom post type (CPT) work ', 'pods-beaver-themer' ),
							// 'connections' => array( 'custom_field' )
							// PodsPageData::pods_get_fields( array('type' => 'pick')),
							// find a way to present a drop down list -
						)
					),
				)
			)
		)

	);

	return $form_first_tab + $form;
}

add_filter( 'fl_builder_register_settings_form', 'pods_loop_settings', 99, 2 );


function pods_loop_query( $query, $settings ) {

	if ( empty( $settings->use_pods ) || 'no' == $settings->use_pods ) {
		return $query;
	}

	$settings->post_type = 'any'; // we have id's no need to specify the type

	// get comma separatd list to power post__in for the BB Custom Query
	$params = array( 'output' => 'id', 'name' => $settings->pods_query_field);
	$ids = pods()->field( $params );
	$settings->{'posts_' . $settings->post_type} = implode( ', ', $ids );

	return FLBuilderLoop::custom_query( $settings );
}

add_filter( 'fl_builder_loop_query', 'pods_loop_query', 99, 2 );