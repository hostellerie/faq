<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => 'よくある質問',
    'FAQ' => 'FAQ',
    'no_new' => '最近追加されたFAQはありません',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'カテゴリー',
    'Question' => '質問',
    'Hits' => '閲覧数',
    'Updated' => '更新',
    'Answer' => '回答',
    'permalink' => '固定リンク',
    'back_to' => '戻る',
    'breadcrumb_aria' => 'FAQパンくずリスト',
    'actions_aria' => 'FAQ操作',
    'related_questions' => '関連する質問',
    'category_page_title' => 'FAQカテゴリー: %s',
    'no_autolink_faq' => '[FAQ ID "%s" は存在しないか、アクセス権がありません]',
    'no_autolink_cat' => '[FAQカテゴリー "%s" は存在しないか、アクセス権がありません]',
    'autolink_error' => '[FAQリンク "%s" にエラーがあります]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'このサイトにはFAQがないか、まだ一度も閲覧されていないようです。',
    'stats_summary' => 'システム内のFAQカテゴリー/項目（閲覧数）',
    'Question' => '質問',
    'Hits' => '閲覧数',
    'headline' => '閲覧数上位10件のFAQ'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'FAQ検索結果',
    'title' => '質問',
    'date' => '更新',
    'author' => '投稿者',
    'category' => 'カテゴリー',
    'hits' => '閲覧数'
);

