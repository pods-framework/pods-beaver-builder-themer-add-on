<?php
/**
 * Plugin Name: Pods Beaver Themer Add-On
 * Plugin URI: http://pods.io/
 * Description: Integration with Beaver Builder Themer (https://www.wpbeaverbuilder.com). Provides a UI for mapping Field Connections with Pods
 * Version: 1.3.5
 * Author: Quasel, Pods Framework Team
 * Author URI: http://pods.io/about/
 * Text Domain: pods-beaver-builder-themer-add-on
 * GitHub Plugin URI: https://github.com/pods-framework/pods-beaver-builder-themer-add-on
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

define( 'PODS_BEAVER_VERSION', '1.3.5' );
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

	// Fake being "in the loop" for any module using FLBuilderLoop::query() (see #15)
	add_action( 'fl_builder_loop_before_query', 'pods_beaver_fake_loop_start');

	// Priority 0 to run before  FLThemeBuilderRulesLocation::set_preview_query() - Beaver Themer
	// add_action( 'wp_enqueue_scripts', 'pods_beaver_enqueue_assets', 0 );

	// add additional pods settings to any posts module
	add_action( 'fl_builder_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );
	add_action( 'uabb_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );
	add_action( 'pp_cg_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );
	add_action( 'pp_ct_loop_settings_before_form', 'pods_beaver_loop_settings_before_form', 10, 1 );


	add_filter( 'fl_builder_loop_before_query_settings', 'pods_beaver_loop_before_query_settings', 99, 2 );

	add_filter( 'fl_builder_get_layout_metadata', 'pods_beaver_update_module_settings_data_source', 10, 3 );
	add_filter( 'fl_builder_render_settings_field', 'pods_beaver_render_settings_field', 10, 3 );

}

add_action( 'fl_page_data_add_properties', 'pods_beaver_init' );

/**
 * Admin nag if Pods or Beaver Builder are not activated.
 *
 * @since 1.0
 */
function pods_beaver_admin_nag() {

	if ( ! class_exists( 'FLBuilder' ) || ! defined( 'PODS_VERSION' ) || version_compare( PODS_VERSION, '2.7.26', '<' ) ) {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			esc_html__( 'Pods Beaver Themer requires that the Pods (2.7.26+) and Beaver Builder Themer plugins be installed and activated.', 'pods-beaver-builder-themer-add-on' )
		);
	}

}

add_action( 'admin_notices', 'pods_beaver_admin_nag' );

/**
 * Post modules:  JS for setting data_source to custom_query if a relationship field is selected as source
 * Enqueue JS
 *
 * @return void
 *
 * @since 1.1.1
 */
function pods_beaver_enqueue_assets() {
	if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

	    $deps = 'fl-builder-layout-' . FLBuilderModel::get_post_id();
	    wp_enqueue_script( 'pods-beaver-settings-form', PODS_BEAVER_URL . 'assets/js/settings-form.js', array( $deps ), PODS_BEAVER_VERSION, true );
	}
}

/**
 * Register function to tell Pods shortcodes to start detecting from the current post.
 *
 * @since 1.3.3
 */
function pods_beaver_fake_loop_start() {
	add_filter( 'pods_shortcode_detect_from_current_post', '__return_true', 9 );
	PodsBeaverPageData::pods_beaver_loop_true();

	// Remove the hook after the end of the loop.
	add_action( 'loop_end', 'pods_beaver_fake_loop_end' );
}

/**
 * Register function to tell Pods shortcodes to stop detecting from the current post.
 *
 * @since 1.3.3
 */
function pods_beaver_fake_loop_end() {
	remove_filter( 'pods_shortcode_detect_from_current_post', '__return_true', 9 );
	PodsBeaverPageData::pods_beaver_loop_false();
}

/**
 * Set $wp_query->in_the_loop to true before rendering content.
 *
 * Example:
 * add_action( 'loop_start', 'pods_beaver_fake_loop_true' );
 *
 * @since 1.0
 * @deprecated since version 1.3.5
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
 * @deprecated since version 1.3.5
 */
