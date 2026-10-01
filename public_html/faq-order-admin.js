(function () {
    'use strict';

    function init() {
        var category = document.getElementById('faq-editor-category');
        var position = document.getElementById('faq-editor-position');
        var templates;

        if (!category || !position) {
            return;
        }

        templates = Array.prototype.map.call(position.options, function (option) {
            return {
                value: option.value,
                text: option.textContent,
                category: option.getAttribute('data-category') || '',
                selected: option.selected
            };
        });

        function rebuild(resetToLast) {
            var selectedCategory = category.value;
            var currentValue = resetToLast ? 'last' : position.value;
            var selectedFound = false;

            position.innerHTML = '';

            templates.forEach(function (item) {
                var option;

                if (item.category !== '' && item.category !== selectedCategory) {
                    return;
                }

                option = document.createElement('option');
                option.value = item.value;
                option.textContent = item.text;

                if (item.category !== '') {
                    option.setAttribute('data-category', item.category);
                }

                if (item.value === currentValue) {
                    option.selected = true;
                    selectedFound = true;
                }

                position.appendChild(option);
            });

            if (!selectedFound) {
                position.value = 'last';
            }
        }

        category.addEventListener('change', function () {
            rebuild(true);
        });

        rebuild(false);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}());
