<nav x-data="{open:false}" class="border-b bg-white">
  <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
    <a href="/" class="font-semibold">MyApp</a>
    <div class="hidden md:flex items-center gap-6">
      <x-ui.button as="a" href="/about">About</x-ui.button>
      <x-ui.button as="a" href="#">Sign In</x-ui.button>
    </div>
    <button @click="open=!open" class="md:hidden rounded-lg border px-3 py-1.5 text-sm">
      Menu
    </button>
  </div>
  <div x-show="open" x-cloak class="md:hidden border-t">
    <div class="mx-auto max-w-7xl px-4 py-3 space-y-3">
      <x-ui.button as="a" href="/about" class="w-full justify-center">About</x-ui.button>
      <x-ui.button as="a" href="#" class="w-full justify-center">Sign In</x-ui.button>
    </div>
  </div>
</nav>
