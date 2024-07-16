<x-layouts::dashboard>
    @push('styles')
        <style>
            #qr-code {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #qr-code img {
                min-width: 128px;
                min-height: 128px;
            }
        </style>
    @endpush

    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ __('Generate QR Code') }}</div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md">
                    <textarea id="qr-code-text" rows="5" class="form-control" placeholder="{{ __('Enter text to generate QR code') }}"></textarea>
                </div>

                <div class="col-md-auto">
                    <div id="qr-code" class="bg-white rounded border p-3"></div>
                </div>
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="button" class="btn btn-primary" btn-save>{{ __('Save Image') }}</button>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('vendor/qrcode/qrcode.min.js') }}"></script>

        <script>
            $(document).ready(() => {
                const qrCode = new QRCode(document.getElementById('qr-code'), {
                    width: 128,
                    height: 128,
                    colorDark: '#000',
                    colorLight: '#fff',
                    correctLevel: QRCode.CorrectLevel.H
                });

                $('#qr-code-text').on('keyup change', () => {
                    const input = $('#qr-code-text').val();

                    if (!input) {
                        return $('#qr-code img').attr('src', '');
                    }

                    qrCode.makeCode($('#qr-code-text').val());
                });

                $('[btn-save]').on('click', () => {
                    const input = $('#qr-code-text').val();

                    if (!input) {
                        return toastify().error('{{ __('Please enter text to generate QR code') }}');
                    }

                    const canvas = $('#qr-code canvas').get(0);
                    const image = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');

                    $('<a></a>').attr('download', 'qr-code.png').attr('href', image).get(0).click();
                });
            });
        </script>
    @endpush
</x-layouts::dashboard>
