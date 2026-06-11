<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TasksDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Throwable;
use Validator;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TasksDataTable $dataTable)
    {
        return $dataTable->render('admin.tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return [
            'status' => true,
            'html' => view('admin.tasks.form')->render(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' =>'required',
            'title_description' => 'required',
            'product_type' => 'required',
            'order_number' => 'required',
            'order_date' => 'required',
            'reviews' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ],422);
        }
        $data = $request->only('title','title_description','product_type','order_number','order_date','reviews');
        try {
            $task = Task::create($data);
            if($task){
                return response()->json(['status' => true,'message' => 'Task Created Successfully','modal' => true,'code' => 200]);
            }
            throw new \Exception("Error Processing Request", 1);
        }catch(Throwable $th){
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail(decrypt($id));
        return [
            'status' => true,
            'html' => view('admin.tasks.form',compact('task'))->render(),
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail(decrypt($id));
        $validator = Validator::make($request->all(), [
            'title' =>'required',
            'title_description' => 'required',
            'product_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ],422);
        }
        $data = $request->only('title','title_description','product_type');
        try {
            if($task->update($data)){
                return response()->json(['status' => true,'message' => 'Task Updated Successfully','modal' => true,'code' => 200]);
            }
            throw new \Exception("Error Processing Request", 1);
        }catch(Throwable $th){
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail(decrypt($id));
        try {
            if($task->delete()){
                return response()->json(['status' => true,'message' => 'Task Deleted Successfully','code' => 200]);
            }
            throw new \Exception("Error Processing Request", 1);
        }catch(Throwable $th){
            return response()->json(['status' => false,'message' => $th->getMessage(),'code' => 400]);
        }
    }
}
