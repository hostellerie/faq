<?php

/******************************************************************************
* english.php
* This is the english language page for the Geeklog FAQ Plug-in!
*
* Copyright (C) 2006 Emil Gustafsson
* emil@cellfish.se
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*
*******************************************************************************
* $Id$
******************************************************************************/

$LANG_FAQ_COMMON = array(
    'FAQs' => 'FAQs',
    'FAQ' => 'FAQ',
    'no_new' => 'No recent new FAQs',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'Categories',
    'Question' => 'Question',
    'Hits' => 'Hits',
    'Updated' => 'Updated',
    'Answer' => 'Answer',
    'permalink' => 'Permalink',
    'back_to' => 'Back to',
    'breadcrumb_aria' => 'FAQ breadcrumb',
    'actions_aria' => 'FAQ actions',
    'related_questions' => 'Related questions',
    'category_page_title' => 'FAQ Category: %s',
    'no_autolink_faq' => '[FAQ ID "%s" does not exist or you do not have access to it]',
    'no_autolink_cat' => '[FAQ category "%s" does not exist or you do not have access to it]',
    'autolink_error' => '[error in "%s" FAQ link]'
);

/******************************************************************************
* for stats
******************************************************************************/
$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'It appears that there are no FAQs on this site or no one has ever clicked on one.',
    'stats_summary' => 'FAQ Categories/Entries (hits) in the system',
    'Question' => 'Question',
    'Hits' => 'Hits',
    'headline' => 'Top Ten FAQs',
);

/******************************************************************************
* for the search
******************************************************************************/
$LANG_FAQ_SEARCH = array(
 'FAQ' => 'FAQ',
 'results' => 'FAQ Results',
 'title' => 'Question',
 'date' => 'Updated',
 'author' => 'Author',
 'category' => 'Category',
 'hits' => 'Hits'
);

/******************************************************************************
* Messages for COM_showMessage the submission form
******************************************************************************/

