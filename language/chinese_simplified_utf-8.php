<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => '常见问题',
    'FAQ' => 'FAQ',
    'no_new' => '最近没有新的常见问题',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => '分类',
    'Question' => '问题',
    'Hits' => '浏览次数',
    'Updated' => '已更新',
    'Answer' => '答案',
    'permalink' => '永久链接',
    'back_to' => '返回',
    'breadcrumb_aria' => 'FAQ 面包屑导航',
    'actions_aria' => 'FAQ 操作',
    'related_questions' => '相关问题',
    'category_page_title' => 'FAQ 分类：%s',
    'no_autolink_faq' => '[FAQ ID "%s" 不存在或您无权访问]',
    'no_autolink_cat' => '[FAQ 分类 "%s" 不存在或您无权访问]',
    'autolink_error' => '[FAQ 链接 "%s" 中存在错误]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => '此站点似乎没有 FAQ，或者尚无人查看过任何 FAQ。',
    'stats_summary' => '系统中的 FAQ 分类/条目（浏览次数）',
    'Question' => '问题',
    'Hits' => '浏览次数',
    'headline' => '浏览次数最多的十个 FAQ'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'FAQ 搜索结果',
    'title' => '问题',
    'date' => '已更新',
    'author' => '作者',
    'category' => '分类',
    'hits' => '浏览次数'
);

