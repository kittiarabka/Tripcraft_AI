<script setup>
import { computed } from 'vue';

const props = defineProps({
    spent: { type: Number, required: true },
    budget: { type: Number, required: true },
});

const radius = 70;
const circumference = 2 * Math.PI * radius;
const ratio = computed(() => (props.budget > 0 ? Math.min(props.spent / props.budget, 1) : 0));
const dashOffset = computed(() => circumference * (1 - ratio.value));
const overBudget = computed(() => props.spent > props.budget);
</script>

<template>
    <div class="flex flex-col items-center gap-2 py-6">
        <div class="font-mono text-xs uppercase tracking-[0.3em] text-ink/40">Budget Gauge</div>
        <svg viewBox="0 0 180 180" class="h-44 w-44">
            <circle cx="90" cy="90" :r="radius" fill="none" stroke="#2A242015" stroke-width="14" />
            <circle
                cx="90"
                cy="90"
                :r="radius"
                fill="none"
                :stroke="overBudget ? '#BB4E2E' : '#123B3A'"
                stroke-width="14"
                :stroke-dasharray="circumference"
                :stroke-dashoffset="dashOffset"
                transform="rotate(-90 90 90)"
                style="transition: stroke-dashoffset 0.6s ease, stroke 0.3s ease"
            />
            <text x="90" y="86" text-anchor="middle" class="fill-ink" style="font-family: 'JetBrains Mono', monospace; font-size: 26px; font-weight: 700">
                {{ Math.round(ratio * 100) }}%
            </text>
            <text x="90" y="108" text-anchor="middle" class="fill-ink/50" style="font-family: 'JetBrains Mono', monospace; font-size: 12px">
                {{ spent.toLocaleString() }} / {{ budget.toLocaleString() }}
            </text>
        </svg>
        <span v-if="overBudget" class="font-mono text-sm font-bold uppercase tracking-wide text-rust">Бюджет превышен</span>
    </div>
</template>
