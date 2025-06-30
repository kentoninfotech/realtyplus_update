@extends('layouts.template')

@section('content')
<!-- @php $pagename="namesearch"; $sn=1; @endphp -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Personnel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">New Personnel</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="card">
        <div class="card-heading">
        </div>
        <div class="card-body">
            <a href="{{ url()->previous() }}" class="btn btn-md btn-primary" style="float: right;">Back</a>
            <br>

            <form method="POST" action="{{ route('create.personnel') }}" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                @endif

                <input type="hidden" name="business_id">
                <input type="hidden" name="user_id">

                <div class="row form-group">
                    <div class="col-lg-4">
                        <label class="control-label col-lg-12" for="content">Firstname:</label>
                        <input name="firstname" type="text" class="form-control" id="first Name" maxlength="50" placeholder="First Name" value="{{ old('firstname') }}">
                    </div>
                    <div class="col-lg-4">
                        <label class="control-label col-lg-12" for="content">Lastname: </label>
                        <input name="lastname" type="text" class="form-control" placeholder="Last Name" value="{{ old('lastname') }}" maxlength="50">
                    </div>
                    <div class="col-lg-4">
                        <label class="control-label col-lg-12" for="content">Othernames: </label>
                        <input name="othername" type="text" class="form-control" placeholder="Othernames" value="{{ old('othername') }}" maxlength="50">
                    </div>
                </div>

                    <div class="row form-group">
                        <div class="col-lg-3">
                        <label class="control-label col-lg-12" for="content">Date of Birth: </label>
                    <input name="dob" type="date" class="form-control" placeholder="Date of Birth" maxlength="50" value="{{ old('dob') }}" id="datepicker">
                    </div>
                    <div class="col-lg-3">
                        <label class="control-label col-lg-12" for="content">State of Origin:</label>
                        <input name="state_of_origin" list="state" class="form-control" id="state" maxlength="50" value="{{ old('state_of_origin') }}" placeholder="State of Origin">
                        <datalist id="state">
                            <option value="Abia">
                            <option value="Adamawa">
                            <option value="Akwa Ibom">
                            <option value="Anambra">
                            <option value="Bauchi">
                            <option value="Bayelsa">
                            <option value="Benue">
                            <option value="Borno">
                            <option value="Cross River">
                            <option value="Delta">
                            <option value="Ebonyi">
                            <option value="Edo">
                            <option value="Ekiti">
                            <option value="Enugu">
                            <option value="Federal Capital Territory">
                            <option value="Gombe">
                            <option value="Imo">
                            <option value="Jigawa">
                            <option value="Kaduna">
                            <option value="Kano">
                            <option value="Katsina">
                            <option value="Kebbi">
                            <option value="Kogi">
                            <option value="Kwara">
                            <option value="Lagos">
                            <option value="Nasarawa">
                            <option value="Niger">
                            <option value="Ogun">
                            <option value="Ondo">
                            <option value="Osun">
                            <option value="Oyo">
                            <option value="Plateau">
                            <option value="Rivers">
                            <option value="Sokoto">
                            <option value="Taraba">
                            <option value="Yobe">
                            <option value="Zamfara">
                        </datalist>
                    </div>
                    <div class="col-lg-3">
                        <label class="control-label col-lg-12" for="content">Nationality </label>
                        <input name="nationality" type="text" class="form-control" placeholder="Nationality" maxlength="50" value="{{ old('nationality') }}">
                    </div>
                    <div class="col-lg-3">
                        <label class="control-label col-lg-12" for="content">Marital Status: </label>
                        <select name="marital_status" class="form-control">
                            <option disabled {{ old('marital_status') ? '' : 'selected' }}>Marital Status</option>
                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                        </select>
                    </div>
                    </div>

                    <div class="row center"><h4>Contact Information</h4></div>
                    <div class="row form-group">
                        <div class="col-lg-4">
                            <label class="control-label col-lg-12" for="content">Phone No: </label>
                            <input name="phone_number" type="text" class="form-control" placeholder="Phone Number" maxlength="50" value="{{ old('phone_number') }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label col-lg-12" for="content">E-mail:</label>
                            <input name="email" type="email" class="form-control" id="titLe" maxlength="100" placeholder="E-mail" value="{{ old('email') }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label col-lg-12" for="content">Address: </label>
                            <textarea col="2" row="" name="address" type="text" class="form-control" placeholder="Address" maxlength="100">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-lg-6">
                            <div class="row center"><h4>Educational Information</h4><hr></div>
                            <label class="control-label col-lg-12" for="content">Highest Certificate / School Name / Year of Graduation: </label>
                            <input name="highest_certificate" list="certificate" class="form-control" placeholder="Highest Certificate" maxlength="50"  value="{{ old('highest_certificate') }}">
                            <datalist id="certificate">
                                <option value="O'Level">
                                <option value="OND">
                                <option value="ND">
                                <option value="HND">
                                <option value="Bsc">
                                <option value="PHd">
                                <option value="Msc">
                                <option value="LLB">
                                <option value="B.Eng">
                                <option value="M.Eng">
                            </datalist>
                        </div>
                        <div class="col-lg-6">
                            <div class="row center"><h4>System Role</h4><hr></div>
                            <label class="control-label col-lg-12" for="content">Role: <i class="fas fa-help"></i></label>
                            <select name="role" class="form-control">
                                <option value="other" {{ old('other') == 'other' ? 'selected' : '' }}>Others(No role)</option>
                                @foreach ($roles as $role)
                                    <!-- <option value="{{ $role }}" {{ old('role', $role->name === $role->name) || $role->name === 'Staff' ? 'selected' : '' }}>{{ $role->name }}</option> -->
                                    <option value="{{ $role }}" {{ old('role') == $role->name ? 'selected' : ''}}>{{ $role->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="row center"><h4>Official Information</h4><hr></div>
                    <div class="row form-group">
                        <div class="col-lg-3">
                            <label class="control-label col-lg-12" for="content">Date Employed: </label>
                            <input name="employment_date" type="date" class="form-control date" placeholder="Date Employed" id="datepicker2" maxlength="50" value="{{ old('empdate') }}">
                        </div>
                        <div class="col-lg-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control" id="department" name="department">
                                 <option selected disabled>Choose Department</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="designation" class="form-label">Designation</label>
                            <select class="form-control" id="designation" name="designation" disabled>
                                <option selected disabled>Choose Designation</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="control-label col-lg-12" for="content">Basic Salary: </label>
                            <input name="salary" type="number" class="form-control" placeholder="Salary" value="{{ old('salary') }}" maxlength="30">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-lg-3">
                            <label class="control-label col-lg-12" for="content">Passport: </label>
                            <input name="picture" type="file" class="form-conrol" value="{{ old('picture') }}">
                            @if (old('picture'))
                                <div class="text-success">Previously selected: {{ old('picture') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            <label class="control-label col-lg-12" for="content">Upload CV: </label>
                            <input name="cv" type="file" class="form-conrol" value="{{ old('cv') }}">
                            @if (old('cv'))
                                <div class="text-success">Previously selected: {{ old('cv') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            <label class="control-label col-lg-12" for="content">Personnel Type: </label>
                            <select name="user_type" class="form-control">
                                <option disabled {{ old('user_type') ? '' : 'selected' }}>Personnel Type</option>
                                <option value="staff" {{ old('user_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="worker" {{ old('user_type') == 'worker' ? 'selected' : '' }}>Worker</option>
                                <option value="contractor" {{ old('user_type') == 'contractor' ? 'selected' : '' }}>Contractor</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="control-label col-lg-12" for="content">Password: </label>
                            <input name="password" type="password" class="form-control" placeholder="Password">
                        </div>
                    </div>

                    <div class="row form-group" style="margin-top:20px !important;">
                        <div class="col-lg-6">
                        <label class="control-label col-lg-12" for="content">. </label>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label col-lg-12" for="content">.</label>
                        <input type="submit" value="Save Personnel Record" class="btn btn-primary" />
                    </div>
                    </div>

            </form>
        </div>
    </div>

@endsection

@section('script')
<script>
    const departmentDesignations = {
        "Project Management": [
        "Project Director",
        "Project Manager",
        "Assistant Project Manager",
        "Project Coordinator",
        "Site Supervisor",
        "Site Engineer"
        ],
        "Engineering": [
        "Chief Engineer",
        "Structural Engineer",
        "Civil Engineer",
        "Mechanical Engineer",
        "Electrical Engineer",
        "Plumbing Engineer",
        "Design Engineer",
        "Quantity Surveyor"
        ],
        "Architecture & Design": [
        "Chief Architect",
        "Senior Architect",
        "Junior Architect",
        "CAD Draftsman",
        "3D Visualizer"
        ],
        "Procurement & Logistics": [
        "Procurement Manager",
        "Procurement Officer",
        "Store Manager",
        "Logistics Coordinator",
        "Inventory Officer",
        "Store Keeper"
        ],
        "Health, Safety & Environment (HSE)": [
        "HSE Manager",
        "HSE Officer",
        "Safety Inspector",
        "Environmental Engineer"
        ],
        "Quality Control & Assurance": [
        "Quality Control Manager",
        "QA/QC Engineer",
        "Quality Inspector",
        "Material Tester"
        ],
        "Finance & Accounts": [
        "Chief Financial Officer (CFO)",
        "Finance Manager",
        "Accountant",
        "Accounts Officer",
        "Payroll Officer"
        ],
        "Human Resources (HR)": [
        "HR Manager",
        "HR Officer",
        "Recruitment Specialist",
        "Training & Development Officer"
        ],
        "Administration": [
        "Admin Manager",
        "Office Administrator",
        "Document Controller",
        "Receptionist"
        ],
        "Legal & Compliance": [
        "Legal Advisor",
        "Contract Manager",
        "Compliance Officer"
        ],
        "Marketing & Business Development": [
        "Business Development Manager",
        "Marketing Manager",
        "Tendering Officer",
        "Proposal Engineer"
        ],
        "IT & Systems": [
        "IT Manager",
        "System Administrator",
        "Network Engineer",
        "Software Support Specialist"
        ]
    };

    const departmentSelect = document.getElementById("department");
    const designationSelect = document.getElementById("designation");

    // Populate departments
    Object.keys(departmentDesignations).forEach(dept => {
        const option = document.createElement("option");
        option.value = dept;
        option.textContent = dept;
        departmentSelect.appendChild(option);
    });

    // Handle department change
    departmentSelect.addEventListener("change", function () {
        const selectedDept = this.value;
        const designations = departmentDesignations[selectedDept] || [];

        designationSelect.innerHTML = `<option selected disabled>Choose Designation</option>`;
        designations.forEach(title => {
        const option = document.createElement("option");
        option.value = title;
        option.textContent = title;
        designationSelect.appendChild(option);
        });

        designationSelect.disabled = designations.length === 0;
    });
</script>
@endsection
