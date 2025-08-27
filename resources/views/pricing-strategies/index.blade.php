@extends('layouts.app')

@section('title', 'Pricing Strategies')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Pricing Strategies</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('pricing-strategies.create') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">add</i> Add Strategy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Strategy</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Effective Period</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($strategies as $strategy)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $strategy->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $strategy->description }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-info">
                                            {{ ucfirst(str_replace('_', ' ', $strategy->strategy_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $strategy->is_active ? 'success' : 'secondary' }}">
                                            {{ $strategy->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            @if($strategy->effective_from)
                                                From: {{ $strategy->effective_from->format('M d, Y') }}
                                            @else
                                                From: Always
                                            @endif
                                            @if($strategy->effective_until)
                                                <br>To: {{ $strategy->effective_until->format('M d, Y') }}
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        <a href="{{ route('pricing-strategies.show', $strategy) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                        </a>
                                        <a href="{{ route('pricing-strategies.edit', $strategy) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                        </a>
                                        <form action="{{ route('pricing-strategies.toggle-active', $strategy) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-secondary mb-0">
                                                <i class="material-symbols-rounded text-sm me-2">{{ $strategy->is_active ? 'pause' : 'play_arrow' }}</i>
                                                {{ $strategy->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No pricing strategies found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
