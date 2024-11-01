<?php

/**
 * Plugin Name:       WP Simple Faqs
 * Plugin URI:        http://www.infobeans.com
 * Description:       A plugin to list and manage FAQ section in your website.
 * Version:           1.0
 * Donate link:       http://donatenow.wc.lt/?donate=iamkapildude@gmail.com&item-name=Development and Maintenance of Plugins&method=PayPal
 * Requires at least: 4.0
 * Tested up to:      4.9
 * Requires PHP:      5.6
 * Stable tag:        1.0
 * Author:            Kapil Yadav
 * Author URI:        https://profiles.wordpress.org/lpkapil008/#content-plugins
 * Text Domain:       wpsimplefaqs
 * License:           GPL-2.0+
 * 
 * PHP version 5.6
 * 
 * @category Class
 * @package  WpSimpleFaqs
 * @author   Kapil Yadav <kapil.yadav@infobeans.com>
 * @license  open source
 * @version  GIT: https://github.com/lpkapil/wpsimplefaqs
 * @link     https://github.com/lpkapil/wpsimplefaqs
 */

namespace WpSimpleFaqs;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * WpSimpleFaqs Class Doc Comment
 *
 * @category Class
 * @package  WpSimpleFaqs
 * @author   Kapil Yadav <kapil.yadav@infobeans.com>
 * @license  open source
 * @version  Release: prod_1.01
 * @link     https://github.com/lpkapil/wpsimplefaqs
 *
 */
class WpSimpleFaqs {

    /**
     * Member Variables
     * 
     * @since   1.0
     */
    private static $_instance, $_version;
    public $helpTabs, $textDomain;

    /**
     * Class constructor
     * 
     * @since 1.0
     */
    private function __construct() {
        self::$_version = '1.0';
        $this->textDomain = 'wpsimplefaqs';
        $this->help_tabs = array(
            array(
                'id' => 'wac_help_tab1',
                'title' => __('About Plugin'),
                'content' => '<p>' . __('<strong>WP Simple FAQs</strong> is a complete solution for managing and displaying FAQs in website pages easily using shortcode support. It also provides settings for setting color and categorised view of FAQs.') . '</p>',
            ),
            array(
                'id' => 'wac_help_tab2',
                'title' => __('Admin Settings'),
                'content' => '<p>' . __('<ul><li><strong>FAQs: </strong> For managing FAQs navigate to , <strong> Admin > FAQs </strong> section, form here you can manage all your Questions and respective answers.</li><li><strong>FAQs Categories: </strong> For managing FAQs Categories navigate to <strong>Admin > FAQs Categories</strong> section, from here you can manage FAQs categories.</li><li><strong>FAQs Settings: </strong> For managing FAQs settigns nvigate to <strong> Admin > FAQs Settings </strong> section, from this section you can set colors and configure other settings.</li><br></ul>') . '</p>',
            ),
            array(
                'id' => 'wac_help_tab3',
                'title' => __('Frontend Display'),
                'content' => '<p>' . __('<ul><li><strong>Shortcode: </strong> For Adding FAQs in a page, simply add shortcode <code>[wpsimplefaqs]</code> to the page content section.</li><li><strong>Templates: </strong> For adding FAQs using wordpress page tempaltes, simply use <code>wpSimpleFaqsLoad();</code> function in page/post template.</li><br></ul>') . '</p>',
            ),
            array(
                'id' => 'wac_help_tab4',
                'title' => __('About Author'),
                'content' => '<p>' . __('Passionate Wordpress Developer and Security Researcher.<br><br><strong class="dashicons-before dashicons-businessman"></strong><a href="https://profiles.wordpress.org/lpkapil008/#content-plugins" target="_blank"> Wordpress Profile</a><br><strong class="dashicons-before dashicons-email-alt"></strong> <a href="mailto:kapil.yadav@infobeans.com"> kapil.yadav@infobeans.com</a><br><strong class="dashicons-before dashicons-thumbs-up"></strong> <a href="http://donatenow.wc.lt/?donate=iamkapildude@gmail.com&item-name=Development and Maintenance of Plugins&method=PayPal" target="_blank"> Like It? Donate</a>') . '</p>',
            )
        );
        $this->wpSimpleFaqsInit();
    }