$PLG_faq_MESSAGE1 = 'アクセス権のない、または存在しないFAQカテゴリーにアクセスしようとしています。この試行は記録されました。';
$PLG_faq_MESSAGE2 = 'アクセス権のない、または存在しないFAQ項目にアクセスしようとしています。この試行は記録されました。';
$PLG_faq_MESSAGE3 = 'FAQを削除しました。';
$PLG_faq_MESSAGE4 = 'FAQを保存しました。';
$PLG_faq_MESSAGE5 = 'アクセス権のない、または存在しないFAQ項目に対して操作を実行しようとしています。この試行は記録されました。';
$PLG_faq_MESSAGE6 = 'FAQプラグインのアップグレードが完了しました。';
$PLG_faq_MESSAGE7 = 'FAQプラグインのアップグレードに失敗しました。';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'FAQカテゴリー',
    'FAQ_Entry' => 'FAQ項目',
    'FAQ Entries' => 'FAQ項目',
    'Edit' => '編集',
    'FAQ Editor' => 'FAQエディター',
    'Cat Editor' => 'FAQカテゴリーエディター',
    'Access Denied MSG' => 'FAQ管理ページへのアクセス権がありません。権限のない機能へのアクセス試行はすべて記録されます。',
    'delete' => '削除',
    'save' => '保存',
    'cancel' => 'キャンセル',
    'show' => '表示',
    'accessdenied' => "権限のないFAQ項目にアクセスしようとしています。この試行は記録されました。<a href="{$_CONF['site_admin_url']}/plugins/faq/index.php">FAQ管理画面に戻ってください</a>。",
    'title' => 'タイトル',
    'description' => '説明',
    'question' => '質問',
    'answer' => '回答',
    'id' => 'ID',
    'id_auto_help' => '読みやすいURLを作成するため、タイトルからIDが自動生成されます。初回保存前であれば調整できます。',
    'id_change_help' => 'このIDは公開URLの一部です。変更すると検索エンジンのインデックスや既存リンクに影響する場合があります。',
    'id_change_confirm' => 'IDと公開URLを変更することを確認します。',
    'id_change_required' => 'IDを変更するには明示的な確認が必要です。',
    'id_exists' => 'このIDはすでに使用されています。別のIDを選択してください。',
    'hits' => '閲覧数',
    'order' => '順序',
    'order_help' => 'カテゴリー内でのFAQの位置を制御します。小さい値ほど先に表示され、10、20、30のように設定すると後から挿入する余地を残せます。',
    'position' => '位置',
    'position_first' => '先頭',
    'position_last' => '最後',
    'position_after' => '次の後: %s',
    'position_help' => 'カテゴリー内でのFAQの位置を選択します。内部の順序は10刻みで自動的に再計算されます。',
    'changed' => '更新',
    'category' => 'カテゴリー',
    'all_cat' => 'すべてのカテゴリー',
    'date_will_update' => '<b>注意:</b> 保存すると日付が更新されます。',
    'reset_date' => '更新日時を<i>現在</i>に設定します。',
    'save_rights_error' => '所有していない権限では保存できません。',
    'missing_fields_faq' => '各FAQ項目には質問、回答、カテゴリーを指定する必要があります。',
    'missing_fields_cat' => '各FAQカテゴリーにはタイトルと説明を指定する必要があります。',
    'delete_note' => '注意: このカテゴリーを削除すると、関連するすべてのFAQも削除されます。',
    'FAQ Plugin' => 'FAQプラグイン',
    'faqman_import' => 'FAQMANプラグインがインストールされています（このプラグインとは別物です）。FAQMANからデータをインポートできます。',
    'cat instructions' => 'ここでFAQカテゴリーを作成・管理します。「質問」タブで各カテゴリーに属する質問を管理できます。',
    'faq instructions' => 'ここでFAQの質問と回答を作成・管理します。カテゴリーのフィルターを使うと、編集したいFAQをすばやく見つけられます。',
    'import' => 'インポート',
    'access' => 'アクセス',
    'editor_mode' => 'エディターモード',
    'visual_editor' => 'ビジュアルエディター',
    'html_source' => 'HTMLソース',
    'insert_media' => 'メディアを挿入',
    'questions' => '質問',
    'categories' => 'カテゴリー',
    'associations' => '関連付け',
    'coverage' => 'カバレッジ',
    'configuration' => '設定',
    'new_question' => '新しい質問',
    'new_category' => '新しいカテゴリー',
    'administration_aria' => 'FAQ管理'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'FAQの関連付け',
    'intro' => '個別のFAQまたはFAQカテゴリー全体を、Geeklogプロバイダーが提供するコンテンツに関連付けます。カテゴリーの関連付けは動的で、そのカテゴリーに新しく追加されたFAQも自動的に含まれます。可能な場合はプロバイダーと選択可能なコンテンツを自動検出し、手動ID入力は代替手段として利用できます。',
    'add_association' => '関連付けを追加',
    'association_source' => '関連付け元',
    'individual_faq' => '個別FAQ',
    'whole_category' => 'カテゴリー全体',
    'faq' => 'FAQ',
    'faq_category' => 'FAQカテゴリー',
    'select_category' => 'カテゴリーを選択',
    'provider' => 'プロバイダー',
    'select_provider' => 'プロバイダーを選択',
    'content' => 'コンテンツ',
    'select_provider_first' => '先にプロバイダーを選択してください',
    'content_id' => 'コンテンツID',
    'placement' => '配置',
    'automatic' => '自動',
    'manual_only' => '手動のみ',
    'order' => '順序',
    'advanced_fallback' => '高度な代替手段',
    'subtype' => 'サブタイプ',
    'subtype_help' => 'サブタイプは通常、選択したプロバイダー項目から検出されます。サブタイプを公開しない旧式またはカスタムプロバイダーの場合のみ手動で入力してください。',
    'save_association' => '関連付けを保存',
    'current_faq_associations' => '現在の個別FAQ関連付け',
    'current_category_associations' => '現在のカテゴリー関連付け',
    'filtered_content' => '絞り込み済みコンテンツ:',
    'show_all_associations' => 'すべての関連付けを表示',
    'relation_table_missing' => 'FAQ 1.3.0の関連テーブルがまだインストールされていません。プラグインのアップグレードを実行してください。',
    'category_relation_table_missing' => 'FAQカテゴリーの関連テーブルがまだインストールされていません。',
    'category' => 'カテゴリー',
    'action' => '操作',
    'delete' => '削除',
    'category_association_saved' => 'カテゴリーの関連付けを保存しました。このカテゴリーに追加される新しいFAQは自動的に含まれます。',
    'association_saved' => '関連付けを保存しました。',
    'association_save_failed' => '関連付けを保存できませんでした。選択したFAQ/カテゴリーとコンテンツを確認してください。',
    'association_deleted' => '関連付けを削除しました。',
    'update' => '更新',
    'association_updated' => '関連付けを更新しました。',
    'association_update_failed' => '関連付けを更新できませんでした。',
    'select_content' => 'コンテンツを選択',
    'loading' => '読み込み中…',
    'no_selectable_content' => '選択可能なコンテンツがありません',
    'enter_id_manually' => 'IDを手動で入力…',
    'collection_unavailable' => 'コレクションを利用できません。コンテンツIDを手動で入力してください。',
    'unable_load_collection' => 'プロバイダーのコレクションを読み込めません。コンテンツIDを手動で入力してください。',
    'manual_fallback' => '手動の代替手段: コンテンツIDを入力してください。サブタイプは任意です。',
    'provider_no_item_info' => 'このプロバイダーはGeeklog Item Infoを公開していません。',
    'provider_no_content' => 'プロバイダーから選択可能なコンテンツが返されませんでした。',
    'provider_selectable_count' => '選択可能な項目: %d件。',
    'external_confirmation_required' => 'この記事にはすでに外部のFAQ/Q&Aシグナルがあります。保存前に関連付けを明示的に確認してください。',
    'confirm_external_faq' => '選択した記事にすでに外部FAQ/Q&Aがある場合でも、この管理された関連付けを追加することを確認します。',
    'topic_provider' => 'Geeklogトピック',
    'topic_scope' => 'トピックの適用範囲',
    'topic_scope_both' => 'トピック + トピックの記事',
    'topic_scope_topic' => 'トピックのみ',
    'topic_scope_articles' => 'トピックの記事のみ',
    'topic_scope_help' => 'トピックへの関連付けでは、FAQをトピックページに表示するか、その記事に継承するか、またはその両方かを選択します。',
    'display_help_title' => 'コンテキストFAQ表示の仕組み',
    'display_help_intro' => '関連付けは自動表示することも、コンテンツ内に手動配置することもできます。',
    'display_help_automatic' => '選択したプロバイダーが対応する標準の挿入位置にFAQを表示します。',
    'display_help_manual' => '自動出力はありません。関連付けたFAQを表示したい場所にこのオートタグを挿入してください:',
    'display_help_related' => 'プロバイダーとコンテンツIDに対する関連付けを明示的に表示します。',
    'display_help_embed' => '関連付けとは無関係に特定のFAQを埋め込みます。',
    'display_help_context_note' => '[faq-context] は現在のコンテンツプロバイダーとIDを使用します。記事ではトピック継承と重複/外部FAQの保護も適用されます。'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'FAQカバレッジ',
    'intro' => '管理されたFAQ、トピックから継承されたFAQ、またはコンテンツ内にすでに存在するFAQを使用しているコンテンツを確認します。フィルターを使って、FAQのないコンテンツ、外部FAQシグナル、確認が必要な関連付けを探せます。',
    'provider' => 'プロバイダー',
    'faq_status' => 'FAQの状態',
    'all' => 'すべて',
    'managed_faq_only' => '管理FAQのみ',
    'external_faq_only' => '外部FAQシグナルのみ',
    'managed_external' => '管理 + 外部',
    'no_faq_detected' => 'FAQは検出されませんでした',
    'show' => '表示',
    'provider_not_enumerable' => 'このプロバイダーは、この環境で列挙可能なItem Infoコレクションを公開していません。FAQはサードパーティープラグインのテーブルを直接参照しません。手動の関連付けは引き続き利用できます。',
    'core_audit_label' => 'コアコンテンツの監査:',
    'core_audit_help' => '現在のGeeklogプロバイダーが必要なコレクション/コンテンツ契約を公開していないため、記事と静的ページは読み取り専用で検査されます。外部FAQの検出は編集上のシグナルであり、確定的な証拠ではありません。',
    'managed' => '管理済み',
    'external_signal' => '外部シグナル',
    'none' => 'なし',
    'manage' => '管理',
    'edit' => '編集',
    'managed_relations' => '管理された関連:',
    'managed_only' => '管理のみ',
    'external_only' => '外部のみ',
    'both' => '両方',
    'content' => 'コンテンツ',
    'external_signals' => '外部シグナル',
    'action' => '操作',
    'articles' => '記事',
    'topics' => 'トピック',
    'origin_direct' => '直接の関連付け',
    'origin_topic' => 'トピックから継承',
    'topic_inheritance_blocked' => '外部FAQによりトピック継承がブロックされています',
    'static_pages' => '静的ページ',
    'videos' => '動画',
    'documents' => 'ドキュメント',
    'maps' => 'マップ',
    'media_gallery' => 'メディアギャラリー',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'FAQPageマイクロデータ',
    'signal_question_answer_schema' => 'Question/Answerスキーマ',
    'signal_details_summary' => 'details/summaryによる繰り返しQ&A',
    'signal_faq_heading' => 'FAQ見出し'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'FAQプラグインのインポート',
    'no_topics' => 'インポートするFAQMANトピックがありません。',
    'no_faqman' => 'FAQMANプラグインがインストールされていません。',
    'not_root' => 'FAQMANトピックをインポートするにはROOTグループのメンバーである必要があります。',
    'unknown' => '不明なインポート操作: ',
    'imported' => '%d件のFAQを%d個のカテゴリーにインポートしました'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'FAQ設定'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => '「新着情報」からFAQを隠す',
    'hidefaqmenu' => 'FAQメニュー項目を隠す',
    'newfaqinterval' => '新着FAQの期間（秒）',
    'no_hit_rights' => '閲覧数に含めない権限',
    'contextual_enabled' => 'コンテキストFAQを有効化',
    'contextual_default_placement' => 'コンテキスト表示の既定位置',
    'structured_data' => 'FAQ構造化データを有効化',
    'coverage_limit' => 'カバレッジページの結果上限',
    'default_permissions' => '既定の権限'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'FAQ設定'
);

