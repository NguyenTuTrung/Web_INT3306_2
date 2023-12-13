<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WarehouseManagerController extends Controller
{
    public function index()
    {
        $pageTitle = "All Warehouse Manager";
        $emptyMessage = "No data found";
        $warehouseManagers = User::where('user_type', 'manager_warehouse')->latest()->with('warehouse')->paginate(getPaginate());
        return view('admin.manager_warehouse.index', compact('pageTitle', 'emptyMessage', 'warehouseManagers'));
    }

    public function create()
    {
        $pageTitle = "Add New Warehouse Manager";
        $warehouses = Warehouse::select('name', 'id')->where('status', 1)->latest()->get();
        return view('admin.manager_warehouse.create', compact('pageTitle', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse' => 'required|exists:warehouses,id',
            'fname' => 'required|max:40',
            'lname' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users',
            'username' => 'required|max:40|unique:users',
            'mobile' => 'required|max:40|unique:users',
            'password' =>'required|confirmed|min:4',
        ]);
        $manager_warehouse = new User();
        $manager_warehouse->warehouse_id = $request->warehouse;
        $manager_warehouse->firstname = $request->fname;
        $manager_warehouse->lastname = $request->lname;
        $manager_warehouse->username = trim($request->username);
        $manager_warehouse->email = trim($request->email);
        $manager_warehouse->mobile = $request->mobile;
        $manager_warehouse->user_type = "manager_warehouse";
        $manager_warehouse->status = $request->status ? 1 : 0;
        $manager_warehouse->password  = Hash::make($request->password);
        $manager_warehouse->save();
        $notify[] = ['success', 'Manager has been created'];
        notify($manager_warehouse, 'MANAGER_CREATE', [
            'username' => $manager_warehouse->username,
            'email' => $manager_warehouse->email,
            'password' => $request->password,
        ]);
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Update Warehouse Manager";
        $warehouses = Warehouse::select('name', 'id')->where('status', 1)->latest()->get();
        $manager_warehouse = User::findOrFail($id);
        return view('admin.manager_warehouse.edit', compact('pageTitle', 'warehouses', 'manager_warehouse'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'warehouse' => 'required|exists:warehouses,id',
            'fname' => 'required|max:40',
            'lname' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users,email,'.$id,
            'username' => 'required|max:40|unique:users,username,'.$id,
            'mobile' => 'required|max:40|unique:users,mobile,'.$id,
            'password' =>'nullable|confirmed|min:4',
        ]);
        $manager_warehouse =User::findOrFail($id);
        $manager_warehouse->warehouse_id = $request->warehouse;
        $manager_warehouse->firstname = $request->fname;
        $manager_warehouse->lastname = $request->lname;
        $manager_warehouse->username = $request->username;
        $manager_warehouse->email = $request->email;
        $manager_warehouse->mobile = $request->mobile;
        $manager_warehouse->status = $request->status ? 1 : 0;
        $manager_warehouse->user_type = "manager_warehouse";
        $manager_warehouse->password  = $request->password ? Hash::make($request->password) : $manager_warehouse->password;
        $manager_warehouse->save();
        $notify[] = ['success', 'Manager has been updated'];
        return back()->withNotify($notify);
    }

    public function staffList($warehouseId)
    {
        $pageTitle = "Staff List";
        $emptyMessage = "No data found";
        $staffs_warehouse = User::where('user_type', 'staff_warehouse')->where('warehouse_id', $warehouseId)->with('warehouse')->paginate(getPaginate());
        return view('admin.manager_warehouse.staff', compact('pageTitle', 'emptyMessage', 'staffs_warehouse'));
    }
}