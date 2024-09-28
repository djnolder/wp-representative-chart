<?php
/**
 * Class for registering the 'representative_chart' custom post type and 'chart_category' custom taxonomy.
 */

if ( ! class_exists( 'REP_Post_And_Taxonomy' ) ) {

    class REP_Post_And_Taxonomy {

        /**
         * Register hooks.
         */
        public function register() {
            $this->representative_chart_post_type();
            $this->representative_chart_taxonomy();
        }

        /**
         * Register custom post type.
         */
        public function representative_chart_post_type() {
            
            $labels = array(
                'name'                  => _x( 'Wholesale Rep', 'Post type general name', REPCHART ),
                'singular_name'         => _x( 'Wholesale Rep', 'Post type singular name', REPCHART ),
                'menu_name'             => _x( 'Wholesale Rep', 'Admin Menu text', REPCHART ),
                'name_admin_bar'        => _x( 'Wholesale Rep', 'Add New on Toolbar', REPCHART ),
                'add_new'               => __( 'Add New Wholesale Rep', REPCHART ),
                'add_new_item'          => __( 'Add New Wholesale Rep', REPCHART ),
                'new_item'              => __( 'New Wholesale Rep', REPCHART ),
                'edit_item'             => __( 'Edit Wholesale Rep', REPCHART ),
                'view_item'             => __( 'View Wholesale Rep', REPCHART ),
                'all_items'             => __( 'All Wholesale Rep', REPCHART ),
                'search_items'          => __( 'Search Wholesale Rep', REPCHART ),
                'parent_item_colon'     => __( 'Parent Wholesale Rep :', REPCHART ),
                'not_found'             => __( 'No Wholesale Rep found.', REPCHART ),
                'not_found_in_trash'    => __( 'No Wholesale Rep found in Trash.', REPCHART ),
                'featured_image'        => _x( 'Wholesale Rep Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', REPCHART ),
                'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', REPCHART ),
                'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', REPCHART ),
                'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', REPCHART ),
                'archives'              => _x( 'Wholesale Rep Chart Archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', REPCHART ),
                'insert_into_item'      => _x( 'Insert into Wholesale Rep Chart', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', REPCHART ),
                'uploaded_to_this_item' => _x( 'Uploaded to this Wholesale Rep Chart', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', REPCHART ),
                'filter_items_list'     => _x( 'Filter Wholesale Rep list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', REPCHART ),
                'items_list_navigation' => _x( 'Wholesale Rep list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', REPCHART ),
                'items_list'            => _x( 'Wholesale Rep list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', REPCHART ),
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'representative-chart' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array( 'title', 'thumbnail', 'excerpt'),
            );

            register_post_type( 'representative_chart', $args );
        }

        /**
         * Register custom taxonomy.
         */
        public function representative_chart_taxonomy() {
            $labels = array(
                'name'              => _x( 'Location', 'taxonomy general name', REPCHART ),
                'singular_name'     => _x( 'Location', 'taxonomy singular name', REPCHART ),
                'search_items'      => __( 'Search Location', REPCHART ),
                'all_items'         => __( 'All Location', REPCHART ),
                'parent_item'       => __( 'Parent Location', REPCHART ),
                'parent_item_colon' => __( 'Parent Location:', REPCHART ),
                'edit_item'         => __( 'Edit Location', REPCHART ),
                'update_item'       => __( 'Update Location', REPCHART ),
                'add_new_item'      => __( 'Add New Location', REPCHART ),
                'new_item_name'     => __( 'New Location Name', REPCHART ),
                'menu_name'         => __( 'Location', REPCHART ),
            );

            $args = array(
                'labels'        => $labels,
                'hierarchical'  => true,
                'public'        => true,
                'show_ui'       => true,
                'show_in_menu'  => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'query_var'     => true,
                'show_admin_column' => true,
            );

            register_taxonomy( 'location', array( 'representative_chart' ), $args );
        }
    }
}
