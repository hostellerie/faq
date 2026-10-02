<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => 'Domande frequenti',
    'FAQ' => 'FAQ',
    'no_new' => 'Nessuna nuova FAQ recente',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'Categorie',
    'Question' => 'Domanda',
    'Hits' => 'Visualizzazioni',
    'Updated' => 'Aggiornato',
    'Answer' => 'Risposta',
    'permalink' => 'Permalink',
    'back_to' => 'Torna a',
    'breadcrumb_aria' => 'Percorso di navigazione FAQ',
    'actions_aria' => 'Azioni FAQ',
    'related_questions' => 'Domande correlate',
    'category_page_title' => 'Categoria FAQ: %s',
    'no_autolink_faq' => '[La FAQ con ID "%s" non esiste o non è accessibile]',
    'no_autolink_cat' => '[La categoria FAQ "%s" non esiste o non è accessibile]',
    'autolink_error' => '[errore nel collegamento FAQ "%s"]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'Sembra che non ci siano FAQ su questo sito oppure che nessuno ne abbia mai consultata una.',
    'stats_summary' => 'Categorie/voci FAQ (visualizzazioni) nel sistema',
    'Question' => 'Domanda',
    'Hits' => 'Visualizzazioni',
    'headline' => 'Le dieci FAQ più consultate'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'Risultati FAQ',
    'title' => 'Domanda',
    'date' => 'Aggiornato',
    'author' => 'Autore',
    'category' => 'Categoria',
    'hits' => 'Visualizzazioni'
);

