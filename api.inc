<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | FAQ Plugin 1.1                                                            |
// +---------------------------------------------------------------------------+
// | api.inc                                                                   |
// |                                                                           |
// | This file implements the necessary and used optional Geeklog Plugin API   |
// | methods.                                                                  |
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

if (strpos ($_SERVER['PHP_SELF'], 'api.inc') !== false) {
    die ('This file can not be used on its own.');
}

// +---------------------------------------------------------------------------+
// | Geeklog Plugin API Implementations                                        |
// +---------------------------------------------------------------------------+

/**
* Return the current version of code.
* Used in the Plugin Editor to show the registered version and code version
*/
function plugin_chkVersion_faq()
{
    global $_CONF;

    require_once $_CONF['path'] . 'plugins/faq/autoinstall.php';          

    $inst_parms = plugin_autoinstall_faq('faq'); 

    return $inst_parms['info']['pi_version'];
}

function plugin_whatsnewsupported_faq()
{
    global $_TABLES, $_FAQ_CONF, $LANG_FAQ_COMMON, $LANG_WHATSNEW;

    if ( $_FAQ_CONF['hidenewfaq'] ) {
        $retval = false;
    }
    else {
        $retval = array( $LANG_FAQ_COMMON['FAQs'], COM_formatTimeString( $LANG_WHATSNEW['new_last'], $_FAQ_CONF['newfaqinterval'] ));
    }

    return $retval;
}

function plugin_getwhatsnew_faq()
{
    global $_CONF, $_TABLES, $_USER, $_FAQ_CONF, $LANG_FAQ_COMMON;

    if ( $_FAQ_CONF['hidenewfaq'] )
        return '';

    $retval = '';

    // Get newest links
    $sql = "SELECT faq.id,faq.title 
              FROM {$_TABLES['faq']} AS faq, 
                   {$_TABLES['faq_category']} AS cat 
             WHERE (faq.date >= (DATE_SUB(NOW(), INTERVAL {$_FAQ_CONF['newfaqinterval']} SECOND)))
               AND faq.category = cat.id" 
         . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
         . COM_getPermSQL( 'AND', 0, 2, 'cat' )
         . ' ORDER BY date DESC LIMIT 15';
    $result = DB_query( $sql );
    $nrows = DB_numRows( $result );

    if( $nrows > 0 )
    {
        $newlinks = array();
        for( $x = 0; $x < $nrows; $x++ )
        {
            $A = DB_fetchArray( $result );
            $A['title'] = stripslashes( $A['title'] );

            // redirect link via portal.php so we can count the clicks
            $lcount = COM_buildUrl( $_CONF['site_url']
                    . '/faq/index.php?faq=' . $A['id'] );

            // Trim the length if over 16 characters
	    $trimlength = 22;
            $itemlen = strlen( $A['title'] );
            if( $itemlen > $trimlength )
            {
                $newlinks [] = '<a href="' . $lcount . '" title="'
                    . $A['title'] . '">' . substr( $A['title'], 0, $trimlength )
                    . '...</a>' . LB;
            }
            else
            {
                $newlinks[] = '<a href="' . $lcount . '">'
                    . substr( $A['title'], 0, $itemlen ) . '</a>' . LB;
            }
        }

        $retval .= COM_makeList( $newlinks, 'list-new-plugins' );
    }
    else
    {
        $retval .= $LANG_FAQ_COMMON['no_new'] . '<br' . XHTML . '>' . LB;
    }

    return $retval;
}


