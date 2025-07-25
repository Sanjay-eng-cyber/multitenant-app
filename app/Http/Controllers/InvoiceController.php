<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $activeCompanyId = $request->user()->activeCompany->id;
        $invoices = Invoice::where("company_id", $activeCompanyId)->get();
        return response()->json([
            "success" => true,
            "invoices" => $invoices
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:3000|max:99999999.99',
            'issue_date' => 'required|date_format:Y-m-d',
            'due_date' => 'nullable|date_format:Y-m-d',
            'status' => 'in:unpaid,paid,overdue',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $activeCompanyId = $request->user()->activeCompany->id;

        // Ensure project belongs to active company
        $project = Project::where("id", $request->project_id)
            ->where("company_id", $activeCompanyId)
            ->first();

        if (!$project) {
            return response()->json([
                "success" => false,
                "message" => "Project does not belong to your company or active company."
            ], 404);
        }

        // Create invoice
        $invoice = new Invoice();
        $invoice->project_id = $project->id;
        $invoice->company_id = $activeCompanyId;
        $invoice->amount = $request->amount;
        $invoice->issue_date = $request->issue_date;
        $invoice->due_date = $request->due_date ?? null;
        $invoice->status = $request->status ?? 'unpaid';

        if ($invoice->save()) {
            return response()->json([
                "success" => true,
                "message" => "Invoice created successfully.",
                "data" => $invoice
            ], 201);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong while saving the invoice."
        ], 500);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:3000|max:99999999.99',
            'issue_date' => 'required|date_format:Y-m-d',
            'due_date' => 'nullable|date_format:Y-m-d',
            'status' => 'in:unpaid,paid,overdue',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $activeCompanyId = $request->user()->activeCompany->id;

        $project = Project::where('id', $request->project_id)
            ->where('company_id', $activeCompanyId)
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project does not belong to your company Or active company.'
            ], 404);
        }

        $invoice = Invoice::where('id', $id)
            ->where('company_id', $activeCompanyId)
            ->first();

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found or Un Active Company.'
            ], 404);
        }

        $invoice->update([
            'amount' => $request->amount,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date ?? null,
            'status' => $request->status ?? 'unpaid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully.'
        ]);
    }


    public function delete(Request $request, $id)
    {
        $activeCompanyId = $request->user()->activeCompany->id;
        $invoice = Invoice::where('id', $id)
            ->where('company_id', $activeCompanyId)
            ->first();
        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found or Un-Active Company.'
            ], 404);
        }
        if ($invoice->delete()) {
            return response()->json([
                "success" => true,
                "message" => "Invoice Deleted Successfully.",
            ], 201);
        }
    }
}