$PLG_faq_MESSAGE1 = 'Stai tentando di accedere a una categoria FAQ alla quale non hai accesso o che non esiste. Il tentativo è stato registrato.';
$PLG_faq_MESSAGE2 = 'Stai tentando di accedere a una voce FAQ alla quale non hai accesso o che non esiste. Il tentativo è stato registrato.';
$PLG_faq_MESSAGE3 = 'La FAQ è stata eliminata correttamente.';
$PLG_faq_MESSAGE4 = 'La FAQ è stata salvata correttamente.';
$PLG_faq_MESSAGE5 = 'Stai tentando di eseguire un\'azione su una voce FAQ alla quale non hai accesso o che non esiste. Il tentativo è stato registrato.';
$PLG_faq_MESSAGE6 = 'L\'aggiornamento del plugin FAQ è riuscito.';
$PLG_faq_MESSAGE7 = 'L\'aggiornamento del plugin FAQ non è riuscito.';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'Categoria FAQ',
    'FAQ_Entry' => 'Voce FAQ',
    'FAQ Entries' => 'Voci FAQ',
    'Edit' => 'Modifica',
    'FAQ Editor' => 'Editor FAQ',
    'Cat Editor' => 'Editor categorie FAQ',
    'Access Denied MSG' => 'Spiacenti, non hai accesso alla pagina di amministrazione delle FAQ. Tutti i tentativi di accesso a funzioni non autorizzate vengono registrati.',
    'delete' => 'elimina',
    'save' => 'salva',
    'cancel' => 'annulla',
    'show' => 'mostra',
    'accessdenied' => "Stai tentando di accedere a un elemento FAQ per il quale non disponi dei diritti necessari. Il tentativo è stato registrato. <a href="{$_CONF['site_admin_url']}/plugins/faq/index.php">Torna alla schermata di amministrazione FAQ</a>.",
    'title' => 'Titolo',
    'description' => 'Descrizione',
    'question' => 'Domanda',
    'answer' => 'Risposta',
    'id' => 'ID',
    'id_auto_help' => 'L\'ID viene generato automaticamente dal titolo per creare un URL leggibile. Puoi modificarlo prima del primo salvataggio.',
    'id_change_help' => 'Questo ID fa parte dell\'URL pubblico. Modificarlo può influire sull\'indicizzazione nei motori di ricerca e sui collegamenti esistenti.',
    'id_change_confirm' => 'Confermo di voler modificare l\'ID e l\'URL pubblico.',
    'id_change_required' => 'La modifica dell\'ID richiede una conferma esplicita.',
    'id_exists' => 'Questo ID è già in uso. Scegli un altro ID.',
    'hits' => 'Visualizzazioni',
    'order' => 'Ordine',
    'order_help' => 'Controlla la posizione di questa FAQ nella categoria. I valori più bassi vengono visualizzati per primi; valori come 10, 20 e 30 lasciano spazio per inserimenti successivi.',
    'position' => 'Posizione',
    'position_first' => 'Prima',
    'position_last' => 'Ultima',
    'position_after' => 'Dopo: %s',
    'position_help' => 'Scegli la posizione di questa FAQ nella categoria. L\'ordinamento interno viene ricalcolato automaticamente a intervalli di 10.',
    'changed' => 'Aggiornato',
    'category' => 'Categoria',
    'all_cat' => 'Tutte le categorie',
    'date_will_update' => '<b>NOTA:</b> La data verrà aggiornata al salvataggio!',
    'reset_date' => 'Aggiorna la data di modifica a <i>adesso</i>.',
    'save_rights_error' => 'Non puoi salvare con autorizzazioni che non possiedi.',
    'missing_fields_faq' => 'Devi indicare una domanda, una risposta e una categoria per ogni voce FAQ.',
    'missing_fields_cat' => 'Devi indicare un titolo e una descrizione per ogni categoria FAQ.',
    'delete_note' => 'NOTA: eliminando questa categoria verranno eliminate TUTTE le FAQ associate.',
    'FAQ Plugin' => 'Plugin FAQ',
    'faqman_import' => 'Il plugin FAQMAN è installato (non è lo stesso di questo plugin). Puoi importare i dati dal plugin FAQMAN.',
    'cat instructions' => 'Crea e gestisci qui le categorie FAQ. Usa la scheda Domande per gestire le domande associate a ciascuna categoria.',
    'faq instructions' => 'Crea e gestisci qui le domande e le risposte FAQ. Usa il filtro delle categorie per trovare rapidamente le FAQ da modificare.',
    'import' => 'importa',
    'access' => 'Accesso',
    'editor_mode' => 'Modalità editor',
    'visual_editor' => 'Editor visuale',
    'html_source' => 'Sorgente HTML',
    'insert_media' => 'Inserisci media',
    'questions' => 'Domande',
    'categories' => 'Categorie',
    'associations' => 'Associazioni',
    'coverage' => 'Copertura',
    'configuration' => 'Configurazione',
    'new_question' => 'Nuova domanda',
    'new_category' => 'Nuova categoria',
    'administration_aria' => 'Amministrazione FAQ'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'Associazioni FAQ',
    'intro' => 'Associa una singola FAQ o un\'intera categoria FAQ ai contenuti esposti dai provider Geeklog. Un\'associazione di categoria è dinamica: le nuove FAQ aggiunte a quella categoria vengono incluse automaticamente. Provider e contenuti selezionabili vengono individuati automaticamente quando possibile; l\'ID manuale resta disponibile come alternativa.',
    'add_association' => 'Aggiungi associazione',
    'association_source' => 'Origine associazione',
    'individual_faq' => 'FAQ singola',
    'whole_category' => 'Intera categoria',
    'faq' => 'FAQ',
    'faq_category' => 'Categoria FAQ',
    'select_category' => 'Seleziona una categoria',
    'provider' => 'Provider',
    'select_provider' => 'Seleziona un provider',
    'content' => 'Contenuto',
    'select_provider_first' => 'Seleziona prima un provider',
    'content_id' => 'ID contenuto',
    'placement' => 'Posizione',
    'automatic' => 'Automatica',
    'manual_only' => 'Solo manuale',
    'order' => 'Ordine',
    'advanced_fallback' => 'Alternativa avanzata',
    'subtype' => 'Sottotipo',
    'subtype_help' => 'Il sottotipo viene normalmente rilevato dall\'elemento del provider selezionato. Inseriscilo manualmente solo per un provider legacy/personalizzato che non lo espone.',
    'save_association' => 'Salva associazione',
    'current_faq_associations' => 'Associazioni correnti di FAQ singole',
    'current_category_associations' => 'Associazioni correnti di categorie',
    'filtered_content' => 'Contenuto filtrato:',
    'show_all_associations' => 'Mostra tutte le associazioni',
    'relation_table_missing' => 'La tabella delle relazioni FAQ 1.3.0 non è ancora installata. Esegui l\'aggiornamento del plugin.',
    'category_relation_table_missing' => 'La tabella delle relazioni delle categorie FAQ non è ancora installata.',
    'category' => 'Categoria',
    'action' => 'Azione',
    'delete' => 'Elimina',
    'category_association_saved' => 'Associazione di categoria salvata. Le nuove FAQ aggiunte a questa categoria verranno incluse automaticamente.',
    'association_saved' => 'Associazione salvata.',
    'association_save_failed' => 'Impossibile salvare l\'associazione. Controlla la FAQ/categoria e il contenuto selezionati.',
    'association_deleted' => 'Associazione eliminata.',
    'update' => 'Aggiorna',
    'association_updated' => 'Associazione aggiornata.',
    'association_update_failed' => 'Impossibile aggiornare l\'associazione.',
    'select_content' => 'Seleziona contenuto',
    'loading' => 'Caricamento…',
    'no_selectable_content' => 'Nessun contenuto selezionabile',
    'enter_id_manually' => 'Inserisci manualmente l\'ID…',
    'collection_unavailable' => 'Raccolta non disponibile; inserisci manualmente l\'ID del contenuto.',
    'unable_load_collection' => 'Impossibile caricare la raccolta del provider; inserisci manualmente l\'ID del contenuto.',
    'manual_fallback' => 'Alternativa manuale: inserisci l\'ID del contenuto. Il sottotipo resta facoltativo.',
    'provider_no_item_info' => 'Questo provider non espone Geeklog Item Info.',
    'provider_no_content' => 'Il provider non ha restituito contenuti selezionabili.',
    'provider_selectable_count' => '%d elemento/i selezionabile/i.',
    'external_confirmation_required' => 'Questo articolo contiene già un segnale FAQ/Q&A esterno. Conferma esplicitamente l\'associazione prima di salvarla.',
    'confirm_external_faq' => 'Se l\'articolo selezionato contiene già una FAQ/Q&A esterna, confermo che questa associazione gestita deve comunque essere aggiunta.',
    'topic_provider' => 'Argomento Geeklog',
    'topic_scope' => 'Ambito argomento',
    'topic_scope_both' => 'Argomento + articoli dell\'argomento',
    'topic_scope_topic' => 'Solo argomento',
    'topic_scope_articles' => 'Solo articoli dell\'argomento',
    'topic_scope_help' => 'Per un\'associazione con un argomento, scegli se le FAQ devono apparire nella pagina dell\'argomento, essere ereditate dai relativi articoli o entrambe le opzioni.',
    'display_help_title' => 'Come funziona la visualizzazione contestuale delle FAQ',
    'display_help_intro' => 'Le associazioni possono essere visualizzate automaticamente o posizionate manualmente nel contenuto.',
    'display_help_automatic' => 'La FAQ viene visualizzata nel punto di inserimento nativo supportato dal provider selezionato.',
    'display_help_manual' => 'Nessun output automatico. Inserisci questo autotag nel punto in cui deve apparire la FAQ associata:',
    'display_help_related' => 'Visualizza esplicitamente le associazioni per un provider e un ID contenuto.',
    'display_help_embed' => 'Incorpora una FAQ specifica indipendentemente dalle associazioni.',
    'display_help_context_note' => '[faq-context] usa il provider e l\'ID del contenuto corrente. Negli articoli applica anche l\'ereditarietà degli argomenti e la protezione da duplicati/FAQ esterne.'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'Copertura FAQ',
    'intro' => 'Controlla quali contenuti usano FAQ gestite, FAQ ereditate dagli argomenti o FAQ già presenti nel contenuto. Usa i filtri per trovare contenuti senza FAQ, segnali FAQ esterni o associazioni da rivedere.',
    'provider' => 'Provider',
    'faq_status' => 'Stato FAQ',
    'all' => 'Tutti',
    'managed_faq_only' => 'Solo FAQ gestita',
    'external_faq_only' => 'Solo segnale FAQ esterno',
    'managed_external' => 'Gestita + esterna',
    'no_faq_detected' => 'Nessuna FAQ rilevata',
    'show' => 'Mostra',
    'provider_not_enumerable' => 'Questo provider non espone una raccolta enumerabile di Item Info in questa installazione. FAQ non interrogherà le tabelle dei plugin di terze parti. Le associazioni manuali restano disponibili.',
    'core_audit_label' => 'Controllo dei contenuti core:',
    'core_audit_help' => 'Articoli e pagine statiche vengono analizzati in sola lettura perché i relativi provider Geeklog attuali non espongono il contratto di raccolta/contenuto richiesto. Il rilevamento di FAQ esterne è un segnale editoriale, non una prova.',
    'managed' => 'Gestita',
    'external_signal' => 'Segnale esterno',
    'none' => 'Nessuna',
    'manage' => 'Gestisci',
    'edit' => 'Modifica',
    'managed_relations' => 'Relazioni gestite:',
    'managed_only' => 'Solo gestita',
    'external_only' => 'Solo esterna',
    'both' => 'Entrambe',
    'content' => 'Contenuto',
    'external_signals' => 'Segnali esterni',
    'action' => 'Azione',
    'articles' => 'Articoli',
    'topics' => 'Argomenti',
    'origin_direct' => 'Associazione diretta',
    'origin_topic' => 'Ereditata dall\'argomento',
    'topic_inheritance_blocked' => 'Ereditarietà dell\'argomento bloccata da una FAQ esterna',
    'static_pages' => 'Pagine statiche',
    'videos' => 'Video',
    'documents' => 'Documenti',
    'maps' => 'Mappe',
    'media_gallery' => 'Galleria multimediale',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'Microdati FAQPage',
    'signal_question_answer_schema' => 'Schema Question/Answer',
    'signal_details_summary' => 'Q&A ripetute con details/summary',
    'signal_faq_heading' => 'Intestazione FAQ'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'Importazione plugin FAQ',
    'no_topics' => 'Non ci sono argomenti FAQMAN da importare.',
    'no_faqman' => 'Il plugin FAQMAN non è installato.',
    'not_root' => 'Devi essere membro del gruppo ROOT per importare gli argomenti FAQMAN.',
    'unknown' => 'Azione di importazione sconosciuta: ',
    'imported' => '%d FAQ importate in %d categorie'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'Configurazione FAQ'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => 'Nascondi le FAQ da Novità',
    'hidefaqmenu' => 'Nascondi la voce FAQ dal menu',
    'newfaqinterval' => 'Intervallo nuove FAQ (secondi)',
    'no_hit_rights' => 'Diritti esclusi dal conteggio delle visualizzazioni',
    'contextual_enabled' => 'Abilita FAQ contestuali',
    'contextual_default_placement' => 'Posizionamento contestuale predefinito',
    'structured_data' => 'Abilita dati strutturati FAQ',
    'coverage_limit' => 'Limite risultati pagina Copertura',
    'default_permissions' => 'Permessi predefiniti'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'Impostazioni FAQ'
);

