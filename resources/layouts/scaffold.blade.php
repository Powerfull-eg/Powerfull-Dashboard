@props([
    'title' => null,
    'direction' => app()->getLocale() == 'ar' ? 'rtl' : 'ltr',
])

@php
    $title = (isset($title) ? $title . ' | ' : '') . __(setting('app_name'));
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $direction }}">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="title" content="{{ $title }}" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Shahna For Trading" />

    @stack('meta')

    <title>{{ $title }}</title>

    <link rel="icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon" />

    {{-- Tabler Core --}}
    <link rel="stylesheet" href="{{ asset("/vendor/tabler/tabler.$direction.min.css") }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/tabler/tabler-vendors.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/tabler-icons/tabler-icons.min.css') }}" />

    {{-- Plugins --}}
    <link rel="stylesheet" href="{{ asset('/vendor/jquery-confirm/jquery-confirm.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="/vendor/Img-Uploader/image-uploader.min.css">

    {{-- DataTable Style --}}
    <link rel="stylesheet" href="{{ asset('/vendor/jquery-dataTables/dataTables.min.css') }}" />

    {{-- Custom Styles --}}
    <link rel="stylesheet" href="{{ asset('/assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/css/overrides.css') }}" />

    @livewireStyles
    @toastifyCss

    @stack('styles')
</head>

<body {{ $attributes->merge(['class' => 'antialiased']) }}>
    <script src="{{ asset('/vendor/tabler/tabler-theme.min.js') }}"></script>

    {{ $slot }}

    {{-- Jquery --}}
    <script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>

    {{-- Tabler Core --}}
    <script src="{{ asset('/vendor/tabler/tabler.min.js') }}"></script>

    {{-- Plugins --}}
    <script src="{{ asset('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/vendor/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('/vendor/litepicker/litepicker.min.js') }}"></script>
    <script src="{{ asset('/vendor/tom-select/tom-select.base.min.js') }}"></script>
    <script src="{{ asset('/vendor/jquery-dataTables/dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="/vendor/Img-Uploader/image-uploader.min.js"></script>

    {{-- Language Script --}}
    <script src="{{ route('global.translations.show', app()->getLocale()) }}"></script>

    {{-- Custom Scripts --}}
    <script src="{{ asset('/assets/js/app.js') }}"></script>
    <script src="{{ asset('/assets/js/functions.js') }}"></script>
    <script src="{{asset('/assets/js/table2excel.min.js')}}"></script>
    @livewireScripts
    @toastifyJs

    @stack('scripts')

    {{--  Facebook Script --}}
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : "{{ env('FACEBOOK_CLIENT_ID') }}",
          cookie     : true,
          xfbml      : true,
          version    : "{{ env('FACBOOK_APP_VERSION') }}"
        });
  
        FB.AppEvents.logPageView();   
  
      };
  
      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "https://connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
</body>

</html>
