@extends('staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Sender Branch - Staff')</th>
                                    <th>@lang('Amount - Order Number')</th>
                                    <th>@lang('Creations Date')</th>
                                    <th>@lang('Payment Status')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($courierDispatchs as $courierInfo)
                                <tr>
                                <tr>
                                    <td data-label="@lang('Sender Branch')">
                                        <span>{{__($courierInfo->senderBranch->name)}}</span><br>
                                        {{__($courierInfo->senderStaffBranch->fullname)}}
                                    </td>

                                    <td data-label="@lang('Amount Order Number')">
                                        <span class="font-weight-bold">{{getAmount($courierInfo->paymentInfo->amount)}} {{ $general->cur_text }}</span><br>
                                        <span>{{__($courierInfo->code) }}</span>
                                    </td>

                                     <td data-label="@lang('Creations Date')">
                                        {{showDateTime($courierInfo->created_at, 'd M Y')}}<br>{{ diffForHumans($courierInfo->created_at)}}
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
                                        @if(($courierInfo->status  == 0 && $courierInfo->paymentInfo->status == 0))
                                            <a href="javascript:void(0)" title="" class="icon-btn btn--success ml-1 payment" data-code="{{$courierInfo->code}}">@lang('Payment')</a>
                                        @endif
                                       <a href="{{route('staff.courier.invoice', encrypt($courierInfo->id))}}" class="icon-btn bg--10 ml-1">@lang('Invoice')</a>
                                       <a href="{{route('staff.courier.details', encrypt($courierInfo->id))}}" class="icon-btn btn--priamry ml-1">@lang('Details')</a>
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
                    {{ paginateLinks($courierDispatchs) }}
                </div>
            </div>
        </div>
    </div>



<div class="modal fade" id="paymentBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Payment Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            
            <form action="{{route('staff.courier.payment')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="code">
                <div class="modal-body">
                    <p>@lang('Are you sure to collect this amount?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



@push('script')
<script>
    'use strict';
    $('.payment').on('click', function () {
        var modal = $('#paymentBy');
        modal.find('input[name=code]').val($(this).data('code'))
        modal.modal('show');
    });
</script>
@endpush