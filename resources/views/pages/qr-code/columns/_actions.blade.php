<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    İşlemler
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="{{ route('qr-code.show', $qrCode) }}" class="menu-link px-3">
            Görüntüle
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="{{ route('qr-code.download', $qrCode) }}" class="menu-link px-3">
            İndir
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="{{ route('qr-code.edit', $qrCode) }}" class="menu-link px-3">
            Düzenle
        </a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3 text-danger" data-kt-qr-id="{{ $qrCode->id }}" data-kt-action="delete_qr">
            Sil
        </a>
    </div>
    <!--end::Menu item-->
</div>
<!--end::Menu-->


