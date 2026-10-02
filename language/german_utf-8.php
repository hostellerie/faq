<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => 'Häufig gestellte Fragen',
    'FAQ' => 'FAQ',
    'no_new' => 'Keine neuen FAQ in letzter Zeit',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'Kategorien',
    'Question' => 'Frage',
    'Hits' => 'Aufrufe',
    'Updated' => 'Aktualisiert',
    'Answer' => 'Antwort',
    'permalink' => 'Permalink',
    'back_to' => 'Zurück zu',
    'breadcrumb_aria' => 'FAQ-Brotkrümelnavigation',
    'actions_aria' => 'FAQ-Aktionen',
    'related_questions' => 'Verwandte Fragen',
    'category_page_title' => 'FAQ-Kategorie: %s',
    'no_autolink_faq' => '[FAQ-ID "%s" existiert nicht oder Sie haben keinen Zugriff darauf]',
    'no_autolink_cat' => '[FAQ-Kategorie "%s" existiert nicht oder Sie haben keinen Zugriff darauf]',
    'autolink_error' => '[Fehler im FAQ-Link "%s"]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'Offenbar gibt es auf dieser Website keine FAQ oder es wurde noch keine aufgerufen.',
    'stats_summary' => 'FAQ-Kategorien/Einträge (Aufrufe) im System',
    'Question' => 'Frage',
    'Hits' => 'Aufrufe',
    'headline' => 'Die zehn meistaufgerufenen FAQ'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'FAQ-Ergebnisse',
    'title' => 'Frage',
    'date' => 'Aktualisiert',
    'author' => 'Autor',
    'category' => 'Kategorie',
    'hits' => 'Aufrufe'
);

