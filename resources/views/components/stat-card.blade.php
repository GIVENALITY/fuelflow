@props([
    'title',
    'value',
    'icon',
    'color' => 'primary',
    'trend' => null,
    'trendDirection' => 'up'
])

<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-{{ $color }} shadow-{{ $color }} text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-symbols-rounded opacity-10">{{ $icon }}</i>
            </div>
            <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">{{ $title }}</p>
                <h4 class="mb-0">{{ $value }}</h4>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
            @if($trend)
                <p class="mb-0">
                    <span class="text-{{ $trendDirection === 'up' ? 'success' : 'danger' }} text-sm font-weight-bolder">
                        {{ $trendDirection === 'up' ? '+' : '-' }}{{ $trend }}%
                    </span> 
                    than last month
                </p>
            @else
                <p class="mb-0">Updated just now</p>
            @endif
        </div>
    </div>
</div>
