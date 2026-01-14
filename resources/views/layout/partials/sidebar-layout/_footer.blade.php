<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer">
	<!--begin::Footer container-->
	<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
		<!--begin::Copyright-->
		<div class="text-gray-900 order-2 order-md-1">
			@php
				$settings = \App\Models\Settings::getSettings();
				$footerText = !empty($settings->footer_text) ? $settings->footer_text : (date('Y') . '© Keenthemes');
			@endphp
			<span class="text-muted fw-semibold">{!! $footerText !!}</span>
		</div>
		<!--end::Copyright-->
	</div>
	<!--end::Footer container-->
</div>
<!--end::Footer-->
