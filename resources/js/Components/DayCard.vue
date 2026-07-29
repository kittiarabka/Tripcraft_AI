<script setup>
import { computed } from 'vue';

const props = defineProps({
    day: { type: Object, required: true },
});

const palette = ['#BB4E2E', '#123B3A', '#D9A441', '#5B7553', '#7A4B6D', '#2E6B6B', '#8C3B2E'];
const color = computed(() => palette[(props.day.day_number - 1) % palette.length]);
</script>

<template>
    <div
        class="rounded-sm border-2 border-ink bg-white p-5 transition hover:shadow-[4px_4px_0_0_#123B3A]"
        :style="{ borderLeft: `6px solid ${color}` }"
    >
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-sm border-2 border-ink font-mono text-sm font-bold text-paper"
                    :style="{ backgroundColor: color }"
                >
                    {{ day.day_number }}
                </span>
                <h3 class="font-display text-sm uppercase tracking-wide text-ink">{{ day.title }}</h3>
            </div>
            <span class="shrink-0 rounded-sm border-2 border-ink bg-paper px-3 py-1 font-mono text-sm font-bold text-ink">
                {{ Number(day.estimated_cost || 0).toLocaleString() }}
            </span>
        </div>
        <p class="mt-2 pl-11 text-sm text-ink/60">{{ day.summary }}</p>
        <ul class="mt-4 space-y-2 pl-11">
            <li v-for="(activity, i) in day.activities || []" :key="i" class="flex items-start gap-3 text-sm">
                <span class="mt-0.5 shrink-0 rounded-sm bg-ink/5 px-2 py-0.5 font-mono text-xs text-ink/60">
                    {{ activity.time }}
                </span>
                <div class="min-w-0 flex-1">
                    <div class="font-medium text-ink">{{ activity.place }}</div>
                    <div class="text-ink/50">{{ activity.description }}</div>
                </div>
                <span class="shrink-0 font-mono text-ink/40">{{ activity.cost }}</span>
            </li>
        </ul>
    </div>
</template>
