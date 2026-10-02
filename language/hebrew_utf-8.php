<?php

$LANG_FAQ_COMMON = array(
    'FAQs' => 'שאלות נפוצות',
    'FAQ' => 'FAQ',
    'no_new' => 'אין שאלות נפוצות חדשות לאחרונה',
    'FAQ_cat_header' => $_CONF['site_name'] . ' FAQ',
    'Categories' => 'קטגוריות',
    'Question' => 'שאלה',
    'Hits' => 'צפיות',
    'Updated' => 'עודכן',
    'Answer' => 'תשובה',
    'permalink' => 'קישור קבוע',
    'back_to' => 'חזרה אל',
    'breadcrumb_aria' => 'פירורי לחם של FAQ',
    'actions_aria' => 'פעולות FAQ',
    'related_questions' => 'שאלות קשורות',
    'category_page_title' => 'קטגוריית FAQ: %s',
    'no_autolink_faq' => '[FAQ עם מזהה "%s" אינה קיימת או שאין לך גישה אליה]',
    'no_autolink_cat' => '[קטגוריית FAQ "%s" אינה קיימת או שאין לך גישה אליה]',
    'autolink_error' => '[שגיאה בקישור FAQ "%s"]'
);

$LANG_FAQ_STATS = array(
    'stats_no_hits' => 'נראה שאין שאלות נפוצות באתר זה, או שאף אחת מהן עדיין לא נצפתה.',
    'stats_summary' => 'קטגוריות/פריטי FAQ (צפיות) במערכת',
    'Question' => 'שאלה',
    'Hits' => 'צפיות',
    'headline' => 'עשר השאלות הנפוצות המובילות'
);

$LANG_FAQ_SEARCH = array(
    'FAQ' => 'FAQ',
    'results' => 'תוצאות FAQ',
    'title' => 'שאלה',
    'date' => 'עודכן',
    'author' => 'מחבר',
    'category' => 'קטגוריה',
    'hits' => 'צפיות'
);

$PLG_faq_MESSAGE1 = 'ניסית לגשת לקטגוריית FAQ שאין לך הרשאה אליה או שאינה קיימת. הניסיון תועד.';
$PLG_faq_MESSAGE2 = 'ניסית לגשת לפריט FAQ שאין לך הרשאה אליו או שאינו קיים. הניסיון תועד.';
$PLG_faq_MESSAGE3 = 'ה-FAQ נמחקה בהצלחה.';
$PLG_faq_MESSAGE4 = 'ה-FAQ נשמרה בהצלחה.';
$PLG_faq_MESSAGE5 = 'ניסית לבצע פעולה על פריט FAQ שאין לך הרשאה אליו או שאינו קיים. הניסיון תועד.';
$PLG_faq_MESSAGE6 = 'שדרוג תוסף FAQ הושלם בהצלחה.';
$PLG_faq_MESSAGE7 = 'שדרוג תוסף FAQ נכשל.';

