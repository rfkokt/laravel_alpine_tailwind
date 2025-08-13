import Alpine from "alpinejs";
import "flowbite";
import faceDetector from "./components/faceDetector";

import "./bootstrap";
window.Alpine = Alpine;
Alpine.data("faceDetector", faceDetector);
Alpine.start();
