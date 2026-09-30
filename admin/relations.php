<?php

require_once '../../../lib-common.php';

if (!SEC_hasRights('faq.admin,faq.edit', 'OR')) {
    $display = COM_showMessageText($LANG_FAQ_ADMIN['Access Denied MSG'], $LANG_FAQ_ADMIN['FAQ Plugin']);
    COM_output(COM_createHTMLDocument($display));
    exit;
}

faq_categoryRelationEnsureTable();

/**
 * Read one Item Info field across the historical Geeklog return shapes.
 *
 * Providers may return a scalar, an associative array, or a numeric array
 * for concrete-item requests. Normalize those forms here so consumers do not
 * need provider-specific knowledge.
 *
 * @param string $provider
 * @param string $itemId
 * @param string $field
 * @return string
 */
function faq_adminItemInfoValue($provider, $itemId, $field)
{
    global $_USER;

    if (!function_exists('PLG_getItemInfo')) {
        return '';
    }

    $uid = isset($_USER['uid']) ? (int) $_USER['uid'] : 0;
    $info = PLG_getItemInfo($provider, $itemId, $field, $uid);

    if (is_scalar($info)) {
        return trim((string) $info);
    }

    if (!is_array($info)) {
        return '';
    }

    if (isset($info[$field]) && is_scalar($info[$field])) {
        return trim((string) $info[$field]);
    }

    if (isset($info[0]) && is_scalar($info[0])) {
        return trim((string) $info[0]);
    }

    return '';
}

/**
 * Resolve an administration association target for display.
 *
 * Prefer the provider-owned canonical URL callback documented by Memorandum,
 * then fall back to Item Info. Core Articles and Static Pages keep the same
 * small native fallback while their providers do not expose both surfaces.
 *
 * @param string $provider
 * @param string $itemId
 * @param string $subType
 * @return array
 */
function faq_adminResolveAssociationTarget($provider, $itemId, $subType = '')
{
    global $_CONF, $_TABLES;

    $provider = faq_normalizeProvider($provider);
    $itemId = (string) $itemId;
    $subType = (string) $subType;
    $resolved = array(
        'title' => '',
        'url' => ''
    );

    // Memorandum content interoperability contract:
    // content.url.resolve -> plugin_idtourl_PLUGIN($sub_type, $item_id)
    $urlCallback = 'plugin_idtourl_' . $provider;
    if (function_exists($urlCallback)) {
        $providerUrl = $urlCallback($subType, $itemId);
        if (is_string($providerUrl) && trim($providerUrl) !== '') {
            $resolved['url'] = trim($providerUrl);
        }
    }

    $resolved['title'] = faq_adminItemInfoValue($provider, $itemId, 'title');

    if ($resolved['url'] === '') {
        $resolved['url'] = faq_adminItemInfoValue($provider, $itemId, 'url');
    }

    if ($provider === 'article') {
        if ($resolved['title'] === '' && !empty($_TABLES['stories'])) {
            $resolved['title'] = (string) DB_getItem(
                $_TABLES['stories'],
                'title',
                "sid = '" . DB_escapeString($itemId) . "'"
            );
        }
        if ($resolved['url'] === '') {
            $url = rtrim($_CONF['site_url'], '/') . '/article.php?story=' . rawurlencode($itemId);
            $resolved['url'] = function_exists('COM_buildURL') ? COM_buildURL($url) : $url;
        }
    } elseif ($provider === 'topic') {
        if ($resolved['title'] === '' && !empty($_TABLES['topics'])) {
            $resolved['title'] = (string) DB_getItem(
                $_TABLES['topics'],
                'topic',
                "tid = '" . DB_escapeString($itemId) . "'"
            );
        }
        if ($resolved['url'] === '') {
            $resolved['url'] = rtrim($_CONF['site_url'], '/') . '/index.php?topic=' . rawurlencode($itemId);
        }
    } elseif ($provider === 'staticpages') {
        if ($resolved['title'] === '' && !empty($_TABLES['staticpage'])) {
            $resolved['title'] = (string) DB_getItem(
                $_TABLES['staticpage'],
                'sp_title',
                "sp_id = '" . DB_escapeString($itemId) . "'"
            );
        }
        if ($resolved['url'] === '') {
            $url = rtrim($_CONF['site_url'], '/') . '/staticpages/index.php?page=' . rawurlencode($itemId);
            $resolved['url'] = function_exists('COM_buildURL') ? COM_buildURL($url) : $url;
        }
    }

    return $resolved;
}

