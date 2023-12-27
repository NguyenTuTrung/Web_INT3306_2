@extends('delivery_man.layouts.app')
@section('panel')
<div class="row">
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
                                <th>@lang('Delivery')</th>
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
                                    <a href="{{route('delivery_man.courier.invoice', encrypt($courierList->courier_id))}}"
                                        title="" class="icon-btn bg--10 ml-1">@lang('Invoice')</a>
                                    <a href="{{route('delivery_man.courier.details', encrypt($courierList->courier_id))}}"
                                        title="" class="icon-btn btn--priamry ml-1">@lang('Details')</a>
                                </td>

                                @if($courierList->status == 0)
                                <td>
                                    <div class="btn-group">
                                        <div class="icon-btn btn--success ml-1 dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown">Action</div>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item done" href="javascript:void(0)"
                                                data-code="{{$courierList->id}}">Done</a>
                                            <a class="dropdown-item miss" href="javascript:void(0)"
                                                data-code="{{$courierList->id}}">Miss</a>
                                        </div>
                                    </div>
                                </td>
                                @endif
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

<div class="modal fade" id="doneBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('delivery_man.courier.confirm')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="code">
                <div class="modal-body">
                    <p>@lang('Are you sure this order has been delivered to the recipient??')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="missBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{route('delivery_man.courier.confirm.miss')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="code">
                <div class="modal-body">
                    <p>@lang('Are you sure to delivery this courier missed?')</p>
                    <br>
                    <textarea name="reason" rows="4" cols="50" placeholder="@lang('Enter your reason here')"></textarea>
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
<form action="{{route('staff.courier.search')}}" method="GET"
    class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
    <div class="input-group has_append  ">
        <input type="text" name="search" class="form-control" placeholder="@lang('Order Number')"
            value="{{ $search ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>

<form action="{{route('staff.courier.date.search')}}" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append ">
        <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en"
            class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Min date - Max date')"
            autocomplete="off" value="{{ @$dateSearch }}">
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
    (function ($) {
        "use strict";
        if (!$('.datepicker-here').val()) {
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
</script>

<script>
    'use strict';
    $('.done').on('click', function () {
        var modal = $('#doneBy');
        modal.find('input[name=code]').val($(this).data('code'))
        modal.modal('show');
    });

    $('.miss').on('click', function () {
        var modal = $('#missBy');
        modal.find('input[name=code]').val($(this).data('code'))
        modal.modal('show');
    });
</script>
@endpush