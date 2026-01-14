<?php

namespace App\DataTables;

use App\Models\QrCode;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class QrCodesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['qr', 'type', 'status', 'scan_count', 'actions'])
            ->editColumn('qr', function (QrCode $qrCode) {
                return view('pages.qr-code.columns._qr', compact('qrCode'));
            })
            ->editColumn('category', function (QrCode $qrCode) {
                // Önce eski category string'ini kontrol et
                if ($qrCode->category) {
                    return $qrCode->category;
                }
                // Sonra category ilişkisini kontrol et
                if ($qrCode->category_id && $qrCode->category) {
                    return $qrCode->category->name;
                }
                return '-';
            })
            ->editColumn('requested_by', function (QrCode $qrCode) {
                return $qrCode->requested_by ?? '-';
            })
            ->editColumn('request_date', function (QrCode $qrCode) {
                return $qrCode->request_date ? $qrCode->request_date->translatedFormat('d F Y') : '-';
            })
            ->editColumn('type', function (QrCode $qrCode) {
                $types = [
                    'file' => ['badge' => 'badge-light-primary', 'text' => __('common.type_file')],
                    'url' => ['badge' => 'badge-light-info', 'text' => __('common.type_url')],
                    'multi_file' => ['badge' => 'badge-light-success', 'text' => __('common.type_multi_file')],
                    'text' => ['badge' => 'badge-light-warning', 'text' => __('common.type_text')],
                    'email' => ['badge' => 'badge-light-success', 'text' => __('common.type_email')],
                    'phone' => ['badge' => 'badge-light-danger', 'text' => __('common.type_phone')],
                    'wifi' => ['badge' => 'badge-light-dark', 'text' => __('common.type_wifi')],
                    'vcard' => ['badge' => 'badge-light-secondary', 'text' => __('common.type_vcard')],
                ];
                $typeData = $types[$qrCode->qr_type] ?? ['badge' => 'badge-light-secondary', 'text' => strtoupper($qrCode->qr_type)];
                return sprintf('<div class="badge %s">%s</div>', $typeData['badge'], $typeData['text']);
            })
            ->editColumn('scan_count', function (QrCode $qrCode) {
                return $qrCode->scan_count;
            })
            ->editColumn('status', function (QrCode $qrCode) {
                if ($qrCode->is_expired) {
                    return '<div class="badge badge-light-danger">' . __('common.expired') . '</div>';
                }
                return $qrCode->is_active 
                    ? '<div class="badge badge-light-success">' . __('common.active') . '</div>'
                    : '<div class="badge badge-light-secondary">' . __('common.inactive') . '</div>';
            })
            ->orderColumn('status', 'is_active $1')
            ->editColumn('created_at', function (QrCode $qrCode) {
                return $qrCode->created_at->translatedFormat('d F Y, H:i');
            })
            ->addColumn('actions', function (QrCode $qrCode) {
                return view('pages.qr-code.columns._actions', compact('qrCode'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(QrCode $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['user', 'file', 'category']); // Tüm QR kodları göster
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('qr-codes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(7, 'desc') // created_at kolonuna göre sırala
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
            ->drawCallback("function() {" . (file_exists(resource_path('views/pages/qr-code/columns/_draw-scripts.js')) ? file_get_contents(resource_path('views/pages/qr-code/columns/_draw-scripts.js')) : '') . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('qr')->addClass('d-flex align-items-center')->name('name'),
            Column::make('category')->title(__('common.category')),
            Column::make('requested_by')->title(__('common.requested_by')),
            Column::make('request_date')->title(__('common.request_date')),
            Column::make('type')->title(__('common.type'))->searchable(false)->orderable(false),
            Column::make('scan_count')->title(__('common.scan_count'))->searchable(false),
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
        return 'QrCodes_' . date('YmdHis');
    }
}

