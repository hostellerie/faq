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

$status = isset($_GET['status']) ? COM_applyFilter($_GET['status']) : 'all';
if (!in_array($status, array('all', 'with', 'without'), true)) {
    $status = 'all';
}

$limit = isset($_FAQ_CONF['coverage_limit']) ? (int) $_FAQ_CONF['coverage_limit'] : 100;
if ($limit < 1 || $limit > 200) {
    $limit = 100;
}

$items = faq_providerCollection($provider, 'id,title,url,date-modified', array(
    'limit' => $limit,
    'order' => 'modified-desc'
));

$_SCRIPTS->setCSSFile('faq_admin', faq_assetPath('faq-admin.css'));
$display = faq_adminNavigation('coverage');
$display .= COM_startBlock('FAQ Coverage');
$display .= '<form method="get" action="' . $_CONF['site_admin_url'] . '/plugins/faq/coverage.php" class="faq-admin-toolbar">';
$display .= '<label for="faq-coverage-provider">Provider</label><select id="faq-coverage-provider" name="provider">';
foreach (array('article' => 'Articles', 'staticpages' => 'Static Pages', 'videos' => 'Videos', 'documents' => 'Documents', 'maps' => 'Maps', 'mediagallery' => 'Media Gallery') as $key => $label) {
    $selected = $provider === $key ? ' selected' : '';
    $display .= '<option value="' . $key . '"' . $selected . '>' . $label . '</option>';
}
$display .= '</select>';
$display .= '<label for="faq-coverage-status">FAQ status</label><select id="faq-coverage-status" name="status">'
          . '<option value="all"' . ($status === 'all' ? ' selected' : '') . '>All</option>'
          . '<option value="with"' . ($status === 'with' ? ' selected' : '') . '>With FAQ</option>'
          . '<option value="without"' . ($status === 'without' ? ' selected' : '') . '>Without FAQ</option>'
          . '</select><input type="submit" value="Show"></form>';

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

        if (($status === 'with' && $count < 1)
            || ($status === 'without' && $count > 0)
        ) {
            continue;
        }

        $title = isset($item['title']) ? $item['title'] : $item['id'];
        $url = isset($item['url']) ? $item['url'] : '';
        $titleHtml = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        if ($url !== '') {
            $titleHtml = '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . $titleHtml . '</a>';
        }

        $rows .= '<tr><td>' . $titleHtml . '<br><small>' . htmlspecialchars((string) $item['id'], ENT_QUOTES, 'UTF-8') . '</small></td>';
        $rows .= '<td>' . $count . '</td>';
        $rows .= '<td><a href="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php?provider='
              . rawurlencode($provider) . '&amp;item_id=' . rawurlencode((string) $item['id']) . '">Manage</a></td></tr>';
    }

    $baseCoverageUrl = $_CONF['site_admin_url'] . '/plugins/faq/coverage.php?provider=' . rawurlencode($provider);
    $display .= '<div class="faq-admin-summary">'
              . '<a class="faq-admin-summary-link' . ($status === 'with' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=with', ENT_QUOTES, 'UTF-8') . '"><strong>' . $with . '</strong>With FAQ</a>'
              . '<a class="faq-admin-summary-link' . ($status === 'without' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=without', ENT_QUOTES, 'UTF-8') . '"><strong>' . $without . '</strong>Without FAQ</a>'
              . '<a class="faq-admin-summary-link' . ($status === 'all' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=all', ENT_QUOTES, 'UTF-8') . '"><strong>' . ($with + $without) . '</strong>All</a>'
              . '</div>';
    $display .= '<div class="faq-admin-table"><table class="admin-list"><thead><tr><th>Content</th><th>FAQ</th><th>Action</th></tr></thead><tbody>' . $rows . '</tbody></table></div>';
}

$display .= COM_endBlock();

COM_output(COM_createHTMLDocument($display, array('pagetitle' => 'FAQ Coverage')));