/**
* Returns the items for this plugin that should appear on the main menu
*
* NOTE: this MUST return the url/value pairs in the following format
* $<arrayname>[<label>] = <url>
*
*/
function plugin_getmenuitems_faq ()
{
    global $_CONF, $_FAQ_CONF, $LANG_FAQ_COMMON, $_TABLES;
    
    if ($_FAQ_CONF['hidefaqmenu'])
        return false;
    
    $sql = "SELECT COUNT(*) AS cnt
              FROM {$_TABLES['faq']} AS faq, 
                   {$_TABLES['faq_category']} AS cat 
             WHERE faq.category = cat.id" 
         . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
         . COM_getPermSQL( 'AND', 0, 2, 'cat' );
    
    $r = DB_query($sql);
    $a = DB_fetchArray($r);
    
    if($a['cnt'] == 0) {
        return false;
    } else {
        $menuitems[$LANG_FAQ_COMMON['FAQ']] = $_CONF['site_url'] . '/faq/index.php';
    }

    return $menuitems;
}

/**
* New stats plugin API function for proper integration with the site stats
*
* @return   array(item text, item count);
*
*/
function plugin_statssummary_faq ()
{
    global $LANG_FAQ_STATS, $_TABLES;

    $cats = DB_count($_TABLES['faq_category']);
    $faqs = DB_count($_TABLES['faq']);
    $r = DB_query("SELECT SUM(hits) AS hits FROM {$_TABLES['faq']}");
    $A = DB_fetchArray($r);
    $hits = $A['hits'];
    
    $item_count = COM_numberFormat ($cats)
                . ' / ' . COM_numberFormat($faqs)
                . ' (' . COM_numberFormat ($hits) . ') ';

    return array ($LANG_FAQ_STATS['stats_summary'], $item_count);
}

/**
* Shows the statistics for the FAQ plugin on stats.php.
* If $showsitestats is 1 then we are to only print the overall stats in the
* 'site statistics box' otherwise we show the detailed stats
*
* @param    int     showsitestate   Flag to let us know which stats to get
*/
function plugin_showstats_faq ($showsitestats)
{
    global $_CONF, $_TABLES, $LANG_FAQ_STATS;
    require_once( $_CONF['path_system'] . 'lib-admin.php' );
    $retval = '';
    $r = DB_query("SELECT faq.id AS id, faq.title AS title, faq.hits AS hits 
                     FROM {$_TABLES['faq']} AS faq, 
                          {$_TABLES['faq_category']} AS cat 
                    WHERE faq.category = cat.id AND hits > 0" 
                  . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
                  . COM_getPermSQL( 'AND', 0, 2, 'cat' )
                  . ' ORDER BY hits DESC LIMIT 10');
                          
    $nrows  = DB_numRows ($r);

    if ($nrows > 0) {
        require_once( $_CONF['path_system'] . 'lib-admin.php' );
        $header_arr = array(
            array('text' => $LANG_FAQ_STATS['Question'], 'field' => 'title'),
            array('text' => $LANG_FAQ_STATS['Hits'], 'field' => 'hits'),
        );
        $data_arr = array();
        $text_arr = array('has_menu'     => false,
                          'title'        => $LANG_FAQ_STATS['headline'],
        );
        for ($i = 0; $i < $nrows; $i++) {
            $A = DB_fetchArray ($r);
            $A['title'] = "<a href=\"" . COM_buildUrl ($_CONF['site_url']
                      . "/faq/index.php?faq={$A['id']}"). "\">{$A['title']}</a>";
            $A['hits'] = COM_NumberFormat ($A['hits']);
            $data_arr[$i] = $A;
        }
        $retval .= ADMIN_simpleList("", $header_arr, $text_arr, $data_arr);
    } else {
        $retval .= COM_startBlock ($LANG_FAQ_STATS['headline']);
        $retval .= $LANG_FAQ_STATS['stats_no_hits'];
        $retval .= COM_endBlock ();
    }
    
    return $retval;
}

/**
* Returns the URL of the plugin's icon
*
* @return   string      URL of the icon
*
*/
function plugin_geticon_faq ()
{
    global $_CONF;

    return $_CONF['site_url'] . '/faq/images/faqplugin.png';
}