function pods_beaver_fake_loop_false() {

	global $wp_query;

	// Stop faking being in the loop.
	$wp_query->in_the_loop = false;

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
	$pods_source_relation     = array();
	$options                  = array();
	$toggle                   = array();

	$pod_setting_and_user_fields = PodsBeaverPageData::pods_get_settings_fields( array( 'type' => 'pick' ) );

	if ( $pod_setting_and_user_fields && ! empty( $pod_setting_and_user_fields['settings_field'] ) ) {
		$source_settings_relation = $pod_setting_and_user_fields['settings_field'];
	}

	if ( 'fl-theme-layout' === get_post_type() ) {
		$location = FLThemeBuilderRulesLocation::get_preview_location( get_the_ID() );
		$location = explode( ':', $location );

		if ( ! empty( $location[0] ) && 'archive' !== $location[0] ) {
			$options['pods_relation'] = __( 'Main Query (Post, Page, Term…)', 'pods-beaver-builder-themer-add-on' );
			$toggle['pods_relation']  = array(
				'fields' => array(
					'pods_source_relation',
				),
			);
			$pods_source_relation     = array(
				'type'    => 'select',
				'label'   => __( 'Pods Field', 'pods-beaver-builder-themer-add-on' ),
				'help'    => __( 'Only Relationship fields that connect to a custom post type work.', 'pods-beaver-builder-themer-add-on' ),
				'options' => PodsBeaverPageData::pods_get_fields( array( 'type' => 'pick' ) ),
			);
		}
	}

	$options['pods_settings_relation'] = __( 'Settings / Logged In User', 'pods-beaver-builder-themer-add-on' );
	$toggle['pods_settings_relation']  = array(
		'fields' => array(
			'pods_source_settings_relation',
		),
	);

	$setting_fields = array(
		'pods_source_type'              => array(
			'type'        => 'select',
			'label'       => __( 'Relation Source', 'pods-beaver-builder-themer-add-on' ),
			'default'     => 'no',
			'help'        => __( 'Modify the custom query to use data from a pods relationship field', 'pods-beaver-builder-themer-add-on' ),
			'description' => __( '', 'pods-beaver-builder-themer-add-on' ),
			'options'     => $options,
			'toggle'      => $toggle,
		),
		'pods_source_relation'          => $pods_source_relation,
		'pods_source_settings_relation' => $source_settings_relation,
	);
	?>
    <div id="fl-builder-settings-section-pods" class="fl-builder-settings-section" data-source="pods_relationship">
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
    <script type="text/javascript">
        (function ($) {
            $('body').on('change', '.fl-loop-data-source-select select[name="data_source"]', function () {
                var val = $(this).val();
                if ('pods_relationship' === val) {
                    $('.fl-loop-data-source').show();
                    $('#fl-builder-settings-section-general').show();
                }
            });
        })(jQuery);
    </script>
	<?php

    /**
     * Same functionality added with BB 2.1.4 ( Selection Order )
     *
     * @deprecated 1.3.1
     */
	if ( defined( 'FL_BUILDER_VERSION' ) && version_compare( FL_BUILDER_VERSION, '2.1.4', '<' ) ) {
		add_filter( 'fl_builder_render_settings_field', 'pods_beaver_render_settings_field_order_by', 10, 3 );
	}

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

	if ( ! isset( $settings->data_source ) || 'pods_relationship' != $settings->data_source ) {
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

	// we need to define the type of user due to the addition of author and modified user in the field connections @todo: add the same option to post modules
    $settings->pods_user_type = "logged_in";

	$pod = PodsBeaverPageData::get_pod( $settings );

	if ( 'pods_relation' === $settings->pods_source_type && ! empty( $settings->pods_source_relation ) ) {
		$field_params = array(
			'name' => trim( $settings->pods_source_relation ),
		);
	} elseif ( 'pods_settings_relation' === $settings->pods_source_type ) {
		$field_params = array(
			'name' => trim( $settings->field ),
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

	add_filter( 'fl_builder_loop_query_args', 'pods_beaver_uabb_blog_posts', 10, 1 );

	$setting_post_type_ids_field_name = 'posts_' . $settings->post_type;
	$settings_post_type_matching_field_name = 'posts_' . $settings->post_type . '_matching';

	// get comma separated list to power post__in for the BB Custom Query
	if ( is_array( $ids ) ) {
		$ids = implode( ', ', $ids );
	}

	$settings->{$setting_post_type_ids_field_name} = $ids;
	$settings->{$settings_post_type_matching_field_name} = '1';


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
 * @param array $field
 * @param string $name
 * @param object $settings
 *
 * @return array
 *
 * @since 1.1
 * @deprecated 1.3.1 same functionality added with BB 2.1.4
 *
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
 * @param array $args
 * @param object $settings
 *
 * @return array
 *
 * @since 1.1
 */
function pods_beaver_uabb_blog_posts( $args ) {

	$args['post_type'] = 'any';
	remove_filter( 'fl_builder_loop_query_args', 'pods_beaver_uabb_blog_posts' );

	return $args;
}

/**
 * Adds PODS relation as data source for posts module.
 *
 * @since 1.3
 *
 * @param array $field
 * @param string $name The field name.
 * @param object $settings
 *
 * @return array
 */
function pods_beaver_render_settings_field( $field, $name, $settings ) {
	if ( 'data_source' != $name ) {
		return $field;
	}

	$field['options']['pods_relationship'] = __( 'Pods Relationship', 'pods-beaver-builder-themer-add-on' );
	$field['toggle']['pods_relationship']  = array(
		'sections' => array( 'pods' ),
		'fields'   => array( 'pods_source_type', 'posts_per_page' )
	);
	$field['hide']['pods_relationship']  = array(
		'sections' => array( 'filter' ),
		'fields'   => array( 'post_type', 'exclude_self' )
	);

	return $field;
}

/**
 * Update module settings to use data_source
 * and remove the (deprecated) use_pods option
 * @todo: maybe change filter to `filter_settings` method once BB 2.2 is released and make that required ;)
 *
 * @since 1.3
 *
 * @param array $data An array of layout node objects.
 * @param string $status Either published or draft.
 * @param int $post_id The ID of the post
 *
 * @return mixed
 */
function pods_beaver_update_module_settings_data_source( $data, $status, $post_id ) {

	foreach ( $data as $node ) {
		if ( 'module' === $node->type && property_exists( $node->settings, 'use_pods' ) ) {
			$module_settings = $node->settings;
			if (  'no' !== $module_settings->use_pods  ) {
				$module_settings->pods_source_type = $module_settings->use_pods;
				$module_settings->data_source      = 'pods_relationship';
			}
			unset( $module_settings->use_pods );
			$data[ $node->node ]->settings = $module_settings;
		}
	}

	return $data;
}

/**
 * Register add-on with Pods Freemius connection.
 *
 * @since 1.3.3
 */
function pods_beaver_freemius() {
	try {
		fs_dynamic_init( [
			'id'               => '5349',
			'slug'             => 'pods-beaver-builder-themer-add-on',
			'type'             => 'plugin',
			'public_key'       => 'pk_d8a10a25a662419add4ff3fbcc493',
			'is_premium'       => false,
			'has_paid_plans'   => false,
			'is_org_compliant' => true,
			'parent'           => [
				'id'         => '5347',
				'slug'       => 'pods',
				'public_key' => 'pk_737105490825babae220297e18920',
				'name'       => 'Pods',
			],
			'menu'             => [
				'slug'        => 'pods-settings',
				'contact'     => false,
				'support'     => false,
				'affiliation' => false,
				'account'     => true,
				'pricing'     => false,
				'addons'      => true,
				'parent'      => [
					'slug' => 'pods',
				],
			],
		] );
	} catch ( \Exception $exception ) {
		return;
	}
}
add_action( 'pods_freemius_init', 'pods_beaver_freemius' );
