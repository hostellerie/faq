<?php

require_once '../../../lib-common.php';

if (!SEC_hasRights('faq.admin,faq.edit', 'OR')) {
    $display = COM_showMessageText($LANG_FAQ_ADMIN['Access Denied MSG'], $LANG_FAQ_ADMIN['FAQ Plugin']);
    COM_output(COM_createHTMLDocument($display));
    exit;
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

$display = faq_adminNavigation('relations');
$msg = '';

if (isset($_POST['faq_relation_action']) && SEC_checkToken()) {
    $action = isset($_POST['faq_relation_action']) ? $_POST['faq_relation_action'] : '';

    if ($action === 'add') {
        $faq_id = COM_applyFilter(isset($_POST['faq_id']) ? $_POST['faq_id'] : '');
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

        $placement = COM_applyFilter(isset($_POST['placement']) ? $_POST['placement'] : 'after');
        if ($placement === 'automatic') {
            // Keep the existing storage value for compatibility. Both legacy
            // before/after values are treated as automatic at render time.
            $placement = 'after';
        }
        $sort_order = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;

        if (faq_relationAdd($faq_id, $provider, $item_id, $subtype, $placement, $sort_order)) {
            $msg = 'Association saved.';
        } else {
            $msg = 'The association could not be saved. Check the FAQ and selected content.';
        }
    } elseif ($action === 'delete') {
        $relation_id = isset($_POST['relation_id']) ? (int) $_POST['relation_id'] : 0;
        if (faq_relationDelete($relation_id)) {
            $msg = 'Association deleted.';
        }
    }
}

$token = SEC_createToken();
$prefillProvider = isset($_GET['provider']) ? faq_normalizeProvider($_GET['provider']) : 'article';
$prefillItem = isset($_GET['item_id']) ? trim((string) $_GET['item_id']) : '';

$providers = faq_relationProviderTypes();
if ($prefillProvider !== '' && !in_array($prefillProvider, $providers, true)) {
    $providers[] = $prefillProvider;
    sort($providers, SORT_STRING);
}

$display .= COM_startBlock('FAQ Associations');

if ($msg !== '') {
    $display .= COM_showMessageText($msg, 'FAQ');
}

$display .= '<p class="faq-admin-help">'
          . 'Associate an existing FAQ with content exposed by Geeklog providers. '
          . 'Providers and selectable content are discovered automatically through the shared Item Info contract. '
          . 'Manual ID entry is only offered as a fallback when a provider cannot enumerate its content.'
          . '</p>';

$display .= '<h2>Add association</h2>';
$display .= '<form method="post" action="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php" class="faq-admin-form">';
$display .= '<div class="faq-relation-grid">';

$display .= '<label>FAQ<select name="faq_id" required>';
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

$display .= '<label>Provider<select id="faq-relation-provider" name="provider"'
          . ' data-items-url="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/faq/relations.php', ENT_QUOTES, 'UTF-8') . '" required>';
