@extends('layouts.app')

@section('title', 'Home')

@section('header')
  <h1 class="text-2xl font-semibold">Welcome</h1>
@endsection

@section('content')
  <div class="mb-6">

    <h1>Custom Button</h1>
    {{-- Default primary md --}}
    <x-ui.button>Primary</x-ui.button>

    {{-- Ukuran kecil --}}
    <x-ui.button size="sm" variant="success">Small Success</x-ui.button>

    {{-- Ukuran besar --}}
    <x-ui.button size="lg" variant="danger">Large Danger</x-ui.button>

    {{-- Outline + besar --}}
    <x-ui.button size="lg" style="outline" variant="warning">Warning Outline</x-ui.button>

    {{-- Ghost + kecil --}}
    <x-ui.button size="sm" style="ghost" variant="secondary">Ghost Secondary</x-ui.button>

    {{-- Link sebagai button --}}
    <x-ui.button as="a" href="/about" size="md" style="outline" variant="primary">About</x-ui.button>

    <br>
    <br>

    <h1>Flowbite Button</h1>
    <button type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Blue</button>
    <button type="button" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Green</button>
    <button type="button" class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Cyan</button>
    <button type="button" class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Teal</button>
    <button type="button" class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Lime</button>
    <button type="button" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Red</button>
    <button type="button" class="text-white bg-gradient-to-r from-pink-400 via-pink-500 to-pink-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-pink-300 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Pink</button>
    <button type="button" class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Purple</button>

  </div>

  <x-ai.face-detector
    model-path="/models"
    :with-landmarks="true"
    :with-expressions="true"
    input-size="320"
    score-threshold="0.5"
  />
@endsection
