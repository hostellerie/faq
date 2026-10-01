<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | FAQ Plugin 1.0                                                            |
// +---------------------------------------------------------------------------+
// | Installation SQL                                                          |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2006 by the following authors:                              |
// |                                                                           |
// | Authors: Emil Gustafsson   - emil AT cellfish DOT se                      |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is licensed under the terms of the GNU General Public License|
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                      |
// | See the GNU General Public License for more details.                      |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+
//

/* TODO: (FULL) TEXT Indexes? */

$_SQL[] = "
CREATE TABLE {$_TABLES['faq_category']} (
  id VARCHAR(40) NOT NULL DEFAULT '',
  title VARCHAR(250) DEFAULT NULL,
  description TEXT,
  owner_id mediumint(8) unsigned NOT NULL default '1',
  group_id mediumint(8) unsigned NOT NULL default '1',
  perm_owner tinyint(1) unsigned NOT NULL default '3',
  perm_group tinyint(1) unsigned NOT NULL default '2',
  perm_members tinyint(1) unsigned NOT NULL default '2',
  perm_anon tinyint(1) unsigned NOT NULL default '2',
  PRIMARY KEY  (id)
) TYPE=MyISAM
";

$_SQL[] = "
CREATE TABLE {$_TABLES['faq']} (
  id VARCHAR(40) NOT NULL DEFAULT '',
  category VARCHAR(40) DEFAULT NULL,
  sort_order int(11) NOT NULL DEFAULT 10,
  title VARCHAR(250),
  description TEXT,
  hits INT(11) DEFAULT 0,
  date DATETIME DEFAULT NULL,
  owner_id mediumint(8) unsigned NOT NULL default '1',
  group_id mediumint(8) unsigned NOT NULL default '1',
  perm_owner tinyint(1) unsigned NOT NULL default '3',
  perm_group tinyint(1) unsigned NOT NULL default '2',
  perm_members tinyint(1) unsigned NOT NULL default '2',
  perm_anon tinyint(1) unsigned NOT NULL default '2',
  PRIMARY KEY  (id),
  INDEX (category)
) TYPE=MyISAM
";


$_SQL[] = "
CREATE TABLE {$_TABLES['faq_relations']} (
  relation_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  faq_id varchar(40) NOT NULL,
  provider varchar(40) NOT NULL,
  item_id varchar(128) NOT NULL,
  item_subtype varchar(64) NOT NULL DEFAULT '',
  placement varchar(16) NOT NULL DEFAULT 'automatic',
  topic_scope varchar(16) NOT NULL DEFAULT 'both',
  sort_order int(11) NOT NULL DEFAULT 0,
  enabled tinyint(1) unsigned NOT NULL DEFAULT 1,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (relation_id),
  UNIQUE KEY faq_target (faq_id, provider, item_id, item_subtype),
  KEY target_lookup (provider, item_id, item_subtype, enabled),
  KEY faq_lookup (faq_id, enabled)
) ENGINE=InnoDB
";


$_SQL[] = "
CREATE TABLE {$_TABLES['faq_category_relations']} (
  relation_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  category_id varchar(40) NOT NULL,
  provider varchar(40) NOT NULL,
  item_id varchar(128) NOT NULL,
  item_subtype varchar(64) NOT NULL DEFAULT '',
  placement varchar(16) NOT NULL DEFAULT 'automatic',
  topic_scope varchar(16) NOT NULL DEFAULT 'both',
  sort_order int(11) NOT NULL DEFAULT 0,
  enabled tinyint(1) unsigned NOT NULL DEFAULT 1,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (relation_id),
  UNIQUE KEY category_target (category_id, provider, item_id, item_subtype),
  KEY target_lookup (provider, item_id, item_subtype, enabled),
  KEY category_lookup (category_id, enabled)
) ENGINE=InnoDB
";

