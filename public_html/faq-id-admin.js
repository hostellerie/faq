(function () {
    'use strict';

    function slugify(value) {
        value = String(value || '').toLowerCase();
        if (value.normalize) {
            value = value.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }
        value = value.replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        return value.substring(0, 40).replace(/-+$/g, '');
    }

    function init() {
        var title = document.getElementById('faq-editor-title');
        var id = document.getElementById('faq-editor-id');
        var confirm = document.getElementById('faq-confirm-id-change');

        if (!title || !id) {
            return;
        }

        if (id.getAttribute('data-auto-id') === '1') {
            var automatic = true;

            title.addEventListener('input', function () {
                if (automatic) {
                    id.value = slugify(title.value);
                }
            });

            id.addEventListener('input', function () {
                automatic = false;
                id.value = slugify(id.value);
            });

            if (id.value === '') {
                id.value = slugify(title.value);
            }
        }

        if (confirm) {
            confirm.addEventListener('change', function () {
                id.readOnly = !confirm.checked;
                if (confirm.checked) {
                    id.focus();
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}());
