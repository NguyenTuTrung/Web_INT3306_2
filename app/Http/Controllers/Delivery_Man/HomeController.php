<?php

namespace App\Http\Controllers\Delivery_Man;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\CourierPayment;
use App\Models\CourierInfo;
use App\Models\DeliveryCourier;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    
    public function dashboard()
    {
        $user = Auth::user();
        $pageTitle = "Delivery Man Dashboard";
        $emptyMessage = "No data found";
        $shipmentCount = DeliveryCourier::where('user_id', $user->id)->where('status', 0)->count();
        $successfullCouriers = DeliveryCourier::where('user_id', $user->id)->where('status', 1)->count();
        $missionCount = DeliveryCourier::where('user_id', $user->id)->where('status', 2)->count();
        $returnsCount = DeliveryCourier::where('user_id', $user->id)->where('status', 3)->count();
        $courierLists = DeliveryCourier::where('user_id', $user->id)->with('courierInfo')->paginate(getPaginate());
        return view('delivery_man.dashboard', compact('pageTitle', 'emptyMessage', 'shipmentCount', 'successfullCouriers', 'missionCount', 'returnsCount', 'courierLists'));
    }

    public function profile()
    {
        $pageTitle = "Delivery Man Profile";
        $staff = Auth::user();
        return view('delivery_man.profile', compact('pageTitle', 'staff'));
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
        return redirect()->route('delivery_man.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $user = Auth::user();
        return view('delivery_man.password', compact('pageTitle', 'user'));
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
        return redirect()->route('delivery_man.password')->withNotify($notify);
    }
}