/**
* This will put an option for faq in the command and control block on
* moderation.php
*
*/
function plugin_cclabel_faq()
{
    global $_CONF, $LANG_FAQ_COMMON;

    if (SEC_hasRights ('faq.edit,faq.admin','OR')) {
        return array ($LANG_FAQ_COMMON['FAQ'],
                $_CONF['site_admin_url'] . '/plugins/faq/index.php',
                plugin_geticon_faq ());
    }

    return false;
}

/**
* returns the administrative option for this plugin
*
*/
function plugin_getadminoption_faq()
{
    global $_CONF, $_TABLES, $LANG_FAQ_COMMON;

    if (!SEC_hasRights('faq.admin,faq.edit', 'OR')) {
        return array();
    }

    $sql = "SELECT COUNT(*) AS cnt
              FROM {$_TABLES['faq']} AS faq,
                   {$_TABLES['faq_category']} AS cat
             WHERE faq.category = cat.id"
         . COM_getPermSQL('AND', 0, 3, 'faq')
         . COM_getPermSQL('AND', 0, 3, 'cat');

    $r = DB_query($sql);
    $A = DB_fetchArray($r);
    $count = isset($A['cnt']) ? (int) $A['cnt'] : 0;

    return array(
        $LANG_FAQ_COMMON['FAQ'],
        $_CONF['site_admin_url'] . '/plugins/faq/index.php',
        $count
    );
}

/**
* Geeklog is asking us to provide any new items that show up in the type
* drop-down on search.php.  Let's users search for FAQs.
*
*/
function plugin_searchtypes_faq()
{
    global $LANG_FAQ_COMMON;

    $tmp['faq'] = $LANG_FAQ_COMMON['FAQ'];

    return $tmp;
}


/**
* this searches for static pages matching the user query and returns an array of
* for the header and table rows back to search.php where it will be formated and
* printed
*
* @param    string  $query      Keywords user is looking for
* @param    date    $datestart  Start date to get results for
* @param    date    $dateend    End date to get results for
* @param    string  $topic      The topic they were searching in
* @param    string  $type       Type of items they are searching, or 'all' (deprecated)
* @param    int     $author     Get all results by this author
* @param    string  $keyType    search key type: 'all', 'phrase', 'any'
* @param    int     $page       page number of current search (deprecated)
* @param    int     $perpage    number of results per page (deprecated)
*
*/

function plugin_dopluginsearch_faqs($query, $datestart, $dateend, $topic, $type, $author, $keyType, $page, $perpage)
{
    global $_TABLES, $LANG_FAQ_SEARCH;

    // Make sure the query is SQL safe
    //$query = trim(addslashes($query));
    $query = trim(DB_escapeString($query));
    
    $sql = "SELECT faq.id AS id, faq.title AS title, faq.description AS description, UNIX_TIMESTAMP(faq.date) AS date, faq.owner_id AS uid, faq.hits AS hits, ";
    $sql .= "CONCAT('/faq/index.php?faq=', faq.id) AS url ";                      
    $sql .= " FROM {$_TABLES['faq']} AS faq WHERE 1=1 ";
    $sql .= COM_getPermSQL( 'AND', 0, 2, 'faq' );

    $search = new SearchCriteria('faq', $LANG_FAQ_SEARCH['results']);

    $columns = array('title' => 'title', 'description');
    $sql .= $search->getDateRangeSQL('AND', 'date', $datestart, $dateend);
    list($sql,$ftsql) = $search->buildSearchSQL($keyType, $query, $columns, $sql);

    $search->setSQL($sql);
    $search->setFTSQL($ftsql);
    $search->setRank(3);
    //$search->setAppendQuery(false);
    $search->setURLRewrite(true);

    return $search;
}