$LANG_FAQ_ADMIN = array(
    'FAQ_Cat' => 'קטגוריית FAQ',
    'FAQ_Entry' => 'פריט FAQ',
    'FAQ Entries' => 'פריטי FAQ',
    'Edit' => 'עריכה',
    'FAQ Editor' => 'עורך FAQ',
    'Cat Editor' => 'עורך קטגוריות FAQ',
    'Access Denied MSG' => 'אין לך גישה לדף ניהול ה-FAQ. כל ניסיון לגשת לפונקציות ללא הרשאה מתועד.',
    'delete' => 'מחיקה',
    'save' => 'שמירה',
    'cancel' => 'ביטול',
    'show' => 'הצגה',
    'accessdenied' => "ניסית לגשת לפריט FAQ שאין לך הרשאה אליו. הניסיון תועד. <a href=\"{$_CONF['site_admin_url']}/plugins/faq/index.php\">חזרה למסך ניהול ה-FAQ</a>.",
    'title' => 'כותרת',
    'description' => 'תיאור',
    'question' => 'שאלה',
    'answer' => 'תשובה',
    'id' => 'ID',
    'id_auto_help' => 'המזהה נוצר אוטומטית מהכותרת כדי ליצור כתובת URL קריאה. אפשר לשנות אותו לפני השמירה הראשונה.',
    'id_change_help' => 'מזהה זה הוא חלק מכתובת ה-URL הציבורית. שינויו עשוי להשפיע על אינדוקס במנועי חיפוש ועל קישורים קיימים.',
    'id_change_confirm' => 'אני מאשר/ת שברצוני לשנות את המזהה ואת כתובת ה-URL הציבורית.',
    'id_change_required' => 'שינוי המזהה דורש אישור מפורש.',
    'id_exists' => 'מזהה זה כבר נמצא בשימוש. יש לבחור מזהה אחר.',
    'hits' => 'צפיות',
    'order' => 'סדר',
    'order_help' => 'קובע את מיקום ה-FAQ בתוך הקטגוריה. ערכים נמוכים מוצגים תחילה; ערכים כמו 10, 20 ו-30 משאירים מקום להוספות עתידיות.',
    'position' => 'מיקום',
    'position_first' => 'ראשון',
    'position_last' => 'אחרון',
    'position_after' => 'אחרי: %s',
    'position_help' => 'יש לבחור את מיקום ה-FAQ בתוך הקטגוריה. הסדר הפנימי מחושב מחדש אוטומטית במרווחים של 10.',
    'changed' => 'עודכן',
    'category' => 'קטגוריה',
    'all_cat' => 'כל הקטגוריות',
    'date_will_update' => '<b>הערה:</b> התאריך יתעדכן בעת השמירה!',
    'reset_date' => 'עדכון תאריך השינוי ל<i>עכשיו</i>.',
    'save_rights_error' => 'לא ניתן לשמור עם הרשאות שאינן ברשותך.',
    'missing_fields_faq' => 'יש להזין שאלה, תשובה וקטגוריה לכל פריט FAQ.',
    'missing_fields_cat' => 'יש להזין כותרת ותיאור לכל קטגוריית FAQ.',
    'delete_note' => 'הערה: מחיקת קטגוריה זו תמחק את כל השאלות הנפוצות המשויכות אליה.',
    'FAQ Plugin' => 'תוסף FAQ',
    'faqman_import' => 'התוסף FAQMAN מותקן (זה אינו אותו תוסף). ניתן לייבא נתונים מ-FAQMAN.',
    'cat instructions' => 'כאן ניתן ליצור ולנהל קטגוריות FAQ. בלשונית שאלות ניתן לנהל את השאלות המשויכות לכל קטגוריה.',
    'faq instructions' => 'כאן ניתן ליצור ולנהל שאלות ותשובות FAQ. מסנן הקטגוריות מאפשר למצוא במהירות FAQ לעריכה.',
    'import' => 'ייבוא',
    'access' => 'גישה',
    'editor_mode' => 'מצב עורך',
    'visual_editor' => 'עורך חזותי',
    'html_source' => 'מקור HTML',
    'insert_media' => 'הוספת מדיה',
    'questions' => 'שאלות',
    'categories' => 'קטגוריות',
    'associations' => 'שיוכים',
    'coverage' => 'כיסוי',
    'configuration' => 'הגדרות',
    'new_question' => 'שאלה חדשה',
    'new_category' => 'קטגוריה חדשה',
    'administration_aria' => 'ניהול FAQ'
);

