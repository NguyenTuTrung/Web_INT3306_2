<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Warehouse';
        $emptyMessage = "No data found";
        $warehouses = Warehouse::latest()->paginate(getPaginate());
        return view('admin.warehouse.index', compact('pageTitle', 'emptyMessage', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|unique:branches',
            'email' => 'required|email|max:40|unique:branches',
            'phone' => 'required|max:40|unique:branches',
            'address' => 'required|max:255',
        ]);
        
        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->status = $request->status ? 1: 0;
        $warehouse->save();
        $notify[] = ['success', 'Warehouse has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40|unique:branches,name,'.$request->id,
            'email' => 'required|email|max:40|unique:branches,email,'.$request->id,
            'phone' => 'required|max:40|unique:branches,phone,'.$request->id,
            'address' => 'required|max:255',
        ]);
        $warehouse = Warehouse::findOrFail($request->id);
        $warehouse->name = $request->name;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->status = $request->status ? 1: 0;
        $warehouse->save();
        $notify[] = ['success', 'Warehouse has been updated'];
        return back()->withNotify($notify);
    }
}