$PLG_faq_MESSAGE1 = "You are trying to access a FAQ category you do not have access to or that doesn't exist. This attempt has been logged.";
$PLG_faq_MESSAGE2 = "You are trying to access a FAQ entry you do not have access to or that doesn't exist. This attempt has been logged.";
$PLG_faq_MESSAGE3 = 'The FAQ has been successfully deleted.';
$PLG_faq_MESSAGE4 = 'The FAQ has been successfully saved.';
$PLG_faq_MESSAGE5 = "You are trying to perform an action on a FAQ entry you do not have access to or that doesn't exist. This attempt has been logged.";
$PLG_faq_MESSAGE6 = 'FAQ plugin upgrade was successful.';
$PLG_faq_MESSAGE7 = 'FAQ plugin upgrade failed.';
/******************************************************************************
* admin
******************************************************************************/
$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'FAQ Category',
    'FAQ_Entry' => 'FAQ Entry',
    'FAQ Entries' => 'FAQ Entries',
    'Edit' => 'Edit',
    'FAQ Editor' => 'FAQ Editor',
    'Cat Editor' => 'FAQ Category Editor',
    'Access Denied MSG' => 'Sorry, you do not have access to the FAQ administration page. Please note that all attempts to access unauthorized features are logged.',
    'delete' => 'delete',
    'save' => 'save',
    'cancel' => 'cancel',
    'show' => 'show',
    'accessdenied' => "You are trying to access a FAQ item that you don't have rights to.  This attempt has been logged. Please <a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php\">go back to the FAQ administration screen</a>.",
    'title' => 'Title',
    'description' => 'Description',
    'question' => 'Question',
    'answer' => 'Answer',
    'id' => 'ID',
    'hits' => 'Hits',
    'order' => 'Order',
    'order_help' => 'Controls this FAQ position inside its category. Lower values appear first; values such as 10, 20, 30 leave room for later insertions.',
    'changed' => 'Updated',
    'category' => 'Category',
    'all_cat' => 'All Categories',
    'date_will_update' => '<b>NOTE:</b> The date will be updated if you save!',
    'reset_date' => 'Update changed date to <i>now</i>.',
    'save_rights_error' => 'You cannot save with persmissions you do not have.',
    'missing_fields_faq' => 'You must supply Question, Answer and a Category for each FAQ entry.',
    'missing_fields_cat' => 'You must supply Title and a Description for each FAQ Category.',
    'delete_note' => 'NOTE: Deleting this category deletes ALL FAQs associated with this category.',
    'FAQ Plugin' => 'FAQ Plugin',
    'faqman_import' => 'You have the FAQMAN plugin installed (which is not the same as this plugin). But you may import data from the FAQMAN plugin.',
    'cat instructions' => 'Create and manage FAQ categories here. Use the Questions tab to manage the questions attached to each category.',
    'faq instructions' => 'Create and manage FAQ questions and answers here. Use the category filter to quickly find the FAQs you want to edit.',
    'import' => 'import',
    'access' => 'Access',
    'editor_mode' => 'Editor mode',
    'visual_editor' => 'Visual editor',
    'html_source' => 'HTML source',
    'insert_media' => 'Insert media',
    'questions' => 'Questions',
    'categories' => 'Categories',
    'associations' => 'Associations',
    'coverage' => 'Coverage',
    'configuration' => 'Configuration',
    'new_question' => 'New question',
    'new_category' => 'New category',
    'administration_aria' => 'FAQ administration'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'FAQ Associations',
    'intro' => 'Associate either one FAQ or an entire FAQ category with content exposed by Geeklog providers. A category association is dynamic: newly added FAQs in that category are included automatically. Providers and selectable content are discovered automatically when possible; manual ID remains a fallback.',
    'add_association' => 'Add association',
    'association_source' => 'Association source',
    'individual_faq' => 'Individual FAQ',
    'whole_category' => 'Whole category',
    'faq' => 'FAQ',
    'faq_category' => 'FAQ category',
    'select_category' => 'Select a category',
    'provider' => 'Provider',
    'select_provider' => 'Select a provider',
    'content' => 'Content',
    'select_provider_first' => 'Select a provider first',
    'content_id' => 'Content ID',
    'placement' => 'Placement',
    'automatic' => 'Automatic',
    'manual_only' => 'Manual only',
    'order' => 'Order',
    'advanced_fallback' => 'Advanced fallback',
    'subtype' => 'Subtype',
    'subtype_help' => 'Subtype is normally detected from the selected provider item. Enter it manually only for a legacy/custom provider that does not expose it.',
    'save_association' => 'Save association',
    'current_faq_associations' => 'Current individual FAQ associations',
    'current_category_associations' => 'Current category associations',
    'filtered_content' => 'Filtered content:',
    'show_all_associations' => 'Show all associations',
    'relation_table_missing' => 'The FAQ 1.3.0 relation table is not installed yet. Run the plugin upgrade.',
    'category_relation_table_missing' => 'The FAQ category relation table is not installed yet.',
    'category' => 'Category',
    'action' => 'Action',
    'delete' => 'Delete',
    'category_association_saved' => 'Category association saved. New FAQs added to this category will be included automatically.',
    'association_saved' => 'Association saved.',
    'association_save_failed' => 'The association could not be saved. Check the selected FAQ/category and content.',
    'association_deleted' => 'Association deleted.',
    'update' => 'Update',
    'association_updated' => 'Association updated.',
    'association_update_failed' => 'The association could not be updated.',
    'select_content' => 'Select content',
    'loading' => 'Loading…',
    'no_selectable_content' => 'No selectable content',
    'enter_id_manually' => 'Enter ID manually…',
    'collection_unavailable' => 'Collection unavailable; enter the content ID manually.',
    'unable_load_collection' => 'Unable to load the provider collection; enter the content ID manually.',
    'manual_fallback' => 'Manual fallback: enter the content ID. Subtype remains optional.',
    'provider_no_item_info' => 'This provider does not expose Geeklog Item Info.',
    'provider_no_content' => 'The provider returned no selectable content.',
    'provider_selectable_count' => '%d selectable item(s).',
    'external_confirmation_required' => 'This article already contains an external FAQ/Q&A signal. Confirm the association explicitly before saving.',
    'confirm_external_faq' => 'If the selected article already contains an external FAQ/Q&A, I confirm that this managed association should still be added.',
    'topic_provider' => 'Geeklog topic',
    'topic_scope' => 'Topic scope',
    'topic_scope_both' => 'Topic + topic articles',
    'topic_scope_topic' => 'Topic only',
    'topic_scope_articles' => 'Topic articles only',
    'topic_scope_help' => 'For a Topic association, choose whether FAQs appear on the Topic page, are inherited by its Articles, or both.',
    'display_help_title' => 'How contextual FAQ display works',
    'display_help_intro' => 'Associations can be rendered automatically or placed manually inside the content.',
    'display_help_automatic' => 'FAQ renders at the native insertion point supported by the selected provider.',
    'display_help_manual' => 'No automatic output. Insert this autotag where the associated FAQ should appear:',
    'display_help_related' => 'Explicitly renders associations for a provider and content ID.',
    'display_help_embed' => 'Embeds one specific FAQ independently of associations.',
    'display_help_context_note' => '[faq-context] uses the current content provider and ID. On articles it also applies topic inheritance and duplicate/external-FAQ protection.'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'FAQ Coverage',
    'intro' => 'Review which content uses managed FAQs, topic-inherited FAQs, or FAQ already present in the content. Use the filters to find content with no FAQ, external FAQ signals, or associations to review.',
    'provider' => 'Provider',
    'faq_status' => 'FAQ status',
    'all' => 'All',
    'managed_faq_only' => 'Managed FAQ only',
    'external_faq_only' => 'External FAQ signal only',
    'managed_external' => 'Managed + external',
    'no_faq_detected' => 'No FAQ detected',
    'show' => 'Show',
    'provider_not_enumerable' => 'This provider does not expose an enumerable Item Info collection on this installation. FAQ will not query third-party plugin tables. Manual associations remain available.',
    'core_audit_label' => 'Core content audit:',
    'core_audit_help' => 'Articles and Static Pages are inspected read-only because their current Geeklog providers do not expose the required collection/content contract. External FAQ detection is an editorial signal, not proof.',
    'managed' => 'Managed',
    'external_signal' => 'External signal',
    'none' => 'None',
    'manage' => 'Manage',
    'edit' => 'Edit',
    'managed_relations' => 'Managed relations:',
    'managed_only' => 'Managed only',
    'external_only' => 'External only',
    'both' => 'Both',
    'content' => 'Content',
    'external_signals' => 'External signals',
    'action' => 'Action',
    'articles' => 'Articles',
    'topics' => 'Topics',
    'origin_direct' => 'Direct association',
    'origin_topic' => 'Inherited from topic',
    'topic_inheritance_blocked' => 'Topic inheritance blocked by external FAQ',
    'static_pages' => 'Static Pages',
    'videos' => 'Videos',
    'documents' => 'Documents',
    'maps' => 'Maps',
    'media_gallery' => 'Media Gallery',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'FAQPage microdata',
    'signal_question_answer_schema' => 'Question/Answer schema',
    'signal_details_summary' => 'Repeated details/summary Q&A',
    'signal_faq_heading' => 'FAQ heading'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'FAQ Plugin Import',
    'no_topics' => 'There are no FAQMAN topics to import.',
    'no_faqman' => 'The FAQMAN plugin is not installed.',
    'not_root' => 'You must be a member of the ROOT group in order to import FAQMAN topics.',
    'unknown' => 'Unknown import action: ',
    'imported' => '%d FAQs in %d Categories imported'
);