if (isset($_GET['faq_ajax']) && $_GET['faq_ajax'] === 'items') {
    $provider = isset($_GET['provider']) ? COM_applyFilter($_GET['provider']) : '';
    $payload = faq_relationObjectOptions($provider, 100);

    if (!headers_sent()) {
        header('Content-Type: application/json; charset=UTF-8');
        header('X-Content-Type-Options: nosniff');
    }

    echo json_encode($payload);
    exit;
}

$_SCRIPTS->setCSSFile('faq_admin', faq_assetPath('faq-admin.css'));
$_SCRIPTS->setJavaScriptFile('faq_relations_admin', faq_assetPath('relations-admin.js'), true, 220);

faq_relationEnsureTopicScopeColumns();

$display = faq_adminNavigation('relations');
$msg = '';

if (isset($_POST['faq_relation_action']) && SEC_checkToken()) {
    $action = isset($_POST['faq_relation_action']) ? $_POST['faq_relation_action'] : '';

    if ($action === 'add') {
        $target_type = isset($_POST['target_type']) && $_POST['target_type'] === 'category' ? 'category' : 'faq';
        $faq_id = COM_applyFilter(isset($_POST['faq_id']) ? $_POST['faq_id'] : '');
        $category_id = COM_applyFilter(isset($_POST['category_id']) ? $_POST['category_id'] : '');
        $provider = COM_applyFilter(isset($_POST['provider']) ? $_POST['provider'] : '');

        $itemChoice = isset($_POST['item_id_choice']) ? trim((string) $_POST['item_id_choice']) : '';
        $itemManual = isset($_POST['item_id_manual']) ? trim((string) $_POST['item_id_manual']) : '';

        if ($itemChoice !== '' && $itemChoice !== '__manual__') {
            $item_id = $itemChoice;
        } else {
            $item_id = $itemManual;
        }

        $subtype = isset($_POST['item_subtype']) ? trim((string) $_POST['item_subtype']) : '';
        $subtypeManual = isset($_POST['item_subtype_manual']) ? trim((string) $_POST['item_subtype_manual']) : '';
        if ($subtype === '' && $subtypeManual !== '') {
            $subtype = $subtypeManual;
        }

        $placement = COM_applyFilter(isset($_POST['placement']) ? $_POST['placement'] : 'automatic');
        $topic_scope = COM_applyFilter(isset($_POST['topic_scope']) ? $_POST['topic_scope'] : 'both');
        $topic_scope = faq_relationNormalizeTopicScope($provider, $topic_scope);
        $sort_order = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $confirmExternal = !empty($_POST['confirm_external_faq']);
        $externalConflict = false;

        if ($provider === 'article' && $item_id !== '') {
            $externalSignals = faq_articleExternalSignals($item_id);
            $externalConflict = !empty($externalSignals);
        }

        if ($externalConflict && !$confirmExternal) {
            $saved = false;
            $msg = $LANG_FAQ_RELATIONS['external_confirmation_required'];
        } else {
            $saved = $target_type === 'category'
                ? faq_categoryRelationAdd($category_id, $provider, $item_id, $subtype, $placement, $sort_order, $topic_scope)
                : faq_relationAdd($faq_id, $provider, $item_id, $subtype, $placement, $sort_order, $topic_scope);
        }

        if ($saved) {
            $msg = $target_type === 'category'
                ? $LANG_FAQ_RELATIONS['category_association_saved']
                : $LANG_FAQ_RELATIONS['association_saved'];
        } elseif (!$externalConflict || $confirmExternal) {
            $msg = $LANG_FAQ_RELATIONS['association_save_failed'];
        }
    } elseif ($action === 'delete' || $action === 'delete_category') {
        $relation_id = isset($_POST['relation_id']) ? (int) $_POST['relation_id'] : 0;
        $deleted = $action === 'delete_category'
            ? faq_categoryRelationDelete($relation_id)
            : faq_relationDelete($relation_id);
        if ($deleted) {
            $msg = $LANG_FAQ_RELATIONS['association_deleted'];
        }
    }
}

