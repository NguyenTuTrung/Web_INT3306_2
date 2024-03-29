@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="row mt-12">
                <div class="form-group col-lg-4">
                    <select class="form-control form-control-lg" name="branch" id="branch" required="">
                        @if($branchId == 0)
                            <option value="0" selected>@lang('All Branch')</option>
                        @else
                            <option value="0">@lang('All Branch')</option>
                        @endif
                        @foreach($branchs as $branch)
                            @if($branchId == $branch->id)
                                <option value="{{$branch->id}}" selected>{{($branch->name)}}</option>
                            @else
                                <option value="{{$branch->id}}">{{($branch->name)}}</option>
                            @endif
                        @endforeach
                    </select>
                    
                </div>
                <div class="form-group col-lg-4">
                    <select class="form-control form-control-lg" name="senderOrReceiver" id="senderOrReceiver" required="">
                        @if($senderOrReceiver == 0)
                            <option value="0" selected>@lang('Sender and Receiver')</option>
                        @else
                            <option value="0">@lang('Sender and Receiver')</option>
                        @endif
                        @if($senderOrReceiver == 1)
                            <option value="1" selected>@lang('Sender')</option>
                        @else
                            <option value="1">@lang('Sender')</option>
                        @endif
                        @if($senderOrReceiver == 2)
                            <option value="2" selected>@lang('Receiver')</option>
                        @else
                            <option value="2">@lang('Receiver')</option>
                        @endif
                    </select>
                </div>
            </div>
            
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Sender Branch - Staff')</th>
                                    <th>@lang('Receiver Branch - Staff')</th>
                                    <th>@lang('Amount - Order Number')</th>
                                    <th>@lang('Creations Date')</th>
                                    <th>@lang('Payment Status')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($courierInfos as $courierInfo)
                                <tr>
                                    <td data-label="@lang('Sender Branch - Staff')">
                                       <span class="font-weight-bold">{{__($courierInfo->senderBranch->name)}}</span><br>
                                       {{__($courierInfo->senderStaffBranch->fullname)}}
                                    </td>
                                    <td data-label="@lang('Receiver Branch - Staff')">
                                        @if($courierInfo->receiver_branch_id)
                                            <span class="font-weight-bold">{{__($courierInfo->receiverBranch->name)}}</span>
                                        @else
                                            @lang('')
                                        @endif
                                        <br>
                                        @if($courierInfo->receiver_staff_id)
                                            {{__($courierInfo->receiverStaff->fullname)}}
                                        @else
                                            <span>@lang('')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Amount - Order Number')">
                                        <span class="font-weight-bold">{{getAmount($courierInfo->paymentInfo->amount)}} {{ $general->cur_text }}</span><br>
                                        <span>{{__($courierInfo->code) }}</span>
                                    </td>

                                     <td data-label="@lang('Creations Date')">
                                        {{showDateTime($courierInfo->created_at, 'd M Y')}}<br>{{ diffForHumans($courierInfo->created_at) }}
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
                                
                                    <td data-label="@lang('Action')">
                                       <a href="{{route('admin.courier.invoice', $courierInfo->id)}}" title="" class="icon-btn bg--10 ml-1">@lang('Invoice')</a>
                                       <a href="{{route('admin.courier.info.details', $courierInfo->id)}}" title="" class="icon-btn btn--priamry">@lang('Details')</a>
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
                <div class="card-footer py-4">
                    {{ paginateLinks($courierInfos) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{route('admin.courier.date.search')}}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append ">
            <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Min date - Max date')" autocomplete="off" value="{{ @$dateSearch }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush


@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
  <script>
    (function($){
        "use strict";
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
  </script>

<script>
    $(document).ready(function () {
        $('#branch, #senderOrReceiver').on('change', function () {
            var branchId = $('#branch').val();
            var senderOrReceiver = $('#senderOrReceiver').val();
            
            if (branchId !== '0') {
                var url = "{{route('admin.courier.select')}}" + "?branchId=" + branchId + "&senderOrReceiver=" + senderOrReceiver;
                window.location.href = url;
            } else {
                window.location.href = "{{route('admin.courier.info.index')}}";
            }
        });
    });
</script>
@endpush

