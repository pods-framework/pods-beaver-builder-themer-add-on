<?php

/**
 * Created by PhpStorm. TEST from SLACK !°
 * User: quasel
 * Date: 12.04.17
 * Time: 18:04
 */

class WPDBBGlobalStylesManager
{
	public static function init()
	{
		add_action( 'fl_builder_register_settings_form', __CLASS__ . '::add_brand_colors_to_global_settings', 10, 2 );
	}

	/**
	 *
	 */
	static function add_brand_colors_to_global_settings( $form, $id )
	{
		if ( 'global' == $id ) {
			$form[ 'tabs' ][ 'brand' ] = [
				'title' => __( 'Brand', 'wpd' ),
				'sections' => [
					'colors' => [
						'title' => __( 'Colors', 'wpd' ),
						'fields' => [
							'brand_primary' => [
								'type' => 'color',
								'label' => __( 'Brand Primary', 'wpd' ),
							],
							'brand_secondary' => [
								'type' => 'color',
								'label' => __( 'Brand Secondary', 'wpd' ),
							],
							'brand_success' => [
								'type' => 'color',
								'label' => __( 'Brand Success', 'wpd' ),
							],
							'brand_info' => [
								'type' => 'color',
								'label' => __( 'Brand Info', 'wpd' ),
							],
							'brand_warning' => [
								'type' => 'color',
								'label' => __( 'Brand Warning', 'wpd' ),
							],
							'brand_danger' => [
								'type' => 'color',
								'label' => __( 'Brand Danger', 'wpd' ),
							],
							'gray_bright' => [
								'type' => 'color',
								'label' => __( 'Gray Bright', 'wpd' ),
							],
							'gray_lighter' => [
								'type' => 'color',
								'label' => __( 'Gray Lighter', 'wpd' ),
							],
							'gray_light' => [
								'type' => 'color',
								'label' => __( 'Gray Light', 'wpd' ),
							],
							'gray' => [
								'type' => 'color',
								'label' => __( 'Gray', 'wpd' ),
							],
							'gray_dark' => [
								'type' => 'color',
								'label' => __( 'Gray Dark', 'wpd' ),
							],
							'gray_darker' => [
								'type' => 'color',
								'label' => __( 'Gray Darker', 'wpd' ),
							],
							'gray_darkest' => [
								'type' => 'color',
								'label' => __( 'Gray Darkest', 'wpd' ),
							],
						]
					]
				]
			];
		}

		return $form;
	}
}

WPDBBGlobalStylesManager::init();