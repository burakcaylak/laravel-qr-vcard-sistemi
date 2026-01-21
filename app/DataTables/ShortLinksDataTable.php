<?php

namespace App\DataTables;

use App\Models\ShortLink;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;

class ShortLinksDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['checkbox', 'short_code', 'original_url', 'status', 'actions'])
            ->addColumn('checkbox', function (ShortLink $shortLink) {
                return '<input type="checkbox" class="form-check-input" data-short-link-id="' . $shortLink->id . '">';
            })
            ->editColumn('short_code', function (ShortLink $shortLink) {
                return '<a href="' . $shortLink->short_url . '" target="_blank" class="text-primary fw-bold">' . 
                       e($shortLink->short_code) . '</a>';
            })
            ->editColumn('original_url', function (ShortLink $shortLink) {
                return '<a href="' . e($shortLink->original_url) . '" target="_blank" class="text-muted">' . 
                       Str::limit(e($shortLink->original_url), 50) . '</a>';
            })
            ->editColumn('category', function (ShortLink $shortLink) {
                return $shortLink->category ? $shortLink->category->name : '-';
            })
            ->editColumn('click_count', function (ShortLink $shortLink) {
                return $shortLink->click_count;
            })
            ->editColumn('status', function (ShortLink $shortLink) {
                if ($shortLink->is_expired) {
                    return '<div class="badge badge-light-danger">' . __('common.expired') . '</div>';
                }
                return $shortLink->is_active 
                    ? '<div class="badge badge-light-success">' . __('common.active') . '</div>'
                    : '<div class="badge badge-light-secondary">' . __('common.inactive') . '</div>';
            })
            ->orderColumn('status', 'is_active $1')
            ->editColumn('created_at', function (ShortLink $shortLink) {
                return $shortLink->created_at->translatedFormat('d F Y, H:i');
            })
            ->addColumn('actions', function (ShortLink $shortLink) {
                return view('pages.short-link.columns._actions', compact('shortLink'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ShortLink $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user', 'category']);

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
            ->setTableId('short-links-table')
            ->columns($this->getColumns())
            ->minifiedAjax(url()->current() . '?' . http_build_query(request()->only(['filter_status', 'filter_category', 'filter_date_from', 'filter_date_to'])))
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>")
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(6, 'desc')
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
                        var checkboxes = document.querySelectorAll('input[type=\"checkbox\"][data-short-link-id]');
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
                
                // Silme işlemi için event listener ekle
                var deleteButtons = document.querySelectorAll('[data-kt-action=\"delete_short_link\"]');
                deleteButtons.forEach(function(element) {
                    // Önceki listener'ı kaldır ve yeni ekle
                    var newElement = element.cloneNode(true);
                    element.parentNode.replaceChild(newElement, element);
                    
                    newElement.addEventListener('click', function(e) {
                        e.preventDefault();
                        var shortLinkId = this.getAttribute('data-kt-short-link-id');
                        
                        Swal.fire({
                            title: '" . addslashes(__('common.are_you_sure')) . "',
                            text: '" . addslashes(__('common.delete_confirm')) . "',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: '" . addslashes(__('common.delete')) . "',
                            cancelButtonText: '" . addslashes(__('common.cancel')) . "'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '" . route('short-link.destroy', ':id') . "'.replace(':id', shortLinkId);
                                
                                var csrfToken = document.createElement('input');
                                csrfToken.type = 'hidden';
                                csrfToken.name = '_token';
                                csrfToken.value = '" . csrf_token() . "';
                                form.appendChild(csrfToken);
                                
                                var methodField = document.createElement('input');
                                methodField.type = 'hidden';
                                methodField.name = '_method';
                                methodField.value = 'DELETE';
                                form.appendChild(methodField);
                                
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    });
                });
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
            Column::make('short_code')->title(__('common.short_code'))->addClass('text-nowrap'),
            Column::make('original_url')->title(__('common.original_url')),
            Column::make('title')->title(__('common.title')),
            Column::make('category')->title(__('common.category')),
            Column::make('click_count')->title(__('common.click_count'))->searchable(false)->orderable(true),
            Column::make('status')->title(__('common.status'))->searchable(false)->orderable(true)->name('is_active'),
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
        return 'ShortLinks_' . date('YmdHis');
    }
}
