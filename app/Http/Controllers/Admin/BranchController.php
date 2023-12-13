<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Warehouse;

class BranchController extends Controller
{
    
    public function index()
    {
        $pageTitle = "Manage Branch";
        $emptyMessage = "No data found";
        $branchs = Branch::latest()->with('warehouse')->paginate(getPaginate());;
        $warehouses = Warehouse::where('status', 1)->latest()->get();
        return view('admin.branch.index', compact('pageTitle', 'emptyMessage', 'branchs', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|unique:branches',
            'email' => 'required|email|max:40|unique:branches',
            'phone' => 'required|max:40|unique:branches',
            'address' => 'required|max:255',
            'warehouse' => 'required|exists:warehouses,id'
        ]);
        $branch = new Branch();
        $branch->warehouse_id = $request->warehouse;
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->phone = $request->phone;
        $branch->address = $request->address;
        $branch->status = $request->status ? 1: 0;
        $branch->save();
        $notify[] = ['success', 'Branch has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|unique:branches,name,'.$request->id,
            'email' => 'required|email|max:40|unique:branches,email,'.$request->id,
            'phone' => 'required|max:40|unique:branches,phone,'.$request->id,
            'address' => 'required|max:255',
            'warehouse' => 'required|exists:warehouses,id'
        ]);
        $branch = Branch::findOrFail($request->id);
        $branch->warehouse_id = $request->warehouse;
        $branch->name = $request->name;
        $branch->email = $request->email;
        $branch->phone = $request->phone;
        $branch->address = $request->address;
        $branch->status = $request->status ? 1: 0;
        $branch->save();
        $notify[] = ['success', 'Branch has been updated'];
        return back()->withNotify($notify);
    }
}
