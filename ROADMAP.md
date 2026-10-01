# FAQ 1.3.0 Roadmap

Status: **release hardening**
Branch: `develop-1.3.0`

## Current implementation status

Legend:

- **Implemented** — code is present on `develop-1.3.0` and has been exercised during development.
- **To validate** — implementation exists but still needs explicit release-level functional/upgrade verification.
- **Deferred** — useful idea, but not required to ship a clean 1.3.0 unless testing reveals a dependency.

### Implemented

- Geeklog 2.1.1-2.2.2 compatibility target and PHP 5.6-8.1 syntax CI.
- Modern installer/configuration metadata and `plugin.json`.
- FAQ/category CRUD preservation and historical autotags.
- Explicit FAQ ordering inside each category, with historical sorting retained as a fallback for equal order values.
- Native Advanced Editor integration with HTML-source fallback and MediaGallery picker support.
- Item Info, collection retrieval, URL resolution, lifecycle callbacks, sitemap, metadata and capability declaration.
- `faq_relations` for individual FAQ associations and `faq_category_relations` for dynamic whole-category associations.
- Associations administration with provider/item selection.
- Coverage administration with managed/external/both/none status, including a read-only Core audit for Articles and Static Pages when normalized collection/content capabilities are unavailable.
- Contextual FAQ rendering, automatic/manual placement model and article compatibility fallback.
- Geeklog Topic associations, Topic-page rendering and Topic-to-Article FAQ inheritance with duplicate suppression and external-FAQ protection.
- Topic association scope: Topic only, Topic articles only, or both.
- `faqembed`, `faqrelated` and `faq-context` manual placement support.
- Public semantic FAQ rendering and Question/Answer structured data.
- Native Geeklog administration discovery through `plugin_getadminoption_faq()` and Command & Control discovery through `plugin_cclabel_faq()`.
- Theme-neutral plugin administration navigation, with Eclipse-aware visual variables and no required UIkit contract.
- Versioned FAQ CSS and automated installable archive generation.

### To validate before release

- Functional matrix on PHP 5.6 and PHP 8.1 beyond syntax linting.
- Full disabled-plugin audit: menus, What's New, blocks, contextual output, structured data and other public contributions.
- Final ACL/CSRF/input-validation audit for FAQ CRUD, Associations and Coverage.
- Coverage behavior with enumerable and non-enumerable providers, including permission filtering.
- Stale relation handling when a remote provider item disappears without a lifecycle callback.
- Final Denim/Eclipse/mobile administration pass.
- Final language fallback/translation audit for new 1.3.0 labels and configuration tooltips.
- README, upgrade notes and release notes aligned with the final tested behavior.

### Deferred unless testing makes them necessary

- Heuristic/semantic suggestions for reusing existing FAQs.
- Agent/LLM-assisted FAQ suggestions.
- Additional caching beyond the current lightweight invalidation hooks unless profiling demonstrates a need.
- Multilingual FAQ identity/schema redesign.

## 1. Goal

FAQ 1.3.0 should modernize the legacy Geeklog FAQ plugin into a reusable question-and-answer content provider.

The release should preserve the historical standalone FAQ features while adding structured interoperability, contextual FAQ placement, content coverage tools, modern administration, SEO/metadata support, and optional integration with Hub and other modern Geeklog consumers.

The plugin must remain provider-owned:

- FAQ owns FAQ questions, answers, categories, permissions, rendering and FAQ-specific associations.
- FAQ associations are **functional attachments**, not a generic editorial relationship graph. Their purpose is to decide which FAQ entries or FAQ categories are rendered with a host content item, including FAQ-specific placement, ordering, ACL and de-duplication rules.
- FAQ must not grow a parallel generic relationship system for Article↔Document, Map↔Video, pillar membership, related-content navigation, relationship roles or cross-provider context. Those generic editorial/context relationships belong to Hub.
- Other plugins must not need to query FAQ SQL tables directly.
- FAQ must not query private tables of other plugins when a public Geeklog/plugin contract is available.
- Hub may observe or consume FAQ-owned attachment context where a public contract exists, but it must not become required for FAQ rendering or persistence.
- Optional modern capabilities must degrade gracefully when the running Geeklog version does not expose them.

### Boundary with Hub