$LANG_FAQ_RELATIONS = array(
    'title' => 'שיוכי FAQ',
    'intro' => 'ניתן לשייך FAQ יחידה או קטגוריית FAQ שלמה לתוכן שמספקים ספקי Geeklog. שיוך קטגוריה הוא דינמי: FAQ חדשות שמתווספות לקטגוריה נכללות אוטומטית. ספקים ותוכן שניתן לבחור מזוהים אוטומטית כשאפשר; הזנת מזהה ידנית נשארת אפשרות חלופית.',
    'add_association' => 'הוספת שיוך',
    'association_source' => 'מקור השיוך',
    'individual_faq' => 'FAQ יחידה',
    'whole_category' => 'קטגוריה שלמה',
    'faq' => 'FAQ',
    'faq_category' => 'קטגוריית FAQ',
    'select_category' => 'בחירת קטגוריה',
    'provider' => 'ספק',
    'select_provider' => 'בחירת ספק',
    'content' => 'תוכן',
    'select_provider_first' => 'יש לבחור ספק תחילה',
    'content_id' => 'מזהה תוכן',
    'placement' => 'מיקום',
    'automatic' => 'אוטומטי',
    'manual_only' => 'ידני בלבד',
    'order' => 'סדר',
    'advanced_fallback' => 'חלופה מתקדמת',
    'subtype' => 'תת-סוג',
    'subtype_help' => 'תת-הסוג מזוהה בדרך כלל מפריט הספק שנבחר. יש להזין אותו ידנית רק עבור ספק ישן או מותאם אישית שאינו חושף אותו.',
    'save_association' => 'שמירת שיוך',
    'current_faq_associations' => 'שיוכי FAQ יחידות נוכחיים',
    'current_category_associations' => 'שיוכי קטגוריות נוכחיים',
    'filtered_content' => 'תוכן מסונן:',
    'show_all_associations' => 'הצגת כל השיוכים',
    'relation_table_missing' => 'טבלת הקשרים של FAQ 1.3.0 עדיין לא מותקנת. יש להריץ את שדרוג התוסף.',
    'category_relation_table_missing' => 'טבלת הקשרים של קטגוריות FAQ עדיין לא מותקנת.',
    'category' => 'קטגוריה',
    'action' => 'פעולה',
    'delete' => 'מחיקה',
    'category_association_saved' => 'שיוך הקטגוריה נשמר. FAQ חדשות שיתווספו לקטגוריה זו ייכללו אוטומטית.',
    'association_saved' => 'השיוך נשמר.',
    'association_save_failed' => 'לא ניתן לשמור את השיוך. יש לבדוק את ה-FAQ/קטגוריה והתוכן שנבחרו.',
    'association_deleted' => 'השיוך נמחק.',
    'update' => 'עדכון',
    'association_updated' => 'השיוך עודכן.',
    'association_update_failed' => 'לא ניתן לעדכן את השיוך.',
    'select_content' => 'בחירת תוכן',
    'loading' => 'טוען…',
    'no_selectable_content' => 'אין תוכן שניתן לבחור',
    'enter_id_manually' => 'הזנת מזהה ידנית…',
    'collection_unavailable' => 'האוסף אינו זמין; יש להזין את מזהה התוכן ידנית.',
    'unable_load_collection' => 'לא ניתן לטעון את אוסף הספק; יש להזין את מזהה התוכן ידנית.',
    'manual_fallback' => 'חלופה ידנית: יש להזין את מזהה התוכן. תת-הסוג נשאר אופציונלי.',
    'provider_no_item_info' => 'ספק זה אינו חושף Geeklog Item Info.',
    'provider_no_content' => 'הספק לא החזיר תוכן שניתן לבחור.',
    'provider_selectable_count' => '%d פריטים ניתנים לבחירה.',
    'external_confirmation_required' => 'מאמר זה כבר מכיל אות FAQ/Q&A חיצוני. יש לאשר במפורש את השיוך לפני השמירה.',
    'confirm_external_faq' => 'אם המאמר שנבחר כבר מכיל FAQ/Q&A חיצוני, אני מאשר/ת שיש להוסיף בכל זאת את השיוך המנוהל הזה.',
    'topic_provider' => 'נושא Geeklog',
    'topic_scope' => 'טווח הנושא',
    'topic_scope_both' => 'נושא + מאמרי הנושא',
    'topic_scope_topic' => 'נושא בלבד',
    'topic_scope_articles' => 'מאמרי הנושא בלבד',
    'topic_scope_help' => 'בשיוך לנושא יש לבחור אם FAQ יוצגו בדף הנושא, יועברו בירושה למאמרים שלו או בשני המקומות.',
    'display_help_title' => 'כיצד פועלת תצוגת FAQ הקשרית',
    'display_help_intro' => 'ניתן להציג שיוכים אוטומטית או למקם אותם ידנית בתוך התוכן.',
    'display_help_automatic' => 'ה-FAQ מוצגת בנקודת ההוספה המקורית שבה תומך הספק שנבחר.',
    'display_help_manual' => 'אין פלט אוטומטי. יש להוסיף את התג האוטומטי הזה במקום שבו ה-FAQ המשויכת אמורה להופיע:',
    'display_help_related' => 'מציג במפורש שיוכים עבור ספק ומזהה תוכן.',
    'display_help_embed' => 'מטמיע FAQ מסוימת ללא תלות בשיוכים.',
    'display_help_context_note' => '[faq-context] משתמש בספק התוכן ובמזהה הנוכחיים. במאמרים הוא גם מחיל ירושה מנושאים והגנה מפני כפילויות/FAQ חיצוניות.'
);

