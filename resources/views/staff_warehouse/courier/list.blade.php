@extends('staff_warehouse.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Sender Warehouse/Branch - Staff')</th>
                                <th>@lang('Receiver Warehouse - Staff')</th>
                                <th>@lang('Amount - Order Number')</th>
                                <th>@lang('Creations Date')</th>
                                <th>@lang('Payment Status')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($courierLists as $courierInfo)
                            <tr>
                                <tr>
                                    <td data-label="@lang('Sender Warehouse/Branch - Staff')">
                                    @if($courierInfo->sender_branch_id != 0 && $courierInfo->sender_warehouse_id == 0)
                                        <span>{{__($courierInfo->senderBranch->name)}}</span><br>
                                    @elseif($courierInfo->sender_branch_id != 0 && $courierInfo->sender_warehouse_id != 0)
                                        <span>{{__($courierInfo->senderWarehouse->name)}}</span><br>
                                    @endif
                                    {{__($courierInfo->senderStaff->fullname)}}
                                </td>

                                <td data-label="@lang('Receiver Branch - Staff')">
                                    @if($courierInfo->status == 4)
                                        <span>
                                            @if($courierInfo->receiver_staff_id)
                                                {{__($courierInfo->receiverWarehouse->name)}}
                                            @else
                                                @lang('')
                                            @endif
                                        </span>
                                        <br>
                                        @if($courierInfo->receiver_staff_id)
                                            {{__($courierInfo->receiverStaff->fullname)}}
                                        @else
                                            <span>@lang('')</span>
                                        @endif
                                    @endif
                                </td>

                                <td data-label="@lang('Amount Order Number')">
                                    <span class="font-weight-bold">{{getAmount($courierInfo->paymentInfo->amount)}} {{ $general->cur_text }}</span><br>
                                    <span>{{__($courierInfo->code) }}</span>
                                </td>

                                 <td data-label="@lang('Creations Date')">
                                    {{showDateTime($courierInfo->created_at, 'd M Y')}}
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
                                    @if($courierInfo->status  == 0)
                                        <a href="javascript:void(0)" title="" class="icon-btn btn--success ml-1 confirm" data-code="{{$courierInfo->code}}">@lang('Confirm')</a>
                                    @endif
                                   <a href="{{route('staff_warehouse.courier.invoice', encrypt($courierInfo->id))}}" title="" class="icon-btn bg--10 ml-1">@lang('Invoice')</a>
                                   <a href="{{route('staff_warehouse.courier.details', encrypt($courierInfo->id))}}" title="" class="icon-btn btn--priamry ml-1">@lang('Details')</a>
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
                {{ paginateLinks($courierLists) }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            
            <form action="{{route('staff_warehouse.courier.confirm')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="code">
                <div class="modal-body">
                    <p>@lang('Are you sure to confirm this courier has arrived at the warehouse?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('breadcrumb-plugins')
    <form action="{{route('staff_warehouse.courier.search')}}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
        <div class="input-group has_append  ">
            <input type="text" name="search" class="form-control" placeholder="@lang('Order Number')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>

    <form action="{{route('staff_warehouse.courier.date.search')}}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append ">
            <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Min date - Max date')" autocomplete="off" value="{{ @$dateSearch }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>

@endpush


@push('script-lib')
  <script src="{{ asset('assets/staff/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/staff/js/vendor/datepicker.en.js') }}"></script>
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
    'use strict';
    $('.confirm').on('click', function () {
        var modal = $('#confirmBy');
        modal.find('input[name=code]').val($(this).data('code'))
        modal.modal('show');
    });
</script>
@endpush