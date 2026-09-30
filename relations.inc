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

function faq_categoryRelationTableExists()
{
    global $_TABLES;

    if (empty($_TABLES['faq_category_relations'])) {
        return false;
    }

    $table = str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $_TABLES['faq_category_relations']);
    $result = DB_query("SHOW TABLES LIKE '" . DB_escapeString($table) . "'");
    return DB_numRows($result) > 0;
}

function faq_categoryRelationEnsureTable()
{
    global $_TABLES;

    if (faq_categoryRelationTableExists() || empty($_TABLES['faq_category_relations'])) {
        return faq_categoryRelationTableExists();
    }

    $sql = "CREATE TABLE IF NOT EXISTS {$_TABLES['faq_category_relations']} (
                relation_id int(11) unsigned NOT NULL AUTO_INCREMENT,
                category_id varchar(40) NOT NULL,
                provider varchar(40) NOT NULL,
                item_id varchar(128) NOT NULL,
                item_subtype varchar(64) NOT NULL DEFAULT '',
                placement varchar(16) NOT NULL DEFAULT 'automatic',
                topic_scope varchar(16) NOT NULL DEFAULT 'both',
                sort_order int(11) NOT NULL DEFAULT 0,
                enabled tinyint(1) unsigned NOT NULL DEFAULT 1,
                created datetime DEFAULT NULL,
                modified datetime DEFAULT NULL,
                PRIMARY KEY (relation_id),
                UNIQUE KEY category_target (category_id, provider, item_id, item_subtype),
                KEY target_lookup (provider, item_id, item_subtype, enabled),
                KEY category_lookup (category_id, enabled)
            ) ENGINE=InnoDB";
    DB_query($sql);

    return faq_categoryRelationTableExists();
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

function faq_relationNormalizeTopicScope($provider, $scope)
{
    $provider = faq_normalizeProvider($provider);
    if ($provider !== 'topic') {
        return 'both';
    }

    $scope = strtolower(trim((string) $scope));
    return in_array($scope, array('topic', 'articles', 'both'), true)
        ? $scope : 'both';
}

function faq_relationTopicScopeSql($alias, $scope)
{
    $scope = strtolower(trim((string) $scope));
    if ($scope === 'topic') {
        return " AND {$alias}.topic_scope IN ('both', 'topic')";
    }
    if ($scope === 'articles') {
        return " AND {$alias}.topic_scope IN ('both', 'articles')";
    }

    return '';
}

function faq_relationColumnExists($table, $column)
{
    $table = str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), (string) $table);
    $column = DB_escapeString((string) $column);
    $result = DB_query("SHOW COLUMNS FROM {$table} LIKE '{$column}'");
    return $result !== false && DB_numRows($result) > 0;
}

