<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Type;
use DNS1D;
use App\Models\CourierInfo;
use App\Models\User;
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
        $types = Type::where('status', 1)->with('unit')->latest()->get();
        return view('staff.courier', compact('pageTitle', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_name' => 'required|max:40',
            'sender_email' => 'required|email|max:40',
            'sender_phone' => 'required|string|max:40',
            'sender_address' => 'required|max:255', 
            'receiver_name' => 'required|max:40',
            'receiver_email' => 'required|email|max:40',
            'receiver_phone' => 'required|string|max:40',
            'receiver_address' => 'required|max:255',
            'courierName.*' => 'required_with:quantity|exists:types,id',
            'quantity.*' => 'required_with:courierName|integer|gt:0',
            'amount' => 'required|array',
            'amount.*' => 'numeric|gt:0',
        ]);
        $sender = Auth::user();
        $courier = new CourierInfo();
        $courier->invoice_id = getTrx();
        $courier->code = getTrx();
        $courier->sender_branch_id = $sender->branch_id;
        $courier->sender_staff_branch = $sender->id;
        $courier->sender_staff_id = $sender->id;
        $courier->sender_name = $request->sender_name;
        $courier->sender_email = $request->sender_email;
        $courier->sender_phone = $request->sender_phone;
        $courier->sender_address = $request->sender_address;
        $courier->receiver_name = $request->receiver_name;
        $courier->receiver_email = $request->receiver_email;
        $courier->receiver_phone = $request->receiver_phone;
        $courier->receiver_address = $request->receiver_address;
        $courier->receiver_branch_id = $request->branch;
        $courier->status = 0;
        $courier->save();

        $totalAmount = 0;
        for ($i=0; $i <count($request->courierName); $i++) { 
            $courierType = Type::where('id',$request->courierName[$i])->where('status', 1)->firstOrFail();
            $totalAmount += $request->quantity[$i] * $courierType->price;
            $courierProduct = new CourierProduct();
            $courierProduct->courier_info_id = $courier->id;
            $courierProduct->courier_type_id = $courierType->id;
            $courierProduct->qty = $request->quantity[$i];
            $courierProduct->fee = $request->quantity[$i] * $courierType->price;
            $courierProduct->save();
        }
        $courierPayment = new CourierPayment();
        $courierPayment->courier_info_id = $courier->id;
        $courierPayment->amount = $totalAmount;
        $courierPayment->status = 0;
        $courierPayment->save();

        $notify[]=['success','Courier created successfully'];
        return redirect()->route('staff.courier.invoice', encrypt($courier->id))->withNotify($notify);
    }


    public function invoice($id)
    {
        $pageTitle = "Invoice";
        $courierInfo = CourierInfo::where('id', decrypt($id))->first();
        $courierProductInfos = CourierProduct::where('courier_info_id', $courierInfo->id)->with('type')->get();
        $courierPayment = CourierPayment::where('courier_info_id', $courierInfo->id)->first();
        $qrCode = QrCode::size(150)->generate($courierInfo->code);
        return view('staff.invoice', compact('pageTitle', 'courierInfo', 'courierProductInfos', 'courierPayment', 'qrCode'));
    }


    public function manageCourierList()
    {
        $user = Auth::user();
        $pageTitle = "All Courier List";
        $emptyMessage = "No data found";
        $courierLists = CourierInfo::where('sender_branch_id', $user->branch_id)->orWhere('receiver_branch_id', $user->branch_id)->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->paginate(getPaginate());
        return view('staff.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists'));
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
        $courierLists = CourierInfo::where('sender_staff_id', $user->id)->orWhere('receiver_staff_id', $user->id)->whereBetween('created_at', [Carbon::parse($start), Carbon::parse($end)])->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists', 'dateSearch'));
    }

    public function courierSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Courier Search";
        $emptyMessage = "No Data Found";
        $user = Auth::user();
        $courierLists = CourierInfo::where('code', $search)->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff.courier.list', compact('pageTitle', 'emptyMessage', 'courierLists', 'search'));
    }


    public function delivery()
    {
        $user = Auth::user();
        $pageTitle = "Courier Delivery List";
        $emptyMessage = "No data found";
        $courierDeliveys = CourierInfo::where('receiver_branch_id', $user->branch_id)->where('status', 5)->orderBy('id', 'DESC')->with('senderBranch','receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->paginate(getPaginate());
        return view('staff.courier.delivery', compact('pageTitle', 'emptyMessage', 'courierDeliveys'));
    }

    public function dispatching()   
    {
        $user = Auth::user();
        $pageTitle = "Courier Dispatch List";
        $emptyMessage = "No data found";
        $courierDispatchs = CourierInfo::where('sender_branch_id', $user->branch_id)->orderBy('id', 'DESC')->with('senderBranch','receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->paginate(getPaginate());
        return view('staff.courier.dispatch', compact('pageTitle', 'emptyMessage', 'courierDispatchs'));
    }

    public function details($id)
    {
        $pageTitle = "Courier Details";
        $courierInfo = CourierInfo::findOrFail(decrypt($id));
        return view('staff.courier.details', compact('pageTitle','courierInfo'));
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

    public function deliveryStore(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:courier_infos,code'
        ]);
        $user = Auth::user();
        $courier = CourierInfo::where('code', $request->code)->where('status', 5)->firstOrFail();
        $courier->receiver_staff_id = $user->id;
        $courier->status = 6;
        $courier->save();

        $notify[] =  ['success', 'Confirm completed'];
        return back()->withNotify($notify);
    }


    public function cash()
    {
        $user = Auth::user();
        $pageTitle = "Courier Cash Collection";
        $emptyMessage = "No data found";
        $branchIncomeLists = CourierPayment::where('receiver_id', $user->id)
                    ->select(DB::raw("*,SUM(amount) as totalAmount"))
                    ->groupBy('date')->paginate(getPaginate());
        return view('staff.courier.cash', compact('pageTitle', 'emptyMessage', 'branchIncomeLists'));
    }

    public function sendWarehouse()
    {
        $user = Auth::user();
        $pageTitle = "Send Warehouse";
        $emptyMessage = "No data found";
        $courierLists = CourierInfo::where('sender_branch_id', $user->branch_id)->where('status',0)->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff.courier.warehouse', compact('pageTitle', 'emptyMessage', 'courierLists'));
    }

    public function storeWarehouse(Request $request)
    {
        for($i=0; $i<count($request->couriers); $i++)
        {
            $courierInfos = CourierInfo::where('id', $request->couriers[$i])->where('status', 0)->firstOrFail();
            $courierInfos->receiver_warehouse_id = Branch::where('id', Auth::user()->branch_id)->latest()->first()->warehouse_id;;
            $courierInfos->status = $courierInfos->status + 1;
            $courierInfos->save();
        }

        $notify[]=['success','Courier sent warehouse successfully'];
        return redirect()->route('staff.courier.send.warehouse')->withNotify($notify);
    }
}
