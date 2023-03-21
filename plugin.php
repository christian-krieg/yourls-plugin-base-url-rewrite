<?php
/*
Plugin Name: Custom shortlink base URL
Plugin URI: https://github.com/christian-krieg/yourls-plugin-rewrite-yourls-site
Description: Rewrite short links such that they point to another (base) domain
             (e.g., https://acme.com/r), different from the base domain of the
             Yourls installation. This can be helpful if you want to server
             Yourls from a sub-domain (e.g., https://link.acme.com), 
Version: 1.0
Author: Christian Krieg <christian@drkrieg.at>
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

yourls_add_filter( 'yourls_link', 'rewrite_shortlink_base_url' );
function rewrite_shortlink_base_url ($link) {

    $shortlink_base_url = yourls_get_option( 'ck_shortlink_base_url', '');

    if ( empty($shortlink_base_url) )
        return $link;

    $rewritten = str_replace(
        yourls_get_yourls_site(),
        trim($shortlink_base_url, '/'),
        $link,
    );

    return $rewritten;

}

// Add a configuration page to the admin panel

yourls_add_action( 'plugins_loaded', 'rewrite_shortlink_base_url_init' );

function rewrite_shortlink_base_url_init() {
    yourls_register_plugin_page(
        'ck_rewrite_shortlink_base_url',
        'BaseURL Rewrite',
        'ck_rewrite_shortlink_base_url_display_page',
    );
}

// The function that will draw the admin page
function ck_rewrite_shortlink_base_url_display_page () {

    // Check if form was submitted
    if( isset( $_POST['shortlink_base_url'] ) ) {
        // If so, verify nonce
        yourls_verify_nonce( 'shortlink_base_url_settings' );
        // and process submission if nonce is valid
        ck_rewrite_shortlink_base_url_settings_update();
    }

    $shortlink_base_url = yourls_get_option('ck_shortlink_base_url', '');
    $nonce = yourls_create_nonce('shortlink_base_url_settings');

    echo <<<HTML
        <main>
            <h2>Random ShortURLs Settings</h2>
            <form method="post">
            <input type="hidden" name="nonce" value="$nonce" />
            <p>
                <label>Shortlink Base URL:</label>
                <input type="text" name="shortlink_base_url" value="$shortlink_base_url" />
            </p>
            <p><input type="submit" value="Save" class="button" /></p>
            </form>
        </main>
HTML;

}


function ck_rewrite_shortlink_base_url_settings_update() {
    $shortlink_base_url = $_POST['shortlink_base_url'];
    yourls_update_option( 'ck_shortlink_base_url', $shortlink_base_url );
}

