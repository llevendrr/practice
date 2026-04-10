import './bootstrap';

const themeStorageKey = 'technodim-theme';
const locale = document.documentElement.lang.startsWith('uk') ? 'uk' : 'en';

const getActiveTheme = () => (document.documentElement.classList.contains('light') ? 'light' : 'dark');

const updateThemeControls = (mode) => {
    const dictionary = {
        uk: {
            light: 'Світла тема',
            dark: 'Темна тема',
            ariaPrefix: 'Поточна тема:',
            ariaAction: 'Натисніть, щоб переключити.',
        },
        en: {
            light: 'Light theme',
            dark: 'Dark theme',
            ariaPrefix: 'Current theme:',
            ariaAction: 'Press to toggle.',
        },
    };
    const labels = dictionary[locale];
    const label = mode === 'light' ? labels.light : labels.dark;
    const icon = mode === 'light' ? '\u2600\uFE0F' : '\uD83C\uDF19';
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.textContent = `${icon} ${label}`;
        button.setAttribute('aria-label', `${labels.ariaPrefix} ${label}. ${labels.ariaAction}`);
    });
};

const applyTheme = (mode) => {
    const html = document.documentElement;
    html.classList.remove('dark', 'light');
    html.classList.add(mode);
    localStorage.setItem(themeStorageKey, mode);
    updateThemeControls(mode);
};

const toggleTheme = () => {
    const nextTheme = getActiveTheme() === 'light' ? 'dark' : 'light';
    applyTheme(nextTheme);
};

const initTheme = () => {
    const stored = localStorage.getItem(themeStorageKey);
    const initialTheme = stored === 'light' ? 'light' : 'dark';
    applyTheme(initialTheme);
};

initTheme();

const escapeHtml = (value = '') =>
    String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#x27;');

const renderInputForField = (field, value) => {
    const name = `spec_values[${field.key}]`;
    const requiredAttr = field.required ? 'required' : '';
    const safeValue = escapeHtml(value ?? '');
    const rawLabel = String(field.label ?? '');
    const label = escapeHtml(rawLabel);
    const lowerLabel = escapeHtml(rawLabel.toLowerCase());
    const textPlaceholder = `Р’РєР°Р¶С–С‚СЊ ${label}`;
    const selectPlaceholder = `Р’РёР±РµСЂС–С‚СЊ ${lowerLabel}`;

    if (field.field_type === 'number') {
        return `
            <div class="field-group">
                <label>${label}${field.required ? ' *' : ''}</label>
                <input
                    type="number"
                    step="any"
                    name="${name}"
                    value="${safeValue}"
                    placeholder="${textPlaceholder}"
                    ${requiredAttr}
                />
            </div>
        `;
    }

    if (field.field_type === 'select') {
        const options = field.options ?? [];
        const optionsMarkup = options
            .map(
                (option) => `
                    <option value="${escapeHtml(option)}" ${option === value ? 'selected' : ''}>
                        ${escapeHtml(option)}
                    </option>
                `
            )
            .join('');

        return `
            <div class="field-group">
                <label>${label}${field.required ? ' *' : ''}</label>
                <select name="${name}" ${requiredAttr}>
                    <option value="">${selectPlaceholder}</option>
                    ${optionsMarkup}
                </select>
            </div>
        `;
    }

    return `
        <div class="field-group">
            <label>${label}${field.required ? ' *' : ''}</label>
            <input
                name="${name}"
                value="${safeValue}"
                placeholder="${textPlaceholder}"
                ${requiredAttr}
            />
        </div>
    `;
};

const formatCardNumber = (value = '') =>
    value
        .replace(/\D/g, '')
        .slice(0, 16)
        .replace(/(.{4})/g, '$1 ')
        .trim();

const formatExpiration = (value = '') => {
    const digits = value.replace(/\D/g, '').slice(0, 4);

    if (digits.length === 0) {
        return '';
    }

    if (digits.length <= 2) {
        return digits;
    }

    return `${digits.slice(0, 2)}/${digits.slice(2)}`;
};

const formatCvv = (value = '') => value.replace(/\D/g, '').slice(0, 4);

const maskCreditField = (input) => {
    const type = input.dataset.creditMask;

    if (!type) {
        return;
    }

    let formatted = input.value;

    if (type === 'card-number') {
        formatted = formatCardNumber(formatted);
    } else if (type === 'expiration') {
        formatted = formatExpiration(formatted);
    } else if (type === 'cvv') {
        formatted = formatCvv(formatted);
    }

    input.value = formatted;
};

const setupCreditCardMasks = () => {
    document.querySelectorAll('[data-credit-mask]').forEach((input) => {
        const sync = () => maskCreditField(input);
        input.addEventListener('input', sync);
        input.addEventListener('blur', sync);
    });
};

