<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $activeCompanyId = $request->user()->activeCompany->id;
        $projects = Project::where("company_id", $activeCompanyId)->get();
        return response()->json([
            "success" => true,
            "projects" => $projects
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:150|unique:projects,name',
            'desc' => 'required|string|min:3|max:20000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::create([
            "name" => $request->name,
            "desc" => $request->desc,
            "company_id" => $request->user()->activeCompany->id,
        ]);
        if ($project) {
            return response()->json([
                "success" => true,
                "message" => "Project Created Succesfully."
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:150|unique:projects,name,' . $id,
            'desc' => 'required|string|min:3|max:20000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $activeCompanyId = $request->user()->activeCompany->id;
        $project = Project::where("company_id", $activeCompanyId)->findOrFail($id);
        $project->name = $request->name;
        $project->desc = $request->desc;
        $project->company_id = $activeCompanyId;
        if ($project->save()) {
            return response()->json([
                "success" => true,
                "message" => "Project Created Succesfully.",
            ]);
        }
    }

    public function delete(Request $request, $id)
    {
        $activeCompanyId = $request->user()->activeCompany->id;
        $project = Project::where("company_id", $activeCompanyId)->findOrFail($id);
        if ($project->delete()) {
            return response()->json([
                "success" => true,
                "message" => "Project Deleted Successfully.",
                "cative"=> $activeCompanyId,
            ]);
        }
    }
}
