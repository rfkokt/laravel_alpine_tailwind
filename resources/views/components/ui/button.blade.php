@props([
  'variant' => 'primary', // primary | secondary | success | warning | danger
  'style' => 'solid',     // solid | outline | ghost
  'size' => 'md',         // sm | md | lg
  'as' => 'button',       // button | a
  'href' => null,
  'type' => 'button',
])

@php
// Base class tanpa ukuran & warna
$base = 'inline-flex items-center justify-center rounded-lg font-medium transition focus:outline-none focus:ring';

// Size mapping
$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];

// Warna solid
$solidColors = [
    'primary'   => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-300',
    'secondary' => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-300',
    'success'   => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-300',
    'warning'   => 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-300',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300',
];

// Warna outline
$outlineColors = [
    'primary'   => 'border border-indigo-600 text-indigo-600 hover:bg-indigo-50 focus:ring-indigo-200',
    'secondary' => 'border border-gray-600 text-gray-600 hover:bg-gray-50 focus:ring-gray-200',
    'success'   => 'border border-emerald-600 text-emerald-600 hover:bg-emerald-50 focus:ring-emerald-200',
    'warning'   => 'border border-amber-500 text-amber-500 hover:bg-amber-50 focus:ring-amber-200',
    'danger'    => 'border border-red-600 text-red-600 hover:bg-red-50 focus:ring-red-200',
];

// Warna ghost
$ghostColors = [
    'primary'   => 'bg-transparent text-indigo-600 hover:bg-indigo-50 focus:ring-indigo-200',
    'secondary' => 'bg-transparent text-gray-600 hover:bg-gray-50 focus:ring-gray-200',
    'success'   => 'bg-transparent text-emerald-600 hover:bg-emerald-50 focus:ring-emerald-200',
    'warning'   => 'bg-transparent text-amber-500 hover:bg-amber-50 focus:ring-amber-200',
    'danger'    => 'bg-transparent text-red-600 hover:bg-red-50 focus:ring-red-200',
];

// Pilih style warna
$styles = match ($style) {
    'outline' => $outlineColors[$variant] ?? $outlineColors['primary'],
    'ghost'   => $ghostColors[$variant] ?? $ghostColors['primary'],
    default   => $solidColors[$variant] ?? $solidColors['primary'],
};

// Gabungkan semua kelas
$classes = "$base $sizeClass $styles";
@endphp

@if($as === 'a' && $href)
  <a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
    {{ $slot }}
  </a>
@else
  <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
  </button>
@endif
