<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'admin.user-payouts.action')
            ->editColumn('user_id',function($query){
                return $query?->user?->member_id ?? '';
            })
            ->editColumn('amount',function($query){
                return (number_format($query?->amount,2) ?? '');
            })
            ->editColumn('transaction_fees',function($query){
                return (number_format($query?->transaction_fees,2) ?? '');
            })
            ->editColumn('net_amount',function($query){
                return (number_format($query?->net_amount,2) ?? '0');
            })
            ->editColumn('status',function($query){
                if($query?->status == 'success'){
                    return '<span class="bg-success text-white px-2 py-1 rounded">Success</span>';
                }else if($query?->status == 'Rejected'){
                    return '<span class="bg-danger text-white  px-2 py-1 rounded">Rejected</span>';
                }else{
                    return '<span class="bg-warning text-dark  px-2 py-1 rounded">Pending</span>';
                }
            })

            ->editColumn('created_at',function($query){
                return $query?->created_at->toDayDateTimeString() ?? '';
            })
            ->rawColumns(['action','status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Transaction $model): QueryBuilder
    {
        $query = $model->newQuery();
        $query->where('type','withdrawl');

        if (request()->filled('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        }
        if (request()->filled('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('transaction-table')
                    ->columns($this->getColumns())
                    // ->minifiedAjax()
                    //->dom('Bfrtip')
                    // ->orderBy(1)
                    // ->selectStyleSingle()
                    // ->buttons([
                    //     Button::make('excel'),
                    //     Button::make('csv'),
                    //     Button::make('pdf'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // ])
                    ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            // Column::make('id'),
            Column::make('user_id')->title('User'),
            Column::make('wallet_address')->title('Bank Details'),
            Column::make('amount'),
            Column::make('transaction_fees'),
            Column::make('net_amount'),
            Column::make('status'),
            Column::make('transaction_hash'),
            Column::make('created_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