$PLG_faq_MESSAGE1 = '您正在尝试访问无权访问或不存在的 FAQ 分类。此尝试已被记录。';
$PLG_faq_MESSAGE2 = '您正在尝试访问无权访问或不存在的 FAQ 条目。此尝试已被记录。';
$PLG_faq_MESSAGE3 = 'FAQ 已成功删除。';
$PLG_faq_MESSAGE4 = 'FAQ 已成功保存。';
$PLG_faq_MESSAGE5 = '您正在尝试对无权访问或不存在的 FAQ 条目执行操作。此尝试已被记录。';
$PLG_faq_MESSAGE6 = 'FAQ 插件升级成功。';
$PLG_faq_MESSAGE7 = 'FAQ 插件升级失败。';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'FAQ 分类',
    'FAQ_Entry' => 'FAQ 条目',
    'FAQ Entries' => 'FAQ 条目',
    'Edit' => '编辑',
    'FAQ Editor' => 'FAQ 编辑器',
    'Cat Editor' => 'FAQ 分类编辑器',
    'Access Denied MSG' => '抱歉，您无权访问 FAQ 管理页面。所有未经授权的功能访问尝试都会被记录。',
    'delete' => '删除',
    'save' => '保存',
    'cancel' => '取消',
    'show' => '显示',
    'accessdenied' => "您正在尝试访问无权访问的 FAQ 项目。此尝试已被记录。请<a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php\">返回 FAQ 管理页面</a>。",
    'title' => '标题',
    'description' => '说明',
    'question' => '问题',
    'answer' => '答案',
    'id' => 'ID',
    'id_auto_help' => 'ID 会根据标题自动生成，以创建易读的 URL。首次保存前可以对其进行调整。',
    'id_change_help' => '此 ID 是公开 URL 的一部分。更改它可能会影响搜索引擎索引和现有链接。',
    'id_change_confirm' => '我确认要更改 ID 和公开 URL。',
    'id_change_required' => '更改 ID 需要明确确认。',
    'id_exists' => '此 ID 已被使用。请选择其他 ID。',
    'hits' => '浏览次数',
    'order' => '顺序',
    'order_help' => '控制此 FAQ 在其分类中的位置。较小的值优先显示；使用 10、20、30 等值可为以后插入内容留出空间。',
    'position' => '位置',
    'position_first' => '第一项',
    'position_last' => '最后一项',
    'position_after' => '位于以下项目之后：%s',
    'position_help' => '选择此 FAQ 在其分类中的位置。内部顺序会以 10 为步长自动重新计算。',
    'changed' => '已更新',
    'category' => '分类',
    'all_cat' => '所有分类',
    'date_will_update' => '<b>注意：</b>保存时日期将更新！',
    'reset_date' => '将修改日期更新为<i>现在</i>。',
    'save_rights_error' => '您不能使用自己不具备的权限保存。',
    'missing_fields_faq' => '每个 FAQ 条目都必须提供问题、答案和分类。',
    'missing_fields_cat' => '每个 FAQ 分类都必须提供标题和说明。',
    'delete_note' => '注意：删除此分类会同时删除与该分类关联的所有 FAQ。',
    'FAQ Plugin' => 'FAQ 插件',
    'faqman_import' => '您已安装 FAQMAN 插件（它与本插件不同）。可以从 FAQMAN 插件导入数据。',
    'cat instructions' => '在此创建和管理 FAQ 分类。使用“问题”选项卡管理各分类中的问题。',
    'faq instructions' => '在此创建和管理 FAQ 问题与答案。使用分类筛选器可快速找到要编辑的 FAQ。',
    'import' => '导入',
    'access' => '访问权限',
    'editor_mode' => '编辑器模式',
    'visual_editor' => '可视化编辑器',
    'html_source' => 'HTML 源代码',
    'insert_media' => '插入媒体',
    'questions' => '问题',
    'categories' => '分类',
    'associations' => '关联',
    'coverage' => '覆盖情况',
    'configuration' => '配置',
    'new_question' => '新建问题',
    'new_category' => '新建分类',
    'administration_aria' => 'FAQ 管理'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'FAQ 关联',
    'intro' => '将单个 FAQ 或整个 FAQ 分类与 Geeklog 提供器公开的内容关联。分类关联是动态的：之后添加到该分类的新 FAQ 会自动包含。系统会在可能时自动发现提供器和可选内容；手动输入 ID 仍可作为备用方式。',
    'add_association' => '添加关联',
    'association_source' => '关联来源',
    'individual_faq' => '单个 FAQ',
    'whole_category' => '整个分类',
    'faq' => 'FAQ',
    'faq_category' => 'FAQ 分类',
    'select_category' => '选择分类',
    'provider' => '提供器',
    'select_provider' => '选择提供器',
    'content' => '内容',
    'select_provider_first' => '请先选择提供器',
    'content_id' => '内容 ID',
    'placement' => '放置位置',
    'automatic' => '自动',
    'manual_only' => '仅手动',
    'order' => '顺序',
    'advanced_fallback' => '高级备用方式',
    'subtype' => '子类型',
    'subtype_help' => '子类型通常会从所选提供器项目中检测。仅当旧版或自定义提供器未公开子类型时才手动输入。',
    'save_association' => '保存关联',
    'current_faq_associations' => '当前单个 FAQ 关联',
    'current_category_associations' => '当前分类关联',
    'filtered_content' => '筛选后的内容：',
    'show_all_associations' => '显示所有关联',
    'relation_table_missing' => 'FAQ 1.3.0 关联表尚未安装。请运行插件升级。',
    'category_relation_table_missing' => 'FAQ 分类关联表尚未安装。',
    'category' => '分类',
    'action' => '操作',
    'delete' => '删除',
    'category_association_saved' => '分类关联已保存。之后添加到此分类的新 FAQ 将自动包含。',
    'association_saved' => '关联已保存。',
    'association_save_failed' => '无法保存关联。请检查所选 FAQ/分类和内容。',
    'association_deleted' => '关联已删除。',
    'update' => '更新',
    'association_updated' => '关联已更新。',
    'association_update_failed' => '无法更新关联。',
    'select_content' => '选择内容',
    'loading' => '正在加载…',
    'no_selectable_content' => '没有可选内容',
    'enter_id_manually' => '手动输入 ID…',
    'collection_unavailable' => '集合不可用；请手动输入内容 ID。',
    'unable_load_collection' => '无法加载提供器集合；请手动输入内容 ID。',
    'manual_fallback' => '手动备用方式：输入内容 ID。子类型仍为可选项。',
    'provider_no_item_info' => '此提供器未公开 Geeklog Item Info。',
    'provider_no_content' => '提供器未返回可选内容。',
    'provider_selectable_count' => '%d 个可选项目。',
    'external_confirmation_required' => '此文章已包含外部 FAQ/Q&A 信号。保存前请明确确认此关联。',
    'confirm_external_faq' => '如果所选文章已包含外部 FAQ/Q&A，我确认仍应添加此受管理关联。',
    'topic_provider' => 'Geeklog 主题',
    'topic_scope' => '主题范围',
    'topic_scope_both' => '主题 + 主题文章',
    'topic_scope_topic' => '仅主题',
    'topic_scope_articles' => '仅主题文章',
    'topic_scope_help' => '对于主题关联，请选择 FAQ 是显示在主题页面、由该主题的文章继承，还是两者都使用。',
    'display_help_title' => '上下文 FAQ 显示方式',
    'display_help_intro' => '关联可以自动呈现，也可以手动放置在内容中。',
    'display_help_automatic' => 'FAQ 会显示在所选提供器支持的原生插入位置。',
    'display_help_manual' => '不自动输出。请在希望显示关联 FAQ 的位置插入此自动标签：',
    'display_help_related' => '明确呈现指定提供器和内容 ID 的关联。',
    'display_help_embed' => '独立于关联嵌入某个特定 FAQ。',
    'display_help_context_note' => '[faq-context] 使用当前内容提供器和 ID。在文章中还会应用主题继承以及重复/外部 FAQ 保护。'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'FAQ 覆盖情况',
    'intro' => '查看哪些内容使用受管理 FAQ、从主题继承的 FAQ，或内容中已存在的 FAQ。使用筛选器可查找没有 FAQ 的内容、外部 FAQ 信号或需要检查的关联。',
    'provider' => '提供器',
    'faq_status' => 'FAQ 状态',
    'all' => '全部',
    'managed_faq_only' => '仅受管理 FAQ',
    'external_faq_only' => '仅外部 FAQ 信号',
    'managed_external' => '受管理 + 外部',
    'no_faq_detected' => '未检测到 FAQ',
    'show' => '显示',
    'provider_not_enumerable' => '此提供器在当前安装中未公开可枚举的 Item Info 集合。FAQ 不会查询第三方插件表。手动关联仍可使用。',
    'core_audit_label' => '核心内容审核：',
    'core_audit_help' => '文章和静态页面以只读方式检查，因为当前 Geeklog 提供器未公开所需的集合/内容契约。外部 FAQ 检测只是编辑提示，并非确定证据。',
    'managed' => '受管理',
    'external_signal' => '外部信号',
    'none' => '无',
    'manage' => '管理',
    'edit' => '编辑',
    'managed_relations' => '受管理关系：',
    'managed_only' => '仅受管理',
    'external_only' => '仅外部',
    'both' => '两者',
    'content' => '内容',
    'external_signals' => '外部信号',
    'action' => '操作',
    'articles' => '文章',
    'topics' => '主题',
    'origin_direct' => '直接关联',
    'origin_topic' => '从主题继承',
    'topic_inheritance_blocked' => '主题继承被外部 FAQ 阻止',
    'static_pages' => '静态页面',
    'videos' => '视频',
    'documents' => '文档',
    'maps' => '地图',
    'media_gallery' => '媒体图库',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'FAQPage 微数据',
    'signal_question_answer_schema' => 'Question/Answer 架构',
    'signal_details_summary' => '重复的 details/summary 问答',
    'signal_faq_heading' => 'FAQ 标题'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'FAQ 插件导入',
    'no_topics' => '没有可导入的 FAQMAN 主题。',
    'no_faqman' => 'FAQMAN 插件未安装。',
    'not_root' => '必须是 ROOT 组成员才能导入 FAQMAN 主题。',
    'unknown' => '未知导入操作：',
    'imported' => '已将 %d 个 FAQ 导入 %d 个分类'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'FAQ 配置'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => '从“最新内容”中隐藏 FAQ',
    'hidefaqmenu' => '隐藏 FAQ 菜单项',
    'newfaqinterval' => '新 FAQ 时间间隔（秒）',
    'no_hit_rights' => '不计入浏览次数的权限',
    'contextual_enabled' => '启用上下文 FAQ',
    'contextual_default_placement' => '默认上下文放置位置',
    'structured_data' => '启用 FAQ 结构化数据',
    'coverage_limit' => '覆盖页面结果上限',
    'default_permissions' => '默认权限'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'FAQ 设置'
);

