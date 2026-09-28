<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | FAQ Plugin 1.1                                                            |
// +---------------------------------------------------------------------------+
// | index.php                                                                 |
// |                                                                           |
// | Geeklog FAQ administration page.                                          |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2006 by the following authors:                              |
// |                                                                           |
// | Authors: Emil Gustafsson   - emil AT cellfish DOT se                      |
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

require_once ('../../../lib-common.php');
require_once ('../../auth.inc.php');

$display = '';
$pagetitle = '';

$mode = UTIL_getParamStr('mode', '');

$no_access = ! SEC_hasRights ('faq.admin,faq.edit','OR');

if ( ! $no_access) {
    if (SEC_hasRights ('faq.edit') && ! SEC_hasRights ('faq.admin') && $mode == 'cat')
        $no_access = true;
}

if ( $no_access ) {
    $display .= COM_showMessageText($MESSAGE[29], $MESSAGE[30]);
    $display = COM_createHTMLDocument($display, array('pagetitle' => $MESSAGE[30]));
    COM_accessLog("User {$_USER['username']} tried to illegally access the FAQ administration screen.");
    COM_output($display);
    exit;
}

/**
* Shows the FAQ editor
*
* @param  string  $id    ID of FAQ to edit
* @return string HTML for the link editor form
*/
function editfaq ($id = '') 
{
    global $_CONF, $_GROUPS, $_TABLES, $_USER, $_FAQ_CONF,
           $LANG_FAQ_ADMIN, $LANG_ACCESS;

    $retval = '';

    $tpl = COM_newTemplate(CTL_plugin_templatePath('faq', 'admin'));
    $tpl->set_file( array('editor' => 'faqedit.thtml',
                          'hits_edit' => 'faqedithits.thtml',
                          'date_edit' => 'faqeditdate.thtml'));
    $tpl->set_var('site_url', $_CONF['site_url']);
    $tpl->set_var('site_admin_url', $_CONF['site_admin_url']);
    $tpl->set_var('layout_url',$_CONF['layout_url']);
    $tpl->set_var('lang_allowed_html', COM_allowedHTML());

    if ( ! empty($id)) {
        $r = DB_query("SELECT faq.* , UNIX_TIMESTAMP(faq.date) AS unixdate
                         FROM {$_TABLES['faq']} AS faq,
                              {$_TABLES['faq_category']} AS cat 
                        WHERE faq.id ='$id'
                          AND faq.category = cat.id"
                       . COM_getPermSQL( 'AND', 0, 3, 'faq' ) 
                       . COM_getPermSQL( 'AND', 0, 3, 'cat' ));
        if ( 1 == DB_numRows($r)) {
            $A = DB_fetchArray($r);
            $tpl->set_var('faq_old_id',$A['id']);
        }
        else {
            $retval .= COM_startBlock($LANG_ACCESS['accessdenied'], '',
                               COM_getBlockTemplate ('_msg_block', 'header'));
            $retval .= $LANG_FAQ_ADMIN['accessdenied'];
            $retval .= COM_endBlock (COM_getBlockTemplate ('_msg_block', 'footer'));
            COM_accessLog("User {$_USER['username']} tried to illegally submit or edit FAQ entry $id.");
            return $retval;
        }
    } else {
        $tpl->set_var('faq_old_id','');
        $A['id'] = COM_makesid();
        $A['category'] = '';
        $A['unixdate'] = 0;
        $A['description'] = '';
        $A['title']= '';
        $A['hits'] = 0;
        $A['owner_id'] = $_USER['uid'];
        if (isset ($_GROUPS['FAQ Admin'])) {
            $A['group_id'] = $_GROUPS['FAQ Admin'];
        } else {
            $A['group_id'] = SEC_getFeatureGroup ('faq.edit');
        }
        SEC_setDefaultPermissions ($A, $_FAQ_CONF['default_permissions']);
    }
    $retval .= COM_startBlock ($LANG_FAQ_ADMIN['FAQ Editor'], '',
                               COM_getBlockTemplate ('_admin_block', 'header'));

    $tpl->set_var('faq_id', $A['id']);
    if (!empty($id) && SEC_hasRights('faq.admin')) {
        $tpl->set_var ('delete_option', '<input type="submit" value="' . $LANG_FAQ_ADMIN['delete'] . '" name="action" onClick="return delconfirm();">');
    }
    $tpl->set_var('faq_lang_title', $LANG_FAQ_ADMIN['question']);
    $tpl->set_var('faq_title', htmlspecialchars (stripslashes ($A['title'])));
    $tpl->set_var('faq_lang_id', $LANG_FAQ_ADMIN['id']);
    $tpl->set_var('faq_id', $A['id']);
    $tpl->set_var('faq_lang_desc', $LANG_FAQ_ADMIN['answer']);
    $tpl->set_var('faq_lang_category', $LANG_FAQ_ADMIN['category']);
    $tpl->set_var('faq_lang_hits', $LANG_FAQ_ADMIN['hits']);
    $tpl->set_var('faq_lang_date', $LANG_FAQ_ADMIN['changed']);
    $tpl->set_var('faq_lang_reset_date', $LANG_FAQ_ADMIN['reset_date']);
    $tpl->set_var('faq_category_options', faq_getCategoryList ($A['category']));
    $tpl->set_var('faq_category', $A['category']);
    $tpl->set_var('faq_hits', $A['hits']);
    $tpl->set_var('faq_desc', stripslashes($A['description']));
    $thetime = COM_getUserDateTimeFormat($A['unixdate']);
    $tpl->set_var('faq_date', $thetime[0]);
    $tpl->set_var('lang_save', $LANG_FAQ_ADMIN['save']);
    $tpl->set_var('lang_cancel', $LANG_FAQ_ADMIN['cancel']);
    if (SEC_hasRights('faq.admin')) {
        $tpl->parse('faq_hits_edit', 'hits_edit');
        $tpl->parse('faq_date_edit', 'date_edit');
    }
    else {
        $tpl->set_var('faq_hits_edit', $tpl->get_var('faq_hits'));
        $tpl->set_var('faq_date_edit', $LANG_FAQ_ADMIN['date_will_update'] . '<input type="hidden" name="faq_date" value="1"/>');
    }

    // user access info
    $tpl->set_var('lang_accessrights', $LANG_ACCESS['accessrights']);
    $tpl->set_var('lang_owner', $LANG_ACCESS['owner']);
    $tpl->set_var('owner_username', DB_getItem($_TABLES['users'],'username',"uid = {$A['owner_id']}")); 
    $tpl->set_var('faq_ownerid', $A['owner_id']);
    $tpl->set_var('lang_group', $LANG_ACCESS['group']);

    $usergroups = SEC_getUserGroups();
    $groupdd = '<select name="group_id">' . LB;
    for ($i = 0; $i < count($usergroups); $i++) {
        $groupdd .= '<option value="' . $usergroups[key($usergroups)] . '"';
        if ($A['group_id'] == $usergroups[key($usergroups)]) {
           $groupdd .= ' selected="selected"';
        }
        $groupdd.= '>' . key($usergroups) . '</option>' . LB;
        next($usergroups);
    }
    $groupdd .= '</select>' . LB;
    
    $tpl->set_var('group_dropdown', $groupdd);
    $tpl->set_var('lang_permissions', $LANG_ACCESS['permissions']);
    $tpl->set_var('lang_permissionskey', $LANG_ACCESS['permissionskey']);
    $tpl->set_var('permissions_editor', SEC_getPermissionsHTML($A['perm_owner'],$A['perm_group'],$A['perm_members'],$A['perm_anon']));
    
    $tpl->set_var('lang_lockmsg', $LANG_ACCESS['permmsg']);
    $tpl->parse('output', 'editor');
    $retval .= $tpl->finish($tpl->get_var('output'));

    $retval .= COM_endBlock (COM_getBlockTemplate ('_admin_block', 'footer'));

    return $retval;
}

