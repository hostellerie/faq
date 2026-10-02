<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => 'Preguntas frecuentes',
    'FAQ' => 'FAQ',
    'no_new' => 'No hay preguntas frecuentes nuevas recientemente',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'Categorías',
    'Question' => 'Pregunta',
    'Hits' => 'Visitas',
    'Updated' => 'Actualizado',
    'Answer' => 'Respuesta',
    'permalink' => 'Enlace permanente',
    'back_to' => 'Volver a',
    'breadcrumb_aria' => 'Ruta de navegación de FAQ',
    'actions_aria' => 'Acciones de FAQ',
    'related_questions' => 'Preguntas relacionadas',
    'category_page_title' => 'Categoría de FAQ: %s',
    'no_autolink_faq' => '[La FAQ con ID "%s" no existe o no tiene acceso a ella]',
    'no_autolink_cat' => '[La categoría de FAQ "%s" no existe o no tiene acceso a ella]',
    'autolink_error' => '[error en el enlace de FAQ "%s"]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'Parece que no hay preguntas frecuentes en este sitio o que nadie ha consultado ninguna.',
    'stats_summary' => 'Categorías/entradas de FAQ (visitas) en el sistema',
    'Question' => 'Pregunta',
    'Hits' => 'Visitas',
    'headline' => 'Las diez FAQ más consultadas'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'Resultados de FAQ',
    'title' => 'Pregunta',
    'date' => 'Actualizado',
    'author' => 'Autor',
    'category' => 'Categoría',
    'hits' => 'Visitas'
);

