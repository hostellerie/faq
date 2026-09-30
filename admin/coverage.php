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
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 50;

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

$coreAudit = in_array($provider, array('article', 'staticpages', 'topic'), true);
$coverageCacheTtl = 300;
$coverageCacheHit = false;
$coverageSummary = null;

if ($coreAudit) {
    $cacheKey = '';
    if (function_exists('CACHE_security_hash')) {
        $cacheKey = 'faq_coverage_v3__' . $provider . '__' . CACHE_security_hash();
    }

    if ($cacheKey !== ''
        && function_exists('CACHE_check_instance')
        && function_exists('CACHE_get_instance_update')
    ) {
        $cached = CACHE_check_instance($cacheKey);
        if ($cached !== false && $cached !== '') {
            $updated = CACHE_get_instance_update($cacheKey);
            if ($updated !== false && (time() - (int) $updated) <= $coverageCacheTtl) {
                $cacheData = @unserialize($cached);
                if (is_array($cacheData)
                    && isset($cacheData['items'])
                    && isset($cacheData['summary'])
                ) {
                    $items = $cacheData['items'];
                    $coverageSummary = $cacheData['summary'];
                    $coverageCacheHit = true;
                }
            }
        }
    }

    if (!$coverageCacheHit) {
        // Core coverage is an audit, not a picker: inspect every accessible item.
        // The expensive full scan is cached; page changes then reuse this result.
        $items = $provider === 'topic'
            ? faq_relationCoreTopicOptions(200)
            : faq_coverageCoreCollection($provider, 0);

        if (is_array($items)) {
            $relationCounts = faq_relationCountMapForProvider(
                $provider,
                $provider === 'topic' ? 'topic' : ''
            );
            $directFaqMap = $provider === 'article' ? faq_relationFaqMapForProvider('article') : array();
            $topicFaqMap = $provider === 'article' ? faq_relationFaqMapForProvider('topic', 'articles') : array();
            $articleTopicMap = $provider === 'article' ? faq_articleTopicMap() : array();
            $summaryBuild = array(
                'managed' => 0,
                'external' => 0,
                'both' => 0,
                'none' => 0
            );

            foreach ($items as $itemIndex => $item) {
                if (!is_array($item) || !isset($item['id'])) {
                    continue;
                }

                $itemId = (string) $item['id'];
                $count = isset($relationCounts[$itemId]) ? (int) $relationCounts[$itemId] : 0;
                $signals = isset($item['_audit_content'])
                    ? faq_coverageExternalSignals($item['_audit_content'])
                    : array();
                $origin = array();
                $topicInheritedBlocked = false;

                if ($provider === 'article') {
                    $visibleFaqs = isset($directFaqMap[$itemId]) ? $directFaqMap[$itemId] : array();
                    if (!empty($visibleFaqs)) {
                        $origin[] = 'direct';
                    }

                    $topicFaqs = array();
                    if (isset($articleTopicMap[$itemId])) {
                        foreach ($articleTopicMap[$itemId] as $topicId) {
                            if (!empty($topicFaqMap[$topicId])) {
                                foreach ($topicFaqMap[$topicId] as $faqId => $true) {
                                    $topicFaqs[$faqId] = true;
                                }
                            }
                        }
                    }

                    if (!empty($topicFaqs)) {
                        if (!empty($signals)) {
                            $topicInheritedBlocked = true;
                        } else {
                            foreach ($topicFaqs as $faqId => $true) {
                                $visibleFaqs[$faqId] = true;
                            }
                            $origin[] = 'topic';
                        }
                    }

                    $count = count($visibleFaqs);
                }

                $hasManaged = $count > 0;
                $hasExternal = !empty($signals);

                if ($hasManaged && $hasExternal) {
                    $rowStatus = 'both';
                } elseif ($hasManaged) {
                    $rowStatus = 'managed';
                } elseif ($hasExternal) {
                    $rowStatus = 'external';
                } else {
                    $rowStatus = 'none';
                }

                $items[$itemIndex]['_coverage_count'] = $count;
                $items[$itemIndex]['_coverage_signals'] = $signals;
                $items[$itemIndex]['_coverage_status'] = $rowStatus;
                $items[$itemIndex]['_coverage_origin'] = $origin;
                $items[$itemIndex]['_coverage_topic_blocked'] = $topicInheritedBlocked;
                $summaryBuild[$rowStatus]++;
            }

            $coverageSummary = $summaryBuild;

            if ($cacheKey !== '' && function_exists('CACHE_create_instance')) {
                CACHE_create_instance($cacheKey, serialize(array(
                    'items' => $items,
                    'summary' => $coverageSummary
                )));
            }
        }
    }
} else {
    $items = faq_providerCollection($provider, 'id,title,url,date-modified', array(
        'limit' => $limit,
        'order' => 'modified-desc'
    ));
}

