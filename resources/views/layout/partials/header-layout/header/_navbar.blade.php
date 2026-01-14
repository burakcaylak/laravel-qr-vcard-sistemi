<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0">
    <!--begin::Activities-->
    <div class="app-navbar-item ms-1 ms-md-3">
        <!--begin::Drawer toggle-->
        <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" id="kt_activities_toggle">{!! getIcon('chart-simple', 'fs-2 fs-md-1') !!}</div>
        <!--end::Drawer toggle-->
    </div>
    <!--end::Activities-->
    <!--begin::User menu-->
    <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <div class="symbol-label fs-3 bg-light-info text-info">
                {!! getIcon('user', 'fs-2') !!}
            </div>
        </div>
        @include('partials/menus/_user-account-menu')
        <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->
</div>
<!--end::Navbar-->
