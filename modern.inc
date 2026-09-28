<?php

if (strpos($_SERVER['PHP_SELF'], 'modern.inc') !== false) {
    die('This file can not be used on its own.');
}

function plugin_getcapabilities_faq()
{
    return array(
        'schema' => 1,
        'roles' => array('content', 'relationship'),
        'capabilities' => array(
            'content.read',
            'content.collection',
            'content.search',
            'content.url.resolve',
            'content.lifecycle'
        )
    );
}

function faq_getItemUrl($id)
{
    global $_CONF;
    return $_CONF['site_url'] . '/faq/index.php?faq=' . rawurlencode($id);
}

function faq_getCategoryUrl($id)
{
    global $_CONF;
    return $_CONF['site_url'] . '/faq/index.php?cat=' . rawurlencode($id);
}

function faq_normalizeItemRow($row, $what = '')
{
    $all = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'url' => faq_getItemUrl($row['id']),
        'description' => $row['description'],
        'excerpt' => COM_truncate(strip_tags(PLG_replaceTags($row['description'])), 240, '...'),
        'date-created' => isset($row['date_created']) ? (int) $row['date_created'] : (int) $row['date_modified'],
        'date-modified' => (int) $row['date_modified'],
        'category' => $row['category'],
        'type' => 'faq',
        'subtype' => 'question',
        'hits' => (int) $row['hits']
    );

    if ($what === '' || $what === '*' || $what === null) {
        return $all;
    }

    $wanted = is_array($what) ? $what : preg_split('/\s*,\s*/', $what, -1, PREG_SPLIT_NO_EMPTY);
    if (count($wanted) === 1 && isset($all[$wanted[0]])) {
        return $all[$wanted[0]];
    }

    $result = array();
    foreach ($wanted as $field) {
        if (array_key_exists($field, $all)) {
            $result[$field] = $all[$field];
        }
    }

    return $result;
}

function plugin_getiteminfo_faq($id, $what, $uid = 0, $options = array())
{
    global $_TABLES;

    $fields = "faq.id, faq.title, faq.description, faq.category, faq.hits,
               UNIX_TIMESTAMP(faq.date) AS date_created,
               UNIX_TIMESTAMP(faq.date) AS date_modified";

    $perm = COM_getPermSQL('AND', $uid, 2, 'faq')
          . COM_getPermSQL('AND', $uid, 2, 'cat');

    if ($id !== '*') {
        $safe_id = DB_escapeString($id);
        $sql = "SELECT {$fields}
                  FROM {$_TABLES['faq']} faq
                  JOIN {$_TABLES['faq_category']} cat ON cat.id = faq.category
                 WHERE faq.id = '{$safe_id}' {$perm}
                 LIMIT 1";
        $result = DB_query($sql);
        if (DB_numRows($result) !== 1) {
            return false;
        }

        return faq_normalizeItemRow(DB_fetchArray($result), $what);
    }

    $limit = isset($options['limit']) ? (int) $options['limit'] : 50;
    if ($limit < 1) {
        $limit = 1;
    } elseif ($limit > 200) {
        $limit = 200;
    }

    $order = isset($options['order']) ? $options['order'] : 'modified-desc';
    switch ($order) {
        case 'hits-desc':
            $order_sql = 'faq.hits DESC, faq.date DESC';
            break;
        case 'created-desc':
        case 'modified-desc':
        default:
            $order_sql = 'faq.date DESC, faq.id ASC';
            break;
    }

    $since_sql = '';
    if (!empty($options['since']) && is_numeric($options['since'])) {
        $since = (int) $options['since'];
        $since_sql = " AND faq.date >= FROM_UNIXTIME({$since})";
    }

    $category_sql = '';
    if (!empty($options['category'])) {
        $category = DB_escapeString($options['category']);
        $category_sql = " AND faq.category = '{$category}'";
    }

    $sql = "SELECT {$fields}
              FROM {$_TABLES['faq']} faq
              JOIN {$_TABLES['faq_category']} cat ON cat.id = faq.category
             WHERE 1=1 {$perm}{$since_sql}{$category_sql}
             ORDER BY {$order_sql}
             LIMIT {$limit}";

    $result = DB_query($sql);
    $items = array();
    while ($row = DB_fetchArray($result)) {
        $items[] = faq_normalizeItemRow($row, $what);
    }

    return $items;
}

function plugin_idtourl_faq($sub_type, $item_id)
{
    if ($sub_type !== '' && $sub_type !== null && $sub_type !== 'question' && $sub_type !== 'faq') {
        return '';
    }

    return faq_getItemUrl($item_id);
}


