@extends('layouts.template')

@section('content')
    @php $modal="material"; $pagetype = "Table"; @endphp

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
    <div class="row">
            <div class="card">
                <div class="card-heading">
                  @canany(['create material_supply', 'create material_checkout', 'create material_damage', 'create material_stock'])
                     <a href="#" class="btn btn-primary float-right mt-3 mx-3" data-toggle="modal" data-target="#supplier">Add New</a>
                  @endcanany
                    </div>
                <div class="card-body">
                  <table class="table responsive-table" id="products">
                      <thead>
                          <tr style="color: ">
                              <th>Contact Person</th>
                              <th>Company Name</th>
                              <th>Materials/Products</th>
                              <th>Phone Number</th>
                              <th>Details</th>
                              <th>Address</th>
                              <th>Location/Facility</th>
                              <th>Action</th>

                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($suppliers as $sup)

                              <tr>

                                  <td><b>{{$sup->supplier_name}}</b></td>
                                  <td><b>{{$sup->company_name}}</b></td>
                                  <td>{{$sup->category}}</td>
                                  <td>{{$sup->phone_number}}</td>
                                  <td>{{$sup->details}}</td>
                                  <td>{{$sup->address}}</td>
                                  <td>{{$sup->business->business_name}}</td>
                                  <td>
                                    <div class="btn-group">
                                      @canany(['edit material_supply', 'edit material_checkout', 'edit material_damage', 'edit material_stock'])
                                         <button class="btn btn-primary btn-xs" id="ach{{$sup->id}}" onclick="supplier({{$sup->id}})"  data-toggle="modal" data-target="#supplier" data-supplier_name="{{$sup->supplier_name}}" data-company_name="{{$sup->company_name}}" data-category="{{$sup->category}}" data-phone_number="{{$sup->phone_number}}"  data-details="{{$sup->details}}"  data-details="{{$sup->details}}"  data-address="{{$sup->address}}"  data-business_id="{{$sup->business_id}}">Edit</button>
                                      @endcanany
                                      @canany(['delete material_supply', 'delete material_checkout', 'delete material_damage', 'delete material_stock'])
                                         <a href="/delete-sup/{{$sup->id}}" class="btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete the supplier: {{$sup->supplier_name}}?')">Delete</a>
                                      @endcanany
                                    </div>
                                    </td>

                              </tr>
                          @endforeach


                      </tbody>
                  </table>
                  <div style="text-align: right">
                      {{$suppliers->links("pagination::bootstrap-4")}}
                  </div>
                </div>
            </div>


    </div>


    <!-- Button to Open the Modal -->


  <!-- The Modal -->
  <div class="modal" id="supplier">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add New Supplier</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

            <form method="POST" action="{{ route('addsupplier') }}" id="materialform" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="supplier_name">Contact Person Name</label>
                        <input type="text" name="supplier_name" id="supplier_name" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="company_name">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="type">Category</label>
                        <select class="form-control" name="category" id="category">
                          <option value="Consumable">Consumable</option>
                          <option value="Cleaning">Cleaning</option>
                          <option value="Machine Part">Machine Part</option>
                          <option value="Solid">Solid</option>
                          <option value="Liquid">Liquid</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="phone_number">phone_number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="details">Details</label>
                        <input type="text" name="details" id="details" class="form-control">
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" class="form-control">
                    </div>

                    <div class="form-group col-md-6">

                        <label for="business_id" class="control-label">Facility / Location</label>
                        <select class="form-control" name="business_id" id="business_id">
                            <option value="1" selected>Select Location</option>
                            @foreach ($userbusinesses as $set)
                                <option value="{{$set->id}}">{{$set->business_name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="supbutton">
                        {{ __('Add Supplier') }}
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
