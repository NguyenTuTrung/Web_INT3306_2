<?php

namespace App\Http\Controllers\Staff_Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\CourierPayment;
use App\Models\CourierInfo;
use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use App\Lib\GoogleAuthenticator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    
    public function dashboard()
    {
        $user = Auth::user();
        $pageTitle = "Staff Dashboard";
        $emptyMessage = "No data found";
        $courierDeliveys = CourierInfo::with('senderBranch', 'senderWarehouse','receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo')->where('status',1)->orWhere('status', 3)->where('receiver_warehouse_id', $user->warehouse_id)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('staff_warehouse.dashboard', compact('pageTitle', 'courierDeliveys', 'emptyMessage'));
    }

    public function profile()
    {
        $pageTitle = 'Staff Warehouse Profile';
        $staff = Auth::user();
        return view('staff_warehouse.profile', compact('pageTitle', 'staff'));
    }


    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'fname' => 'required|max:40',
            'lname' => 'required|max:40',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, imagePath()['profile']['user']['path'], imagePath()['profile']['user']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $user->firstname = $request->fname;
        $user->lastname = $request->lname;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('staff_warehouse.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $user = Auth::user();
        return view('staff_warehouse.password', compact('pageTitle', 'user'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password do not match !!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return redirect()->route('staff_warehouse.password')->withNotify($notify);
    }

    public function warehouseSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Warehouse search list";
        $emptyMessage = "No data found";
        $warehouses = Warehouse::where('status', 1)->where('name', 'like',"%$search%")->orWhere('email', 'like',"%$search%")->orWhere('address', 'like',"%$search%")->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('staff_warehouse.warehouse.index', compact('pageTitle', 'emptyMessage', 'warehouses', 'search'));
        
    }

    public function warehouseList()
    {
        $pageTitle = "Warehouse List";
        $emptyMessage = "No data found";
        $warehouses = Warehouse::where('status', 1)->latest()->paginate(getPaginate());
        return view('staff_warehouse.warehouse.index', compact('pageTitle', 'emptyMessage', 'warehouses'));
    }

}