$token = SEC_createToken();
$prefillProvider = isset($_GET['provider']) ? faq_normalizeProvider($_GET['provider']) : 'article';
$prefillItem = isset($_GET['item_id']) ? trim((string) $_GET['item_id']) : '';
if (!empty($externalConflict) && !$confirmExternal) {
    $prefillProvider = faq_normalizeProvider($provider);
    $prefillItem = (string) $item_id;
}

$filterProvider = $prefillItem !== '' ? $prefillProvider : '';
$filterItem = $prefillItem;

$providers = faq_relationProviderTypes();
if ($prefillProvider !== '' && !in_array($prefillProvider, $providers, true)) {
    $providers[] = $prefillProvider;
    sort($providers, SORT_STRING);
}

$display .= COM_startBlock($LANG_FAQ_RELATIONS['title']);

if ($msg !== '') {
    $display .= COM_showMessageText($msg, 'FAQ');
}

$display .= '<p class="faq-admin-help">'
          . htmlspecialchars($LANG_FAQ_RELATIONS['intro'], ENT_QUOTES, 'UTF-8')
          . '</p>';

$display .= '<details class="faq-relation-advanced faq-relation-help"><summary>'
          . htmlspecialchars($LANG_FAQ_RELATIONS['display_help_title'], ENT_QUOTES, 'UTF-8')
          . '</summary>'
          . '<p>' . htmlspecialchars($LANG_FAQ_RELATIONS['display_help_intro'], ENT_QUOTES, 'UTF-8') . '</p>'
          . '<ul>'
          . '<li><strong>' . htmlspecialchars($LANG_FAQ_RELATIONS['automatic'], ENT_QUOTES, 'UTF-8') . '</strong> — '
          . htmlspecialchars($LANG_FAQ_RELATIONS['display_help_automatic'], ENT_QUOTES, 'UTF-8') . '</li>'
          . '<li><strong>' . htmlspecialchars($LANG_FAQ_RELATIONS['manual_only'], ENT_QUOTES, 'UTF-8') . '</strong> — '
          . htmlspecialchars($LANG_FAQ_RELATIONS['display_help_manual'], ENT_QUOTES, 'UTF-8')
          . ' <code>[faq-context]</code></li>'
          . '<li><code>[faqrelated:article my-article-id]</code> — '
          . htmlspecialchars($LANG_FAQ_RELATIONS['display_help_related'], ENT_QUOTES, 'UTF-8') . '</li>'
          . '<li><code>[faqembed:faq-id]</code> — '
          . htmlspecialchars($LANG_FAQ_RELATIONS['display_help_embed'], ENT_QUOTES, 'UTF-8') . '</li>'
          . '</ul>'
          . '<p>' . htmlspecialchars($LANG_FAQ_RELATIONS['display_help_context_note'], ENT_QUOTES, 'UTF-8') . '</p>'
          . '</details>';

$display .= '<h2>' . htmlspecialchars($LANG_FAQ_RELATIONS['add_association'], ENT_QUOTES, 'UTF-8') . '</h2>';
$display .= '<form method="post" action="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php" class="faq-admin-form">';
$display .= '<div class="faq-relation-grid">';