function editcat ($id = '') 
{
    global $_CONF, $_GROUPS, $_TABLES, $_USER, $_FAQ_CONF,
           $LANG_FAQ_ADMIN, $LANG_ACCESS;

    $retval = '';

    $tpl = COM_newTemplate(CTL_plugin_templatePath('faq', 'admin'));
    $tpl->set_file( array('editor' => 'catedit.thtml'));
    $tpl->set_var('site_url', $_CONF['site_url']);
    $tpl->set_var('site_admin_url', $_CONF['site_admin_url']);
    $tpl->set_var('layout_url',$_CONF['layout_url']);
    $tpl->set_var('lang_allowed_html', COM_allowedHTML());

    if ( ! empty($id)) {
        $r = DB_query("SELECT *
                         FROM {$_TABLES['faq_category']} 
                        WHERE id ='$id'"
                       . COM_getPermSQL( 'AND', 0, 3));
        if ( 1 == DB_numRows($r)) {
            $A = DB_fetchArray($r);
            $tpl->set_var('faq_old_id',$A['id']);
        }
        else {
            $retval .= COM_startBlock($LANG_ACCESS['accessdenied'], '',
                               COM_getBlockTemplate ('_msg_block', 'header'));
            $retval .= $LANG_FAQ_ADMIN['accessdenied'];
            $retval .= COM_endBlock (COM_getBlockTemplate ('_msg_block', 'footer'));
            COM_accessLog("User {$_USER['username']} tried to illegally submit or edit FAQ category $id.");
            return $retval;
        }
    } else {
        $tpl->set_var('faq_old_id','');
        $A['id'] = COM_makesid();
        $A['description'] = '';
        $A['title']= '';
        $A['owner_id'] = $_USER['uid'];
        if (isset ($_GROUPS['FAQ Admin'])) {
            $A['group_id'] = $_GROUPS['FAQ Admin'];
        } else {
            $A['group_id'] = SEC_getFeatureGroup ('faq.admin');
        }
        SEC_setDefaultPermissions ($A, $_FAQ_CONF['default_permissions']);
    }
    $retval .= COM_startBlock ($LANG_FAQ_ADMIN['Cat Editor'], '',
                               COM_getBlockTemplate ('_admin_block', 'header'));

    $tpl->set_var('faq_id', $A['id']);
    if (!empty($id) && SEC_hasRights('faq.admin')) {
        $tpl->set_var ('delete_option', 
                       '<input type="submit" value="' . $LANG_FAQ_ADMIN['delete'] . '" name="action" onClick="return delconfirm();"><br>' . $LANG_FAQ_ADMIN['delete_note']);
    }
    $tpl->set_var('faq_lang_title', $LANG_FAQ_ADMIN['title']);
    $tpl->set_var('faq_title', htmlspecialchars (stripslashes ($A['title'])));
    $tpl->set_var('faq_lang_id', $LANG_FAQ_ADMIN['id']);
    $tpl->set_var('faq_id', $A['id']);
    $tpl->set_var('faq_lang_desc', $LANG_FAQ_ADMIN['description']);
    $tpl->set_var('faq_desc', stripslashes($A['description']));
    $tpl->set_var('lang_save', $LANG_FAQ_ADMIN['save']);
    $tpl->set_var('lang_cancel', $LANG_FAQ_ADMIN['cancel']);
    
    // user access info
    $tpl->set_var('lang_accessrights', $LANG_ACCESS['accessrights']);
    $tpl->set_var('lang_owner', $LANG_ACCESS['owner']);
    $tpl->set_var('owner_username', DB_getItem($_TABLES['users'],'username',"uid = {$A['owner_id']}")); 
    $tpl->set_var('faq_ownerid', $A['owner_id']);
    $tpl->set_var('lang_group', $LANG_ACCESS['group']);

    $usergroups = SEC_getUserGroups();
    $groupdd = '<select name="group_id">' . LB;
    for ($i = 0; $i < count($usergroups); $i++) {
        $groupdd .= '<option value="' . $usergroups[key($usergroups)] . '"';
        if ($A['group_id'] == $usergroups[key($usergroups)]) {
           $groupdd .= ' selected="selected"';
        }
        $groupdd.= '>' . key($usergroups) . '</option>' . LB;
        next($usergroups);
    }
    $groupdd .= '</select>' . LB;
    
    $tpl->set_var('group_dropdown', $groupdd);
    $tpl->set_var('lang_permissions', $LANG_ACCESS['permissions']);
    $tpl->set_var('lang_permissionskey', $LANG_ACCESS['permissionskey']);
    $tpl->set_var('permissions_editor', SEC_getPermissionsHTML($A['perm_owner'],$A['perm_group'],$A['perm_members'],$A['perm_anon']));
    
    $tpl->set_var('lang_lockmsg', $LANG_ACCESS['permmsg']);
    $tpl->parse('output', 'editor');
    $retval .= $tpl->finish($tpl->get_var('output'));

    $retval .= COM_endBlock (COM_getBlockTemplate ('_admin_block', 'footer'));

    return $retval;
}