/**
 * Inject contextual FAQ relations into full Geeklog article pages.
 *
 * Core stories use PLG_templateSetVars() reliably across Geeklog 2.1.1-2.2.x,
 * while they do not provide the same generic PLG_itemDisplay() surface as
 * modern content-provider plugins. This mirrors the compatibility strategy
 * used by Hub without changing the stored article content.
 *
 * @param string $templatename
 * @param object $template
 * @return void
 */
function plugin_templatesetvars_faq($templatename, &$template)
{
    global $_FAQ_CONF;

    if (empty($_FAQ_CONF['contextual_enabled'])
        || !is_object($template)
        || !method_exists($template, 'get_var')
        || !method_exists($template, 'set_var')
    ) {
        return;
    }

    $placement = !empty($_FAQ_CONF['contextual_default_placement'])
        ? $_FAQ_CONF['contextual_default_placement']
        : 'automatic';

    if ($placement === 'manual') {
        return;
    }

    $articleTemplates = array(
        'storytext',
        'featuredstorytext',
        'archivestorytext',
        'article',
        'featuredarticle',
        'archivearticle'
    );

    if (!in_array($templatename, $articleTemplates, true)) {
        return;
    }

    $storyId = (string) $template->get_var('story_id');
    if ($storyId === '' && isset($_GET['story'])) {
        $storyId = trim((string) $_GET['story']);
    }

    if ($storyId === '') {
        return;
    }

    $displayType = (string) $template->get_var('story_display_type');
    if ($displayType === '') {
        $displayType = (string) $template->get_var('display_type');
    }

    $script = isset($_SERVER['PHP_SELF'])
        ? basename((string) $_SERVER['PHP_SELF'])
        : '';

    $isFullArticle = ($displayType === 'n')
        || ($displayType === '' && $script === 'article.php');

    if (!$isFullArticle) {
        return;
    }

    $html = faq_renderContextual('article', $storyId, '', 'automatic');
    if ($html === '') {
        return;
    }

    /*
     * Geeklog themes do not all use the same story variable. Update both
     * standard full-story variables when present, while guarding against
     * duplicate execution through multiple story template-hook families.
     */
    $storyText = (string) $template->get_var('story_text_no_br');
    if ($storyText !== '' && strpos($storyText, 'faq-context-block') === false) {
        $template->set_var('story_text_no_br', $storyText . "\n" . $html);
    }

    $storyIntro = (string) $template->get_var('story_introtext');
    if ($storyIntro !== '' && strpos($storyIntro, 'faq-context-block') === false) {
        $template->set_var('story_introtext', $storyIntro . "\n" . $html);
    }
}

function plugin_itemdisplay_faq($id, $type)
{
    global $_FAQ_CONF;

    if (empty($_FAQ_CONF['contextual_enabled'])) {
        return array(true);
    }

    $normalizedType = faq_normalizeProvider($type);

    /*
     * Articles use plugin_templatesetvars_faq() for Geeklog 2.1.1-2.2.x
     * compatibility. Do not emit a second copy if a theme/core path also
     * happens to expose PLG_itemDisplay() for stories.
     */
    if ($normalizedType === 'article') {
        return array(true);
    }

    $placement = !empty($_FAQ_CONF['contextual_default_placement']) ? $_FAQ_CONF['contextual_default_placement'] : 'automatic';
    if ($placement === 'manual') {
        return array(true);
    }

    /*
     * Geeklog PLG_itemDisplay() exposes one provider-defined insertion point.
     * It has no generic before/after argument. Therefore every non-manual FAQ
     * relation is rendered at the provider's item-display insertion point.
     */
    $html = faq_renderContextual($type, (string) $id, '', 'automatic');
    if ($html === '') {
        return array(true);
    }

    return array(true, $html);
}

function plugin_collectSitemapItems_faq($uid = 0, $limit = 0)
{
    $options = array(
        'limit' => $limit > 0 ? (int) $limit : 200,
        'order' => 'modified-desc'
    );

    $rows = plugin_getiteminfo_faq('*', 'id,url,date-modified', $uid, $options);
    $items = array();

    if (!is_array($rows)) {
        return $items;
    }

    foreach ($rows as $row) {
        if (!is_array($row) || empty($row['url'])) {
            continue;
        }

        $items[] = array(
            'url' => $row['url'],
            'date-modified' => isset($row['date-modified']) ? $row['date-modified'] : 0,
            'change-freq' => 'monthly',
            'priority' => 0.5
        );
    }

    return $items;
}

function plugin_getmetatags_faq($type, $id)
{
    /*
     * PLG_getMetaTags() calls this callback only when FAQ is not the owning
     * provider of the current page. FAQ does not override host metadata.
     * Standalone FAQ metadata is supplied by the FAQ page itself.
     */
    return array();
}

