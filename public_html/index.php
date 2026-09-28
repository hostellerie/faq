<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Geeklog 1.4                                                               |
// +---------------------------------------------------------------------------+
// | public_html/faq/index.php                                                 |
// |                                                                           |
// | This is he FAQ viewing page.                                              |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2006 by the following authors:                              |
// |                                                                           |
// | Authors: Emil Gustafsson   - emil AT cellfish DOT SE                      |
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

require_once ('../lib-common.php');

// MAIN
//
// Parameters are handled in the following order (first matching):
// faq will display that FAQ entry.
// cat will display that FAQ category.
// else list the categories.

$display = '';

$faq_id = COM_applyFilter(UTIL_getParamStr('faq', ''));
$cat_id = COM_applyFilter(UTIL_getParamStr('cat', ''));

$pagetitle = '';
if ( ! empty($faq_id)) {
    $pagetitle = $LANG_FAQ_COMMON['FAQ'] . ' Entry';
    
    $e = DB_query("SELECT faq.id AS id, faq.title AS title, faq.description AS description, cat.id AS cat_id, cat.title AS cat
                     FROM {$_TABLES['faq']} AS faq, 
                          {$_TABLES['faq_category']} AS cat 
                    WHERE faq.category = cat.id
                      AND faq.id = '{$faq_id}'" 
                . COM_getPermSQL( 'AND', 0, 3, 'faq' ) 
                . COM_getPermSQL( 'AND', 0, 3, 'cat' ));
    
    $r = DB_query("SELECT faq.id AS id, faq.title AS title, faq.description AS description, cat.id AS cat_id, cat.title AS cat, faq.hits AS hits, UNIX_TIMESTAMP(faq.date) AS unixdate
                     FROM {$_TABLES['faq']} AS faq, 
                          {$_TABLES['faq_category']} AS cat 
                    WHERE faq.category = cat.id
                      AND faq.id = '{$faq_id}'" 
                . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
                . COM_getPermSQL( 'AND', 0, 2, 'cat' ));
                
    if (1 != DB_numRows($r)) {
        COM_accessLog("User UID {$_USER['username']} tried to view FAQ Entry: {$faq_id}");
        COM_redirect($_CONF['site_url'] . '/index.php?msg=2&plugin=faq');
    }
    $A = DB_fetchArray($r);
    
    $tpl = COM_newTemplate(CTL_plugin_templatePath('faq'));
    $tpl->set_file( array('faq' => 'faq.thtml'));
    
    $tpl->set_var('block_start', COM_startBlock($pagetitle));
    
	$tpl->set_var( 'faq_lang_cats' , $LANG_FAQ_COMMON['Categories'] );
	$tpl->set_var( 'site_url', $_CONF['site_url']  );
	$tpl->set_var( 'faq_cats_url', $_CONF['site_url'] . '/faq/index.php' );
	$tpl->set_var( 'faq_title', $A['title'] );
	$tpl->set_var( 'faq_desc', PLG_replaceTags(faq_normalizeContentHtml($A['description'])) );
	$tpl->set_var( 'faq_cat_url', $_CONF['site_url'] . '/faq/index.php?cat=' . $A['cat_id'] );
	$tpl->set_var( 'faq_cat_title', $A['cat'] );
	$tpl->set_var( 'faq_lang_hits', $LANG_FAQ_COMMON['Hits'] );
	$tpl->set_var( 'faq_hits', COM_numberFormat($A['hits']) );
	$tpl->set_var( 'faq_lang_updated', $LANG_FAQ_COMMON['Updated'] );
        $thetime = COM_getUserDateTimeFormat($A['unixdate']);
	$tpl->set_var( 'faq_updated', $thetime[0] );
	$tpl->set_var( 'faq_edit', '' );
	if (1 == DB_numRows($e) && SEC_hasRights ('faq.edit'))
	    $tpl->set_var( 'faq_edit', ' | <a href="' . $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=faq&amp;action=edit&amp;id=' . $faq_id . '">' . $LANG_FAQ_ADMIN['Edit'] . '</a>' );
	    
    $tpl->set_var('block_end', COM_endBlock());    
        
	$tpl->parse('output', 'faq');
    $display .= $tpl->finish($tpl->get_var('output'));
    $display .= faq_structuredQuestions(array(array('title' => $A['title'], 'description' => $A['description'])), true);
    
    if ( ! SEC_hasRights($_FAQ_CONF['no_hit_rights'], 'OR'))
        DB_query("UPDATE {$_TABLES['faq']} SET hits = hits + 1 WHERE id = '{$faq_id}'");
}
    else if ( ! empty($cat_id)) {
        $r = DB_query("SELECT * 
                     FROM {$_TABLES['faq_category']} 
                    WHERE id = '{$cat_id}'" 
                . COM_getPermSQL( 'AND' ) );
    if (1 != DB_numRows($r)) {
        COM_accessLog("User UID {$_USER['username']} tried to view FAQ category: {$cat_id}", 1);
        echo COM_refresh ($_CONF['site_url'] . '/index.php?msg=1&plugin=faq');
        exit;
    }
    $A = DB_fetchArray($r);

    $pagetitle = $LANG_FAQ_COMMON['FAQ'] . ' Category: ' . $A['title'];
    
    require_once( $_CONF['path_system'] . 'lib-admin.php' );
    $header_arr = array(
                        array('text' => $LANG_FAQ_COMMON['Question'], 'field' => 'title', 'sort' => false),
                        array('text' => $LANG_FAQ_COMMON['Updated'], 'field' => 'unixdate', 'sort' => false),
                        array('text' => $LANG_FAQ_COMMON['Hits'], 'field' => 'hits', 'sort' => false)
                       );

    $menu_arr = array ( array('url'=>$_CONF['site_url'] . '/faq/index.php', 'text'=>$LANG_FAQ_COMMON['Categories']));
    if (SEC_hasRights ('faq.admin') &&
        3 <= SEC_hasAccess($A['owner_id'], $A['group_id'], $A['perm_owner'], $A['perm_group'], $A['perm_members'], $A['perm_anon']))
        $menu_arr[] = array('url'=>$_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=cat&amp;action=edit&amp;id=' . $cat_id, 'text'=>$LANG_FAQ_ADMIN['Edit']);

    $text_arr = array('has_menu' =>  true,
    //                  'title' => $A['title'], 
                      'instructions' => PLG_replaceTags(faq_normalizeContentHtml($A['description'])),
                      'icon' => $_CONF['site_url'] . '/faq/images/category-questions.png');

    $data_arr = array();

    $r = DB_query("SELECT faq.id AS id, faq.title AS title, faq.description AS description, 
                          faq.hits AS hits, faq.date AS date, UNIX_TIMESTAMP(faq.date) AS unixdate
                     FROM {$_TABLES['faq']} AS faq, 
                          {$_TABLES['faq_category']} AS cat 
                    WHERE faq.category = cat.id
                      AND cat.id = '{$cat_id}'" 
                . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
                . COM_getPermSQL( 'AND', 0, 2, 'cat' )
                . " ORDER BY {$_FAQ_CONF['faq_sort_order']}");
    $numRows = DB_numRows ($r);
    for ($i = 0; $i < $numRows; $i++) {
        $A = DB_fetchArray($r);
        
        $data_arr[] = $A;
    }

    $display .= COM_startBlock($pagetitle, '',  COM_getBlockTemplate('_admin_block', 'header'));    
    $display .= ADMIN_createMenu($menu_arr, $text_arr['instructions'], $text_arr['icon']);
    $display .= ADMIN_simpleList ("faq_getListField_faq", $header_arr, $text_arr, $data_arr);
    $display .= COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));    
}
else {
    $pagetitle = $LANG_FAQ_COMMON['FAQ'];
    
    $tpl = COM_newTemplate(CTL_plugin_templatePath('faq'));
    $tpl->set_file( array('list' => 'cat_list.thtml'));
    $tpl->set_block('list', 'cat_list_item');

                          
    $tpl->set_var('block_start', COM_startBlock($LANG_FAQ_COMMON['FAQ_cat_header']));
	$tpl->set_var( 'faq_cat_header' , $LANG_FAQ_COMMON['FAQ_cat_header'] );
    
	$tpl->set_var( 'site_url', $_CONF['site_url'] );
    
    $r = DB_query("SELECT cat.id AS id, cat.title AS title, cat.description AS description, 
                          MAX(faq.date) AS date, SUM(faq.hits) AS hits, COUNT(*) AS cnt
                     FROM {$_TABLES['faq']} AS faq, 
                          {$_TABLES['faq_category']} AS cat 
                    WHERE faq.category = cat.id" 
                . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
                . COM_getPermSQL( 'AND', 0, 2, 'cat' )
                . " GROUP BY cat.id, cat.title, cat.description ORDER BY {$_FAQ_CONF['cat_sort_order']}");
    $numRows = DB_numRows ($r);
    for ($i = 0; $i < $numRows; $i++) {
        $A = DB_fetchArray($r);
        $tpl->set_var('faq_cat_url', $_CONF['site_url'] . '/faq/index.php?cat=' . $A['id']);
        $tpl->set_var('faq_cat_title', $A['title']);
        $tpl->set_var('faq_cat_desc', PLG_replaceTags(faq_normalizeContentHtml($A['description'])));
        $tpl->set_var('faq_cat_faqs', $A['cnt']);
        $tpl->set_var('faq_cat_hits', $A['hits']);
        
        $tpl->parse('faq_cat_list_item', 'cat_list_item', true);
    }
    
    $tpl->set_var('block_end', COM_endBlock());
    
    $tpl->parse('output', 'list');
    $display .= $tpl->finish($tpl->get_var('output'));
}

$display = COM_createHTMLDocument($display, array('pagetitle' => $pagetitle));

COM_output($display);

?>