$display .= '<option value="">Select a provider</option>';
foreach ($providers as $provider) {
    $selected = $provider === $prefillProvider ? ' selected' : '';
    $display .= '<option value="' . htmlspecialchars($provider, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>'
              . htmlspecialchars($provider, ENT_QUOTES, 'UTF-8') . '</option>';
}
$display .= '</select></label>';

$display .= '<label id="faq-relation-item-wrap">Content'
          . '<select id="faq-relation-item" name="item_id_choice" data-selected-item="'
          . htmlspecialchars($prefillItem, ENT_QUOTES, 'UTF-8') . '" disabled>'
          . '<option value="">Select a provider first</option></select></label>';

$display .= '<label id="faq-relation-item-manual-wrap" style="display:none">Content ID'
          . '<input type="text" id="faq-relation-item-manual" name="item_id_manual" maxlength="128" autocomplete="off" value="'
          . htmlspecialchars($prefillItem, ENT_QUOTES, 'UTF-8') . '"></label>';

$display .= '<input type="hidden" id="faq-relation-subtype" name="item_subtype" value="">';

$display .= '<label>Placement<select name="placement">'
          . '<option value="automatic">Automatic</option>'
          . '<option value="manual">Manual only</option>'
          . '</select></label>';

$display .= '<label>Order<input type="number" name="sort_order" value="0" min="0"></label>';

$display .= '</div>';

$display .= '<details class="faq-relation-advanced"><summary>Advanced fallback</summary>'
          . '<div class="faq-relation-grid">'
          . '<label>Subtype <input type="text" id="faq-relation-subtype-manual" name="item_subtype_manual" maxlength="64" autocomplete="off"></label>'
          . '</div>'
          . '<p class="faq-admin-help">Subtype is normally detected from the selected provider item. Enter it manually only for a legacy/custom provider that does not expose it.</p>'
          . '</details>';

$display .= '<div id="faq-relation-note" class="faq-admin-help"></div>';
$display .= '<input type="hidden" name="faq_relation_action" value="add">';
$display .= '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . $token . '">';
$display .= '<p><input type="submit" value="Save association"></p>';
$display .= '</form>';

$display .= '<h2>Current associations</h2>';

if (!faq_relationTableExists()) {
    $display .= '<p>The FAQ 1.3.0 relation table is not installed yet. Run the plugin upgrade.</p>';
} else {
    $sql = "SELECT rel.relation_id, rel.faq_id, rel.provider, rel.item_id, rel.item_subtype,
                   rel.placement, rel.sort_order, rel.enabled, faq.title
              FROM {$_TABLES['faq_relations']} rel
              JOIN {$_TABLES['faq']} faq ON faq.id = rel.faq_id
             ORDER BY rel.provider, rel.item_id, rel.sort_order, faq.title";
    $result = DB_query($sql);

    $display .= '<div class="faq-admin-table"><table class="admin-list"><thead><tr>'
              . '<th>FAQ</th><th>Content</th><th>Placement</th><th>Order</th><th>Action</th>'
              . '</tr></thead><tbody>';

    while ($row = DB_fetchArray($result)) {
        $target = $row['provider'] . ':' . $row['item_id'];
        if ($row['item_subtype'] !== '') {
            $target .= ' (' . $row['item_subtype'] . ')';
        }

        $resolvedTitle = '';
        $resolvedUrl = '';

        if (function_exists('PLG_getItemInfo')) {
            $info = PLG_getItemInfo($row['provider'], $row['item_id'], 'title,url', 0);
            if (is_array($info)) {
                $resolvedTitle = isset($info['title']) ? (string) $info['title'] : '';
                $resolvedUrl = isset($info['url']) ? (string) $info['url'] : '';
            }
        }

        $display .= '<tr><td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '<br><small>'
                  . htmlspecialchars($row['faq_id'], ENT_QUOTES, 'UTF-8') . '</small></td>';

        $display .= '<td>';
        if ($resolvedTitle !== '') {
            $display .= '<strong>' . htmlspecialchars($resolvedTitle, ENT_QUOTES, 'UTF-8') . '</strong><br>';
        }
        $display .= '<code>' . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '</code>';
        if ($resolvedUrl !== '') {
            $display .= '<br><a href="' . htmlspecialchars($resolvedUrl, ENT_QUOTES, 'UTF-8') . '">View content</a>';
        }
        $display .= '</td>';

                $placementLabel = $row['placement'] === 'manual' ? 'Manual only' : 'Automatic';
        $display .= '<td>' . htmlspecialchars($placementLabel, ENT_QUOTES, 'UTF-8') . '</td>';
        $display .= '<td>' . (int) $row['sort_order'] . '</td><td>';
        $display .= '<form method="post" action="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php" style="display:inline">';
        $display .= '<input type="hidden" name="faq_relation_action" value="delete">';
        $display .= '<input type="hidden" name="relation_id" value="' . (int) $row['relation_id'] . '">';
        $display .= '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . SEC_createToken() . '">';
        $display .= '<input type="submit" value="Delete"></form></td></tr>';
    }

    $display .= '</tbody></table></div>';
}

$display .= COM_endBlock();

COM_output(COM_createHTMLDocument($display, array('pagetitle' => 'FAQ Associations')));
