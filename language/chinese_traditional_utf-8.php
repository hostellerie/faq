<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => '常見問題',
    'FAQ' => 'FAQ',
    'no_new' => '最近沒有新的常見問題',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => '分類',
    'Question' => '問題',
    'Hits' => '瀏覽次數',
    'Updated' => '已更新',
    'Answer' => '答案',
    'permalink' => '永久連結',
    'back_to' => '返回',
    'breadcrumb_aria' => 'FAQ 麵包屑導覽',
    'actions_aria' => 'FAQ 操作',
    'related_questions' => '相關問題',
    'category_page_title' => 'FAQ 分類：%s',
    'no_autolink_faq' => '[FAQ ID "%s" 不存在或您無權存取]',
    'no_autolink_cat' => '[FAQ 分類 "%s" 不存在或您無權存取]',
    'autolink_error' => '[FAQ 鏈接 "%s" 中存在錯誤]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => '此站点似乎沒有 FAQ，或者尚無人檢視過任何 FAQ。',
    'stats_summary' => '系统中的 FAQ 分類/項目（瀏覽次數）',
    'Question' => '問題',
    'Hits' => '瀏覽次數',
    'headline' => '瀏覽次數最多的十個 FAQ'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'FAQ 搜尋結果',
    'title' => '問題',
    'date' => '已更新',
    'author' => '作者',
    'category' => '分類',
    'hits' => '瀏覽次數'
);

