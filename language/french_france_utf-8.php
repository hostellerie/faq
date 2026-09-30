<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => 'FAQ',
    'FAQ' => 'FAQ',
    'no_new' => 'Aucune nouvelle FAQ récente',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'Catégories',
    'Question' => 'Question',
    'Hits' => 'Vues',
    'Updated' => 'Mis à jour',
    'Answer' => 'Réponse',
    'permalink' => 'Lien permanent',
    'back_to' => 'Retour à',
    'breadcrumb_aria' => 'Fil d’Ariane FAQ',
    'actions_aria' => 'Actions FAQ',
    'related_questions' => 'Questions associées',
    'category_page_title' => 'Catégorie FAQ : %s',
    'no_autolink_faq' => '[La FAQ "%s" n’existe pas ou vous n’y avez pas accès]',
    'no_autolink_cat' => '[La catégorie FAQ "%s" n’existe pas ou vous n’y avez pas accès]',
    'autolink_error' => '[Erreur dans le lien FAQ "%s"]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'Il semble qu’aucune FAQ ne soit disponible ou consultée sur ce site.',
    'stats_summary' => 'Catégories / entrées FAQ (vues)',
    'Question' => 'Question',
    'Hits' => 'Vues',
    'headline' => 'Top 10 des FAQ'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'Résultats FAQ',
    'title' => 'Question',
    'date' => 'Mis à jour',
    'author' => 'Auteur',
    'category' => 'Catégorie',
    'hits' => 'Vues'
);

$PLG_faq_MESSAGE1 = 'Vous tentez d’accéder à une catégorie FAQ inexistante ou pour laquelle vous ne disposez pas des droits nécessaires.';
$PLG_faq_MESSAGE2 = 'Vous tentez d’accéder à une FAQ inexistante ou pour laquelle vous ne disposez pas des droits nécessaires.';
$PLG_faq_MESSAGE3 = 'La FAQ a été supprimée avec succès.';
$PLG_faq_MESSAGE4 = 'La FAQ a été enregistrée avec succès.';
$PLG_faq_MESSAGE5 = 'Vous tentez d’effectuer une action sur une FAQ inexistante ou pour laquelle vous ne disposez pas des droits nécessaires.';
$PLG_faq_MESSAGE6 = 'La mise à jour du plugin FAQ a réussi.';
$PLG_faq_MESSAGE7 = 'La mise à jour du plugin FAQ a échoué.';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'Catégorie FAQ',
    'FAQ_Entry' => 'FAQ',
    'FAQ Entries' => 'FAQ',
    'Edit' => 'Modifier',
    'FAQ Editor' => 'Éditeur de FAQ',
    'Cat Editor' => 'Éditeur de catégorie FAQ',
    'Access Denied MSG' => 'Vous n’avez pas accès à l’administration des FAQ. Les tentatives d’accès non autorisées sont journalisées.',
    'delete' => 'supprimer',
    'save' => 'enregistrer',
    'cancel' => 'annuler',
    'show' => 'afficher',
    'accessdenied' => "Vous tentez d’accéder à un élément FAQ pour lequel vous ne disposez pas des droits nécessaires.",
    'title' => 'Titre',
    'description' => 'Description',
    'question' => 'Question',
    'answer' => 'Réponse',
    'id' => 'ID',
    'hits' => 'Vues',
    'changed' => 'Mis à jour',
    'category' => 'Catégorie',
    'all_cat' => 'Toutes les catégories',
    'date_will_update' => '<b>NOTE :</b> la date sera mise à jour lors de l’enregistrement.',
    'reset_date' => 'Mettre la date de modification à <i>maintenant</i>.',
    'save_rights_error' => 'Vous ne pouvez pas enregistrer avec des droits que vous ne possédez pas.',
    'missing_fields_faq' => 'Vous devez renseigner la question, la réponse et la catégorie.',
    'missing_fields_cat' => 'Vous devez renseigner le titre et la description de la catégorie.',
    'delete_note' => 'NOTE : supprimer cette catégorie supprime toutes les FAQ qui lui sont associées.',
    'FAQ Plugin' => 'Plugin FAQ',
    'faqman_import' => 'Le plugin FAQMAN est installé. Vous pouvez importer ses données dans FAQ.',
    'cat instructions' => 'Créez et gérez ici les catégories FAQ. Utilisez l’onglet Questions pour gérer les questions de chaque catégorie.',
    'import' => 'importer',
    'access' => 'Accès',
    'editor_mode' => 'Mode d’édition',
    'visual_editor' => 'Éditeur visuel',
    'html_source' => 'Source HTML',
    'insert_media' => 'Insérer un média',
    'questions' => 'Questions',
    'categories' => 'Catégories',
    'associations' => 'Associations',
    'coverage' => 'Couverture',
    'configuration' => 'Configuration',
    'new_question' => 'Nouvelle question',
    'new_category' => 'Nouvelle catégorie',
    'administration_aria' => 'Administration FAQ'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'Associations FAQ',
    'intro' => 'Associez une FAQ ou une catégorie FAQ entière à un contenu exposé par un fournisseur Geeklog. Une association de catégorie est dynamique : les nouvelles FAQ ajoutées à cette catégorie sont incluses automatiquement. Les fournisseurs et contenus sont découverts automatiquement lorsque cela est possible ; la saisie manuelle de l’ID reste disponible en secours.',
    'add_association' => 'Ajouter une association',
    'association_source' => 'Source de l’association',
    'individual_faq' => 'FAQ individuelle',
    'whole_category' => 'Catégorie entière',
    'faq' => 'FAQ',
    'faq_category' => 'Catégorie FAQ',
    'select_category' => 'Sélectionner une catégorie',
    'provider' => 'Fournisseur',
    'select_provider' => 'Sélectionner un fournisseur',
    'content' => 'Contenu',
    'select_provider_first' => 'Sélectionnez d’abord un fournisseur',
    'content_id' => 'ID du contenu',
    'placement' => 'Emplacement',
    'automatic' => 'Automatique',
    'manual_only' => 'Manuel uniquement',
    'order' => 'Ordre',
    'advanced_fallback' => 'Saisie avancée',
    'subtype' => 'Sous-type',
    'subtype_help' => 'Le sous-type est normalement détecté à partir du contenu sélectionné. Saisissez-le manuellement uniquement pour un fournisseur ancien ou personnalisé qui ne l’expose pas.',
    'save_association' => 'Enregistrer l’association',
    'current_faq_associations' => 'Associations actuelles de FAQ individuelles',
    'current_category_associations' => 'Associations actuelles de catégories FAQ',
    'filtered_content' => 'Contenu filtré :',
    'show_all_associations' => 'Afficher toutes les associations',
    'relation_table_missing' => 'La table des relations FAQ 1.3.0 n’est pas encore installée. Lancez la mise à jour du plugin.',
    'category_relation_table_missing' => 'La table des relations de catégories FAQ n’est pas encore installée.',
    'category' => 'Catégorie',
    'action' => 'Action',
    'delete' => 'Supprimer',
    'category_association_saved' => 'Association de catégorie enregistrée. Les nouvelles FAQ ajoutées à cette catégorie seront incluses automatiquement.',
    'association_saved' => 'Association enregistrée.',
    'association_save_failed' => 'L’association n’a pas pu être enregistrée. Vérifiez la FAQ ou la catégorie sélectionnée ainsi que le contenu cible.',
    'association_deleted' => 'Association supprimée.',
    'select_content' => 'Sélectionner un contenu',
    'loading' => 'Chargement…',
    'no_selectable_content' => 'Aucun contenu sélectionnable',
    'enter_id_manually' => 'Saisir l’ID manuellement…',
    'collection_unavailable' => 'Collection indisponible ; saisissez manuellement l’ID du contenu.',
    'unable_load_collection' => 'Impossible de charger la collection du fournisseur ; saisissez manuellement l’ID du contenu.',
    'manual_fallback' => 'Saisie manuelle : indiquez l’ID du contenu. Le sous-type reste facultatif.',
    'provider_no_item_info' => 'Ce fournisseur n’expose pas Geeklog Item Info.',
    'provider_no_content' => 'Le fournisseur ne retourne aucun contenu sélectionnable.',
    'provider_selectable_count' => '%d élément(s) sélectionnable(s).',
    'external_confirmation_required' => 'Cet article contient déjà un signal FAQ/Q&R externe. Confirmez explicitement l’association avant de l’enregistrer.',
    'confirm_external_faq' => 'Si l’article sélectionné contient déjà une FAQ/Q&R externe, je confirme que cette association gérée doit tout de même être ajoutée.',
    'topic_provider' => 'Topic Geeklog',
    'display_help_title' => 'Fonctionnement de l’affichage contextuel',
    'display_help_intro' => 'Les associations peuvent être affichées automatiquement ou placées manuellement dans le contenu.',
    'display_help_automatic' => 'La FAQ est affichée au point d’insertion natif pris en charge par le fournisseur sélectionné.',
    'display_help_manual' => 'Aucun affichage automatique. Insérez cet autotag à l’endroit où les FAQ associées doivent apparaître :',
    'display_help_related' => 'Affiche explicitement les associations d’un fournisseur et d’un ID de contenu.',
    'display_help_embed' => 'Insère une FAQ précise indépendamment des associations.',
    'display_help_context_note' => '[faq-context] utilise automatiquement le fournisseur et l’ID du contenu courant. Dans un article, il applique aussi l’héritage des topics ainsi que la protection contre les doublons et les FAQ externes.'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'Couverture FAQ',
    'provider' => 'Fournisseur',
    'faq_status' => 'État FAQ',
    'all' => 'Tout',
    'managed_faq_only' => 'FAQ gérées uniquement',
    'external_faq_only' => 'Signal FAQ externe uniquement',
    'managed_external' => 'Géré + externe',
    'no_faq_detected' => 'Aucune FAQ détectée',
    'show' => 'Afficher',
    'provider_not_enumerable' => 'Ce fournisseur n’expose pas de collection Item Info énumérable sur cette installation. FAQ n’interrogera pas directement les tables des plugins tiers. Les associations manuelles restent disponibles.',
    'core_audit_label' => 'Audit du contenu Core :',
    'core_audit_help' => 'Les articles et pages statiques sont inspectés en lecture seule car leurs fournisseurs Geeklog actuels n’exposent pas encore le contrat de collection/contenu requis. La détection de FAQ externes est un signal éditorial, pas une preuve.',
    'managed' => 'Géré',
    'external_signal' => 'Signal externe',
    'none' => 'Aucun',
    'manage' => 'Gérer',
    'edit' => 'Modifier',
    'managed_relations' => 'Relations gérées :',
    'managed_only' => 'Géré uniquement',
    'external_only' => 'Externe uniquement',
    'both' => 'Les deux',
    'content' => 'Contenu',
    'external_signals' => 'Signaux externes',
    'action' => 'Action',
    'articles' => 'Articles',
    'topics' => 'Topics',
    'origin_direct' => 'Association directe',
    'origin_topic' => 'Héritée du topic',
    'topic_inheritance_blocked' => 'Héritage du topic bloqué par une FAQ externe',
    'static_pages' => 'Pages statiques',
    'videos' => 'Vidéos',
    'documents' => 'Documents',
    'maps' => 'Cartes',
    'media_gallery' => 'Galerie média',
    'signal_faqpage_jsonld' => 'FAQPage en JSON-LD',
    'signal_faqpage_microdata' => 'Microdonnées FAQPage',
    'signal_question_answer_schema' => 'Schéma Question/Réponse',
    'signal_details_summary' => 'Blocs questions/réponses details/summary répétés',
    'signal_faq_heading' => 'Titre de section FAQ'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'Import FAQ',
    'no_topics' => 'Aucun sujet FAQMAN à importer.',
    'no_faqman' => 'Le plugin FAQMAN n’est pas installé.',
    'not_root' => 'Vous devez appartenir au groupe ROOT pour importer les sujets FAQMAN.',
    'unknown' => 'Action d’import inconnue : ',
    'imported' => '%d FAQ importées dans %d catégories'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'Configuration FAQ'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => 'Masquer les FAQ dans les nouveautés',
    'hidefaqmenu' => 'Masquer l’entrée FAQ du menu',
    'newfaqinterval' => 'Durée pendant laquelle une FAQ est considérée comme nouvelle (secondes)',
    'no_hit_rights' => 'Droits exclus du comptage des vues',
    'contextual_enabled' => 'Activer les FAQ contextuelles',
    'contextual_default_placement' => 'Emplacement contextuel par défaut',
    'structured_data' => 'Activer les données structurées FAQ',
    'coverage_limit' => 'Limite de résultats de la page Couverture',
    'default_permissions' => 'Permissions par défaut'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'Paramètres FAQ'
);

