<?php

if (strpos(strtolower($_SERVER['PHP_SELF']), 'install_defaults.php') !== false) {
    die('This file can not be used on its own!');
}

function plugin_initconfig_faq()
{
    global $_CONF;

    if (!class_exists('config')) {
        require_once $_CONF['path_system'] . 'classes/config.class.php';
    }

    $c = config::get_instance();

    if ($c->group_exists('faq')) {
        return true;
    }

    $c->add('sg_main', null, 'subgroup', 0, 0, null, 0, true, 'faq', 0);
    $c->add('tab_general', null, 'tab', 0, 0, null, 0, true, 'faq', 0);
    $c->add('fs_general', null, 'fieldset', 0, 0, null, 0, true, 'faq', 0);

    $c->add('hidenewfaq', 1, 'select', 0, 0, 0, 10, true, 'faq', 0);
    $c->add('hidefaqmenu', 0, 'select', 0, 0, 0, 20, true, 'faq', 0);
    $c->add('newfaqinterval', 1209600, 'text', 0, 0, null, 30, true, 'faq', 0);
    $c->add('no_hit_rights', 'faq.admin,faq.edit', 'text', 0, 0, null, 40, true, 'faq', 0);

    $c->add('tab_context', null, 'tab', 0, 0, null, 0, true, 'faq', 1);
    $c->add('fs_context', null, 'fieldset', 0, 0, null, 0, true, 'faq', 1);
    $c->add('contextual_enabled', 1, 'select', 0, 0, 0, 10, true, 'faq', 1);
    $c->add('contextual_default_placement', 'after', 'select', 0, 0, 1, 20, true, 'faq', 1);
    $c->add('structured_data', 1, 'select', 0, 0, 0, 30, true, 'faq', 1);
    $c->add('coverage_limit', 100, 'text', 0, 0, null, 40, true, 'faq', 1);

    $c->add('tab_permissions', null, 'tab', 0, 0, null, 0, true, 'faq', 2);
    $c->add('fs_permissions', null, 'fieldset', 0, 0, null, 0, true, 'faq', 2);
    $c->add('default_permissions', array(3, 3, 2, 2), '@select', 0, 0, 12, 10, true, 'faq', 2);

    return true;
}
