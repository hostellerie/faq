# Geeklog FAQ Plugin

* Maintainers: [Geeklog Community Members](https://github.com/Geeklog-Plugins/faq/graphs/contributors)
* Development branch 1.3.0 supports: Geeklog v2.1.1 through 2.2.2 (PHP 5.6 through 8.1 target)

## Summary

The Geeklog FAQ plugin allows webmasters to create a list of FAQ categories which contain FAQ entries. Categories can have descriptions and Entries have a question asked and an answer given.

## Main Features

- Ids of FAQ Categories and Entries can be a unique text string (good for SEO)
- Entries include a hit counter
- Entries support HTML and autotags
- FAQ entries use logical positions inside their category: **First**, **After: [existing FAQ]**, or **Last**. The plugin stores the order internally and renumbers the category automatically in steps of 10.
- Makes use of Geeklog permissions for Categories and Entries

## Other Information

For installation instructions; open the doc/install.html file. For upgrade instructions; open the doc/install.html file.

Geeklog Homepage:
https://www.geeklog.net

FAQ Plugin Homepage:
https://github.com/Geeklog-Plugins/faq

To find the latest releases see:
https://github.com/Geeklog-Plugins/faq/releases

To request a feature or report an issue see: 
https://github.com/Geeklog-Plugins/faq/issues

## Versions

Version 1.2.0.3:
- Fix for PHP 8.1.

Version 1.2.0.2:
- Added missing entries for the language files.
- Added the Persian language file provided by Mahdi Montazeri

Version 1.2.0.1:
- Fixed an undefined variable error that could cause a sql error.

Version 1.2.0:
- Updated plugin for GeekLog 2.2.0 API. NOTE: This plugin will work with 
  GeekLog v2.1.3 and higher.
- Now plugin supports multiple themes.
- Fixed undefined variable errors.
- Removed FAQMAN import.
- Add Auto install and updated uninstall and upgrade functions.

Version 1.1.0:
- Updated plugin for GeekLog 1.5.0 API. NOTE: This plugin will not work with 
  earlier versions of GeekLog.
- Fixed SQL bug when updating FAQ categories.

Version 1.0.3:
- Fixed bug where updating FAQ categories and questions with percent 
  signs (%)was impossible.
- Added access rights column to FAQ (and category) lists for admins.
- Added UTF-8 language files for english and swedish in UTF-8 encoding.

Version 1.0.2:
- Some internal cleanup of the code.
- Patch so FAQMAN imports now handle content with ' in it.
- Patch for FAQMAN imports and old FAQMAN autotags.
- Number of hits and change date now available in faq templates.
  Templates updated.
- Allowed HTML now added to edit templates.

Version 1.0.1:
- This is the first public release of this plugin and it include 
  features like:
  - Autolink support.
  - GeekLog security limiting FAQ access.
  - Search functionality.
  - Ability to import FAQ topics from the faqman plugin.


## FAQ 1.3.0 development

Version 1.3.0 modernizes FAQ as a reusable Geeklog question-and-answer content provider while preserving the historical standalone FAQ pages.

### New architecture

- Normalized Item Info through `plugin_getiteminfo_faq()`, including collection requests with `id = '*'`.
- Sitemap, URL resolution, lifecycle and capability integration for modern Geeklog consumers.
- Contextual FAQ associations stored in `faq_relations`, including Geeklog Topic targets.
- Automatic contextual rendering through `plugin_itemdisplay_faq()` when the host provider calls `PLG_itemDisplay()`.
- Topic-level FAQ rendering on Geeklog topic index pages and inheritance from Topics to their Articles.
- Manual fallback through autotags when automatic placement is unavailable.
- Editorial **Associations** and **Coverage** administration pages.
- Existing associations can be edited inline for placement, Topic scope and sort order without delete/recreate.
- Provider-neutral design: FAQ does not query private Story, Static Pages or third-party plugin tables for coverage.
- Optional Hub interoperability without making Hub an installation dependency.
- Semantic server-rendered FAQ markup and Question/Answer JSON-LD.
- Native Geeklog configuration with contextual rendering, structured-data and coverage options.

### Contextual FAQ associations

An association links an existing FAQ entry to a Geeklog content identity:

```text
FAQ ID -> provider + item ID + optional subtype
```

Examples:

```text
rocket-stove-draft -> article:123
rocket-stove-draft -> staticpages:rocket-stove
rocket-stove-category -> topic:construction
```

One FAQ may be reused on several items and one item may contain several FAQs.

Geeklog Topics are first-class contextual targets in 1.3.0. A FAQ or FAQ category associated with a Topic can be rendered on the Topic index page and inherited by Articles assigned to that Topic. Direct Article associations take priority over inherited Topic associations, and duplicate FAQ ids are removed across direct and multi-topic sources.

Topic associations also have an explicit scope:

- **Topic only** — render on the Topic index page but do not inherit into Articles;
- **Topic articles only** — inherit into Articles but do not render on the Topic index page;
- **Topic + topic articles** — do both (default, preserving the original 1.3.0-development behavior).

When an Article already contains an external FAQ/Q&A signal, automatic Topic inheritance is suppressed for that Article to avoid silent duplication. An administrator may still create a direct managed association explicitly after confirming the potential conflict.

A whole FAQ category can also be linked to a content item. Category links are dynamic: FAQ stores one category relation, then resolves the category's current readable FAQs at render time. New FAQs added to that category therefore appear automatically. Individual FAQ links are de-duplicated against category-derived FAQs.

The administration pages are:

```text
admin/plugins/faq/relations.php
admin/plugins/faq/coverage.php
```

### Autotags

Existing autotags remain supported:

```text
[faq:faq-id]
[faqcat:category-id]
```

Version 1.3.0 adds:

```text
[faqembed:faq-id]
```

to render one complete question/answer block, and:

```text
[faqrelated:article 123]
```

to render FAQs associated with an explicit content identity.

It also adds:

```text
[faq-context]
```

to render the managed FAQs associated with the current content without repeating its provider and id. Geeklog's `PLG_replaceTags()` provider/id context is used when available; conservative Core fallbacks are kept for compatible older call paths.

For Articles, `[faq-context]` uses the same relation engine as automatic rendering: direct relations, Topic inheritance, duplicate suppression and external-FAQ protection remain consistent.

### Geeklog 2.1.1 compatibility

FAQ 1.3.0 keeps one codebase for Geeklog 2.1.1 through 2.2.2.

The plugin feature-detects newer integration surfaces. Core FAQ management, permissions, search, autotags, associations and manual contextual placement do not require Geeklog 2.2.2.

When an older host provider does not call `PLG_itemDisplay()`, use `faqembed` or `faqrelated` for manual placement instead of coupling FAQ to the provider's SQL tables.

Subtype-aware lifecycle calls are used only on Geeklog 2.2.2 and later.

### Installable archive

Every push to `develop-1.3.0` automatically rebuilds:

```text
dist/faq_1.3.0.zip
```

The archive is rooted at `faq/` and is validated before commit. No file or directory whose name begins with `.` is allowed inside the ZIP.

The build is reproducible through:

```sh
./build-dist.sh
```

PHP syntax is validated in GitHub Actions against PHP 5.6 and PHP 8.1.


### Automatic placement contract

Contextual FAQ rendering follows Geeklog's native `PLG_itemDisplay($id, $type)` contract.

- **Automatic** returns the FAQ fragment to the host provider.
- **Manual only** keeps the relation active but suppresses automatic output; place it with `[faq-context]`, `[faqrelated:...]` or `[faqembed:...]`.
- The host provider decides where the automatic fragment appears on its public item page.
- Geeklog Topic pages use Core centerblock positions; FAQ currently renders Topic FAQs after the article list.
- FAQ does not invent generic before/after slots that the Geeklog API does not expose.

Known provider implementations used during FAQ 1.3.0 development include Documents `documents_1.3.0`, Videos `videos_0.21.0`, and Maps `update/maps-1.7`.

Older development rows containing `before` or `after` are interpreted as `automatic`.


## Languages

FAQ 1.3.0 keeps all public, administration, Associations, Coverage and native Configuration labels in the plugin language files.

Maintained language files:

- `english.php`
- `english_utf-8.php`
- `french_france_utf-8.php`
- `persian_utf-8.php`
- `swedish.php`
- `swedish_utf-8.php`

French has a complete native translation for the current 1.3.0 interface.

Historical Persian and Swedish translations keep their existing translated strings and include explicit English fallbacks for newer 1.3.0 keys that have not yet received a native translation. This keeps the language-key contract complete and avoids missing labels or undefined language keys.
