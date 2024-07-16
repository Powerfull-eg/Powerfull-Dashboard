/* ---------------------------------
 * Plugins initialization
 * --------------------------------- */

/**
 * Initialize the tinyMCE editor.
 *
 * @param {string} selector
 * @param {object} options
 * @returns {object}
 * @see https://www.tiny.cloud/docs/
 */
async function initTinyMCE(selector, options) {
    const theme = localStorage.getItem('theme') || 'light';

    options = Object.assign({}, options, {
        selector,
        language: document.documentElement.lang,
        directionality: document.documentElement.dir,
        height: 300,
        menubar: false,
        branding: false,
        skin: theme === 'dark' ? 'oxide-dark' : 'oxide',
        content_css: theme === 'dark' ? 'dark' : 'default',
        plugins: 'advlist autolink code directionality link lists table image',
        toolbar: 'undo redo | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify outdent indent ltr rtl | bullist numlist | table image',
        toolbar_mode: 'sliding',
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/tinymce/upload',
        file_picker_types: 'image',
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = () => {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = () => {
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache = tinyMCE.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    cb(blobInfo.blobUri(), { title: file.name });
                };

                reader.readAsDataURL(file);
            };

            input.click();
        },
    });

    const [ instance ] = await tinyMCE.init(options);

    document.addEventListener('theme:changed', () => {
        instance?.destroy();
        initTinyMCE(selector, options);
    }, { once: true });
};

/**
 * Initialize the litepicker date picker.
 *
 * @param {string} selector
 * @param {object} options
 * @returns {object}
 * @see https://litepicker.com/
 */
function initLitepicker(selector, options) {
    options = Object.assign({}, options, {
        element: document.querySelector(selector),
        showTooltip: true,
        autoApply: true,
        allowRepick: true,
        lang: document.documentElement.lang,
        buttonText: {
            previousMonth: '<i class="ti ti-chevron-left"></i>',
            nextMonth: '<i class="ti ti-chevron-right"></i>',
        },
    });

    const picker = new Litepicker(options);
    $(selector).data('litepicker', picker);
};

/* ---------------------------------
 * Utilities
 * --------------------------------- */

/**
 * Toggle the password visibility.
 *
 * @param {string|object} el
 * @param {string} selector
 * @returns {void}
 */
function togglePassword(el, selector) {
    $(el).find('i').toggleClass('ti-eye ti-eye-closed');
    $(selector).attr('type', (i, attr) => attr === 'password' ? 'text' : 'password');
};

/**
 * Translate the given key with the given parameters.
 *
 * @param {string} key
 * @param {object} params
 * @returns {string}
 */
function __(key, params = {}) {
    if (typeof window.__translations === 'undefined') {
        return key;
    }

    let translation = window.__translations[key] || key;

    for (const [ param, value ] of Object.entries(params)) {
        translation = translation.replaceAll(`:${param}`, value);
    }

    return translation;
}