function plugin_dopluginsearch_faq($query, $datestart, $dateend, $topic, $type, $author, $keyType, $page, $perpage)
{
    global $_TABLES, $LANG_FAQ_SEARCH;

    // Make sure the query is SQL safe
    $query = trim(addslashes($query));


    $sql_f = "SELECT faq.id AS id, faq.title AS title, faq.description AS description, UNIX_TIMESTAMP(faq.date) AS date, faq.hits AS hits , faq.owner_id AS uid,";
    $sql_f .= "CONCAT('/faq/index.php?faq=', faq.id) AS url ";                      
    $sql_f .= " FROM {$_TABLES['faq']} AS faq WHERE 1=1 ";
    
    $sql_c = "SELECT faqc.id AS id, faqc.title AS title, faqc.description AS description, faqc.owner_id AS uid, CURDATE() AS date,"; // Add fake date field (since category does not have actual date field) for search results else ordered by date will error out
    $sql_c .= "CONCAT('/faq/index.php?cat=', faqc.id) AS url, 0 AS hits ";                      
    $sql_c .= " FROM {$_TABLES['faq_category']} AS faqc WHERE 1=1 ";

    $sql = COM_getPermSQL('AND') . ' ';

    if (!empty ($author)) {
        $sql .= "AND (owner_id = '$author') ";
    }

    
    // Search the faq
    $search_f = new SearchCriteria('faq', $LANG_FAQ_SEARCH['FAQ']);

    $columns = array('title' => 'title', 'description');
    $sql_d = $search_f->getDateRangeSQL('AND', 'date', $datestart, $dateend);
    list($sql_tmp, $ftsql_tmp) = $search_f->buildSearchSQL($keyType, $query, $columns, $sql_f . $sql . $sql_d);

    $search_f->setSQL($sql_tmp);
    $search_f->setFTSQL($ftsql_tmp);
    $search_f->setRank(3);

    // Search faq categories 
    $search_c = new SearchCriteria('faq', array($LANG_FAQ_SEARCH['FAQ'], $LANG_FAQ_SEARCH['category']));

    $columns = array('title' => 'title', 'description');
    list($sql_tmp,$ftsql_tmp) = $search_c->buildSearchSQL($keyType, $query, $columns, $sql_c . $sql);
//echo $ftsql_tmp[mysql];
//echo $sql_tmp;
    $search_c->setSQL($sql_tmp);
    $search_c->setFTSQL($ftsql_tmp);
    $search_c->setRank(2);

    return array($search_f, $search_c);
}


/**
* A user is about to be deleted. Update ownership of any polls owned
* by that user or delete them.
*
* @param   uid   int   User id of deleted user
*
*/
function plugin_user_delete_faq ($uid)
{
    global $_TABLES;

    // assign ownership to a user from the Root group
    $rootgroup = DB_getItem ($_TABLES['groups'], 'grp_id', "grp_name = 'Root'");
    $r = DB_query ("SELECT DISTINCT ug_uid FROM {$_TABLES['group_assignments']} WHERE ug_main_grp_id = $rootgroup ORDER BY ug_uid LIMIT 1");
    list($rootuser) = DB_fetchArray ($r);

    DB_query ("UPDATE {$_TABLES['faq']} SET owner_id = $rootuser WHERE owner_id = $uid");
    DB_query ("UPDATE {$_TABLES['faq_category']} SET owner_id = $rootuser WHERE owner_id = $uid");
}

