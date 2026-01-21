<?php

namespace App\DataTables;

use App\Models\Brochure;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;

class BrochuresDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['checkbox', 'qr_code', 'name', 'background', 'status', 'actions'])
            ->addColumn('checkbox', function (Brochure $brochure) {
                return '<input type="checkbox" class="form-check-input" data-brochure-id="' . $brochure->id . '">';
            })
            ->addColumn('qr_code', function (Brochure $brochure) {
                if ($brochure->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($brochure->qr_code_path)) {
                    return '<a href="' . route('brochure.download', $brochure) . '" class="d-inline-block" title="' . __('common.download_qr_code') . '" download>
                        <img src="' . asset('storage/' . $brochure->qr_code_path) . '" alt="QR Code" class="w-50px h-50px" style="cursor: pointer; object-fit: contain;">
                    </a>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('name', function (Brochure $brochure) {
                $html = '<span class="text-dark fw-bold text-hover-primary d-block fs-6">' . e($brochure->name) . '</span>';
                if ($brochure->description) {
                    $html .= '<span class="text-muted fw-semibold text-muted d-block fs-7 mt-1">' . Str::limit(e($brochure->description), 50) . '</span>';
                }
                return $html;
            })
            ->editColumn('category', function (Brochure $brochure) {
                return $brochure->category ? $brochure->category->name : '-';
            })
            ->editColumn('background', function (Brochure $brochure) {
                if ($brochure->background_type === 'image' && $brochure->background_image_path) {
                    return '<span class="badge badge-light-info">' . __('common.image') . '</span>';
                } else {
                    $color = $brochure->background_color ?? '#ffffff';
                    return '<div class="d-flex align-items-center">
                        <span class="badge badge-light-primary me-2">' . __('common.color') . '</span>
                        <div style="width: 20px; height: 20px; background-color: ' . $color . '; border: 1px solid #ddd; border-radius: 3px;"></div>
                    </div>';
                }
            })
            ->editColumn('status', function (Brochure $brochure) {
                if ($brochure->is_expired) {
                    return '<div class="badge badge-light-danger">' . __('common.expired') . '</div>';
                }
                return $brochure->is_active 
                    ? '<div class="badge badge-light-success">' . __('common.active') . '</div>'
                    : '<div class="badge badge-light-secondary">' . __('common.inactive') . '</div>';
            })
            ->orderColumn('status', 'is_active $1')
            ->editColumn('view_count', function (Brochure $brochure) {
                return $brochure->view_count;
            })
            ->editColumn('created_at', function (Brochure $brochure) {
                return $brochure->created_at->translatedFormat('d F Y, H:i');
            })
            ->addColumn('actions', function (Brochure $brochure) {
                return view('pages.brochure.columns._actions', compact('brochure'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Brochure $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user', 'category', 'file']);

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
            ->setTableId('brochures-table')
            ->columns($this->getColumns())
            ->minifiedAjax(url()->current() . '?' . http_build_query(request()->only(['filter_status', 'filter_category', 'filter_date_from', 'filter_date_to'])))
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>")
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(7, 'desc')
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
                        var checkboxes = document.querySelectorAll('input[type=\"checkbox\"][data-brochure-id]');
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
            Column::make('name')->title(__('common.name')),
            Column::make('category')->title(__('common.category'))->name('category_id'),
            Column::computed('background')->title(__('common.background'))->orderable(false)->searchable(false),
            Column::make('status')->title(__('common.status'))->searchable(false)->orderable(true)->name('is_active'),
            Column::make('view_count')->title(__('common.view_count'))->searchable(false)->orderable(true),
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
        return 'Brochures_' . date('YmdHis');
    }
}
