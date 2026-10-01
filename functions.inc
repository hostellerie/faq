<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | FAQ Plugin 1.0                                                            |
// +---------------------------------------------------------------------------+
// | functions.inc                                                             |
// |                                                                           |
// | This file does two things: 1) it implements the necessary Geeklog Plugin  |
// | API method and 2) implements all the common code needed by the FAQ        |
// | plugin' PHP files.                                                        |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2006 by the following authors:                              |
// |                                                                           |
// | Authors: Emil Gustafsson  - emil AT cellfish DOT se                       |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+
//
// $Id$

global $_DB_table_prefix;

if (strpos ($_SERVER['PHP_SELF'], 'functions.inc') !== false) {
    die ('This file can not be used on its own.');
}

$langfile = $_CONF['path'] . 'plugins/faq/language/'
          . $_CONF['language'] . '.php';

if (file_exists ($langfile)) {
    require_once ($langfile);
} else {
    require_once ($_CONF['path'] . 'plugins/faq/language/english.php');
}

require_once ($_CONF['path'] . 'plugins/faq/config.php');

require_once ($_CONF['path'] . 'plugins/faq/api.inc');
require_once ($_CONF['path'] . 'plugins/faq/util.inc');

// database table names - don't change
$_TABLES['faq']                    = $_DB_table_prefix . 'faq';
$_TABLES['faq_category']           = $_DB_table_prefix . 'faq_category';
$_TABLES['faq_relations']          = $_DB_table_prefix . 'faq_relations';
$_TABLES['faq_category_relations'] = $_DB_table_prefix . 'faq_category_relations';

require_once ($_CONF['path'] . 'plugins/faq/relations.inc');
require_once ($_CONF['path'] . 'plugins/faq/modern.inc');
function faq_faqSortOrderColumnExists()
{
    global $_TABLES;

    if (empty($_TABLES['faq'])) {
        return false;
    }

    $table = str_replace('`', '``', (string) $_TABLES['faq']);
    $result = DB_query("SHOW COLUMNS FROM `{$table}` LIKE 'sort_order'");
    return $result !== false && DB_numRows($result) > 0;
}

function faq_ensureFaqSortOrderColumn()
{
    global $_TABLES;

    if (empty($_TABLES['faq'])) {
        return false;
    }

    if (!faq_faqSortOrderColumnExists()) {
        DB_query("ALTER TABLE {$_TABLES['faq']} ADD sort_order int(11) NOT NULL DEFAULT 10 AFTER category");
    }

    if (faq_faqSortOrderColumnExists()) {
        DB_query("ALTER TABLE {$_TABLES['faq']} MODIFY sort_order int(11) NOT NULL DEFAULT 10");
        DB_query("UPDATE {$_TABLES['faq']} SET sort_order = 10 WHERE sort_order = 0");
        return true;
    }

    return false;
}

function faq_faqOrderBySql($fallback)
{
    $fallback = trim((string) $fallback);
    if ($fallback === '') {
        $fallback = 'title ASC';
    }

    if (faq_faqSortOrderColumnExists()) {
        return 'faq.sort_order ASC, faq.' . $fallback;
    }

    return 'faq.' . $fallback;
}


function phpblock_faq_random()
{
    global $_TABLES, $_CONF, $LANG_FAQ_COMMON;
    
    $retval = '';
    
    $r = DB_query("SELECT faq.id AS id, faq.title AS title
                     FROM {$_TABLES['faq']} AS faq, 
                          {$_TABLES['faq_category']} AS cat 
                    WHERE faq.category = cat.id" 
                . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
                . COM_getPermSQL( 'AND', 0, 2, 'cat' ));
                
    $n = DB_numRows($r);
    if ($n < 1) {
        return '';
    }
    $f = rand(0, $n - 1);
    
    $retval .= DB_result($r, $f, 'title');
    $retval .= '<p align="right"><a href="' . $_CONF['site_url'];
    $retval .= '/faq/index.php?faq=' . DB_result($r, $f, 'id');
    $retval .= '">' . $LANG_FAQ_COMMON['Answer'];
    $retval .= '</a></p>';

    return $retval;
}

function faq_accessHelper($access)
{
  switch($access) {
  case 0:
    return '-';
  case 2:
    return 'R';
  case 3:
    return 'W';
  default:
    return '?';
  }
}