$PLG_faq_MESSAGE1 = 'Está intentando acceder a una categoría de FAQ a la que no tiene acceso o que no existe. Este intento ha sido registrado.';
$PLG_faq_MESSAGE2 = 'Está intentando acceder a una entrada de FAQ a la que no tiene acceso o que no existe. Este intento ha sido registrado.';
$PLG_faq_MESSAGE3 = 'La FAQ se ha eliminado correctamente.';
$PLG_faq_MESSAGE4 = 'La FAQ se ha guardado correctamente.';
$PLG_faq_MESSAGE5 = 'Está intentando realizar una acción sobre una entrada de FAQ a la que no tiene acceso o que no existe. Este intento ha sido registrado.';
$PLG_faq_MESSAGE6 = 'La actualización del plugin FAQ se ha completado correctamente.';
$PLG_faq_MESSAGE7 = 'La actualización del plugin FAQ ha fallado.';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'Categoría de FAQ',
    'FAQ_Entry' => 'Entrada de FAQ',
    'FAQ Entries' => 'Entradas de FAQ',
    'Edit' => 'Editar',
    'FAQ Editor' => 'Editor de FAQ',
    'Cat Editor' => 'Editor de categorías de FAQ',
    'Access Denied MSG' => 'Lo sentimos, no tiene acceso a la página de administración de FAQ. Todos los intentos de acceso a funciones no autorizadas quedan registrados.',
    'delete' => 'eliminar',
    'save' => 'guardar',
    'cancel' => 'cancelar',
    'show' => 'mostrar',
    'accessdenied' => "Está intentando acceder a un elemento de FAQ para el que no tiene permisos. Este intento ha sido registrado. <a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php\">Vuelva a la pantalla de administración de FAQ</a>.",
    'title' => 'Título',
    'description' => 'Descripción',
    'question' => 'Pregunta',
    'answer' => 'Respuesta',
    'id' => 'ID',
    'id_auto_help' => 'El ID se genera automáticamente a partir del título para crear una URL legible. Puede ajustarlo antes de guardar por primera vez.',
    'id_change_help' => 'Este ID forma parte de la URL pública. Cambiarlo puede afectar a la indexación en buscadores y a los enlaces existentes.',
    'id_change_confirm' => 'Confirmo que deseo cambiar el ID y la URL pública.',
    'id_change_required' => 'Para cambiar el ID se requiere una confirmación explícita.',
    'id_exists' => 'Este ID ya está en uso. Elija otro ID.',
    'hits' => 'Visitas',
    'order' => 'Orden',
    'order_help' => 'Controla la posición de esta FAQ dentro de su categoría. Los valores menores aparecen primero; valores como 10, 20 y 30 dejan espacio para inserciones posteriores.',
    'position' => 'Posición',
    'position_first' => 'Primera',
    'position_last' => 'Última',
    'position_after' => 'Después de: %s',
    'position_help' => 'Elija la posición de esta FAQ dentro de su categoría. El orden interno se recalcula automáticamente en intervalos de 10.',
    'changed' => 'Actualizado',
    'category' => 'Categoría',
    'all_cat' => 'Todas las categorías',
    'date_will_update' => '<b>NOTA:</b> ¡La fecha se actualizará al guardar!',
    'reset_date' => 'Actualizar la fecha de modificación a <i>ahora</i>.',
    'save_rights_error' => 'No puede guardar con permisos que no posee.',
    'missing_fields_faq' => 'Debe proporcionar una pregunta, una respuesta y una categoría para cada entrada de FAQ.',
    'missing_fields_cat' => 'Debe proporcionar un título y una descripción para cada categoría de FAQ.',
    'delete_note' => 'NOTA: Al eliminar esta categoría se eliminan TODAS las FAQ asociadas a ella.',
    'FAQ Plugin' => 'Plugin FAQ',
    'faqman_import' => 'Tiene instalado el plugin FAQMAN (que no es el mismo que este plugin). Puede importar los datos del plugin FAQMAN.',
    'cat instructions' => 'Cree y gestione aquí las categorías de FAQ. Use la pestaña Preguntas para gestionar las preguntas asociadas a cada categoría.',
    'faq instructions' => 'Cree y gestione aquí las preguntas y respuestas de FAQ. Use el filtro de categorías para encontrar rápidamente las FAQ que desea editar.',
    'import' => 'importar',
    'access' => 'Acceso',
    'editor_mode' => 'Modo del editor',
    'visual_editor' => 'Editor visual',
    'html_source' => 'Código HTML',
    'insert_media' => 'Insertar multimedia',
    'questions' => 'Preguntas',
    'categories' => 'Categorías',
    'associations' => 'Asociaciones',
    'coverage' => 'Cobertura',
    'configuration' => 'Configuración',
    'new_question' => 'Nueva pregunta',
    'new_category' => 'Nueva categoría',
    'administration_aria' => 'Administración de FAQ'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'Asociaciones de FAQ',
    'intro' => 'Asocie una FAQ individual o una categoría completa de FAQ con contenido expuesto por proveedores de Geeklog. Una asociación de categoría es dinámica: las nuevas FAQ añadidas a esa categoría se incluyen automáticamente. Los proveedores y el contenido seleccionable se detectan automáticamente cuando es posible; el ID manual sigue disponible como alternativa.',
    'add_association' => 'Añadir asociación',
    'association_source' => 'Origen de la asociación',
    'individual_faq' => 'FAQ individual',
    'whole_category' => 'Categoría completa',
    'faq' => 'FAQ',
    'faq_category' => 'Categoría de FAQ',
    'select_category' => 'Seleccione una categoría',
    'provider' => 'Proveedor',
    'select_provider' => 'Seleccione un proveedor',
    'content' => 'Contenido',
    'select_provider_first' => 'Seleccione primero un proveedor',
    'content_id' => 'ID del contenido',
    'placement' => 'Ubicación',
    'automatic' => 'Automática',
    'manual_only' => 'Solo manual',
    'order' => 'Orden',
    'advanced_fallback' => 'Alternativa avanzada',
    'subtype' => 'Subtipo',
    'subtype_help' => 'El subtipo suele detectarse a partir del elemento del proveedor seleccionado. Introdúzcalo manualmente solo para un proveedor antiguo o personalizado que no lo exponga.',
    'save_association' => 'Guardar asociación',
    'current_faq_associations' => 'Asociaciones actuales de FAQ individuales',
    'current_category_associations' => 'Asociaciones actuales de categorías',
    'filtered_content' => 'Contenido filtrado:',
    'show_all_associations' => 'Mostrar todas las asociaciones',
    'relation_table_missing' => 'La tabla de relaciones de FAQ 1.3.0 aún no está instalada. Ejecute la actualización del plugin.',
    'category_relation_table_missing' => 'La tabla de relaciones de categorías de FAQ aún no está instalada.',
    'category' => 'Categoría',
    'action' => 'Acción',
    'delete' => 'Eliminar',
    'category_association_saved' => 'Asociación de categoría guardada. Las nuevas FAQ añadidas a esta categoría se incluirán automáticamente.',
    'association_saved' => 'Asociación guardada.',
    'association_save_failed' => 'No se pudo guardar la asociación. Compruebe la FAQ/categoría y el contenido seleccionados.',
    'association_deleted' => 'Asociación eliminada.',
    'update' => 'Actualizar',
    'association_updated' => 'Asociación actualizada.',
    'association_update_failed' => 'No se pudo actualizar la asociación.',
    'select_content' => 'Seleccionar contenido',
    'loading' => 'Cargando…',
    'no_selectable_content' => 'No hay contenido seleccionable',
    'enter_id_manually' => 'Introducir el ID manualmente…',
    'collection_unavailable' => 'Colección no disponible; introduzca manualmente el ID del contenido.',
    'unable_load_collection' => 'No se pudo cargar la colección del proveedor; introduzca manualmente el ID del contenido.',
    'manual_fallback' => 'Alternativa manual: introduzca el ID del contenido. El subtipo sigue siendo opcional.',
    'provider_no_item_info' => 'Este proveedor no expone Geeklog Item Info.',
    'provider_no_content' => 'El proveedor no devolvió contenido seleccionable.',
    'provider_selectable_count' => '%d elemento(s) seleccionable(s).',
    'external_confirmation_required' => 'Este artículo ya contiene una señal externa de FAQ/Q&A. Confirme explícitamente la asociación antes de guardarla.',
    'confirm_external_faq' => 'Si el artículo seleccionado ya contiene una FAQ/Q&A externa, confirmo que esta asociación gestionada debe añadirse igualmente.',
    'topic_provider' => 'Tema de Geeklog',
    'topic_scope' => 'Ámbito del tema',
    'topic_scope_both' => 'Tema + artículos del tema',
    'topic_scope_topic' => 'Solo el tema',
    'topic_scope_articles' => 'Solo artículos del tema',
    'topic_scope_help' => 'Para una asociación con un tema, elija si las FAQ aparecen en la página del tema, son heredadas por sus artículos o ambas cosas.',
    'display_help_title' => 'Cómo funciona la visualización contextual de FAQ',
    'display_help_intro' => 'Las asociaciones pueden mostrarse automáticamente o colocarse manualmente dentro del contenido.',
    'display_help_automatic' => 'La FAQ se muestra en el punto de inserción nativo admitido por el proveedor seleccionado.',
    'display_help_manual' => 'No hay salida automática. Inserte esta etiqueta automática donde deba aparecer la FAQ asociada:',
    'display_help_related' => 'Muestra explícitamente las asociaciones para un proveedor y un ID de contenido.',
    'display_help_embed' => 'Inserta una FAQ concreta independientemente de las asociaciones.',
    'display_help_context_note' => '[faq-context] usa el proveedor y el ID del contenido actual. En los artículos también aplica la herencia de temas y la protección frente a duplicados/FAQ externas.'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'Cobertura de FAQ',
    'intro' => 'Revise qué contenido usa FAQ gestionadas, FAQ heredadas de temas o FAQ ya presentes en el contenido. Use los filtros para encontrar contenido sin FAQ, señales de FAQ externas o asociaciones que deban revisarse.',
    'provider' => 'Proveedor',
    'faq_status' => 'Estado de FAQ',
    'all' => 'Todos',
    'managed_faq_only' => 'Solo FAQ gestionada',
    'external_faq_only' => 'Solo señal de FAQ externa',
    'managed_external' => 'Gestionada + externa',
    'no_faq_detected' => 'No se detectó ninguna FAQ',
    'show' => 'Mostrar',
    'provider_not_enumerable' => 'Este proveedor no expone una colección enumerable de Item Info en esta instalación. FAQ no consultará las tablas de plugins de terceros. Las asociaciones manuales siguen disponibles.',
    'core_audit_label' => 'Auditoría de contenido del núcleo:',
    'core_audit_help' => 'Los artículos y las páginas estáticas se inspeccionan en modo de solo lectura porque sus proveedores actuales de Geeklog no exponen el contrato de colección/contenido necesario. La detección de FAQ externas es una señal editorial, no una prueba.',
    'managed' => 'Gestionada',
    'external_signal' => 'Señal externa',
    'none' => 'Ninguna',
    'manage' => 'Gestionar',
    'edit' => 'Editar',
    'managed_relations' => 'Relaciones gestionadas:',
    'managed_only' => 'Solo gestionada',
    'external_only' => 'Solo externa',
    'both' => 'Ambas',
    'content' => 'Contenido',
    'external_signals' => 'Señales externas',
    'action' => 'Acción',
    'articles' => 'Artículos',
    'topics' => 'Temas',
    'origin_direct' => 'Asociación directa',
    'origin_topic' => 'Heredada del tema',
    'topic_inheritance_blocked' => 'Herencia del tema bloqueada por una FAQ externa',
    'static_pages' => 'Páginas estáticas',
    'videos' => 'Vídeos',
    'documents' => 'Documentos',
    'maps' => 'Mapas',
    'media_gallery' => 'Galería multimedia',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'Microdatos FAQPage',
    'signal_question_answer_schema' => 'Esquema Question/Answer',
    'signal_details_summary' => 'Preguntas y respuestas repetidas con details/summary',
    'signal_faq_heading' => 'Encabezado de FAQ'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'Importación del plugin FAQ',
    'no_topics' => 'No hay temas de FAQMAN para importar.',
    'no_faqman' => 'El plugin FAQMAN no está instalado.',
    'not_root' => 'Debe pertenecer al grupo ROOT para importar temas de FAQMAN.',
    'unknown' => 'Acción de importación desconocida: ',
    'imported' => '%d FAQ importadas en %d categorías'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'Configuración de FAQ'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => 'Ocultar las FAQ de Novedades',
    'hidefaqmenu' => 'Ocultar la opción FAQ del menú',
    'newfaqinterval' => 'Intervalo de FAQ nuevas (segundos)',
    'no_hit_rights' => 'Permisos excluidos del recuento de visitas',
    'contextual_enabled' => 'Activar FAQ contextuales',
    'contextual_default_placement' => 'Ubicación contextual predeterminada',
    'structured_data' => 'Activar datos estructurados de FAQ',
    'coverage_limit' => 'Límite de resultados de la página Cobertura',
    'default_permissions' => 'Permisos predeterminados'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'Configuración de FAQ'
);

