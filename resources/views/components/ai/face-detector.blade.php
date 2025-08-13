@props([
  'modelPath' => '/models',
  'withLandmarks' => true,
  'withExpressions' => true,
  'inputSize' => 320,
  'scoreThreshold' => 0.5,
])

<div
  x-data="faceDetector({
    modelPath: '{{ $modelPath }}',
    withLandmarks: {{ $withLandmarks ? 'true' : 'false' }},
    withExpressions: {{ $withExpressions ? 'true' : 'false' }},
    inputSize: {{ (int) $inputSize }},
    scoreThreshold: {{ (float) $scoreThreshold }},
  })"
  x-init="init()"
  x-on:beforeunload.window="destroy()"
  class="w-full max-w-3xl rounded-2xl border bg-white shadow p-4"
>
  <div class="flex items-center justify-between gap-3 border-b pb-3">
    <h3 class="text-base font-semibold">Face Detector</h3>
    <div class="flex items-center gap-2">
      <button @click="toggleLandmarks()" class="rounded-lg border px-3 py-1.5 text-sm hover:bg-gray-50">
        Landmarks: <span x-text="withLandmarks ? 'On' : 'Off'"></span>
      </button>
      <button @click="toggleExpressions()" class="rounded-lg border px-3 py-1.5 text-sm hover:bg-gray-50">
        Expressions: <span x-text="withExpressions ? 'On' : 'Off'"></span>
      </button>
      <button @click="snapshot()" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-sm text-white hover:bg-indigo-700">
        Snapshot
      </button>
    </div>
  </div>

  <div class="relative mt-4 aspect-video w-full overflow-hidden rounded-xl bg-black">
    <video
      x-ref="video"
      playsinline
      muted
      autoplay
      class="absolute inset-0 h-full w-full object-cover"
    ></video>

    {{-- Canvas overlay di atas video, transparan --}}
    <canvas
      x-ref="canvas"
      class="absolute inset-0 h-full w-full pointer-events-none"
    ></canvas>

  {{-- Overlay loading/error pakai x-show (bukan x-if agar elemen tidak remount) --}}
    <div x-show="!ready && !error" class="absolute inset-0 grid place-items-center text-white/80">
      <div class="animate-pulse text-sm">Loading models & camera…</div>
    </div>
    <div x-show="error" class="absolute inset-0 grid place-items-center bg-red-50 text-red-700">
      <div class="text-center text-sm">
        <div class="font-semibold">Error</div>
        <div x-text="error"></div>
      </div>
    </div>
  </div>  


  <div class="mt-3 grid grid-cols-2 gap-3 text-sm text-gray-600">
    <div>
      <label class="block text-xs">Input Size</label>
      <select x-model.number="inputSize" class="mt-1 w-full rounded-lg border px-3 py-1.5">
        <option value="160">160 (lebih cepat)</option>
        <option value="224">224</option>
        <option value="320">320 (default)</option>
        <option value="416">416</option>
        <option value="512">512 (lebih akurat)</option>
      </select>
    </div>
    <div>
      <label class="block text-xs">Score Threshold</label>
      <input type="number" step="0.05" min="0.1" max="0.9" x-model.number="scoreThreshold"
             class="mt-1 w-full rounded-lg border px-3 py-1.5" />
    </div>
  </div>
</div>
