@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate . 'partials.breadcrumb')
@php 
    $orderTracking = getContent('order_tracking.content', true);
@endphp
     <section class="track-section pt-120 pb-120">
        <div class="container">
            <div class="section__header section__header__center">
                <span class="section__cate">
                    {{__(@$orderTracking->data_values->title)}}
                </span>
                <h3 class="section__title"> {{__(@$orderTracking->data_values->heading)}}</h3>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9 col-xl-6">
                    <form action="{{route('order.tracking')}}" method="GET" class="order-track-form mb-4 mb-md-5">
                        @csrf
                        @method("GET")
                        <div class="order-track-form-group">
                            <input type="text" name="order_number" placeholder="@lang('Enter Your Order Id')" value="{{@$orderNumber->code}}">
                            <button type="submit">@lang('Track Now')</button>
                        </div>
                    </form>
                </div>
            </div>

            @if($orderNumber)
            <div class="container">
                <div class="checkout-form">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="user-profile-data">
                                <!-- General Information -->
                                <div class="payment-wrap">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="track-title">
                                                <h5 class="form_sub" style="background-color: #ff700a; border-radius: 3px; color:white">Sender</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="track-title">
                                                <span class="ti-location-pin align-top"style="font-size: 30px;"></span> <label>Name<br>
                                                    <b>{{$orderNumber->sender_name}}</b></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="track-title">
                                                <span class="ti-location-pin align-top"style="font-size: 30px;"></span> <label>Phone<br>   
                                                    <b>{{$orderNumber->sender_phone}}</b></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="track-title">
                                                <span class="ti-calendar align-top"style="font-size: 30px;"></span> <label>Email<br>
                                                    <b>{{$orderNumber->sender_email}}</b></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="track-title">
                                                    <span class="ti-timer align-top"style="font-size: 30px;"></span> <label>Date Of Shipment<br>
                                                        <b>{{$orderNumber->created_at}}</b></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="track-title">
                                                <span class="ti-calendar align-top"style="font-size: 30px;"></span> <label>Address<br>
                                                    <b>{{$orderNumber->sender_address}}</b></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="track-title">
                                                <span class="ti-calendar align-top"style="font-size: 30px;"></span> <label>Address<br>
                                                    @foreach($courierProducts as $courierProduct)
                                                        <b>{{($courierProduct->type->name)}}</b></label>
                                                    @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--// General Information -->

                                <!-- track shipment -->
                                <div class="payment-wrap">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="track-title">
                                                <h5 class="form_sub"  style="background-color: #ff700a; border-radius: 3px; color:white">Receiver</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="track-title">
                                                <span class="ti-location-pin align-top" style="font-size: 30px;"></span> <label>Name<br>
                                                    <b>{{$orderNumber->receiver_name}}</b></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="track-title">
                                                <span class="ti-location-pin align-top" style="font-size: 30px;"></span> <label>Phone<br>
                                                    <b>{{$orderNumber->receiver_phone}}</b></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="track-title">
                                                <span class="ti-calendar align-top"
                                                    style="font-size: 30px;"></span> <label>Email<br>
                                                    <b>{{$orderNumber->receiver_email}}</b>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="track-title">
                                                    <span class="ti-timer align-top"
                                                        style="font-size: 30px;"></span> <label>Expected Date Of Arrival<br>
                                                        @if($orderNumber->status > 6 && getStatus($orderNumber->id) == 1 )    
                                                         <b>{{$orderNumber->updated_at}}</b></label>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="track-title">
                                                <span class="ti-calendar align-top"
                                                    style="font-size: 30px;"></span> <label>Address<br>
                                                    <b>{{$orderNumber->receiver_address}}</b>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- /.user-profile-data -->
                        </div> <!-- /.col- -->

                        <div class="col-lg-5">
                            <br><br><br><br><br><br><br>
                            <div class="booking-summary_block">
                                <div class="booking-summary-box">
                                    <h5>Shipping Record</h5>
                                    <div class="track-cost">
                                        <ul class="timeline a">
                                            <li class="event">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <p class="text-left button5">{{$orderNumber->created_at}}</p>
                                                        <h6 class="text-left button4">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <p class="text-right button5">Created</p>
                                                        <h4></h4>
                                                    </div>
                                                </div>
                                            </li>
                                            <!--event schedule 1 end-->
                                        </ul>
                                    </div>
                                    <div class="track-cost">

                                        <ul class="timeline a">
                                                @if(count($timeLog) > 1)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{$timeLog[1]}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('Sending to warehouse') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 2)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{$timeLog[2]}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('The order is in warehouse') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 3)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{$timeLog[3]}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('Sending to warehouse near reveicer') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 4)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{$timeLog[4]}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('The order is in warehouse near receicer') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 5)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{$timeLog[5]}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('Sending to branch near receiver') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 6)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{$timeLog[6]}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('This order is in branch near receiver') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 7)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{$timeLog[7]}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('The order is being delivered to the receiver') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 7 && getStatus($orderNumber->id) == 1)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{getTime($orderNumber->id)}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('Successful delivery') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                                @if(count($timeLog) > 7 && getStatus($orderNumber->id) == 3)
                                                <li class="event">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <p class="text-left button5">{{getTime($orderNumber->id)}}</p>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <p class="text-right button5">@lang('Delivery was unsuccessful and the item was returned to a branch near you') </p>
                                                            <h4></h4>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                            </li>
                                            <!--event schedule 1 end-->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.row -->
                </div> <!-- /.checkout-form -->
            </div> <!-- /.container -->
            @endif
        </div>
    </section>
@endsection

