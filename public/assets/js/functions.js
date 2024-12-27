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
// Add note
function addNote(form){
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !event.shiftKey) {
            $.confirm({
                title: 'Add Note!',
                content: 'Are you want to add the note',
                buttons: {
                    confirm: function () {
                        // submit form 
                        form.submit();
                    },
                    cancel: function () {},
                }
            });
        }
    });
}

// Check device connection
async function checkDeviceConnection(device,label) {
    let deviceData,online;
    await getDeviceData(device).then(data => deviceData = data);
    if(typeof deviceData === 'object' && Object.keys(deviceData).length > 0){
        online = deviceData["cabinet"]["online"];
    }

    return online == 1;
}


// prepare image uploader 
function prepareImageUploader() {
    // image uploader
    const uploaders = document.querySelectorAll('.img-uploader');
    uploaders.forEach(uploader => {
        let imageInput = uploader.querySelector('.image-input');
        let imagePreview = uploader.querySelector('.image-preview');

        // Change Image Preview on input change
        imageInput.addEventListener('change', (event) => { 
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
        // open input on click
        imagePreview.addEventListener('click', () => { imageInput.click(); });
    });
}
function prepareMenuUploader(menuImages,label) {

          // Menu Images
          let menu = menuImages ?? [];
          let images = [];
          menu.forEach((item) => {
              images.push({id: item.id, src: item.image});
          });
          options = {
              label: label,
              preloaded: images,
              imagesInputName: 'menu_images',
          }
          $('.menu-images').imageUploader(options);
          const addElement = `<div class="uploaded-image add"><i class="ti ti-circle-plus"></i></div>`;
          $('.image-uploader .uploaded').prepend(addElement);
}

// Filter
function filter(needle){
    const elements = document.querySelectorAll("[attr-filter]");
    elements.forEach(element => {
        const haystack = element.getAttribute('attr-filter');
        if(haystack.toLowerCase().includes(needle.toLowerCase())) {
            element.classList.contains('d-none') ? element.classList.remove('d-none'): '';
        }else{
            element.classList.contains('d-none') ? '' : element.classList.add('d-none');
        }
    });
}

// Prepare Chart 
let chartData;
const prepareChart = (id,chartData) => {
    const ctx = document.getElementById(id);
    const dataValues = chartData.dataValues ?? null;
    const title = chartData.title ?? null;
    const dataLabels = chartData.dataLabels ?? null;
    const labels = dataLabels;
    const data = {
      labels: labels,
      datasets: [{
        label: title,
        data: dataValues ?? [],
        backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(201, 203, 207, 0.2)',
        'rgba(155, 181, 232, 0.2)'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)',
        'rgb(155, 181, 232)'
      ],
        borderWidth: 1
      }]
    };
    const config = {
      type: 'bar',
      data: data,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      },
    };
    new Chart(ctx,config);

}

// Capitalize first letter
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


/* ---------------------------------------------
 * Show Page Loader on Submit and link click    |
 * --------------------------------------------- */
$(document).ready(() => {
    $('#page-overlay').addClass('d-none');
})
$(document).on('submit', 'form', () => {
     showPageLoader();
});

$(document).on('click', 'a:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"]):not(.btn-close)', () => { 
    showPageLoader();
});

function showPageLoader() { $('#page-overlay').removeClass('d-none'); }
window.addEventListener('pageshow', function (event) {
    if (event.persisted) { $('#page-overlay').addClass('d-none'); }
});