The ownership boundary is intentional:

```text
Hub
= generic editorial relationships and context between content objects

FAQ
= FAQ-specific attachment/rendering rules for a host content object
```

A relation such as `article -> document`, pillar membership, bidirectional related-content navigation or cross-plugin context belongs to Hub. A relation such as `FAQ -> article` or `FAQ category -> static page` belongs to FAQ because the relation directly drives FAQ-specific rendering and behavior.

Hub may later expose these FAQ attachments as observed context or diagnostics, but it should not own, mutate or duplicate the underlying FAQ association records.

This roadmap follows the interoperability and modernization guidance in the `hostellerie/memorandum` repository.

---

## 2. Compatibility target

### Preferred target

FAQ 1.3.0 should target:

- Geeklog **2.1.1 through 2.2.2**
- PHP **5.6 through 8.1** where practical

The implementation must use the common safe PHP subset when code is shared across the supported range.

### Compatibility principle

Do not maintain separate FAQ codebases for Geeklog 2.1.1 and 2.2.2.

Use:

1. feature detection;
2. optional callbacks;
3. safe fallbacks;
4. version-aware signatures only where Geeklog API differences require them.

A modern capability that is unavailable on Geeklog 2.1.1 must not prevent the plugin from installing or running its core FAQ features.

### 2.1.1 fallback policy

The following should remain available on Geeklog 2.1.1:

- FAQ categories;
- FAQ entries;
- permissions;
- administration;
- public FAQ pages;
- search;
- autotags;
- contextual FAQ rendering where the host/provider exposes a usable placement hook;
- FAQ-to-content associations;
- lifecycle calls supported by that Geeklog version;
- Item Info when the API exists with a compatible signature;
- native configuration;
- cache invalidation inside FAQ;
- optional Hub integration through feature-detected services/contracts.

Capabilities specific to newer Geeklog versions should be implemented conditionally.

Examples:

- `plugin_idtourl_faq()`: expose/use when supported;
- subtype-aware lifecycle events: use subtype on 2.2.2, omit it on older versions;
- newer plugin callbacks: implement safely but never assume the caller exists;
- richer provider placement: use when host plugins/Core expose it, otherwise rely on explicit autotags/manual associations.

### Definition of compatibility

"Compatible with Geeklog 2.1.1" means the plugin installs, upgrades, administers and renders FAQs without fatal errors and without requiring 2.2.2-only APIs.

It does **not** mean every 2.2.2 capability must be emulated on 2.1.1.

---

## 3. Preserve existing user data

The 1.3.0 upgrade must be non-destructive.

Preserve:

- FAQ IDs;
- category IDs;
- questions/titles;
- answers/descriptions;
- hits;
- dates;
- owner/group data;
- Geeklog permissions;
- existing autotags;
- existing public URLs wherever practical.

Do not force administrators to recreate FAQ content after upgrade.

Add explicit upgrade steps from 1.2.x.

Before changing the schema:

- document current tables and indexes;
- verify charset/collation behavior;
- test migration with real 1.2.x data;
- keep rollback guidance for development/testing.

---

## 4. Database modernization

### Existing tables

Review and modernize:

- `faq`
- `faq_category`

Tasks:

- replace obsolete MyISAM declarations with a modern engine where migration is safe;
- ensure UTF-8/utf8mb4 compatibility according to Geeklog/site database conventions;
- add useful indexes;
- keep stable textual IDs;
- preserve Geeklog ACL columns;
- avoid unnecessary schema changes that break upgrades.

### New relation table

Add a provider-neutral FAQ association table, conceptually:

`faq_relations`

Suggested fields:

- relation ID;
- FAQ ID;
- provider/plugin;
- item ID;
- optional subtype;
- placement;
- sort order;
- enabled flag;
- created timestamp;
- modified timestamp.

Requirements:

- one FAQ can be associated with many content items;
- one content item can have many FAQs;
- duplicate associations must be prevented;
- ordering must be deterministic;
- deletion of a FAQ must remove its own associations safely;
- deletion of a remote content item should be handled when a lifecycle notification is available, but stale associations must also be detectable.

Do not make Hub mandatory for storing FAQ associations.

---

## 5. Installation, upgrade and configuration

Modernize installation around Geeklog's supported plugin installer.

Tasks:

- review `autoinstall.php`;
- review `install_defaults.php`;
- implement clean upgrade path to 1.3.0;
- ensure uninstall declarations cover new tables/configuration;
- avoid legacy/manual install paths where unnecessary;
- remove obsolete compatibility code only after confirming no supported upgrade path depends on it.

Use Geeklog's native Configuration API.

Configuration should include at least:

- enable/disable public FAQ area;
- menu visibility;
- What's New visibility;
- default category sort;
- default FAQ sort;
- default contextual FAQ placement;
- enable automatic contextual rendering;
- enable structured data;
- enable hit counting;
- cache settings if required;
- optional relation/coverage settings;
- optional Hub integration behavior.

Add complete configuration language metadata and tooltips.

---

## 6. Static metadata manifest

Update `plugin.json` according to the memorandum metadata contract.

It should expose reliable static information such as:

- schema;
- id;
- human-readable name;
- icon;
- minimum supported Geeklog version;
- minimum supported PHP version when confidently maintained.

Do not put runtime capability claims in the static manifest if they belong in a capability contract.

---

## 7. Modern content contract

Implement `plugin_getiteminfo_faq()`.

### Single FAQ

At minimum expose where applicable:

- id;
- title;
- url;
- description;
- excerpt;
- date-created;
- date-modified;
- category;
- type/subtype;
- hits.

Permissions must be enforced inside FAQ.

### Collections

Support `id = '*'` where practical.

Initial common options:

- `since`;
- `limit`;
- `order`.

Recommended ordering:

- modified-desc;
- created-desc;
- hits-desc.

Possible later filters:

- category;
- IDs;
- language;
- subtype.

Consumers must not need SQL knowledge of FAQ tables.

---

## 8. URL resolution

For Geeklog versions that support it, implement:

`plugin_idtourl_faq($sub_type, $item_id)`

Provide stable FAQ URLs even for consumers reacting to lifecycle events.

On older Geeklog versions, retain a local helper that resolves URLs for FAQ's own internal use and expose the native callback only where safe.

---

## 9. Lifecycle interoperability

Ensure FAQ emits lifecycle events on all meaningful mutation paths:

- create FAQ;
- update FAQ;
- delete FAQ;
- create/update/delete relevant category data where consumers need notification;
- create/update/delete FAQ associations when appropriate.

Use the best lifecycle signature available in the running Geeklog version.

For 2.2.2:

- preserve subtype information where meaningful.

For 2.1.1:

- fall back to the older supported callback form.

Do not duplicate event logic across administration paths.

---

## 10. Search

Keep and modernize native Geeklog search support.

Review:

- `plugin_searchtypes_faq()`;
- `plugin_dopluginsearch_faq()`;
- optional `plugin_searchformat_faq()`.

Search should cover:

- question/title;
- answer;
- category where appropriate.

Respect FAQ and category permissions.

---

## 11. Autotags

Preserve existing autotags:

- `[faq:...]`;
- `[faqcat:...]`.

Add a modern contextual/group rendering autotag only if its syntax is unambiguous and documented.

Possible concept:

- one FAQ item;
- one category/group;
- related FAQs for the current host item.

Autotags should remain the explicit/manual placement mechanism.

They are especially important as a compatibility fallback when automatic provider placement is unavailable on Geeklog 2.1.1 or in an older host plugin.

---

## 12. Contextual FAQ rendering

Implement FAQ as a contextual fragment provider.

Target callback:

`plugin_itemdisplay_faq($id, $type)`

Responsibilities:

- identify FAQ associations for the displayed host item;
- enforce FAQ permissions;
- order associated FAQs;
- render a reusable FAQ block;
- return an empty result when there is nothing to display;
- avoid side effects and hit inflation from embedded rendering;
- cache safely if required.

### Provider-side requirement

Automatic contextual placement depends on the host content provider exposing a call equivalent to:

`PLG_itemDisplay($id, $type)`

Audit at least:

- Stories;
- Static Pages.

If a host does not expose the provider placement point:

- do not add plugin-specific SQL coupling;
- document the missing provider capability;
- use autotags/manual placement as fallback;
- propose a provider/Core enhancement separately where appropriate.

### Supported placement modes

FAQ follows Geeklog's actual `PLG_itemDisplay($id, $type)` contract.