$LANG_tab['faq'] = array(
    'tab_general' => '一般',
    'tab_context' => 'コンテキストFAQ',
    'tab_permissions' => '権限'
);

$LANG_fs['faq'] = array(
    'fs_general' => '一般設定',
    'fs_context' => 'コンテキストFAQ設定',
    'fs_permissions' => '既定の権限'
);

$LANG_configselects['faq'][0] = array(
    '有効' => 1,
    '無効' => 0
);

$LANG_configselects['faq'][1] = array(
    '自動（プロバイダーの項目表示位置）' => 'automatic',
    '手動のみ' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    'アクセス不可' => 0,
    '読み取り専用' => 2,
    '読み書き可能' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => 'FAQ項目をGeeklogの「新着情報」ブロックから隠します。',
    'hidefaqmenu' => '公開FAQのURLを利用可能なまま、サイトメニューからFAQ項目を隠します。',
    'newfaqinterval' => '最近更新されたFAQを新着とみなす秒数です。',
    'no_hit_rights' => 'FAQの閲覧数を増やさないユーザー権限をカンマ区切りで指定します。',
    'contextual_enabled' => '記事、ドキュメント、マップ、動画など、対応するコンテンツプロバイダー内でFAQの関連付けを表示できるようにします。',
    'contextual_default_placement' => '自動ではコンテンツプロバイダーが標準の表示位置を選択します。手動のみでは自動のコンテキスト出力を無効にします。',
    'structured_data' => '適切な場合、単独のFAQページにFAQPage構造化データを出力します。',
    'coverage_limit' => 'カバレッジ管理ページで検査・表示するプロバイダー項目の最大数です。',
    'default_permissions' => '新しいFAQコンテンツに割り当てる所有者、グループ、メンバー、匿名ユーザーの既定権限です。'
);
