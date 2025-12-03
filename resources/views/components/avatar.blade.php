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
    
    // Generate consistent color based on initials
    $colors = [
        'bg-blue-500',
        'bg-green-500',
        'bg-yellow-500',
        'bg-red-500',
        'bg-purple-500',
        'bg-pink-500',
        'bg-indigo-500',
        'bg-teal-500',
        'bg-orange-500',
        'bg-cyan-500',
    ];
    
    $colorIndex = ord(substr($initials, 0, 1)) % count($colors);
    $bgColor = $colors[$colorIndex];
@endphp

@if($src)
    <img src="{{ $src }}" 
         alt="Avatar" 
         {{ $attributes->merge(['class' => "rounded-full object-cover {$sizeClass} {$class}"]) }}>
@else
    <div {{ $attributes->merge(['class' => "rounded-full {$bgColor} text-white flex items-center justify-center font-semibold {$sizeClass} {$class}"]) }}>
        {{ $initials }}
    </div>
@endif