$LANG_FAQ_COVERAGE = array(
    'title' => 'כיסוי FAQ',
    'intro' => 'סקירה של תוכן המשתמש ב-FAQ מנוהלות, FAQ שיורשו מנושאים או FAQ שכבר קיימות בתוכן. המסננים מאפשרים למצוא תוכן ללא FAQ, אותות FAQ חיצוניים או שיוכים שדורשים בדיקה.',
    'provider' => 'ספק',
    'faq_status' => 'מצב FAQ',
    'all' => 'הכול',
    'managed_faq_only' => 'FAQ מנוהלת בלבד',
    'external_faq_only' => 'אות FAQ חיצוני בלבד',
    'managed_external' => 'מנוהלת + חיצונית',
    'no_faq_detected' => 'לא זוהתה FAQ',
    'show' => 'הצגה',
    'provider_not_enumerable' => 'ספק זה אינו חושף אוסף Item Info שניתן למנות בהתקנה זו. FAQ לא תבצע שאילתות ישירות בטבלאות של תוספים חיצוניים. שיוכים ידניים עדיין זמינים.',
    'core_audit_label' => 'בדיקת תוכן ליבה:',
    'core_audit_help' => 'מאמרים ודפים סטטיים נבדקים לקריאה בלבד מפני שהספקים הנוכחיים שלהם ב-Geeklog אינם חושפים את חוזה האוסף/תוכן הנדרש. זיהוי FAQ חיצונית הוא אות עריכתי ולא הוכחה.',
    'managed' => 'מנוהלת',
    'external_signal' => 'אות חיצוני',
    'none' => 'ללא',
    'manage' => 'ניהול',
    'edit' => 'עריכה',
    'managed_relations' => 'קשרים מנוהלים:',
    'managed_only' => 'מנוהלת בלבד',
    'external_only' => 'חיצונית בלבד',
    'both' => 'שתיהן',
    'content' => 'תוכן',
    'external_signals' => 'אותות חיצוניים',
    'action' => 'פעולה',
    'articles' => 'מאמרים',
    'topics' => 'נושאים',
    'origin_direct' => 'שיוך ישיר',
    'origin_topic' => 'ירושה מהנושא',
    'topic_inheritance_blocked' => 'ירושה מהנושא נחסמה על ידי FAQ חיצונית',
    'static_pages' => 'דפים סטטיים',
    'videos' => 'סרטונים',
    'documents' => 'מסמכים',
    'maps' => 'מפות',
    'media_gallery' => 'גלריית מדיה',
    'signal_faqpage_jsonld' => 'FAQPage JSON-LD',
    'signal_faqpage_microdata' => 'מיקרו-נתונים FAQPage',
    'signal_question_answer_schema' => 'סכמת Question/Answer',
    'signal_details_summary' => 'שאלות ותשובות חוזרות באמצעות details/summary',
    'signal_faq_heading' => 'כותרת FAQ'
);