function plugin_autotags_faq ($op, $content = '', $autotag = '', $parameters = array())
{
    global $_CONF, $_TABLES, $LANG_FAQ_COMMON, $_FAQ_CONF;

    if ($op == 'tagname' ) {
        return array ('faq', 'faqcat', 'faqembed', 'faqrelated');     
    } 
    else if ($op == 'parse') { 
        switch ($autotag['tag']) { 
        case 'faq':
            $r = DB_query("SELECT faq.title AS title
                             FROM {$_TABLES['faq']} AS faq, 
                                  {$_TABLES['faq_category']} AS cat 
                            WHERE faq.category = cat.id
                              AND faq.id = '{$autotag['parm1']}'" 
                        . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
                        . COM_getPermSQL( 'AND', 0, 2, 'cat' ));
            if (0 == DB_numRows($r) && 
                $_FAQ_CONF['faqman_autolink_patch'] &&
                is_numeric($autotag['parm1'])) {
              $r = DB_query("SELECT faq.title AS title, faq.id AS id
                               FROM {$_TABLES['faq']} AS faq, 
                                    {$_TABLES['faq_category']} AS cat 
                              WHERE faq.category = cat.id
                                AND faq.id LIKE 'FAQMAN_{$autotag['parm1']}_%'" 
                          . COM_getPermSQL( 'AND', 0, 2, 'faq' ) 
                          . COM_getPermSQL( 'AND', 0, 2, 'cat' ));
              if (1 == DB_numRows($r)) {
                $autotag['parm1'] = DB_result ($r, 0, 'id');
              }
            }
            if (1 == DB_numRows($r)) {
                if (!empty ($autotag['parm2'])) {
                    $label = $autotag['parm2'];
                } 
                else {
                    $label = DB_result ($r, 0, 'title');
                }
            }
            else {
                $link = sprintf($LANG_FAQ_COMMON['no_autolink_faq'], $autotag['parm1']);
                break;
            }
            
            $url = $_CONF['site_url'] . '/faq/index.php?faq=' . $autotag['parm1'];
            $link = '<a href="' . $url . '">' . $label . '</a>';
            break;
        case 'faqcat':
            $r = DB_query("SELECT title
                             FROM {$_TABLES['faq_category']} AS cat 
                            WHERE id = '{$autotag['parm1']}'" 
                        . COM_getPermSQL( 'AND', 0, 2));
            if (1 == DB_numRows($r)) {
                if (!empty ($autotag['parm2'])) {
                    $label = $autotag['parm2'];
                } 
                else {
                    $label = DB_result ($r, 0, 'title');
                }
            }
            else {
                $link = sprintf($LANG_FAQ_COMMON['no_autolink_cat'], $autotag['parm1']);
                break;
            }
            
            $url = $_CONF['site_url'] . '/faq/index.php?cat=' . $autotag['parm1'];
            $link = '<a href="' . $url . '">' . $label . '</a>';
            break;
        case 'faqembed':
            $faq_id = isset($autotag['parm1']) ? $autotag['parm1'] : '';
            $item = plugin_getiteminfo_faq($faq_id, 'id,title,description,url', 0, array());
            if (!is_array($item)) {
                $link = sprintf($LANG_FAQ_COMMON['no_autolink_faq'], $faq_id);
                break;
            }
            $embedded = array(array(
                'faq_id' => $item['id'],
                'title' => $item['title'],
                'description' => $item['description']
            ));
            $link = faq_renderFaqItems($embedded, 'faq-autotag faq-autotag-embed', false);
            break;

        case 'faqrelated':
            $provider = isset($autotag['parm1']) ? trim($autotag['parm1']) : '';
            $item_id = isset($autotag['parm2']) ? trim($autotag['parm2']) : '';

            if ($provider === '' && isset($parameters['type'])) {
                $provider = $parameters['type'];
            }
            if ($item_id === '' && isset($parameters['id'])) {
                $item_id = (string) $parameters['id'];
            }

            if ($provider === '' || $item_id === '') {
                $link = '[faqrelated requires a provider and item id]';
                break;
            }

            $related = faq_relationGetForItem($provider, $item_id, '', '');
            $link = faq_renderFaqItems($related, 'faq-autotag faq-autotag-related', false);
            break;

        default:
            $link = sprintf($LANG_FAQ_COMMON['autolink_error'], $autotag['tag']);
        }
        
        $content = str_replace ($autotag['tagstr'], $link, $content);
        
        return $content;
    }
}

