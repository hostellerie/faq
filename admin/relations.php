<?php

require_once '../../../lib-common.php';

if (!SEC_hasRights('faq.admin,faq.edit', 'OR')) {
    $display = COM_showMessageText($LANG_FAQ_ADMIN['Access Denied MSG'], $LANG_FAQ_ADMIN['FAQ Plugin']);
    COM_output(COM_createHTMLDocument($display));
    exit;
}

$display = '';
$msg = '';

if (isset($_POST['faq_relation_action']) && SEC_checkToken()) {
    $action = isset($_POST['faq_relation_action']) ? $_POST['faq_relation_action'] : '';

    if ($action === 'add') {
        $faq_id = COM_applyFilter(isset($_POST['faq_id']) ? $_POST['faq_id'] : '');
        $provider = COM_applyFilter(isset($_POST['provider']) ? $_POST['provider'] : '');
        $item_id = COM_applyFilter(isset($_POST['item_id']) ? $_POST['item_id'] : '');
        $subtype = COM_applyFilter(isset($_POST['item_subtype']) ? $_POST['item_subtype'] : '');
        $placement = COM_applyFilter(isset($_POST['placement']) ? $_POST['placement'] : 'after');
        $sort_order = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;

        if (faq_relationAdd($faq_id, $provider, $item_id, $subtype, $placement, $sort_order)) {
            $msg = 'Association saved.';
        } else {
            $msg = 'The association could not be saved. Check the FAQ, provider and item ID.';
        }
    } elseif ($action === 'delete') {
        $relation_id = isset($_POST['relation_id']) ? (int) $_POST['relation_id'] : 0;
        if (faq_relationDelete($relation_id)) {
            $msg = 'Association deleted.';
        }
    }
}

$token = SEC_createToken();

$display .= COM_startBlock('FAQ Associations');
$display .= '<p><a href="' . $_CONF['site_admin_url'] . '/plugins/faq/index.php">FAQ administration</a> | ';
$display .= '<a href="' . $_CONF['site_admin_url'] . '/plugins/faq/coverage.php">Coverage</a></p>';

if ($msg !== '') {
    $display .= COM_showMessageText($msg, 'FAQ');
}

$display .= '<h2>Add or update an association</h2>';
$display .= '<form method="post" action="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php">';
$display .= '<table class="admin-list">';
$display .= '<tr><th>FAQ ID</th><td><select name="faq_id" required>';
$result = DB_query("SELECT faq.id, faq.title
                      FROM {$_TABLES['faq']} faq
                      JOIN {$_TABLES['faq_category']} cat ON cat.id = faq.category"
                  . COM_getPermSQL('WHERE', 0, 3, 'faq')
                  . COM_getPermSQL('AND', 0, 3, 'cat')
                  . ' ORDER BY faq.title');
while ($row = DB_fetchArray($result)) {
    $display .= '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">'
              . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</option>';
}
$display .= '</select></td></tr>';
$display .= '<tr><th>Provider</th><td><input type="text" name="provider" value="article" maxlength="40" required> <small>Examples: article, staticpages, videos, documents</small></td></tr>';
$display .= '<tr><th>Item ID</th><td><input type="text" name="item_id" maxlength="128" required></td></tr>';
$display .= '<tr><th>Subtype</th><td><input type="text" name="item_subtype" maxlength="64"></td></tr>';
$display .= '<tr><th>Placement</th><td><select name="placement"><option value="after">After content</option><option value="before">Before content</option><option value="manual">Manual only</option></select></td></tr>';
$display .= '<tr><th>Order</th><td><input type="number" name="sort_order" value="0"></td></tr>';
$display .= '</table>';
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

    $display .= '<table class="admin-list"><thead><tr><th>FAQ</th><th>Target</th><th>Placement</th><th>Order</th><th></th></tr></thead><tbody>';
    while ($row = DB_fetchArray($result)) {
        $target = $row['provider'] . ':' . $row['item_id'];
        if ($row['item_subtype'] !== '') {
            $target .= ' (' . $row['item_subtype'] . ')';
        }

        $display .= '<tr><td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '<br><small>'
                  . htmlspecialchars($row['faq_id'], ENT_QUOTES, 'UTF-8') . '</small></td>';
        $display .= '<td>' . htmlspecialchars($target, ENT_QUOTES, 'UTF-8') . '</td>';
        $display .= '<td>' . htmlspecialchars($row['placement'], ENT_QUOTES, 'UTF-8') . '</td>';
        $display .= '<td>' . (int) $row['sort_order'] . '</td><td>';
        $display .= '<form method="post" action="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php" style="display:inline">';
        $display .= '<input type="hidden" name="faq_relation_action" value="delete">';
        $display .= '<input type="hidden" name="relation_id" value="' . (int) $row['relation_id'] . '">';
        $display .= '<input type="hidden" name="' . CSRF_TOKEN . '" value="' . SEC_createToken() . '">';
        $display .= '<input type="submit" value="Delete"></form></td></tr>';
    }
    $display .= '</tbody></table>';
}

$display .= COM_endBlock();

COM_output(COM_createHTMLDocument($display, array('pagetitle' => 'FAQ Associations')));
