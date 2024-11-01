<?php

/**
 * Fired when the plugin is uninstalled. 
 */
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;


//Delete faqscat Taxonomy terms
$wpdb->get_results($wpdb->prepare("DELETE t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s')", 'faqscat'));
$wpdb->delete($wpdb->term_taxonomy, array('taxonomy' => $taxonomy), array('%s'));

//Force Delete all wpsimplefaqs posts
$faqsPosts = $wpdb->get_results('SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_type="wpsimplefaqs"');
foreach ($faqsPosts as $faqPost):
    wp_delete_post($faqPost->ID, true);
endforeach;

//Delete settings options
$options = [
    'wpsimplefaqs_headingcolor',
    'wpsimplefaqs_headingtxtcolor',
    'wpsimplefaqs_headingcontentcolor',
    'wpsimplefaqs_grouped',
];
foreach ($options as $option):
    delete_option($option);
endforeach;

