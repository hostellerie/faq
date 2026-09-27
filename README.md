# Geeklog FAQ Plugin

* Maintainers: [Geeklog Community Members](https://github.com/Geeklog-Plugins/faq/graphs/contributors)
* Development branch 1.3.0 supports: Geeklog v2.1.1 through 2.2.2 (PHP 5.6 through 8.1 target)

## Summary

The Geeklog FAQ plugin allows webmasters to create a list of FAQ categories which contain FAQ entries. Categories can have descriptions and Entries have a question asked and an answer given.

## Main Features

- Ids of FAQ Categories and Entries can be a unique text string (good for SEO)
- Entries include a hit counter
- Entries support HTML and autotags
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
- Contextual FAQ associations stored in `faq_relations`.
- Automatic contextual rendering through `plugin_itemdisplay_faq()` when the host provider calls `PLG_itemDisplay()`.
- Manual fallback through autotags when automatic placement is unavailable.
- Editorial **Associations** and **Coverage** administration pages.
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
```

One FAQ may be reused on several items and one item may contain several FAQs.

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

to render FAQs associated with a content identity.

Where Geeklog supplies autotag context, `faqrelated` can also reuse the current provider/item context.

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