Supported modes are:

- **automatic** — render the FAQ fragment at the insertion point chosen by the host provider;
- **manual only** — never return the relation through `plugin_itemdisplay_faq()`; use an autotag or other explicit placement.

FAQ must not advertise generic "before content" or "after content" positions because Geeklog does not pass such a placement argument to `PLG_itemDisplay()`. The host provider owns the physical insertion point.

Verified provider examples:

- Documents `documents_1.3.0`: after the rendered document body;
- Videos `videos_0.21.0`: after the full video article;
- Maps `update/maps-1.7`: at the provider-defined map/marker item display point.

Legacy development values `before` and `after` must be treated as `automatic` without breaking existing test data.

---

## 13. Relations and Hub

FAQ must work without Hub.

When Hub is available:

- expose FAQ identities through normalized content contracts;
- allow Hub to discover/consume FAQ relationships;
- avoid creating a parallel global relationship registry in FAQ;
- keep FAQ-specific association semantics owned by FAQ;
- use Hub for broader cross-content orchestration where appropriate.

Feature-detect Hub services/capabilities.

Never hard-require Hub during FAQ installation.

---

## 14. Capability declaration

Add an optional memorandum-compatible capability declaration, feature-detected by consumers.

Candidate roles:

- content;
- relationship.

Candidate capabilities:

- content.read;
- content.collection;
- content.search;
- content.url.resolve;
- content.lifecycle;
- content.related where genuinely supported.

Do not advertise unsupported capabilities.

Capability declarations must remain deterministic and permission-neutral; actual data access still enforces permissions.

---

## 15. Administration redesign

Replace the legacy single-page administration experience with a clearer structure.

Target sections:

- Questions;
- Categories;
- Associations;
- Coverage;
- Configuration.

### Questions list

Include useful columns such as:

- question;
- category;
- usage count;
- modified date;
- hits;
- access;
- status where introduced.

### FAQ editor

Support:

- stable ID;
- question;
- answer;
- category;
- permissions;
- tags/keywords if introduced;
- linked content;
- ordering/placement metadata where relevant.

#### Modern editor integration guardrail

Do **not** select, bundle or recreate a FAQ-specific WYSIWYG editor before checking the editor API actually exposed by the supported Geeklog versions.

For Geeklog 2.1.1 through 2.2.2, FAQ should integrate through Geeklog's native Advanced Editor API (`COM_setupAdvancedEditor()` / `AdvancedEditor`) and let Geeklog own the configured editor implementation. FAQ must not call the old `editor_generate()` mechanism and must not depend on an unrelated global JavaScript editor.

Editor requirements:

- keep `description` as the authoritative HTML source field;
- provide a source-HTML mode for both FAQ answers and category descriptions;
- load historical HTML without rewriting, normalizing or migrating it on open;
- never replace stored historical `description` merely because the record was opened in a visual editor;
- consider content editable only after an explicit user content edit;
- only then synchronize visual-editor output back to `description` and pass it through Geeklog's normal HTML validation on save;
- preserve Geeklog autotags;
- use paragraph/block semantics supplied by the Geeklog editor adapter rather than newline-to-`<br>` normalization;
- integrate MediaGallery through its modern picker contract when available, without depending on the historical jQuery media-browser mechanism;
- degrade to HTML source editing when the configured editor adapter cannot guarantee the no-silent-rewrite contract;
- remain responsive and theme-neutral, including Denim and Eclipse.

The bundled editor used by a particular Geeklog release is an implementation detail. FAQ owns the preservation and integration contract, not the WYSIWYG engine.

Keep UI compatible with standard Geeklog administration patterns.

Do not require Eclipse.

---

## 16. Associations administration

Provide a dedicated way to associate existing FAQ entries with host content.

Requirements:

- search/select provider;
- search/select content item through provider-owned contracts;
- attach an existing individual FAQ;
- attach an entire FAQ category dynamically;
- detach FAQ/category associations;
- reorder FAQ/category sources for one host item;
- enable/disable one association;
- show where a FAQ or category is currently used.

### Whole-category associations

FAQ 1.3.0 supports a dedicated `faq_category_relations` table.

A category relation stores only:

`category_id + provider + item_id + optional subtype + placement + sort order`.

