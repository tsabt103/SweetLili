@props([
    'size' => 'md',
    'showText' => true,
])

@php
    $dimensions = match($size) {
        'sm' => 'w-7 h-7',
        'lg' => 'w-12 h-12',
        default => 'w-9 h-9',
    };
    $textSize = match($size) {
        'sm' => 'text-base',
        'lg' => 'text-2xl',
        default => 'text-lg',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 font-bold tracking-tight']) }}>
    <div class="relative flex items-center justify-center {{ $dimensions }} rounded-xl bg-sage-600 text-cream-50 shadow-sm shadow-sage-900/10 transition-transform hover:scale-105">
        <!-- SVG Icon: Palette + Brush & Pencil -->
        <svg class="w-3/5 h-3/5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- ist Palette Shape -->
            <path d="M12 2C6.48 2 2 6.48 2 12C2 14.5 2.9 16.78 4.39 18.53C4.84 19.06 5.56 19.34 6.25 19.23C7.29 19.06 8.08 18.08 8.08 17.03V16.5C8.08 15.12 9.2 14 10.58 14H12C16.42 14 20 10.42 20 6C20 3.79 16.42 2 12 2Z" fill="currentColor" fill-opacity="0.9" />
            <!-- Palette Color Wells -->
            <circle cx="6.5" cy="8.5" r="1.3" fill="#faf6ef" />
            <circle cx="10" cy="5.8" r="1.3" fill="#d5664d" />
            <circle cx="14.2" cy="6.5" r="1.3" fill="#e28a74" />
            <!-- Brush / Pencil Tip in Terracotta Accent -->
            <path d="M17.5 12.5L21.5 8.5C22.16 7.84 22.16 6.78 21.5 6.12L20.88 5.5C20.22 4.84 19.16 4.84 18.5 5.5L14.5 9.5L17.5 12.5Z" fill="#d5664d" />
            <path d="M13.5 10.5L16.5 13.5L12 18L9 18L9 15L13.5 10.5Z" fill="#c04e35" />
        </svg>
        <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-terracotta-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-terracotta-500"></span>
        </span>
    </div>

    @if($showText)
        <div class="flex flex-col leading-tight">
            <span class="{{ $textSize }} font-extrabold text-sage-900 tracking-tight"><span class="text-terracotta-600">SWEETLILI</span></span>
            <span class="text-[10px] tracking-wider uppercase font-semibold text-sage-600">Cake Shop</span>
        </div>
    @endif
</div>