function plugin_getstructureddatatypes_faq()
{
    return array('FAQPage', 'Question', 'Answer');
}

function plugin_clearcache_faq()
{
    faq_clearLocalCache();
    return true;
}

function plugin_configchange_faq($group, $changes = array())
{
    if ($group === 'faq') {
        faq_clearLocalCache();
    }
}

function faq_notifySaved($id, $old_id = '')
{
    if (!function_exists('PLG_itemSaved')) {
        return;
    }

    if (version_compare(VERSION, '2.2.2', '>=')) {
        PLG_itemSaved($id, 'faq', $old_id, 'question');
    } else {
        PLG_itemSaved($id, 'faq', $old_id);
    }
}

function faq_notifyDeleted($id)
{
    if (!function_exists('PLG_itemDeleted')) {
        return;
    }

    if (version_compare(VERSION, '2.2.0', '>=')) {
        PLG_itemDeleted($id, 'faq', 'question');
    } else {
        PLG_itemDeleted($id, 'faq');
    }
}


function plugin_getconfigtooltip_faq($id)
{
    $tips = array(
        'hidenewfaq' => 'Hide FAQ entries from the Geeklog What\'s New block.',
        'hidefaqmenu' => 'Hide the FAQ entry from the site menu.',
        'newfaqinterval' => 'Number of seconds during which a FAQ is considered new.',
        'no_hit_rights' => 'Comma-separated rights whose users should not increment FAQ hit counters.',
        'contextual_enabled' => 'Render FAQ associations when a content provider exposes PLG_itemDisplay().',
        'contextual_default_placement' => 'Placement consumed by the contextual FAQ renderer. Manual disables automatic output.',
        'structured_data' => 'Emit machine-readable Question/Answer JSON-LD for visible FAQ content.',
        'coverage_limit' => 'Maximum number of provider items inspected on one Coverage request.',
        'default_permissions' => 'Geeklog ACL applied to newly created FAQ entries and categories.'
    );

    return isset($tips[$id]) ? $tips[$id] : '';
}

function faq_structuredQuestions($items, $asFaqPage)
{
    global $_FAQ_CONF;

    if (empty($_FAQ_CONF['structured_data']) || !is_array($items) || count($items) === 0) {
        return '';
    }

    $questions = array();
    foreach ($items as $item) {
        if (empty($item['title']) || !isset($item['description'])) {
            continue;
        }

        $questions[] = array(
            '@type' => 'Question',
            'name' => html_entity_decode(strip_tags($item['title']), ENT_QUOTES, 'UTF-8'),
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => html_entity_decode(strip_tags(PLG_replaceTags($item['description'])), ENT_QUOTES, 'UTF-8')
            )
        );
    }

    if (count($questions) === 0) {
        return '';
    }

    if ($asFaqPage) {
        $data = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $questions
        );
    } else {
        $data = array(
            '@context' => 'https://schema.org',
            '@graph' => $questions
        );
    }

    return '<script type="application/ld+json">'
         . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
         . '</script>';
}


function plugin_itemsaved_faq($id, $type, $old_id = '', $sub_type = '')
{
    global $_TABLES;

    if (!faq_relationTableExists()) {
        return;
    }

    $provider = faq_normalizeProvider($type);
    if ($provider === 'faq' || !faq_relationProviderAllowed($provider)) {
        return;
    }

    if ($old_id !== '' && (string) $old_id !== (string) $id) {
        $old_id_sql = DB_escapeString((string) $old_id);
        $id_sql = DB_escapeString((string) $id);
        $provider_sql = DB_escapeString($provider);

        DB_query("UPDATE {$_TABLES['faq_relations']}
                     SET item_id = '{$id_sql}', modified = NOW()
                   WHERE provider = '{$provider_sql}'
                     AND item_id = '{$old_id_sql}'");
        faq_clearLocalCache();
    }
}

function plugin_itemdeleted_faq($id, $type, $sub_type = '')
{
    global $_TABLES;

    if (!faq_relationTableExists()) {
        return;
    }

    $provider = faq_normalizeProvider($type);
    if ($provider === 'faq' || !faq_relationProviderAllowed($provider)) {
        return;
    }

    $provider_sql = DB_escapeString($provider);
    $id_sql = DB_escapeString((string) $id);
    $subtype_sql = DB_escapeString((string) $sub_type);

    $sql = "DELETE FROM {$_TABLES['faq_relations']}
             WHERE provider = '{$provider_sql}'
               AND item_id = '{$id_sql}'";

    if ($subtype_sql !== '') {
        $sql .= " AND (item_subtype = '' OR item_subtype = '{$subtype_sql}')";
    }

    DB_query($sql);
    faq_clearLocalCache();
}