It does **not** copy one relation row per FAQ. At render time FAQ resolves the current readable members of the category dynamically. Therefore:

- adding a FAQ to the category makes it available to every linked content item automatically;
- moving a FAQ out of the category removes it from those category-driven blocks;
- ACL is still checked for every FAQ and for the category;
- an FAQ linked individually and also inherited through a category is rendered once, with the individual relation taking precedence;
- deleting a category removes its category relations;
- renaming a category keeps its category relations attached.

Avoid direct SQL queries into Story, Static Pages or third-party plugin tables when a normalized provider interface is available.

---

## 17. Coverage tool

Add an editorial coverage view showing which supported content items have contextual FAQs.

Initial targets:

- Stories;
- Static Pages.

Later providers can participate through the same normalized content capability.

Views/filters:

- all content;
- with FAQ;
- without FAQ;
- provider/type;
- category/topic when supplied by provider;
- search.

Display at minimum:

- content title;
- provider/type;
- number of associated FAQs;
- last modified when available.

Coverage must be permission-aware.

### Current 1.3.0 coverage semantics

Coverage distinguishes FAQ-plugin relations from FAQ-like content authored directly in native Geeklog content.

For providers exposing the shared collection contract, Coverage enumerates items through Item Info and counts enabled relations stored by FAQ through `faq_relations` / `faq_relationCountForItem()`.

Geeklog Core articles and Static Pages do not currently expose the full collection/content surface required for this audit. For these two **Geeklog-owned** providers only, FAQ follows the same read-only exception already used by Hub's editorial audits:

- Articles are enumerated from the Core stories table with publication, ACL and language filtering.
- Static Pages are enumerated from the Static Pages table with draft/template, ACL and language filtering.
- Stored content is inspected read-only; FAQ never rewrites it.
- Third-party plugin tables are never inspected by this fallback.

The external-content audit reports strong editorial signals rather than claiming semantic certainty. Current signals include:

- FAQPage JSON-LD;
- FAQPage microdata;
- paired Question/Answer schema;
- repeated `<details><summary>` Q&A structures;
- explicit FAQ / Frequently Asked Questions / Questions fréquentes headings.

Coverage can therefore distinguish:

- **Managed FAQ only**;
- **External FAQ signal only**;
- **Managed + external**;
- **No FAQ detected**.

FAQ-plugin autotags alone are not treated as external FAQ authorship. This direct Core-table fallback should be removed or reduced when Core/Static Pages expose equivalent normalized collection/content capabilities.

### Graceful degradation

If a provider cannot expose a collection through a supported contract:

- report it as unsupported/not enumerable;
- do not silently query its private database table;
- keep FAQ functional for providers that do support the contract.

---

## 18. FAQ reuse assistance

Prepare the data model/UI for suggesting existing FAQ entries that may fit a content item.

Version 1.3.0 may initially use deterministic signals such as:

- category;
- title keywords;
- tags;
- simple text relevance.

Suggestions must require human validation.

Do not automatically attach generated or suggested FAQ content.

Agent/LLM-assisted suggestions may be added later through external/provider-neutral contracts without making Agent a dependency.

---

## 19. Public rendering and templates

Modernize public templates while keeping theme override support.

Requirements:

- semantic HTML;
- accessible question/answer markup;
- responsive output;
- no hard-coded visual dependency on Denim or Eclipse;
- minimal inline styles;
- theme-friendly CSS hooks;
- graceful display without JavaScript.

Optional progressive enhancement:

- accordion disclosure behavior;
- expand/collapse all.

The full question and answer must remain available to users and crawlers in server-rendered HTML.

---

## 20. SEO and metadata

Implement or modernize:

- `plugin_getmetatags_faq()`;
- sitemap integration;
- canonical URL handling where appropriate;
- meaningful page titles/descriptions;
- stable addressable FAQ pages.

Add `plugin_collectSitemapItems_faq()` where supported.

Do not sell FAQ structured data as a Google FAQ rich-result feature.

Google removed FAQ rich results from Search in 2026, but semantic structured data may still be useful for machine-readable representation and interoperability.

---

## 21. Structured data

Support FAQ semantics where they accurately describe the rendered content.

Possible support:

- FAQPage for genuine standalone FAQ collection pages where appropriate;
- Question;
- Answer.

