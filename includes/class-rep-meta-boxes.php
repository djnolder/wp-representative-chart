<?php

namespace Rep_Chart;

/**
 *  Addon Main Class
 */
defined('ABSPATH') || exit;

/**
 * Main Class
 */
class REPCHART_Meta_Boxes
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
        add_action('init', array(&$this, 'init'));
        if (is_admin()) {
            add_action('admin_init', array(&$this, 'admin_init'));
        }
    }

    /**
     * Function called by WordPress's init action
     */
    public function init() {}

    /**
     * Initialize the admin, adding actions to properly display and handle 
     * the Book custom post type add/edit page
     */
    public function admin_init()
    {
        global $pagenow;
        if ($pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php') {
            add_action('add_meta_boxes', [$this, 'meta_boxes'], 10, 3);
            add_action('save_post', [$this, 'meta_boxes_save'], 1, 2);
        }
    }

    /**
     * Add the meta boxe used for the representative_chart post type
     */
    public function meta_boxes($post_type, $post)
    {
        if ($post_type == 'representative_chart') {
            add_meta_box(
                'representative-meta-box',
                __('Representative Variables', REPCHART),
                [$this, 'representative_meta_box'],
                'representative_chart',
                'normal',
                'high'
            );
        }
    }

    /**
     * Display the meta box
     */
    public function representative_meta_box()
    {
        global $post;

        // build the image data
        $upload_link = esc_url(get_upload_iframe_src('image', $post->ID));
        $profile_picture = get_post_meta($post->ID, 'profile_picture', true);
        $image_src =  wp_get_attachment_image_src($profile_picture, 'full');

        // grab the other fields
        $email_address = get_post_meta($post->ID, 'email_address', true);
        $phone_number = get_post_meta($post->ID, 'phone_number', true);
        $company_and_agency_name = get_post_meta($post->ID, 'company_and_agency_name', true);



?>
        <div class="representative_meta_box">
            <div class="field_wrapper">
                <div><label for="profile_picture_image">Profile Picture</label></div>
                <div class="image_box">
                    <input type="hidden" name="profile_picture" id="profile_picture" value="<?php echo $profile_picture; ?>" />
                    <div class="profile_image">
                        <?php if (is_array($image_src)): ?>
                            <img src="<?php echo $image_src[0] ?>" alt="" />
                        <?php endif; ?>
                    </div>
                    <a class="set_profile_picture <?php if (is_array($image_src)) echo 'hidden'; ?>" title="Set" href="#"><img src="<?php echo REPCHART_PLUGIN_URL ?>assets/icons/pencil.svg" /></a>
                    <a class="remove_profile_picture <?php if (!is_array($image_src)) echo 'hidden'; ?>" title="Remove" href="#"><img src="<?php echo REPCHART_PLUGIN_URL ?>assets/icons/xmark.svg" /></a>
                </div>
            </div>
            <div class="field_wrapper">
                <div><label for="email_address">Email Address</label></div>
                <div><input type="email" id="email_address" name="email_address" value="<?php echo $email_address; ?>" /></div>
            </div>
            <div class="field_wrapper">
                <div><label for="phone_number">Phone Number</label></div>
                <div><input type="text" id="phone_number" name="phone_number" value="<?php echo $phone_number; ?>" /></div>
            </div>
            <div class="field_wrapper">
                <div><label for="company_and_agency_name">Company And Agency Name</label></div>
                <div><input type="text" id="company_and_agency_name" name="company_and_agency_name" value="<?php echo $company_and_agency_name; ?>" /></div>
            </div>
        </div>
<?php
    }

    /**
     * Save meta boxes
     * 
     * Runs when a post is saved and does an action which the write panel save scripts can hook into.
     */
    public function meta_boxes_save($post_id, $post)
    {
        if (empty($post_id) || empty($post) || empty($_POST)) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (is_int(wp_is_post_revision($post))) return;
        if (is_int(wp_is_post_autosave($post))) return;
        if (! current_user_can('edit_post', $post_id)) return;
        if ($post->post_type != 'representative_chart') return;

        update_post_meta($post_id, 'profile_picture', $_POST['profile_picture']);
        update_post_meta($post_id, 'email_address', $_POST['email_address']);
        update_post_meta($post_id, 'phone_number', $_POST['phone_number']);
        update_post_meta($post_id, 'company_and_agency_name', $_POST['company_and_agency_name']);
    }
}

new REPCHART_Meta_Boxes();
