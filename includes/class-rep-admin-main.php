<?php
/**
 * This configuration sets the Representative file uploads.
 *
 * @package Representative Chart
 * @since 1.0
 */

// Addon Main Class.
defined( 'ABSPATH' ) || exit;

/**
 * Manage the mxupload admin.
 */
class REPCHART_Admin_Main {


	/**
	 * Constructor.
	 * Initializes and adds functions to filter and action hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_shortcode( 'representative_information', array( $this, 'res_chart_shortcode' ) );

        add_action( 'wp_ajax_representative_ajax_handle', array( $this, 'representative_ajax_handle' ) );
        add_action( 'wp_ajax_nopriv_representative_ajax_handle', array( $this, 'representative_ajax_handle' ) );

	}

	/**
	 * Styles for the settings page.
	 *
	 * @since 2.0
	 */
	public function enqueue_assets() {

        wp_enqueue_style( 'custom-style', REPCHART_PLUGIN_URL . 'assets/css/style.css', array(), wp_get_theme()->get( 'Version' ), 'all' );

        wp_enqueue_script( 'charts-script', 'https://www.gstatic.com/charts/loader.js', array(), wp_get_theme()->get( 'Version' ), true );
		wp_enqueue_script( 'custom-script', REPCHART_PLUGIN_URL . 'assets/js/custom.js', array(), wp_get_theme()->get( 'Version' ), true );

        wp_localize_script( 'custom-script', 'repchart_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'repchart_ajax_nonce' ),
		));
        
	}

    /**
     * The content.
     *
     * @since 1.0
     */
    public function res_chart_shortcode( $atts ) {
        ob_start();

        $args = array(
            'post_type'      => 'representative_chart',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        $query = new WP_Query( $args );
        $total_posts = $query->found_posts;

        if ( $query->have_posts() ) :
        ?>
            <div class="rep-section">
                <div class="rep-chart-info">
                    <?php /* <p class="total-sales-resp">Total Sales reps: <?php echo esc_html( $total_posts ); ?></p> */ ?>
                    <div class="rep-chart-wrap">
                        <div class="rep-chart-inner-wrap">
                            <?php
                            $location_data = array();
                            $location_terms = get_terms(
                                    array(
                                    'taxonomy'   => 'location',
                                    'hide_empty' => false,
                                )
                            );

                            foreach ( $location_terms as $key => $location_term ) {
                                $terms_name = isset( $location_term->name ) ? $location_term->name : '';
                                $terms_count = isset( $location_term->count ) ? $location_term->count : '';

                                $location_data[] = array( $terms_name, $terms_count );
                            }

                            ?>
                            <div class="regions_wrapper" data-taxonomy='<?php echo json_encode( $location_data ); ?>'>
                                <div id="regions_data"></div>
                            </div>
                            <div class="rep-regions-lists">
                                <div class="page-loader">
                                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                        <path fill="#154734" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"><animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" /></path>
                                    </svg>
                                </div>
                                <div class="rep-regions-heading">
                                    <div class="location-name">
                                        <span class="location-svg">
                                            <svg height="40px" width="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 255.856 255.856" xml:space="preserve">
                                                <g>
                                                    <path style="fill:#000002;" d="M127.928,38.8c-30.75,0-55.768,25.017-55.768,55.767s25.018,55.767,55.768,55.767
                                                        s55.768-25.017,55.768-55.767S158.678,38.8,127.928,38.8z M127.928,135.333c-22.479,0-40.768-18.288-40.768-40.767
                                                        S105.449,53.8,127.928,53.8s40.768,18.288,40.768,40.767S150.408,135.333,127.928,135.333z"/>
                                                    <path style="fill:#000002;" d="M127.928,0C75.784,0,33.362,42.422,33.362,94.566c0,30.072,25.22,74.875,40.253,98.904
                                                        c9.891,15.809,20.52,30.855,29.928,42.365c15.101,18.474,20.506,20.02,24.386,20.02c3.938,0,9.041-1.547,24.095-20.031
                                                        c9.429-11.579,20.063-26.616,29.944-42.342c15.136-24.088,40.527-68.971,40.527-98.917C222.495,42.422,180.073,0,127.928,0z
                                                        M171.569,181.803c-19.396,31.483-37.203,52.757-43.73,58.188c-6.561-5.264-24.079-26.032-43.746-58.089
                                                        c-22.707-37.015-35.73-68.848-35.73-87.336C48.362,50.693,84.055,15,127.928,15c43.873,0,79.566,35.693,79.566,79.566
                                                        C207.495,112.948,194.4,144.744,171.569,181.803z"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <span class="sub-title">Region</span>
                                    </div>
                                    <div class="total-sales-reps"><?php echo esc_attr( $total_posts ); ?> Sales reps</div>
                                </div>
                                <div class="rep-sales-rep-content">
                                    <div class="rep-sales-rep-content-wrapper">
                                        <?php
                                        while ( $query->have_posts() ) :
                                            $query->the_post();

                                            $post_id         = $query->post->ID;
                                            $post_title      = get_the_title( $post_id );
                                            $caa_name        = get_field( 'company_and_agency_name', $post_id );
                                            $email_address   = get_field( 'email_address', $post_id );
                                            $phone_number    = get_field( 'phone_number', $post_id );
                                            $profile_picture = get_field( 'profile_picture', $post_id );

                                            $phone_number_without_space = str_replace('-', '', $phone_number );

                                        ?>
                                            <div class="rep-data">
                                                <?php if ( ! empty( $profile_picture ) ) : ?>
                                                    <img class="profile-picture" src="<?php echo esc_url( $profile_picture ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" />
                                                <?php endif; ?>

                                                <div class="rep-content">
                                                    <p class="title"><?php echo esc_html( $post_title ); ?></p>

                                                    <?php if ( ! empty( $caa_name ) ) : ?>
                                                        <p class="company-and-agency-name"><?php echo wp_kses_post( $caa_name ); ?></p>
                                                    <?php endif; ?>

                                                    <?php if ( ! empty( $email_address ) || ! empty( $phone_number )  ) : ?>
                                                        <div class="rep-contact-details">
                                                            <?php if ( ! empty( $email_address ) ) : ?>
                                                                <p>
                                                                    <a href="mailto:<?php echo esc_attr( $email_address ); ?>">
                                                                    <span class="mail-icon">
                                                                        <svg fill="#000000" width="30px" height="30px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                                            <title/>
                                                                            <g data-name="Layer 17" id="Layer_17">
                                                                                <path d="M25.12,6H6.88A3.89,3.89,0,0,0,3,9.89V21.11A3.89,3.89,0,0,0,6.88,25H25.12A3.89,3.89,0,0,0,29,21.11V9.89A3.89,3.89,0,0,0,25.12,6Zm0,2,.16,0L16,14.76,6.72,8l.16,0ZM27,21.11A1.89,1.89,0,0,1,25.12,23H6.88A1.89,1.89,0,0,1,5,21.11V9.89a1.92,1.92,0,0,1,.1-.59l10.32,7.51a1,1,0,0,0,1.18,0L26.91,9.3a1.92,1.92,0,0,1,.1.59Z"/>
                                                                            </g>
                                                                        </svg>
                                                                    </span>
                                                                    <?php echo wp_kses_post( $email_address ); ?>
                                                                    </a>
                                                                </p>
                                                            <?php endif; ?>
                                                            <?php if ( ! empty( $phone_number ) ) : ?>
                                                                <p>
                                                                    <a href="tel:<?php echo esc_attr( $phone_number_without_space ); ?>">
                                                                        <span class="call-icon">
                                                                            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.3545 22.2323C15.3344 21.7262 11.1989 20.2993 7.44976 16.5502C3.70065 12.8011 2.2738 8.66559 1.76767 6.6455C1.47681 5.48459 2.00058 4.36434 2.88869 3.72997L5.21694 2.06693C6.57922 1.09388 8.47432 1.42407 9.42724 2.80051L10.893 4.91776C11.5152 5.8165 11.3006 7.0483 10.4111 7.68365L9.24234 8.51849C9.41923 9.1951 9.96939 10.5846 11.6924 12.3076C13.4154 14.0306 14.8049 14.5807 15.4815 14.7576L16.3163 13.5888C16.9517 12.6994 18.1835 12.4847 19.0822 13.1069L21.1995 14.5727C22.5759 15.5257 22.9061 17.4207 21.933 18.783L20.27 21.1113C19.6356 21.9994 18.5154 22.5232 17.3545 22.2323ZM8.86397 15.136C12.2734 18.5454 16.0358 19.8401 17.8405 20.2923C18.1043 20.3583 18.4232 20.2558 18.6425 19.9488L20.3056 17.6205C20.6299 17.1665 20.5199 16.5348 20.061 16.2171L17.9438 14.7513L17.0479 16.0056C16.6818 16.5182 16.0047 16.9202 15.2163 16.7501C14.2323 16.5378 12.4133 15.8569 10.2782 13.7218C8.1431 11.5867 7.46219 9.7677 7.24987 8.7837C7.07977 7.9953 7.48181 7.31821 7.99439 6.95208L9.24864 6.05618L7.78285 3.93893C7.46521 3.48011 6.83351 3.37005 6.37942 3.6944L4.05117 5.35744C3.74413 5.57675 3.64162 5.89565 3.70771 6.15943C4.15989 7.96418 5.45459 11.7266 8.86397 15.136Z" fill="#0F0F0F"/>
                                                                            </svg>
                                                                        </span>
                                                                        <?php echo wp_kses_post( $phone_number ); ?>
                                                                    </a>
                                                                </p>                                                   
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php
                                        endwhile;
                                        wp_reset_postdata();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endif;
        return ob_get_clean();
    }

    /**
     * Callback function to handle the AJAX request
     */
    public function representative_ajax_handle() {

        $response = array(
            'status' => false,
        );

        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'repchart_ajax_nonce' ) ) {
            $response['message'] = 'Nonce verification failed. Please try again';
        }

        $country = isset( $_POST['country'] ) ? $_POST['country'] : '';
        $country_slug = strtolower( $country );
        
        $args = array(
            'post_type'      => 'representative_chart',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'location',
                    'field' => 'slug',
                    'terms' => $country_slug,
                ),
            ),
        );

        $query = new WP_Query( $args );
        $total_posts = $query->found_posts;
        if ( $query->have_posts() ) :
            ob_start();
            ?>
            <div class="rep-sales-rep-content-wrapper">
                <?php
                while ( $query->have_posts() ) :
                    $query->the_post();

                    $post_id         = $query->post->ID;
                    $post_title      = get_the_title( $post_id );
                    $caa_name        = get_field( 'company_and_agency_name', $post_id );
                    $email_address   = get_field( 'email_address', $post_id );
                    $phone_number    = get_field( 'phone_number', $post_id );
                    $profile_picture = get_field( 'profile_picture', $post_id );

                    $phone_number_without_space = str_replace('-', '', $phone_number );

                ?>
                    <div class="rep-data">
                        <?php if ( ! empty( $profile_picture ) ) : ?>
                            <img class="profile-picture" src="<?php echo esc_url( $profile_picture ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" />
                        <?php endif; ?>

                        <div class="rep-content">
                            <p class="title"><?php echo esc_html( $post_title ); ?></p>

                            <?php if ( ! empty( $caa_name ) ) : ?>
                                <p class="company-and-agency-name"><?php echo wp_kses_post( $caa_name ); ?></p>
                            <?php endif; ?>

                            <?php if ( ! empty( $email_address ) || ! empty( $phone_number )  ) : ?>
                                <div class="rep-contact-details">
                                    <?php if ( ! empty( $email_address ) ) : ?>
                                        <p>
                                            <a href="mailto:<?php echo esc_attr( $email_address ); ?>">
                                            <span class="mail-icon">
                                                <svg fill="#000000" width="30px" height="30px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                                    <title/>
                                                    <g data-name="Layer 17" id="Layer_17">
                                                        <path d="M25.12,6H6.88A3.89,3.89,0,0,0,3,9.89V21.11A3.89,3.89,0,0,0,6.88,25H25.12A3.89,3.89,0,0,0,29,21.11V9.89A3.89,3.89,0,0,0,25.12,6Zm0,2,.16,0L16,14.76,6.72,8l.16,0ZM27,21.11A1.89,1.89,0,0,1,25.12,23H6.88A1.89,1.89,0,0,1,5,21.11V9.89a1.92,1.92,0,0,1,.1-.59l10.32,7.51a1,1,0,0,0,1.18,0L26.91,9.3a1.92,1.92,0,0,1,.1.59Z"/>
                                                    </g>
                                                </svg>
                                            </span>
                                            <?php echo wp_kses_post( $email_address ); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $phone_number ) ) : ?>
                                        <p>
                                            <a href="tel:<?php echo esc_attr( $phone_number_without_space ); ?>">
                                                <span class="call-icon">
                                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.3545 22.2323C15.3344 21.7262 11.1989 20.2993 7.44976 16.5502C3.70065 12.8011 2.2738 8.66559 1.76767 6.6455C1.47681 5.48459 2.00058 4.36434 2.88869 3.72997L5.21694 2.06693C6.57922 1.09388 8.47432 1.42407 9.42724 2.80051L10.893 4.91776C11.5152 5.8165 11.3006 7.0483 10.4111 7.68365L9.24234 8.51849C9.41923 9.1951 9.96939 10.5846 11.6924 12.3076C13.4154 14.0306 14.8049 14.5807 15.4815 14.7576L16.3163 13.5888C16.9517 12.6994 18.1835 12.4847 19.0822 13.1069L21.1995 14.5727C22.5759 15.5257 22.9061 17.4207 21.933 18.783L20.27 21.1113C19.6356 21.9994 18.5154 22.5232 17.3545 22.2323ZM8.86397 15.136C12.2734 18.5454 16.0358 19.8401 17.8405 20.2923C18.1043 20.3583 18.4232 20.2558 18.6425 19.9488L20.3056 17.6205C20.6299 17.1665 20.5199 16.5348 20.061 16.2171L17.9438 14.7513L17.0479 16.0056C16.6818 16.5182 16.0047 16.9202 15.2163 16.7501C14.2323 16.5378 12.4133 15.8569 10.2782 13.7218C8.1431 11.5867 7.46219 9.7677 7.24987 8.7837C7.07977 7.9953 7.48181 7.31821 7.99439 6.95208L9.24864 6.05618L7.78285 3.93893C7.46521 3.48011 6.83351 3.37005 6.37942 3.6944L4.05117 5.35744C3.74413 5.57675 3.64162 5.89565 3.70771 6.15943C4.15989 7.96418 5.45459 11.7266 8.86397 15.136Z" fill="#0F0F0F"/>
                                                    </svg>
                                                </span>
                                                <?php echo wp_kses_post( $phone_number ); ?>
                                            </a>
                                        </p>                                                   
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <?php
            $html_content = ob_get_clean();
        endif;

        $response['total_posts'] = $total_posts;

        if ( ! empty( $html_content ) ) {
            $response['status'] = true;
            $response['data'] = $html_content;
        } else {
            $response['status'] = false;
            $response['message'] = 'Please Choose the region to see sales rep';
        }

        // Send JSON response
        wp_send_json_success( $response );
    }
}
