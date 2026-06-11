<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AgentCategoryDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\AgentCategory;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\error;

class AgentCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AgentCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.agent-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return [
            'status' => true,
            'html' => view('admin.agent-categories.form')->render(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $request->only('name','unlock_balance','required_points','massive_order_rate','daily_order_limit','community_bonus_rate','valid_downline','team_a','team_b_c','team_a_profit','team_b_profit','team_c_profit','level_upgrade_income');
        try {
            if($request->hasFile('image')){
                $image = $request->file('image');
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('storage/uploads/agent-categories/'), $imageName);
                $data['image'] = $imageName;
            }
            $category = AgentCategory::create($data);
            if ($category) {
                return response()->json(['status' => true,'message' => 'Agent Category Created Successfully','modal' => true,'code' => 200], 200);
            }
            throw new \Exception("Error Processing Request", 1);
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'messge' => $th->getMessage(),'code' => 400]);
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
        $category = AgentCategory::findOrFail(decrypt($id));
        return [
            'status' => true,
            'html' => view('admin.agent-categories.form', compact('category'))->render(),
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = AgentCategory::findOrFail(decrypt($id));
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $request->only('name','unlock_balance','required_points','massive_order_rate','daily_order_limit','community_bonus_rate','valid_downline','team_a','team_b_c','team_a_profit','team_b_profit','team_c_profit','level_upgrade_income');
        try {
            if($request->hasFile('image')){
                if ($category->image) {
                    $oldImagePath = $category->image_path;
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                $image = $request->file('image');
                $imageName = time().'.'.$image->extension();
                $image->move(public_path('storage/uploads/agent-categories/'), $imageName);
                $data['image'] = $imageName;
            }
            if ($category->update($data)) {
                return response()->json(['status' => true,'modal' => true,'message' => 'Agent Category updated Successfully','code' => 200], 200);
            }
            throw new \Exception("Error Processing Request", 1);
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'messge' => $th->getMessage(),'code' => 400]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = AgentCategory::findOrFail(decrypt($id));
        try {
            if ($category->delete()) {
                return response()->json(['status' => true,'message' => 'Agent Category deleted Successfully','code' => 200], 200);
            }
            throw new \Exception("Error Processing Request", 1);
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'messge' => $th->getMessage(),'code' => 400]);
        }
    }
}
