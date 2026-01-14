<x-default-layout>

    @section('title')
        {{ __('common.user_details') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.users.show', $user) }}
    @endsection

    <div class="d-flex flex-column flex-lg-row gap-5">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px">
            <div class="card mb-5">
                <div class="card-body text-center">
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" alt="image"/>
                            @else
                                <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $user->name) }}">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    <h3 class="fw-bold mb-2">{{ $user->name }}</h3>
                    <div class="mb-5">
                            @foreach($user->roles as $role)
                                @php
                                    $roleNames = [
                                        'superadmin' => __('common.super_admin'),
                                        'editor' => __('common.editor'),
                                    ];
                                    $roleDisplay = $roleNames[$role->name] ?? ucfirst($role->name);
                                @endphp
                                <span class="badge badge-lg badge-light-primary">{{ $roleDisplay }}</span>
                            @endforeach
                        </div>
                    <div class="d-flex flex-column gap-2">
                        @if(!auth()->user()->hasRole('editor') || auth()->id() === $user->id)
                            <a href="{{ route('user-management.users.edit', $user) }}" class="btn btn-primary">
                                {!! getIcon('pencil', 'fs-2', '', 'i') !!}
                                {{ __('common.edit') }}
                            </a>
                        @endif
                        @if(!auth()->user()->hasRole('editor'))
                            <form action="{{ route('user-management.users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('common.delete_user_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light-danger w-100">
                                    {!! getIcon('trash', 'fs-2', '', 'i') !!}
                                    {{ __('common.delete') }}
                                </button>
                            </form>
                        @endif
                    </div>
                            </div>
                                    </div>
                                </div>
        <!--end::Sidebar-->

        <!--begin::Content-->
        <div class="flex-lg-row-fluid">
            <!--begin::Kullanıcı Bilgileri-->
            <div class="card mb-5">
                <div class="card-header">
                            <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.user_information') }}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ad Soyad</label>
                            <div class="text-gray-700">{{ $user->name }}</div>
                                    </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">E-Posta</label>
                            <div class="text-gray-700">{{ $user->email }}</div>
                                </div>
                                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bölüm</label>
                            <div class="text-gray-700">{{ $user->department ?? '-' }}</div>
                                </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Unvan</label>
                            <div class="text-gray-700">{{ $user->title ?? '-' }}</div>
                                    </div>
                                </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rolü</label>
                            <div class="text-gray-700">
                                @foreach($user->roles as $role)
                                    @php
                                        $roleNames = [
                                            'superadmin' => 'Süper Yönetici',
                                            'editor' => 'Editör',
                                        ];
                                        $roleDisplay = $roleNames[$role->name] ?? ucfirst($role->name);
                                    @endphp
                                    <span class="badge badge-lg badge-light-primary">{{ $roleDisplay }}</span>
                                @endforeach
                                    </div>
                                </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hesap ID</label>
                            <div class="text-gray-700">{{ $user->account_id ?? '-' }}</div>
                                    </div>
                                </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dil Seçeneği</label>
                            <div class="text-gray-700">{{ $user->language == 'tr' ? __('common.turkish') : __('common.english') }}</div>
                                    </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Son Giriş</label>
                            <div class="text-gray-700">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('d.m.Y H:i') }}
                                @else
                                    -
                                @endif
                                </div>
                                    </div>
                                </div>
                                    </div>
                                </div>
            <!--end::Kullanıcı Bilgileri-->

            <!--begin::Son Giriş Log Kayıtları-->
            <div class="card mb-5">
                <div class="card-header">
                            <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.last_login_logs') }}</h3>
                            </div>
                            </div>
                <div class="card-body">
                    @if(isset($loginLogs) && $loginLogs->count() > 0)
                            <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.date') }}</th>
                                        <th>{{ __('common.description') }}</th>
                                        <th>{{ __('common.ip_address') }}</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @foreach($loginLogs as $log)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d.m.Y H:i') }}</td>
                                            <td>{{ $log->description }}</td>
                                            <td>{{ $log->properties ? (is_string($log->properties) ? json_decode($log->properties, true)['ip'] ?? '-' : ($log->properties->ip ?? '-')) : '-' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_login_logs') }}</p>
                        </div>
                    @endif
                    </div>
            </div>
            <!--end::Son Giriş Log Kayıtları-->

            <!--begin::Olaylar ve Loglar-->
            <div class="card">
                <div class="card-header">
                            <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.events_and_logs') }}</h3>
                            </div>
                            </div>
                <div class="card-body">
                    @if(isset($activityLogs) && $activityLogs->count() > 0)
                            <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.date') }}</th>
                                        <th>{{ __('common.event') }}</th>
                                        <th>{{ __('common.description') }}</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityLogs as $log)
                                        @php
                                            $eventClass = 'primary';
                                            if ($log->event == 'created' || $log->event == 'uploaded') {
                                                $eventClass = 'success';
                                            } elseif ($log->event == 'updated') {
                                                $eventClass = 'primary';
                                            } elseif ($log->event == 'deleted') {
                                                $eventClass = 'danger';
                                            } elseif ($log->event == 'downloaded') {
                                                $eventClass = 'info';
                                            }
                                            
                                            $eventText = match($log->event) {
                                                'created' => __('common.created'),
                                                'updated' => __('common.updated'),
                                                'deleted' => __('common.deleted'),
                                                'uploaded' => __('common.uploaded'),
                                                'downloaded' => __('common.downloaded'),
                                                default => ucfirst($log->event ?? 'İşlem')
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <span class="badge badge-light-{{ $eventClass }}">
                                                    {{ $eventText }}
                                                </span>
                                        </td>
                                            <td>{{ $log->description }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_activity_logs') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <!--end::Olaylar ve Loglar-->
        </div>
        <!--end::Content-->
    </div>

</x-default-layout>