/* Geeklog Configuration API metadata */
$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'FAQ Configuration'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => 'Hide FAQs from What\'s New',
    'hidefaqmenu' => 'Hide FAQ menu item',
    'newfaqinterval' => 'New FAQ interval (seconds)',
    'no_hit_rights' => 'Rights excluded from hit counting',
    'contextual_enabled' => 'Enable contextual FAQs',
    'contextual_default_placement' => 'Default contextual placement',
    'structured_data' => 'Enable FAQ structured data',
    'coverage_limit' => 'Coverage page result limit',
    'default_permissions' => 'Default permissions'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'FAQ settings'
);

$LANG_tab['faq'] = array(
    'tab_general' => 'General',
    'tab_context' => 'Contextual FAQs',
    'tab_permissions' => 'Permissions'
);

$LANG_fs['faq'] = array(
    'fs_general' => 'General settings',
    'fs_context' => 'Contextual FAQ settings',
    'fs_permissions' => 'Default permissions'
);

$LANG_configselects['faq'][0] = array(
    'True' => 1,
    'False' => 0
);

$LANG_configselects['faq'][1] = array(
    'Automatic (provider item display point)' => 'automatic',
    'Manual only' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    'No access' => 0,
    'Read-Only' => 2,
    'Read-Write' => 3
);


$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => 'Hide FAQ entries from the Geeklog What\'s New block.',
    'hidefaqmenu' => 'Hide the FAQ item from the site menu while keeping public FAQ URLs available.',
    'newfaqinterval' => 'Number of seconds during which a recently updated FAQ is considered new.',
    'no_hit_rights' => 'Comma-separated FAQ rights whose users should not increment FAQ hit counters.',
    'contextual_enabled' => 'Allow FAQ associations to be rendered inside supported content providers such as articles, Documents, Maps and Videos.',
    'contextual_default_placement' => 'Automatic lets the content provider choose its native item-display position. Manual only disables automatic contextual output.',
    'structured_data' => 'Output FAQPage structured data on standalone FAQ pages when appropriate.',
    'coverage_limit' => 'Maximum number of provider items inspected and displayed on the Coverage administration page.',
    'default_permissions' => 'Default owner, group, member and anonymous permissions assigned to newly created FAQ content.'
);