$_SCRIPTS->setCSSFile('faq_admin', faq_assetPath('faq-admin.css'));
$display = faq_adminNavigation('coverage');
$display .= COM_startBlock($LANG_FAQ_COVERAGE['title']);

$display .= '<p class="faq-admin-help">'
          . htmlspecialchars($LANG_FAQ_COVERAGE['intro'], ENT_QUOTES, 'UTF-8')
          . '</p>';

$providerLabels = array(
    'article' => $LANG_FAQ_COVERAGE['articles'],
    'topic' => $LANG_FAQ_COVERAGE['topics'],
    'staticpages' => $LANG_FAQ_COVERAGE['static_pages'],
    'videos' => $LANG_FAQ_COVERAGE['videos'],
    'documents' => $LANG_FAQ_COVERAGE['documents'],
    'maps' => $LANG_FAQ_COVERAGE['maps'],
    'mediagallery' => $LANG_FAQ_COVERAGE['media_gallery']
);

$display .= '<form method="get" action="' . $_CONF['site_admin_url']
          . '/plugins/faq/coverage.php" class="faq-admin-toolbar">';
$display .= '<label for="faq-coverage-provider">'
          . htmlspecialchars($LANG_FAQ_COVERAGE['provider'], ENT_QUOTES, 'UTF-8')
          . '</label><select id="faq-coverage-provider" name="provider">';
foreach ($providerLabels as $key => $label) {
    $selected = $provider === $key ? ' selected' : '';
    $display .= '<option value="' . $key . '"' . $selected . '>'
              . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
}
$display .= '</select>';

