<?php

/**
 * Plugin Name: Wholesale Rep Map
 * Plugin URI: 
 * Description: A representative chart, also known as a data visualization or graph, is a visual tool used to illustrate data and information in an easily interpretable format.
 * Version: 1.01.05
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

namespace Rep_Chart;

define('REPCHART_VERSION', '1.01.05');
define('REPCHART_FILE', __FILE__);
define('REPCHART_PLUGIN_URL', plugin_dir_url(__FILE__));
define('REPCHART_ABSPATH', dirname(__FILE__) . '/');
define('REPCHART', 'wp-representative-chart');

class Loader
{
  private static $_instance = null;

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  protected function __construct()
  {
    // our includes
    include_once REPCHART_ABSPATH . 'includes/class-rep-post-and-taxonomy.php';
    include_once REPCHART_ABSPATH . 'includes/class-rep-frontend.php';
    include_once REPCHART_ABSPATH . 'includes/class-rep-meta-boxes.php';

    add_action('init', [$this, 'init']);

    add_action('admin_enqueue_scripts', [$this, 'admin_enqueue']);
    add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
  }

  public function init() {}

  /**
   * Styles for the settings page.
   */
  public function enqueue_assets()
  {
    wp_enqueue_style('rep-metabox-frontend-style', REPCHART_PLUGIN_URL . 'assets/css/frontend.min.css', null, REPCHART_VERSION, 'all');
    wp_enqueue_script('rep-metabox-frontend-script', REPCHART_PLUGIN_URL . 'assets/js/frontend.min.js', null, REPCHART_VERSION, true);
  }


  /**
   * Enqueue the admin.css file
   */
  public function admin_enqueue()
  {
    // we need to make sure the media kavascript is avail for the image picker in the meta box
    wp_enqueue_media();
    wp_enqueue_style('rep-metabox-admin-style', REPCHART_PLUGIN_URL . 'assets/css/admin.min.css', null, REPCHART_VERSION, 'all');
    wp_enqueue_script('rep-metabox-admin-script', REPCHART_PLUGIN_URL . 'assets/js/admin.min.js', null, REPCHART_VERSION, true);
  }
}
Loader::instance();
