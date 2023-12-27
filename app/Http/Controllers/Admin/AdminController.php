<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Branch;
use App\Models\CourierInfo;
use App\Models\CourierPayment;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        $pageTitle = 'Dashboard';
        $branchCount = Branch::count();
        $branchs = Branch::latest()->take(5)->get();
        $courierInfoCount = CourierInfo::count();
        $managerCount = User::where('user_type', 'manager')->count();
        $totalIncome = CourierPayment::sum('amount');
        $userLoginData = UserLogin::where('created_at', '>=', \Carbon\Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);
        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);
        return view('admin.dashboard', compact('pageTitle', 'chart', 'branchCount', 'courierInfoCount', 'managerCount', 'totalIncome', 'branchs'));
    }


    public function branchManager($id)
    {
        $branch  = Branch::findOrFail($id);
        $pageTitle = $branch->name." Manager List";
        $emptyMessage = "No data found";
        $branchManagers = User::where('user_type', 'manager')->where('branch_id', $id)->latest()->with('branch')->paginate(getPaginate());
        return view('admin.manager.index', compact('pageTitle', 'emptyMessage', 'branchManagers'));
    }

    public function branchCourierList($id)
    {
        $branch  = Branch::findOrFail($id);
        $pageTitle = $branch->name." Courier list";
        $emptyMessage = "No data found";
        $courierInfos = CourierInfo::where('sender_branch_id', $id)->orWhere('receiver_branch_id', $id)->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('admin.courier.index', compact('pageTitle', 'emptyMessage', 'courierInfos'));
    }


    public function branchCourierDelivery($id)
    {
        $branch  = Branch::findOrFail($id);
        $pageTitle = $branch->name." Courier delivery list";
        $emptyMessage = "No data found";
        $courierInfos = CourierInfo::where('receiver_branch_id', $id)->where('status', 1)->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('admin.courier.index', compact('pageTitle', 'emptyMessage', 'courierInfos'));
    }


    public function profile()
    {
        $pageTitle = 'Admin Profile';
        $admin = Auth::user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $user = Auth::user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, imagePath()['profile']['admin']['path'], imagePath()['profile']['admin']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = Auth::user();
        return view('admin.password', compact('pageTitle', 'admin'));
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
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return redirect()->route('admin.password')->withNotify($notify);
    }
}
