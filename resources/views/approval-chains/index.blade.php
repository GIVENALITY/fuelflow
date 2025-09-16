@extends('layouts.app')

@section('title', 'Approval Chains')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Approval Chains</h6>
                            <p class="text-sm text-secondary mb-0">Manage custom approval workflows for fuel requests</p>
                        </div>
                        <a href="{{ route('approval-chains.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Create Chain
                        </a>
                    </div>
                </div>
            </div>

            <!-- Approval Chains Grid -->
            <div class="row">
                @forelse($chains as $chain)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $chain->name }}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('approval-chains.show', $chain) }}">
                                                    <i class="fas fa-eye me-2"></i>View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('approval-chains.edit', $chain) }}">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('approval-chains.duplicate', $chain) }}">
                                                    <i class="fas fa-copy me-2"></i>Duplicate
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('approval-chains.destroy', $chain) }}" 
                                                      onsubmit="return confirm('Are you sure you want to delete this approval chain?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($chain->description)
                                    <p class="text-sm text-secondary mb-3">{{ $chain->description }}</p>
                                @endif

                                <!-- Status Badge -->
                                <div class="mb-3">
                                    @if($chain->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>

                                <!-- Steps Preview -->
                                <div class="mb-3">
                                    <h6 class="text-sm font-weight-bold mb-2">Approval Steps ({{ $chain->steps->count() }})</h6>
                                    <div class="steps-preview">
                                        @foreach($chain->steps->take(3) as $index => $step)
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="step-number me-2">
                                                    <span class="badge bg-primary rounded-circle">{{ $index + 1 }}</span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="text-sm font-weight-bold">{{ $step->name }}</div>
                                                    <div class="text-xs text-secondary">
                                                        @switch($step->approver_type)
                                                            @case('user')
                                                                {{ $step->approver->name ?? 'User not found' }}
                                                                @break
                                                            @case('role')
                                                                {{ ucfirst(str_replace('_', ' ', $step->approver_role)) }}
                                                                @break
                                                            @case('station_manager')
                                                                Station Manager
                                                                @break
                                                            @case('admin')
                                                                Administrator
                                                                @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($chain->steps->count() > 3)
                                            <div class="text-xs text-muted">
                                                +{{ $chain->steps->count() - 3 }} more steps
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Rules -->
                                @if($chain->rules && count($chain->rules) > 0)
                                    <div class="mb-3">
                                        <h6 class="text-sm font-weight-bold mb-2">Rules</h6>
                                        <div class="rules-preview">
                                            @foreach($chain->rules as $rule)
                                                <span class="badge bg-light text-dark me-1 mb-1">
                                                    {{ ucfirst($rule['type']) }}: {{ $rule['value'] ?? 'N/A' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Stats -->
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-sm font-weight-bold">{{ $chain->fuelRequests->count() }}</div>
                                        <div class="text-xs text-secondary">Requests</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-sm font-weight-bold">
                                            {{ $chain->fuelRequests->where('status', 'approved')->count() }}
                                        </div>
                                        <div class="text-xs text-secondary">Approved</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Created by {{ $chain->createdBy->name }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $chain->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-sitemap text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">No approval chains found</h5>
                                <p class="text-muted">Create your first approval chain to customize the approval workflow for fuel requests.</p>
                                <a href="{{ route('approval-chains.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Create First Chain
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($chains->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $chains->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.steps-preview {
    max-height: 200px;
    overflow-y: auto;
}

.step-number {
    min-width: 30px;
}

.rules-preview {
    max-height: 100px;
    overflow-y: auto;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection
