<?php

namespace App\DataTables;

use App\Models\VCard;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class VCardsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['checkbox', 'qr_code', 'name', 'status', 'actions'])
            ->addColumn('checkbox', function (VCard $vCard) {
                return '<input type="checkbox" class="form-check-input" data-v-card-id="' . $vCard->id . '">';
            })
            ->addColumn('qr_code', function (VCard $vCard) {
                if ($vCard->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($vCard->file_path)) {
                    return '<a href="' . route('v-card.download', $vCard) . '" class="d-inline-block" title="' . __('common.download_qr_code') . '" download>
                        <img src="' . asset('storage/' . $vCard->file_path) . '" alt="QR Code" class="w-50px h-50px" style="cursor: pointer; object-fit: contain;">
                    </a>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('name', function (VCard $vCard) {
                return '<span class="text-dark fw-bold text-hover-primary d-block fs-6">' . e($vCard->getLocalizedField('name') ?? '-') . '</span>';
            })
            ->editColumn('company', function (VCard $vCard) {
                return $vCard->getLocalizedField('company') ?? '-';
            })
            ->editColumn('email', function (VCard $vCard) {
                return $vCard->email ?? $vCard->getLocalizedField('email') ?? '-';
            })
            ->editColumn('phone', function (VCard $vCard) {
                return $vCard->phone ?? $vCard->getLocalizedField('phone') ?? '-';
            })
            ->editColumn('status', function (VCard $vCard) {
                if ($vCard->is_expired) {
                    return '<div class="badge badge-light-danger">' . __('common.expired') . '</div>';
                }
                return $vCard->is_active 
                    ? '<div class="badge badge-light-success">' . __('common.active') . '</div>'
                    : '<div class="badge badge-light-secondary">' . __('common.inactive') . '</div>';
            })
            ->orderColumn('status', 'is_active $1')
            ->editColumn('scan_count', function (VCard $vCard) {
                return $vCard->scan_count;
            })
            ->editColumn('created_at', function (VCard $vCard) {
                return $vCard->created_at->translatedFormat('d F Y, H:i');
            })
            ->addColumn('actions', function (VCard $vCard) {
                return view('pages.v-card.columns._actions', compact('vCard'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(VCard $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user', 'category', 'template']);

        // Filtreleme
        if (request()->has('filter_status')) {
            $status = request()->get('filter_status');
            if ($status === 'active') {
                $query->where('is_active', true)->where(function($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                });
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->where('expires_at', '<=', now());
            }
        }

        if (request()->has('filter_category') && request()->get('filter_category')) {
            $query->where('category_id', request()->get('filter_category'));
        }

        if (request()->has('filter_date_from') && request()->get('filter_date_from')) {
            $query->whereDate('created_at', '>=', request()->get('filter_date_from'));
        }

        if (request()->has('filter_date_to') && request()->get('filter_date_to')) {
            $query->whereDate('created_at', '<=', request()->get('filter_date_to'));
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('v-cards-table')
            ->columns($this->getColumns())
            ->minifiedAjax(url()->current() . '?' . http_build_query(request()->only(['filter_status', 'filter_category', 'filter_date_from', 'filter_date_to'])))
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>")
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(8, 'desc')
            ->language([
                'info' => __('common.datatable_info'),
                'infoEmpty' => __('common.datatable_info_empty'),
                'infoFiltered' => __('common.datatable_info_filtered'),
                'lengthMenu' => __('common.datatable_length_menu'),
                'loadingRecords' => __('common.datatable_loading'),
                'processing' => __('common.datatable_processing'),
                'search' => __('common.datatable_search'),
                'zeroRecords' => __('common.datatable_zero_records'),
                'paginate' => [
                    'first' => __('common.datatable_first'),
                    'last' => __('common.datatable_last'),
                    'next' => __('common.datatable_next'),
                    'previous' => __('common.datatable_previous')
                ]
            ])
            ->drawCallback("function() {
                // Menüyü yeniden başlat
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
                
                // Tümünü seç checkbox
                var selectAll = document.getElementById('select-all');
                if (selectAll) {
                    // Önceki listener'ı kaldır ve yeni ekle
                    var newSelectAll = selectAll.cloneNode(true);
                    selectAll.parentNode.replaceChild(newSelectAll, selectAll);
                    
                    newSelectAll.addEventListener('change', function() {
                        var checkboxes = document.querySelectorAll('input[type=\"checkbox\"][data-v-card-id]');
                        checkboxes.forEach(function(cb) {
                            cb.checked = newSelectAll.checked;
                        });
                        // Bulk actions container'ı göster/gizle
                        var bulkContainer = document.getElementById('bulk-actions-container');
                        if (bulkContainer) {
                            if (newSelectAll.checked && checkboxes.length > 0) {
                                bulkContainer.classList.remove('d-none');
                            } else {
                                bulkContainer.classList.add('d-none');
                            }
                        }
                    });
                }
            }");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->title('<input type="checkbox" class="form-check-input" id="select-all">')
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false)
                ->width(30)
                ->exportable(false)
                ->printable(false),
            Column::computed('qr_code')->title(__('common.qr_code'))->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('name')->title(__('common.name'))->name('name_tr'),
            Column::make('company')->title(__('common.company'))->name('company_tr'),
            Column::make('email')->title(__('common.email')),
            Column::make('phone')->title(__('common.phone')),
            Column::make('status')->title(__('common.status'))->searchable(false)->orderable(true)->name('is_active'),
            Column::make('scan_count')->title(__('common.scan_count'))->searchable(false)->orderable(true),
            Column::make('created_at')->title(__('common.created_at'))->addClass('text-nowrap'),
            Column::computed('actions')
                ->title(__('common.actions'))
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'VCards_' . date('YmdHis');
    }
}