function faq_getListField_common($mode, $fieldname, $fieldvalue, $A, $icon_arr)
{
    global $_CONF,$_TABLES;

    switch($fieldname) {
        case "edit":
            $retval = "<a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php?mode={$mode}&amp;action=edit&amp;id={$A['id']}\">{$icon_arr['edit']}</a>";
            break;
        case 'title':
            $retval = "<a href=\"{$_CONF['site_url']}/faq/index.php?{$mode}={$A['id']}\">$fieldvalue</a>";
            break;
        case 'unixdate':
				if (is_callable('COM_strftime')) {
					$retval = COM_strftime($_CONF['daytime'], $A['unixdate']);
				} else {
					$retval = strftime($_CONF['daytime'], $A['unixdate']);
				}		
                break;
        case 'hits':
            $retval = COM_numberFormat ($A['hits']);
            break;
        case 'cnt':
            $retval = COM_numberFormat ($A['cnt']);
            break;
        case 'access':
            $user = DB_getItem($_TABLES['users'],'username', "uid = {$A['owner_id']}");
            $group = DB_getItem($_TABLES['groups'],'grp_name', "grp_id = {$A['group_id']}");
            $retval = sprintf("%s:%s (%s%s%s%s)",$user,$group,
                              faq_accessHelper($A['perm_owner']),
                              faq_accessHelper($A['perm_group']),
                              faq_accessHelper($A['perm_members']),
                              faq_accessHelper($A['perm_anon']));
            break;
        default:
            $retval = $fieldvalue;
            break;
    }
    
    return $retval;
}

function faq_getListField_faq($fieldname, $fieldvalue, $A, $icon_arr)
{
    return faq_getListField_common('faq', $fieldname, $fieldvalue, $A, $icon_arr);
}

function faq_getListField_cat($fieldname, $fieldvalue, $A, $icon_arr)
{
    return faq_getListField_common('cat', $fieldname, $fieldvalue, $A, $icon_arr);
}

function faq_getCategoryList($selected = '')
{
  global $_TABLES, $_FAQ_CONF;

  $retval = '';
  $r = DB_query("SELECT id, title FROM {$_TABLES['faq_category']}" . COM_getPermSQL( 'WHERE', 0, 3));
  $n = DB_numRows($r);
  for ($i=0; $i<$n; $i++) {
    $A = DB_fetchArray($r);
    $retval .= "<option value=\"{$A['id']}\"";
    if ( ! empty($selected) && $A['id'] == $selected)
      $retval .= " selected=\"selected\"";
    $retval .= ">{$A['title']}</option>\n";
  }
  return $retval;
}



/**
 * Detect legacy FAQ content where formatting relies on literal line breaks
 * instead of block-level HTML.
 *
 * Inline HTML is intentionally allowed: old FAQ entries often mixed tags such
 * as <strong> or <a> with plain-text newlines.
 */
function faq_usesLegacyLineBreaks($content)
{
    $content = (string) $content;

    if (!preg_match('/\\R/u', $content)) {
        return false;
    }

    return !preg_match(
        '/<(?:address|article|aside|blockquote|br|div|dl|fieldset|figure|footer|form|h[1-6]|header|hr|li|main|nav|ol|p|pre|section|table|thead|tbody|tfoot|tr|td|th|ul)\\b/i',
        $content
    );
}

/**
 * Prepare stored FAQ content for a visual HTML editor without changing the
 * database representation.
 */
function faq_prepareEditorHtml($content)
{
    $content = (string) $content;

    if (faq_usesLegacyLineBreaks($content)) {
        return nl2br($content);
    }

    return $content;
}

/**
 * Render FAQ content while preserving historical plain-text line breaks.
 *
 * Conversion happens before plugin tags are expanded so line breaks generated
 * inside replacement HTML are not altered.
 */
function faq_renderContent($content)
{
    return PLG_replaceTags(faq_prepareEditorHtml($content));
}


function faq_assetUrl($relative)
{
    global $_CONF, $LANG_FAQ_ADMIN;

    $relative = ltrim((string) $relative, '/');
    $path = $_CONF['path_html'] . 'faq/' . $relative;
    $url = $_CONF['site_url'] . '/faq/' . $relative;

    if (file_exists($path)) {
        $url .= '?v=' . filemtime($path);
    }

    return $url;
}