$LANG_tab['faq'] = array(
    'tab_general' => '常规',
    'tab_context' => '上下文 FAQ',
    'tab_permissions' => '权限'
);

$LANG_fs['faq'] = array(
    'fs_general' => '常规设置',
    'fs_context' => '上下文 FAQ 设置',
    'fs_permissions' => '默认权限'
);

$LANG_configselects['faq'][0] = array(
    '是' => 1,
    '否' => 0
);

$LANG_configselects['faq'][1] = array(
    '自动（提供器项目显示位置）' => 'automatic',
    '仅手动' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    '无访问权限' => 0,
    '只读' => 2,
    '读写' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => '在 Geeklog 的“最新内容”区块中隐藏 FAQ 条目。',
    'hidefaqmenu' => '从站点菜单中隐藏 FAQ 项，同时保留公开 FAQ URL。',
    'newfaqinterval' => '最近更新的 FAQ 被视为新的持续秒数。',
    'no_hit_rights' => '以逗号分隔的 FAQ 权限列表，拥有这些权限的用户不会增加 FAQ 浏览计数。',
    'contextual_enabled' => '允许在受支持的内容提供器中显示 FAQ 关联，例如文章、文档、地图和视频。',
    'contextual_default_placement' => '自动模式允许内容提供器选择其原生显示位置。仅手动模式会禁用自动上下文输出。',
    'structured_data' => '在适当情况下，在独立 FAQ 页面输出 FAQPage 结构化数据。',
    'coverage_limit' => '“覆盖情况”管理页面中检查并显示的提供器项目最大数量。',
    'default_permissions' => '分配给新 FAQ 内容的默认所有者、组、成员和匿名用户权限。'
);
