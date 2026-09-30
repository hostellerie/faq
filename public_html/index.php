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
$documentOptions = array();

$faq_id = COM_applyFilter(UTIL_getParamStr('faq', ''));
$cat_id = COM_applyFilter(UTIL_getParamStr('cat', ''));

$pagetitle = '';
if ( ! empty($faq_id)) {
    $pagetitle = '';
    
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
    $pagetitle = $A['title'];

    $answerParts = faq_splitAnswerNavigation($A['description']);
    $faqAnswerHtml = faq_renderContent($answerParts['answer']);
    $faqRelatedHtml = '';
    if ($answerParts['related'] !== '') {
        $relatedLabel = $LANG_FAQ_COMMON['related_questions'];
        $faqRelatedHtml = '<aside class="faq-related" aria-label="'
                        . htmlspecialchars($relatedLabel, ENT_QUOTES, 'UTF-8') . '">'
                        . '<h2>' . htmlspecialchars($relatedLabel, ENT_QUOTES, 'UTF-8') . '</h2>'
                        . '<div class="faq-related-links">' . PLG_replaceTags($answerParts['related']) . '</div>'
                        . '</aside>';
    }

    $canonicalUrl = $_CONF['site_url'] . '/faq/index.php?faq=' . rawurlencode($faq_id);
    $metaDescription = faq_plainTextExcerpt($A['description'], 155);
    $headerCode = '<link rel="canonical" href="'
                . htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
    if ($metaDescription !== '') {
        $headerCode .= PLG_getMetaTags(
            'faq',
            $faq_id,
            array(
                array(
                    'name' => 'description',
                    'content' => $metaDescription
                )
            )
        );
    }
    $documentOptions = array(
        'pagetitle' => $pagetitle,
        'headercode' => $headerCode
    );

    $tpl = COM_newTemplate(CTL_plugin_templatePath('faq'));
    $tpl->set_file( array('faq' => 'faq.thtml'));
    
    $tpl->set_var('block_start', COM_startBlock($LANG_FAQ_COMMON['FAQ']));
    
    $tpl->set_var('faq_lang_cats', $LANG_FAQ_COMMON['Categories']);
    $tpl->set_var('faq_aria_breadcrumb', $LANG_FAQ_COMMON['breadcrumb_aria']);
    $tpl->set_var('faq_aria_breadcrumb', $LANG_FAQ_COMMON['breadcrumb_aria']);
    $tpl->set_var('faq_aria_actions', $LANG_FAQ_COMMON['actions_aria']);
    $tpl->set_var('faq_back_to', $LANG_FAQ_COMMON['back_to']);
	$tpl->set_var( 'site_url', $_CONF['site_url']  );
	$tpl->set_var( 'faq_cats_url', $_CONF['site_url'] . '/faq/index.php' );
	$tpl->set_var( 'faq_title', $A['title'] );
	$tpl->set_var( 'faq_desc', $faqAnswerHtml );
    $tpl->set_var('faq_related', $faqRelatedHtml);
	$tpl->set_var( 'faq_cat_url', $_CONF['site_url'] . '/faq/index.php?cat=' . $A['cat_id'] );
	$tpl->set_var( 'faq_cat_title', $A['cat'] );
	$tpl->set_var( 'faq_lang_hits', $LANG_FAQ_COMMON['Hits'] );
	$tpl->set_var( 'faq_hits', COM_numberFormat($A['hits']) );
	$tpl->set_var( 'faq_lang_updated', $LANG_FAQ_COMMON['Updated'] );
        $thetime = COM_getUserDateTimeFormat($A['unixdate']);
	$tpl->set_var( 'faq_updated', $thetime[0] );
	$tpl->set_var( 'faq_edit', '' );
    if (1 == DB_numRows($e) && SEC_hasRights('faq.edit')) {
        $tpl->set_var(
            'faq_edit',
            '<a href="' . $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=faq&amp;action=edit&amp;id='
            . rawurlencode($faq_id) . '">' . $LANG_FAQ_ADMIN['Edit'] . '</a>'
        );
    }
	    
    $tpl->set_var('block_end', COM_endBlock());    
        
	$tpl->parse('output', 'faq');
    $display .= $tpl->finish($tpl->get_var('output'));
    $display .= faq_structuredQuestions(array(array('title' => $A['title'], 'description' => $answerParts['answer'])), true);
    
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

    $pagetitle = sprintf($LANG_FAQ_COMMON['category_page_title'], $A['title']);

    $cat = $A;

    $tpl = COM_newTemplate(CTL_plugin_templatePath('faq'));
    $tpl->set_file(array('category' => 'category.thtml'));

    $tpl->set_var('block_start', COM_startBlock($LANG_FAQ_COMMON['FAQ']));
    $tpl->set_var('block_end', COM_endBlock());
    $tpl->set_var('faq_lang_cats', $LANG_FAQ_COMMON['Categories']);
    $tpl->set_var('faq_cats_url', $_CONF['site_url'] . '/faq/index.php');
    $tpl->set_var('faq_cat_title', $cat['title']);
    $tpl->set_var('faq_cat_desc', faq_renderContent($cat['description']));
    $tpl->set_var('faq_updated_label', $LANG_FAQ_COMMON['Updated']);
    $tpl->set_var('faq_hits_label', $LANG_FAQ_COMMON['Hits']);
    $tpl->set_var('faq_cat_edit', '');

    if (SEC_hasRights('faq.admin')
        && 3 <= SEC_hasAccess(
            $cat['owner_id'],
            $cat['group_id'],
            $cat['perm_owner'],
            $cat['perm_group'],
            $cat['perm_members'],
            $cat['perm_anon']
        )) {
        $tpl->set_var(
            'faq_cat_edit',
            '<div class="faq-actions"><a href="' . $_CONF['site_admin_url']
            . '/plugins/faq/index.php?mode=cat&amp;action=edit&amp;id=' . rawurlencode($cat_id)
            . '">' . $LANG_FAQ_ADMIN['Edit'] . '</a></div>'
        );
    }

    $r = DB_query("SELECT faq.id AS id, faq.title AS title,
                          faq.hits AS hits, UNIX_TIMESTAMP(faq.date) AS unixdate
                     FROM {$_TABLES['faq']} AS faq,
                          {$_TABLES['faq_category']} AS cat
                    WHERE faq.category = cat.id
                      AND cat.id = '{$cat_id}'"
                . COM_getPermSQL('AND', 0, 2, 'faq')
                . COM_getPermSQL('AND', 0, 2, 'cat')
                . " ORDER BY {$_FAQ_CONF['faq_sort_order']}");

    $questionList = '';
    while ($faq = DB_fetchArray($r)) {
        $thetime = COM_getUserDateTimeFormat($faq['unixdate']);
        $faqUrl = $_CONF['site_url'] . '/faq/index.php?faq=' . rawurlencode($faq['id']);

        $questionList .= '<li class="faq-question-card">'
                      . '<h2><a href="' . htmlspecialchars($faqUrl, ENT_QUOTES, 'UTF-8') . '">'
                      . htmlspecialchars($faq['title'], ENT_QUOTES, 'UTF-8') . '</a></h2>'
                      . '<div class="faq-card-meta">'
                      . '<span>' . htmlspecialchars($LANG_FAQ_COMMON['Updated'], ENT_QUOTES, 'UTF-8') . ': '
                      . htmlspecialchars($thetime[0], ENT_QUOTES, 'UTF-8') . '</span>'
                      . '<span>' . htmlspecialchars($LANG_FAQ_COMMON['Hits'], ENT_QUOTES, 'UTF-8') . ': '
                      . COM_numberFormat($faq['hits']) . '</span>'
                      . '</div></li>';
    }
    $tpl->set_var('faq_question_list', $questionList);

    $tpl->parse('output', 'category');
    $display .= $tpl->finish($tpl->get_var('output'));
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
        $tpl->set_var('faq_cat_desc', faq_renderContent($A['description']));
        $tpl->set_var('faq_cat_faqs', $A['cnt']);
        $tpl->set_var('faq_cat_hits', COM_numberFormat($A['hits']));
        $tpl->set_var('faq_cat_faqs_label', $LANG_FAQ_COMMON['FAQs']);
        $tpl->set_var('faq_hits_label', $LANG_FAQ_COMMON['Hits']);
        
        $tpl->parse('faq_cat_list_item', 'cat_list_item', true);
    }
    
    $tpl->set_var('block_end', COM_endBlock());
    
    $tpl->parse('output', 'list');
    $display .= $tpl->finish($tpl->get_var('output'));
}

if (empty($documentOptions)) {
    $documentOptions = array('pagetitle' => $pagetitle);
}
$display = COM_createHTMLDocument($display, $documentOptions);

COM_output($display);

?>
