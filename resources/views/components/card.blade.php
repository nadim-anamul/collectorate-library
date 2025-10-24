@props([
    'title' => null,
    'subtitle' => null,
    'header' => null,
    'footer' => null,
    'padding' => 'default',
    'shadow' => 'default',
    'rounded' => 'default',
])

@php
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-4',
        'default' => 'p-6',
        'lg' => 'p-8',
    ];
    
    $shadowClasses = [
        'none' => '',
        'sm' => 'shadow-sm',
        'default' => 'shadow-md',
        'lg' => 'shadow-lg',
        'xl' => 'shadow-xl',
    ];
    
    $roundedClasses = [
        'none' => '',
        'sm' => 'rounded-sm',
        'default' => 'rounded-lg',
        'lg' => 'rounded-xl',
        'xl' => 'rounded-2xl',
    ];
    
    $baseClasses = 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700';
    $classes = $baseClasses . ' ' . $paddingClasses[$padding] . ' ' . $shadowClasses[$shadow] . ' ' . $roundedClasses[$rounded];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($header || $title || $subtitle)
        <div class="card-header mb-4">
            @if($header)
                {{ $header }}
            @else
                @if($title)
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
                @endif
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer mt-4">
            {{ $footer }}
        </div>
    @endif
</div>