$_SQL[] = "INSERT INTO {$_TABLES['faq_category']}(id,title,description,owner_id,group_id) 
           VALUES('faqfaq','FAQ for the FAQ Plugin','Common questions about the FAQ Plugin administration.',{$_USER['uid']},#group#)";
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('faqman_import_1','faqfaq','Can I import FAQ\'s from the FAQMAN plugin?','Yes, if you access the <a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php\">FAQ Manager</a> with a user that is a member of the ROOT group you will have an option to import FAQMAN topics if any FAQMAN topics exist.<p><b>See also:</b> [faq:faqman_import_2] [faq:faqman_import_3]',NOW(),{$_USER['uid']},#group#)";
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('faqman_import_2','faqfaq','I\'m a member of the ROOT group but I can\'t see the import option on the FAQ Manager page. Where is it?','Check all these:<ol type=\"1\"><li>Do you have faq.admin rights?</li><li>Do you have the FAQMAN plugin installed?</li><li>Does the FAQMAN plugin have any topics (empty categories does not count)?</li></ol>',NOW(),{$_USER['uid']},#group#)";
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('faqman_import_3','faqfaq','I imported data from the FAQMAN plugin but now the old autotags no longer work. What happened?','When importing new IDs are generated. As of version 1.0.2 of the FAQ plugin there is a patch in the autotag function that you can enable setting \$_FAQ_CONF[\'faqman_autolink_patch\'] = true in your FAQ plugin config.php.<p>You should not do this if you do not have old faq autotags since it might affect performance slightly.',NOW(),{$_USER['uid']},#group#)";
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('autolinks','faqfaq','Does the FAQ plugin support autolinks?','Yes it does. You use the <i>faq</i> autolink to link to FAQs and the <i>faqcat</i> autolink to link to FAQ categories. If you do not provide a label the FAQ question/category title will be used as label.<p>You may also <i>autolinks</i> in FAQ answers and FAQ category descriptions.',NOW(),{$_USER['uid']},#group#)";
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('security','faqfaq','How does the FAQ security work?','First of all there are two <i>access rights</i> involved. If you have <i>faq.edit</i> rights you may create and potentially change FAQ questions. If you have <i>faq.admin</i> rights you may create and potentially change and delete FAQ questions and FAQ categories.<br>In order to create a FAQ question/category you only need the appropiate access right. In order to change or delete a FAQ question/category you need the appropiate access right and you also need write access to the specific FAQ question/category.<br>FAQ questions also inherit rights from the FAQ category they belong to. This means you may not create, change or delete a FAQ question in a FAQ category you do not have write access to. A user that may not change the FAQ categories, but should be able to change FAQ questions should hence have the faq.edit right (but not the faq.admin right) and write access to both the appropiate FAQ categories and FAQ questions.<p><b>See also:</b> [faq:users_dont_see_faq]',NOW(),{$_USER['uid']},#group#)";
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('users_dont_see_faq','faqfaq','Why do my users not see the FAQ link/categories I have created?','The FAQ link is only displayed if the user have access to read at least one FAQ question. The same goes for FAQ categories, if there are no FAQ questions the user may read in a FAQ category, that FAQ category is not shown to the user.<p><b>See also: [faq:security]',NOW(),{$_USER['uid']},#group#)";
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('change_theme','faqfaq','How can I change the FAQ templates to match my theme?','The templates used can be found in the /path/to/geeklog/plugins/faq/templates folder. If you want to change the templates to match your theme, <i>copy</i> the contents of the templates folder into a new folder named <i>faq</i> within your theme folder.<br>Example commandline to do this:[code]cp -r /path/to/geeklog/plugins/faq/templates /path/to/geeklog/public_html/layout/your_teme/faq[/code]',NOW(),{$_USER['uid']},#group#)";

/*
$_SQL[] = "INSERT INTO {$_TABLES['faq']}(id,category,title,description,date,owner_id,group_id) 
           VALUES('autolinks','faqfaq','Q','A',NOW(),{$_USER['uid']},#group#)";
*/
           
$_SQL[] = "INSERT INTO {$_TABLES['blocks']} (is_enabled, name, type, title, blockorder, content, onleft, phpblockfn, owner_id, group_id, perm_owner, perm_group) 
           VALUES (1,'faq_random_block','phpblock','FAQ',55,'',0,'phpblock_faq_random',{$_USER['uid']},#group#,3,3)";


?>
