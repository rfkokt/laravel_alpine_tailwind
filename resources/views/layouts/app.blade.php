<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

<body class="antialiased bg-gray-50 text-gray-900">
  <div class="min-h-screen md:grid md:grid-cols-[16rem_1fr]">
    {{-- Sidebar --}}
    <!-- <x-ui.sidebar /> -->
    <x-ui.flowbite-sidebar/>
    {{-- Konten --}}
    <div class="flex flex-col min-h-screen mt-12">
      {{-- Topbar mobile --}}
      <div class="md:hidden sticky top-0 z-40 bg-white border-b">
        <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
          <div class="font-semibold">{{ config('app.name') }}</div>
          <button
            class="rounded-lg border px-3 py-1.5 text-sm"
            @click="window.dispatchEvent(new CustomEvent('sidebar-open'))"
          >Menu</button>
        </div>
      </div>

      {{-- Header halaman --}}
      @hasSection('header')
        <header class="mx-auto max-w-7xl px-6 pt-6">
          @yield('header')
        </header>
      @endif

      {{-- Konten utama --}}
      <main class="flex-1 max-w-7xl px-6 py-6">
        @yield('content')
      </main>

      {{-- Footer selalu di bawah --}}
      <footer class="mt-auto mx-auto max-w-7xl px-6 py-10 text-sm text-gray-500">
        <div>&copy; {{ date('Y') }} {{ config('app.name') }}</div>
      </footer>
    </div>
  </div>
</body>

</html>
