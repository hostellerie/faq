<?php

require_once '../../../lib-common.php';

if (!SEC_hasRights('faq.admin,faq.edit', 'OR')) {
    $display = COM_showMessageText($LANG_FAQ_ADMIN['Access Denied MSG'], $LANG_FAQ_ADMIN['FAQ Plugin']);
    COM_output(COM_createHTMLDocument($display));
    exit;
}

$provider = isset($_GET['provider']) ? COM_applyFilter($_GET['provider']) : 'article';
if (!faq_relationProviderAllowed($provider)) {
    $provider = 'article';
}

$limit = isset($_FAQ_CONF['coverage_limit']) ? (int) $_FAQ_CONF['coverage_limit'] : 100;
if ($limit < 1 || $limit > 200) {
    $limit = 100;
}

$items = faq_providerCollection($provider, 'id,title,url,date-modified', array(
    'limit' => $limit,
    'order' => 'modified-desc'
));

$display = COM_startBlock('FAQ Coverage');
$display .= '<p><a href="' . $_CONF['site_admin_url'] . '/plugins/faq/index.php">FAQ administration</a> | ';
$display .= '<a href="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php">Associations</a></p>';
$display .= '<form method="get" action="' . $_CONF['site_admin_url'] . '/plugins/faq/coverage.php">';
$display .= '<label>Provider <select name="provider">';
foreach (array('article' => 'Articles', 'staticpages' => 'Static Pages', 'videos' => 'Videos', 'documents' => 'Documents', 'maps' => 'Maps', 'mediagallery' => 'Media Gallery') as $key => $label) {
    $selected = $provider === $key ? ' selected' : '';
    $display .= '<option value="' . $key . '"' . $selected . '>' . $label . '</option>';
}
$display .= '</select></label> <input type="submit" value="Show"></form>';

if ($items === false) {
    $display .= '<p>This provider does not expose an enumerable Item Info collection on this installation. FAQ will not query its private SQL tables. Manual associations remain available.</p>';
} else {
    $with = 0;
    $without = 0;
    $rows = '';

    foreach ($items as $item) {
        if (!is_array($item) || !isset($item['id'])) {
            continue;
        }

        $count = faq_relationCountForItem($provider, (string) $item['id']);
        if ($count > 0) {
            $with++;
        } else {
            $without++;
        }

        $title = isset($item['title']) ? $item['title'] : $item['id'];
        $url = isset($item['url']) ? $item['url'] : '';
        $titleHtml = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        if ($url !== '') {
            $titleHtml = '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . $titleHtml . '</a>';
        }

        $rows .= '<tr><td>' . $titleHtml . '<br><small>' . htmlspecialchars((string) $item['id'], ENT_QUOTES, 'UTF-8') . '</small></td>';
        $rows .= '<td>' . $count . '</td>';
        $rows .= '<td><a href="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php">Manage</a></td></tr>';
    }

    $display .= '<p><strong>With FAQ:</strong> ' . $with . ' &nbsp; <strong>Without FAQ:</strong> ' . $without . '</p>';
    $display .= '<table class="admin-list"><thead><tr><th>Content</th><th>FAQ</th><th></th></tr></thead><tbody>' . $rows . '</tbody></table>';
}

$display .= COM_endBlock();

COM_output(COM_createHTMLDocument($display, array('pagetitle' => 'FAQ Coverage')));