$PLG_faq_MESSAGE1 = 'Sie versuchen, auf eine FAQ-Kategorie zuzugreifen, für die Sie keine Berechtigung haben oder die nicht existiert. Dieser Versuch wurde protokolliert.';
$PLG_faq_MESSAGE2 = 'Sie versuchen, auf einen FAQ-Eintrag zuzugreifen, für den Sie keine Berechtigung haben oder der nicht existiert. Dieser Versuch wurde protokolliert.';
$PLG_faq_MESSAGE3 = 'Die FAQ wurde erfolgreich gelöscht.';
$PLG_faq_MESSAGE4 = 'Die FAQ wurde erfolgreich gespeichert.';
$PLG_faq_MESSAGE5 = 'Sie versuchen, eine Aktion für einen FAQ-Eintrag auszuführen, für den Sie keine Berechtigung haben oder der nicht existiert. Dieser Versuch wurde protokolliert.';
$PLG_faq_MESSAGE6 = 'Das Upgrade des FAQ-Plugins wurde erfolgreich abgeschlossen.';
$PLG_faq_MESSAGE7 = 'Das Upgrade des FAQ-Plugins ist fehlgeschlagen.';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'FAQ-Kategorie',
    'FAQ_Entry' => 'FAQ-Eintrag',
    'FAQ Entries' => 'FAQ-Einträge',
    'Edit' => 'Bearbeiten',
    'FAQ Editor' => 'FAQ-Editor',
    'Cat Editor' => 'Editor für FAQ-Kategorien',
    'Access Denied MSG' => 'Sie haben keinen Zugriff auf die FAQ-Administrationsseite. Alle Versuche, auf nicht autorisierte Funktionen zuzugreifen, werden protokolliert.',
    'delete' => 'löschen',
    'save' => 'speichern',
    'cancel' => 'abbrechen',
    'show' => 'anzeigen',
    'accessdenied' => "Sie versuchen, auf ein FAQ-Element zuzugreifen, für das Sie keine Berechtigung haben. Dieser Versuch wurde protokolliert. Bitte <a href="{$_CONF['site_admin_url']}/plugins/faq/index.php">kehren Sie zur FAQ-Administration zurück</a>.",
    'title' => 'Titel',
    'description' => 'Beschreibung',
    'question' => 'Frage',
    'answer' => 'Antwort',
    'id' => 'ID',
    'id_auto_help' => 'Die ID wird automatisch aus dem Titel erzeugt, um eine lesbare URL zu erstellen. Sie können sie vor dem ersten Speichern anpassen.',
    'id_change_help' => 'Diese ID ist Bestandteil der öffentlichen URL. Eine Änderung kann die Suchmaschinenindexierung und vorhandene Links beeinflussen.',
    'id_change_confirm' => 'Ich bestätige, dass ich die ID und die öffentliche URL ändern möchte.',
    'id_change_required' => 'Das Ändern der ID erfordert eine ausdrückliche Bestätigung.',
    'id_exists' => 'Diese ID wird bereits verwendet. Wählen Sie eine andere ID.',
    'hits' => 'Aufrufe',
    'order' => 'Reihenfolge',
    'order_help' => 'Steuert die Position dieser FAQ innerhalb ihrer Kategorie. Niedrigere Werte werden zuerst angezeigt; Werte wie 10, 20 und 30 lassen Platz für spätere Einfügungen.',
    'position' => 'Position',
    'position_first' => 'Erste',
    'position_last' => 'Letzte',
    'position_after' => 'Nach: %s',
    'position_help' => 'Wählen Sie die Position dieser FAQ innerhalb ihrer Kategorie. Die interne Reihenfolge wird automatisch in 10er-Schritten neu berechnet.',
    'changed' => 'Aktualisiert',
    'category' => 'Kategorie',
    'all_cat' => 'Alle Kategorien',
    'date_will_update' => '<b>HINWEIS:</b> Das Datum wird beim Speichern aktualisiert!',
    'reset_date' => 'Änderungsdatum auf <i>jetzt</i> setzen.',
    'save_rights_error' => 'Sie können nicht mit Berechtigungen speichern, die Sie nicht besitzen.',
    'missing_fields_faq' => 'Für jeden FAQ-Eintrag müssen Frage, Antwort und Kategorie angegeben werden.',
    'missing_fields_cat' => 'Für jede FAQ-Kategorie müssen Titel und Beschreibung angegeben werden.',
    'delete_note' => 'HINWEIS: Beim Löschen dieser Kategorie werden ALLE zugehörigen FAQ gelöscht.',
    'FAQ Plugin' => 'FAQ-Plugin',
    'faqman_import' => 'Das FAQMAN-Plugin ist installiert (es handelt sich nicht um dasselbe Plugin). Sie können Daten aus FAQMAN importieren.',
    'cat instructions' => 'Erstellen und verwalten Sie hier FAQ-Kategorien. Verwenden Sie die Registerkarte Fragen, um die Fragen der jeweiligen Kategorie zu verwalten.',
    'faq instructions' => 'Erstellen und verwalten Sie hier FAQ-Fragen und -Antworten. Verwenden Sie den Kategorienfilter, um die zu bearbeitenden FAQ schnell zu finden.',
    'import' => 'importieren',
    'access' => 'Zugriff',
    'editor_mode' => 'Editor-Modus',
    'visual_editor' => 'Visueller Editor',
    'html_source' => 'HTML-Quelltext',
    'insert_media' => 'Medien einfügen',
    'questions' => 'Fragen',
    'categories' => 'Kategorien',
    'associations' => 'Zuordnungen',
    'coverage' => 'Abdeckung',
    'configuration' => 'Konfiguration',
    'new_question' => 'Neue Frage',
    'new_category' => 'Neue Kategorie',
    'administration_aria' => 'FAQ-Administration'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'FAQ-Zuordnungen',
    'intro' => 'Ordnen Sie entweder eine einzelne FAQ oder eine vollständige FAQ-Kategorie Inhalten zu, die von Geeklog-Providern bereitgestellt werden. Eine Kategoriezuordnung ist dynamisch: Neu zur Kategorie hinzugefügte FAQ werden automatisch einbezogen. Provider und auswählbare Inhalte werden nach Möglichkeit automatisch erkannt; eine manuelle ID bleibt als Alternative verfügbar.',
    'add_association' => 'Zuordnung hinzufügen',
    'association_source' => 'Quelle der Zuordnung',
    'individual_faq' => 'Einzelne FAQ',
    'whole_category' => 'Gesamte Kategorie',
    'faq' => 'FAQ',
    'faq_category' => 'FAQ-Kategorie',
    'select_category' => 'Kategorie auswählen',
    'provider' => 'Provider',
    'select_provider' => 'Provider auswählen',
    'content' => 'Inhalt',
    'select_provider_first' => 'Zuerst einen Provider auswählen',
    'content_id' => 'Inhalts-ID',
    'placement' => 'Platzierung',
    'automatic' => 'Automatisch',
    'manual_only' => 'Nur manuell',
    'order' => 'Reihenfolge',
    'advanced_fallback' => 'Erweiterte Alternative',
    'subtype' => 'Untertyp',
    'subtype_help' => 'Der Untertyp wird normalerweise aus dem ausgewählten Provider-Element ermittelt. Geben Sie ihn nur bei einem älteren oder benutzerdefinierten Provider manuell ein, der ihn nicht bereitstellt.',
    'save_association' => 'Zuordnung speichern',
    'current_faq_associations' => 'Aktuelle Zuordnungen einzelner FAQ',
    'current_category_associations' => 'Aktuelle Kategoriezuordnungen',
    'filtered_content' => 'Gefilterter Inhalt:',
    'show_all_associations' => 'Alle Zuordnungen anzeigen',
    'relation_table_missing' => 'Die Relationstabelle von FAQ 1.3.0 ist noch nicht installiert. Führen Sie das Plugin-Upgrade aus.',
    'category_relation_table_missing' => 'Die Relationstabelle für FAQ-Kategorien ist noch nicht installiert.',
    'category' => 'Kategorie',
    'action' => 'Aktion',
    'delete' => 'Löschen',
    'category_association_saved' => 'Kategoriezuordnung gespeichert. Neue FAQ in dieser Kategorie werden automatisch einbezogen.',
    'association_saved' => 'Zuordnung gespeichert.',
    'association_save_failed' => 'Die Zuordnung konnte nicht gespeichert werden. Prüfen Sie die ausgewählte FAQ/Kategorie und den Inhalt.',
    'association_deleted' => 'Zuordnung gelöscht.',
    'update' => 'Aktualisieren',
    'association_updated' => 'Zuordnung aktualisiert.',
    'association_update_failed' => 'Die Zuordnung konnte nicht aktualisiert werden.',
    'select_content' => 'Inhalt auswählen',
    'loading' => 'Wird geladen…',
    'no_selectable_content' => 'Kein auswählbarer Inhalt',
    'enter_id_manually' => 'ID manuell eingeben…',
    'collection_unavailable' => 'Sammlung nicht verfügbar; geben Sie die Inhalts-ID manuell ein.',
    'unable_load_collection' => 'Die Provider-Sammlung konnte nicht geladen werden; geben Sie die Inhalts-ID manuell ein.',
    'manual_fallback' => 'Manuelle Alternative: Geben Sie die Inhalts-ID ein. Der Untertyp bleibt optional.',
    'provider_no_item_info' => 'Dieser Provider stellt keine Geeklog Item Info bereit.',
    'provider_no_content' => 'Der Provider hat keinen auswählbaren Inhalt zurückgegeben.',
    'provider_selectable_count' => '%d auswählbare(s) Element(e).',
    'external_confirmation_required' => 'Dieser Artikel enthält bereits ein externes FAQ/Q&A-Signal. Bestätigen Sie die Zuordnung ausdrücklich vor dem Speichern.',
    'confirm_external_faq' => 'Falls der ausgewählte Artikel bereits eine externe FAQ/Q&A enthält, bestätige ich, dass diese verwaltete Zuordnung dennoch hinzugefügt werden soll.',
    'topic_provider' => 'Geeklog-Thema',
    'topic_scope' => 'Themenbereich',
    'topic_scope_both' => 'Thema + Themenartikel',
    'topic_scope_topic' => 'Nur Thema',
    'topic_scope_articles' => 'Nur Themenartikel',
    'topic_scope_help' => 'Wählen Sie bei einer Themenzuordnung, ob FAQ auf der Themenseite angezeigt, von den Artikeln des Themas geerbt oder in beiden Fällen verwendet werden sollen.',
    'display_help_title' => 'So funktioniert die kontextbezogene FAQ-Anzeige',
    'display_help_intro' => 'Zuordnungen können automatisch ausgegeben oder manuell im Inhalt platziert werden.',
    'display_help_automatic' => 'Die FAQ wird am nativen Einfügepunkt des ausgewählten Providers angezeigt.',
    'display_help_manual' => 'Keine automatische Ausgabe. Fügen Sie dieses Autotag dort ein, wo die zugeordnete FAQ erscheinen soll:',
    'display_help_related' => 'Zeigt Zuordnungen für einen Provider und eine Inhalts-ID ausdrücklich an.',
    'display_help_embed' => 'Bettet eine bestimmte FAQ unabhängig von Zuordnungen ein.',
    'display_help_context_note' => '[faq-context] verwendet den aktuellen Inhalts-Provider und die aktuelle ID. Bei Artikeln werden zusätzlich Themenvererbung und Schutz vor Duplikaten/externen FAQ angewendet.'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'FAQ-Abdeckung',
    'intro' => 'Prüfen Sie, welche Inhalte verwaltete FAQ, vom Thema geerbte FAQ oder bereits im Inhalt vorhandene FAQ verwenden. Nutzen Sie die Filter, um Inhalte ohne FAQ, externe FAQ-Signale oder zu prüfende Zuordnungen zu finden.',
    'provider' => 'Provider',
    'faq_status' => 'FAQ-Status',
    'all' => 'Alle',
    'managed_faq_only' => 'Nur verwaltete FAQ',
    'external_faq_only' => 'Nur externes FAQ-Signal',
    'managed_external' => 'Verwaltet + extern',
    'no_faq_detected' => 'Keine FAQ erkannt',
    'show' => 'Anzeigen',
    'provider_not_enumerable' => 'Dieser Provider stellt in dieser Installation keine aufzählbare Item-Info-Sammlung bereit. FAQ fragt keine Tabellen von Drittanbieter-Plugins ab. Manuelle Zuordnungen bleiben verfügbar.',
    'core_audit_label' => 'Prüfung der Kerninhalte:',
    'core_audit_help' => 'Artikel und statische Seiten werden schreibgeschützt geprüft, da ihre aktuellen Geeklog-Provider den erforderlichen Sammlungs-/Inhaltsvertrag nicht bereitstellen. Die Erkennung externer FAQ ist ein redaktionelles Signal, kein Beweis.',
    'managed' => 'Verwaltet',
    'external_signal' => 'Externes Signal',
    'none' => 'Keine',
    'manage' => 'Verwalten',
    'edit' => 'Bearbeiten',
    'managed_relations' => 'Verwaltete Beziehungen:',
    'managed_only' => 'Nur verwaltet',
    'external_only' => 'Nur extern',
    'both' => 'Beides',
    'content' => 'Inhalt',
    'external_signals' => 'Externe Signale',
    'action' => 'Aktion',
    'articles' => 'Artikel',
    'topics' => 'Themen',
    'origin_direct' => 'Direkte Zuordnung',
    'origin_topic' => 'Vom Thema geerbt',
    'topic_inheritance_blocked' => 'Themenvererbung durch externe FAQ blockiert',
    'static_pages' => 'Statische Seiten',
    'videos' => 'Videos',
    'documents' => 'Dokumente',
    'maps' => 'Karten',
    'media_gallery' => 'Mediengalerie',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'FAQPage-Mikrodaten',
    'signal_question_answer_schema' => 'Question/Answer-Schema',
    'signal_details_summary' => 'Wiederholte details/summary-Fragen und -Antworten',
    'signal_faq_heading' => 'FAQ-Überschrift'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'FAQ-Plugin-Import',
    'no_topics' => 'Es gibt keine FAQMAN-Themen zum Importieren.',
    'no_faqman' => 'Das FAQMAN-Plugin ist nicht installiert.',
    'not_root' => 'Sie müssen Mitglied der ROOT-Gruppe sein, um FAQMAN-Themen zu importieren.',
    'unknown' => 'Unbekannte Importaktion: ',
    'imported' => '%d FAQ in %d Kategorien importiert'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'FAQ-Konfiguration'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => 'FAQ aus Neuigkeiten ausblenden',
    'hidefaqmenu' => 'FAQ-Menüeintrag ausblenden',
    'newfaqinterval' => 'Intervall für neue FAQ (Sekunden)',
    'no_hit_rights' => 'Vom Aufrufzähler ausgeschlossene Rechte',
    'contextual_enabled' => 'Kontextbezogene FAQ aktivieren',
    'contextual_default_placement' => 'Standardplatzierung für kontextbezogene FAQ',
    'structured_data' => 'Strukturierte FAQ-Daten aktivieren',
    'coverage_limit' => 'Ergebnislimit der Abdeckungsseite',
    'default_permissions' => 'Standardberechtigungen'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'FAQ-Einstellungen'
);

