@extends('layouts.template')

<style>
    .transaction-form {
        background: #f8f9fa;
        padding: 30px 0 80px 0;
        width: 100%;
    }
    
    .form-section {
        background: white;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #007bff;
    }
    
    .form-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: center;
    }
    
    .form-section h3 i {
        margin-right: 10px;
        color: #007bff;
        font-size: 20px;
    }
    
    .form-group label {
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
    }
    
    .form-control, .custom-select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px 12px;
        font-size: 14px;
    }
    
    .form-control:focus, .custom-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #004085;
    }
    
    .amount-display {
        font-size: 32px;
        font-weight: 700;
        color: #28a745;
        margin: 15px 0;
    }
    
    .payment-progress {
        margin-top: 20px;
        padding: 15px;
        background: #f0f0f0;
        border-radius: 4px;
    }
    
    .payment-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 4px;
        margin-top: 15px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 500;
        color: #666;
    }
    
    .detail-value {
        font-weight: 600;
        color: #333;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 500;
        border-radius: 4px;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .hidden-section {
        display: none;
    }
    
    .badge-info {
        display: inline-block;
        padding: 6px 12px;
        background: #e7f3ff;
        color: #004085;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        margin-left: 10px;
    }
</style>

@section('content')
    <div class="transaction-form">
        <div class="container-fluid">
            {{-- Header --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h1 class="m-0" style="font-size: 28px; font-weight: 700; color: #333;">
                                <i class="fas fa-plus-circle"></i> Record New Transaction
                            </h1>
                            <p style="color: #999; margin-top: 5px;">Create and manage payments, part payments, and transaction records</p>
                        </div>
                        <a href="{{ route('property.transaction') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Transactions
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('create.transaction') }}" enctype="multipart/form-data">
                @csrf

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Please Fix These Errors</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                {{-- Section 1: What are we paying for? --}}
                <div class="form-section">
                    <h3><i class="fas fa-list"></i> What Are We Recording a Transaction For?</h3>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="transactionable_type">Transaction Category <span style="color: red;">*</span></label>
                            <select name="transactionable_type" id="transactionable_type" class="form-control" required>
                                <option value="">-- Select Category --</option>
                                @foreach($transactionable as $tr)
                                   <option value="{{ $tr }}" {{ old('transactionable_type') == $tr ? 'selected' : '' }}>
                                       @if($tr === 'App\\Models\\Lease')
                                           Lease Payment
                                       @elseif($tr === 'App\\Models\\Property')
                                           Property Transaction
                                       @elseif($tr === 'App\\Models\\PropertyUnit')
                                           Property Unit Transaction
                                       @elseif($tr === 'App\\Models\\UnitSale')
                                           Unit/Property Sale
                                       @elseif($tr === 'App\\Models\\MaintenanceRequest')
                                           Maintenance Request
                                       @else
                                           {{ class_basename($tr) }}
                                       @endif
                                   </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Select what this payment is for</small>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="transactionable_id">Specific Item <span style="color: red;">*</span></label>
                            <select id="transactionable_id" name="transactionable_id" class="form-control" required></select>
                            <small class="form-text text-muted">Select the specific lease, property, unit, or maintenance request</small>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Who is paying? --}}
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Who Is Making This Payment?</h3>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="payer_type">Payer Type <span style="color: red;">*</span></label>
                            <select id="payer_type" name="payer_type" class="form-control" required>
                                <option value="">-- Select Payer Type --</option>
                                @foreach($payerType as $py)
                                    <option value="{{ $py }}" {{ old('payer_type') == $py ? 'selected' : '' }}>
                                        @if($py === 'App\\Models\\Tenant')
                                            <i class="fas fa-person"></i> Tenant
                                        @elseif($py === 'App\\Models\\Owner')
                                            <i class="fas fa-key"></i> Owner
                                        @elseif($py === 'App\\Models\\Agent')
                                            <i class="fas fa-user-tie"></i> Agent
                                        @elseif($py === 'App\\Models\\Client')
                                            <i class="fas fa-briefcase"></i> Client
                                        @else
                                            {{ class_basename($py) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="payer_id">Select Payer <span style="color: red;">*</span></label>
                            <select id="payer_id" name="payer_id" class="form-control" required></select>
                            <small class="form-text text-muted">Search by name, email, or phone</small>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Payment Details --}}
                <div class="form-section">
                    <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
                    
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="type">Payment Type <span style="color: red;">*</span></label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="credit" selected>Income (Credit)</option>
                                <option value="debit">Expense (Debit)</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="amount">Amount Paid <span style="color: red;">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₦</span>
                                </div>
                                <input type="number" value="{{ old('amount') }}" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="status">Payment Status <span style="color: red;">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach($status as $st)
                                <option value="{{ $st }}" {{ old('status', 'completed') == $st ? 'selected' : '' }}>{{ Str::headline($st) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <strong>Partial Payments:</strong> You can record multiple transactions for the same item to track part payments. Each transaction will be recorded individually and the status will show payment progress.
                    </div>
                </div>

                {{-- Section 4: Transaction Information --}}
                <div class="form-section">
                    <h3><i class="fas fa-receipt"></i> Transaction Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="transaction_date">Transaction Date <span style="color: red;">*</span></label>
                            <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="payment_method">Payment Method <span style="color: red;">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                @foreach($method as $m)
                                     <option value="{{ $m }}" {{ old('payment_method') == $m ? 'selected' : '' }}>{{ Str::headline($m) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="reference_number">Reference / Cheque Number (optional)</label>
                            <input type="text" value="{{ old('reference_number') }}" class="form-control" id="reference_number" name="reference_number" maxlength="191" placeholder="e.g., CHQ-2026-001">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="purpose">Purpose / Reason for Payment <span style="color: red;">*</span></label>
                        <select id="purpose" name="purpose" class="form-control" required>
                            @foreach($purposes as $p)
                                <option value="{{ $p }}" {{ old('purpose') == $p ? 'selected' : '' }}>{{ Str::headline($p) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Additional Notes / Description (optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Add any relevant notes, payment conditions, or special instructions...">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- Section 5: Supporting Documents --}}
                <div class="form-section">
                    <h3><i class="fas fa-paperclip"></i> Supporting Documents (Optional)</h3>
                    
                    <div class="form-group">
                        <label for="documents">Attach Payment Proof</label>
                        <div class="custom-file-upload" style="border: 2px dashed #007bff; padding: 30px; border-radius: 4px; text-align: center; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #007bff; margin-bottom: 10px; display: block;"></i>
                            <input type="file" class="form-control-file" id="documents" name="documents[]" multiple style="display: none;">
                            <p style="margin: 0; color: #666;">
                                <strong>Click to upload or drag and drop</strong><br>
                                <small>PNG, JPG, PDF (Max 10MB each) - Evidence of payment, receipts, cheques, etc.</small>
                            </p>
                        </div>
                        <div id="file-list" style="margin-top: 10px;"></div>
                    </div>
                </div>

                {{-- Section 6: Summary & Actions --}}
                <div class="form-section" style="border-left-color: #28a745;">
                    <h3 style="color: #28a745;"><i class="fas fa-check-circle"></i> Ready to Record?</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-save"></i> Record Transaction
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('property.transaction') }}" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            $('#payer_id').select2({
                placeholder: 'Select payer...',
                ajax: {
                    url: '{{ route("payers.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            type: $('#payer_type').val()
                        };
                    },
                    processResults: function(data) {
                        return { results: data }; // already in {id, text} format
                    },
                    cache: true
                }
            });

            $('#payer_type').on('change', function() {
                $('#payer_id').val(null).trigger('change');
            });

            $('#transactionable_id').select2({
                placeholder: 'Select transactionable...',
                ajax: {
                    url: '{{ route("transactionables.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            type: $('#transactionable_type').val()
                        };
                    },
                    processResults: function(data) {
                        return { results: data }; // already in {id, text} format
                    },
                    cache: true
                }
            });

            $('#transactionable_type').on('change', function() {
                $('#transactionable_id').val(null).trigger('change');
            });

            // File upload handler with drag and drop
            const fileUploadZone = document.querySelector('.custom-file-upload');
            const fileInput = document.getElementById('documents');
            const fileList = document.getElementById('file-list');

            if (fileUploadZone && fileInput) {
                // Click to upload
                fileUploadZone.addEventListener('click', () => fileInput.click());

                // Drag and drop
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    fileUploadZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    fileUploadZone.addEventListener(eventName, () => {
                        fileUploadZone.style.borderColor = '#0056b3';
                        fileUploadZone.style.backgroundColor = '#e7f3ff';
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    fileUploadZone.addEventListener(eventName, () => {
                        fileUploadZone.style.borderColor = '#007bff';
                        fileUploadZone.style.backgroundColor = 'transparent';
                    });
                });

                fileUploadZone.addEventListener('drop', (e) => {
                    fileInput.files = e.dataTransfer.files;
                    updateFileList();
                });

                fileInput.addEventListener('change', updateFileList);

                function updateFileList() {
                    fileList.innerHTML = '';
                    if (fileInput.files.length > 0) {
                        const ul = document.createElement('ul');
                        ul.style.listStyle = 'none';
                        ul.style.padding = '0';
                        
                        Array.from(fileInput.files).forEach((file, index) => {
                            const li = document.createElement('li');
                            li.style.padding = '8px';
                            li.style.marginBottom = '5px';
                            li.style.background = '#f0f0f0';
                            li.style.borderRadius = '4px';
                            li.style.display = 'flex';
                            li.style.justifyContent = 'space-between';
                            li.style.alignItems = 'center';
                            
                            const fileName = document.createElement('span');
                            fileName.innerHTML = `<i class="fas fa-file"></i> ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                            
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'btn btn-sm btn-outline-danger';
                            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                            removeBtn.addEventListener('click', (e) => {
                                e.preventDefault();
                                const dataTransfer = new DataTransfer();
                                Array.from(fileInput.files).forEach((f, i) => {
                                    if (i !== index) dataTransfer.items.add(f);
                                });
                                fileInput.files = dataTransfer.files;
                                updateFileList();
                            });
                            
                            li.appendChild(fileName);
                            li.appendChild(removeBtn);
                            ul.appendChild(li);
                        });
                        fileList.appendChild(ul);
                    }
                }
            }

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const amount = parseFloat(document.getElementById('amount').value);
                if (isNaN(amount) || amount <= 0) {
                    e.preventDefault();
                    Swal.fire('Validation Error', 'Please enter a valid amount greater than 0', 'error');
                }
            });

        });
    });
</script>

