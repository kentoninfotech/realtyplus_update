@extends('layouts.print-template')

@section('content')

    <h4 class="page-title" style="font-weight: bold; text-align: center;">Material Details for : {{$material->name}}</h4>
    <div class="row" style="margin: 10px auto; margin: 20px;">



                <h3 style="clear: both;">
                            <b>Available in Stock: </b> {{$material->stock->quantity}}
                </h3>



                    <table class="table responsive-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Measurement Unit</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td><b>{{$material->type}}</b></td>
                                    <td>{{$material->size}}</td>
                                    <td>{{$material->measurement_unit}}</td>
                                    <td>{{$material->cost_per}}</td>
                                </tr>
                        </tbody>
                    </table>
                    <hr>

                    <h4>Supplied from Vendors</h4>
                    <table class="table responsive-table">
                        <thead>
                            <tr style="color: ">
                                <th>Supplier/Company</th>
                                <th>Supply Batch</th>
                                <th>Confirmed By</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($susupplies as $msu)
                                <tr>
                                    <td>{{$msu->supplier->supplier_name}} / {{$msu->supplier->company_name}}</td>
                                    <td>{{$msu->batchno}}</td>
                                    <td><b>{{$msu->comfirmedby->name}}</b></td>
                                    <td>{{$msu->quantity}}</td>
                                    <td>{{$msu->cost_per}}</td>
                                    <td>{{$msu->total_amount}}</td>
                                    <td>{{$msu->date_supplied}}</td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td colspan="3">Total Supplied:</td>
                                    <td colspan="2">{{$totalqsupplied = $susupplies->sum('quantity')}}</td>
                                    <td>{{$totalasupplied = $susupplies->sum('total_amount')}}</td>
                                    <td></td>
                                </tr>
                        </tbody>
                    </table>
                    <hr>

                    <h4>Material Checked Out for Project</h4>
                    <table class="table responsive-table">
                        <thead>
                            <tr style="color: ">
                                <td>Job</td>
                                <th>Project</th>
                                <th>Quantity</th>
                                <th>Details</th>
                                <th>Checkout By</th>
                                <th>Approved By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mcheckouts as $mtc)

                            <tr>
                                <td><b>{{$mtc->task->subject ?? ''}}</b></td>
                                <td><b>{{$mtc->task->project->title ?? ''}}</b></td>
                                <td><b>{{$mtc->quantity ?? ''}}{{$mtc->material->measurement_unit ?? ''}}</b></td>
                                <td>{{$mtc->details ?? ''}}</td>
                                <td>{{$mtc->checkoutby->name ?? ''}}</td>
                                <td>{{$mtc->approvedby->name ?? ''}}</td>
                                <td>{{$mtc->dated ?? ''}}</td>
                            </tr>
                        @endforeach

                                <tr>
                                    <td>Total Checked Out:</td>
                                    <td colspan="5">{{$totalqcout = $mcheckouts->sum('quantity')}}</td>
                                </tr>
                        </tbody>
                    </table>
                    <p>Quantity Deficit: {{($totalqsupplied)-($totalqcout+$material->stock->quantity)}} (<small><b>Key:</b> Total Supplied - (Total Sold + Stock) = 0</small>)</p>



    </div>


@endsection
