<?php

namespace App\Http\Controllers\Staff_warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Models\Type;
use DNS1D;
use App\Models\CourierInfo;
use Carbon\Carbon;
use App\Models\CourierProduct;
use App\Models\CourierPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CourierController extends Controller
{
    public function create()
    {
        $pageTitle = "Courier Send";
        $emptyMessage = "No data found";
        $warehouses = Warehouse::where('status', 1)->latest()->get();
        $branchs = Branch::where('status', 1)->latest()->get();
        $courierLists = CourierInfo::where('status', 2)->orWhere('status', 4)->where('receiver_warehouse_id', Auth::user()->warehouse_id)->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderWarehouse')->paginate(getPaginate());
        return view('staff_warehouse.courier', compact('pageTitle', 'emptyMessage', 'warehouses', 'branchs', 'courierLists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch' => 'required',
            'warehouse' => 'required',
            'couriers' => 'required',
        ]);
        $sender = Auth::user();
        
        for ($i=0; $i <count($request->couriers); $i++) { 
            $courierInfos = CourierInfo::where('status', 2)->orWhere('status', 4)->where('id', $request->couriers[$i])->firstOrFail();
            $time_logs = json_decode($courierInfos->time_logs, true);
            array_push($time_logs, date('Y-m-d H:i:s'));
            $courierInfos->time_logs = json_encode($time_logs);
            $courierInfos->receiver_branch_id = $request->branch;
            $courierInfos->receiver_warehouse_id = $request->warehouse;
            $courierInfos->sender_staff_id = $sender->id;
            $courierInfos->sender_warehouse_id = $sender->warehouse_id;
            $courierInfos->status = $courierInfos->status + 1;
            $courierInfos->save();
        }

        $notify[]=['success','Courier sent successfully'];
        return redirect()->route('staff_warehouse.courier.create')->withNotify($notify);
    }

    public function manageCourierList()
    {
        $user = Auth::user();
        $pageTitle = "All Courier List";
        $emptyMessage = "No data found";
        $courierLists = CourierInfo::Where('status', 2)->orWhere('status', 4)->where('sender_warehouse_id', $user->warehouse_id)->orWhere('receiver_warehouse_id', $user->warehouse_id)->orderBy('id', 'DESC')->with('senderBranch', 'senderWarehouse', 'receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->paginate(getPaginate());
        return view('staff_warehouse.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function details($id)
    {
        $pageTitle = "Courier Details";
        $courierInfo = CourierInfo::findOrFail(decrypt($id));
        return view('staff_warehouse.courier.details', compact('pageTitle','courierInfo'));
    }

    public function invoice($id)
    {
        $pageTitle = "Invoice";
        $courierInfo = CourierInfo::where('id', decrypt($id))->first();
        $courierProductInfos = CourierProduct::where('courier_info_id', $courierInfo->id)->with('type')->get();
        $courierPayment = CourierPayment::where('courier_info_id', $courierInfo->id)->first();
        $qrCode = QrCode::size(150)->generate($courierInfo->code);
        return view('staff_warehouse.invoice', compact('pageTitle', 'courierInfo', 'courierProductInfos', 'courierPayment', 'qrCode'));
    }

    public function payment(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:courier_infos,code'
        ]);
        $user = Auth::user();
        $courier = CourierInfo::where('code', $request->code)->where('status', 0)->firstOrFail();
        $courierPayment = CourierPayment::where('courier_info_id', $courier->id)->where('status', 0)->firstOrFail();
        $courierPayment->receiver_id = $user->id;
        $courierPayment->branch_id = $user->branch_id;
        $courierPayment->date = Carbon::now();
        $courierPayment->status = 1;
        $courierPayment->save();

        $notify[] =  ['success', 'Payment completed'];
        return back()->withNotify($notify);
    }

    public function delivery()
    {
        $user = Auth::user();
        $pageTitle = "Courier Delivery List";
        $emptyMessage = "No data found";
        $courierDeliveys = CourierInfo::with('senderBranch', 'senderWarehouse','receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo', 'user')->where('status',1)->orWhere('status', 3)->where('receiver_warehouse_id', $user->warehouse_id)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('staff_warehouse.courier.delivery', compact('pageTitle', 'emptyMessage', 'courierDeliveys'));
    }

    public function confirmStore(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:courier_infos,code'
        ]);
        $user = Auth::user();
        $courier = CourierInfo::where('status', 1)->orWhere('status', 3)->where('code', $request->code)->firstOrFail();
        $time_logs = json_decode($courier->time_logs, true);
        array_push($time_logs, date('Y-m-d H:i:s'));
        $courier->time_logs = json_encode($time_logs);
        $courier->receiver_staff_id = $user->id;
        $courier->status = $courier->status + 1;
        $courier->save();

        $notify[] =  ['success', 'Confirm completed'];
        return back()->withNotify($notify);
    }

    public function cash()
    {
        $user = Auth::user();
        $pageTitle = "Courier Cash Collection";
        $emptyMessage = "No data found";
        $warehouseIncomeLists = CourierPayment::where('receiver_id', $user->id)
                    ->select(DB::raw("*,SUM(amount) as totalAmount"))
                    ->groupBy('date')->paginate(getPaginate());
        return view('staff_warehouse.courier.cash', compact('pageTitle', 'emptyMessage', 'warehouseIncomeLists'));
    }

    public function courierDateSearch(Request $request)
    {
        $user = Auth::user();
        $search = $request->date;
        if (!$search) {
            return back();
        }
        $date = explode('-',$search);
        $start = @$date[0];
        $end = @$date[1];
        $pattern = "/\d{2}\/\d{2}\/\d{4}/";
        if ($start && !preg_match($pattern,$start)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.courier.info.index')->withNotify($notify);
        }
        if ($end && !preg_match($pattern,$end)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.courier.info.index')->withNotify($notify);
        }
        $pageTitle = "Courier Search";
        $dateSearch = $search;
        $emptyMessage = "No data found";
        $courierLists = CourierInfo::where('sender_staff_id', $user->id)->orWhere('receiver_staff_id', $user->id)->whereBetween('created_at', [Carbon::parse($start), Carbon::parse($end)])->orderBy('id', 'DESC')->with('senderBranch', 'senderWarehouse', 'receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff_warehouse.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists', 'dateSearch'));
    }

    public function courierSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Courier Search";
        $emptyMessage = "No Data Found";
        $user = Auth::user();
        $courierLists = CourierInfo::where('code', $search)->with('senderBranch', 'senderWarehouse', 'receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff_warehouse.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists', 'search'));
    }
}