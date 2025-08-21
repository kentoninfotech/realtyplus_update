@extends('layouts.template')

<style>
    /* Basic styling for hidden sections to prevent layout shifts */
    .hidden-section {
        display: none;
    }
</style>

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Transaction</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('property.transaction') }}">Transactions</a></li>
                        <li class="breadcrumb-item active">Make Payment</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Add Payment new Payment</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('create.transaction') }}" enctype="multipart/form-data" class="card card-body">
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

                {{-- Hidden: transactionable --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="transactionable_type">Transaction For</label>
                        <select name="transactionable_type" id="transactionable_type" class="form-control">
                            <option value="">-- Select Transaction Type --</option>
                            @foreach($transactionable as $tr)
                               <option value="{{ $tr }}" {{ old('transactionable_type') == $tr ? 'selected' : '' }}>{{ class_basename($tr) }}</option>
                            @endforeach
                        </select>
                    </div>
                     {{-- Transactionable ID Selector (AJAX) --}}
                    <div class="form-group col-md-6">
                        <label>Transactionable ID</label>
                        <select id="transactionable_id" name="transactionable_id" class="form-control"></select>
                    </div>
                </div>
                {{-- Payer Type --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Payer Type</label>
                        <select id="payer_type" name="payer_type" class="form-control">
                            <option value="">-- Select Payer Type --</option>
                            @foreach($payerType as $py)
                                <option value="{{ $py }}" {{ old('payer_type') == $py ? 'selected' : '' }}>{{ class_basename($py) }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Payer Selector (AJAX) --}}
                    <div class="form-group col-md-6">
                        <label>Payer</label>
                        <select id="payer_id" name="payer_id" class="form-control"></select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="type">Payment Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="credit" selected>Credit</option>
                            <option value="debit">Debit</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="purpose">Purpose</label>
                        <select id="purpose" name="purpose" class="form-control" required>
                            @foreach($purposes as $p)
                                <option value="{{ $p }}" {{ old('purpose') == $p ? 'selected' : '' }}>{{ Str::headline($p) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="amount">Amount</label>
                        <input type="number" value="{{ old('amount') }}" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="transaction_date">Transaction Date</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            @foreach($method as $m)
                                 <option value="{{ $m }}" {{ old('payment_method') == $m ? 'selected' : '' }}>{{ Str::headline($m) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="reference_number">Reference (optional)</label>
                        <input type="text" value="{{ old('reference_number') }}" class="form-control" id="reference_number" name="reference_number" maxlength="191">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="status">Status </label>
                        <select name="status" id="status" class="form-control">
                            @foreach($status as $st)
                            <option value="{{ $st }}" {{ old('status') == $st ? 'selected' : '' }}>{{ Str::headline($st) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="documents">Attach Documents (optional)</label>
                        <input type="file" class="form-control-file" id="documents" name="documents[]" multiple>
                        <small class="form-text text-muted">e.g Evidence of payment</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description (optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Payment</button>
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


            // // Initialize select2 for payer_id
            // $('#payer_id').select2({
            //     placeholder: 'Select payer...',
            //     ajax: {
            //         url: '{{ route("payers.search") }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function(params) {
            //             return {
            //                 q: params.term, // search term
            //                 type: $('#payer_type').val() // payer type
            //             };
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: $.map(data, function(item) {
            //                     let textValue = '';

            //                     // First check: Prioritize showing the full name
            //                     if (item.first_name || item.last_name) {
            //                         textValue = (item.first_name || '') + ' ' + (item.last_name || '');
            //                         // Trim any leading/trailing spaces
            //                         textValue = textValue.trim();
            //                     }

            //                     // If the name is empty, fall back to the email
            //                     if (textValue === '' && item.email) {
            //                         textValue = item.email;
            //                     }

            //                     return {
            //                         id: item.id,
            //                         text: textValue
            //                     };
            //                 })
            //             };
            //         },
            //         cache: true
            //     }
            // });

            // // Reload payer_id select2 when payer_type changes
            // $('#payer_type').on('change', function() {
            //     $('#payer_id').val(null).trigger('change');
            // });

            // // Transactionable select2
            // $('#transactionable_id').select2({
            //     placeholder: 'Select transactionable...',
            //     ajax: {
            //         url: '{{ route("transactionables.search") }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function(params) {
            //             return {
            //                 q: params.term, 
            //                 type: $('#transactionable_type').val()
            //             };
            //         },
            //         processResults: function(data) {
            //             return {
            //                 results: $.map(data, function(item) {
            //                     let textValue = '';

            //                     if (item.reference_no) {
            //                         textValue = 'Lease Ref: ' + item.reference_no;
            //                     } else if (item.name) {
            //                         textValue = item.name;
            //                     } else if (item.address) {
            //                         textValue = item.address;
            //                     } else if (item.title) {
            //                         textValue = item.title;
            //                     } else {
            //                         textValue = 'ID: ' + item.id;
            //                     }

            //                     return { id: item.id, text: textValue };
            //                 })
            //             };
            //         },
            //         cache: true
            //     }
            // });

            // // Reset dropdown when type changes
            // $('#transactionable_type').on('change', function() {
            //     $('#transactionable_id').val(null).trigger('change');
            // });

        });
    });
</script>

