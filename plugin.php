<?php
 /**
 * Plugin Name: Wholesale Rep Map
 * Plugin URI: 
 * Description: A representative chart, also known as a data visualization or graph, is a visual tool used to illustrate data and information in an easily interpretable format.
 * Version: 1.0.0
 * Requires at least: 6.3
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Author: Perceptions Studio
 * Author URI: 
 * Text Domain: wp-representative-chart
 * License: GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
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

define( 'REPCHART_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'REPCHART_ABSPATH', dirname( __FILE__ ) . '/' );
define( 'REPCHART', 'wp-representative-chart');


include_once REPCHART_ABSPATH . '/includes/class-rep-main.php';

/**
 * Main instance function.
 */
function rep_chart_init() {
  return REP_Chart_Main::instance();
}
add_action( 'plugins_loaded', 'rep_chart_init' );