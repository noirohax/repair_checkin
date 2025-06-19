<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Repair Module 
Description: Repair Module For Perfex CRM
Version: 1.0.0
Requires at least: 2.3.*
*/


define('Repair_checkin', 'repair_checkin');

hooks()->add_action('admin_init', 'repair_module_init_menu_items');
hooks()->add_action('customers_navigation_end', 'customers_navigation_repair_module');

/**
 * Register activation module hook
 */
register_activation_hook(Repair_checkin, 'repair_module_activation_hook');

function repair_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(Repair_checkin, [Repair_checkin]);

/**
 * Init backup module menu items in setup in admin_init hook
 * @return null
 */
function repair_module_init_menu_items()
{
    /**
     * If the logged in user is administrator, add custom menu in Setup
     */
    if (is_admin()) {
        $CI = &get_instance();

        $CI->app_menu->add_sidebar_menu_item('template_menu', [
            'name'     => 'Repairs',
            'href'     => admin_url('repair_checkin'),
            'icon'     => 'fa fa-computer',
            'position' => 5,
        ]);
    }
}

function customers_navigation_repair_module()
{
    echo '<li><a href="' . admin_url('repair_checkin/repair_module_client') . '">' . _l('template_menu') . '</a></li>';
}
