<?php

namespace App\Services;

use App\Models\PropertyTransaction;
use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class TransactionService
{
    /** Allowed enums */
    public const TYPES = ['credit', 'debit'];
    public const PURPOSES = [
        'full_payment',
        'partial_payment',
        'deposit',
        'maintenance_expense',
        'refund',
    ];

    /**
     * Create a PropertyTransaction and (optionally) attach uploaded documents.
     *
     * $data should include:
     * - transactionable_type (FQCN or morph alias), transactionable_id
     * - payer_type (FQCN or morph alias), payer_id
     * - type: credit|debit
     * - purpose: one of self::PURPOSES
     * - amount, transaction_date, payment_method, reference?, status?, description?
     *
     * @param  array        $data
     * @param  UploadedFile[] $files
     * @return PropertyTransaction
     * @throws ValidationException
     */
    public function create(array $data, array $files = []): PropertyTransaction
    {
        $data = $this->prepareAndValidate($data);

        return DB::transaction(function () use ($data, $files) {
            /** @var PropertyTransaction $transaction */
            $transaction = new PropertyTransaction();
            $transaction->fill(Arr::only($data, [
                'transactionable_type', 'transactionable_id',
                'payer_type', 'payer_id',
                'type', 'purpose', 'amount', 'transaction_date',
                'payment_method', 'reference_number', 'status', 'description',
            ]));
            $transaction->save();

            if (!empty($files)) {
                $this->attachDocuments($transaction, $files);
            }

            return $transaction->load(['transactionable', 'payer', 'documents']);
        });
    }
    /**
     * Update a PropertyTransaction and (optionally) attach uploaded documents.
     */
    public function update(PropertyTransaction $transaction, array $data, array $files = []): PropertyTransaction
    {
        $data = $this->prepareAndValidate($data);

        return DB::transaction(function () use ($transaction, $data, $files) {
            $transaction->fill(Arr::only($data, [
                'transactionable_type', 'transactionable_id',
                'payer_type', 'payer_id',
                'type', 'purpose', 'amount', 'transaction_date',
                'payment_method', 'reference_number', 'status', 'description',
            ]));
            $transaction->save();

            if (!empty($files)) {
                // optional: clear old documents first
                // $transaction->documents()->delete();
                $this->attachDocuments($transaction, $files);
            }

            return $transaction->load(['transactionable', 'payer', 'documents']);
        });
    }
    /**
     * Attach uploaded files to a transaction as Document records.
     *
     * @param  PropertyTransaction $transaction
     * @param  UploadedFile[]      $files
     * @return void
     */
    public function attachDocuments(PropertyTransaction $transaction, array $files): void
    {
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            // Store under public disk: storage/app/public/transactions/{id}/...
            $filename = $file->getClientOriginalName();
            $path = $file->move(public_path('documents/transactions/'), $filename);
            // $path = $file->move(public_path('documents/transactions/{$transaction->id}'), $filename);
            // $path = $file->store("documents/transactions/{$transaction->id}", 'public');

            // Adjust these fields to match your Document schema
            $transaction->documents()->create([
                'title'                 => $file->getClientOriginalName(),
                'file_path'             => $path,
                'file_type'             => $file->getClientMimeType(),
                'description'           => 'Transaction document ' . $file->getClientOriginalName(),
                'uploaded_by_user_id'   => auth()->user()->id,
            ]);
        }
    }

    /**
     * Normalize / validate incoming data before persisting.
     *
     * @param  array $data
     * @return array
     * @throws ValidationException
     */
    protected function prepareAndValidate(array $data): array
    {
        // Required enums
        if (!in_array($data['type'] ?? '', self::TYPES, true)) {
            throw ValidationException::withMessages(['type' => 'Type must be: '.implode(',', self::TYPES)]);
        }

        if (!in_array($data['purpose'] ?? '', self::PURPOSES, true)) {
            throw ValidationException::withMessages(['purpose' => 'Purpose must be: '.implode(',', self::PURPOSES)]);
        }

        // Amount
        if (!isset($data['amount']) || !is_numeric($data['amount']) || (float)$data['amount'] <= 0) {
            throw ValidationException::withMessages(['amount' => 'Amount must be a positive number.']);
        }
        $data['amount'] = (float) $data['amount'];

        // Date
        if (empty($data['transaction_date'])) {
            throw ValidationException::withMessages(['transaction_date' => 'Transaction date is required.']);
        }
        $data['transaction_date'] = Carbon::parse($data['transaction_date']);

        // Validate polymorphic targets exist
        $this->assertMorphTarget(
            $data['transactionable_type'] ?? null,
            $data['transactionable_id'] ?? null,
            'transactionable'
        );

        $this->assertMorphTarget(
            $data['payer_type'] ?? null,
            $data['payer_id'] ?? null,
            'payer'
        );

        return $data;
    }

    /**
     * Ensure morph type resolves to a valid Model class and record exists.
     *
     * Accepts FQCN (e.g. App\Models\Lease) or morph alias (e.g. 'lease') if you use Relation::morphMap().
     *
     * @param  string|null $type
     * @param  int|null    $id
     * @param  string      $label
     * @return void
     * @throws ValidationException
     */
    protected function assertMorphTarget(?string $type, ?int $id, string $label): void
    {
        if (!$type || !$id) {
            throw ValidationException::withMessages([
                $label => ucfirst($label).'_type and '.$label.'_id are required.',
            ]);
        }

        // If you use morphMap aliases, resolve them here
        $resolvedClass = Relation::getMorphedModel($type) ?? $type;

        if (!class_exists($resolvedClass) || !is_subclass_of($resolvedClass, Model::class)) {
            throw ValidationException::withMessages([
                $label => "Invalid {$label}_type: {$type}",
            ]);
        }

        if (!$resolvedClass::query()->whereKey($id)->exists()) {
            throw ValidationException::withMessages([
                $label => "The selected {$label} (ID: {$id}) does not exist.",
            ]);
        }
    }
}
