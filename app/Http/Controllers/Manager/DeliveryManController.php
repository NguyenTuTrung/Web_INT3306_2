<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeliveryManController extends Controller
{
    public function create()
    {
        $pageTitle = "Create New Delivery Man";
        return view('manager.delivery_man.create', compact('pageTitle'));
    }

    public function index()
    {
        $pageTitle = "Manage Delivery Man";
        $emptyMessage = "No data found";
        $manager = Auth::user();
        $delivery_mans = User::where('user_type', 'delivery_man')->where('branch_id', $manager->branch_id)->with('branch')->latest()->paginate(getPaginate());
        return view('manager.delivery_man.index', compact('pageTitle', 'emptyMessage', 'delivery_mans'));
    }

    public function search(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Delivery Man search list";
        $emptyMessage = "No data found";
        $delivery_mans = User::where('user_type', 'delivery_man')->where('username', $search)->orWhere('email', $search)->with('branch')->paginate(getPaginate());
        return view('manager.delivery_man.index', compact('pageTitle', 'emptyMessage', 'delivery_mans', 'search'));
    }

    public function store(Request $request)
    {
        $manager = Auth::user();
        $request->validate([
            'fname' => 'required|max:40',
            'lname' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users',
            'username' => 'required|max:40|unique:users',
            'mobile' => 'required|max:40|unique:users',
            'password' =>'required|confirmed|min:4',
        ]);

        $delivery_man = new User();
        $delivery_man->branch_id = $manager->branch_id;
        $delivery_man->firstname = $request->fname;
        $delivery_man->lastname = $request->lname;
        $delivery_man->username = $request->username;
        $delivery_man->email = $request->email;
        $delivery_man->mobile = $request->mobile;
        $delivery_man->user_type = "delivery_man";
        $delivery_man->password  = Hash::make($request->password);
        $delivery_man->status = $request->status ? 1 : 0;
        $delivery_man->save();
        notify($delivery_man, 'DELIVERY_MAN_CREATE', [
            'username' => $delivery_man->username,
            'email' => $delivery_man->email,
            'password' => $request->password,
        ]);
        $notify[] = ['success', 'Delivery Man has been created'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Delivery Man Update";
        $manager = Auth::user();
        $delivery_man = User::where('id', decrypt($id))->where('branch_id', $manager->branch_id)->firstOrFail();
        return view('manager.delivery_man.edit', compact('pageTitle', 'delivery_man'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $manager = Auth::user();
        $request->validate([
            'fname' => 'required|max:40',
            'lname' => 'required|max:40',
            'email' => 'required|email|max:40|unique:users,email,'.$id,
            'username' => 'required|max:40|unique:users,username,'.$id,
            'mobile' => 'required|max:40|unique:users,mobile,'.$id,
            'password' =>'nullable|confirmed|min:4',
        ]);
        $delivery_man = User::where('id', $id)->where('branch_id', $manager->branch_id)->firstOrFail();
        $delivery_man->branch_id = $manager->branch_id;
        $delivery_man->firstname = $request->fname;
        $delivery_man->lastname = $request->lname;
        $delivery_man->username = $request->username;
        $delivery_man->email = $request->email;
        $delivery_man->mobile = $request->mobile;
        $delivery_man->user_type = "delivery_man";
        $delivery_man->password  = $request->password ? Hash::make($request->password) : $delivery_man->password;
        $delivery_man->status = $request->status ? 1 : 0;
        $delivery_man->save();
        $notify[] = ['success', 'Delivery Man has been updated'];
        return back()->withNotify($notify);
    }
}