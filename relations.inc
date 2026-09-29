<?php

if (strpos($_SERVER['PHP_SELF'], 'relations.inc') !== false) {
    die('This file can not be used on its own.');
}

function faq_relationTableExists()
{
    global $_TABLES;

    if (empty($_TABLES['faq_relations'])) {
        return false;
    }

    $table = str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $_TABLES['faq_relations']);
    $result = DB_query("SHOW TABLES LIKE '" . DB_escapeString($table) . "'");
    return DB_numRows($result) > 0;
}

function faq_relationProviderAllowed($provider)
{
    return (bool) preg_match('/^[a-z0-9_-]{1,40}$/i', $provider);
}

function faq_normalizeProvider($provider)
{
    $provider = strtolower(trim((string) $provider));
    if ($provider === 'story') {
        return 'article';
    }
    if ($provider === 'staticpage') {
        return 'staticpages';
    }
    return $provider;
}


function faq_relationNormalizeInfoRecord($record, $fallbackId = '')
{
    $item = array(
        'id' => (string) $fallbackId,
        'title' => (string) $fallbackId,
        'url' => '',
        'subtype' => ''
    );

    if (!is_array($record)) {
        return $item;
    }

    if (isset($record['id']) || isset($record['title']) || isset($record['url']) || isset($record['subtype'])) {
        if (isset($record['id']) && (string) $record['id'] !== '') {
            $item['id'] = (string) $record['id'];
        }
        if (isset($record['title']) && (string) $record['title'] !== '') {
            $item['title'] = (string) $record['title'];
        }
        if (isset($record['url'])) {
            $item['url'] = (string) $record['url'];
        }
        if (isset($record['subtype'])) {
            $item['subtype'] = (string) $record['subtype'];
        }
        if ($item['subtype'] === '' && isset($record['type']) && (string) $record['type'] !== '') {
            $item['subtype'] = (string) $record['type'];
        }

        return $item;
    }

    if (isset($record[0])) {
        $item['id'] = (string) $record[0];
    }
    if (isset($record[1]) && (string) $record[1] !== '') {
        $item['title'] = (string) $record[1];
    }
    if (isset($record[2])) {
        $item['url'] = (string) $record[2];
    }

    return $item;
}

function faq_relationProviderTypes()
{
    global $_PLUGINS;

    $types = array(
        'article' => true,
        'staticpages' => true
    );

    if (is_array($_PLUGINS)) {
        foreach ($_PLUGINS as $plugin) {
            $plugin = faq_normalizeProvider($plugin);
            if ($plugin === '' || $plugin === 'faq') {
                continue;
            }

            if (function_exists('plugin_getiteminfo_' . $plugin)) {
                $types[$plugin] = true;
            }
        }
    }

    $types = array_keys($types);
    sort($types, SORT_STRING);

    return $types;
}

function faq_relationCoreArticleOptions($limit)
{
    global $_TABLES;

    $rows = array();
    $limit = max(1, min(200, (int) $limit));

    if (empty($_TABLES['stories'])) {
        return $rows;
    }

    $sql = "SELECT s.sid AS id, s.title AS title
              FROM {$_TABLES['stories']} AS s
             WHERE s.draft_flag = 0
               AND s.date <= NOW()"
         . COM_getPermSQL('AND', 0, 2, 's');

    if (function_exists('COM_getLangSQL')) {
        $sql .= COM_getLangSQL('sid', 'AND', 's');
    }

    $sql .= " ORDER BY s.date DESC, s.title ASC LIMIT " . $limit;

    $result = DB_query($sql, 1);
    if ($result === false) {
        return $rows;
    }

    while ($row = DB_fetchArray($result)) {
        $rows[] = array(
            'id' => (string) $row['id'],
            'title' => (string) $row['title'],
            'url' => '',
            'subtype' => ''
        );
    }

    return $rows;
}

