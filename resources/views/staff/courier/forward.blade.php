@extends('staff.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('staff.courier.forward.store')}}" method="POST">
                        @csrf
                        <div class="row">
                             <div class="col-lg-12">
                                <div class="card border--primary mt-3">
                                    <h5 class="card-header bg--primary  text-white">@lang('Receiver Information')</h5>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="delivery_man" class="form-control-label font-weight-bold">@lang('Select Delivery Man')</label>
                                                <select class="form-control form-control-lg" name="delivery_man" id="delivery_man" required="">
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach($delivery_mans as $delivery_man)
                                                        <option value="{{$delivery_man->id}}">{{__($delivery_man->fullname)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label for="receiver_name" class="form-control-label font-weight-bold">@lang('Name')</label>
                                                <input type="text" class="form-control form-control-lg" id="receiver_name" name="receiver_name" value="{{old('receiver_name')}}" placeholder="@lang("Enter Receiver Name")"  maxlength="40" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="receiver_phone" class="form-control-label font-weight-bold">@lang('Phone')</label>
                                                <input type="text" class="form-control form-control-lg" id="receiver_phone" name="receiver_phone" placeholder="@lang("Enter Receiver Phone")" value="{{old('receiver_phone')}}" required="">
                                            </div>


                                            <div class="form-group col-lg-6">
                                                <label for="receiver_email" class="form-control-label font-weight-bold">@lang('Email')</label>
                                                <input type="email" class="form-control form-control-lg" id="receiver_email" name="receiver_email" value="{{old('receiver_email')}}" placeholder="@lang("Enter Receiver Email")"  required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="receiver_address" class="form-control-label font-weight-bold">@lang('Address')</label>
                                                <input type="text" class="form-control form-control-lg" id="receiver_address" name="receiver_address" placeholder="@lang("Enter Receiver Address")" value="{{old('receiver_address')}}"  required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-30">
                            <div class="col-lg-12">
                                <div class="card border--primary mt-3">
                                    <table class="table table--light style--two">
                                        <thead>
                                            <tr>
                                                <th>@lang('Sender - Phone')</th>
                                                <th>@lang('Receiver - Phone - Email')</th>
                                                <th>@lang('Order Number')</th>
                                                <th>@lang('Creations Date')</th>
                                                <th>@lang('Receiver Address')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
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
    <a href="{{route('staff.dashboard')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i> @lang('Go Back')</a>
@endpush

@push('script')
<script>
    "use strict";
    function currierType(id) {
        let unit = $("#courier_type_" + id).find(':selected').data('unit');
        let price = $("#courier_type_" + id).find(':selected').data('price');
        $("#unit_" + id).html(unit);

        if ($('#courier_type_' + id).val()) {
            $(".currier_quantity_" + id).removeAttr("disabled");
        }
    }

    function courierQuantity(id)
    {
        let quantity = $(".currier_quantity_" + id).val();
        let price = $("#courier_type_" + id).find(':selected').data('price');
        $(".currier_fee_" + id).val(quantity * price);
    }
</script>



<script type="text/javascript">

    $(document).ready(function(){
        $('#receiver_name, #receiver_phone, #receiver_email, #receiver_address').trigger('keyup');
    });

    $('#receiver_name, #receiver_phone, #receiver_email, #receiver_address').on('keyup',function(){
        var value_name = $('#receiver_name').val();
        var value_phone = $('#receiver_phone').val();
        var value_email = $('#receiver_email').val();
        var value_address = $('#receiver_address').val();

        $.ajax({
            type: 'get',
            url: '{{route('staff.courier.forward.search')}}',
            data:{
                'receiver_name': value_name,
                'receiver_phone': value_phone,
                'receiver_email': value_email,
                'receiver_address': value_address
            },

            success:function(data)
            {
                console.table(data);
                $('.table tbody').html(data);
            },

            error: function(jqXHR, textStatus, errorThrown) {
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });
</script>
@endpush