$LANG_tab['faq'] = array(
    'tab_general' => 'General',
    'tab_context' => 'FAQ contextuales',
    'tab_permissions' => 'Permisos'
);

$LANG_fs['faq'] = array(
    'fs_general' => 'Configuración general',
    'fs_context' => 'Configuración de FAQ contextuales',
    'fs_permissions' => 'Permisos predeterminados'
);

$LANG_configselects['faq'][0] = array(
    'Verdadero' => 1,
    'Falso' => 0
);

$LANG_configselects['faq'][1] = array(
    'Automática (punto de visualización del elemento del proveedor)' => 'automatic',
    'Solo manual' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    'Sin acceso' => 0,
    'Solo lectura' => 2,
    'Lectura y escritura' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => 'Oculta las entradas de FAQ del bloque Novedades de Geeklog.',
    'hidefaqmenu' => 'Oculta la opción FAQ del menú del sitio, manteniendo disponibles las URL públicas de FAQ.',
    'newfaqinterval' => 'Número de segundos durante los que una FAQ actualizada recientemente se considera nueva.',
    'no_hit_rights' => 'Lista separada por comas de permisos de FAQ cuyos usuarios no deben incrementar los contadores de visitas.',
    'contextual_enabled' => 'Permite mostrar asociaciones de FAQ dentro de proveedores de contenido compatibles, como artículos, Documentos, Mapas y Vídeos.',
    'contextual_default_placement' => 'Automática permite que el proveedor de contenido elija su punto nativo de visualización. Solo manual desactiva la salida contextual automática.',
    'structured_data' => 'Genera datos estructurados FAQPage en páginas independientes de FAQ cuando corresponda.',
    'coverage_limit' => 'Número máximo de elementos del proveedor inspeccionados y mostrados en la página de administración Cobertura.',
    'default_permissions' => 'Permisos predeterminados de propietario, grupo, miembros y usuarios anónimos asignados al nuevo contenido de FAQ.'
);
