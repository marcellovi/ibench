<?php


$url = URL::to("/");

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wirecard Transaction List</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-light">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Wirecard Transaction List</h2>
            <hr/>
            @if(isset($error))
                <div class="alert alert-danger">
                    <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
                    {{ $error }}
                </div>
            @endif
            @if(isset($success))
                <div class="alert alert-success">
                    <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
                    {{ $success }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger">
                    <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
                    {{ session()->get('error') }}
                </div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success">
                    <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
                    {{ session()->get('success') }}
                </div>
            @endif
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-all-tab" data-toggle="pill" href="#pills-all" role="tab" aria-controls="pills-all" aria-selected="true">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-waiting-tab" data-toggle="pill" href="#pills-waiting" role="tab" aria-controls="pills-waiting" aria-selected="false">Waiting</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-authorized-tab" data-toggle="pill" href="#pills-authorized" role="tab" aria-controls="pills-authorized" aria-selected="false">Authorized</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-cancelled-tab" data-toggle="pill" href="#pills-cancelled" role="tab" aria-controls="pills-cancelled" aria-selected="false">Cancelled</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-refunds-tab" data-toggle="pill" href="#pills-refunds" role="tab" aria-controls="pills-refunds" aria-selected="false">Refunds</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                    @if(empty($output['all']))
                        <h4 class="text-center">No Transaction Yet!</h4>
                    @else
                        @foreach($output['all'] as $item)
                            <div class="card">
                                <div class="card-header bg-success text-white px-3 py-1">
                                    <div class="row">
                                        <div class="col-md-10" data-toggle="collapse" data-target="#{{$item['id']}}_all" role="button" aria-expanded="false" aria-controls="{{$item['id']}}_all">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <i class="fa fa-arrow-down"></i>
                                                    multipayment
                                                </div>
                                                <div aria-hidden="true">
                                                    ID : {{$item['id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Payment ID : {{$item['payment_id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Status : {{$item['status']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Total Amount : BRL {{number_format($item['amount'] / 100,2,",",".")}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{$url}}/admin/wirecard-approve/{{$item['payment_id']}}" class="btn btn-primary btn-sm" @if($item['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-check"></i> Approve</a>
                                            <a href="{{$url}}/admin/wirecard-cancel/{{$item['payment_id']}}" class="btn btn-danger btn-sm" @if($item['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-stop-circle"></i> Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse" id="{{$item['id']}}_all">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Payment ID</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Escrow ID</th>
                                            <th scope="col">Escrow Status</th>
                                            <th scope="col">Refund Status</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($item['payments']))

                                            @foreach($item['payments'] as $payment)
                                                <tr>
                                                    <th scope="row">{{$payment['id']}}</th>
                                                    <td>{{$payment['payment_id']}}</td>
                                                    <td>{{$payment['status']}}</td>
                                                    <td>{{$payment['escrow_id']}}</td>
                                                    <td>{{$payment['escrow_status']}}</td>
                                                    <td>{{$payment['refunds_status']}}</td>
                                                    <td>BRL {{number_format($payment['amount'] / 100,2,",",".")}}</td>
                                                    <td>
                                                        <a href="{{$url}}/admin/wirecard-release/{{$payment['escrow_id']}}" class="btn btn-success btn-sm" @if(@$payment['status'] != "AUTHORIZED" || @$payment['escrow_status'] == "RELEASED") style="display:none;" @endif><i class="fa fa-money"></i> Release</a>
                                                        <a href="{{$url}}/admin/wirecard-refunds/{{$payment['payment_id']}}" class="btn btn-success btn-sm" @if(@$payment['status'] != "AUTHORIZED" || @$payment['escrow_status'] != "RELEASED" || @$payment['refunds_status'] == "COMPLETED") style="display:none;" @endif><i class="fa fa-money"></i> Refunds</a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-waiting" role="tabpanel" aria-labelledby="pills-waiting-tab">
                    @if(empty($output['waiting']))
                        <h4 class="text-center">No Transaction Yet!</h4>
                    @else
                        @foreach($output['waiting'] as $item_waiting)
                            <div class="card">
                                <div class="card-header bg-success text-white px-3 py-1">
                                    <div class="row">
                                        <div class="col-md-10" data-toggle="collapse" data-target="#{{$item_waiting['id']}}_waiting" role="button" aria-expanded="false" aria-controls="{{$item_waiting['id']}}_waiting">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <i class="fa fa-arrow-down"></i>
                                                    multipayment
                                                </div>
                                                <div aria-hidden="true">
                                                    ID : {{$item_waiting['id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Payment ID : {{$item_waiting['payment_id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Status : {{$item_waiting['status']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Total Amount : BRL {{number_format($item_waiting['amount'] / 100,2,",",".")}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{$url}}/admin/wirecard-approve/{{$item_waiting['payment_id']}}" class="btn btn-primary btn-sm" @if($item_waiting['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-check"></i> Approve</a>
                                            <a href="{{$url}}/admin/wirecard-cancel/{{$item_waiting['payment_id']}}" class="btn btn-danger btn-sm" @if($item_waiting['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-stop-circle"></i> Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse" id="{{$item_waiting['id']}}_waiting">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Payment ID</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Escrow ID</th>
                                            <th scope="col">Escrow Status</th>
                                            <th scope="col">Refund Status</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($item_waiting['payments']))

                                            @foreach($item_waiting['payments'] as $payment_waiting)
                                                <tr>
                                                    <th scope="row">{{$payment_waiting['id']}}</th>
                                                    <td>{{$payment_waiting['payment_id']}}</td>
                                                    <td>{{$payment_waiting['status']}}</td>
                                                    <td>{{$payment_waiting['escrow_id']}}</td>
                                                    <td>{{$payment_waiting['escrow_status']}}</td>
                                                    <td>{{$payment_waiting['refunds_status']}}</td>
                                                    <td>BRL {{number_format($payment_waiting['amount'] / 100,2,",",".")}}</td>
                                                </tr>

                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
                <div class="tab-pane fade" id="pills-authorized" role="tabpanel" aria-labelledby="pills-authorized-tab">
                    @if(empty($output['authorized']))
                        <h4 class="text-center">No Transaction Yet!</h4>
                    @else
                        @foreach($output['authorized'] as $item_authorized)
                            <div class="card">
                                <div class="card-header bg-success text-white px-3 py-1">
                                    <div class="row">
                                        <div class="col-md-10" data-toggle="collapse" data-target="#{{$item_authorized['id']}}_authorized" role="button" aria-expanded="false" aria-controls="{{$item_authorized['id']}}_authorized">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <i class="fa fa-arrow-down"></i>
                                                    multipayment
                                                </div>
                                                <div aria-hidden="true">
                                                    ID : {{$item_authorized['id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Payment ID : {{$item_authorized['payment_id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Status : {{$item_authorized['status']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Total Amount : BRL {{number_format($item_authorized['amount'] / 100,2,",",".")}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{$url}}/admin/wirecard-approve/{{$item_authorized['payment_id']}}" class="btn btn-primary btn-sm" @if($item_authorized['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-check"></i> Approve</a>
                                            <a href="{{$url}}/admin/wirecard-cancel/{{$item_authorized['payment_id']}}" class="btn btn-danger btn-sm" @if($item_authorized['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-stop-circle"></i> Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse" id="{{$item_authorized['id']}}_authorized">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Payment ID</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Escrow ID</th>
                                            <th scope="col">Escrow Status</th>
                                            <th scope="col">Refund Status</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($item_authorized['payments']))

                                            @foreach($item_authorized['payments'] as $payment_authorized)
                                                <tr>
                                                    <th scope="row">{{$payment_authorized['id']}}</th>
                                                    <td>{{$payment_authorized['payment_id']}}</td>
                                                    <td>{{$payment_authorized['status']}}</td>
                                                    <td>{{$payment_authorized['escrow_id']}}</td>
                                                    <td>{{$payment_authorized['escrow_status']}}</td>
                                                    <td>{{$payment_authorized['refunds_status']}}</td>
                                                    <td>BRL {{number_format($payment_authorized['amount'] / 100,2,",",".")}}</td>
                                                    <td>
                                                        <a href="{{$url}}/admin/wirecard-release/{{$payment_authorized['escrow_id']}}" class="btn btn-success btn-sm" @if(@$payment_authorized['status'] != "AUTHORIZED" || @$payment_authorized['escrow_status'] == "RELEASED") style="display:none;" @endif><i class="fa fa-money"></i> Release</a>
                                                        <a href="{{$url}}/admin/wirecard-refunds/{{$payment_authorized['payment_id']}}" class="btn btn-success btn-sm" @if(@$payment_authorized['status'] != "AUTHORIZED" || @$payment_authorized['escrow_status'] != "RELEASED" || @$payment_authorized['refunds_status'] == "COMPLETED") style="display:none;" @endif><i class="fa fa-money"></i> Refunds</a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
                <div class="tab-pane fade" id="pills-cancelled" role="tabpanel" aria-labelledby="pills-cancelled-tab">
                    @if(empty($output['cancelled']))
                        <h4 class="text-center">No Transaction Yet!</h4>
                    @else
                        @foreach($output['cancelled'] as $item_cancelled)
                            <div class="card">
                                <div class="card-header bg-success text-white px-3 py-1">
                                    <div class="row">
                                        <div class="col-md-10" data-toggle="collapse" data-target="#{{$item_cancelled['id']}}_cancelled" role="button" aria-expanded="false" aria-controls="{{$item_cancelled['id']}}_cancelled">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <i class="fa fa-arrow-down"></i>
                                                    multipayment
                                                </div>
                                                <div aria-hidden="true">
                                                    ID : {{$item_cancelled['id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Payment ID : {{$item_cancelled['payment_id']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Status : {{$item_cancelled['status']}}
                                                </div>
                                                <div aria-hidden="true">
                                                    Total Amount : BRL {{number_format($item_cancelled['amount'] / 100,2,",",".")}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{$url}}/admin/wirecard-approve/{{$item_cancelled['payment_id']}}" class="btn btn-primary btn-sm" @if($item_cancelled['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-check"></i> Approve</a>
                                            <a href="{{$url}}/admin/wirecard-cancel/{{$item_cancelled['payment_id']}}" class="btn btn-danger btn-sm" @if($item_cancelled['status'] != "PRE_AUTHORIZED") style="display:none;" @endif><i class="fa fa-stop-circle"></i> Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse" id="{{$item_cancelled['id']}}_cancelled">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Payment ID</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Escrow ID</th>
                                            <th scope="col">Escrow Status</th>
                                            <th scope="col">Refund Status</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($item_cancelled['payments']))

                                            @foreach($item_cancelled['payments'] as $payment_cancelled)
                                                <tr>
                                                    <th scope="row">{{$payment_cancelled['id']}}</th>
                                                    <td>{{$payment_cancelled['payment_id']}}</td>
                                                    <td>{{$payment_cancelled['status']}}</td>
                                                    <td>{{$payment_cancelled['escrow_id']}}</td>
                                                    <td>{{$payment_cancelled['escrow_status']}}</td>
                                                    <td>{{$payment_cancelled['refunds_status']}}</td>
                                                    <td>BRL {{number_format($payment_cancelled['amount'] / 100,2,",",".")}}</td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
                <div class="tab-pane fade" id="pills-refunds" role="tabpanel" aria-labelledby="pills-refunds-tab">
                    @if(empty($output['refunds']))
                        <h4 class="text-center">No Transaction Refund Yet!</h4>
                    @else
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Ref ID</th>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Payment ID</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Card</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($output['refunds'] as $item_refunds)
                                                <tr>
                                                    <th scope="row">{{$item_refunds['id']}}</th>
                                                    <td>{{$item_refunds['ref_id']}}</td>
                                                    <td>{{$item_refunds['order_id']}}</td>
                                                    <td>{{$item_refunds['payment_id']}}</td>
                                                    <td>{{$item_refunds['type']}}</td>
                                                    <td>{{$item_refunds['currency']}} {{number_format($item_refunds['amount'] / 100,2,",",".")}}</td>
                                                    <td>{{$item_refunds['status']}}</td>
                                                    <td>{{$item_refunds['card']}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
<script src="//code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