function faq_relationEnsureTopicScopeColumns()
{
    global $_TABLES;

    $tables = array();
    if (faq_relationTableExists()) {
        $tables[] = $_TABLES['faq_relations'];
    }
    if (faq_categoryRelationTableExists()) {
        $tables[] = $_TABLES['faq_category_relations'];
    }

    foreach ($tables as $table) {
        if (!faq_relationColumnExists($table, 'topic_scope')) {
            DB_query("ALTER TABLE {$table}
                      ADD topic_scope varchar(16) NOT NULL DEFAULT 'both'
                      AFTER placement");
        }
    }
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
        'staticpages' => true,
        'topic' => true
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

function faq_relationCoreTopicOptions($limit)
{
    global $_CONF, $_TABLES;

    $rows = array();
    $limit = max(1, min(200, (int) $limit));

    if (empty($_TABLES['topics'])) {
        return $rows;
    }

    $sql = "SELECT t.tid AS id, t.topic AS title
              FROM {$_TABLES['topics']} AS t"
         . COM_getPermSQL('WHERE', 0, 2, 't')
         . " ORDER BY t.topic ASC, t.tid ASC LIMIT " . $limit;

    $result = DB_query($sql, 1);
    if ($result === false) {
        return $rows;
    }

    while ($row = DB_fetchArray($result)) {
        $id = (string) $row['id'];
        $url = rtrim($_CONF['site_url'], '/') . '/index.php?topic=' . rawurlencode($id);
        $rows[] = array(
            'id' => $id,
            'title' => (string) $row['title'],
            'url' => $url,
            'subtype' => 'topic'
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
    global $LANG_FAQ_RELATIONS;

    $provider = faq_normalizeProvider($provider);
    $limit = max(1, min(200, (int) $limit));

    if ($provider === 'article') {
        return array(
            'supported' => true,
            'items' => faq_relationCoreArticleOptions($limit),
            'message' => ''
        );
    }

    if ($provider === 'topic') {
        return array(
            'supported' => true,
            'items' => faq_relationCoreTopicOptions($limit),
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
                'message' => $LANG_FAQ_RELATIONS['provider_no_item_info']
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

function faq_relationCategoryExists($category_id, $access = 2)
{
    global $_TABLES;

    $category_id = DB_escapeString($category_id);
    $sql = "SELECT cat.id
              FROM {$_TABLES['faq_category']} cat
             WHERE cat.id = '{$category_id}'"
         . COM_getPermSQL('AND', 0, $access, 'cat');

    $result = DB_query($sql);
    return DB_numRows($result) === 1;
}

function faq_categoryRelationAdd($category_id, $provider, $item_id, $subtype = '', $placement = 'automatic', $sort_order = 0, $topic_scope = 'both')
{
    global $_TABLES;

    if (!faq_categoryRelationTableExists() || !faq_relationCategoryExists($category_id, 3)) {
        return false;
    }

    $provider = faq_normalizeProvider($provider);
    if (!faq_relationProviderAllowed($provider) || !faq_relationItemAllowed($item_id)) {
        return false;
    }

    $category_id = DB_escapeString($category_id);
    $provider = DB_escapeString($provider);
    $item_id = DB_escapeString($item_id);
    $subtype = DB_escapeString(substr((string) $subtype, 0, 64));
    $placement = strtolower(trim((string) $placement));
    if ($placement === 'before' || $placement === 'after') {
        $placement = 'automatic';
    }
    $placement = in_array($placement, array('automatic', 'manual'), true)
        ? $placement : 'automatic';
    $sort_order = (int) $sort_order;

    $sql = "INSERT INTO {$_TABLES['faq_category_relations']}
                (category_id, provider, item_id, item_subtype, placement, sort_order, enabled, created, modified)
            VALUES
                ('{$category_id}', '{$provider}', '{$item_id}', '{$subtype}', '{$placement}', {$sort_order}, 1, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                placement = VALUES(placement),
                sort_order = VALUES(sort_order),
                enabled = 1,
                modified = NOW()";

    DB_query($sql);
    faq_clearLocalCache();

    return true;
}

function faq_categoryRelationDelete($relation_id)
{
    global $_TABLES;

    if (!faq_categoryRelationTableExists()) {
        return false;
    }

    $relation_id = (int) $relation_id;
    if ($relation_id < 1) {
        return false;
    }

    DB_query("DELETE FROM {$_TABLES['faq_category_relations']} WHERE relation_id = {$relation_id}");
    faq_clearLocalCache();

    return true;
}

function faq_categoryRelationDeleteForCategory($category_id)
{
    global $_TABLES;

    if (!faq_categoryRelationTableExists()) {
        return;
    }

    $category_id = DB_escapeString($category_id);
    DB_query("DELETE FROM {$_TABLES['faq_category_relations']} WHERE category_id = '{$category_id}'");
    faq_clearLocalCache();
}

function faq_categoryRelationGetForItem($provider, $item_id, $subtype = '', $placement = '', $topic_scope = '')
{
    global $_TABLES;

    $items = array();
    $provider = faq_normalizeProvider($provider);
    if (!faq_categoryRelationTableExists()
        || !faq_relationProviderAllowed($provider)
        || !faq_relationItemAllowed($item_id)
    ) {
        return $items;
    }

    $provider = DB_escapeString($provider);
    $item_id = DB_escapeString($item_id);
    $subtype = DB_escapeString(substr((string) $subtype, 0, 64));

    $sql = "SELECT cr.relation_id AS category_relation_id,
                   cr.category_id, cr.provider, cr.item_id, cr.item_subtype,
                   cr.placement, cr.topic_scope, cr.sort_order,
                   faq.id AS faq_id, faq.title, faq.description, faq.category,
                   faq.hits, UNIX_TIMESTAMP(faq.date) AS modified
              FROM {$_TABLES['faq_category_relations']} cr
              JOIN {$_TABLES['faq_category']} cat ON cat.id = cr.category_id
              JOIN {$_TABLES['faq']} faq ON faq.category = cat.id
             WHERE cr.enabled = 1
               AND cr.provider = '{$provider}'
               AND cr.item_id = '{$item_id}'";

    if ($subtype !== '') {
        $sql .= " AND (cr.item_subtype = '' OR cr.item_subtype = '{$subtype}')";
    }

    if ($placement === 'automatic') {
        $sql .= " AND cr.placement <> 'manual'";
    } elseif ($placement !== '') {
        $placement = DB_escapeString($placement);
        $sql .= " AND cr.placement = '{$placement}'";
    }
    if ($provider === 'topic' && $topic_scope !== '') {
        $sql .= faq_relationTopicScopeSql('cr', $topic_scope);
    }

    $sql .= COM_getPermSQL('AND', 0, 2, 'faq')
          . COM_getPermSQL('AND', 0, 2, 'cat')
          . ' ORDER BY cr.sort_order ASC, faq.title ASC';

    $result = DB_query($sql);
    while ($row = DB_fetchArray($result)) {
        $items[] = $row;
    }

    return $items;
}

function faq_relationAdd($faq_id, $provider, $item_id, $subtype = '', $placement = 'automatic', $sort_order = 0, $topic_scope = 'both')
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

function faq_relationGetForItem($provider, $item_id, $subtype = '', $placement = '', $topic_scope = '')
{
    global $_TABLES;

    $items = array();
    $provider = faq_normalizeProvider($provider);
    if (!faq_relationTableExists() || !faq_relationProviderAllowed($provider) || !faq_relationItemAllowed($item_id)) {
        return $items;
    }

    $categoryItems = faq_categoryRelationGetForItem($provider, $item_id, $subtype, $placement, $topic_scope);

    $provider = DB_escapeString($provider);
    $item_id = DB_escapeString($item_id);
    $subtype = DB_escapeString(substr((string) $subtype, 0, 64));

    $sql = "SELECT rel.relation_id, rel.faq_id, rel.provider, rel.item_id, rel.item_subtype,
                   rel.placement, rel.topic_scope, rel.sort_order, faq.title, faq.description, faq.category,
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
    if ($provider === 'topic' && $topic_scope !== '') {
        $sql .= faq_relationTopicScopeSql('rel', $topic_scope);
    }

    $sql .= COM_getPermSQL('AND', 0, 2, 'faq')
          . COM_getPermSQL('AND', 0, 2, 'cat')
          . ' ORDER BY rel.sort_order ASC, faq.title ASC';

    $result = DB_query($sql);
    $seen = array();
    while ($row = DB_fetchArray($result)) {
        $faqKey = isset($row['faq_id']) ? (string) $row['faq_id'] : '';
        if ($faqKey !== '') {
            $seen[$faqKey] = true;
        }
        $items[] = $row;
    }

    foreach ($categoryItems as $row) {
        $faqKey = isset($row['faq_id']) ? (string) $row['faq_id'] : '';
        if ($faqKey !== '' && isset($seen[$faqKey])) {
            continue;
        }
        if ($faqKey !== '') {
            $seen[$faqKey] = true;
        }
        $items[] = $row;
    }

    usort($items, function ($left, $right) {
        $leftOrder = isset($left['sort_order']) ? (int) $left['sort_order'] : 0;
        $rightOrder = isset($right['sort_order']) ? (int) $right['sort_order'] : 0;
        if ($leftOrder === $rightOrder) {
            return strcasecmp(
                isset($left['title']) ? (string) $left['title'] : '',
                isset($right['title']) ? (string) $right['title'] : ''
            );
        }
        return ($leftOrder < $rightOrder) ? -1 : 1;
    });

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


/**
 * Return managed FAQ counts for every item of one provider in one query.
 *
 * This is used by Coverage to avoid one relation query per audited item.
 */
/**
 * Return FAQ ids grouped by target item for one provider in one query.
 */
function faq_relationFaqMapForProvider($provider, $topic_scope = '')
{
    global $_TABLES;

    $map = array();
    $provider = faq_normalizeProvider($provider);

    if (!faq_relationProviderAllowed($provider)) {
        return $map;
    }

    $providerSql = DB_escapeString($provider);
    $parts = array();

    if (faq_relationTableExists()) {
        $parts[] = "SELECT rel.item_id, rel.faq_id
                      FROM {$_TABLES['faq_relations']} rel
                      JOIN {$_TABLES['faq']} faq ON faq.id = rel.faq_id
                      JOIN {$_TABLES['faq_category']} cat ON cat.id = faq.category
                     WHERE rel.enabled = 1
                       AND rel.provider = '{$providerSql}'"
                 . (($provider === 'topic' && $topic_scope !== '') ? faq_relationTopicScopeSql('rel', $topic_scope) : '')
                 . COM_getPermSQL('AND', 0, 2, 'faq')
                 . COM_getPermSQL('AND', 0, 2, 'cat');
    }

    if (faq_categoryRelationTableExists()) {
        $parts[] = "SELECT cr.item_id, faq.id AS faq_id
                      FROM {$_TABLES['faq_category_relations']} cr
                      JOIN {$_TABLES['faq_category']} cat ON cat.id = cr.category_id
                      JOIN {$_TABLES['faq']} faq ON faq.category = cat.id
                     WHERE cr.enabled = 1
                       AND cr.provider = '{$providerSql}'"
                 . (($provider === 'topic' && $topic_scope !== '') ? faq_relationTopicScopeSql('cr', $topic_scope) : '')
                 . COM_getPermSQL('AND', 0, 2, 'faq')
                 . COM_getPermSQL('AND', 0, 2, 'cat');
    }

    if (empty($parts)) {
        return $map;
    }

    $result = DB_query(implode(" UNION ALL ", $parts));
    if ($result === false) {
        return $map;
    }

    while ($row = DB_fetchArray($result)) {
        $itemId = (string) $row['item_id'];
        $faqId = (string) $row['faq_id'];
        if (!isset($map[$itemId])) {
            $map[$itemId] = array();
        }
        $map[$itemId][$faqId] = true;
    }

    return $map;
}

/**
 * Return all article-to-topic assignments in one query.
 */
function faq_articleTopicMap()
{
    global $_TABLES;

    $map = array();
    if (empty($_TABLES['topic_assignments']) || empty($_TABLES['topics'])) {
        return $map;
    }

    $sql = "SELECT ta.id AS story_id, ta.tid
              FROM {$_TABLES['topic_assignments']} ta
              JOIN {$_TABLES['topics']} t ON t.tid = ta.tid
             WHERE ta.type = 'article'"
         . COM_getPermSQL('AND', 0, 2, 't')
         . " ORDER BY ta.id, ta.tid";

    $result = DB_query($sql);
    if ($result === false) {
        return $map;
    }

    while ($row = DB_fetchArray($result)) {
        $storyId = (string) $row['story_id'];
        if (!isset($map[$storyId])) {
            $map[$storyId] = array();
        }
        $map[$storyId][] = (string) $row['tid'];
    }

    return $map;
}

function faq_relationCountMapForProvider($provider, $topic_scope = '')
{
    global $_TABLES;

    $counts = array();
    $provider = faq_normalizeProvider($provider);

    if (!faq_relationProviderAllowed($provider)) {
        return $counts;
    }

    $providerSql = DB_escapeString($provider);
    $parts = array();

    if (faq_relationTableExists()) {
        $parts[] = "SELECT rel.item_id, rel.faq_id
                      FROM {$_TABLES['faq_relations']} rel
                      JOIN {$_TABLES['faq']} faq ON faq.id = rel.faq_id
                      JOIN {$_TABLES['faq_category']} cat ON cat.id = faq.category
                     WHERE rel.enabled = 1
                       AND rel.provider = '{$providerSql}'"
                 . COM_getPermSQL('AND', 0, 2, 'faq')
                 . COM_getPermSQL('AND', 0, 2, 'cat');
    }

    if (faq_categoryRelationTableExists()) {
        $parts[] = "SELECT cr.item_id, faq.id AS faq_id
                      FROM {$_TABLES['faq_category_relations']} cr
                      JOIN {$_TABLES['faq_category']} cat ON cat.id = cr.category_id
                      JOIN {$_TABLES['faq']} faq ON faq.category = cat.id
                     WHERE cr.enabled = 1
                       AND cr.provider = '{$providerSql}'"
                 . COM_getPermSQL('AND', 0, 2, 'faq')
                 . COM_getPermSQL('AND', 0, 2, 'cat');
    }

    if (empty($parts)) {
        return $counts;
    }

    $sql = "SELECT item_id, COUNT(DISTINCT faq_id) AS faq_count
              FROM (" . implode(" UNION ALL ", $parts) . ") faq_coverage_rel
             GROUP BY item_id";

    $result = DB_query($sql);
    if ($result === false) {
        return $counts;
    }

    while ($row = DB_fetchArray($result)) {
        $counts[(string) $row['item_id']] = (int) $row['faq_count'];
    }

    return $counts;
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
function faq_splitAnswerNavigation($description)
{
    $description = (string) $description;
    $result = array(
        'answer' => $description,
        'related' => ''
    );

    if (preg_match(
        '~<p\b[^>]*>\s*<(?:b|strong)\b[^>]*>\s*See also:\s*</(?:b|strong)>\s*((?:\[faq:[^\]]+\]\s*)+)(?:</p>)?\s*$~i',
        $description,
        $match,
        PREG_OFFSET_CAPTURE
    )) {
        $full = $match[0];
        $related = isset($match[1][0]) ? trim((string) $match[1][0]) : '';
        $offset = isset($full[1]) ? (int) $full[1] : -1;

        if ($offset >= 0) {
            $result['answer'] = rtrim(substr($description, 0, $offset));
            $result['related'] = $related;
        }
    }

    return $result;
}

function faq_contextualAnswerSource($description)
{
    $parts = faq_splitAnswerNavigation($description);
    return $parts['answer'];
}

function faq_plainTextExcerpt($description, $limit = 155)
{
    $parts = faq_splitAnswerNavigation($description);
    $text = PLG_replaceTags($parts['answer']);
    $text = html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', trim($text));

    if (function_exists('MBYTE_strlen') && function_exists('MBYTE_substr')) {
        if (MBYTE_strlen($text) > $limit) {
            $text = rtrim(MBYTE_substr($text, 0, max(1, $limit - 1))) . '…';
        }
    } elseif (strlen($text) > $limit) {
        $text = rtrim(substr($text, 0, max(1, $limit - 3))) . '...';
    }

    return $text;
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
        $answer = faq_renderContent($answerSource);

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

/**
 * Return every readable Geeklog topic assigned to an article.
 */
function faq_articleTopicIds($storyId)
{
    global $_TABLES;

    $topics = array();
    if (empty($_TABLES['topic_assignments']) || empty($_TABLES['topics'])) {
        return $topics;
    }

    $storyIdSql = DB_escapeString((string) $storyId);
    $sql = "SELECT DISTINCT ta.tid
              FROM {$_TABLES['topic_assignments']} ta
              JOIN {$_TABLES['topics']} t ON t.tid = ta.tid
             WHERE ta.type = 'article'
               AND ta.id = '{$storyIdSql}'"
         . COM_getPermSQL('AND', 0, 2, 't')
         . " ORDER BY ta.tid";

    $result = DB_query($sql);
    while ($row = DB_fetchArray($result)) {
        $topics[] = (string) $row['tid'];
    }

    return $topics;
}

/**
 * Detect FAQ-like content already embedded directly in an article.
 */
function faq_articleExternalSignals($storyId)
{
    global $_TABLES;

    if (empty($_TABLES['stories'])) {
        return array();
    }

    $storyIdSql = DB_escapeString((string) $storyId);
    $sql = "SELECT introtext, bodytext
              FROM {$_TABLES['stories']}
             WHERE sid = '{$storyIdSql}'
               AND draft_flag = 0
               AND date <= NOW()"
         . COM_getPermSQL('AND', 0, 2);

    $result = DB_query($sql);
    if ($result === false || DB_numRows($result) !== 1) {
        return array();
    }

    $row = DB_fetchArray($result);
    $content = (isset($row['introtext']) ? (string) $row['introtext'] : '')
             . "\n"
             . (isset($row['bodytext']) ? (string) $row['bodytext'] : '');

    return faq_coverageExternalSignals($content);
}

/**
 * Merge direct article associations with inherited topic associations.
 *
 * Direct article associations always win. Topic inheritance is suppressed when
 * the article already contains an external FAQ/Q&A signal. Duplicate FAQ ids
 * are removed across direct and multi-topic sources.
 */
function faq_relationGetForArticleWithTopics($storyId, $placement = 'automatic')
{
    $direct = faq_relationGetForItem('article', (string) $storyId, '', $placement);
    $seen = array();
    $items = array();

    foreach ($direct as $row) {
        $faqId = isset($row['faq_id']) ? (string) $row['faq_id'] : '';
        if ($faqId !== '') {
            $seen[$faqId] = true;
        }
        $row['_faq_origin'] = 'direct';
        $items[] = $row;
    }

    // Explicit direct associations are honored even when external FAQ content
    // exists. Only automatic topic inheritance is suppressed.
    if (!empty(faq_articleExternalSignals($storyId))) {
        return $items;
    }

    foreach (faq_articleTopicIds($storyId) as $topicId) {
        $topicItems = faq_relationGetForItem('topic', $topicId, 'topic', $placement, 'articles');
        foreach ($topicItems as $row) {
            $faqId = isset($row['faq_id']) ? (string) $row['faq_id'] : '';
            if ($faqId !== '' && isset($seen[$faqId])) {
                continue;
            }
            if ($faqId !== '') {
                $seen[$faqId] = true;
            }
            $row['_faq_origin'] = 'topic';
            $row['_faq_topic_id'] = $topicId;
            $items[] = $row;
        }
    }

    return $items;
}

function faq_renderArticleContextual($storyId, $placement = 'automatic')
{
    $items = faq_relationGetForArticleWithTopics($storyId, $placement);
    return faq_renderFaqItems($items, 'faq-contextual faq-contextual-article', false);
}

function faq_renderContextual($provider, $item_id, $subtype = '', $placement = '', $topic_scope = '')
{
    $items = faq_relationGetForItem($provider, $item_id, $subtype, $placement, $topic_scope);
    return faq_renderFaqItems($items, 'faq-contextual', false);
}

function faq_clearLocalCache()
{
    if (function_exists('CACHE_remove_instance')) {
        CACHE_remove_instance('faq');

        if (function_exists('CACHE_security_hash')) {
            $securityHash = CACHE_security_hash();
            CACHE_remove_instance('faq_coverage__article__' . $securityHash);
            CACHE_remove_instance('faq_coverage__staticpages__' . $securityHash);
            CACHE_remove_instance('faq_coverage_v2__article__' . $securityHash);
            CACHE_remove_instance('faq_coverage_v2__staticpages__' . $securityHash);
            CACHE_remove_instance('faq_coverage_v2__topic__' . $securityHash);
        }
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


/**
 * Enumerate native Geeklog content for Coverage when Core/Static Pages do not
 * expose the shared Item Info collection contract.
 *
 * This exception is deliberately limited to Geeklog-owned tables, following
 * the same read-only audit strategy used by Hub. Third-party plugin tables
 * must never be inspected here.
 *
 * @param string $provider
 * @param int    $limit
 * @return array|false
 */
function faq_coverageCoreCollection($provider, $limit = 100)
{
    global $_CONF, $_TABLES;

    $provider = faq_normalizeProvider($provider);
    $limit = (int) $limit;
    if ($limit < 0) {
        $limit = 0;
    } elseif ($limit > 200) {
        $limit = 200;
    }
    $limitSql = $limit > 0 ? ' LIMIT ' . $limit : '';
    $items = array();

    if ($provider === 'article') {
        if (empty($_TABLES['stories'])) {
            return false;
        }

        $sql = "SELECT s.sid AS id, s.title, s.introtext, s.bodytext, UNIX_TIMESTAMP(s.date) AS modified
                  FROM {$_TABLES['stories']} AS s
                 WHERE s.draft_flag = 0
                   AND s.date <= NOW()";

        if (function_exists('COM_getPermSQL')) {
            $sql .= COM_getPermSQL('AND', 0, 2, 's');
        }
        if (function_exists('COM_getLangSQL')) {
            $sql .= COM_getLangSQL('sid', 'AND', 's');
        }

        $sql .= " ORDER BY s.date DESC, s.title ASC" . $limitSql;
        $result = DB_query($sql, 1);
        if ($result === false) {
            return false;
        }

        while ($row = DB_fetchArray($result)) {
            $id = isset($row['id']) ? (string) $row['id'] : '';
            if ($id === '') {
                continue;
            }
            $url = rtrim($_CONF['site_url'], '/') . '/article.php?story=' . rawurlencode($id);
            if (function_exists('COM_buildUrl')) {
                $url = COM_buildUrl($url);
            }

            $items[] = array(
                'id' => $id,
                'title' => isset($row['title']) ? (string) $row['title'] : $id,
                'url' => $url,
                'date-modified' => isset($row['modified']) ? (int) $row['modified'] : 0,
                '_audit_content' => (isset($row['introtext']) ? (string) $row['introtext'] : '')
                    . "\n"
                    . (isset($row['bodytext']) ? (string) $row['bodytext'] : ''),
            );
        }

        return $items;
    }

    if ($provider === 'staticpages') {
        if (empty($_TABLES['staticpage'])) {
            return false;
        }

        $sql = "SELECT sp.sp_id AS id, sp.sp_title AS title, sp.sp_content AS content";

        // Older Static Pages schemas do not consistently expose a modified
        // timestamp. Coverage does not require one for the semantic audit.
        $sql .= " FROM {$_TABLES['staticpage']} AS sp"
              . " WHERE sp.draft_flag = 0 AND sp.template_flag = 0";

        if (function_exists('COM_getPermSQL')) {
            $sql .= COM_getPermSQL('AND', 0, 2, 'sp');
        }
        if (function_exists('COM_getLangSQL')) {
            $sql .= COM_getLangSQL('sp_id', 'AND', 'sp');
        }

        $sql .= " ORDER BY sp.sp_title ASC, sp.sp_id ASC" . $limitSql;
        $result = DB_query($sql, 1);
        if ($result === false) {
            return false;
        }

        while ($row = DB_fetchArray($result)) {
            $id = isset($row['id']) ? (string) $row['id'] : '';
            if ($id === '') {
                continue;
            }
            $url = rtrim($_CONF['site_url'], '/') . '/staticpages/index.php?page=' . rawurlencode($id);
            if (function_exists('COM_buildUrl')) {
                $url = COM_buildUrl($url);
            }

            $items[] = array(
                'id' => $id,
                'title' => isset($row['title']) ? (string) $row['title'] : $id,
                'url' => $url,
                'date-modified' => 0,
                '_audit_content' => isset($row['content']) ? (string) $row['content'] : '',
            );
        }

        return $items;
    }

    return false;
}

/**
 * Detect strong FAQ/Q&A signals in stored provider content that is not owned
 * by the FAQ plugin. This is an editorial audit signal, not a semantic proof.
 *
 * @param string $content
 * @return array
 */
function faq_coverageExternalSignals($content)
{
    global $LANG_FAQ_COVERAGE;

    $content = (string) $content;
    $signals = array();

    if ($content === '') {
        return $signals;
    }

    if (preg_match('/["\']@type["\']\s*:\s*["\']FAQPage["\']/i', $content)) {
        $signals[] = $LANG_FAQ_COVERAGE['signal_faqpage_jsonld'];
    }

    if (preg_match('~itemtype\s*=\s*["\'][^"\']*schema\\.org/FAQPage[^"\']*["\']~i', $content)) {
        $signals[] = $LANG_FAQ_COVERAGE['signal_faqpage_microdata'];
    }

    $hasQuestionSchema = preg_match('/["\']@type["\']\s*:\s*["\']Question["\']/i', $content)
        || preg_match('~itemtype\s*=\s*["\'][^"\']*schema\\.org/Question[^"\']*["\']~i', $content);
    $hasAnswerSchema = preg_match('/["\']@type["\']\s*:\s*["\']Answer["\']/i', $content)
        || preg_match('~itemtype\s*=\s*["\'][^"\']*schema\\.org/Answer[^"\']*["\']~i', $content);
    if ($hasQuestionSchema && $hasAnswerSchema) {
        $signals[] = $LANG_FAQ_COVERAGE['signal_question_answer_schema'];
    }

    $detailsCount = preg_match_all('~<details\b[^>]*>.*?<summary\b[^>]*>.*?</summary>.*?</details>~is', $content, $detailsMatches);
    if ($detailsCount >= 2) {
        $signals[] = $LANG_FAQ_COVERAGE['signal_details_summary'];
    }

    $hasFaqHeading = preg_match(
        '~<h[1-6]\b[^>]*>\s*(?:FAQ|Foire\s+aux\s+questions|Questions\s+fr[ée]quentes|Frequently\s+Asked\s+Questions)\b~iu',
        $content
    );
    if ($hasFaqHeading) {
        $signals[] = $LANG_FAQ_COVERAGE['signal_faq_heading'];
    }

    return array_values(array_unique($signals));
}