function faq_relationCoreStaticPageOptions($limit)
{
    global $_TABLES;

    $rows = array();
    $limit = max(1, min(200, (int) $limit));

    if (empty($_TABLES['staticpage'])) {
        return $rows;
    }

    $sql = "SELECT sp.sp_id AS id, sp.sp_title AS title
              FROM {$_TABLES['staticpage']} AS sp"
         . COM_getPermSQL('WHERE', 0, 2, 'sp')
         . " ORDER BY sp.sp_title ASC, sp.sp_id ASC LIMIT " . $limit;

    $result = DB_query($sql, 1);
    if ($result === false) {
        return $rows;
    }

    while ($row = DB_fetchArray($result)) {
        $rows[] = array(
            'id' => (string) $row['id'],
            'title' => (string) $row['title'],
            'url' => '',
            'subtype' => ''
        );
    }

    return $rows;
}

function faq_relationObjectOptions($provider, $limit = 100)
{
    $provider = faq_normalizeProvider($provider);
    $limit = max(1, min(200, (int) $limit));

    if ($provider === 'article') {
        return array(
            'supported' => true,
            'items' => faq_relationCoreArticleOptions($limit),
            'message' => ''
        );
    }

    if ($provider === 'staticpages') {
        $items = array();

        if (function_exists('PLG_getItemInfo') && function_exists('plugin_getiteminfo_staticpages')) {
            try {
                $items = PLG_getItemInfo(
                    'staticpages',
                    '*',
                    'id,title,url,subtype,type',
                    0,
                    array('limit' => $limit, 'order' => 'modified-desc')
                );
            } catch (Exception $e) {
                $items = array();
            }
        }

        if (!is_array($items) || empty($items)) {
            $items = faq_relationCoreStaticPageOptions($limit);
        }
    } else {
        if (!function_exists('PLG_getItemInfo') || !function_exists('plugin_getiteminfo_' . $provider)) {
            return array(
                'supported' => false,
                'items' => array(),
                'message' => 'This provider does not expose Geeklog Item Info.'
            );
        }

        try {
            $items = PLG_getItemInfo(
                $provider,
                '*',
                'id,title,url,subtype,type',
                0,
                array('limit' => $limit, 'order' => 'modified-desc')
            );
        } catch (Exception $e) {
            $items = array();
        }
    }

    if (!is_array($items)) {
        $items = array();
    }

    $normalized = array();
    foreach ($items as $record) {
        $item = faq_relationNormalizeInfoRecord($record);
        $item['id'] = substr(trim((string) $item['id']), 0, 128);
        $item['subtype'] = substr(trim((string) $item['subtype']), 0, 64);

        if ($item['id'] === '') {
            continue;
        }
        if ($item['title'] === '') {
            $item['title'] = $item['id'];
        }

        $normalized[$item['id']] = $item;
    }

    uasort($normalized, function ($left, $right) {
        return strcasecmp((string) $left['title'], (string) $right['title']);
    });

    return array(
        'supported' => true,
        'items' => array_values($normalized),
        'message' => empty($normalized)
            ? 'The provider returned no selectable content.'
            : count($normalized) . ' selectable item(s).'
    );
}

function faq_relationItemAllowed($item_id)
{
    return is_string($item_id) && $item_id !== '' && strlen($item_id) <= 128;
}

function faq_relationFaqExists($faq_id, $access = 2)
{
    global $_TABLES;

    $faq_id = DB_escapeString($faq_id);
    $sql = "SELECT faq.id
              FROM {$_TABLES['faq']} faq, {$_TABLES['faq_category']} cat
             WHERE faq.category = cat.id
               AND faq.id = '{$faq_id}'"
         . COM_getPermSQL('AND', 0, $access, 'faq')
         . COM_getPermSQL('AND', 0, $access, 'cat');

    $result = DB_query($sql);
    return DB_numRows($result) === 1;
}

