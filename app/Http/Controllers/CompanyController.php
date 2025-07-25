<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = $request->user()->companies;
        return response()->json([
            "success" => true,
            "companies" => $companies
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:90|unique:companies,name',
            'address' => 'required|string|min:3|max:190',
            'industry' => 'required|string|max:150|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $company = $request->user()->companies()->create([
            "name" => $request->name,
            "address" => $request->address,
            "industry" => $request->industry,
        ]);
        if ($company) {
            return response()->json([
                "success" => true,
                "message" => "Company Created Succesfully."
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:90|unique:companies,name,' . $id,
            'address' => 'required|string|min:3|max:190',
            'industry' => 'required|string|max:150|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $company = $request->user()->companies()->findOrFail($id);
        $company->update([
            "name" => $request->name,
            "address" => $request->address,
            "industry" => $request->industry,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Company Updated Successfully."
        ], 201);
    }

    public function delete(Request $request, $id)
    {
        $company = $request->user()->companies()->findOrFail($id);
        if ($company->delete()) {
            return response()->json([
                "success" => true,
                "message" => "Company Deleted Successfully."
            ], 201);
        }
    }

    public function switch(Request $request, $id)
    {
        $company = $request->user()->companies()->findOrFail($id);
        if ($company) {
            $request->user()->update([
                "active_company_id" => $company->id,
            ]);

            return response()->json([
                "success" => true,
                "message" => "User Switch to $company->name",
            ]);
        }
    }
}