$display .= '<label>' . htmlspecialchars($LANG_FAQ_RELATIONS['association_source'], ENT_QUOTES, 'UTF-8')
          . '<select id="faq-relation-target-type" name="target_type">'
          . '<option value="faq">' . htmlspecialchars($LANG_FAQ_RELATIONS['individual_faq'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="category">' . htmlspecialchars($LANG_FAQ_RELATIONS['whole_category'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '</select></label>';

$display .= '<label id="faq-relation-faq-wrap">' . htmlspecialchars($LANG_FAQ_RELATIONS['faq'], ENT_QUOTES, 'UTF-8')
          . '<select id="faq-relation-faq" name="faq_id">';
$result = DB_query("SELECT faq.id, faq.title
                      FROM {$_TABLES['faq']} faq
                      JOIN {$_TABLES['faq_category']} cat ON cat.id = faq.category"
                  . COM_getPermSQL('WHERE', 0, 3, 'faq')
                  . COM_getPermSQL('AND', 0, 3, 'cat')
                  . ' ORDER BY faq.title');
while ($row = DB_fetchArray($result)) {
    $display .= '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">'
              . htmlspecialchars($row['title'] . ' [' . $row['id'] . ']', ENT_QUOTES, 'UTF-8') . '</option>';
}
$display .= '</select></label>';

$display .= '<label id="faq-relation-category-wrap" style="display:none">'
          . htmlspecialchars($LANG_FAQ_RELATIONS['faq_category'], ENT_QUOTES, 'UTF-8')
          . '<select id="faq-relation-category" name="category_id"><option value="">'
          . htmlspecialchars($LANG_FAQ_RELATIONS['select_category'], ENT_QUOTES, 'UTF-8') . '</option>';
$categoryResult = DB_query("SELECT cat.id, cat.title
                              FROM {$_TABLES['faq_category']} cat"
                          . COM_getPermSQL('WHERE', 0, 3, 'cat')
                          . ' ORDER BY cat.title');
while ($categoryRow = DB_fetchArray($categoryResult)) {
    $display .= '<option value="' . htmlspecialchars($categoryRow['id'], ENT_QUOTES, 'UTF-8') . '">'
              . htmlspecialchars($categoryRow['title'] . ' [' . $categoryRow['id'] . ']', ENT_QUOTES, 'UTF-8') . '</option>';
}
$display .= '</select></label>';

$display .= '<label>' . htmlspecialchars($LANG_FAQ_RELATIONS['provider'], ENT_QUOTES, 'UTF-8')
          . '<select id="faq-relation-provider" name="provider"'
          . ' data-items-url="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/faq/relations.php', ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-select-content="' . htmlspecialchars($LANG_FAQ_RELATIONS['select_content'], ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-loading="' . htmlspecialchars($LANG_FAQ_RELATIONS['loading'], ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-select-provider-first="' . htmlspecialchars($LANG_FAQ_RELATIONS['select_provider_first'], ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-no-content="' . htmlspecialchars($LANG_FAQ_RELATIONS['no_selectable_content'], ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-enter-manual="' . htmlspecialchars($LANG_FAQ_RELATIONS['enter_id_manually'], ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-collection-unavailable="' . htmlspecialchars($LANG_FAQ_RELATIONS['collection_unavailable'], ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-load-failed="' . htmlspecialchars($LANG_FAQ_RELATIONS['unable_load_collection'], ENT_QUOTES, 'UTF-8') . '"'
          . ' data-msg-manual-fallback="' . htmlspecialchars($LANG_FAQ_RELATIONS['manual_fallback'], ENT_QUOTES, 'UTF-8') . '" required>';
$display .= '<option value="">' . htmlspecialchars($LANG_FAQ_RELATIONS['select_provider'], ENT_QUOTES, 'UTF-8') . '</option>';
foreach ($providers as $provider) {
    $selected = $provider === $prefillProvider ? ' selected' : '';
    $providerLabel = $provider === 'topic' ? $LANG_FAQ_RELATIONS['topic_provider'] : $provider;
    $display .= '<option value="' . htmlspecialchars($provider, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>'
              . htmlspecialchars($providerLabel, ENT_QUOTES, 'UTF-8') . '</option>';
}
$display .= '</select></label>';

$display .= '<label id="faq-relation-item-wrap">' . htmlspecialchars($LANG_FAQ_RELATIONS['content'], ENT_QUOTES, 'UTF-8')
          . '<select id="faq-relation-item" name="item_id_choice" data-selected-item="'
          . htmlspecialchars($prefillItem, ENT_QUOTES, 'UTF-8') . '" disabled>'
          . '<option value="">' . htmlspecialchars($LANG_FAQ_RELATIONS['select_provider_first'], ENT_QUOTES, 'UTF-8') . '</option></select></label>';

$display .= '<label id="faq-relation-item-manual-wrap" style="display:none">'
          . htmlspecialchars($LANG_FAQ_RELATIONS['content_id'], ENT_QUOTES, 'UTF-8')
          . '<input type="text" id="faq-relation-item-manual" name="item_id_manual" maxlength="128" autocomplete="off" value="'
          . htmlspecialchars($prefillItem, ENT_QUOTES, 'UTF-8') . '"></label>';

$display .= '<input type="hidden" id="faq-relation-subtype" name="item_subtype" value="">';

$display .= '<label>' . htmlspecialchars($LANG_FAQ_RELATIONS['placement'], ENT_QUOTES, 'UTF-8')
          . '<select name="placement">'
          . '<option value="automatic">' . htmlspecialchars($LANG_FAQ_RELATIONS['automatic'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="manual">' . htmlspecialchars($LANG_FAQ_RELATIONS['manual_only'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '</select></label>';

$display .= '<label id="faq-relation-topic-scope-wrap" style="display:none">'
          . htmlspecialchars($LANG_FAQ_RELATIONS['topic_scope'], ENT_QUOTES, 'UTF-8')
          . '<select id="faq-relation-topic-scope" name="topic_scope">'
          . '<option value="both">' . htmlspecialchars($LANG_FAQ_RELATIONS['topic_scope_both'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="topic">' . htmlspecialchars($LANG_FAQ_RELATIONS['topic_scope_topic'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="articles">' . htmlspecialchars($LANG_FAQ_RELATIONS['topic_scope_articles'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '</select></label>';

$display .= '<label>' . htmlspecialchars($LANG_FAQ_RELATIONS['order'], ENT_QUOTES, 'UTF-8')
          . '<input type="number" name="sort_order" value="0" min="0"></label>';

$display .= '</div>';

$display .= '<details class="faq-relation-advanced"><summary>'
          . htmlspecialchars($LANG_FAQ_RELATIONS['advanced_fallback'], ENT_QUOTES, 'UTF-8') . '</summary>'
          . '<div class="faq-relation-grid">'
          . '<label>' . htmlspecialchars($LANG_FAQ_RELATIONS['subtype'], ENT_QUOTES, 'UTF-8')
          . ' <input type="text" id="faq-relation-subtype-manual" name="item_subtype_manual" maxlength="64" autocomplete="off"></label>'
          . '</div>'
          . '<p class="faq-admin-help">' . htmlspecialchars($LANG_FAQ_RELATIONS['subtype_help'], ENT_QUOTES, 'UTF-8') . '</p>'
          . '</details>';

$display .= '<div id="faq-relation-note" class="faq-admin-help"></div>';
$display .= '<label class="faq-admin-help"><input type="checkbox" name="confirm_external_faq" value="1"> '
          . htmlspecialchars($LANG_FAQ_RELATIONS['confirm_external_faq'], ENT_QUOTES, 'UTF-8')
          . '</label>';
$display .= '<input type="hidden" name="faq_relation_action" value="add">';
$display .= '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . $token . '">';
$display .= '<div class="faq-relation-actions"><input type="submit" value="'
          . htmlspecialchars($LANG_FAQ_RELATIONS['save_association'], ENT_QUOTES, 'UTF-8') . '"></div>';
$display .= '</form>';

$display .= '<h2 class="faq-relation-current-title">' . htmlspecialchars($LANG_FAQ_RELATIONS['current_faq_associations'], ENT_QUOTES, 'UTF-8') . '</h2>';

if ($filterProvider !== '' && $filterItem !== '') {
    $display .= '<div class="faq-admin-filter-context"><strong>'
              . htmlspecialchars($LANG_FAQ_RELATIONS['filtered_content'], ENT_QUOTES, 'UTF-8')
              . '</strong> <code>'
              . htmlspecialchars($filterProvider . ':' . $filterItem, ENT_QUOTES, 'UTF-8')
              . '</code> <a href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/faq/relations.php', ENT_QUOTES, 'UTF-8')
              . '">' . htmlspecialchars($LANG_FAQ_RELATIONS['show_all_associations'], ENT_QUOTES, 'UTF-8') . '</a></div>';
}

if (!faq_relationTableExists()) {
    $display .= '<p>' . htmlspecialchars($LANG_FAQ_RELATIONS['relation_table_missing'], ENT_QUOTES, 'UTF-8') . '</p>';
} else {
    $sql = "SELECT rel.relation_id, rel.faq_id, rel.provider, rel.item_id, rel.item_subtype,
                   rel.placement, rel.topic_scope, rel.sort_order, rel.enabled, faq.title
              FROM {$_TABLES['faq_relations']} rel
              JOIN {$_TABLES['faq']} faq ON faq.id = rel.faq_id";

    if ($filterProvider !== '' && $filterItem !== '') {
        $sql .= " WHERE rel.provider = '" . DB_escapeString($filterProvider) . "'"
              . " AND rel.item_id = '" . DB_escapeString($filterItem) . "'";
    }

    $sql .= " ORDER BY rel.provider, rel.item_id, rel.sort_order, faq.title";
    $result = DB_query($sql);

    $display .= '<div class="faq-admin-table"><table class="admin-list"><thead><tr>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['faq'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['content'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['placement'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['topic_scope'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['order'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['action'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '</tr></thead><tbody>';

    while ($row = DB_fetchArray($result)) {
        $target = $row['provider'] . ':' . $row['item_id'];
        if ($row['item_subtype'] !== '') {
            $target .= ' (' . $row['item_subtype'] . ')';
        }

        $resolved = faq_adminResolveAssociationTarget($row['provider'], $row['item_id'], $row['item_subtype']);
        $resolvedTitle = $resolved['title'];
        $resolvedUrl = $resolved['url'];

        $display .= '<tr><td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '<br><small>'
                  . htmlspecialchars($row['faq_id'], ENT_QUOTES, 'UTF-8') . '</small></td>';

        $display .= '<td>';
        if ($resolvedTitle !== '') {
            if ($resolvedUrl !== '') {
                $display .= '<strong><a href="' . htmlspecialchars($resolvedUrl, ENT_QUOTES, 'UTF-8') . '">'
                          . htmlspecialchars($resolvedTitle, ENT_QUOTES, 'UTF-8') . '</a></strong><br>';
            } else {
                $display .= '<strong>' . htmlspecialchars($resolvedTitle, ENT_QUOTES, 'UTF-8') . '</strong><br>';
            }
        }
        if ($resolvedTitle === '' && $resolvedUrl !== '') {
            $display .= '<a href="' . htmlspecialchars($resolvedUrl, ENT_QUOTES, 'UTF-8') . '"><code>'
                      . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '</code></a>';
        } else {
            $display .= '<code>' . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '</code>';
        }
        $display .= '</td>';

                $placementLabel = $row['placement'] === 'manual'
            ? $LANG_FAQ_RELATIONS['manual_only']
            : $LANG_FAQ_RELATIONS['automatic'];
        $display .= '<td>' . htmlspecialchars($placementLabel, ENT_QUOTES, 'UTF-8') . '</td>';
        if ($row['provider'] === 'topic') {
            $scopeKey = isset($row['topic_scope']) ? $row['topic_scope'] : 'both';
            $scopeLabels = array(
                'topic' => $LANG_FAQ_RELATIONS['topic_scope_topic'],
                'articles' => $LANG_FAQ_RELATIONS['topic_scope_articles'],
                'both' => $LANG_FAQ_RELATIONS['topic_scope_both']
            );
            $scopeLabel = isset($scopeLabels[$scopeKey]) ? $scopeLabels[$scopeKey] : $scopeLabels['both'];
        } else {
            $scopeLabel = '—';
        }
        $display .= '<td>' . htmlspecialchars($scopeLabel, ENT_QUOTES, 'UTF-8') . '</td>';
        $display .= '<td>' . (int) $row['sort_order'] . '</td><td>';
        $deleteAction = $_CONF['site_admin_url'] . '/plugins/faq/relations.php';
        if ($filterProvider !== '' && $filterItem !== '') {
            $deleteAction .= '?provider=' . rawurlencode($filterProvider) . '&item_id=' . rawurlencode($filterItem);
        }
        $display .= '<form method="post" action="' . htmlspecialchars($deleteAction, ENT_QUOTES, 'UTF-8') . '" style="display:inline">';
        $display .= '<input type="hidden" name="faq_relation_action" value="delete">';
        $display .= '<input type="hidden" name="relation_id" value="' . (int) $row['relation_id'] . '">';
        $display .= '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . SEC_createToken() . '">';
        $display .= '<input type="submit" value="' . htmlspecialchars($LANG_FAQ_RELATIONS['delete'], ENT_QUOTES, 'UTF-8') . '"></form></td></tr>';
    }

    $display .= '</tbody></table></div>';
}

$display .= '<h2 class="faq-relation-current-title">' . htmlspecialchars($LANG_FAQ_RELATIONS['current_category_associations'], ENT_QUOTES, 'UTF-8') . '</h2>';
if (!faq_categoryRelationTableExists()) {
    $display .= '<p>' . htmlspecialchars($LANG_FAQ_RELATIONS['category_relation_table_missing'], ENT_QUOTES, 'UTF-8') . '</p>';
} else {
    $sql = "SELECT rel.relation_id, rel.category_id, rel.provider, rel.item_id, rel.item_subtype,
                   rel.placement, rel.topic_scope, rel.sort_order, rel.enabled, cat.title
              FROM {$_TABLES['faq_category_relations']} rel
              JOIN {$_TABLES['faq_category']} cat ON cat.id = rel.category_id";

    if ($filterProvider !== '' && $filterItem !== '') {
        $sql .= " WHERE rel.provider = '" . DB_escapeString($filterProvider) . "'"
              . " AND rel.item_id = '" . DB_escapeString($filterItem) . "'";
    }

    $sql .= " ORDER BY rel.provider, rel.item_id, rel.sort_order, cat.title";
    $result = DB_query($sql);

    $display .= '<div class="faq-admin-table"><table class="admin-list"><thead><tr>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['category'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['content'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['placement'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['topic_scope'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['order'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_RELATIONS['action'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '</tr></thead><tbody>';

    while ($row = DB_fetchArray($result)) {
        $target = $row['provider'] . ':' . $row['item_id'];
        if ($row['item_subtype'] !== '') {
            $target .= ' (' . $row['item_subtype'] . ')';
        }

        $resolved = faq_adminResolveAssociationTarget($row['provider'], $row['item_id'], $row['item_subtype']);
        $resolvedTitle = $resolved['title'];
        $resolvedUrl = $resolved['url'];

        $display .= '<tr><td><strong>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</strong><br><small>'
                  . htmlspecialchars($row['category_id'], ENT_QUOTES, 'UTF-8') . '</small></td><td>';
        if ($resolvedTitle !== '') {
            if ($resolvedUrl !== '') {
                $display .= '<strong><a href="' . htmlspecialchars($resolvedUrl, ENT_QUOTES, 'UTF-8') . '">'
                          . htmlspecialchars($resolvedTitle, ENT_QUOTES, 'UTF-8') . '</a></strong><br>';
            } else {
                $display .= '<strong>' . htmlspecialchars($resolvedTitle, ENT_QUOTES, 'UTF-8') . '</strong><br>';
            }
        }
        if ($resolvedTitle === '' && $resolvedUrl !== '') {
            $display .= '<a href="' . htmlspecialchars($resolvedUrl, ENT_QUOTES, 'UTF-8') . '"><code>'
                      . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '</code></a>';
        } else {
            $display .= '<code>' . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '</code>';
        }
        $display .= '</td>';

        $placementLabel = $row['placement'] === 'manual'
            ? $LANG_FAQ_RELATIONS['manual_only']
            : $LANG_FAQ_RELATIONS['automatic'];
        $display .= '<td>' . htmlspecialchars($placementLabel, ENT_QUOTES, 'UTF-8') . '</td>';
        if ($row['provider'] === 'topic') {
            $scopeKey = isset($row['topic_scope']) ? $row['topic_scope'] : 'both';
            $scopeLabels = array(
                'topic' => $LANG_FAQ_RELATIONS['topic_scope_topic'],
                'articles' => $LANG_FAQ_RELATIONS['topic_scope_articles'],
                'both' => $LANG_FAQ_RELATIONS['topic_scope_both']
            );
            $scopeLabel = isset($scopeLabels[$scopeKey]) ? $scopeLabels[$scopeKey] : $scopeLabels['both'];
        } else {
            $scopeLabel = '—';
        }
        $display .= '<td>' . htmlspecialchars($scopeLabel, ENT_QUOTES, 'UTF-8') . '</td>';
        $display .= '<td>' . (int) $row['sort_order'] . '</td><td>';

        $deleteAction = $_CONF['site_admin_url'] . '/plugins/faq/relations.php';
        if ($filterProvider !== '' && $filterItem !== '') {
            $deleteAction .= '?provider=' . rawurlencode($filterProvider) . '&item_id=' . rawurlencode($filterItem);
        }
        $display .= '<form method="post" action="' . htmlspecialchars($deleteAction, ENT_QUOTES, 'UTF-8') . '" style="display:inline">'
                  . '<input type="hidden" name="faq_relation_action" value="delete_category">'
                  . '<input type="hidden" name="relation_id" value="' . (int) $row['relation_id'] . '">'
                  . '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . SEC_createToken() . '">'
                  . '<input type="submit" value="' . htmlspecialchars($LANG_FAQ_RELATIONS['delete'], ENT_QUOTES, 'UTF-8') . '"></form></td></tr>';
    }

    $display .= '</tbody></table></div>';
}

$display .= COM_endBlock();

COM_output(COM_createHTMLDocument($display, array('pagetitle' => $LANG_FAQ_RELATIONS['title'])));
