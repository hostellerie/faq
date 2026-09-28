<?php

if (strpos(strtolower($_SERVER['PHP_SELF']), 'config.php') !== false) {
    die('This file can not be used on its own.');
}

$_FAQ_CONF = array(
    'faqman_autolink_patch' => false,
    'newfaqinterval' => 60 * 60 * 24 * 14,
    'hidenewfaq' => true,
    'hidefaqmenu' => false,
    'no_hit_rights' => 'faq.admin,faq.edit',
    'default_permissions' => array(3, 3, 2, 2),
    'cat_sort_order' => 'hits DESC',
    'faq_sort_order' => 'hits DESC',
    'contextual_enabled' => true,
    'contextual_default_placement' => 'automatic',
    'structured_data' => true,
    'coverage_limit' => 100
);

if (isset($_PI_CONF) && isset($_PI_CONF['faq']) && is_array($_PI_CONF['faq'])) {
    foreach ($_PI_CONF['faq'] as $key => $value) {
        $_FAQ_CONF[$key] = $value;
    }
}
