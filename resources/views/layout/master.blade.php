<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! printHtmlAttributes('html') !!}>
<!--begin::Head-->
<head>
    <base href=""/>
    <title>@if(View::hasSection('title'))@yield('title') - @endif{{ config('app.name', 'WM Dosya&QR Yönetimi') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content=""/>
    <link rel="canonical" href="{{ url()->current() }}"/>

    {!! includeFavicon() !!}

    <!--begin::Fonts-->
    {!! includeFonts() !!}
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    @foreach(getGlobalAssets('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Vendor Stylesheets(used by this page)-->
    @foreach(getVendors('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Vendor Stylesheets-->

    <!--begin::Custom Stylesheets(optional)-->
    @foreach(getCustomCss() as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Custom Stylesheets-->

    @livewireStyles
    
    <!--begin::SweetAlert2 Cancel Button Style-->
    <style>
        .swal2-cancel.btn.btn-secondary {
            color: #ffffff !important;
        }
        .swal2-cancel.btn.btn-secondary:hover,
        .swal2-cancel.btn.btn-secondary:focus {
            color: #ffffff !important;
        }
    </style>
    <!--end::SweetAlert2 Cancel Button Style-->
</head>
<!--end::Head-->

<!--begin::Body-->
<body {!! printHtmlClasses('body') !!} {!! printHtmlAttributes('body') !!}>

@include('partials/theme-mode/_init')

@yield('content')

<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
@foreach(getGlobalAssets() as $path)
    {!! sprintf('<script src="%s"></script>', asset($path)) !!}
@endforeach
<!--end::Global Javascript Bundle-->

<!--begin::Vendors Javascript(used by this page)-->
@foreach(getVendors('js') as $path)
    {!! sprintf('<script src="%s"></script>', asset($path)) !!}
@endforeach
<!--end::Vendors Javascript-->

<!--begin::Custom Javascript(optional)-->
@foreach(getCustomJs() as $path)
    {!! sprintf('<script src="%s"></script>', asset($path)) !!}
@endforeach
<!--end::Custom Javascript-->
@stack('scripts')
<!--end::Javascript-->

<script>
    // Make translations available to JavaScript
    @php
        $translations = [
            'common' => [
                'delete_qr_code_confirm' => __('common.delete_qr_code_confirm'),
                'qr_code_deleted' => __('common.qr_code_deleted'),
                'delete_file_confirm' => __('common.delete_file_confirm'),
                'file_deleted' => __('common.file_deleted'),
                'delete_user_confirm' => __('common.delete_user_confirm'),
                'yes' => __('common.yes'),
                'no' => __('common.no'),
                'ok' => __('common.ok'),
                'cancel' => __('common.cancel'),
                'delete' => __('common.delete'),
                'at_least_one_file' => __('common.at_least_one_file'),
                'file_already_exists' => __('common.file_already_exists'),
                'overwrite_file' => __('common.overwrite_file'),
                'error_occurred' => __('common.error_occurred'),
                'file_uploaded' => __('common.file_uploaded'),
                'loading_activities_error' => __('common.loading_activities_error'),
                'created' => __('common.created'),
                'updated' => __('common.updated'),
                'deleted' => __('common.deleted'),
                'uploaded' => __('common.uploaded'),
                'downloaded' => __('common.downloaded'),
                'login_event' => __('common.login_event'),
                'logout_event' => __('common.logout_event'),
                'just_now' => __('common.just_now'),
                'minutes_ago' => __('common.minutes_ago'),
                'hours_ago' => __('common.hours_ago'),
                'days_ago' => __('common.days_ago'),
                'turkish' => __('common.turkish'),
                'add_new_category' => __('common.add_new_category'),
                'new_category_name_prompt' => __('common.new_category_name_prompt'),
                'category_create_error_message' => __('common.category_create_error_message'),
                'selected' => __('common.selected'),
                'select' => __('common.select'),
                'select_file' => __('common.select_file'),
                'no_files_found' => __('common.no_files_found'),
                'loading' => __('common.loading'),
                'select_category' => __('common.select_category'),
                'category_created' => __('common.category_created'),
            ]
        ];
    @endphp
    window.translations = @json($translations);
    
    // Helper function to get translation
    window.__ = function(key) {
        const keys = key.split('.');
        let value = window.translations;
        for (let k of keys) {
            value = value?.[k];
        }
        return value || key;
    };

    document.addEventListener('livewire:init', () => {
        Livewire.on('success', (message) => {
            toastr.success(message);
        });
        Livewire.on('error', (message) => {
            toastr.error(message);
        });

        Livewire.on('swal', (message, icon, confirmButtonText) => {
            if (typeof icon === 'undefined') {
                icon = 'success';
            }
            if (typeof confirmButtonText === 'undefined') {
                confirmButtonText = window.__('common.ok');
            }
            Swal.fire({
                text: message,
                icon: icon,
                buttonsStyling: false,
                confirmButtonText: confirmButtonText,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        });
    });
</script>

@livewireScripts
</body>
<!--end::Body-->

</html>
