(function () {
    'use strict';

    function byId(id) {
        return document.getElementById(id);
    }

    function setManual(input, wrapper, enabled) {
        if (wrapper) {
            wrapper.style.display = enabled ? '' : 'none';
        }
        if (input) {
            input.required = enabled;
            if (!enabled) {
                input.value = '';
            }
        }
    }

    function resetItems(select, note, text) {
        if (!select) {
            return;
        }
        select.innerHTML = '';
        var option = document.createElement('option');
        option.value = '';
        option.textContent = text || 'Select content';
        select.appendChild(option);
        select.disabled = true;
        if (note) {
            note.textContent = '';
        }
    }

    function init() {
        var targetType = byId('faq-relation-target-type');
        var faqSelect = byId('faq-relation-faq');
        var categorySelect = byId('faq-relation-category');
        var faqWrap = byId('faq-relation-faq-wrap');
        var categoryWrap = byId('faq-relation-category-wrap');
        var provider = byId('faq-relation-provider');
        var itemSelect = byId('faq-relation-item');
        var itemManual = byId('faq-relation-item-manual');
        var itemManualWrap = byId('faq-relation-item-manual-wrap');
        var itemWrap = byId('faq-relation-item-wrap');
        var subtype = byId('faq-relation-subtype');
        var subtypeManual = byId('faq-relation-subtype-manual');
        var note = byId('faq-relation-note');
        var endpoint;
        var selectedItem;
        var messages;

        if (!provider || !itemSelect) {
            return;
        }

        endpoint = provider.getAttribute('data-items-url') || 'relations.php';
        selectedItem = itemSelect.getAttribute('data-selected-item') || '';
        messages = {
            selectContent: provider.getAttribute('data-msg-select-content') || 'Select content',
            loading: provider.getAttribute('data-msg-loading') || 'Loading…',
            selectProviderFirst: provider.getAttribute('data-msg-select-provider-first') || 'Select a provider first',
            noContent: provider.getAttribute('data-msg-no-content') || 'No selectable content',
            enterManual: provider.getAttribute('data-msg-enter-manual') || 'Enter ID manually…',
            collectionUnavailable: provider.getAttribute('data-msg-collection-unavailable') || 'Collection unavailable; enter the content ID manually.',
            loadFailed: provider.getAttribute('data-msg-load-failed') || 'Unable to load the provider collection; enter the content ID manually.',
            manualFallback: provider.getAttribute('data-msg-manual-fallback') || 'Manual fallback: enter the content ID. Subtype remains optional.'
        };

        function updateTargetSource() {
            var categoryMode = targetType && targetType.value === 'category';

            if (faqWrap) {
                faqWrap.style.display = categoryMode ? 'none' : '';
            }
            if (categoryWrap) {
                categoryWrap.style.display = categoryMode ? '' : 'none';
            }
            if (faqSelect) {
                faqSelect.required = !categoryMode;
            }
            if (categorySelect) {
                categorySelect.required = categoryMode;
            }
        }

        if (targetType) {
            targetType.addEventListener('change', updateTargetSource);
            updateTargetSource();
        }

        function showManual(message) {
            if (itemWrap) {
                itemWrap.style.display = 'none';
            }
            setManual(itemManual, itemManualWrap, true);
            if (note) {
                note.textContent = message || '';
            }
            if (subtype) {
                subtype.value = '';
            }
        }

        function loadItems() {
            var type = provider.value;

            resetItems(itemSelect, note, messages.loading);
            setManual(itemManual, itemManualWrap, false);
            if (itemWrap) {
                itemWrap.style.display = '';
            }
            if (subtype) {
                subtype.value = '';
            }
            if (subtypeManual) {
                subtypeManual.value = '';
            }

            if (!type) {
                resetItems(itemSelect, note, messages.selectProviderFirst);
                return;
            }

            fetch(endpoint + '?faq_ajax=items&provider=' + encodeURIComponent(type), {
                credentials: 'same-origin'
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }
                    return response.json();
                })
                .then(function (data) {
                    var first;
                    var manual;
                    var i;
                    var row;
                    var option;

                    itemSelect.innerHTML = '';

                    if (!data || !data.supported) {
                        showManual(data && data.message ? data.message : messages.collectionUnavailable);
                        return;
                    }

                    if (itemWrap) {
                        itemWrap.style.display = '';
                    }

                    first = document.createElement('option');
                    first.value = '';
                    first.textContent = data.items && data.items.length ? messages.selectContent : messages.noContent;
                    itemSelect.appendChild(first);

                    if (data.items) {
                        for (i = 0; i < data.items.length; i += 1) {
                            row = data.items[i];
                            option = document.createElement('option');
                            option.value = row.id;
                            option.textContent = (row.title || row.id) + ' [' + row.id + ']';
                            option.setAttribute('data-subtype', row.subtype || '');
                            itemSelect.appendChild(option);
                        }
                    }

                    manual = document.createElement('option');
                    manual.value = '__manual__';
                    manual.textContent = messages.enterManual;
                    itemSelect.appendChild(manual);
                    itemSelect.disabled = false;

                    if (selectedItem) {
                        for (i = 0; i < itemSelect.options.length; i += 1) {
                            if (itemSelect.options[i].value === selectedItem) {
                                itemSelect.selectedIndex = i;
                                subtype.value = itemSelect.options[i].getAttribute('data-subtype') || '';
                                selectedItem = '';
                                break;
                            }
                        }
                    }

                    if (note) {
                        note.textContent = data.message || '';
                    }
                })
                .catch(function () {
                    showManual(messages.loadFailed);
                });
        }

        provider.addEventListener('change', loadItems);

        itemSelect.addEventListener('change', function () {
            var manual = itemSelect.value === '__manual__';
            var selected = itemSelect.options[itemSelect.selectedIndex];
            var detectedSubtype = selected ? selected.getAttribute('data-subtype') : '';

            setManual(itemManual, itemManualWrap, manual);

            if (subtype) {
                subtype.value = manual ? '' : (detectedSubtype || '');
            }

            if (subtypeManual) {
                subtypeManual.value = '';
            }

            if (note && manual) {
                note.textContent = messages.manualFallback;
            }

            if (manual && itemManual) {
                itemManual.focus();
            }
        });

        loadItems();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}());
