<?php
/*
Plugin Name: Environment Notification
Plugin URI: https://github.com/dehayol/wp_env_plugin
Description: A simple plugin to help signify a wordpress environement is development and not production. Hacked from Alex Phelps' Development Environment Notification
Version: 0.1
Author: Olivier Dehaybe
Author Email: olivier@dehaybe.be
*/

//if constant is defined, add our style and add our button with wp hooks

$environment = ucfirst(getenv ( 'WORDPRESS_ENV' ));

if ($environment!='Production' && $environment!=''){
    add_action( 'init', 'dev_admin_bar_overrides' );
    add_action('admin_bar_menu', 'add_items');
    add_filter( 'login_message', 'dev_notify_login_message' );
}

// add css style for the button

function dev_admin_bar_overrides() {
    if ( is_admin_bar_showing() ) {
        add_action('wp_head', 'add_dev_btn_css_style');
        add_action('admin_head', 'add_dev_btn_css_style');
        //print styles
        function add_dev_btn_css_style() {
            $devbtnstyle = "<style>#wpadminbar .dev-mode-notification {background-color:rgba(255, 0, 0, 0.99) !important;}</style>";
            echo $devbtnstyle;
        }
    }
}

//create our button

function add_items($admin_bar) {
    global $environment;
    $admin_bar->add_menu( array(
        'id'    => 'dev-mode',
        'parent' => 'top-secondary',
        'title' => $environment,
        'href'  => '#',
        'meta'  => array(
            'title' => __($environment),
            'class' => 'dev-mode-notification'
        ),
    ) );
}

//add notification on login screen
function dev_notify_login_message() {
    global $environment;
    $message = "<p class='message' id='login_error'>$environment</p>";
    echo $message;

}