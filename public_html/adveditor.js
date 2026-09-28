(function () {
    'use strict';

    var FAQEditor = {
        plainId: 'faq_desc_source',
        advancedId: 'faq_desc_advanced',
        dirtyId: 'faq_desc_edited',
        suppressDirty: true
    };

    function get(id) {
        return document.getElementById(id);
    }

    FAQEditor.markDirty = function () {
        if (FAQEditor.suppressDirty) {
            return;
        }
        var field = get(FAQEditor.dirtyId);
        if (field) {
            field.value = '1';
        }
    };

    FAQEditor.syncSourceToVisual = function () {
        var source = get(FAQEditor.plainId);
        if (!source || !window.AdvancedEditor || !AdvancedEditor.api || !AdvancedEditor.api[AdvancedEditor.editor]) {
            return;
        }
        try {
            AdvancedEditor.api[AdvancedEditor.editor].setContent(FAQEditor.advancedId, source.value);
        } catch (e) {
            // The visual editor may not be ready yet. Source HTML remains authoritative.
        }
    };

    FAQEditor.bindCkeditorDirty = function () {
        if (!window.CKEDITOR) {
            return;
        }

        CKEDITOR.on('instanceReady', function (event) {
            if (!event.editor || event.editor.name !== FAQEditor.advancedId) {
                return;
            }

            event.editor.resetDirty();
            window.setTimeout(function () {
                FAQEditor.suppressDirty = false;
                event.editor.on('change', FAQEditor.markDirty);
            }, 0);
        });
    };

    FAQEditor.bindSourceDirty = function () {
        var source = get(FAQEditor.plainId);
        if (!source) {
            return;
        }

        source.addEventListener('input', function () {
            FAQEditor.suppressDirty = false;
            FAQEditor.markDirty();

            if (window.AdvancedEditor && AdvancedEditor.isAdvancedMode && AdvancedEditor.isAdvancedMode()) {
                FAQEditor.suppressDirty = true;
                FAQEditor.syncSourceToVisual();
                window.setTimeout(function () {
                    FAQEditor.suppressDirty = false;
                    if (window.CKEDITOR && CKEDITOR.instances[FAQEditor.advancedId]) {
                        CKEDITOR.instances[FAQEditor.advancedId].resetDirty();
                    }
                }, 0);
            }
        });
    };

    FAQEditor.init = function () {
        if (!window.AdvancedEditor) {
            FAQEditor.suppressDirty = false;
            FAQEditor.bindSourceDirty();
            return;
        }

        FAQEditor.bindCkeditorDirty();

        AdvancedEditor.onchange_editmode = function () {
            var advanced = AdvancedEditor.isAdvancedMode();
            var visual = get('faq_advanced_editarea');
            var source = get('faq_html_editarea');
            var toolbar = get('faq_editor_toolbar');

            if (visual) {
                visual.style.display = advanced ? '' : 'none';
            }
            if (source) {
                source.style.display = advanced ? 'none' : '';
            }
            if (toolbar) {
                toolbar.style.display = advanced ? '' : 'none';
            }

            FAQEditor.suppressDirty = true;
            AdvancedEditor.swapEditorContent();
            window.setTimeout(function () {
                FAQEditor.suppressDirty = false;
                if (window.CKEDITOR && CKEDITOR.instances[FAQEditor.advancedId]) {
                    CKEDITOR.instances[FAQEditor.advancedId].resetDirty();
                }
            }, 0);
        };

        if (window.CKEDITOR) {
            CKEDITOR.config.enterMode = CKEDITOR.ENTER_P;
            CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
        }

        AdvancedEditor.newEditor({
            TextareaId: [
                {plain: FAQEditor.plainId, advanced: FAQEditor.advancedId}
            ],
            toolbar: 1
        });

        AdvancedEditor.set_postcontent = function () {
            var dirty = get(FAQEditor.dirtyId);
            if (!dirty || dirty.value !== '1') {
                return;
            }
            if (AdvancedEditor.isAdvancedMode()) {
                get(FAQEditor.plainId).value =
                    AdvancedEditor.api[AdvancedEditor.editor].getContent(FAQEditor.advancedId);
            }
        };

        FAQEditor.bindSourceDirty();
        window.setTimeout(function () {
            FAQEditor.suppressDirty = false;
        }, 0);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', FAQEditor.init);
    } else {
        FAQEditor.init();
    }
}());


window.faqEditorPrepareSubmit = function () {
    if (window.AdvancedEditor && typeof AdvancedEditor.set_postcontent === 'function') {
        AdvancedEditor.set_postcontent();
    }
    return true;
};