$LANG_tab['faq'] = array(
    'tab_general' => 'Generale',
    'tab_context' => 'FAQ contestuali',
    'tab_permissions' => 'Permessi'
);

$LANG_fs['faq'] = array(
    'fs_general' => 'Impostazioni generali',
    'fs_context' => 'Impostazioni FAQ contestuali',
    'fs_permissions' => 'Permessi predefiniti'
);

$LANG_configselects['faq'][0] = array(
    'Vero' => 1,
    'Falso' => 0
);

$LANG_configselects['faq'][1] = array(
    'Automatico (punto di visualizzazione elemento del provider)' => 'automatic',
    'Solo manuale' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    'Nessun accesso' => 0,
    'Sola lettura' => 2,
    'Lettura-scrittura' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => 'Nasconde le voci FAQ dal blocco Novità di Geeklog.',
    'hidefaqmenu' => 'Nasconde la voce FAQ dal menu del sito mantenendo disponibili gli URL pubblici delle FAQ.',
    'newfaqinterval' => 'Numero di secondi durante i quali una FAQ aggiornata di recente viene considerata nuova.',
    'no_hit_rights' => 'Elenco separato da virgole dei diritti FAQ i cui utenti non devono incrementare i contatori delle visualizzazioni.',
    'contextual_enabled' => 'Consente di visualizzare associazioni FAQ all\'interno di provider di contenuti supportati, come articoli, Documenti, Mappe e Video.',
    'contextual_default_placement' => 'Automatico consente al provider di contenuti di scegliere il proprio punto di visualizzazione nativo. Solo manuale disabilita l\'output contestuale automatico.',
    'structured_data' => 'Genera dati strutturati FAQPage nelle pagine FAQ autonome quando appropriato.',
    'coverage_limit' => 'Numero massimo di elementi del provider analizzati e visualizzati nella pagina di amministrazione Copertura.',
    'default_permissions' => 'Permessi predefiniti per proprietario, gruppo, membri e utenti anonimi assegnati ai nuovi contenuti FAQ.'
);
