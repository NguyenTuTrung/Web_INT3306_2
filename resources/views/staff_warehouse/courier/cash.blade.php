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
                                <th>@lang('ID')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Income')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($warehouseIncomeLists as $warehouseIncome)
                            <tr>
                                <td data-label="@lang('ID')">
                                    <span class="font-weight-bold">{{$loop->iteration}}</span>
                                </td>
                                <td data-label="@lang('Date')">
                                    <span class="font-weight-bold">{{showDateTime($warehouseIncome->date, 'd M Y')}}</span>
                                </td>
                                 <td data-label="@lang('Income')">
                                    <span class="font-weight-bold">{{getAmount($warehouseIncome->totalAmount)}} {{$general->cur_text}}</span>
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
                {{ paginateLinks($warehouseIncomeLists) }}
            </div>
        </div>
    </div>
</div>
@endsection
