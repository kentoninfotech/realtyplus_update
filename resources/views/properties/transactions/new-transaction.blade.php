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
                               <option value="{{ $tr }}">{{ class_basename($tr) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Transactionable ID</label>
                        <input type="number" name="transactionable_id" class="form-control">
                    </div>
                </div>
                {{-- Payer Type --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Payer Type</label>
                        <select id="payer_type" name="payer_type" class="form-control">
                            <option value="">-- Select Payer Type --</option>
                            @foreach($payerType as $py)
                                <option value="{{ $py }}">{{ class_basename($py) }}</option>
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
                                <option value="{{ $p }}">{{ str_replace('_', ' ', $p) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="amount">Amount</label>
                        <input type="number" value="{{ old('amount') }}" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
                        <small class="form-text text-muted">Amount (₦)</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="transaction_date">Transaction Date</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="payment_method">Payment Method</label>
                        <input type="text" value="{{ old('payment_method') }}" class="form-control" id="payment_method" name="payment_method" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="reference_number">Reference (optional)</label>
                        <input type="text" value="{{ old('reference_number') }}" class="form-control" id="reference_number" name="reference_number" maxlength="191">
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status </label>
                    <select name="status" id="status" class="form-control">
                        @foreach($status as $st)
                           <option value="{{ $st }}">{{ Str::headline($st) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description (optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="documents">Attach Documents (optional)</label>
                    <input type="file" class="form-control-file" id="documents" name="documents[]" multiple>
                </div>

                <button type="submit" class="btn btn-primary">Save Payment</button>
            </form>

        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            // Initialize empty select2 for payer_id
            $('#payer_id').select2({
                placeholder: 'Select payer...',
                ajax: {
                    url: '{{ route("payers.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            type: $('#payer_type').val() // payer type
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return { id: item.id, text: item.first_name + ' ' + item.last_name }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Reload payer_id select2 when payer_type changes
            $('#payer_type').on('change', function() {
                $('#payer_id').val(null).trigger('change');
            });
        });
    });
</script>

