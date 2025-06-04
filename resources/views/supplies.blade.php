@extends('layouts.template')

@section('content')
    @php $modal="supplies"; $pagetype = "Table"; @endphp

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Suppliers</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Suppliers</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <h3 class="page-title">Building Material Supplies | <small style="color: green">List</small></h3>
    <div class="row">
            <div class="card">
                <div class="card-heading">
                     @canany(['create material_supply', 'create material_checkout', 'create material_damage', 'create material_stock'])
                        <a href="#" class="btn btn-primary float-right mt-3 mx-3" data-toggle="modal" data-target="#supply" id="supplyupdate">Add New</a>
                     @endcanany
                    </div>
                <div class="card-body">
                    <table class="table responsive-table" id="products">
                        <thead>
                            <tr style="color: ">
                                <th>Batch No.</th>
                                <th>Material/Product</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Cost Per Unit</th>
                                <th>Total Amount</th>
                                <th>Location/Facility</th>
                                <th>Date Supplied</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supplies as $sp)
                                <tr>
                                    <td><b>{{$sp->batchno}}</b></td>
                                    <td><b>{{$sp->material->name}}</b></td>
                                    <td><b>{{$sp->supplier->supplier_name}} / {{$sp->supplier->company_name}}</b></td>
                                    <td>{{$sp->quantity}}</td>
                                    <td>{{$sp->cost_per}}</td>
                                    <td>{{$sp->total_amount}}</td>
                                    <td>{{$sp->business->business_name}}</td>
                                    <td>{{$sp->date_supplied}}</td>
                                    <td>
                                      <div class="btn-group">
                                        @canany(['edit material_supply', 'edit material_checkout', 'edit material_damage', 'edit material_stock'])
                                          <button class="btn btn-primary btn-xs" id="ach{{$sp->id}}" onclick="supply({{$sp->id}})"  data-toggle="modal" data-target="#supply" data-supplier_id="{{$sp->supplier_id}}" data-material_id="{{$sp->material_id}}" data-quantity="{{$sp->quantity}}" data-cost_per="{{$sp->cost_per}}"  data-total_amount="{{$sp->total_amount}}" data-date_supplied="{{$sp->date_supplied}}" data-business_id="{{$sp->business_id}}"  data-batchno="{{$sp->batchno}}" data-confirmed_by="{{$sp->confirmed_by}}">Edit</button>
                                        @endcanany
                                        @canany(['delete material_supply', 'delete material_checkout', 'delete material_damage', 'delete material_stock'])
                                          <a href="/delete-sp/{{$sp->id}}" class="btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete the supply: {{$sp->material->name}}?')">Delete</a>
                                        @endcanany  
                                    </div>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{$supplies->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>


    <!-- Button to Open the Modal -->


  <!-- The Modal -->
  <div class="modal" id="supply">
    <div class="modal-dialog modal-lg"  style="width: 90%">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add New Supply</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <form method="POST" action="{{ route('addsupply') }}" id="supplyform" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="updating" id="updating" value="Yes">
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="material_id">Material</label>
                        <select class="form-control" name="material_id" id="material_id">
                            @foreach ($business->materials as $sma)
                                <option value="{{$sma->id}}">{{$sma->name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-control" name="supplier_id" id="supplier_id">
                            @foreach ($business->suppliers as $ssu)
                                <option value="{{$ssu->id}}">{{$ssu->supplier_name}} ({{$ssu->company_name}})</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="batchno">Batch No</label>
                        <input type="text" name="batchno" id="batchno" class="form-control" placeholder="Reference, Invoice No etc">
                    </div>


                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="quantity">Quantity</label>
                        <input type="number" step="0.01" name="quantity" id="quantity" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cost_per">Cost Per Unit</label>
                        <input type="number" step="0.01" name="cost_per" id="cost_per" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="total_amount">Total Amount (Naira)</label>
                        <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control">
                    </div>



                </div>

                <div class="row">

                  <div class="col-md-4">
                    <label>Date Supplied:</label>
                      <div class="input-group date" id="start_date_activator" data-target-input="nearest">
                          <input type="text" name="date_supplied" class="form-control datetimepicker-input" data-target="#start_date_activator">
                          <div class="input-group-append" data-target="#start_date_activator" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                      </div>
                  </div>

                    <div class="form-group col-md-4">

                        <label for="business_id" class="control-label">Facility / Location</label>
                        <select class="form-control" name="business_id" id="business_id">
                            @foreach ($userbusinesses as $set)
                                <option value="{{$set->id}}">{{$set->business_name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="confirmed_by">Confirmed By</label>
                        <select class="form-control" name="confirmed_by" id="confirmed_by">
                            @foreach ($business->personnel as $usr)
                                <option value="{{$usr->id}}">{{$usr->name}}</option>
                            @endforeach

                        </select>
                    </div>


                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="spbutton">
                        {{ __('Add Supply') }}
                    </button>
                </div>


            </form>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger modaldismiss" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>
@endsection
