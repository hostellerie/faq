<?php

/******************************************************************************
* swedish.php
* This is the swedish language page for the Geeklog FAQ Plug-in!
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
    'no_new' => 'Inga nya FAQs',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'Kategorier',
    'Question' => 'Fråga',
    'Hits' => 'Klick',
    'Updated' => 'Uppdaterad',
    'Answer' => 'Svar',
    'no_autolink_faq' => '[FAQ ID "%s" existerar inte eller du har inte behörighet att läsa den]',
    'no_autolink_cat' => '[FAQ kategori "%s" existerar inte eller du har inte behörighet att läsa den]',
    'autolink_error' => '[fel i "%s" FAQ länk]'
);

/******************************************************************************
* for stats
******************************************************************************/
$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'Det verkar inte finnas några FAQs eller så har ingen läst någon FAQ.',
    'stats_summary' => 'FAQ Kategorier/Frågor (klick) i systemet',
    'Question' => 'Fråga',
    'Hits' => 'Klick',
    'headline' => 'Topp Tio FAQs',
);

/******************************************************************************
* for the search
******************************************************************************/
$LANG_FAQ_SEARCH = array(
 'FAQ' => 'FAQ',
 'results' => 'FAQ Resultat',
 'title' => 'Fråga',
 'date' => 'Uppdaterad',
 'author' => 'Författare',
 'category' => 'Kategori',
 'hits' => 'Klick'
);

/******************************************************************************
* Messages for COM_showMessage the submission form
******************************************************************************/

$PLG_faq_MESSAGE1 = "Du försöker accessa en FAQ kategori du inte har behörighet till eller som inte finns. Detta försök har loggats.";
$PLG_faq_MESSAGE2 = "Du försöker accessa en FAQ fråga du inte har behörighet till eller som inte finns. Detta försök har loggats.";
$PLG_faq_MESSAGE3 = 'FAQen har raderats.';
$PLG_faq_MESSAGE4 = 'FAQen har sparats.';
$PLG_faq_MESSAGE5 = "Du försöker utföra en handling på en FAQ du inte har behörighet till eller som inte existerar. Detta försök har loggats.";
$PLG_faq_MESSAGE6 = 'Uppdatering av FAQ plugin lyckad.';
$PLG_faq_MESSAGE7 = 'Uppdatering av FAQ plugin misslyckad.';

/******************************************************************************
* admin
******************************************************************************/
$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'FAQ Kategori',
    'FAQ_Entry' => 'FAQ Fråga',
    'FAQ Entries' => 'FAQ Frågor',
    'Edit' => 'Ändra',
    'FAQ Editor' => 'FAQ Editor',
    'Cat Editor' => 'FAQ Kategori Editor',
    'Access Denied MSG' => 'Du har inte behörighet till FAQ administrationen. Notera att alla sådana försök loggas.',
    'delete' => 'radera',
    'save' => 'spara',
    'cancel' => 'avbryt',
    'show' => 'visa',
    'accessdenied' => "Du föröker accessa en FAQ du inte har behörighet till. Detta försök har loggats. <a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php\">Gå tillbaka till FAQ administrationen</a>.",
    'title' => 'Titel',
    'description' => 'Beskrivning',
    'question' => 'Fråga',
    'answer' => 'Svar',
    'id' => 'ID',
    'hits' => 'Klick',
    'changed' => 'Uppdaterad',
    'category' => 'Kategori',
    'all_cat' => 'Alla Kategorier',
    'date_will_update' => '<b>OBS:</b> Datumet kommer uppdateras då du sparar!',
    'reset_date' => 'Uppdatera datumet till <i>nu</i>.',
    'save_rights_error' => 'Du kan inte spara med en behörighet du inte har.',
    'missing_fields_faq' => 'Du måste ange fråga, svar och kategori.',
    'missing_fields_cat' => 'Du måste ange titel och beskrivning för varje kategori.',
    'delete_note' => 'OBS: Om du raderar denna kategori så raderas ALLA FAQs som tillhör denna kategori.',
    'FAQ Plugin' => 'FAQ Plugin',
    'faqman_import' => 'Du har FAQMAN pluginen installerad (vilken icke skall förväxlas med denna plugin). Men du kan importera data från FAQMAN pluginen.',
    'cat instructions' => 'Click on "Create New" menu item to create a FAQ Category. Click on the "FAQ Editor" menu item to create FAQ entries for your FAQ Category.',
    'faq instructions' => 'Create and manage FAQ questions and answers here. Use the category filter to quickly find the FAQs you want to edit.',
    'import' => 'import',
    'access' => 'Rättigheter'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'FAQ Plugin Import',
    'no_topics' => 'Det finns inga FAQMAN topics att importera.',
    'no_faqman' => 'FAQMAN pluginen är inte installerad.',
    'not_root' => 'Du måste vara medlem i ROOT gruppen för att importera FAQMAN topics.',
    'unknown' => 'Okänd import metod: ',
    'imported' => '%d FAQs i %d Kategorier importerade'
);


/* FAQ 1.3.0 language-key compatibility.
 * Existing translations above are preserved; new keys fall back to English
 * until a native translation is supplied.
 */
$LANG_FAQ_COMMON += array(
    'permalink' => 'Permalink',
    'back_to' => 'Back to',
    'breadcrumb_aria' => 'FAQ breadcrumb',
    'actions_aria' => 'FAQ actions',
    'related_questions' => 'Related questions',
    'category_page_title' => 'FAQ Category: %s'
);

$LANG_FAQ_ADMIN += array(
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
    'contextual_enabled' => 'Allow FAQ associations to be rendered inside supported content providers.',
    'contextual_default_placement' => 'Automatic lets the content provider choose its native item-display position. Manual only disables automatic contextual output.',
    'structured_data' => 'Output FAQPage structured data on standalone FAQ pages when appropriate.',
    'coverage_limit' => 'Maximum number of provider items inspected and displayed on the Coverage administration page.',
    'default_permissions' => 'Default owner, group, member and anonymous permissions assigned to newly created FAQ content.'
);
