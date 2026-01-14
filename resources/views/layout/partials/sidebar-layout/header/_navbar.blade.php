<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0">
    <!--begin::Activities-->
	<div class="app-navbar-item ms-1 ms-md-4">
        <!--begin::Drawer toggle-->
		<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px" id="kt_activities_toggle">{!! getIcon('messages', 'fs-2') !!}</div>
        <!--end::Drawer toggle-->
    </div>
    <!--end::Activities-->
    <!--begin::User menu-->
	<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
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
