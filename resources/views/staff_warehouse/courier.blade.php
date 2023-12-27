@extends('staff_warehouse.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('staff_warehouse.courier.store')}}" method="POST">
                        @csrf
                        <div class="row mb-30">
                            <div class="col-lg-12">
                                <div class="card border--primary mt-3">
                                    <h5 class="card-header bg--primary  text-white">@lang('Receiver Information')</h5>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="=receiver" class="form-control-label font-weight-bold">@lang('Select Receiver')</label>
                                                <select class="form-control form-control-lg" name="receiver" id="receiver" required="">
                                                    <option value="">@lang('Select One')</option>
                                                    <option value="branch">@lang('Branch')</option>
                                                    <option value="warehouse">@lang('Warehouse')</option>
                                                </select>
                                            </div>

                                            <div id="branchBlock" class="form-group col-lg-6" style="display: none;">
                                                <label for="=branch" class="form-control-label font-weight-bold">@lang('Select Branch')</label>
                                                <select class="form-control form-control-lg" name="branch" id="branch" required="">
                                                    <option value="0" >@lang('Select One')</option>
                                                    @foreach($branchs as $branch)
                                                        <option value="{{$branch->id}}">{{__($branch->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div id="warehouseBlock" class="form-group col-lg-6" style="display: none;">
                                                <label for="warehouse" class="form-control-label font-weight-bold">@lang('Select warehouse')</label>
                                                <select class="form-control form-control-lg" name="warehouse" id="warehouse" required="">
                                                    <option value="0" >@lang('Select One')</option>
                                                    @foreach($warehouses as $warehouse)
                                                        <option value="{{$warehouse->id}}">{{__($warehouse->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                                        <th>@lang('Add')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($courierLists as $courierInfo)
                                                    <tr>
                                                        <tr>
                                                            <td data-label="@lang('Sender Branch/Warehouse')">
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
                                                            {{showDateTime($courierInfo->created_at, 'd M Y')}}
                                                        </td>

                                                        <td data-label="@lang('Payment Status')">
                                                            @if($courierInfo->paymentInfo->status == 1)
                                                                <span class="badge badge--success">@lang('Paid')</span>
                                                            @elseif($courierInfo->paymentInfo->status == 0)
                                                                <span class="badge badge--danger">@lang('Unpaid')</span>
                                                            @endif
                                                        </td>

                                                        <td><input type="checkbox" name="couriers[]" value="{{ $courierInfo->id }}"></td>
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
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block"><i class="fa fa-fw fa-paper-plane"></i> @lang('Send Courier')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('staff_warehouse.courier.create')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i> @lang('Go Back')</a>
@endpush

@push('script')
<script>
    "use strict";

    document.getElementById('receiver').addEventListener('change', function() {
        var selectedValue = this.value;
        var branchBlock = document.getElementById('branchBlock');
        var warehouseBlock = document.getElementById('warehouseBlock');

        if (selectedValue === 'branch') {
            branchBlock.style.display = 'block';
            warehouseBlock.style.display = 'none';
        } else if (selectedValue === 'warehouse') {
            branchBlock.style.display = 'none';
            warehouseBlock.style.display = 'block';
        } else {
            branchBlock.style.display = 'none';
            warehouseBlock.style.display = 'none';
        }
    });
</script>
@endpush
