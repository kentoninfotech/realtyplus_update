@extends('superadmin.layout')
@section('title','Landing CMS')
@section('content')
<div class="d-flex justify-content-between mb-3 align-items-center">
    <ul class="nav nav-pills">
        @foreach($sections as $s)
            <li class="nav-item">
                <a class="nav-link {{ $section==$s ? 'active' : '' }}" href="{{ route('superadmin.landing.index', ['section' => $s]) }}">{{ str_replace('_',' ',ucfirst($s)) }}</a>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('superadmin.landing.create', ['section' => $section]) }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New {{ str_replace('_',' ',$section) }}</a>
</div>
<div class="card p-3">
    <table class="table align-middle">
        <thead><tr><th>#</th><th>Title</th><th>Subtitle / Body</th><th>Active</th><th></th></tr></thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $item->sort_order }}</td>
                <td><strong>{{ $item->title }}</strong>@if($item->key)<br><small class="text-muted">key: {{ $item->key }}</small>@endif</td>
                <td><small>{{ Str::limit($item->subtitle ?? $item->body, 100) }}</small></td>
                <td>{!! $item->is_active ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-muted"></i>' !!}</td>
                <td class="text-end">
                    <a href="{{ route('superadmin.landing.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pen"></i></a>
                    <form method="POST" action="{{ route('superadmin.landing.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Delete?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-muted text-center">No items in this section yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
