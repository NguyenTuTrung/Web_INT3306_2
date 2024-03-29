<?php 

namespace App\Http\Controllers\Manager_Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Models\Warehouse;
use App\Models\CourierInfo;
use App\Models\GeneralSetting;
use App\Models\CourierProduct;
use App\Models\CourierPayment;
use App\Models\User;
use Carbon\Carbon;
use App\Lib\GoogleAuthenticator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HomeController extends Controller
{
    public function dashboard()
    {
        $manager_warehouse = Auth::user();
        $pageTitle = "Manager Warehouse Dashboard";
        $emptyMessage = "No data found";
        $warehouseListCount = Warehouse::where('status', 1)->count();
        $totalStaffCount =  User::where('user_type', 'staff_warehouse')->where('warehouse_id', $manager_warehouse->warehouse_id)->count();
        $courierInfoCount = CourierInfo::where('receiver_warehouse_id', $manager_warehouse->warehouse_id)->orWhere('sender_warehouse_id', $manager_warehouse->warehouse_id)->count();
        $branchCount = Branch::where('warehouse_id', $manager_warehouse->warehouse_id)->count();
        $courierInfos = CourierInfo::where('receiver_warehouse_id', $manager_warehouse->warehouse_id)->orWhere('sender_warehouse_id', $manager_warehouse->warehouse_id)->orderBy('id', 'DESC')->take(5)->with('senderBranch', 'receiverBranch', 'senderStaff', 'receiverStaff', 'paymentInfo', 'senderWarehouse')->get();
        return view('manager_warehouse.dashboard', compact('pageTitle', 'warehouseListCount', 'totalStaffCount', 'courierInfoCount', 'branchCount', 'courierInfos', 'emptyMessage'));
    }

    public function warehouseList()
    {
        $pageTitle = "Warehouse list";
        $emptyMessage = "No data found";
        $warehouses = Warehouse::where('status', 1)->latest()->paginate(getPaginate());
        return view('manager_warehouse.warehouse.index', compact('pageTitle', 'emptyMessage', 'warehouses'));
    }

    public function warehouseSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Warehouse search list";
        $emptyMessage = "No data found";
        $warehouses = Warehouse::where('status', 1)->where('name', 'like',"%$search%")->orWhere('email', 'like',"%$search%")->orWhere('address', 'like',"%$search%")->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('manager_warehouse.warehouse.index', compact('pageTitle', 'emptyMessage', 'warehouses', 'search'));
        
    }

    public function branchList()
    {
        $manager_warehouse = Auth::user();
        $pageTitle = "Branch list";
        $emptyMessage = "No data found";
        $branchs = Branch::where('status', 1)->where('warehouse_id', $manager_warehouse->warehouse_id)->latest()->paginate(getPaginate());
        return view('manager_warehouse.branch.index', compact('pageTitle', 'emptyMessage', 'branchs'));
    }

    public function branchSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $manager_warehouse = Auth::user();
        $search = $request->search;
        $pageTitle = "Branch search list";
        $emptyMessage = "No data found";
        $branchs = Branch::where('status', 1)->where('warehouse_id', $manager_warehouse->warehouse_id)->where('name', 'like',"%$search%")->orWhere('email', 'like',"%$search%")->orWhere('address', 'like',"%$search%")->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('manager_warehouse.branch.index', compact('pageTitle', 'emptyMessage', 'branchs', 'search'));    
    }

    public function courierInfo()
    {
        $user = Auth::user();
        $pageTitle = "All Courier List";
        $emptyMessage = "No data found";
        $courierInfos = CourierInfo::where('sender_warehouse_id', $user->warehouse_id)->orWhere('receiver_warehouse_id', $user->warehouse_id)->orderBy('id', 'DESC')->with('senderWarehouse', 'receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo', 'receiverBranch')->paginate(getPaginate());
        return view('manager_warehouse.courier.index', compact('pageTitle', 'emptyMessage', 'courierInfos'));
    }

    public function sendCourier()
    {
        $user = Auth::user();
        $pageTitle = "Dispatch Courier List";
        $emptyMessage = "No data found";
        $courierInfos = CourierInfo::where('sender_warehouse_id', $user->warehouse_id)->orderBy('id', 'DESC')->with('senderWarehouse', 'receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo', 'receiverBranch')->paginate(getPaginate());
        return view('manager_warehouse.courier.index', compact('pageTitle', 'emptyMessage', 'courierInfos'));
    }


    public function receivedCourier()
    {
        $user = Auth::user();
        $pageTitle = "Upcoming Courier List";
        $emptyMessage = "No data found";
        $courierInfos = CourierInfo::where('receiver_warehouse_id', $user->warehouse_id)->orderBy('id', 'DESC')->with('senderWarehouse', 'receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo', 'receiverBranch')->paginate(getPaginate());
        return view('manager_warehouse.courier.index', compact('pageTitle', 'emptyMessage', 'courierInfos'));
    }

    public function courierSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = "Courier Search";
        $emptyMessage = "No Data Found";
        $user = Auth::user();
        $courierInfos = CourierInfo::where('code', $search)->with('senderWarehouse', 'receiverWarehouse', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('manager_warehouse.courier.index', compact('pageTitle', 'emptyMessage', 'courierInfos', 'search'));
    }

    public function invoice($id)
    {
        $pageTitle = "Invoice";
        $courierInfo = CourierInfo::where('id', $id)->first();
        $courierProductInfos = CourierProduct::with('type')->where('courier_info_id', $courierInfo->id)->get();
        $courierPayment = CourierPayment::where('courier_info_id', $courierInfo->id)->first();
        $qrCode = QrCode::size(150)->generate($courierInfo->code);
        return view('manager_warehouse.courier.invoice', compact('pageTitle', 'courierInfo', 'courierProductInfos', 'courierPayment', 'qrCode'));
    }

    
}