$display .= '<label for="faq-coverage-status">'
          . htmlspecialchars($LANG_FAQ_COVERAGE['faq_status'], ENT_QUOTES, 'UTF-8')
          . '</label><select id="faq-coverage-status" name="status">'
          . '<option value="all"' . ($status === 'all' ? ' selected' : '') . '>'
          . htmlspecialchars($LANG_FAQ_COVERAGE['all'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="managed"' . ($status === 'managed' ? ' selected' : '') . '>'
          . htmlspecialchars($LANG_FAQ_COVERAGE['managed_faq_only'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="external"' . ($status === 'external' ? ' selected' : '') . '>'
          . htmlspecialchars($LANG_FAQ_COVERAGE['external_faq_only'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="both"' . ($status === 'both' ? ' selected' : '') . '>'
          . htmlspecialchars($LANG_FAQ_COVERAGE['managed_external'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '<option value="none"' . ($status === 'none' ? ' selected' : '') . '>'
          . htmlspecialchars($LANG_FAQ_COVERAGE['no_faq_detected'], ENT_QUOTES, 'UTF-8') . '</option>'
          . '</select><input type="submit" value="'
          . htmlspecialchars($LANG_FAQ_COVERAGE['show'], ENT_QUOTES, 'UTF-8') . '"></form>';

if ($items === false) {
    $display .= '<p>'
              . htmlspecialchars($LANG_FAQ_COVERAGE['provider_not_enumerable'], ENT_QUOTES, 'UTF-8')
              . '</p>';
} else {
    if ($coreAudit) {
        $display .= '<p class="faq-admin-audit-note"><strong>'
                  . htmlspecialchars($LANG_FAQ_COVERAGE['core_audit_label'], ENT_QUOTES, 'UTF-8')
                  . '</strong> '
                  . htmlspecialchars($LANG_FAQ_COVERAGE['core_audit_help'], ENT_QUOTES, 'UTF-8')
                  . '</p>';
    }

    $managedOnly = 0;
    $externalOnly = 0;
    $both = 0;
    $none = 0;
    $filteredItems = array();

    if ($coreAudit && is_array($coverageSummary)) {
        $managedOnly = isset($coverageSummary['managed']) ? (int) $coverageSummary['managed'] : 0;
        $externalOnly = isset($coverageSummary['external']) ? (int) $coverageSummary['external'] : 0;
        $both = isset($coverageSummary['both']) ? (int) $coverageSummary['both'] : 0;
        $none = isset($coverageSummary['none']) ? (int) $coverageSummary['none'] : 0;
    }

    foreach ($items as $item) {
        if (!is_array($item) || !isset($item['id'])) {
            continue;
        }

        if ($coreAudit) {
            $count = isset($item['_coverage_count']) ? (int) $item['_coverage_count'] : 0;
            $signals = isset($item['_coverage_signals']) && is_array($item['_coverage_signals'])
                ? $item['_coverage_signals'] : array();
            $rowStatus = isset($item['_coverage_status']) ? $item['_coverage_status'] : 'none';
        } else {
            $count = faq_relationCountForItem($provider, (string) $item['id']);
            $signals = array();
            $hasManaged = $count > 0;
            $hasExternal = false;

            if ($hasManaged) {
                $managedOnly++;
                $rowStatus = 'managed';
            } else {
                $none++;
                $rowStatus = 'none';
            }

            $item['_coverage_count'] = $count;
            $item['_coverage_signals'] = $signals;
            $item['_coverage_status'] = $rowStatus;
        }

        if ($status !== 'all' && $status !== $rowStatus) {
            continue;
        }

        $filteredItems[] = $item;
    }

    $baseCoverageUrl = $_CONF['site_admin_url']
        . '/plugins/faq/coverage.php?provider=' . rawurlencode($provider);
    $total = $managedOnly + $externalOnly + $both + $none;

    $filteredTotal = count($filteredItems);
    $pageCount = max(1, (int) ceil($filteredTotal / $perPage));
    if ($page > $pageCount) {
        $page = $pageCount;
    }
    $offset = ($page - 1) * $perPage;
    $pageItems = array_slice($filteredItems, $offset, $perPage);
    $rows = '';

    foreach ($pageItems as $item) {
        $count = isset($item['_coverage_count']) ? (int) $item['_coverage_count'] : 0;
        $signals = isset($item['_coverage_signals']) && is_array($item['_coverage_signals'])
            ? $item['_coverage_signals'] : array();
        $rowStatus = isset($item['_coverage_status']) ? $item['_coverage_status'] : 'none';
        $origin = isset($item['_coverage_origin']) && is_array($item['_coverage_origin'])
            ? $item['_coverage_origin'] : array();
        $topicBlocked = !empty($item['_coverage_topic_blocked']);

        $title = isset($item['title']) ? $item['title'] : $item['id'];
        $title = html_entity_decode((string) $title, ENT_QUOTES, 'UTF-8');
        $url = isset($item['url']) ? $item['url'] : '';
        $titleHtml = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        if ($url !== '') {
            $titleHtml = '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">'
                       . $titleHtml . '</a>';
        }

        $signalHtml = empty($signals)
            ? '&mdash;'
            : htmlspecialchars(implode(', ', $signals), ENT_QUOTES, 'UTF-8');

        $stateLabel = $rowStatus === 'both'
            ? $LANG_FAQ_COVERAGE['managed_external']
            : ($rowStatus === 'managed'
                ? $LANG_FAQ_COVERAGE['managed']
                : ($rowStatus === 'external'
                    ? $LANG_FAQ_COVERAGE['external_signal']
                    : $LANG_FAQ_COVERAGE['none']));

        $actions = '<a href="' . $_CONF['site_admin_url']
                 . '/plugins/faq/relations.php?provider=' . rawurlencode($provider)
                 . '&amp;item_id=' . rawurlencode((string) $item['id']) . '">'
                 . htmlspecialchars($LANG_FAQ_COVERAGE['manage'], ENT_QUOTES, 'UTF-8')
                 . '</a>';

        if ($provider === 'article') {
            $actions .= ' &middot; <a href="' . $_CONF['site_admin_url']
                     . '/story.php?mode=edit&amp;sid='
                     . rawurlencode((string) $item['id']) . '">'
                     . htmlspecialchars($LANG_FAQ_COVERAGE['edit'], ENT_QUOTES, 'UTF-8') . '</a>';
        } elseif ($provider === 'staticpages') {
            $actions .= ' &middot; <a href="' . $_CONF['site_admin_url']
                     . '/plugins/staticpages/index.php?mode=edit&amp;sp_id='
                     . rawurlencode((string) $item['id']) . '">'
                     . htmlspecialchars($LANG_FAQ_COVERAGE['edit'], ENT_QUOTES, 'UTF-8') . '</a>';
        }

        $rows .= '<tr><td>' . $titleHtml . '<br><small>'
               . htmlspecialchars((string) $item['id'], ENT_QUOTES, 'UTF-8') . '</small></td>';
        $originLabels = array();
        foreach ($origin as $originKey) {
            if ($originKey === 'direct') {
                $originLabels[] = $LANG_FAQ_COVERAGE['origin_direct'];
            } elseif ($originKey === 'topic') {
                $originLabels[] = $LANG_FAQ_COVERAGE['origin_topic'];
            }
        }
        if ($topicBlocked) {
            $originLabels[] = $LANG_FAQ_COVERAGE['topic_inheritance_blocked'];
        }

        $rows .= '<td><strong>' . htmlspecialchars($stateLabel, ENT_QUOTES, 'UTF-8')
               . '</strong><br><small>'
               . htmlspecialchars($LANG_FAQ_COVERAGE['managed_relations'], ENT_QUOTES, 'UTF-8')
               . ' ' . $count;
        if (!empty($originLabels)) {
            $rows .= '<br>' . htmlspecialchars(implode(' · ', $originLabels), ENT_QUOTES, 'UTF-8');
        }
        $rows .= '</small></td>';
        $rows .= '<td>' . $signalHtml . '</td>';
        $rows .= '<td>' . $actions . '</td></tr>';
    }

    $summary = array(
        'managed' => array($managedOnly, $LANG_FAQ_COVERAGE['managed_only']),
        'external' => array($externalOnly, $LANG_FAQ_COVERAGE['external_only']),
        'both' => array($both, $LANG_FAQ_COVERAGE['both']),
        'none' => array($none, $LANG_FAQ_COVERAGE['none']),
        'all' => array($total, $LANG_FAQ_COVERAGE['all'])
    );

    $display .= '<div class="faq-admin-summary">';
    foreach ($summary as $summaryKey => $summaryData) {
        $display .= '<a class="faq-admin-summary-link'
                  . ($status === $summaryKey ? ' active' : '')
                  . '" href="'
                  . htmlspecialchars($baseCoverageUrl . '&status=' . $summaryKey, ENT_QUOTES, 'UTF-8')
                  . '"><strong>' . (int) $summaryData[0] . '</strong>'
                  . htmlspecialchars($summaryData[1], ENT_QUOTES, 'UTF-8') . '</a>';
    }
    $display .= '</div>';

    $display .= '<div class="faq-admin-table"><table class="admin-list"><thead><tr>'
              . '<th>' . htmlspecialchars($LANG_FAQ_COVERAGE['content'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_COVERAGE['faq_status'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_COVERAGE['external_signals'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '<th>' . htmlspecialchars($LANG_FAQ_COVERAGE['action'], ENT_QUOTES, 'UTF-8') . '</th>'
              . '</tr></thead><tbody>' . $rows . '</tbody></table></div>';

    if ($pageCount > 1) {
        $paginationBase = $baseCoverageUrl . '&status=' . rawurlencode($status);
        $display .= '<nav class="faq-admin-pagination" aria-label="Pagination">';

        $startPage = max(1, $page - 3);
        $endPage = min($pageCount, $page + 3);

        if ($startPage > 1) {
            $display .= '<a href="' . htmlspecialchars($paginationBase . '&page=1', ENT_QUOTES, 'UTF-8') . '">1</a>';
            if ($startPage > 2) {
                $display .= '<span>&hellip;</span>';
            }
        }

        for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++) {
            if ($pageNumber === $page) {
                $display .= '<strong aria-current="page">' . $pageNumber . '</strong>';
            } else {
                $display .= '<a href="'
                          . htmlspecialchars($paginationBase . '&page=' . $pageNumber, ENT_QUOTES, 'UTF-8')
                          . '">' . $pageNumber . '</a>';
            }
        }

        if ($endPage < $pageCount) {
            if ($endPage < $pageCount - 1) {
                $display .= '<span>&hellip;</span>';
            }
            $display .= '<a href="'
                      . htmlspecialchars($paginationBase . '&page=' . $pageCount, ENT_QUOTES, 'UTF-8')
                      . '">' . $pageCount . '</a>';
        }

        $display .= '</nav>';
    }
}

$display .= COM_endBlock();

COM_output(COM_createHTMLDocument(
    $display,
    array('pagetitle' => $LANG_FAQ_COVERAGE['title'])
));
