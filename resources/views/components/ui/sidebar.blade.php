@props([
  // Ubah item menu di sini kalau perlu
  'items' => [
    ['label' => 'Home',  'href' => route('home', absolute: false),  'match' => 'home'],
    ['label' => 'About', 'href' => url('/about'),                   'match' => 'about'],
    ['label' => 'Sign In', 'href' => '#',                           'match' => null],
  ],
])

{{-- Off-canvas (mobile) + Fixed (desktop) --}}
<div
  x-data="{ open: false }"
  @sidebar-open.window="open = true"
  @sidebar-close.window="open = false"
  class="relative z-50"
>
  {{-- Backdrop mobile --}}
  <div
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 bg-black/40 md:hidden"
    @click="open = false"
  ></div>

  {{-- Panel --}}
  <aside
  class="
    /* mobile: off-canvas */
    fixed inset-y-0 left-0 w-64 bg-white border-r shadow-sm
    transform transition-transform duration-200 ease-out -translate-x-full
    /* desktop: elemen normal di kolom grid */
    md:static md:translate-x-0 md:shadow-none md:h-full md:overflow-y-auto
  "
  :class="open ? 'translate-x-0' : ''"
>
 <div class="flex h-full flex-col">
    <div class="h-16 flex items-center justify-between px-4 border-b">
      <a href="/" class="font-semibold">MyApp</a>
      <button
        class="md:hidden rounded-lg border px-2 py-1 text-sm"
        @click="open = false"
      >
        Close
      </button>
    </div>

    <nav class="px-3 py-4 space-y-1">
      @foreach ($items as $item)
        @php
          $active = $item['match']
            ? request()->routeIs($item['match'])
            : request()->fullUrlIs($item['href']);
        @endphp

        <a
          href="{{ $item['href'] }}"
          class="block rounded-lg px-3 py-2 text-sm font-medium
                 {{ $active
                    ? 'bg-indigo-600 text-white'
                    : 'text-gray-700 hover:bg-gray-100'
                 }}"
          @click="open = false"
        >
          {{ $item['label'] }}
        </a>
      @endforeach
    </nav>

    <div class="mt-auto border-t p-3 text-xs text-gray-500">
      © {{ date('Y') }} {{ config('app.name') }}
    </div>
    </div>
  </aside>
</div>
