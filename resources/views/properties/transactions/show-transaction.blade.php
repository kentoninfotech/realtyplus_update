@extends('layouts.template')

@section('content')
<div class="container">
    <div class="card mt-3">
        <div class="card-header">
            <h1>Transaction #{{ $transaction->id }}</h1>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">{{ $transaction->type }}</dd>

                <dt class="col-sm-3">Purpose</dt>
                <dd class="col-sm-9">{{ $transaction->purpose }}</dd>

                <dt class="col-sm-3">Amount</dt>
                <dd class="col-sm-9">{{ number_format($transaction->amount, 2) }}</dd>

                <dt class="col-sm-3">Date</dt>
                <dd class="col-sm-9">{{ optional($transaction->transaction_date)->toDateString() }}</dd>

                <dt class="col-sm-3">Payment Method</dt>
                <dd class="col-sm-9">{{ $transaction->payment_method }}</dd>

                <dt class="col-sm-3">Reference</dt>
                <dd class="col-sm-9">{{ $transaction->reference }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ $transaction->status }}</dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9">{{ $transaction->description }}</dd>

                <dt class="col-sm-3">Transactionable</dt>
                <dd class="col-sm-9">
                    {{ class_basename($transaction->transactionable_type) }} #{{ $transaction->transactionable_id }}
                </dd>

                <dt class="col-sm-3">Payer</dt>
                <dd class="col-sm-9">
                    {{ class_basename($transaction->payer_type) }} #{{ $transaction->payer_id }}
                </dd>
            </dl>

            <h4>Documents</h4>
            <ul>
                @forelse ($transaction->documents as $doc)
                    <li>
                        <a href="{{ asset('public/'. $doc->file_path) }}" target="_blank">
                            {{ $doc->title ?? basename($doc->file_type) }}
                        </a>
                    </li>
                @empty
                    <li>No documents attached.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
