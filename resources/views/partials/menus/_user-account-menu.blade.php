<!--begin::User account menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
    <!--begin::Menu item-->
    <div class="menu-item px-5 my-1">
        <a href="{{ route('user-management.users.edit', Auth::user()->id) }}" class="menu-link px-5">{{ __('common.account_settings') }}</a>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu item-->
    <div class="menu-item px-5">
        <a class="button-ajax menu-link px-5" href="#" data-action="{{ route('logout') }}" data-method="post" data-csrf="{{ csrf_token() }}" data-reload="true">
            {{ __('common.sign_out') }}
        </a>
    </div>
    <!--end::Menu item-->
</div>
<!--end::User account menu-->
