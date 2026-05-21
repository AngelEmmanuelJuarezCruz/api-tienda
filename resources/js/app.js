import './bootstrap';

const SELECT_ENHANCED_CLASS = 'smart-select-native';

function closeSmartSelect(except = null) {
    document.querySelectorAll('.smart-select.is-open').forEach((select) => {
        if (select !== except) {
            select.classList.remove('is-open');
            const dropdown = select.querySelector('.smart-select-dropdown');
            if (dropdown) {
                dropdown.hidden = true;
            }
        }
    });
}

function normalizeText(value) {
    return value
        .toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function positionDropdown(wrapper, dropdown) {
    const button = wrapper.querySelector('.smart-select-button');
    const rect = button.getBoundingClientRect();
    const margin = 8;
    const maxHeight = Math.min(320, window.innerHeight - (margin * 2));
    const spaceBelow = window.innerHeight - rect.bottom - margin;
    const spaceAbove = rect.top - margin;
    const openUp = spaceBelow < 220 && spaceAbove > spaceBelow;
    const availableHeight = Math.max(160, openUp ? spaceAbove : spaceBelow);

    dropdown.style.left = `${rect.left}px`;
    dropdown.style.width = `${rect.width}px`;
    dropdown.style.maxHeight = `${Math.min(maxHeight, availableHeight)}px`;
    dropdown.style.top = openUp
        ? `${Math.max(margin, rect.top - Math.min(maxHeight, availableHeight) - 6)}px`
        : `${rect.bottom + 6}px`;
}

function enhanceSelect(select) {
    if (select.classList.contains(SELECT_ENHANCED_CLASS) || select.dataset.nativeSelect === 'true') {
        return;
    }

    const wrapper = document.createElement('div');
    wrapper.className = 'smart-select';
    select.parentNode.insertBefore(wrapper, select);
    wrapper.appendChild(select);

    select.classList.add(SELECT_ENHANCED_CLASS);
    select.dataset.smartRequired = select.required ? 'true' : 'false';
    select.required = false;
    select.tabIndex = -1;
    select.setAttribute('aria-hidden', 'true');
    select.style.display = 'none';

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'smart-select-button';
    button.setAttribute('aria-haspopup', 'listbox');
    button.setAttribute('aria-expanded', 'false');

    const label = document.createElement('span');
    label.className = 'smart-select-label';

    const chevron = document.createElement('span');
    chevron.className = 'smart-select-chevron';
    chevron.innerHTML = '&#9662;';

    button.append(label, chevron);

    const dropdown = document.createElement('div');
    dropdown.className = 'smart-select-dropdown';
    dropdown.hidden = true;

    const search = document.createElement('input');
    search.type = 'search';
    search.className = 'smart-select-search';
    search.placeholder = 'Buscar...';
    search.autocomplete = 'off';

    const list = document.createElement('div');
    list.className = 'smart-select-list';
    list.setAttribute('role', 'listbox');

    const empty = document.createElement('div');
    empty.className = 'smart-select-empty';
    empty.textContent = 'Sin resultados';
    empty.hidden = true;

    dropdown.append(search, list, empty);
    wrapper.append(button, dropdown);

    const options = Array.from(select.options).map((option) => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'smart-select-option';
        item.textContent = option.textContent.trim();
        item.dataset.value = option.value;
        item.dataset.search = normalizeText(option.textContent);
        item.setAttribute('role', 'option');

        if (option.disabled) {
            item.disabled = true;
        }

        item.addEventListener('click', () => {
            if (option.disabled) {
                return;
            }

            select.value = option.value;
            select.dispatchEvent(new Event('input', { bubbles: true }));
            select.dispatchEvent(new Event('change', { bubbles: true }));
            updateLabel();
            closeSmartSelect();
            button.focus();
        });

        list.appendChild(item);
        return item;
    });

    function updateLabel() {
        const selected = select.options[select.selectedIndex];
        label.textContent = selected ? selected.textContent.trim() : 'Selecciona una opcion';
        options.forEach((item) => {
            const isSelected = item.dataset.value !== '' && item.dataset.value === select.value;
            item.classList.toggle('is-selected', isSelected);
            item.setAttribute('aria-selected', isSelected ? 'true' : 'false');
        });
    }

    function filterOptions() {
        const term = normalizeText(search.value);
        let visible = 0;

        options.forEach((item) => {
            const matches = item.dataset.search.includes(term);
            item.hidden = !matches;
            if (matches) {
                visible += 1;
            }
        });

        empty.hidden = visible > 0;
    }

    function openDropdown() {
        closeSmartSelect(wrapper);
        wrapper.classList.add('is-open');
        dropdown.hidden = false;
        button.setAttribute('aria-expanded', 'true');
        search.value = '';
        filterOptions();
        positionDropdown(wrapper, dropdown);
        search.focus({ preventScroll: true });
    }

    function closeDropdown() {
        wrapper.classList.remove('is-open');
        dropdown.hidden = true;
        button.setAttribute('aria-expanded', 'false');
    }

    button.addEventListener('click', () => {
        if (wrapper.classList.contains('is-open')) {
            closeDropdown();
            return;
        }

        openDropdown();
    });

    search.addEventListener('input', filterOptions);
    select.addEventListener('change', updateLabel);

    wrapper.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeDropdown();
            button.focus();
        }
    });

    updateLabel();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('select').forEach(enhanceSelect);

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const invalidSelect = Array.from(form.querySelectorAll('select.smart-select-native'))
                .find((select) => select.dataset.smartRequired === 'true' && !select.value);

            if (!invalidSelect) {
                return;
            }

            event.preventDefault();
            const wrapper = invalidSelect.closest('.smart-select');
            const button = wrapper?.querySelector('.smart-select-button');
            if (wrapper && button) {
                button.classList.add('smart-select-invalid');
                button.focus();
                setTimeout(() => button.classList.remove('smart-select-invalid'), 1600);
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.smart-select')) {
            closeSmartSelect();
        }
    });

    window.addEventListener('resize', () => {
        const open = document.querySelector('.smart-select.is-open');
        const dropdown = open?.querySelector('.smart-select-dropdown');
        if (open && dropdown) {
            positionDropdown(open, dropdown);
        }
    });

    window.addEventListener('scroll', () => {
        const open = document.querySelector('.smart-select.is-open');
        const dropdown = open?.querySelector('.smart-select-dropdown');
        if (open && dropdown) {
            positionDropdown(open, dropdown);
        }
    }, true);
});