/**
* Saves FAQ to the database
*
* @param    string  $id             ID for FAQ
* @param    string  $old_id         old ID for FAQ
* [...]
* @param    int     $hits           Number of hits for FAQ
* @param    int     $owner_id       ID of owner
* @param    int     $group_id       ID of group FAQ belongs to
* @param    int     $perm_owner     Permissions the owner has
* @param    int     $perm_group     Permissions the group has
* @param    int     $perm_members   Permissions members have
* @param    int     $perm_anon      Permissions anonymous users have
* @return   string                  HTML redirect or error message
* 
*/
function savefaq ($id, $old_id, $category, $description, $title, $hits, $date, $owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon)
{
    global $_CONF, $_GROUPS, $_TABLES, $_USER, $MESSAGE, $LANG_FAQ_ADMIN, $_FAQ_CONF;

    $retval = '';

    // Convert array values to numeric permission values
    if (is_array($perm_owner) OR is_array($perm_group) OR is_array($perm_members) OR is_array($perm_anon)) {
        list($perm_owner,$perm_group,$perm_members,$perm_anon) = SEC_getPermissionValues($perm_owner,$perm_group,$perm_members,$perm_anon);
    }
    
    /*$retval .= "id = $id<br>
                old_id = $old_id<br>
                category = $category<br>
                description = $description<br>
                title = $title<br>
                hits = $hits<br>
                date = $date<br>
                owner_id = $owner_id<br>
                group_id = $group_id<br>
                perm_owner = $perm_owner<br>
                perm_group = $perm_group<br>
                perm_members = $perm_members<br>
                perm_anon = $perm_anon";
    return $retval;*/

    // clean 'em up 
    $description = faq_normalizeContentHtml($description);
    $description = faq_normalizeContentHtml($description);
    $description = addslashes (COM_checkHTML (COM_checkWords ($description)));
    $title = addslashes (COM_checkHTML (COM_checkWords ($title)));
    $id = addslashes ($id);
    
    if (empty ($owner_id)) {
        // this is new link from admin, set default values
        $owner_id = $_USER['uid'];
        if (isset ($_GROUPS['FAQ Admin'])) {
            $group_id = $_GROUPS['FAQ Admin'];
        } else {
            $group_id = SEC_getFeatureGroup ('faq.edit');
        }
        $perm_owner = $_FAQ_CONF['default_permissions'][0];
        $perm_group = $_FAQ_CONF['default_permissions'][1];
        $perm_members = $_FAQ_CONF['default_permissions'][2];
        $perm_anon = $_FAQ_CONF['default_permissions'][3];
    }

    if (empty ($id)) {
        if (empty ($old_id)) {
            $id = COM_makeSid ();
        } else {
            $id = $old_id;
        }
    }

    $access = 0;
    $do_update = false;
    $old_id = addslashes ($old_id);
    if (DB_count ($_TABLES['faq'], 'id', $old_id) > 0) { /* Check old entry access */
        $r = DB_query ("SELECT COUNT(*) AS cnt
                          FROM {$_TABLES['faq']} AS faq, {$_TABLES['faq_category']} AS cat
                         WHERE faq.id = '{$old_id}'
                           AND faq.category = cat.id"
                       . COM_getPermSQL( 'AND', 0, 3, 'faq' ) 
                       . COM_getPermSQL( 'AND', 0, 3, 'cat' ));
        $A = DB_fetchArray ($r);
        if ($A['cnt'] == 1)
            $access = 3;
        $do_update = true;
    }
    else
        $access = 3;
    
    if ($access >= 3) { /* Check new category access */
        $r = DB_query ("SELECT COUNT(*) AS cnt FROM {$_TABLES['faq_category']} WHERE id = '{$category}'"
                       . COM_getPermSQL( 'AND', 0, 3));
        $A = DB_fetchArray ($r);
        if ($A['cnt'] == 1)
            $access = 3;
    }
    
    if ($access >= 3) /* Check new access */
        $access = SEC_hasAccess ($owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon);
    
    if (($access < 3) || !SEC_inGroup ($group_id)) {
        $display .= COM_showMessageText($LANG_FAQ_ADMIN['save_rights_error'], $MESSAGE[30]);
        $display = COM_createHTMLDocument($display, array('pagetitle' => $MESSAGE[30]));
        COM_accessLog("User {$_USER['username']} tried to illegally edit FAQ $id.");
        COM_output($display);
        exit;
    } elseif (!empty($title) && !empty($description) && !empty($category)) {

        if ($do_update) {
            $sql = sprintf("UPDATE {$_TABLES['faq']}
                       SET id = '%s'
                         , title = '%s'
                         , description = '%s'
                         , category = '%s'
                         , owner_id = %d
                         , group_id = %d 
                         , perm_owner = %d
                         , perm_group = %d
                         , perm_members = %d
                         , perm_anon = %d"
                         , $id,$title,$description,$category,$owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon);
            if (SEC_hasRights ('faq.admin')) {
                $sql .= sprintf(", hits = %d", $hits);
                if ($date)
                    $sql .= ', date = NOW()';
            }
            else
                $sql .= ', date = NOW()';
            $sql .= " WHERE id = '{$old_id}'";
        }
        else {
            $sql = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,hits,owner_id,group_id,perm_owner,perm_group,perm_members,perm_anon) ";
            $sql .= sprintf(" VALUES('%s','%s','%s','%s',NOW(),%d,%d,%d,%d,%d,%d,%d)",
                            $id, $category, $title, $description, $hits, $owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon);
        }        
        DB_query($sql);

        if ($do_update && $old_id !== $id && faq_relationTableExists()) {
            DB_query("UPDATE {$_TABLES['faq_relations']}
                         SET faq_id = '" . DB_escapeString($id) . "', modified = NOW()
                       WHERE faq_id = '" . DB_escapeString($old_id) . "'");
        }

        faq_clearLocalCache();
        faq_notifySaved($id, $old_id);

        return COM_refresh ($_CONF['site_admin_url'] . '/plugins/faq/index.php?msg=4&mode=faq&cat=' . $category);
    } else { // missing fields
        $retval .= COM_errorLog($LANG_FAQ_ADMIN['missing_fields_faq'],2);
        if (DB_count ($_TABLES['faq'], 'id', $old_id) > 0) {
            $retval .= editfaq ($old_id);
        } else {
            $retval .= editfaq ('');
        }

        return $retval;
    }
}

