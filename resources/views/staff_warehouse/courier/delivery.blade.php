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
                                    <th>@lang('Sender Branch/Warehouse - Staff')</th>
                                    <th>@lang('Amount - Order Number')</th>
                                    <th>@lang('Creations Date')</th>
                                    <th>@lang('Payment Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($courierDeliveys as $courierInfo)
                                <tr>
                                    <tr>
                                    <td data-label="@lang('Sender Branch/Warehouse - Staff')">
                                    @if($courierInfo->sender_warehouse_id != 0)
                                        <span>{{__($courierInfo->senderWarehouse->name)}}</span><br>
                                    @else
                                        <span>{{__($courierInfo->senderBranch->name)}}</span><br>
                                    @endif
                                    {{__($courierInfo->senderStaff->fullname)}}
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
                                
                                    <td data-label="@lang('Action')">
                                        @if($courierInfo->status  == 1 || $courierInfo->status == 3)
                                            <a href="javascript:void(0)" title="" class="icon-btn btn--info ml-1 confirm" data-code="{{$courierInfo->code}}">@lang('Confirm')</a>
                                        @endif
                                       <a href="{{route('staff_warehouse.courier.invoice', $courierInfo->id)}}" class="icon-btn bg--10 ml-1">@lang('Invoice')</a>
                                       <a href="{{route('staff_warehouse.courier.details', $courierInfo->id)}}" class="icon-btn btn--priamry ml-1">@lang('Details')</a>
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
                    {{ paginateLinks($courierDeliveys) }}
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
                    <p>@lang('Are you sure to confirm this courier in the warehouse?')</p>
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



@push('script')
<script>
    'use strict';
    $('.payment').on('click', function () {
        var modal = $('#paymentBy');
        modal.find('input[name=code]').val($(this).data('code'))
        modal.modal('show');
    });

    $('.confirm').on('click', function () {
        var modal = $('#confirmBy');
        modal.find('input[name=code]').val($(this).data('code'))
        modal.modal('show');
    });
</script>
@endpush