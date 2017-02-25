<?php
/**
 * Plugin Name: Pods Beaver Builder Themer Add-On
 * Plugin URI: http://pods.io/
 * Description: Integration with Beaver Builder Themer  (https://www.wpbeaverbuilder.com); Provides a UI for mapping Field Connections with Pods
 * Version: 0.1-alpha
 * Author: Pods Framework Team / Quasel
 * Author URI: http://pods.io/about/
 * Text Domain: pods-beaver-themer
 * Domain Path: /languages/
 *
 * Copyright 2013-2016  Pods Foundation, Inc  (email : contact@podsfoundation.org)
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

define( 'PODS_BEAVER_VERSION', '0.1-alpha' );
define( 'PODS_BEAVER_FILE', __FILE__ );
define( 'PODS_BEAVER_DIR', plugin_dir_path( PODS_BEAVER_FILE ) );
define( 'PODS_BEAVER_URL', plugin_dir_url( PODS_BEAVER_FILE ) );


/**
 * Include main functions and initiate
 *
 */
function pods_beaver_init() {

	if ( ! function_exists( 'pods' ) || ! class_exists( 'FLBuilder' ) ) {
		return false;
	}

	// Include main functions
	// require_once( PODS_BEAVER_DIR . 'includes/functions.php' );
	require_once( PODS_BEAVER_DIR . 'classes/class-pods-page-data.php' );
	require_once( PODS_BEAVER_DIR . 'includes/pods-page-data.php' );

}

add_action( 'fl_page_data_add_properties', 'pods_beaver_init' );



/**
 * Admin nag if Pods or BEAVER isn't activated.
 */
add_action( 'plugins_loaded', 'pods_beaver_admin_nag' );

function pods_beaver_admin_nag() {
	if ( is_admin() && ( ! class_exists( 'FLBuilder' ) || ! defined( 'PODS_VERSION' ) ) ) {
		echo sprintf( '<div id="message" class="error"><p>%s</p></div>',
			__( 'Pods Beaver Themer requires that the Pods and Beaver Builder Themer plugins be installed and activated.', 'pods-beaver-themer' )
		);
	}

}