const installListeners = () => {
    const searchToggle = document.querySelector('[data-search-toggle]');
    const searchBox = document.querySelector('[data-site-search]');
    console.debug('Spec sync: init started');

    if (searchToggle && searchBox) {
        searchToggle.addEventListener('click', () => {
            searchBox.classList.toggle('open');
            searchBox.querySelector('input')?.focus();
        });
    }

    const specSelect = document.getElementById('category_id') ?? document.querySelector('[data-spec-select]');
    const specContainer = document.getElementById('spec-fields') ?? document.querySelector('[data-spec-fields]');

    if (specSelect && specContainer) {
        const specUrlTemplate = specSelect.dataset.specUrl;
        const specHint = document.querySelector('[data-spec-hint]');
        const promptMessage = 'РЎРїРѕС‡Р°С‚РєСѓ РѕР±РµСЂС–С‚СЊ РєР°С‚РµРіРѕСЂС–СЋ, С‰РѕР± РїРѕР±Р°С‡РёС‚Рё РЅР°Р±С–СЂ С…Р°СЂР°РєС‚РµСЂРёСЃС‚РёРє.';
        const emptyMessage = 'Р”Р»СЏ С†С–С”С— РєР°С‚РµРіРѕСЂС–С— РЅРµ Р·Р°РґР°РЅРѕ С…Р°СЂР°РєС‚РµСЂРёСЃС‚РёРє.';
        const loadingMessage = 'Р—Р°РІР°РЅС‚Р°Р¶РµРЅРЅСЏ С…Р°СЂР°РєС‚РµСЂРёСЃС‚РёРєвЂ¦';

        const renderMessage = (message) => {
            specContainer.innerHTML = `<p class="muted-note">${message}</p>`;
        };

        const showLoadingMessage = () => {
            renderMessage(loadingMessage);
        };

        const parseStoredValues = () => {
            try {
                return (specSelect.dataset.specValues && JSON.parse(specSelect.dataset.specValues)) || {};
            } catch (error) {
                console.error('Spec sync: failed to parse stored values', error);
                return {};
            }
        };

        const captureSpecValues = () => {
            const values = {};
            specContainer.querySelectorAll('[name^="spec_values"]').forEach((input) => {
                const key = input.name.replace('spec_values[', '').replace(']', '');
                values[key] = input.value;
            });
            return values;
        };

        const hasCategory = () => Number(specSelect.value) > 0;

        const toggleSpecHint = (isActive) => {
            if (specHint) {
                specHint.hidden = isActive;
            }
        };

        const refreshSpecState = () => {
            const active = hasCategory();
            specContainer.classList.toggle('spec-grid--disabled', !active);
            specContainer.setAttribute('aria-disabled', (!active).toString());
            toggleSpecHint(active);
        };

        const fetchSpecs = async ({ preserveValues = true } = {}) => {
            const categoryValue = specSelect.value;
            const active = hasCategory();

            if (!specUrlTemplate || !active) {
                renderMessage(promptMessage);
                refreshSpecState();
                return;
            }

            if (preserveValues) {
                const currentValues = captureSpecValues();
                specSelect.dataset.specValues = JSON.stringify(currentValues);
            } else {
                specSelect.dataset.specValues = '{}';
            }

            const requestUrl = specUrlTemplate.replace('__CATEGORY__', categoryValue);
            showLoadingMessage();

            try {
                const response = await fetch(requestUrl, { headers: { Accept: 'application/json' } });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const fields = await response.json();

                if (!fields.length) {
                    renderMessage(emptyMessage);
                    return;
                }

                const storedValues = parseStoredValues();

                specContainer.innerHTML = fields
                    .map((field) => {
                        const value = storedValues[field.key] ?? '';
                        return renderInputForField(field, value);
                    })
                    .join('');
            } catch (error) {
                console.error('Spec sync: failed to load fields', error);
                renderMessage('РќРµ РІРґР°Р»РѕСЃСЏ Р·Р°РІР°РЅС‚Р°Р¶РёС‚Рё С…Р°СЂР°РєС‚РµСЂРёСЃС‚РёРєРё. РЎРїСЂРѕР±СѓР№С‚Рµ С‰Рµ СЂР°Р·.');
                alert('РќРµ РІРґР°Р»РѕСЃСЏ Р·Р°РІР°РЅС‚Р°Р¶РёС‚Рё С…Р°СЂР°РєС‚РµСЂРёСЃС‚РёРєРё. РџРµСЂРµРІС–СЂС‚Рµ С–РЅС‚РµСЂРЅРµС‚-Р·вЂ™С”РґРЅР°РЅРЅСЏ Р°Р±Рѕ СЃРїСЂРѕР±СѓР№С‚Рµ Р·РЅРѕРІСѓ.');
            } finally {
                refreshSpecState();
            }
        };

        specSelect.addEventListener('change', () => {
            refreshSpecState();
            fetchSpecs({ preserveValues: false });
        });

        refreshSpecState();
        fetchSpecs();
    }

    const adminFiltersForm = document.querySelector('[data-admin-product-filters]');
    if (adminFiltersForm) {
        let timer = null;
        adminFiltersForm.querySelectorAll('[data-filter-autosubmit]').forEach((field) => {
            const mode = field.getAttribute('data-filter-autosubmit');
            if (mode === 'change') {
                field.addEventListener('change', () => adminFiltersForm.requestSubmit());
                return;
            }

            field.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => adminFiltersForm.requestSubmit(), 350);
            });
        });
    }

    const themeToggleButtons = document.querySelectorAll('[data-theme-toggle]');
    themeToggleButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            toggleTheme();
        });
    });

    updateThemeControls(getActiveTheme());
    setupCreditCardMasks();

    const chatScrollContainers = document.querySelectorAll('[data-chat-scroll]');
    chatScrollContainers.forEach((container) => {
        container.scrollTop = container.scrollHeight;
    });
};

document.addEventListener('DOMContentLoaded', installListeners);