$PLG_faq_MESSAGE1 = '您正在嘗試訪問無權存取或不存在的 FAQ 分類。此嘗試已被記錄。';
$PLG_faq_MESSAGE2 = '您正在嘗試訪問無權存取或不存在的 FAQ 項目。此嘗試已被記錄。';
$PLG_faq_MESSAGE3 = 'FAQ 已成功刪除。';
$PLG_faq_MESSAGE4 = 'FAQ 已成功儲存。';
$PLG_faq_MESSAGE5 = '您正在嘗試对無權存取或不存在的 FAQ 項目执行操作。此嘗試已被記錄。';
$PLG_faq_MESSAGE6 = 'FAQ 外掛程式升級成功。';
$PLG_faq_MESSAGE7 = 'FAQ 外掛程式升級失败。';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'FAQ 分類',
    'FAQ_Entry' => 'FAQ 項目',
    'FAQ Entries' => 'FAQ 項目',
    'Edit' => '編輯',
    'FAQ Editor' => 'FAQ 編輯器',
    'Cat Editor' => 'FAQ 分類編輯器',
    'Access Denied MSG' => '抱歉，您無權存取 FAQ 管理頁面。所有未經授權的功能訪問嘗試都会被記錄。',
    'delete' => '刪除',
    'save' => '儲存',
    'cancel' => '取消',
    'show' => '顯示',
    'accessdenied' => "您正在嘗試訪問無權存取的 FAQ 項目。此嘗試已被記錄。请<a href="{$_CONF['site_admin_url']}/plugins/faq/index.php">返回 FAQ 管理頁面</a>。",
    'title' => '標題',
    'description' => '說明',
    'question' => '問題',
    'answer' => '答案',
    'id' => 'ID',
    'id_auto_help' => 'ID 会根據標題自動生成，以建立易讀的 URL。首次儲存前可以对其進行调整。',
    'id_change_help' => '此 ID 是公開 URL 的一部分。變更它可能会影響搜尋引擎索引和現有連結。',
    'id_change_confirm' => '我確認要變更 ID 和公開 URL。',
    'id_change_required' => '變更 ID 需要明確確認。',
    'id_exists' => '此 ID 已被使用。请選擇其他 ID。',
    'hits' => '瀏覽次數',
    'order' => '順序',
    'order_help' => '控制此 FAQ 在其分類中的位置。較小的值優先顯示；使用 10、20、30 等值可為之後插入内容留出空間。',
    'position' => '位置',
    'position_first' => '第一項',
    'position_last' => '最後一項',
    'position_after' => '位於以下項目之後：%s',
    'position_help' => '選擇此 FAQ 在其分類中的位置。内部順序会以 10 為步长自動重新計算。',
    'changed' => '已更新',
    'category' => '分類',
    'all_cat' => '所有分類',
    'date_will_update' => '<b>注意：</b>儲存時日期將更新！',
    'reset_date' => '將修改日期更新為<i>現在</i>。',
    'save_rights_error' => '您不能使用自己不具备的權限儲存。',
    'missing_fields_faq' => '每個 FAQ 項目都必须提供問題、答案和分類。',
    'missing_fields_cat' => '每個 FAQ 分類都必须提供標題和說明。',
    'delete_note' => '注意：刪除此分類会同時刪除與該分類關聯的所有 FAQ。',
    'FAQ Plugin' => 'FAQ 插件',
    'faqman_import' => '您已安装 FAQMAN 插件（它與本插件不同）。可以從 FAQMAN 插件匯入數據。',
    'cat instructions' => '在此建立和管理 FAQ 分類。使用“問題”分頁管理各分類中的問題。',
    'faq instructions' => '在此建立和管理 FAQ 問題與答案。使用分類篩選器可快速找到要編輯的 FAQ。',
    'import' => '匯入',
    'access' => '訪問權限',
    'editor_mode' => '編輯器模式',
    'visual_editor' => '視覺化編輯器',
    'html_source' => 'HTML 原始碼',
    'insert_media' => '插入媒體',
    'questions' => '問題',
    'categories' => '分類',
    'associations' => '關聯',
    'coverage' => '涵蓋情況',
    'configuration' => '設定',
    'new_question' => '新增問題',
    'new_category' => '新增分類',
    'administration_aria' => 'FAQ 管理'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'FAQ 關聯',
    'intro' => '將單個 FAQ 或整個 FAQ 分類與 Geeklog 提供者公開的内容關聯。分類關聯是動態的：之後添加到該分類的新 FAQ 会自動包含。系统会在可能時自動發現提供者和可選內容；手動輸入 ID 仍可作為備用方式。',
    'add_association' => '添加關聯',
    'association_source' => '關聯来源',
    'individual_faq' => '單個 FAQ',
    'whole_category' => '整個分類',
    'faq' => 'FAQ',
    'faq_category' => 'FAQ 分類',
    'select_category' => '選擇分類',
    'provider' => '提供者',
    'select_provider' => '選擇提供者',
    'content' => '内容',
    'select_provider_first' => '请先選擇提供者',
    'content_id' => '內容 ID',
    'placement' => '放置位置',
    'automatic' => '自動',
    'manual_only' => '僅手動',
    'order' => '順序',
    'advanced_fallback' => '進階備用方式',
    'subtype' => '子類型',
    'subtype_help' => '子類型通常会從所選提供者項目中偵測。僅當舊版或自訂提供者未公開子類型時才手動輸入。',
    'save_association' => '儲存關聯',
    'current_faq_associations' => '目前單個 FAQ 關聯',
    'current_category_associations' => '目前分類關聯',
    'filtered_content' => '篩選後的內容：',
    'show_all_associations' => '顯示所有關聯',
    'relation_table_missing' => 'FAQ 1.3.0 關聯表尚未安裝。请執行外掛程式升級。',
    'category_relation_table_missing' => 'FAQ 分類關聯表尚未安裝。',
    'category' => '分類',
    'action' => '操作',
    'delete' => '刪除',
    'category_association_saved' => '分類關聯已儲存。之後添加到此分類的新 FAQ 將自動包含。',
    'association_saved' => '關聯已儲存。',
    'association_save_failed' => '無法儲存關聯。请檢查所選 FAQ/分類和内容。',
    'association_deleted' => '關聯已刪除。',
    'update' => '更新',
    'association_updated' => '關聯已更新。',
    'association_update_failed' => '無法更新關聯。',
    'select_content' => '選擇内容',
    'loading' => '正在載入…',
    'no_selectable_content' => '沒有可選內容',
    'enter_id_manually' => '手動輸入 ID…',
    'collection_unavailable' => '集合無法使用；请手動輸入內容 ID。',
    'unable_load_collection' => '無法載入提供者集合；请手動輸入內容 ID。',
    'manual_fallback' => '手動備用方式：輸入內容 ID。子類型仍為可選項。',
    'provider_no_item_info' => '此提供者未公開 Geeklog Item Info。',
    'provider_no_content' => '提供者未返回可選內容。',
    'provider_selectable_count' => '%d 個可選項目。',
    'external_confirmation_required' => '此文章已包含外部 FAQ/Q&A 訊號。儲存前请明確確認此關聯。',
    'confirm_external_faq' => '如果所選文章已包含外部 FAQ/Q&A，我確認仍應添加此受管理關聯。',
    'topic_provider' => 'Geeklog 主題',
    'topic_scope' => '主題範圍',
    'topic_scope_both' => '主題 + 主題文章',
    'topic_scope_topic' => '僅主題',
    'topic_scope_articles' => '僅主題文章',
    'topic_scope_help' => '对于主題關聯，请選擇 FAQ 是顯示在主題頁面、由該主題的文章繼承，還是兩者都使用。',
    'display_help_title' => '上下文 FAQ 顯示方式',
    'display_help_intro' => '關聯可以自動呈現，也可以手動放置在内容中。',
    'display_help_automatic' => 'FAQ 会顯示在所選提供者支援的原生插入位置。',
    'display_help_manual' => '不自動輸出。请在希望顯示關聯 FAQ 的位置插入此自動標籤：',
    'display_help_related' => '明确呈現指定提供者和內容 ID 的關聯。',
    'display_help_embed' => '獨立于關聯嵌入某個特定 FAQ。',
    'display_help_context_note' => '[faq-context] 使用目前内容提供者和 ID。在文章中還会應用主題繼承以及重複/外部 FAQ 保护。'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'FAQ 涵蓋情況',
    'intro' => '檢視哪些内容使用受管理 FAQ、從主題繼承的 FAQ，或内容中已存在的 FAQ。使用篩選器可查找沒有 FAQ 的内容、外部 FAQ 訊號或需要檢查的關聯。',
    'provider' => '提供者',
    'faq_status' => 'FAQ 状態',
    'all' => '全部',
    'managed_faq_only' => '僅受管理 FAQ',
    'external_faq_only' => '僅外部 FAQ 訊號',
    'managed_external' => '受管理 + 外部',
    'no_faq_detected' => '未偵測到 FAQ',
    'show' => '顯示',
    'provider_not_enumerable' => '此提供者在目前安装中未公開可枚举的 Item Info 集合。FAQ 不会查询第三方插件表。手動關聯仍可使用。',
    'core_audit_label' => '核心內容稽核：',
    'core_audit_help' => '文章和靜態頁面以唯讀方式檢查，因為目前 Geeklog 提供者未公開所需的集合/内容契约。外部 FAQ 偵測只是編輯提示，並非確定證據。',
    'managed' => '受管理',
    'external_signal' => '外部訊號',
    'none' => '無',
    'manage' => '管理',
    'edit' => '編輯',
    'managed_relations' => '受管理關系：',
    'managed_only' => '僅受管理',
    'external_only' => '僅外部',
    'both' => '兩者',
    'content' => '内容',
    'external_signals' => '外部訊號',
    'action' => '操作',
    'articles' => '文章',
    'topics' => '主題',
    'origin_direct' => '直接關聯',
    'origin_topic' => '從主題繼承',
    'topic_inheritance_blocked' => '主題繼承被外部 FAQ 阻止',
    'static_pages' => '靜態頁面',
    'videos' => '影片',
    'documents' => '文件',
    'maps' => '地圖',
    'media_gallery' => '媒體圖庫',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'FAQPage 微資料',
    'signal_question_answer_schema' => 'Question/Answer 結構描述',
    'signal_details_summary' => '重複的 details/summary 問答',
    'signal_faq_heading' => 'FAQ 標題'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'FAQ 插件匯入',
    'no_topics' => '沒有可匯入的 FAQMAN 主題。',
    'no_faqman' => 'FAQMAN 插件未安装。',
    'not_root' => '必须是 ROOT 组成员才能匯入 FAQMAN 主題。',
    'unknown' => '未知匯入操作：',
    'imported' => '已將 %d 個 FAQ 匯入 %d 個分類'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'FAQ 設定'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => '從“最新內容”中隱藏 FAQ',
    'hidefaqmenu' => '隱藏 FAQ 菜單項',
    'newfaqinterval' => '新 FAQ 時間間隔（秒）',
    'no_hit_rights' => '不計入瀏覽次數的權限',
    'contextual_enabled' => '啟用上下文 FAQ',
    'contextual_default_placement' => '預設上下文放置位置',
    'structured_data' => '啟用 FAQ 結構化資料',
    'coverage_limit' => '覆蓋頁面結果上限',
    'default_permissions' => '預設權限'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'FAQ 設定'
);

