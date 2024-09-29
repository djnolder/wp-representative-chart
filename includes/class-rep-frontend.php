<?php

namespace Rep_Chart;

/**
 *  Addon Main Class
 */
defined('ABSPATH') || exit;

/**
 * Main Class
 */
class Frontend
{
    /**
     * Variable used for Singleton instance
     *
     * @var [type]
     */
    protected static $_instance = null;

    /**
     * function to initiate the Singleton instance 
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     * 
     * Used main to initialize any hooks needed
     */
    public function __construct()
    {
        add_shortcode('representative_information', array($this, 'res_chart_shortcode'));
    }

    /**
     * Shortcode to output rep chart
     *
     * @param [array] $atts
     * @return void
     */
    public function res_chart_shortcode($atts)
    {
        ob_start();

        $posts = get_posts([
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type' => 'representative_chart',
        ]);

        $total_posts = sizeof($posts);

        if ($total_posts) :

            $location_data = array();
            $location_terms = get_terms(
                array(
                    'taxonomy'   => 'location',
                    'hide_empty' => false,
                )
            );

            foreach ($location_terms as $location_term) {
                $term_name = isset($location_term->name) ? $location_term->name : '';
                $term_count = isset($location_term->count) ? $location_term->count : 0;
                $location_data[$term_name] = $term_count;
            }
?>
            <div class="rep-section">
                <div class="rep-chart-wrap">
                    <div class="regions_wrapper">
                        <div id="regions_map" data-locationdata='<?= json_encode($location_data); ?>'><?= file_get_contents(REPCHART_PLUGIN_URL . "assets/images/us.svg"); ?></div>
                        <div id="regions_map_tooltip" class="hidden">
                            <div class="state">State</div>
                            <div class="reptesentatives">Representatives: <span class="count">0</span></div>
                        </div>
                    </div>
                    <div class="rep-regions-lists">
                        <div id="rep-regions-heading">
                            There are <?= esc_attr($total_posts); ?> sales reps around the <b>US</b>.
                        </div>
                        <div class="rep-sales-rep-content">
                            <div class="rep-sales-rep-content-wrapper">
                                <?php
                                foreach ($posts as $post) :
                                    $post_id = $post->ID;
                                    $post_title = get_the_title($post_id);
                                    $caa_name = get_post_meta($post_id, 'company_and_agency_name', true);
                                    $email_address = get_post_meta($post_id, 'email_address', true);
                                    $phone_number = get_post_meta($post_id, 'phone_number', true);
                                    $profile_picture_id = get_post_meta($post_id, 'profile_picture', true);
                                    $profile_picture =  wp_get_attachment_image_src($profile_picture_id, 'medium');
                                    $location_terms = get_the_terms($post_id, 'location');
                                    $regions = array_map(function ($c) {
                                        return $c->description;
                                    }, $location_terms);
                                ?>
                                    <div class="rep-data visible" data-regions='<?= json_encode($regions) ?>'>
                                        <?php if (! empty($profile_picture[0])) : ?>
                                            <img class="profile-picture" src="<?= esc_url($profile_picture[0]); ?>" alt="<?= esc_attr($post_title); ?>" />
                                        <?php endif; ?>
                                        <div class="rep-content">
                                            <div class="title"><?= esc_html($post_title); ?></div>

                                            <?php if (! empty($caa_name)) : ?>
                                                <div class="company-and-agency-name"><?= wp_kses_post($caa_name); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="rep-contact">
                                            <?php if (! empty($email_address)) : ?>
                                                <div class="email_address">
                                                    <a href="mailto:<?= esc_attr($email_address); ?>">
                                                        <img class="mail-icon" src="<?= REPCHART_PLUGIN_URL ?>assets/icons/email.svg" />
                                                        <?= wp_kses_post($email_address); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (! empty($phone_number)) : ?>
                                                <div class="phone_number">
                                                    <a href="tel:<?= $this->format_phone($phone_number, true); ?>">
                                                        <img class="call-icon" src="<?= REPCHART_PLUGIN_URL ?>assets/icons/phone.svg" />
                                                        <?= $this->format_phone($phone_number); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
        endif;

        return ob_get_clean();
    }

    function format_phone($data, $link = false)
    {
        // Check if international
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $ipdat = @json_decode(file_get_contents(
            "http://www.geoplugin.net/json.gp?ip=" . $ip
        ));

        $prefix = '';
        if ($ipdat->geoplugin_countryName && $ipdat->geoplugin_countryName != 'United States') {
            $prefix = '+1';
        }
        if (preg_match('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', $data,  $matches)) {
            if ($link) {
                return $prefix . $matches[1] . $matches[2] . $matches[3];
            } else {
                return $prefix . ' (' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
            }
            return $result;
        }
        return $data;
    }
}

new Frontend();
