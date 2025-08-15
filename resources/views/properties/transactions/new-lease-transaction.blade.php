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
                    <h1 class="m-0">Add Payment for Lease #{{ $lease->id }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('leases') }}">Leases</a></li>
                        <li class="breadcrumb-item active">Make Payment</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Add Payment for Lease #{{ $lease->id }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('create.lease.transaction', $lease->id) }}" enctype="multipart/form-data" class="card card-body">
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
                <input type="hidden" name="transactionable_type" value="App\Models\Lease">
                <input type="hidden" name="transactionable_id" value="{{ $lease->id }}">

                {{-- Hidden: payer --}}
                <input type="hidden" name="payer_type" value="{{ $payerType }}">
                <input type="hidden" name="payer_id" value="{{ $payerId }}">

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
                        <small class="form-text text-muted">(Due amount ₦{{ number_format($lease->rent_amount, 0, '.', ',') }} for rent related payment)</small>
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
        //
    });
</script>
