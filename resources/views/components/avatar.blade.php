@props([
    'src' => null,
    'initials' => '?',
    'size' => 'md',
    'class' => '',
])

@php
    $sizes = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-16 h-16 text-xl',
        '2xl' => 'w-20 h-20 text-2xl',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    // Use consistent yellow color for all avatars without photo
    $bgColor = 'bg-yellow-500';
@endphp

@if($src)
    <img src="{{ $src }}" 
         alt="Avatar" 
         {{ $attributes->merge(['class' => "rounded-full object-cover ring-2 ring-yellow-500 {$sizeClass} {$class}"]) }}>
@else
    <div {{ $attributes->merge(['class' => "rounded-full {$bgColor} text-white flex items-center justify-center font-semibold ring-2 ring-yellow-600 {$sizeClass} {$class}"]) }}>
        {{ $initials }}
    </div>
@endif
