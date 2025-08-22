<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Owner;
use App\Models\Agent;
use App\Models\Lease;
use App\Models\Property;
use App\Models\MaintenanceRequest;


class MorphSearchService
{
    public function search(string $type, ?string $term = null)
    {
        $term = $term ?? '';
        $results = collect();

        switch ($type) {
            // --- Payers ---
            case Tenant::class:
            case Owner::class:
            case Agent::class:
                $results = (new $type)::where(function ($q) use ($term) {
                        $q->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                    })
                    ->limit(10)
                    ->get()
                    ->map(function ($item) {
                        $name = trim(($item->first_name ?? '') . ' ' . ($item->last_name ?? ''));
                        $text = $name !== '' ? $name : $item->email;
                        return [
                            'id' => $item->id,
                            'text' => $text,
                        ];
                    });
                break;

            // --- Transactionables ---
            case 'App\Models\Lease':
                $results = Lease::whereHas('tenant', function ($q) use ($term) {
                        $q->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%");
                    })
                    ->orWhere('status', 'like', "%{$term}%")
                    ->limit(10)
                    ->get()
                    ->map(fn($lease) => [
                        'id' => $lease->id,
                        'text' => 'Lease #: ' . $lease->id . ' (Tenant: ' . $lease->tenant->full_name . ')',
                    ]);
                break;

            case 'App\Models\Property':
                $results = Property::where('name', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%")
                    ->limit(10)
                    ->get()
                    ->map(fn($property) => [
                        'id' => $property->id,
                        'text' => $property->name ?? $property->address,
                    ]);
                break;

            case 'App\Models\MaintenanceRequest':
                $results = MaintenanceRequest::where('title', 'like', "%{$term}%")
                    ->orWhere('status', 'like', "%{$term}%")
                    ->limit(10)
                    ->get()
                    ->map(fn($mr) => [
                        'id' => $mr->id,
                        'text' => $mr->title,
                    ]);
                break;
        }

        return $results;

    }

}