/**
* Automatic uninstall function for plugins
*
* @return   array
*
* This code is automatically uninstalling the plugin.
* It passes an array to the core code function that removes
* tables, groups, features and php blocks from the tables.
* Additionally, this code can perform special actions that cannot be
* foreseen by the core code (interactions with other plugins for example)
*
*/
function plugin_autouninstall_faq()
{
    $out = array (
        /* give the name of the tables, without $_TABLES[] */
        'tables' => array('faq',
                          'faq_category',
                          'faq_relations'),
        /* give the full name of the group, as in the db */
        'groups' => array('FAQ Admin'),
        /* give the full name of the feature, as in the db */
        'features' => array('faq.admin',
                            'faq.edit'),
        /* give the full name of the block, including 'phpblock_', etc */
        'php_blocks' => array('phpblock_faq_random'),
        /* give all vars with their name */
        'vars'=> array()
    );
    return $out;
}

/**
 * Update the FAQ plugin
 *
 * @return   int     Number of message to display (true = generic success msg)
 */
function plugin_upgrade_faq()
{
    global $_CONF, $_TABLES, $_DB_dbms, $_DB_table_prefix;

    $installed_version = DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name = 'faq'");
    $code_version = plugin_chkVersion_faq();
    if ($installed_version == $code_version) {
        // nothing to do
        return true;
    }

    require_once $_CONF['path'] . 'plugins/faq/autoinstall.php';

    if (!plugin_compatible_with_this_version_faq('faq')) {
        return 3002;
    }
    
    // Retrieve other plugin info like homepage 
    $inst_parms = plugin_autoinstall_faq('faq');

    //require_once $_CONF['path'] . 'plugins/faq/sql/' . $_DB_dbms . '_updates.php';

    //require_once $_CONF['path'] . 'plugins/faq/install_updates.php';

    $current_version = $installed_version;
    $done = false;
    while (!$done) {
        switch ($current_version) {
            case '1.0.0':
            case '1.0.1':
            case '1.0.2':
            case '1.0.3':            
                // no db changes
                $current_version = '1.1.0';
                break;

            case '1.1.0':
                // no db changes
                $current_version = '1.2.0';
                break;

            case '1.2.0':
                // no db changes
                $current_version = '1.2.0.1';
                break;

            case '1.2.0.1':
                // no db changes
                $current_version = '1.2.0.2';
                break;

            case '1.2.0.2':
                // no db changes
                $current_version = '1.2.0.3';
                break;

            case '1.2.0.3':
                $_TABLES['faq_relations'] = $_DB_table_prefix . 'faq_relations';
                $sql = "CREATE TABLE IF NOT EXISTS {$_TABLES['faq_relations']} (
                            relation_id int(11) unsigned NOT NULL AUTO_INCREMENT,
                            faq_id varchar(40) NOT NULL,
                            provider varchar(40) NOT NULL,
                            item_id varchar(128) NOT NULL,
                            item_subtype varchar(64) NOT NULL DEFAULT '',
                            placement varchar(16) NOT NULL DEFAULT 'automatic',
                            sort_order int(11) NOT NULL DEFAULT 0,
                            enabled tinyint(1) unsigned NOT NULL DEFAULT 1,
                            created datetime DEFAULT NULL,
                            modified datetime DEFAULT NULL,
                            PRIMARY KEY (relation_id),
                            UNIQUE KEY faq_target (faq_id, provider, item_id, item_subtype),
                            KEY target_lookup (provider, item_id, item_subtype, enabled),
                            KEY faq_lookup (faq_id, enabled)
                        ) ENGINE=InnoDB";
                DB_query($sql);
                $current_version = '1.3.0';
                break;

            default:
                $done = true;
                break;
        }
    }

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $_CONF['path'] . 'plugins/faq/install_defaults.php';
    plugin_initconfig_faq();

    DB_query("UPDATE {$_TABLES['plugins']} SET pi_version = '$code_version', pi_gl_version = '{$inst_parms['info']['pi_gl_version']}', pi_homepage = '{$inst_parms['info']['pi_homepage']}' WHERE pi_name = 'faq'");
    COM_errorLog( "Updated FAQ plugin from v$installed_version to v$code_version", 1 );

    return true;
}

?>