Rules:

- only describe visible FAQ content;
- do not mark hidden/unrelated FAQ data;
- contextual FAQs embedded in an article must not incorrectly redefine the main page as an FAQPage;
- preserve the host page's primary content type;
- avoid duplicate structured data if another provider already owns the page-level schema.

Expose structured-data support through the proper Geeklog capability/API when available.

---

## 22. Sitemap

Expose addressable FAQ content through Geeklog sitemap integration when supported.

Include:

- URL;
- modified date;
- sensible change frequency/priority only when justified.

Respect permissions and disabled/private content.

For older Geeklog versions without the same callback surface, the absence of this enhancement must not break FAQ.

---

## 23. Cache

Add cache only where profiling shows value.

Potential cache targets:

- associated FAQs for a host item;
- coverage counts;
- normalized FAQ collections;
- rendered contextual fragments.

Requirements:

- deterministic cache keys;
- permissions must not leak across users;
- invalidate on FAQ save/delete;
- invalidate on association save/delete;
- implement `plugin_clearcache_faq()` where supported;
- react to relevant configuration changes.

Do not cache merely to modernize the code.

---

## 24. Security

Audit all input and mutation paths.

Requirements:

- use Geeklog filtering/validation APIs;
- escape output according to context;
- use CSRF protection for administration mutations;
- enforce rights and item ACLs consistently;
- never trust provider/item IDs from requests;
- validate relation providers and item identities;
- preserve permission inheritance rules between category and FAQ;
- prevent unauthorized enumeration through Coverage/Associations;
- ensure embedded FAQ output cannot reveal private FAQ entries.

Review legacy SQL construction and remove unsafe interpolation where practical.

---

## 25. Modern code cleanup

Refactor without unnecessary framework introduction.

Priorities:

- separate data access, rendering and administration logic;
- reduce giant procedural functions;
- centralize URL building;
- centralize permission checks;
- centralize relation CRUD;
- centralize lifecycle notification;
- eliminate duplicated SQL;
- replace obsolete PHP constructs;
- retain PHP 5.6-compatible syntax if 2.1.1/PHP 5.6 remains supported.

Do not introduce PHP syntax requiring 7.x/8.x in common code if PHP 5.6 support is declared.

---

## 26. Language support

Preserve existing language packs where practical.

At minimum, fully maintain:

- English.

Update all maintained languages for new configuration/admin labels where possible.

Fallback safely to English for missing optional strings.

Prepare the data model for multilingual FAQ content, but do not force a disruptive multilingual schema migration unless the implementation can be made stable within 1.3.0.

A future translation model should preserve one logical FAQ identity across language variants.

---

## 27. What's New and statistics

Keep existing Geeklog integrations but modernize them.

Requirements:

- do not expose content when FAQ is disabled;
- permissions must always be respected;
- avoid showing contextual-only/private FAQ entries unintentionally;
- preserve aggregate statistics;
- expose per-item `hits` through Item Info.

Embedded contextual display should not increment the standalone FAQ hit counter unless explicitly designed to do so.

---

## 28. Plugin state handling

Audit behavior when FAQ is disabled.

When disabled:

- no public FAQ menu;
- no What's New contribution;
- no contextual FAQ output;
- no FAQ block output for anonymous/public pages;
- no structured data contribution;
- no background/scheduled behavior.

Administration behavior should follow normal Geeklog plugin rules.

---

## 29. Tests

Create a compatibility matrix covering at least:

### Geeklog

- 2.1.1;
- 2.2.2.

### PHP

Where the test environment allows:

- PHP 5.6 for 2.1.1 legacy compatibility;
- PHP 8.1 for modern compatibility.

### Upgrade paths

Test:

- clean install;
- 1.2.x -> 1.3.0 upgrade;
- uninstall/reinstall in development;
- existing autotags after upgrade;
- existing public URLs;
- permissions after schema migration.

### Functional tests

Test:

- category CRUD;
- FAQ CRUD;
- search;
- autotags;
- contextual association;
- contextual output;
- permissions;
- Coverage;
- provider without collection support;
- provider without item-display placement;
- disabled plugin behavior;
- Hub absent;
- Hub present where integration exists.

---

## 30. Documentation

Update README and documentation for 1.3.0.

