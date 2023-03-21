<?php
/*
Plugin Name: BaseURL Rewrite
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


//
// This filter function actually rewrites short URLs by replacing the original
// base URL (as specified by ``YOURLS_SITE`` in `config.php`) with the base URL
// given in the configuration option. If the base URL configuration option is
// empty, the original short URL is returned.
//
// EXAMPLE:
//
// Lets assume the following:
//
//     YOURLS_SITE: "https://link.acme.com" (a constant defined in `config.php`)
//     ck_base_url: "https://acme.com/r" (a configuration option)
//
// Then, YOURLS will create the (example) short link
//
//     https://acme.com/r/Ac3fG
//
// instead of
//
//     https://link.acme.com/Ac3fG
//
// It is assumed that the system behind ``https://acme.com/r`` performs (e.g.,
// wildcard) redirects from ``https://acme.com/r/(.*)`` to
// ``https://link.acme.com/$1`` which are then further redirected to the target
// URL.
//
yourls_add_filter( 'yourls_link', 'ck_base_url_rewrite' );
function ck_base_url_rewrite ($link) {
    $base_url = yourls_get_option( 'ck_base_url', '');

    if ( empty($base_url) )
        return $link;

    $rewritten = str_replace(
        yourls_get_yourls_site(),
        trim($base_url, '/'),
        $link,
    );
    return $rewritten;
}


// Add a configuration page to the admin panel
yourls_add_action( 'plugins_loaded', 'ck_base_url_rewrite_init' );
function ck_base_url_rewrite_init() {
    yourls_register_plugin_page(
        'ck_base_url_rewrite',
        'BaseURL Rewrite',
        'ck_base_url_rewrite_display_page',
    );
}


// The function that will draw the admin page
function ck_base_url_rewrite_display_page () {
    // Check if form was submitted
    if( isset( $_POST['base_url'] ) ) {
        // If so, verify nonce
        yourls_verify_nonce( 'base_url_settings' );
        // and process submission if nonce is valid
        ck_base_url_rewrite_settings_update();
    }

    $base_url = yourls_get_option('ck_base_url', '');
    $nonce = yourls_create_nonce('base_url_settings');

    echo <<<HTML
        <main>
            <h2>BaseURL Rewrite Settings</h2>
            <form method="post">
            <input type="hidden" name="nonce" value="$nonce" />
            <p>
                <label>Shortlink Base URL:</label>
                <input type="text" name="base_url" value="$base_url" />
            </p>
            <p><input type="submit" value="Save" class="button" /></p>
            </form>
        </main>
HTML;
}


// The function that updates the configuration option
function ck_base_url_rewrite_settings_update() {
    $base_url = $_POST['base_url'];
    yourls_update_option( 'ck_base_url', $base_url );
}