$LANG_tab['faq'] = array(
    'tab_general' => '一般',
    'tab_context' => '上下文 FAQ',
    'tab_permissions' => '權限'
);

$LANG_fs['faq'] = array(
    'fs_general' => '一般設定',
    'fs_context' => '上下文 FAQ 設定',
    'fs_permissions' => '預設權限'
);

$LANG_configselects['faq'][0] = array(
    '是' => 1,
    '否' => 0
);

$LANG_configselects['faq'][1] = array(
    '自動（提供者項目顯示位置）' => 'automatic',
    '僅手動' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    '無訪問權限' => 0,
    '唯讀' => 2,
    '讀寫' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => '在 Geeklog 的“最新內容”区块中隱藏 FAQ 項目。',
    'hidefaqmenu' => '從站点菜單中隱藏 FAQ 項，同時保留公開 FAQ URL。',
    'newfaqinterval' => '最近更新的 FAQ 被視為新的持续秒數。',
    'no_hit_rights' => '以逗號分隔的 FAQ 權限列表，擁有這些權限的使用者不会增加 FAQ 瀏覽計數。',
    'contextual_enabled' => '允许在受支援的内容提供者中顯示 FAQ 關聯，例如文章、文件、地圖和影片。',
    'contextual_default_placement' => '自動模式允许内容提供者選擇其原生顯示位置。僅手動模式会禁用自動上下文輸出。',
    'structured_data' => '在适當情况下，在獨立 FAQ 頁面輸出 FAQPage 結構化資料。',
    'coverage_limit' => '“涵蓋情況”管理頁面中檢查並顯示的提供者項目最大數量。',
    'default_permissions' => '指派給新 FAQ 内容的預設擁有者、组、成员和匿名使用者權限。'
);
