@extends('manager_warehouse.layouts.app')
@section('panel')
    <div class="row mt-50 mb-none-30">
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--19 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="fa fa-wallet"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$totalStaffCount}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Staff')</span>
                    </div>
                    <a href="{{route('manager_warehouse.staff.index')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--3 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="fa fa-hand-holding-usd"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$warehouseListCount}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Warehouse')</span>
                    </div>
                    <a href="{{ route('manager_warehouse.warehouse.index') }}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--12 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="fa fa-money-bill-alt"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$branchCount}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Branch In The Area')</span>
                    </div>

                    <a href="{{route('manager_warehouse.branch.list')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--6 b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-spinner"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$courierInfoCount}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Courier')</span>
                    </div>

                    <a href="{{route('manager_warehouse.courier.index')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-50">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Sender Branch/Warehouse - Staff')</th>
                                <th>@lang('Receiver Branch/Warehouse - Staff')</th>
                                <th>@lang('Amount - Order Number')</th>
                                <th>@lang('Creations Date')</th>
                                <th>@lang('Payment Status')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($courierInfos as $courierInfo)
                            <tr>
                                <td data-label="@lang('Sender Warehouse - Staff')">
                                    <span>{{__($courierInfo->senderWarehouse->name)}}</span><br>
                                    <a href="{{route('manager_warehouse.staff.edit', encrypt($courierInfo->senderStaff->id))}}"><span>@</span>{{__($courierInfo->senderStaff->username)}}</a>
                                </td>

                                <td data-label="@lang('Receiver Warehouse - Staff')">
                                    <span>
                                        @if($courierInfo->receiver_warehouse_id)
                                            {{__($courierInfo->receiverWarehouse->name)}}
                                        @elseif($courierInfo->receiver_branch_id)
                                            {{__($courierInfo->receiverBranch->name)}}
                                        @else
                                            @lang('')
                                        @endif
                                    </span>
                                    <br>
                                    @if($courierInfo->receiver_staff_id)
                                        <a href="{{route('manager_warehouse.staff.edit', encrypt($courierInfo->receiverStaff->id))}}"><span>@</span>{{__($courierInfo->receiverStaff->username)}}</a>
                                    @else
                                        <span>@lang('')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Amount Order Number')">
                                    <span class="font-weight-bold">{{getAmount($courierInfo->paymentInfo->amount)}} {{ $general->cur_text }}</span><br>
                                    <span>{{__($courierInfo->code) }}</span>
                                </td>

                                 <td data-label="@lang('Creations Date')">
                                    <span>{{showDateTime($courierInfo->created_at, 'd M Y')}}</span><br>
                                    <span>{{diffForHumans($courierInfo->created_at) }}</span>
                                </td>

                                <td data-label="@lang('Payment Status')">
                                    @if($courierInfo->paymentInfo->status == 1)
                                        <span class="badge badge--success">@lang('Paid')</span>
                                    @elseif($courierInfo->paymentInfo->status == 0)
                                        <span class="badge badge--danger">@lang('Unpaid')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Status')">
                                    @if($courierInfo->status == 0)
                                        <span class="badge badge--primary">@lang('Received')</span>
                                    @elseif($courierInfo->status == 1)
                                        <span class="badge badge--primary">@lang('Sending To') {{$courierInfo->receiverWarehouse->name}}</span>
                                    @elseif($courierInfo->status == 2)
                                        <span class="badge badge--primary">{{$courierInfo->receiverWarehouse->name}} @lang('Received')</span>
                                    @elseif($courierInfo->status == 3)
                                        <span class="badge badge--primary">@lang('Sending To') {{$courierInfo->receiverWarehouse->name}}</span>
                                    @elseif($courierInfo->status == 4)
                                        <span class="badge badge--primary">{{$courierInfo->receiverWarehouse->name}} @lang('Received')</span>
                                    @elseif($courierInfo->status == 5)
                                        <span class="badge badge--primary">@lang('Sending To') {{$courierInfo->receiverBranch->name}}</span>
                                    @elseif($courierInfo->status == 6)
                                        <span class="badge badge--primary">{{$courierInfo->receiverBranch->name}} @lang('Received')</span>
                                    @elseif($courierInfo->status >= 7 && getStatus($courierInfo->id) == 0)
                                        <span class="badge badge--primary">@lang('Sending To') {{$courierInfo->receiver_name}}</span>
                                    @elseif($courierInfo->status >= 7 && getStatus($courierInfo->id) == 1)
                                        <span class="badge badge--success">Successful Delivery</span>
                                    @elseif($courierInfo->status >= 7 && getStatus($courierInfo->id) == 2)
                                        <span class="badge badge--danger">Unsuccessful Delivery</span>
                                    @elseif($courierInfo->status >= 7 && getStatus($courierInfo->id) == 3)
                                        <span class="badge badge--danger">@lang('Returned') {{$courierInfo->receiverBranch->name}}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