function savecat ($id, $old_id, $description, $title, $owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon)
{
    global $_CONF, $_GROUPS, $_TABLES, $_USER, $MESSAGE, $LANG_FAQ_ADMIN, $_FAQ_CONF;

    $retval = '';

    // Convert array values to numeric permission values
    if (is_array($perm_owner) OR is_array($perm_group) OR is_array($perm_members) OR is_array($perm_anon)) {
        list($perm_owner,$perm_group,$perm_members,$perm_anon) = SEC_getPermissionValues($perm_owner,$perm_group,$perm_members,$perm_anon);
    }
    
    /*$retval .= "id = $id<br>
                old_id = $old_id<br>
                description = $description<br>
                title = $title<br>
                owner_id = $owner_id<br>
                group_id = $group_id<br>
                perm_owner = $perm_owner<br>
                perm_group = $perm_group<br>
                perm_members = $perm_members<br>
                perm_anon = $perm_anon";
    return $retval;*/

    // clean 'em up 
    $description = addslashes (COM_checkHTML (COM_checkWords ($description)));
    $title = addslashes (COM_checkHTML (COM_checkWords ($title)));
    $id = addslashes ($id);
    
    if (empty ($owner_id)) {
        // this is new link from admin, set default values
        $owner_id = $_USER['uid'];
        if (isset ($_GROUPS['FAQ Admin'])) {
            $group_id = $_GROUPS['FAQ Admin'];
        } else {
            $group_id = SEC_getFeatureGroup ('faq.admin');
        }
        $perm_owner = $_FAQ_CONF['default_permissions'][0];
        $perm_group = $_FAQ_CONF['default_permissions'][1];
        $perm_members = $_FAQ_CONF['default_permissions'][2];
        $perm_anon = $_FAQ_CONF['default_permissions'][3];
    }

    if (empty ($id)) {
        if (empty ($old_id)) {
            $id = COM_makeSid ();
        } else {
            $id = $old_id;
        }
    }

    $access = 0;
    $do_update = false;
    $old_id = addslashes ($old_id);
    if (DB_count ($_TABLES['faq_category'], 'id', $old_id) > 0) { /* Check old entry access */
        $r = DB_query ("SELECT COUNT(*) AS cnt
                          FROM {$_TABLES['faq_category']} AS cat
                         WHERE id = '{$old_id}'"
                       . COM_getPermSQL( 'AND', 0, 3, 'cat' ));
        $A = DB_fetchArray ($r);
        if ($A['cnt'] == 1)
            $access = 3;
        $do_update = true;
    }
    else
        $access = 3;
    
    if ($access >= 3) /* Check new access */
        $access = SEC_hasAccess ($owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon);
    
    if (($access < 3) || !SEC_inGroup ($group_id)) {
        $display .= COM_showMessageText($LANG_FAQ_ADMIN['save_rights_error'], $MESSAGE[30]);
        $display = COM_createHTMLDocument($display, array('pagetitle' => $MESSAGE[30]));
        COM_accessLog("User {$_USER['username']} tried to illegally edit FAQ Category $id.");
        COM_output($display);
        exit;        
    } elseif (!empty($title) && !empty($description)) {
        if ($do_update) {
            $sql = sprintf("UPDATE {$_TABLES['faq_category']}
                       SET id = '%s'
                         , title = '%s'
                         , description = '%s'
                         , owner_id = %d
                         , group_id = %d 
                         , perm_owner = %d
                         , perm_group = %d
                         , perm_members = %d
                         , perm_anon = %d"
                         , $id, $title, $description, $owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon);
            $sql .= " WHERE id = '{$old_id}'";
        }
        else {
            $sql = "INSERT INTO {$_TABLES['faq_category']}(id,title,description,owner_id,group_id,perm_owner,perm_group,perm_members,perm_anon) ";
            $sql .= sprintf(" VALUES('%s','%s','%s',%d,%d,%d,%d,%d,%d)",
                            $id, $title, $description, $owner_id, $group_id, $perm_owner, $perm_group, $perm_members, $perm_anon);
        }        
        DB_query($sql);
        if ($do_update && $old_id != $id) {
            DB_query("UPDATE {$_TABLES['faq']}
                         SET category = '{$id}'
                       WHERE category = '{$old_id}'");
        }
        
        return COM_refresh ($_CONF['site_admin_url'] . '/plugins/faq/index.php?msg=4&mode=cat');
    } else { // missing fields
        $retval .= COM_errorLog($LANG_FAQ_ADMIN['missing_fields_cat'],2);
        if (DB_count ($_TABLES['faq_category'], 'id', $old_id) > 0) {
            $retval .= editcat ($old_id);
        } else {
            $retval .= editcat ('');
        }

        return $retval;
    }
}
/**
 * List FAQ
 */
function listfaq ($cat = '')
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_FAQ_ADMIN, $LANG_ACCESS, $_FAQ_CONF;
    require_once( $_CONF['path_system'] . 'lib-admin.php' );
    $retval = '';
    
    $tpl = COM_newTemplate(CTL_plugin_templatePath('faq', 'admin'));
    $tpl->set_file( array('list' => 'faqcatfilter.thtml'));
    $tpl->set_var('site_url', $_CONF['site_url']);
    $tpl->set_var('site_admin_url', $_CONF['site_admin_url']);
    $tpl->set_var('layout_url',$_CONF['layout_url']);

    $sql = "SELECT faq.* , UNIX_TIMESTAMP(faq.date) AS unixdate, cat.title AS cat_title
              FROM {$_TABLES['faq']} AS faq,
                   {$_TABLES['faq_category']} AS cat 
             WHERE faq.category = cat.id"
         . COM_getPermSQL( 'AND', 0, 3, 'faq' ) 
         . COM_getPermSQL( 'AND', 0, 3, 'cat' );
    if ( ! empty($cat))
        $sql .= " AND cat.id = '{$cat}'";
    $sql .= " ORDER BY faq.{$_FAQ_CONF['faq_sort_order']}";
     
    $tpl->set_var('faq_lang_category', $LANG_FAQ_ADMIN['category']);
    $tpl->set_var('faq_lang_all', $LANG_FAQ_ADMIN['all_cat']);
    $tpl->set_var('faq_category_options', faq_getCategoryList ($cat));
    $tpl->set_var('faq_lang_show', $LANG_FAQ_ADMIN['show']);
    
    $header_arr = array(      # dislay 'text' and use table field 'field'
                    array('text' => $LANG_ADMIN['edit'], 'field' => 'edit'),
                    array('text' => $LANG_FAQ_ADMIN['id'], 'field' => 'id'),
                    array('text' => $LANG_FAQ_ADMIN['title'], 'field' => 'title'),
                    array('text' => $LANG_FAQ_ADMIN['category'], 'field' => 'cat_title'),
                    array('text' => $LANG_FAQ_ADMIN['hits'], 'field' => 'hits'),
                    array('text' => $LANG_FAQ_ADMIN['access'], 'field' => 'access'));

    $menu_arr = array ( array('url' => $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=faq&action=edit', 'text' => $LANG_ADMIN['create_new']) );
    if (SEC_hasRights ('faq.admin'))
        $menu_arr[] = array('url' => $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=cat', 'text' => $LANG_FAQ_ADMIN['Cat Editor']);

    $tpl->parse('output', 'list');
    $text_arr = array('has_menu' => true,
    //                  'title' => $LANG_FAQ_ADMIN['FAQ Editor'],
                      'instructions' => $tpl->finish($tpl->get_var('output')),
                      'icon' => plugin_geticon_faq());

    $data_arr = array();
    $r = DB_query($sql);
    $n = DB_numRows ($r);
    for ($i=0; $i<$n; $i++) {
        $A = DB_fetchArray($r);
        $data_arr[] = $A;
    }

    $retval .= COM_startBlock($LANG_FAQ_ADMIN['FAQ Editor'], '', COM_getBlockTemplate('_admin_block', 'header'));
    $retval .= ADMIN_createMenu($menu_arr, $text_arr['instructions'], $text_arr['icon']);
    $retval .= ADMIN_simpleList('faq_getListField_faq', $header_arr, $text_arr, $data_arr);
    $retval .= COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));
    
    return $retval;
}

function listcat ()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_FAQ_ADMIN, $LANG_ACCESS, $_FAQ_CONF;
    require_once( $_CONF['path_system'] . 'lib-admin.php' );
    $retval = '';
    
    $sql = "SELECT cat.id AS id, cat.title AS title, cat.description AS description, 
                   cat.owner_id AS owner_id, cat.group_id AS group_id,
                   cat.perm_owner AS perm_owner, cat.perm_group AS perm_group,
                   cat.perm_members AS perm_members, cat.perm_anon AS perm_anon,
                   MAX(faq.date) AS date, SUM(faq.hits) AS hits, CASE WHEN faq.id IS NULL THEN 0 ELSE COUNT(*) END AS cnt
              FROM {$_TABLES['faq_category']} AS cat LEFT OUTER JOIN
                   {$_TABLES['faq']} AS faq ON faq.category = cat.id"
         . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
         . COM_getPermSQL( 'AND', 0, 3, 'cat' );
    $sql .= " GROUP BY cat.id, cat.title, cat.description, cat.owner_id, cat.group_id, cat.perm_owner, cat.perm_group, cat.perm_members, cat.perm_anon";
    $sql .= " ORDER BY faq.{$_FAQ_CONF['cat_sort_order']}";
     
    $header_arr = array(      # dislay 'text' and use table field 'field'
                    array('text' => $LANG_ADMIN['edit'], 'field' => 'edit'),
                    array('text' => $LANG_FAQ_ADMIN['id'], 'field' => 'id'),
                    array('text' => $LANG_FAQ_ADMIN['title'], 'field' => 'title'),
                    array('text' => $LANG_FAQ_ADMIN['FAQ Entries'], 'field' => 'cnt'),
                    array('text' => $LANG_FAQ_ADMIN['hits'], 'field' => 'hits'),
                    array('text' => $LANG_FAQ_ADMIN['access'], 'field' => 'access'));

    $menu_arr = array ( array('url' => $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=cat&action=edit', 'text' => $LANG_ADMIN['create_new']),
                        array('url' => $_CONF['site_admin_url'] . '/plugins/faq/index.php?mode=faq', 'text' => $LANG_FAQ_ADMIN['FAQ Editor'])
                       );

    $text_arr = array('has_menu' =>  true,
    //                  'title' => $LANG_FAQ_ADMIN['Cat Editor'],
                      'instructions' => $LANG_FAQ_ADMIN['cat instructions'],
                      'icon' => plugin_geticon_faq());

    $data_arr = array();
    $r = DB_query($sql);
    $n = DB_numRows ($r);
    for ($i=0; $i<$n; $i++) {
        $A = DB_fetchArray($r);
        $data_arr[] = $A;
    }

    $retval .= COM_startBlock($LANG_FAQ_ADMIN['Cat Editor'], '', COM_getBlockTemplate('_admin_block', 'header'));
    $retval .= ADMIN_createMenu($menu_arr, $text_arr['instructions'], $text_arr['icon']);
    $retval .= ADMIN_simpleList('faq_getListField_cat', $header_arr, $text_arr, $data_arr);
    $retval .= COM_endBlock(COM_getBlockTemplate('_admin_block', 'footer'));
    
    return $retval;
}


/**
* Delete a FAQ
*
* @param    string  $id    id of FAQ to delete
* @return   string          HTML redirect
*
*/
function deletefaq ($id)
{
    global $_CONF, $_TABLES, $_USER;

    $r = DB_query ("SELECT COUNT(*) AS cnt
                      FROM {$_TABLES['faq']} AS faq, {$_TABLES['faq_category']} AS cat
                     WHERE faq.id = '{$id}'
                       AND faq.category = cat.id"
                   . COM_getPermSQL( 'AND', 0, 3, 'faq' ) 
                   . COM_getPermSQL( 'AND', 0, 3, 'cat' ));
    $A = DB_fetchArray ($r);
    if ($A['cnt'] != 1 || ! SEC_hasRights ('faq.admin')) {
        COM_accessLog ("User {$_USER['username']} tried to illegally delete FAQ $id.");
        return COM_refresh ($_CONF['site_admin_url'] . '/plugins/faq/index.php?msq=5&mode=faq');
    }

    faq_relationDeleteForFaq($id);
    DB_delete ($_TABLES['faq'], 'id', $id);
    faq_clearLocalCache();
    faq_notifyDeleted($id);

    return COM_refresh ($_CONF['site_admin_url'] . '/plugins/faq/index.php?msg=3&mode=faq');
}

function deletecat ($id)
{
    global $_CONF, $_TABLES, $_USER;

    $r = DB_query ("SELECT COUNT(*) AS cnt
                      FROM {$_TABLES['faq_category']}
                     WHERE id = '{$id}'"
                   . COM_getPermSQL( 'AND', 0, 3, 'cat' ));
    $A = DB_fetchArray ($r);
    if ($A['cnt'] != 1 || ! SEC_hasRights ('faq.admin')) {
        COM_accessLog ("User {$_USER['username']} tried to illegally delete FAQ Category $id.");
        return COM_refresh ($_CONF['site_admin_url'] . '/plugins/faq/index.php?msq=5&mode=cat');
    }

    $faq_result = DB_query("SELECT id FROM {$_TABLES['faq']} WHERE category = '" . DB_escapeString($id) . "'");
    while ($faq_row = DB_fetchArray($faq_result)) {
        faq_relationDeleteForFaq($faq_row['id']);
        faq_notifyDeleted($faq_row['id']);
    }

    DB_delete ($_TABLES['faq'], 'category', $id);
    DB_delete ($_TABLES['faq_category'], 'id', $id);
    faq_clearLocalCache();

    return COM_refresh ($_CONF['site_admin_url'] . '/plugins/faq/index.php?msg=3&mode=cat');
}


// MAIN
$id = UTIL_getParamStr('id', '');
$action = UTIL_getParamStr('action', '');

if (($action == $LANG_FAQ_ADMIN['delete']) && !empty ($LANG_FAQ_ADMIN['delete'])) { // delete
    if ( $mode == 'faq' ) {
        $display .= deletefaq(UTIL_getParamStr('faq_id'));
    }
    else if ( $mode == 'cat' ) {
        $pagetitle = $LANG_FAQ_ADMIN['Cat Editor'];
        $display .= deletecat(UTIL_getParamStr('faq_id'));
    }
    else {
        $pagetitle = $LANG_FAQ_ADMIN['FAQ Editor'];
        $display .= COM_errorLog('FAQ editor tried to delete with unknown mode: ' . $mode);
    }
} else if (($action == $LANG_FAQ_ADMIN['save']) && !empty ($LANG_FAQ_ADMIN['save'])) { // save
    if ( $mode == 'faq' ) {
        $display .= savefaq(UTIL_getParamStr('faq_id'),
                            UTIL_getParamStr('faq_old_id'),
                            UTIL_getParamStr('faq_category'),
                            UTIL_getParamStr('faq_desc'),
                            UTIL_getParamStr('faq_title'),
                            UTIL_getParamInt('faq_hits'),
                            UTIL_getParamInt('faq_date'),
                            UTIL_getParam('owner_id'),
                            UTIL_getParam('group_id'),
                            UTIL_getParam('perm_owner'),
                            UTIL_getParam('perm_group'),
                            UTIL_getParam('perm_members'),
                            UTIL_getParam('perm_anon'));
    }
    else if ( $mode == 'cat' ) {
        $display .= savecat(UTIL_getParamStr('faq_id'),
                            UTIL_getParamStr('faq_old_id'),
                            UTIL_getParamStr('faq_desc'),
                            UTIL_getParamStr('faq_title'),
                            UTIL_getParam('owner_id'),
                            UTIL_getParam('group_id'),
                            UTIL_getParam('perm_owner'),
                            UTIL_getParam('perm_group'),
                            UTIL_getParam('perm_members'),
                            UTIL_getParam('perm_anon'));
    }
    else {
        $pagetitle = $LANG_FAQ_ADMIN['FAQ Editor'];
        $display .= COM_errorLog('FAQ editor tried to save with unknown mode: ' . $mode);
    }
} else if ($action == 'edit') {
    if ( $mode == 'faq' ) {
        $pagetitle = $LANG_FAQ_ADMIN['FAQ Editor'];
        $display .= editfaq($id);
    }
    else if ( $mode == 'cat' ) {
        $pagetitle = $LANG_FAQ_ADMIN['Cat Editor'];
        $display .= editcat($id);
    }
    else {
        $pagetitle = $LANG_FAQ_ADMIN['FAQ Editor'];
        $display .= COM_errorLog('FAQ editor opened with unknown mode: ' . $mode);
    }
} else { // 'cancel', 'show' or no mode at all
    $msg = UTIL_getParamInt('msg');
    if ($msg > 0) {
         $msg_txt = COM_showMessage ($msg, 'faq');
    }
    else
         $msg_txt = '';
    
    if ( $mode == 'faq' ) {
        $pagetitle = $LANG_FAQ_ADMIN['FAQ Editor'];
        $display .= $msg_txt;
        $display .= listfaq(UTIL_getParamStr('cat'));
    }
    else if ( $mode == 'cat' ) {
        $pagetitle = $LANG_FAQ_ADMIN['Cat Editor'];
        $display .= $msg_txt;
        $display .= listcat();
    }
    else {
        $pagetitle = $LANG_FAQ_ADMIN['FAQ Editor'];
        $display .= $msg_txt;
        $display .= "<h2>{$LANG_FAQ_ADMIN['FAQ Plugin']}</h2>\n";
        $display .= "<ul>\n";
        if (SEC_hasRights ('faq.admin'))
            $display .= "<li><a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php?mode=cat\">{$LANG_FAQ_ADMIN['Cat Editor']}</a></li>\n";
        $display .= "<li><a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php?mode=faq\">{$LANG_FAQ_ADMIN['FAQ Editor']}</a></li>\n";
        $display .= "<li><a href=\"{$_CONF['site_admin_url']}/plugins/faq/relations.php\">Associations</a></li>\n";
        $display .= "<li><a href=\"{$_CONF['site_admin_url']}/plugins/faq/coverage.php\">Coverage</a></li>\n";
        if (SEC_inGroup ('Root') && 
            0 < DB_count($_TABLES['plugins'], 'pi_name', 'faqman') &&
            0 < DB_count($_TABLES['faq_topics'])) {
            $display .= "<li>{$LANG_FAQ_ADMIN['faqman_import']}\n";
            $display .= "<form action=\"{$_CONF['site_admin_url']}/plugins/faq/import.php\" method=\"post\">";
            $display .= '<input type="hidden" name="import" value="faqman"/>';
            $usergroups = SEC_getUserGroups();
            $groupdd = '<select name="group_id">' . LB;
            for ($i = 0; $i < count($usergroups); $i++) {
                $groupdd .= '<option value="' . $usergroups[key($usergroups)] . '"';
                if ($_GROUPS['FAQ Admin'] == $usergroups[key($usergroups)]) {
                    $groupdd .= ' selected="selected"';
                }
                $groupdd.= '>' . key($usergroups) . '</option>' . LB;
                next($usergroups);
            }
            $groupdd .= '</select>' . LB;
            
            $display .= $LANG_ACCESS['group'] . ': ' . $groupdd;
            $display .= "<input type=\"submit\" name=\"action\" value=\"{$LANG_FAQ_ADMIN['import']}\"/>";
            $display .= "</form></li>\n";
        }
        $display .= "</ul>\n";
    }
}

$display = COM_createHTMLDocument($display, array('pagetitle' => $pagetitle));

COM_output($display);

?>