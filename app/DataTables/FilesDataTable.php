<?php

namespace App\DataTables;

use App\Models\File;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class FilesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['checkbox', 'file', 'type', 'actions'])
            ->addColumn('checkbox', function (File $file) {
                return '<input type="checkbox" class="form-check-input" data-file-id="' . $file->id . '">';
            })
            ->editColumn('file', function (File $file) {
                return view('pages.file-management.columns._file', compact('file'));
            })
            ->editColumn('type', function (File $file) {
                $badges = [
                    'image' => 'badge-light-primary',
                    'document' => 'badge-light-info',
                    'video' => 'badge-light-danger',
                    'audio' => 'badge-light-success',
                    'other' => 'badge-light-secondary',
                ];
                $types = [
                    'image' => __('common.type_image'),
                    'document' => __('common.type_document'),
                    'video' => __('common.type_video'),
                    'audio' => __('common.type_audio'),
                    'other' => __('common.type_other'),
                ];
                $badge = $badges[$file->type] ?? 'badge-light-secondary';
                $typeText = $types[$file->type] ?? ucfirst($file->type);
                return sprintf('<div class="badge %s">%s</div>', $badge, $typeText);
            })
            ->editColumn('size', function (File $file) {
                return $file->size_human;
            })
            ->editColumn('category', function (File $file) {
                return $file->category ?? '-';
            })
            ->editColumn('user', function (File $file) {
                return $file->user->name;
            })
            ->editColumn('created_at', function (File $file) {
                return $file->created_at->format('d.m.Y H:i');
            })
            ->addColumn('actions', function (File $file) {
                return view('pages.file-management.columns._actions', compact('file'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(File $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with('user');

        // Filtreleme
        if (request()->has('filter_status')) {
            $status = request()->get('filter_status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
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
            ->setTableId('files-table')
            ->columns($this->getColumns())
            ->minifiedAjax(url()->current() . '?' . http_build_query(request()->only(['filter_status', 'filter_category', 'filter_date_from', 'filter_date_to'])))
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(7)
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
                    'previous' => __('common.datatable_previous'),
                ],
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
                        var checkboxes = document.querySelectorAll('input[type=\"checkbox\"][data-file-id]');
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
            Column::make('file')->title(__('common.file'))->addClass('d-flex align-items-center')->name('name'),
            Column::make('type')->title(__('common.type'))->searchable(false),
            Column::make('size')->title(__('common.file_size'))->searchable(false),
            Column::make('category')->title(__('common.category')),
            Column::make('user')->title(__('common.uploaded_by'))->name('user_id')->searchable(false),
            Column::make('created_at')->title(__('common.upload_date'))->addClass('text-nowrap'),
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
        return 'Files_' . date('YmdHis');
    }
}