$LANG_tab['faq'] = array(
    'tab_general' => 'Allgemein',
    'tab_context' => 'Kontextbezogene FAQ',
    'tab_permissions' => 'Berechtigungen'
);

$LANG_fs['faq'] = array(
    'fs_general' => 'Allgemeine Einstellungen',
    'fs_context' => 'Einstellungen für kontextbezogene FAQ',
    'fs_permissions' => 'Standardberechtigungen'
);

$LANG_configselects['faq'][0] = array(
    'Wahr' => 1,
    'Falsch' => 0
);

$LANG_configselects['faq'][1] = array(
    'Automatisch (Anzeigeort des Provider-Elements)' => 'automatic',
    'Nur manuell' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    'Kein Zugriff' => 0,
    'Nur Lesen' => 2,
    'Lesen-Schreiben' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => 'Blendet FAQ-Einträge aus dem Geeklog-Block Neuigkeiten aus.',
    'hidefaqmenu' => 'Blendet den FAQ-Eintrag im Website-Menü aus, während öffentliche FAQ-URLs verfügbar bleiben.',
    'newfaqinterval' => 'Anzahl der Sekunden, während der eine kürzlich aktualisierte FAQ als neu gilt.',
    'no_hit_rights' => 'Kommagetrennte Liste von FAQ-Rechten, deren Benutzer die FAQ-Aufrufzähler nicht erhöhen sollen.',
    'contextual_enabled' => 'Erlaubt die Anzeige von FAQ-Zuordnungen in unterstützten Inhalts-Providern wie Artikeln, Dokumenten, Karten und Videos.',
    'contextual_default_placement' => 'Automatisch lässt den Inhalts-Provider seinen nativen Anzeigeort wählen. Nur manuell deaktiviert die automatische kontextbezogene Ausgabe.',
    'structured_data' => 'Gibt bei Bedarf strukturierte FAQPage-Daten auf eigenständigen FAQ-Seiten aus.',
    'coverage_limit' => 'Maximale Anzahl von Provider-Elementen, die auf der Administrationsseite Abdeckung geprüft und angezeigt werden.',
    'default_permissions' => 'Standardberechtigungen für Eigentümer, Gruppe, Mitglieder und anonyme Benutzer, die neuen FAQ-Inhalten zugewiesen werden.'
);
