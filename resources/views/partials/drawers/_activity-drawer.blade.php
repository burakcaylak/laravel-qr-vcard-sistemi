<!--begin::Activities drawer-->
<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '600px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close" style="width: 600px !important;">
	<div class="card shadow-none border-0 rounded-0">
		<!--begin::Header-->
		<div class="card-header" id="kt_activities_header">
			<h3 class="card-title fw-bold text-gray-900">{{ __('common.activity_logs') }}</h3>
			<div class="card-toolbar">
				<button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_activities_close">{!! getIcon('cross', 'fs-1') !!}</button>
			</div>
		</div>
		<!--end::Header-->
		<!--begin::Body-->
		<div class="card-body position-relative" id="kt_activities_body">
			<!--begin::Content-->
			<div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body" data-kt-scroll-dependencies="#kt_activities_header" data-kt-scroll-offset="5px">
				<!--begin::Loading-->
				<div id="kt_activities_loading" class="text-center py-10">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden">{{ __('common.loading') }}</span>
					</div>
				</div>
				<!--end::Loading-->
				<!--begin::Timeline items-->
				<div class="timeline" id="kt_activities_timeline" style="display: none;">
					<!-- Activity logs will be loaded here via AJAX -->
				</div>
				<!--end::Timeline items-->
				<!--begin::Empty state-->
				<div id="kt_activities_empty" class="text-center py-10" style="display: none;">
					<p class="text-muted">{{ __('common.no_activity_logs') }}</p>
				</div>
				<!--end::Empty state-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Body-->
	</div>
</div>
<!--end::Activities drawer-->

<style>
#kt_activities {
	width: 600px !important;
}
@media (max-width: 991.98px) {
	#kt_activities {
		width: 100% !important;
	}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const drawer = document.getElementById('kt_activities');
	const toggle = document.getElementById('kt_activities_toggle');
	const timeline = document.getElementById('kt_activities_timeline');
	const loading = document.getElementById('kt_activities_loading');
	const empty = document.getElementById('kt_activities_empty');
	let loaded = false;

	function getEventIcon(event) {
		const icons = {
			'created': 'check',
			'updated': 'pencil',
			'deleted': 'trash',
			'uploaded': 'arrow-up',
			'downloaded': 'arrow-down',
			'login': 'user',
			'logout': 'exit-right',
		};
		return icons[event] || 'information-5';
	}

	function getEventColor(event) {
		const colors = {
			'created': 'success',
			'updated': 'primary',
			'deleted': 'danger',
			'uploaded': 'info',
			'downloaded': 'info',
			'login': 'success',
			'logout': 'warning',
		};
		return colors[event] || 'primary';
	}

	function getEventText(event) {
		const texts = {
			'created': 'Oluşturuldu',
			'updated': 'Güncellendi',
			'deleted': 'Silindi',
			'uploaded': 'Yüklendi',
			'downloaded': 'İndirildi',
			'login': 'Giriş Yapıldı',
			'logout': 'Çıkış Yapıldı',
		};
		return texts[event] || event;
	}

	function formatDate(dateString) {
		const date = new Date(dateString);
		const now = new Date();
		const diff = now - date;
		const minutes = Math.floor(diff / 60000);
		const hours = Math.floor(diff / 3600000);
		const days = Math.floor(diff / 86400000);

		if (minutes < 1) return window.__('common.just_now');
		if (minutes < 60) return window.__('common.minutes_ago').replace(':minutes', minutes);
		if (hours < 24) return window.__('common.hours_ago').replace(':hours', hours);
		if (days < 7) return window.__('common.days_ago').replace(':days', days);

		const locale = window.translations?.common?.turkish ? 'tr-TR' : 'en-US';
		return date.toLocaleDateString(locale, {
			day: 'numeric',
			month: 'long',
			year: 'numeric',
			hour: '2-digit',
			minute: '2-digit'
		});
	}

	function getUserAvatar(user) {
		// Her zaman sabit user ikonu göster - profil fotoğrafı asla kullanılmayacak
		return '<div class="symbol-label fs-3 bg-light-info text-info"><span class="ki-solid ki-user fs-2"></span></div>';
	}

	function loadActivityLogs() {
		if (loaded) return;
		loaded = true;

		fetch('{{ route("activity-logs.index") }}', {
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
			},
			credentials: 'same-origin'
		})
		.then(response => {
			if (!response.ok) {
				return response.json().then(err => {
					throw new Error(err.error || `HTTP error! status: ${response.status}`);
				});
			}
			return response.json();
		})
		.then(data => {
			loading.style.display = 'none';
			
			// Check if response has error
			if (data && data.error) {
				throw new Error(data.error);
			}
			
			if (!data || data.length === 0) {
				empty.style.display = 'block';
				return;
			}

			timeline.style.display = 'block';
			timeline.innerHTML = '';

			data.forEach(log => {
				const event = log.event || 'info';
				const icon = getEventIcon(event);
				const color = getEventColor(event);
				const eventText = getEventText(event);
				const date = formatDate(log.created_at);
				const user = {
					user_name: log.user_name || null,
					user_email: log.user_email || null,
					profile_photo_url: log.profile_photo_url || null,
					event: event
				};

				const timelineItem = document.createElement('div');
				timelineItem.className = 'timeline-item';
				timelineItem.innerHTML = `
					<!--begin::Timeline line-->
					<div class="timeline-line w-40px"></div>
					<!--end::Timeline line-->
					<!--begin::Timeline icon-->
					<div class="timeline-icon symbol symbol-circle symbol-40px me-4">
						<div class="symbol-label fs-3 bg-light-${color} text-${color}">
							<span class="ki-solid ki-${icon} fs-2"></span>
						</div>
					</div>
					<!--end::Timeline icon-->
					<!--begin::Timeline content-->
					<div class="timeline-content mb-10 mt-n1">
						<!--begin::Timeline heading-->
						<div class="pe-3 mb-5">
							<!--begin::Title-->
							<div class="fs-5 fw-semibold mb-2">${log.description || 'Aktivite'}</div>
							<!--end::Title-->
							<!--begin::Description-->
							<div class="d-flex align-items-center mt-1 fs-6">
								<!--begin::Info-->
								<div class="text-muted me-2 fs-7">${date}</div>
								<!--end::Info-->
								${log.user_name ? `
								<!--begin::User-->
								<div class="symbol symbol-circle symbol-25px ms-2" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="${log.user_name}">
									${getUserAvatar(user)}
								</div>
								<!--end::User-->
								` : ''}
								<!--begin::Event badge-->
								<span class="badge badge-light-${color} ms-2">${eventText}</span>
								<!--end::Event badge-->
							</div>
							<!--end::Description-->
						</div>
						<!--end::Timeline heading-->
					</div>
					<!--end::Timeline content-->
				`;
				timeline.appendChild(timelineItem);
			});

			// Initialize tooltips
			if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
				const tooltipTriggerList = [].slice.call(timeline.querySelectorAll('[data-bs-toggle="tooltip"]'));
				tooltipTriggerList.map(function (tooltipTriggerEl) {
					return new bootstrap.Tooltip(tooltipTriggerEl);
				});
			}
		})
		.catch(error => {
			console.error('Activity logs yüklenirken hata:', error);
			loading.style.display = 'none';
			empty.style.display = 'block';
			empty.innerHTML = '<p class="text-danger">' + window.__('common.loading_activities_error') + '</p>';
		});
	}

	// Load logs when drawer is opened
	if (drawer && toggle) {
		toggle.addEventListener('click', function() {
			setTimeout(loadActivityLogs, 300);
		});

		// Also listen for drawer show event
		drawer.addEventListener('shown.bs.drawer', loadActivityLogs);
	}
});
</script>
