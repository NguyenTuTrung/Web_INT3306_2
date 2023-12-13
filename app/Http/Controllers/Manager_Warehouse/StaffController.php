<?php

namespace App\Http\Controllers\Manager_Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function create()
    {
        $pageTitle = "Create New Staff";
        return view('manager_warehouse.staff.create', compact('pageTitle'));
    }

    public function index()
    {
        $pageTitle = "Manage Staff";
        $emptyMessage = "No data found";
        $manager_warehouse = Auth::user();
        $staffs = User::where('user_type', 'staff_warehouse')->where('warehouse_id', $manager_warehouse->warehouse_id)->with('warehouse')->latest()->paginate(getPaginate());
        return view('manager_warehouse.staff.index', compact('pageTitle', 'emptyMessage', 'staffs'));
    }

    public function search(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Staff search list";
        $emptyMessage = "No data found";
        $staffs = User::where('user_type', 'staff_warehouse')->where('username', $search)->orWhere('email', $search)->with('warehouse')->paginate(getPaginate());
        return view('manager_warehouse.staff.index', compact('pageTitle', 'emptyMessage', 'staffs', 'search'));
        
    }


    public function store(Request $request)
    {
        $manager_warehouse = Auth::user();
        $request->validate([
            'fname' => 'required|max:40',
            'lname' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users',
            'username' => 'required|max:40|unique:users',
            'mobile' => 'required|max:40|unique:users',
            'password' =>'required|confirmed|min:4',
        ]);

        $staff = new User();
        $staff->warehouse_id = $manager_warehouse->warehouse_id;
        $staff->firstname = $request->fname;
        $staff->lastname = $request->lname;
        $staff->username = $request->username;
        $staff->email = $request->email;
        $staff->mobile = $request->mobile;
        $staff->user_type = "staff_warehouse";
        $staff->password  = Hash::make($request->password);
        $staff->status = $request->status ? 1 : 0;
        $staff->save();
        notify($staff, 'STAFF_CREATE', [
            'username' => $staff->username,
            'email' => $staff->email,
            'password' => $request->password,
        ]);
        $notify[] = ['success', 'Staff has been created'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Staff Update";
        $manager_warehouse = Auth::user();
        $staff = User::where('id', decrypt($id))->where('warehouse_id', $manager_warehouse->warehouse_id)->firstOrFail();
        return view('manager_warehouse.staff.edit', compact('pageTitle', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $manager_warehouse = Auth::user();
        $request->validate([
            'fname' => 'required|max:40',
            'lname' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users,email,'.$id,
            'username' => 'required|max:40|unique:users,username,'.$id,
            'mobile' => 'required|max:40|unique:users,mobile,'.$id,
            'password' =>'nullable|confirmed|min:4',
        ]);
        $staff = User::where('id', $id)->where('warehouse_id', $manager_warehouse->warehouses_id)->firstOrFail();
        $staff->warehouse_id = $manager_warehouse->warehouse_id;
        $staff->firstname = $request->fname;
        $staff->lastname = $request->lname;
        $staff->username = $request->username;
        $staff->email = $request->email;
        $staff->mobile = $request->mobile;
        $staff->user_type = "staff";
        $staff->password  = $request->password ? Hash::make($request->password) : $staff->password;
        $staff->status = $request->status ? 1 : 0;
        $staff->save();
        $notify[] = ['success', 'Staff has been updated'];
        return back()->withNotify($notify);
    }
}
