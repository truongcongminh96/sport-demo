@extends('frontend.main_master')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@section('title')
    @if(session()->get('language') == 'vietnam') Thanh toán @else Checkout @endif
@endsection
<div class="breadcrumb">
    <div class="container">
        <div class="breadcrumb-inner">
            <ul class="list-inline list-unstyled">
                <li><a href="#">Home</a></li>
                <li class='active'>Checkout</li>
            </ul>
        </div><!-- /.breadcrumb-inner -->
    </div><!-- /.container -->
</div><!-- /.breadcrumb -->
<div class="body-content">
    <div class="container">
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
        <div class="checkout-box ">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel-group checkout-steps" id="accordion">
                        <!-- checkout-step-01  -->
                        <div class="panel panel-default checkout-step-01">

                            <!-- panel-heading -->
                            <div class="panel-heading">
                                <h4 class="unicase-checkout-title">
                                    <a data-toggle="collapse" class="" data-parent="#accordion" href="#collapseOne">
                                        <span>1</span>Checkout Method
                                    </a>
                                </h4>
                            </div>
                            <!-- panel-heading -->

                            <div id="collapseOne" class="panel-collapse collapse in">

                                <!-- panel-body  -->
                                <div class="panel-body">
                                    <div class="row">

                                        <!-- guest-login -->
                                        <div class="col-md-6 col-sm-6 already-registered-login">
                                            <h4 class="checkout-subtitle"><b>Shipping address</b></h4>

                                            <form class="register-form" role="form">
                                                <div class="form-group">
                                                    <label class="info-title" for="exampleInputEmail1">Shipping Name
                                                        <span>*</span></label>
                                                    <input type="text" name="shipping_name"
                                                           class="form-control unicase-form-control text-input"
                                                           id="exampleInputEmail1" placeholder="Full name"
                                                           value="{{ Auth::user()->name }}" required="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="info-title" for="exampleInputEmail1">Email
                                                        <span>*</span></label>
                                                    <input type="email" name="shipping_email"
                                                           class="form-control unicase-form-control text-input"
                                                           id="exampleInputEmail1" placeholder="Email"
                                                           value="{{ Auth::user()->email }}">
                                                </div>

                                                <div class="form-group">
                                                    <label class="info-title" for="exampleInputEmail1">Phone
                                                        <span>*</span></label>
                                                    <input type="number" name="shipping_phone"
                                                           class="form-control unicase-form-control text-input"
                                                           id="exampleInputEmail1" placeholder="Phone"
                                                           value="{{ Auth::user()->phone }}" required="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="info-title" for="exampleInputEmail1">Post code
                                                        <span>*</span></label>
                                                    <input type="text" name="post_code"
                                                           class="form-control unicase-form-control text-input"
                                                           id="exampleInputEmail1" placeholder="Post code">
                                                </div>

                                            </form>
                                        </div>
                                        <!-- guest-login -->

                                        <!-- already-registered-login -->
                                        <div class="col-md-6 col-sm-6 already-registered-login">
                                            <h4 class="checkout-subtitle">Already registered?</h4>
                                            <p class="text title-tag-line">Please log in below:</p>
                                            <form class="register-form" role="form">

                                                <div class="form-group">
                                                    <h5>Province Select <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <select name="province_id" class="form-control" required="">
                                                            <option value="" selected="" disabled="">Select Province
                                                            </option>
                                                            @foreach($provinces as $item)
                                                                <option
                                                                    value="{{ $item->id }}">{{ $item->province_name }}</option>
                                                            @endforeach
                                                        </select>

                                                        @error('province_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>District Select <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <select name="district_id" class="form-control" required="">
                                                            <option value="" selected="" disabled="">Select District
                                                            </option>
                                                        </select>

                                                        @error('district_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <h5>Ward Select <span class="text-danger">*</span></h5>
                                                    <div class="controls">
                                                        <select name="ward_id" class="form-control" required="">
                                                            <option value="" selected="" disabled="">Select Ward
                                                            </option>
                                                        </select>

                                                        @error('ward_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="info-title" for="exampleInputEmail1">Notes<span>*</span></label>
                                                    <textarea type="text" name="notes"
                                                           class="form-control unicase-form-control text-input"
                                                              id="exampleInputEmail1" placeholder="Notes"></textarea>
                                                </div>

                                                <button type="submit"
                                                        class="btn-upper btn btn-primary checkout-page-button">Login
                                                </button>
                                            </form>
                                        </div>
                                        <!-- already-registered-login -->

                                    </div>
                                </div>
                                <!-- panel-body  -->

                            </div><!-- row -->
                        </div>
                        <!-- checkout-step-01  -->

                    </div><!-- /.checkout-steps -->
                </div>
                <div class="col-md-4">
                    <!-- checkout-progress-sidebar -->
                    <div class="checkout-progress-sidebar ">
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="unicase-checkout-title">Your Checkout Progress</h4>
                                </div>
                                <div class="">
                                    <ul class="nav nav-checkout-progress list-unstyled">
                                        @foreach($carts as $item)
                                            <li>
                                                <strong>Image: </strong>
                                                <img src="{{ asset($item->options->image) }}"
                                                     style="height: 50px; width: 50px">
                                            </li>
                                            <li>
                                                <strong>Qty: </strong>
                                                ( {{ $item->qty }} )
                                                <strong>Color: </strong>
                                                {{ $item->options->color }}
                                                <strong>Size: </strong>
                                                {{ $item->options->size }}
                                            </li>
                                        @endforeach
                                        @if(Session::has('coupon'))
                                            <li><a href="#"><strong>Subtotal: </strong> {{ $cartTotal }}</a></li>
                                            <hr>
                                            <li><a href="#"><strong>Coupon
                                                        Name: </strong> {{ session()->get('coupon')['coupon_name'] }}
                                                    ( {{ session()->get('coupon')['coupon_discount'] }} % )</a></li>
                                            <hr>
                                            <li><a href="#"><strong>Coupon
                                                        Discount: </strong> {{ session()->get('coupon')['discount_amount'] }}
                                                </a></li>
                                            <hr>
                                            <li><a href="#"><strong>Grand
                                                        Total: </strong> {{ session()->get('coupon')['total_amount'] }}
                                                </a></li>
                                            <hr>
                                        @else
                                            <li><a href="#"><strong>Subtotal: </strong> {{ $cartTotal }}</a></li>
                                            <hr>
                                            <li><a href="#"><strong>Grand Total: </strong> {{ $cartTotal }}</a></li>
                                            <hr>
                                        @endif


                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- checkout-progress-sidebar -->                </div>

                <div class="col-md-4">
                    <!-- checkout-progress-sidebar -->
                    <div class="checkout-progress-sidebar ">
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="unicase-checkout-title">Select Payment Method</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Stripe</label>
                                        <label>
                                            <input type="radio" name="payment_method" value="stripe">
                                        </label>
                                        <img src="{{ asset('frontend/assets/images/payments/4.png') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Card</label>
                                        <label>
                                            <input type="radio" name="payment_method" value="card">
                                        </label>
                                        <img src="{{ asset('frontend/assets/images/payments/3.png') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Cash</label>
                                        <label>
                                            <input type="radio" name="payment_method" value="cash">
                                        </label>
                                        <img src="{{ asset('frontend/assets/images/payments/2.png') }}">
                                    </div>

                                </div>
                                <hr>
                                <button type="submit" class="btn-upper btn btn-primary checkout-page-button">Payment Step</button>
                            </div>
                        </div>
                    </div>
                    <!-- checkout-progress-sidebar -->                </div>
            </div><!-- /.row -->
        </div><!-- /.checkout-box -->
        </form>
        <!-- ============================================== BRANDS CAROUSEL ============================================== -->

        <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
    </div><!-- /.container -->
</div><!-- /.body-content -->

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="province_id"]').on('change', function(){
            var province_id = $(this).val();
            if(province_id) {
                $.ajax({
                    url: "{{  url('/district-get/ajax') }}/"+province_id,
                    type:"GET",
                    dataType:"json",
                    success:function(data) {
                        $('select[name="ward_id"]').empty();
                        var d =$('select[name="district_id"]').empty();
                        $.each(data, function(key, value){
                            $('select[name="district_id"]').append('<option value="'+ value.id +'">' + value.district_name + '</option>');
                        });
                    },
                });
            } else {
                alert('danger');
            }
        });
        $('select[name="district_id"]').on('change', function(){
            var district_id = $(this).val();
            if(district_id) {
                $.ajax({
                    url: "{{  url('/ward-get/ajax') }}/"+district_id,
                    type:"GET",
                    dataType:"json",
                    success:function(data) {
                        var d =$('select[name="ward_id"]').empty();
                        $.each(data, function(key, value){
                            $('select[name="ward_id"]').append('<option value="'+ value.id +'">' + value.ward_name + '</option>');
                        });
                    },
                });
            } else {
                alert('danger');
            }
        });

    });
</script>
@endsection
