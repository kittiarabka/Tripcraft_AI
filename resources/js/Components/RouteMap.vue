<script setup>
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps({
    days: { type: Array, required: true },
});

const mapEl = ref(null);
let map;
let markersLayer;
const renderedDayNumbers = new Set();

const palette = ['#BB4E2E', '#123B3A', '#D9A441', '#5B7553', '#7A4B6D', '#2E6B6B', '#8C3B2E'];

const numberIcon = (n, color) => L.divIcon({
    html: `<div style="background:${color};color:#F3ECDA;width:26px;height:26px;border:2px solid #2A2420;border-radius:2px;display:flex;align-items:center;justify-content:center;font:700 13px/1 'JetBrains Mono',monospace;box-shadow:2px 2px 0 0 #2A2420">${n}</div>`,
    className: '',
    iconSize: [26, 26],
    iconAnchor: [13, 13],
});

function renderDay(day) {
    if (renderedDayNumbers.has(day.day_number)) return;
    renderedDayNumbers.add(day.day_number);

    const color = palette[(day.day_number - 1) % palette.length];
    const points = [];

    (day.activities || []).forEach((activity) => {
        if (typeof activity.lat !== 'number' || typeof activity.lng !== 'number') return;
        L.marker([activity.lat, activity.lng], { icon: numberIcon(day.day_number, color) })
            .bindPopup(`<strong>День ${day.day_number}: ${day.title}</strong><br>${activity.time ?? ''} — ${activity.place ?? ''}`)
            .addTo(markersLayer);
        points.push([activity.lat, activity.lng]);
    });

    if (points.length > 1) {
        L.polyline(points, { color, weight: 3, opacity: 0.7 }).addTo(markersLayer);
    }

    if (points.length) {
        map.fitBounds(markersLayer.getBounds(), { padding: [40, 40] });
    }
}

onMounted(() => {
    map = L.map(mapEl.value, { zoomControl: true }).setView([20, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);
    markersLayer = L.featureGroup().addTo(map);

    requestAnimationFrame(() => {
        map.invalidateSize();
        props.days.forEach(renderDay);
    });
});

watch(() => props.days.length, () => {
    props.days.forEach(renderDay);
});

onBeforeUnmount(() => map?.remove());
</script>

<template>
    <div ref="mapEl" class="h-96 w-full" style="z-index: 0"></div>
</template>
