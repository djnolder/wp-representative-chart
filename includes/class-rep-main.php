<?php
/**
 *  Addon Main Class
 */
defined( 'ABSPATH' ) || exit;

/**
 * Main Class
 */
class REP_Chart_Main {

    /**
	 * Created Instance for main class
	 *
	 * @var Object
	 */
	protected static $_instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {

        $this->includes();
		$this->init_hooks();
	}

	/**
	 * Instance function
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    public function includes() {

        include_once REPCHART_ABSPATH . 'includes/class-rep-post-and-texonomy.php';
        include_once REPCHART_ABSPATH . 'includes/class-rep-admin-main.php';
    }

    /**
     * Initialize hooks
     */
    public function init_hooks() {
        add_action( 'init', array( $this, 'register_custom_post_type_and_taxonomy' ) );
    }

    /**
     * Register custom post type and taxonomy
     */
    public function register_custom_post_type_and_taxonomy() {
        $rep_chart = new REP_Post_And_Taxonomy();
        new REPCHART_Admin_Main();
        $rep_chart->register();
    }
}