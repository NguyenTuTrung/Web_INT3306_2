<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Type;
use App\Models\CourierInfo;
Use App\Models\DeliveryCourier;
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
        $time_logs = json_decode($courier->time_logs, true);
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
        $time_logs[] = date('Y-m-d H:i:s');
        $courier->time_logs = json_encode($time_logs);
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

    public function forward(Request $request) 
    {
        $pageTitle = "Courier Forward";
        $delivery_mans = User::where('user_type', 'delivery_man')->where('branch_id', Auth::user()->branch_id)->where('status', 1)->latest()->get();
        $couriers = CourierInfo::where('status', 6)->where('receiver_branch_id', Auth::user()->branch_id)->orderByRaw('receiver_name COLLATE utf8mb4_vietnamese_ci ASC')->with('paymentInfo')->latest()->get();
        return view('staff.courier.forward', compact('pageTitle', 'delivery_mans', 'couriers'));
    }

    public function forward_store(Request $request)
    {
        $request->validate([
            'delivery_man' => 'required|exists:users,id',
            'receiver_name' => 'required|max:40',
            'receiver_email' => 'required|email|max:40',
            'receiver_phone' => 'required|string|max:40',
            'receiver_address' => 'required|max:255',
        ]);
        
        $couriers = CourierInfo::where('status', 6)->where('receiver_branch_id', Auth::user()->branch_id)
        ->where('receiver_name', $request->receiver_name)
        ->where('receiver_email', $request->receiver_email)
        ->where('receiver_phone', $request->receiver_phone)
        ->where('receiver_address', $request->receiver_address)
        ->latest()->get();

        foreach($couriers as $courier)
        {
            $time_logs = json_decode($courier->time_logs, true);
            array_push($time_logs, date('Y-m-d H:i:s'));
            $courier->time_logs = json_encode($time_logs);
            $courier->status = $courier->status + 1;
            $courier->save();

            $delivery_courier = new DeliveryCourier();
            $delivery_courier->user_id = $request->delivery_man;
            $delivery_courier->courier_id = $courier->id;
            $delivery_courier->save();
        }

        $notify[]=['success','Courier forwarded successfully'];
        return redirect()->route('staff.courier.forward')->withNotify($notify);
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
        $courierLists = CourierInfo::where('sender_branch_id', $user->branch_id)->orWhere('receiver_branch_id', $user->branch_id)->orderBy('id', 'DESC')->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch', 'receiverWarehouse')->paginate(getPaginate());
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
        $courierDispatchs = CourierInfo::where('sender_branch_id', $user->branch_id)->orderBy('id', 'DESC')->with('senderBranch','receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch', 'receiverWarehouse')->paginate(getPaginate());
        return view('staff.courier.dispatch', compact('pageTitle', 'emptyMessage', 'courierDispatchs'));
    }

    public function return()
    {
        $user = Auth::user();
        $pageTitle = "Courier Return List";
        $emptyMessage = "No data found";
        $courierReturns = CourierInfo::where('receiver_branch_id', $user->branch_id)->where('status','>',6)->orderBy('id', 'DESC')->with('senderBranch','receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderStaffBranch')->paginate(getPaginate());
        return view('staff.courier.return', compact('pageTitle', 'emptyMessage', 'courierReturns'));
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
        $courier = CourierInfo::where('status', 0)->orWhere('status', 5)->where('code', $request->code)->firstOrFail();
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
        $time_logs = json_decode($courier->time_logs, true);
        array_push($time_logs, date('Y-m-d H:i:s'));
        $courier->time_logs = json_encode($time_logs);
        $courier->receiver_staff_id = $user->id;
        $courier->status = 6;
        $courier->save();

        $notify[] =  ['success', 'Confirm completed'];
        return back()->withNotify($notify);
    }

    public function returnStore(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:delivery_couriers,id'
        ]);
        $user = Auth::user();
        $courier = DeliveryCourier::where('id', $request->code)->where('status', 2)->firstOrFail();
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
            $time_logs = json_decode($courierInfos->time_logs, true);
            $courierInfos->receiver_warehouse_id = Branch::where('id', Auth::user()->branch_id)->latest()->first()->warehouse_id;;
            $courierInfos->status = $courierInfos->status + 1;
            array_push($time_logs, date('Y-m-d H:i:s'));
            $courierInfos->time_logs = json_encode($time_logs);
            $courierInfos->save();
        }

        $notify[]=['success','Courier sent warehouse successfully'];
        return redirect()->route('staff.courier.send.warehouse')->withNotify($notify);
    }

    public function search(Request $request)
    {
        $output = '';
        $couriers = CourierInfo::where('status', 6)
        ->where('receiver_branch_id', Auth::user()->branch_id)
        ->when($request->receiver_name, function ($query, $name) {
            return $query->where('receiver_name', $name);
        })
        ->when($request->receiver_phone, function ($query, $phone) {
            return $query->where('receiver_phone', $phone);
        })
        ->when($request->receiver_email, function ($query, $email) {
            return $query->where('receiver_email', $email);
        })
        ->when($request->receiver_address, function ($query, $address) {
            return $query->where('receiver_address', $address);
        })
        ->latest()->get();
        if ($couriers) {
            foreach ($couriers as $courier) {
                $output .= '<tr>
                    <td>' . $courier->sender_name.'<br>'.$courier->sender_phone.'</td>
                    <td>'.$courier->receiver_name.'<br>'.$courier->receiver_phone.'<br>'.$courier->receiver_email.'</td>
                    <td>'.$courier->code.'</td>
                    <td>'.showDateTime($courier->created_at, 'd M Y').'</td>
                    <td>'.$courier->receiver_address.'</td>
                </tr>';
            }
        }
        
        return response($output);
    }
}
