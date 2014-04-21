<?php
/*
Plugin Name:     Simple Shortcodes
Plugin URI:      https://github.com/kiriaze/simple-shortcodes
Description:     Simple Shortcodes Description
Version:         1.0.0
Author:          Constantine Kiriaze (@kiriaze)
Author URI:      http://getsimple.io/about
License:         GNU General Public License v2 or later
License URI:     http://www.gnu.org/licenses/gpl-2.0.html
Copyright:       (c) 2013, Constantine Kiriaze
Text Domain:     simple
GitHub Plugin URI: https://github.com/kiriaze/simple-shortcodes
GitHub Branch:     master
*/

/*
    Copyright (C) 2013  Constantine Kiriaze (hello@kiriaze.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

//  Define Constants

define( 'SIMPLE_SHORTCODES_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define( 'SIMPLE_SHORTCODES_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'SIMPLE_SHORTCODES_PLUGIN_BASENAME', plugin_basename(__FILE__));

//  Wrapped in after_setup_theme to utilize options
add_action('after_setup_theme', 'simple_shortcodes_init');
function simple_shortcodes_init(){
    if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
        //  Load class
        require_once( SIMPLE_SHORTCODES_PLUGIN_PATH . 'class-simple-shortcodes.php' );
    }
}