/**
 * Return a Geeklog Resource-safe path under public_html.
 *
 * $_SCRIPTS->setCSSFile() / setJavaScriptFile() expect a real relative
 * public_html path. Do not append cache-busting query strings here: Geeklog
 * 2.2.2 validates the physical file before registering it.
 */
function faq_assetPath($relative)
{
    return '/faq/' . ltrim((string) $relative, '/');
}

function faq_adminNavigation($active = '')
{
    global $_CONF, $LANG_FAQ_ADMIN;

    if (!SEC_hasRights('faq.admin,faq.edit', 'OR')) {
        return '';
    }

    $items = array();

    $items[] = array(
        'key' => 'faq',
        'label' => $LANG_FAQ_ADMIN['questions'],
        'url' => $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=faq'
    );

    if (SEC_hasRights('faq.admin')) {
        $items[] = array(
            'key' => 'cat',
            'label' => $LANG_FAQ_ADMIN['categories'],
            'url' => $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=cat'
        );
    }

    $items[] = array(
        'key' => 'relations',
        'label' => $LANG_FAQ_ADMIN['associations'],
        'url' => $_CONF['site_admin_url'] . '/plugins/faq/relations.php'
    );

    $items[] = array(
        'key' => 'coverage',
        'label' => $LANG_FAQ_ADMIN['coverage'],
        'url' => $_CONF['site_admin_url'] . '/plugins/faq/coverage.php'
    );

    $html = '<nav class="plugin-admin-nav faq-admin-nav" aria-label="'
          . htmlspecialchars($LANG_FAQ_ADMIN['administration_aria'], ENT_QUOTES, 'UTF-8')
          . '">'
          . '<div class="plugin-admin-nav__primary faq-admin-nav__primary">';

    foreach ($items as $item) {
        $classes = 'plugin-admin-nav__item faq-admin-nav__button';
        $ariaCurrent = '';
        if ($active === $item['key']) {
            $classes .= ' is-active';
            $ariaCurrent = ' aria-current="page"';
        }

        $html .= '<a class="' . $classes . '"' . $ariaCurrent . ' href="'
              . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '">'
              . htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') . '</a>';
    }

    if (SEC_inGroup('Root')) {
        $classes = 'plugin-admin-nav__item faq-admin-nav__button';
        $ariaCurrent = '';
        if ($active === 'config') {
            $classes .= ' is-active';
            $ariaCurrent = ' aria-current="page"';
        }

        $html .= '<form method="post" action="'
              . htmlspecialchars($_CONF['site_admin_url'] . '/configuration.php', ENT_QUOTES, 'UTF-8')
              . '" class="plugin-admin-nav__form faq-admin-config-form">'
              . '<input type="hidden" name="conf_group" value="faq">'
              . '<button type="submit" class="' . $classes . '"' . $ariaCurrent . '>'
              . htmlspecialchars($LANG_FAQ_ADMIN['configuration'], ENT_QUOTES, 'UTF-8')
              . '</button></form>';
    }

    $html .= '</div></nav>';

    return $html;
}


/**
 * Provide native Geeklog Configuration tooltips.
 *
 * Returning text lets configuration.php render the standard "?" tooltip
 * used by Core plugins, rather than relying on ad-hoc help markup.
 *
 * @param string $id Configuration option id.
 * @return string
 */
function plugin_getconfigtooltip_faq($id)
{
    global $LANG_configtooltips;

    if (isset($LANG_configtooltips['faq'])
        && isset($LANG_configtooltips['faq'][$id])) {
        return $LANG_configtooltips['faq'][$id];
    }

    return '';
}


/**
 * Load FAQ public styles independently from the active theme.
 *
 * This is required for contextual FAQ fragments injected into other providers
 * such as Documents, Videos or Maps through PLG_itemDisplay().
 *
 * @return string
 */
function plugin_getheadercode_faq()
{
    global $_CONF;

    $cssFile = rtrim($_CONF['path_html'], '/\\') . DIRECTORY_SEPARATOR . 'faq' . DIRECTORY_SEPARATOR . 'faq.css';
    $version = '1.3.0';

    if (is_file($cssFile)) {
        $version .= '-' . (string) filemtime($cssFile);
    }

    $href = rtrim($_CONF['site_url'], '/') . '/faq/faq.css?v=' . rawurlencode($version);

    return '<link rel="stylesheet" type="text/css" href="'
        . htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
        . '">' . PHP_EOL;
}

?>
