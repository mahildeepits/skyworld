<?php

namespace App\DataTables;

use App\Models\AgentCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AgentCategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'admin.agent-categories.action')
            ->editColumn('name', function ($query){
                return ucwords($query->name) ?? '';
            })
            ->editColumn('image', function ($query){
                if($query->image != null){
                    return "<img src='".asset($query->image_path)."' width='40px' alt='image'>";
                }
                return '';
            })
            ->editColumn('unlock_balance', function ($query){
                return '$ '. $query->unlock_balance ?? '';
            })
            ->editColumn('massive_order_rate', function ($query){
                return $query->massive_order_rate != null? $query->massive_order_rate.' % ' : '';
            })
            ->editColumn('created_at', function ($query){
                return $query->created_at->toDayDateTimeString() ?? '';
            })
            ->rawColumns(['action', 'image'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(AgentCategory $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('agentcategory-table')
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
            Column::make('id'),
            Column::make('name'),
            Column::make('image'),
            Column::make('unlock_balance')->title('Wallet Balance'),
            Column::make('massive_order_rate')->title('ROI (%)'),
            Column::make('team_a')->title('Team A'),
            Column::make('team_b_c')->title('Team B & C'),
            Column::make('created_at'),
            // Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AgentCategory_' . date('YmdHis');
    }
}
