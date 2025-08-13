import * as faceapi from "@vladmandic/face-api";

export default function faceDetector({
    modelPath = "/models",
    withLandmarks = true,
    withExpressions = true,
    inputSize = 320,
    scoreThreshold = 0.5,
    detectFps = 8, // throttle deteksi ~8 fps
} = {}) {
    return {
        // state
        ready: false,
        running: false,
        initializing: false,
        error: null,

        withLandmarks,
        withExpressions,
        inputSize,
        scoreThreshold,
        detectFps,

        // refs
        $video: null,
        $canvas: null,
        ctx: null,
        stream: null,
        intervalId: null, // untuk throttle deteksi

        async init() {
            try {
                if (this.initializing || this.ready) return;
                this.initializing = true;

                this.$video = this.$refs.video;
                this.$canvas = this.$refs.canvas;
                this.ctx = this.$canvas.getContext("2d");

                // Load models
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(modelPath),
                    this.withLandmarks
                        ? faceapi.nets.faceLandmark68Net.loadFromUri(modelPath)
                        : Promise.resolve(),
                    this.withExpressions
                        ? faceapi.nets.faceExpressionNet.loadFromUri(modelPath)
                        : Promise.resolve(),
                ]);

                // Start camera (pastikan tidak double)
                if (this.stream)
                    for (const t of this.stream.getTracks()) t.stop();
                this.$video.autoplay = true;
                this.$video.muted = true;
                this.$video.playsInline = true;

                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "user" },
                    audio: false,
                });

                this.$video.srcObject = this.stream;

                // Tunggu metadata agar ukuran diketahui
                await new Promise((resolve) => {
                    this.$video.addEventListener("loadedmetadata", resolve, {
                        once: true,
                    });
                });

                // Set ukuran canvas overlay mengikuti video
                this.$canvas.width = this.$video.videoWidth;
                this.$canvas.height = this.$video.videoHeight;

                try {
                    await this.$video.play();
                } catch (_) {}

                this.ready = true;
                this.initializing = false;

                this.start(); // mulai deteksi
            } catch (e) {
                this.initializing = false;
                this.error = e?.message || String(e);
                console.error(e);
            }
        },

        start() {
            if (this.running || !this.ready) return;
            this.running = true;

            // Jalankan deteksi dengan interval (mis. setiap ~125ms)
            const period = Math.max(50, Math.round(1000 / this.detectFps));
            this.intervalId = setInterval(() => this.detectAndDraw(), period);
        },

        stop() {
            this.running = false;
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
            // Bersihkan overlay
            if (this.ctx && this.$canvas) {
                this.ctx.clearRect(
                    0,
                    0,
                    this.$canvas.width,
                    this.$canvas.height
                );
            }
        },

        destroy() {
            this.stop();
            if (this.stream) {
                for (const t of this.stream.getTracks()) t.stop();
                this.stream = null;
            }
            if (this.$video) {
                this.$video.pause();
                this.$video.srcObject = null;
            }
        },

        async detectAndDraw() {
            if (!this.running) return;

            const options = new faceapi.TinyFaceDetectorOptions({
                inputSize: this.inputSize,
                scoreThreshold: this.scoreThreshold,
            });

            let detections;
            try {
                if (this.withLandmarks || this.withExpressions) {
                    detections = await faceapi
                        .detectAllFaces(this.$video, options)
                        .withFaceLandmarks(this.withLandmarks)
                        .withFaceExpressions(this.withExpressions);
                } else {
                    detections = await faceapi.detectAllFaces(
                        this.$video,
                        options
                    );
                }
            } catch (e) {
                console.error("detect error", e);
                return;
            }

            // Bersihkan overlay dulu
            this.ctx.clearRect(0, 0, this.$canvas.width, this.$canvas.height);

            if (!detections?.length) return;

            const dims = {
                width: this.$video.videoWidth,
                height: this.$video.videoHeight,
            };
            const resized = faceapi.resizeResults(detections, dims);

            // Gambar box/landmarks/expressions di canvas overlay (tanpa draw video)
            resized.forEach((det) => {
                const { x, y, width, height } = det.detection.box;

                // Box
                this.ctx.lineWidth = 2;
                this.ctx.strokeStyle = "rgba(99,102,241,1)";
                this.ctx.strokeRect(x, y, width, height);

                // Confidence
                const conf = (det.detection.score * 100).toFixed(0) + "%";
                this.ctx.fillStyle = "rgba(99,102,241,1)";
                this.ctx.font = "12px ui-sans-serif, system-ui, -apple-system";
                this.ctx.fillText(conf, x + 4, y + 14);

                // Landmarks
                if (this.withLandmarks && det.landmarks) {
                    this.ctx.fillStyle = "rgba(16,185,129,1)";
                    det.landmarks.positions.forEach((pt) => {
                        this.ctx.beginPath();
                        this.ctx.arc(pt.x, pt.y, 1.8, 0, Math.PI * 2);
                        this.ctx.fill();
                    });
                }

                // Expressions
                if (this.withExpressions && det.expressions) {
                    const top = Object.entries(det.expressions)
                        .sort((a, b) => b[1] - a[1])
                        .slice(0, 2)
                        .map(([k, v]) => `${k}:${(v * 100).toFixed(0)}%`)
                        .join("  ");
                    this.ctx.fillStyle = "rgba(17,24,39,1)";
                    this.ctx.fillText(top, x + 4, y + height - 6);
                }
            });
        },

        toggleLandmarks() {
            this.withLandmarks = !this.withLandmarks;
        },
        toggleExpressions() {
            this.withExpressions = !this.withExpressions;
        },
        snapshot() {
            const url = this.$canvas.toDataURL("image/png");
            const a = document.createElement("a");
            a.href = url;
            a.download = `snapshot-${Date.now()}.png`;
            a.click();
        },
    };
}
