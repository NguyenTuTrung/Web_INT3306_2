@extends('delivery_man.layouts.app')
@section('panel')
    <div class="row mt-50 mb-none-30">
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--19 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="fa fa-wallet"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$shipmentCount}}</span>
                    </div> 
                    <div class="desciption">
                        <span>@lang('Total Couriers')</span>
                    </div>
                    <a href="{{route('delivery_man.courier.index')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--3 b-radius--10 box-shadow" >
                <div class="icon">
                    <i class="fa fa-spinner"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$successfullCouriers}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Successful Couriers')</span>
                    </div>
                    <a href="{{route('delivery_man.courier.successful.index')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
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
                        <span class="amount">{{$missionCount}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Missions')</span>
                    </div>

                    <a href="{{route('delivery_man.courier.mission')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
            <div class="dashboard-w1 bg--6 b-radius--10 box-shadow">
                <div class="icon">
                    <i class="fa fa-hand-holding-usd"></i>
                </div>
                <div class="details">
                    <div class="numbers">
                        <span class="amount">{{$returnsCount}}</span>
                    </div>
                    <div class="desciption">
                        <span>@lang('Total Returns')</span>
                    </div>

                    <a href="{{route('delivery_man.courier.return')}}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View All')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-30">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Sender - Phone - Email')</th>
                                    <th>@lang('Receiver - Phone - Email')</th>
                                    <th>@lang('Order Number')</th>
                                    <th>@lang('Creations Date')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($courierLists as $courierList)
                            <tr>
                                    <td data-label="@lang('Sender - Phone - Email')">
                                    <span>{{__($courierList->courierInfo->sender_name)}}</span><br>
                                    {{__($courierList->courierInfo->sender_phone)}}<br>
                                    {{$courierList->courierInfo->sender_email}}
                                </td>

                                <td data-label="@lang('Receiver - Phone - Email')">
                                    <span>{{__($courierList->courierInfo->receiver_name)}}</span><br>
                                    {{__($courierList->courierInfo->receiver_phone)}}<br>
                                    {{$courierList->courierInfo->receiver_email}}
                                </td>

                                <td data-label="@lang('Order Number')">
                                    <span>{{__($courierList->courierInfo->code) }}</span>
                                </td>

                                 <td data-label="@lang('Creations Date')">
                                    <span>{{showDateTime($courierList->courierInfo->created_at, 'd M Y')}}</span><br>
                                    <span>{{diffForHumans($courierList->courierInfo->created_at) }}</span>
                                </td>

                                <td data-label="@lang('Status')">
                                    @if($courierList->status == 0)
                                        <span class="badge badge--primary">@lang('Delivery')</span>
                                    @elseif($courierList->status == 1)
                                        <span class="badge badge--success">@lang('Delivery Complete')</span>
                                    @elseif($courierList->status == 2)
                                        <span class="badge badge--danger">@lang('Missed')</span>
                                    @elseif($courierList->status == 3)
                                        <span class="badge badge--primary">@lang('Return Branch')</span>
                                    @endif
                                </td>
                            
                                <td data-label="@lang('Action')">
                                   <a href="{{route('delivery_man.courier.invoice', encrypt($courierList->courier_id))}}" title="" class="icon-btn bg--10 ml-1">@lang('Invoice')</a>
                                   <a href="{{route('delivery_man.courier.details', encrypt($courierList->courier_id))}}" title="" class="icon-btn btn--priamry ml-1">@lang('Details')</a>
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



@push('script')

@endpush