function faq_relationAdd($faq_id, $provider, $item_id, $subtype = '', $placement = 'automatic', $sort_order = 0)
{
    global $_TABLES;

    if (!faq_relationTableExists() || !faq_relationFaqExists($faq_id, 3)) {
        return false;
    }
    $provider = faq_normalizeProvider($provider);
    if (!faq_relationProviderAllowed($provider) || !faq_relationItemAllowed($item_id)) {
        return false;
    }

    $faq_id = DB_escapeString($faq_id);
    $provider = DB_escapeString($provider);
    $item_id = DB_escapeString($item_id);
    $subtype = DB_escapeString(substr((string) $subtype, 0, 64));
    $placement = strtolower(trim((string) $placement));
    if ($placement === 'before' || $placement === 'after') {
        // Legacy 1.3.0-development values: Geeklog exposes one provider-defined
        // PLG_itemDisplay() insertion point, not generic before/after slots.
        $placement = 'automatic';
    }
    $placement = in_array($placement, array('automatic', 'manual'), true)
        ? $placement : 'automatic';
    $sort_order = (int) $sort_order;

    $sql = "INSERT INTO {$_TABLES['faq_relations']}
                (faq_id, provider, item_id, item_subtype, placement, sort_order, enabled, created, modified)
            VALUES
                ('{$faq_id}', '{$provider}', '{$item_id}', '{$subtype}', '{$placement}', {$sort_order}, 1, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                placement = VALUES(placement),
                sort_order = VALUES(sort_order),
                enabled = 1,
                modified = NOW()";

    DB_query($sql);
    faq_clearLocalCache();

    return true;
}

function faq_relationDelete($relation_id)
{
    global $_TABLES;

    if (!faq_relationTableExists()) {
        return false;
    }

    $relation_id = (int) $relation_id;
    if ($relation_id < 1) {
        return false;
    }

    DB_query("DELETE FROM {$_TABLES['faq_relations']} WHERE relation_id = {$relation_id}");
    faq_clearLocalCache();

    return true;
}

function faq_relationDeleteForFaq($faq_id)
{
    global $_TABLES;

    if (!faq_relationTableExists()) {
        return;
    }

    $faq_id = DB_escapeString($faq_id);
    DB_query("DELETE FROM {$_TABLES['faq_relations']} WHERE faq_id = '{$faq_id}'");
    faq_clearLocalCache();
}

function faq_relationGetForItem($provider, $item_id, $subtype = '', $placement = '')
{
    global $_TABLES;

    $items = array();
    $provider = faq_normalizeProvider($provider);
    if (!faq_relationTableExists() || !faq_relationProviderAllowed($provider) || !faq_relationItemAllowed($item_id)) {
        return $items;
    }

    $provider = DB_escapeString($provider);
    $item_id = DB_escapeString($item_id);
    $subtype = DB_escapeString(substr((string) $subtype, 0, 64));

    $sql = "SELECT rel.relation_id, rel.faq_id, rel.provider, rel.item_id, rel.item_subtype,
                   rel.placement, rel.sort_order, faq.title, faq.description, faq.category,
                   faq.hits, UNIX_TIMESTAMP(faq.date) AS modified
              FROM {$_TABLES['faq_relations']} rel
              JOIN {$_TABLES['faq']} faq ON faq.id = rel.faq_id
              JOIN {$_TABLES['faq_category']} cat ON cat.id = faq.category
             WHERE rel.enabled = 1
               AND rel.provider = '{$provider}'
               AND rel.item_id = '{$item_id}'";

    /*
     * PLG_itemDisplay() only passes item id + type, not a subtype. When no
     * subtype is supplied by the caller, do not filter subtype-specific
     * relations out. The subtype remains useful metadata for consumers that
     * can provide it explicitly.
     */
    if ($subtype !== '') {
        $sql .= " AND (rel.item_subtype = '' OR rel.item_subtype = '{$subtype}')";
    }

    if ($placement === 'automatic') {
        $sql .= " AND rel.placement <> 'manual'";
    } elseif ($placement !== '') {
        $placement = DB_escapeString($placement);
        $sql .= " AND rel.placement = '{$placement}'";
    }

    $sql .= COM_getPermSQL('AND', 0, 2, 'faq')
          . COM_getPermSQL('AND', 0, 2, 'cat')
          . ' ORDER BY rel.sort_order ASC, faq.title ASC';

    $result = DB_query($sql);
    while ($row = DB_fetchArray($result)) {
        $items[] = $row;
    }

    return $items;
}

function faq_relationGetForFaq($faq_id)
{
    global $_TABLES;

    $items = array();
    if (!faq_relationTableExists()) {
        return $items;
    }

    $faq_id = DB_escapeString($faq_id);
    $result = DB_query("SELECT relation_id, faq_id, provider, item_id, item_subtype, placement, sort_order, enabled,
                               UNIX_TIMESTAMP(created) AS created, UNIX_TIMESTAMP(modified) AS modified
                          FROM {$_TABLES['faq_relations']}
                         WHERE faq_id = '{$faq_id}'
                         ORDER BY provider, item_id, sort_order");

    while ($row = DB_fetchArray($result)) {
        $items[] = $row;
    }

    return $items;
}

function faq_relationCountForItem($provider, $item_id, $subtype = '')
{
    if (!faq_relationTableExists()) {
        return 0;
    }

    return count(faq_relationGetForItem($provider, $item_id, $subtype));
}

/**
 * Remove standalone-FAQ navigation helpers from contextual answers.
 *
 * Historical FAQ content may end with a "See also:" paragraph containing
 * FAQ autotags. Keep that navigation on the canonical FAQ page, but omit it
 * when the answer is embedded into another provider.
 *
 * @param string $description
 * @return string
 */
function faq_contextualAnswerSource($description)
{
    $description = (string) $description;

    return preg_replace(
        '~<p\b[^>]*>\s*<(?:b|strong)\b[^>]*>\s*See also:\s*</(?:b|strong)>\s*(?:\[faq:[^\]]+\]\s*)+$~i',
        '',
        $description
    );
}

function faq_renderFaqItems($items, $css_class, $asFaqPage)
{
    global $_CONF, $LANG_FAQ_COMMON;

    if (!is_array($items) || count($items) === 0) {
        return '';
    }

    $label = isset($LANG_FAQ_COMMON['FAQs']) ? $LANG_FAQ_COMMON['FAQs'] : 'FAQs';
    $permalink = isset($LANG_FAQ_COMMON['permalink']) ? $LANG_FAQ_COMMON['permalink'] : 'Permalink';

    $html = '<section class="faq-context-block ' . htmlspecialchars($css_class, ENT_QUOTES, 'UTF-8') . '" aria-label="'
          . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">';
    $html .= '<div class="faq-context-title">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</div>';

    foreach ($items as $item) {
        if (empty($item['title']) || !isset($item['description'])) {
            continue;
        }

        $faq_id = isset($item['faq_id']) ? $item['faq_id'] : (isset($item['id']) ? $item['id'] : '');
        $question = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
        $answerSource = $asFaqPage
            ? (string) $item['description']
            : faq_contextualAnswerSource($item['description']);
        $answer = PLG_replaceTags($answerSource);

        $html .= '<details class="faq-contextual-item">';
        $html .= '<summary class="faq-contextual-question">' . $question . '</summary>';
        $html .= '<div class="faq-contextual-answer">' . $answer;

        if ($asFaqPage && $faq_id !== '') {
            $url = $_CONF['site_url'] . '/faq/index.php?faq=' . rawurlencode($faq_id);
            $html .= '<p class="faq-contextual-permalink"><a href="'
                  . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">'
                  . htmlspecialchars($permalink, ENT_QUOTES, 'UTF-8') . '</a></p>';
        }

        $html .= '</div></details>';
    }

    $html .= '</section>';
    $html .= faq_structuredQuestions($items, $asFaqPage);

    return $html;
}

function faq_renderContextual($provider, $item_id, $subtype = '', $placement = '')
{
    $items = faq_relationGetForItem($provider, $item_id, $subtype, $placement);
    return faq_renderFaqItems($items, 'faq-contextual', false);
}

function faq_clearLocalCache()
{
    if (function_exists('CACHE_remove_instance')) {
        CACHE_remove_instance('faq');
    }
}

function faq_providerCollection($provider, $fields, $options)
{
    if (!function_exists('PLG_getItemInfo')) {
        return false;
    }

    $items = PLG_getItemInfo($provider, '*', $fields, 0, $options);
    return is_array($items) ? $items : false;
}