Document:

- purpose of the modernized plugin;
- compatibility matrix;
- installation;
- upgrade;
- FAQ/category management;
- autotags;
- contextual associations;
- automatic vs manual placement;
- Coverage;
- provider requirements;
- optional Hub integration;
- structured-data limitations;
- fallback behavior on Geeklog 2.1.1.

Add release notes describing migrations and compatibility.

---

## 31. Implementation order

### Phase A — baseline and compatibility

- freeze/document 1.2.x schema and behavior;
- add 2.1.1/2.2.2 compatibility helpers;
- modernize installer/configuration;
- modernize manifest;
- PHP/security cleanup;
- upgrade tests.

### Phase B — normalized content provider

- Item Info;
- collection retrieval;
- URL resolution;
- search cleanup;
- lifecycle;
- sitemap;
- metadata;
- structured-data declaration;
- capability declaration.

### Phase C — contextual FAQ model

- relation table;
- relation service/helpers;
- administration associations;
- contextual renderer;
- `plugin_itemdisplay_faq()`;
- host placement audit;
- autotag fallback.

### Phase D — editorial tooling

- Coverage;
- usage counts;
- reuse/suggestion support;
- stale relation diagnostics.

### Phase E — integration and hardening — **current phase**

- [x] Hub feature-detected integration surface.
- [x] Automated release archive generation.
- [x] Native administration menu / Command & Control hooks.
- [x] Theme-neutral administration navigation contract.
- [x] Real historical 1.2.x upgrade test with populated data.
- [x] Clean install / uninstall on Geeklog 2.1.1 and 2.2.2.
- [ ] Disabled-state audit.
- [ ] Permissions/security/CSRF audit.
- [ ] Remaining functional compatibility matrix on Geeklog 2.1.1 and 2.2.2.
- [ ] Denim/Eclipse/mobile visual regression pass.
- [ ] Language fallback audit.
- [x] README updated for Topic inheritance and `faq-context`.
- [ ] Final upgrade / release notes.
- [ ] Cache profiling decision: add nothing unless justified.

---

## 32. Acceptance criteria for 1.3.0

FAQ 1.3.0 is ready when:

1. existing 1.2.x FAQ/category content upgrades without data loss;
2. the plugin installs and operates on Geeklog 2.1.1 and 2.2.2 within the declared compatibility range;
3. unsupported 2.2.2-only enhancements degrade gracefully on 2.1.1;
4. FAQ entries expose normalized Item Info;
5. FAQ emits lifecycle notifications through the best API available;
6. existing FAQ and FAQ-category autotags still work;
7. FAQ entries and whole FAQ categories can be associated with at least Stories and Static Pages without storing FAQ data in those providers;
8. contextual FAQ rendering works where the host exposes a placement point;
9. manual/autotag fallback exists when automatic placement is unavailable;
10. Coverage can identify supported content with/without FAQ without private-table coupling;
11. FAQ remains fully usable without Hub;
12. Hub integration, when available, does not duplicate provider ownership;
13. public/admin output respects Geeklog permissions;
14. disabling FAQ suppresses public contributions;
15. configuration uses Geeklog-native configuration patterns;
16. sitemap/metadata/structured-data features do not cause failures on older supported Geeklog versions;
17. README, upgrade instructions and release notes are complete;
18. an installable release archive can be generated from the final 1.3.0 tree;
19. opening and saving an existing FAQ/category without editing its content leaves its stored `description` unchanged;
20. the FAQ editor uses Geeklog's Advanced Editor API where safely available and contains no `editor_generate()` dependency;
21. HTML source mode remains available and MediaGallery insertion uses the modern picker when that capability is present.

---

## 33. Explicit non-goals for 1.3.0

Unless implementation proves trivial and safe, do not make these release blockers:

- mandatory Agent/LLM integration;
- automatic AI-generated FAQ publication;
- mandatory Hub installation;
- direct editing of Story/Static Page source text;
- plugin-specific integrations for every Geeklog content plugin;
- replacing the generic Geeklog Plugin API with a FAQ-specific ecosystem API;
- reproducing 2.2.2-only Core behavior inside Geeklog 2.1.1.

The priority is one clean FAQ provider with reliable fallback behavior, not parallel implementations for every consumer.
