<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
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
        $branchCount = Branch::where('status', 1)->count();
        $sendCourierCount = CourierInfo::where('sender_staff_branch', $user->id)->count();
        $receivedCourierCount = CourierInfo::where('receiver_staff_id', $user->id)->count();
        $cashCollection = CourierPayment::where('receiver_id', $user->id)->sum('amount');
        $courierDeliveys = CourierInfo::where('receiver_branch_id', $user->branch_id)->orderBy('id', 'DESC')->with('senderBranch','receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->take(5)->get();
        return view('staff.dashboard', compact('pageTitle', 'branchCount', 'sendCourierCount', 'receivedCourierCount', 'cashCollection', 'courierDeliveys', 'emptyMessage'));
    }

    public function sendCourierList()
    {
        $user = Auth::user();
        $pageTitle = "Courier Dispatch List";
        $emptyMessage = "No data found";
        $courierLists = CourierInfo::where('sender_branch_id', $user->branch_id)->orderBy('id', 'DESC')->with('senderBranch','receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->paginate(getPaginate());
        return view('staff.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function receivedCourierList()
    {
        $user = Auth::user();
        $pageTitle = "Received Courier List";
        $emptyMessage = "No Data Found";
        $courierLists = CourierInfo::where('receiver_staff_id', $user->id)->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->paginate(getPaginate());
        return view('staff.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function branchList()
    {
        $pageTitle = "Branch List";
        $emptyMessage = "No data found";
        $branchs = Branch::where('status', 1)->latest()->paginate(getPaginate());
        return view('staff.branch.index', compact('pageTitle', 'emptyMessage', 'branchs'));
    }

    public function branchSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Branch search list";
        $emptyMessage = "No data found";
        $branchs = Branch::where('status', 1)->where('name', 'like',"%$search%")->orWhere('email', 'like',"%$search%")->orWhere('address', 'like',"%$search%")->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('staff.branch.index', compact('pageTitle', 'emptyMessage', 'branchs', 'search'));
        
    }

    public function profile()
    {
        $pageTitle = "Staff Profile";
        $staff = Auth::user();
        return view('staff.profile', compact('pageTitle', 'staff'));
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
        return redirect()->route('staff.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $user = Auth::user();
        return view('staff.password', compact('pageTitle', 'user'));
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
        return redirect()->route('staff.password')->withNotify($notify);
    }
}