    /**
     * wpSimpleFaqsGetInstance Get Singleton Instance of the class
     * 
     * @Method wpSimpleFaqsGetInstance
     * @return object
     * @since   1.0
     */
    public static function wpSimpleFaqsGetInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new WpSimpleFaqs();
        }
        return self::$_instance;
    }

    /**
     * wpSimpleFaqsInit Initial Bootstrap Hooks and Filters
     * 
     * @Method wpSimpleFaqsInit
     * @return void
     * @since   1.0
     */
    public function wpSimpleFaqsInit() {

        //Hooks
        add_action('admin_init', [$this, 'wpSimpleFaqsRegisterSettings']);
        add_action('init', [$this, 'wpSimpleFaqsRegister']);
        add_action('wp_enqueue_scripts', [$this, 'wpSimpleFaqsLoadAssets']);
        add_action('admin_menu', [$this, 'wpSimpleFaqsAddSettingsPage']);
        add_action('admin_head', [$this, 'wpSimpleFaqsRemoveMedia']);
        add_action('admin_head', [$this, 'wpSimpleFaqsHelpTabs']);

        //Add Shortcode support
        add_shortcode('wpsimplefaqs', [$this, 'wpSimpleFaqsLoad']);
    }

    /**
     * wpSimpleFaqsGetInstance Get Singleton Instance of the class
     * 
     * @Method wpSimpleFaqsGetInstance
     * @return object
     * @since   1.0
     */
    public function wpSimpleFaqsLoadAssets() {
        wp_enqueue_style('wpsimplefaqsstyle', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], false, 'all');
        wp_enqueue_script('wpsimplefaqsscript', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['jquery'], false, false);
    }

    /**
     * wpSimpleFaqsRegister Register FAQs CPT and Taxonomy
     * 
     * @Method wpSimpleFaqsLoad
     * @link http://codex.wordpress.org/Function_Reference/register_post_type
     * @return void
     * @since   1.0
     */
    public function wpSimpleFaqsRegister() {

        $labels = array(
            'name' => _x('FAQs', 'post type general name', $this->textDomain),
            'singular_name' => _x('FAQ', 'post type singular name', $this->textDomain),
            'menu_name' => _x('FAQs', 'admin menu', $this->textDomain),
            'name_admin_bar' => _x('FAQ', 'add new on admin bar', $this->textDomain),
            'add_new' => _x('Add New', 'faq', $this->textDomain),
            'add_new_item' => __('Add New FAQ', $this->textDomain),
            'new_item' => __('New FAQ', $this->textDomain),
            'edit_item' => __('Edit FAQ', $this->textDomain),
            'view_item' => __('View FAQ', $this->textDomain),
            'all_items' => __('All FAQs', $this->textDomain),
            'search_items' => __('Search FAQs', $this->textDomain),
            'parent_item_colon' => __('Parent FAQs:', $this->textDomain),
            'not_found' => __('No faqs found.', $this->textDomain),
            'not_found_in_trash' => __('No faqs found in Trash.', $this->textDomain)
        );

        $args = array(
            'labels' => $labels,
            'description' => __('Description.', $this->textDomain),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'wpsimplefaqs'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => ['title', 'editor']
        );

        register_post_type('wpsimplefaqs', $args);

        //Register FAQs Taxonomy, faqscat
        $taxonomyLabels = array(
            'name' => _x('FAQ Categories', 'taxonomy general name', $this->textDomain),
            'singular_name' => _x('Category', 'taxonomy singular name', $this->textDomain),
            'search_items' => __('Search Categories', $this->textDomain),
            'popular_items' => __('Popular Categories', $this->textDomain),
            'all_items' => __('All Categories', $this->textDomain),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Category', $this->textDomain),
            'update_item' => __('Update Category', $this->textDomain),
            'add_new_item' => __('Add New Category', $this->textDomain),
            'new_item_name' => __('New Category Name', $this->textDomain),
            'separate_items_with_commas' => __('Separate categories with commas', $this->textDomain),
            'add_or_remove_items' => __('Add or remove categories', $this->textDomain),
            'choose_from_most_used' => __('Choose from the most used categories', $this->textDomain),
            'not_found' => __('No categories found.', $this->textDomain),
            'menu_name' => __('FAQ Categories', $this->textDomain),
        );

        $taxonomyArgs = array(
            'hierarchical' => false,
            'labels' => $taxonomyLabels,
            'show_ui' => true,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'public' => false,
            'rewrite' => ['slug' => 'faqscat'],
        );

        register_taxonomy('faqscat', 'wpsimplefaqs', $taxonomyArgs);
    }

    /**
     * wpSimpleFaqsRemoveMedia Remove Media Button from FAQs content editor
     * 
     * @return  void
     * @since   1.0
     */
    public function wpSimpleFaqsRemoveMedia() {
        global $current_screen;
        if ('wpsimplefaqs' === $current_screen->post_type):
            remove_action('media_buttons', 'media_buttons');
        endif;
    }

    /**
     * wpSimpleFaqsHelpTabs
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public function wpSimpleFaqsHelpTabs() {
        global $current_screen;

        if ('wpsimplefaqs' === $current_screen->post_type):
            foreach ($this->help_tabs as $tab) :
                $current_screen->add_help_tab($tab);
            endforeach;
        endif;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public function wpSimpleFaqsAddSettingsPage() {

        add_submenu_page('edit.php?post_type=wpsimplefaqs', __('FAQ Settings', $this->textDomain), __('FAQ Settings', $this->textDomain), 'manage_options', plugin_dir_path(__FILE__) . '/views/settings.php', '');
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public function wpSimpleFaqsLoad() {

        $headingColor = get_option('wpsimplefaqs_headingcolor');
        $headingTxtColor = get_option('wpsimplefaqs_headingtxtcolor');
        $headingContentColor = get_option('wpsimplefaqs_headingcontentcolor');
        $groupedSetting = get_option('wpsimplefaqs_grouped');

        if (empty($groupedSetting)):

            $faqs = new \WP_Query(['post_type' => 'wpsimplefaqs', 'post_status' => 'publish', 'posts_per_page' => -1]);
            if ($faqs->have_posts()) :
                while ($faqs->have_posts()) :
                    $faqs->the_post();
                    //Faqs Listing
                    include __DIR__ . '/views/faqs.php';
                endwhile;
            endif;
        else:

            $faqsCats = get_terms('faqscat', ['orderby' => 'name', 'order' => 'ASC', 'hide_empty' => true]);
            if (!empty($faqsCats)):
                foreach ($faqsCats as $cat):
                    // Categories Tag Cloud
                    include __DIR__ . '/views/faqs_tags.php';
                endforeach;
            endif;

            if (!empty($faqsCats)):
                foreach ($faqsCats as $cat):

                    //Category Heading
                    include __DIR__ . '/views/faqs_cat.php';

                    $faqs = new \WP_Query([
                        'post_type' => 'wpsimplefaqs',
                        'post_status' => 'publish',
                        'tax_query' => [
                            [
                                'taxonomy' => 'faqscat',
                                'field' => 'slug',
                                'terms' => [$cat->slug],
                                'operator' => 'IN'
                            ]
                        ]
                    ]);

                    if ($faqs->have_posts()) :
                        while ($faqs->have_posts()) :
                            $faqs->the_post();
                            //Faqs Listing
                            include __DIR__ . '/views/faqs.php';
                        endwhile;
                    endif;
                endforeach;
            endif;
        endif;
    }

    /**
     * Add a single condition, or an array of conditions to the WHERE clause of the query.
     * 
     * @param   mixed   $conditions  A string or array of where conditions.
     * @return  JDatabaseQuery  Returns this object to allow chaining.
     * @since   1.0
     */
    public function wpSimpleFaqsRegisterSettings() {
        register_setting('wpsimplefaqs_settings', 'wpsimplefaqs_headingcolor', 'string');
        register_setting('wpsimplefaqs_settings', 'wpsimplefaqs_headingtxtcolor', 'string');
        register_setting('wpsimplefaqs_settings', 'wpsimplefaqs_headingcontentcolor', 'string');
        register_setting('wpsimplefaqs_settings', 'wpsimplefaqs_grouped', 'string');
    }
}

$instance = WpSimpleFaqs::wpSimpleFaqsGetInstance();

register_activation_hook(__FILE__, [$instance, 'wpSimpleFaqsInit']);

