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

// Generate qrcode
function qrCodeGenerate(){
    const qrCode = new QRCode(document.getElementById('qr-code'), {
        width: 128,
        height: 128,
        colorDark: '#000',
        colorLight: '#fff',
        correctLevel: QRCode.CorrectLevel.H
    });

    let input = $('#qr-code-text').val();

    if (!input) {
        return $('#qr-code img').attr('src', '');
    }

    qrCode.makeCode($('#qr-code-text').val());

    $('[btn-save]').on('click', () => {
        input = $('#qr-code-text').val();

        if (!input) {
            return toastify().error('Please enter text to generate QR code');
        }

        const canvas = $('#qr-code canvas').get(0);
        const image = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');

        $('<a></a>').attr('download', 'qr-code.png').attr('href', image).get(0).click();
    });
}

/**
 * Get Device Data
 * @param {string} deviceID
 * @returns {object | string}
 *
 */
async function  getDeviceData(deviceID) {
    if (!deviceID) {
        throw new Error('Device ID is required');
    }
    let device = {};
    // Fetch device data
    await $.ajax({
        type: 'GET',
        url: `/dashboard/device-data/${deviceID}`,
        success: function (data) {
            if (data) {
                device = data;
                return data;
            }
        }
    });

    return device ?? {};
}

// Bind device data for devices index page
async function bindDeviceSelector(device) {
    let deviceData,online,full,empty;
    const deviceId = device.getAttribute("attr-device");
    await getDeviceData(deviceId).then(data => deviceData = data);
  
    if(typeof deviceData === 'object' && Object.keys(deviceData).length > 0){
        online = deviceData["cabinet"]["online"];
        full = deviceData["cabinet"]["busySlots"];
        empty = deviceData["cabinet"]["emptySlots"];
    }
    // Remove loaders
    device.querySelectorAll(".spinner-grow").forEach(el => el.style.display = "none");

    if(online == 1){
        device.querySelector(".device-status .online").style.display = "flex";
        device.querySelector(".device-status .offline").style.display = "none";
        device.querySelector(".filled-batteries .num").innerHTML = full;
        device.querySelector(".empty-batteries .num").innerHTML = empty;   
    }else{
        device.querySelector(".device-status .online").style.display = "none";
        device.querySelector(".device-status .offline").style.display = "flex";
        device.querySelector(".filled-batteries .num").innerHTML = 0;
        device.querySelector(".empty-batteries .num").innerHTML = 0;
    }
}

// Bind device data for device show page
async function bindDeviceData(device) {
    let deviceData,online,full,empty;
    await getDeviceData(device).then(data => deviceData = data);
    console.log(deviceData);
    if(typeof deviceData === 'object' && Object.keys(deviceData).length > 0){
        online = deviceData["cabinet"]["online"];
        full = deviceData["cabinet"]["busySlots"];
        empty = deviceData["cabinet"]["emptySlots"];
    }

    // Remove loaders
    // device.querySelectorAll(".spinner-grow").forEach(el => el.style.display = "none");

    document.querySelector(".device-status .online").style.display = online == 1 ? "flex" : "none";
    document.querySelector(".device-status .offline").style.display = online == 1 ? "none" : "flex";
    document.querySelector(".filled-batteries .num").innerHTML = online == 1 ? full : 0;
    document.querySelector(".empty-batteries .num").innerHTML = online == 1 ? empty : 0;  

    // handle radio inputs when offline
    const slots = document.querySelectorAll("[name='slot']");
    slots.forEach((slot)=> online !== 1 ? slot.setAttribute("disabled","disabled"): '');

    
}
