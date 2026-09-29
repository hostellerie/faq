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

// Development compatibility with the first 1.3.0 Coverage filters.
if ($status === 'with') {
    $status = 'managed';
} elseif ($status === 'without') {
    $status = 'none';
}
if (!in_array($status, array('all', 'managed', 'external', 'both', 'none'), true)) {
    $status = 'all';
}

$limit = isset($_FAQ_CONF['coverage_limit']) ? (int) $_FAQ_CONF['coverage_limit'] : 100;
if ($limit < 1 || $limit > 200) {
    $limit = 100;
}

$coreAudit = in_array($provider, array('article', 'staticpages'), true);

if ($coreAudit) {
    // Geeklog Core and Static Pages do not yet expose the shared collection
    // surface required for this audit. Follow Hub's read-only Core-table audit
    // precedent rather than pretending the capability exists.
    $items = faq_coverageCoreCollection($provider, $limit);
} else {
    $items = faq_providerCollection($provider, 'id,title,url,date-modified', array(
        'limit' => $limit,
        'order' => 'modified-desc'
    ));
}

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
          . '<option value="managed"' . ($status === 'managed' ? ' selected' : '') . '>Managed FAQ only</option>'
          . '<option value="external"' . ($status === 'external' ? ' selected' : '') . '>External FAQ signal only</option>'
          . '<option value="both"' . ($status === 'both' ? ' selected' : '') . '>Managed + external</option>'
          . '<option value="none"' . ($status === 'none' ? ' selected' : '') . '>No FAQ detected</option>'
          . '</select><input type="submit" value="Show"></form>';

if ($items === false) {
    $display .= '<p>This provider does not expose an enumerable Item Info collection on this installation. FAQ will not query third-party plugin tables. Manual associations remain available.</p>';
} else {
    if ($coreAudit) {
        $display .= '<p class="faq-admin-audit-note"><strong>Core content audit:</strong> Articles and Static Pages are inspected read-only because their current Geeklog providers do not expose the required collection/content contract. External FAQ detection is an editorial signal, not proof.</p>';
    }

    $managedOnly = 0;
    $externalOnly = 0;
    $both = 0;
    $none = 0;
    $rows = '';

    foreach ($items as $item) {
        if (!is_array($item) || !isset($item['id'])) {
            continue;
        }

        $count = faq_relationCountForItem($provider, (string) $item['id']);
        $signals = array();
        if ($coreAudit && isset($item['_audit_content'])) {
            $signals = faq_coverageExternalSignals($item['_audit_content']);
        }

        $hasManaged = $count > 0;
        $hasExternal = !empty($signals);

        if ($hasManaged && $hasExternal) {
            $both++;
            $rowStatus = 'both';
        } elseif ($hasManaged) {
            $managedOnly++;
            $rowStatus = 'managed';
        } elseif ($hasExternal) {
            $externalOnly++;
            $rowStatus = 'external';
        } else {
            $none++;
            $rowStatus = 'none';
        }

        if ($status !== 'all' && $status !== $rowStatus) {
            continue;
        }

        $title = isset($item['title']) ? $item['title'] : $item['id'];
        $url = isset($item['url']) ? $item['url'] : '';
        $titleHtml = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        if ($url !== '') {
            $titleHtml = '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . $titleHtml . '</a>';
        }

        $signalHtml = empty($signals)
            ? '&mdash;'
            : htmlspecialchars(implode(', ', $signals), ENT_QUOTES, 'UTF-8');

        $stateLabel = $rowStatus === 'both'
            ? 'Managed + external'
            : ($rowStatus === 'managed'
                ? 'Managed'
                : ($rowStatus === 'external' ? 'External signal' : 'None'));

        $actions = '<a href="' . $_CONF['site_admin_url'] . '/plugins/faq/relations.php?provider='
                 . rawurlencode($provider) . '&amp;item_id=' . rawurlencode((string) $item['id']) . '">Manage</a>';

        if ($provider === 'article') {
            $actions .= ' &middot; <a href="' . $_CONF['site_admin_url'] . '/story.php?mode=edit&amp;sid='
                     . rawurlencode((string) $item['id']) . '">Edit</a>';
        } elseif ($provider === 'staticpages') {
            $actions .= ' &middot; <a href="' . $_CONF['site_admin_url'] . '/plugins/staticpages/index.php?mode=edit&amp;sp_id='
                     . rawurlencode((string) $item['id']) . '">Edit</a>';
        }

        $rows .= '<tr><td>' . $titleHtml . '<br><small>' . htmlspecialchars((string) $item['id'], ENT_QUOTES, 'UTF-8') . '</small></td>';
        $rows .= '<td><strong>' . htmlspecialchars($stateLabel, ENT_QUOTES, 'UTF-8') . '</strong><br><small>Managed relations: ' . $count . '</small></td>';
        $rows .= '<td>' . $signalHtml . '</td>';
        $rows .= '<td>' . $actions . '</td></tr>';
    }

    $baseCoverageUrl = $_CONF['site_admin_url'] . '/plugins/faq/coverage.php?provider=' . rawurlencode($provider);
    $total = $managedOnly + $externalOnly + $both + $none;
    $display .= '<div class="faq-admin-summary">'
              . '<a class="faq-admin-summary-link' . ($status === 'managed' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=managed', ENT_QUOTES, 'UTF-8') . '"><strong>' . $managedOnly . '</strong>Managed only</a>'
              . '<a class="faq-admin-summary-link' . ($status === 'external' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=external', ENT_QUOTES, 'UTF-8') . '"><strong>' . $externalOnly . '</strong>External only</a>'
              . '<a class="faq-admin-summary-link' . ($status === 'both' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=both', ENT_QUOTES, 'UTF-8') . '"><strong>' . $both . '</strong>Both</a>'
              . '<a class="faq-admin-summary-link' . ($status === 'none' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=none', ENT_QUOTES, 'UTF-8') . '"><strong>' . $none . '</strong>None</a>'
              . '<a class="faq-admin-summary-link' . ($status === 'all' ? ' active' : '') . '" href="' . htmlspecialchars($baseCoverageUrl . '&status=all', ENT_QUOTES, 'UTF-8') . '"><strong>' . $total . '</strong>All</a>'
              . '</div>';
    $display .= '<div class="faq-admin-table"><table class="admin-list"><thead><tr><th>Content</th><th>FAQ status</th><th>External signals</th><th>Action</th></tr></thead><tbody>' . $rows . '</tbody></table></div>';
}

$display .= COM_endBlock();

COM_output(COM_createHTMLDocument($display, array('pagetitle' => 'FAQ Coverage')));
