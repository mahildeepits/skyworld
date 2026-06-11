<?php

namespace App\DataTables;

use App\Models\UserPayout;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserPayoutsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            // ->addColumn('action', 'user-payouts.action')
            ->editColumn('created_at',function($query){
                return $query->created_at->toDayDateTimeString() ?? '';
            })
            ->editColumn('income_type',function($query){
                return ucwords($query->income_type) ?? '';
            })
            ->editColumn('amount',function($query){
                return '$'. $query->amount ?? '';
            })
            ->editColumn('tds',function($query){
                return '$'. $query->tds ?? '';
            })
            ->editColumn('admin_charges',function($query){
                return '$'. $query->admin_charges ?? '';
            })
            ->editColumn('net_amount',function($query){
                return '$'. $query->net_amount ?? '';
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UserPayout $model): QueryBuilder
    {
        $query = $model->newQuery();
        $user_id =  $this->user_id ?? null;
        $type = $this->type;
        $from_date = request()->get('from') ?? null;
        $to_date = request()->get('to') ?? null;
        $filter_type = request()->get('type') ?? null;
        $status = request()->get('status') ?? null;
        $income_type = request()->get('income_type') ?? null;
        $query->when(($type == 'requested' || $filter_type == 'requested'),function($query){
            return $query->whereNotNull('is_requested');
        })->when($type == 'not_requested',function($query){
            return $query->whereNull('is_requested');
        })->when($user_id, function($query) use($user_id){
            return $query->where('user_id',$user_id);
        })->when(($status == 'paid'),function($query){
            return $query->whereNotNull('is_paid')->whereNotNull('transaction_id');
        })->when(($status == 'not_paid'),function($query){
            return $query->whereNull('is_paid')->whereNull('transaction_id');
        })->when($income_type,function($query) use($income_type){
            return $query->where('income_type',$income_type);
        })->when($from_date,function($query) use($from_date,$to_date){
            return $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        });
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('userpayouts-table')
                    ->columns($this->getColumns())
                    // ->minifiedAjax()
                    // //->dom('Bfrtip')
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
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            Column::make('id'),
            Column::make('income_type'),
            Column::make('amount'),
            Column::make('tds'),
            Column::make('admin_charges'),
            Column::make('net_amount'),
            Column::make('created_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UserPayouts_' . date('YmdHis');
    }
}
