<?php

###############################################################################
# persian_utf-8.php
#
# This is the Persian language file for Geeklog faq plugin
# Special thanks to Mahdi Montazeri for his work on this project
#
# Copyright (C) 2018 geeklog.ir
# info AT mahdimontazeri DOT com
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
#
###############################################################################

$LANG_FAQ_COMMON = array(
    'FAQs' => 'پرسشگان ها',
    'FAQ' => 'پرسشگان',
    'no_new' => 'No recent new FAQs',
    'FAQ_cat_header' => $_CONF['site_name'] . ' پرسشگان',
    'Categories' => 'دسته بندی ها',
    'Question' => 'پرسش',
    'Hits' => 'بازدید',
    'Updated' => 'بروزرسانی شد',
    'Answer' => 'پاسخ',
    'no_autolink_faq' => '[FAQ ID "%s" does not exist or you do not have access to it]',
    'no_autolink_cat' => '[FAQ category "%s" does not exist or you do not have access to it]',
    'autolink_error' => '[error in "%s" FAQ link]'
);

/******************************************************************************
* for stats
******************************************************************************/
$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'به نظر می رسد که هیچ پرسشگانی در این سایت وجود ندارد یا هیچکس همواره روی یکی کلیک نکرده است.',
    'stats_summary' => 'دسته بندی های/ورودی های پرسشگان (بازدید) در سیستم',
    'Question' => 'پرسش',
    'Hits' => 'بازدید',
    'headline' => 'ده پرسشگان برتر',
);

/******************************************************************************
* for the search
******************************************************************************/
$LANG_FAQ_SEARCH = array(
 'FAQ' => 'پرسشگان',
 'category' => 'دسته بندی',
 'results' => 'نتایج پرسشگان',
 'title' => 'پرسش',
 'date' => 'بروزرسانی شد',
 'author' => 'نویسنده',
 'category' => 'دسته بندی',
 'hits' => 'بازدید'
);

/******************************************************************************
* Messages for COM_showMessage the submission form
******************************************************************************/

$PLG_faq_MESSAGE1 = "شما در حال تلاش برای دسترسی به یک دسته بندی پرسشگان می باشید که به آن دسترسی ندارید یا آن وجود ندارد. این تلاش ضبط شده است.";
$PLG_faq_MESSAGE2 = "شما در حال تلاش برای دسترسی به یک ورودی پرسشگان می باشید که به آن دسترسی ندارید یا آن وجود ندارد. این تلاش ضبط شده است.";
$PLG_faq_MESSAGE3 = 'پرسشگان با موفقیت حذف شده است.';
$PLG_faq_MESSAGE4 = 'پرسشگان با موفقیت ذخیره شده است.';
$PLG_faq_MESSAGE5 = "شما در حال تلاش برای انجام یک اقدام روی یک ورودی پرسشگان می باشید که دسترسی ندارید یا آن وجود ندارد. این تلاش ضبط شده است.";
$PLG_faq_MESSAGE6 = 'ارتقا افزونه پرسشگان موفق بود.';
$PLG_faq_MESSAGE7 = 'ارتقا ناموفق افزونه پرسشگان.';
/******************************************************************************
* admin
******************************************************************************/
$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'دسته بندی پرسشگان',
    'FAQ_Entry' => 'ورودی پرسشگان',
    'FAQ Entries' => 'ورودی های پرسشگان',
    'Edit' => 'ویرایش',
    'FAQ Editor' => 'ویرایشگر پرسشگان',
    'Cat Editor' => 'ویرایشگر دسته بندی پرسشگان',
    'Access Denied MSG' => 'با عرض پوزش، شما به صفحه مدیریت وب سایت دسترسی ندارید. لطفا توجه داشته باشید که همه تلاش ها برای دسترسی به ویژگی های غیر مجاز ضبط شده اند.',
    'delete' => 'حذف',
    'save' => 'ذخیره',
    'cancel' => 'لغو',
    'show' => 'نمایش',
    'accessdenied' => "شما در حال تلاش برای دسترسی به یک مورد پرسشگان می باشید که حق آن را ندارید. این تلاش ضبط شده است لطفا <a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php\">به صفحه مدیریت پرسشگان بازگردید</a>",
    'title' => 'عنوان',
    'description' => 'شرح',
    'question' => 'پرسش',
    'answer' => 'پاسخ',
    'id' => 'شناسه',
    'hits' => 'بازدید',
    'changed' => 'بروزرسانی شد',
    'category' => 'دسته بندی',
    'all_cat' => 'همه دسته بندی ها',
    'date_will_update' => '<b>توجه:</b> اگر ذخیره کنید، تاریخ بروزرسانی خواهد شد!',
    'reset_date' => 'بروزرسانی تاریخ را به <i>در حال حاضر</i> تغییر داد.',
    'save_rights_error' => 'شما نمی توانید با مجوز هایی که ندارید ذخیره کنید.',
    'missing_fields_faq' => 'باید پرسش، پاسخ و یک دسته بندی برای هر ورودی پرسشگان عرضه کنید.',
    'missing_fields_cat' => 'باید عنوان و یک شرح برای هر دسته بندی پرسشگان عرضه کنید.',
    'delete_note' => 'توجه: حذف این دسته بندی همه پرسشگان های مرتبط با این دسته بندی را حذف می کند.',
    'FAQ Plugin' => 'افزونه پرسشگان',
    'faqman_import' => 'افزونه مدیریت پرسشگان را نصب کرده اید (که مشابه این افزونه نمی باشد). اما می توانید داده ها را از افزونه مدیریت پرسشگان وارد کنید.',
    'cat instructions' => 'برای ایجاد یک دسته بندی پرسشگان روی "ایجاد جدید" کلیک کنید. برای ایجاد ورودی های پرسشگان برای دسته بندی پرسشگان خود روی "ویرایشگر پرسشگان" کلیک کنید.',
    'import' => 'وارد كردن',
    'access' => 'دسترسی'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'وارد کردن افزونه پرسشگان',
    'no_topics' => 'هیچ موضوع مدیریت پرسشگان برای وارد کردن وجود ندارد.',
    'no_faqman' => 'افزونه مدیریت پرسشگان نصب نشده است.',
    'not_root' => 'به منظور وارد کردن موضوعات مدیریت پرسشگان باید یک عضو گروه ریشه باشید.',
    'unknown' => 'اقدام وارد کردن ناشناخته: ',
    'imported' => '%d پرسشگان در %d دسته بندی وارد شد'
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
    'topic_provider' => 'Geeklog topic'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'FAQ Coverage',
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
