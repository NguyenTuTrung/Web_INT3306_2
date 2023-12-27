<?php

namespace App\Http\Controllers\Delivery_Man;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Type;
use DNS1D;
use App\Models\CourierInfo;
use App\Models\DeliveryCourier;
use App\Models\User;
use Carbon\Carbon;
use App\Models\CourierProduct;
use App\Models\CourierPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pageTitle = "Couriers";
        $emptyMessage = "No data found";
        $courierLists = DeliveryCourier::where('user_id', $user->id)->where('status', 0)->with('courierInfo')->paginate(getPaginate());
        return view('delivery_man.courier.index', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function successfulIndex()
    {
        $user = Auth::user();
        $pageTitle = "Couriers";
        $emptyMessage = "No data found";
        $courierLists = DeliveryCourier::where('user_id', $user->id)->where('status', 1)->with('courierInfo')->paginate(getPaginate());
        return view('delivery_man.courier.index', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function missedIndex()
    {
        $user = Auth::user();
        $pageTitle = "Couriers";
        $emptyMessage = "No data found";
        $courierLists = DeliveryCourier::where('user_id', $user->id)->where('status', 2)->with('courierInfo')->paginate(getPaginate());
        return view('delivery_man.courier.index', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function returnedIndex()
    {
        $user = Auth::user();
        $pageTitle = "Couriers";
        $emptyMessage = "No data found";
        $courierLists = DeliveryCourier::where('user_id', $user->id)->where('status', 3)->with('courierInfo')->paginate(getPaginate());
        return view('delivery_man.courier.index', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function details($id)
    {
        $pageTitle = "Courier Details";
        $courierInfo = CourierInfo::findOrFail(decrypt($id));
        return view('delivery_man.courier.details', compact('pageTitle','courierInfo'));
    }

    public function invoice($id)
    {
        $pageTitle = "Invoice";
        $courierInfo = CourierInfo::where('id', decrypt($id))->first();
        $courierProductInfos = CourierProduct::where('courier_info_id', $courierInfo->id)->with('type')->get();
        $courierPayment = CourierPayment::where('courier_info_id', $courierInfo->id)->first();
        $code = '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($courierInfo->code, 'C128') . '" alt="barcode"   />' . "<br>" . $courierInfo->code;
        return view('delivery_man.invoice', compact('pageTitle', 'courierInfo', 'courierProductInfos', 'courierPayment', 'code'));
    }

    public function confirmDone(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:delivery_couriers,id'
        ]);
        $user = Auth::user();
        $courier = DeliveryCourier::where('user_id', $user->id)->where('id', $request->code)->where('status', 0)->firstOrFail();
        $courier->status = $courier->status + 1;
        $courier->save();

        $notify[] =  ['success', 'Confirm completed'];
        return back()->withNotify($notify);
    }

    public function confirmMiss(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:delivery_couriers,id',
            'reason' => 'required'
        ]);
        $user = Auth::user();
        $courier = DeliveryCourier::where('user_id', $user->id)->where('id', $request->code)->where('status', 0)->firstOrFail();
        $courier->status = $courier->status + 2;
        $courier->reasons = $request->reason;
        $courier->save();

        $notify[] =  ['success', 'Confirm completed'];
        return back()->withNotify($notify);
    }
}