$LANG_tab['faq'] = array(
    'tab_general' => 'Général',
    'tab_context' => 'FAQ contextuelles',
    'tab_permissions' => 'Permissions'
);

$LANG_fs['faq'] = array(
    'fs_general' => 'Paramètres généraux',
    'fs_context' => 'Paramètres des FAQ contextuelles',
    'fs_permissions' => 'Permissions par défaut'
);

$LANG_configselects['faq'][0] = array(
    'Oui' => 1,
    'Non' => 0
);

$LANG_configselects['faq'][1] = array(
    'Automatique (emplacement défini par le fournisseur)' => 'automatic',
    'Manuel uniquement' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    'Aucun accès' => 0,
    'Lecture seule' => 2,
    'Lecture-écriture' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => 'Masquer les FAQ dans le bloc des nouveautés Geeklog.',
    'hidefaqmenu' => 'Masquer l’entrée FAQ du menu tout en conservant les URL publiques accessibles.',
    'newfaqinterval' => 'Nombre de secondes pendant lesquelles une FAQ récemment mise à jour est considérée comme nouvelle.',
    'no_hit_rights' => 'Liste séparée par des virgules des droits FAQ dont les utilisateurs ne doivent pas incrémenter le compteur de vues.',
    'contextual_enabled' => 'Autoriser l’affichage des associations FAQ dans les fournisseurs de contenu compatibles.',
    'contextual_default_placement' => 'Automatique laisse le fournisseur choisir son emplacement natif. Manuel uniquement désactive la sortie contextuelle automatique.',
    'structured_data' => 'Produire les données structurées FAQPage sur les pages FAQ autonomes lorsque cela est approprié.',
    'coverage_limit' => 'Nombre maximal d’éléments fournisseur inspectés et affichés sur la page Couverture.',
    'default_permissions' => 'Permissions propriétaire, groupe, membres et anonymes attribuées aux nouveaux contenus FAQ.'
);