$LANG_FAQ_IMPORT = array(
    'header' => 'ייבוא תוסף FAQ',
    'no_topics' => 'אין נושאי FAQMAN לייבוא.',
    'no_faqman' => 'התוסף FAQMAN אינו מותקן.',
    'not_root' => 'יש להיות חבר/ה בקבוצת ROOT כדי לייבא נושאי FAQMAN.',
    'unknown' => 'פעולת ייבוא לא מוכרת: ',
    'imported' => '%d FAQ יובאו אל %d קטגוריות'
);

$LANG_configsections['faq'] = array(
    'label' => 'FAQ',
    'title' => 'הגדרות FAQ'
);

$LANG_confignames['faq'] = array(
    'hidenewfaq' => 'הסתרת FAQ ממה חדש',
    'hidefaqmenu' => 'הסתרת פריט FAQ מהתפריט',
    'newfaqinterval' => 'מרווח FAQ חדשות (שניות)',
    'no_hit_rights' => 'הרשאות שאינן נספרות בצפיות',
    'contextual_enabled' => 'הפעלת FAQ הקשריות',
    'contextual_default_placement' => 'מיקום ברירת מחדל ל-FAQ הקשריות',
    'structured_data' => 'הפעלת נתונים מובנים ל-FAQ',
    'coverage_limit' => 'מגבלת תוצאות בדף הכיסוי',
    'default_permissions' => 'הרשאות ברירת מחדל'
);

$LANG_configsubgroups['faq'] = array(
    'sg_main' => 'הגדרות FAQ'
);

$LANG_tab['faq'] = array(
    'tab_general' => 'כללי',
    'tab_context' => 'FAQ הקשריות',
    'tab_permissions' => 'הרשאות'
);

$LANG_fs['faq'] = array(
    'fs_general' => 'הגדרות כלליות',
    'fs_context' => 'הגדרות FAQ הקשריות',
    'fs_permissions' => 'הרשאות ברירת מחדל'
);

$LANG_configselects['faq'][0] = array(
    'כן' => 1,
    'לא' => 0
);

$LANG_configselects['faq'][1] = array(
    'אוטומטי (נקודת תצוגת פריט הספק)' => 'automatic',
    'ידני בלבד' => 'manual'
);

$LANG_configselects['faq'][12] = array(
    'ללא גישה' => 0,
    'קריאה בלבד' => 2,
    'קריאה-כתיבה' => 3
);

$LANG_configtooltips['faq'] = array(
    'hidenewfaq' => 'הסתרת פריטי FAQ מהבלוק "מה חדש" של Geeklog.',
    'hidefaqmenu' => 'הסתרת פריט FAQ מתפריט האתר תוך השארת כתובות ה-URL הציבוריות של FAQ זמינות.',
    'newfaqinterval' => 'מספר השניות שבהן FAQ שעודכנה לאחרונה נחשבת חדשה.',
    'no_hit_rights' => 'רשימת הרשאות FAQ מופרדת בפסיקים, שמשתמשים בעלי הרשאות אלה לא יגדילו את מוני הצפיות.',
    'contextual_enabled' => 'מאפשר להציג שיוכי FAQ בתוך ספקי תוכן נתמכים כגון מאמרים, מסמכים, מפות וסרטונים.',
    'contextual_default_placement' => 'מצב אוטומטי מאפשר לספק התוכן לבחור את נקודת התצוגה המקורית שלו. ידני בלבד מבטל פלט הקשרי אוטומטי.',
    'structured_data' => 'פלט נתונים מובנים מסוג FAQPage בדפי FAQ עצמאיים כאשר הדבר מתאים.',
    'coverage_limit' => 'המספר המרבי של פריטי ספק שנבדקים ומוצגים בדף ניהול הכיסוי.',
    'default_permissions' => 'הרשאות ברירת המחדל לבעלים, לקבוצה, לחברים ולמשתמשים אנונימיים שמוקצות לתוכן FAQ חדש.'
);
