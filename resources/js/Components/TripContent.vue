<script setup>
import BudgetDonut from '@/Components/BudgetDonut.vue';
import DayCard from '@/Components/DayCard.vue';
import RouteMap from '@/Components/RouteMap.vue';

defineProps({
    trip: { type: Object, required: true },
    days: { type: Array, required: true },
    spent: { type: Number, required: true },
    status: { type: String, required: true },
    errorMessage: { type: String, default: '' },
});
</script>

<template>
    <div class="space-y-6">
        <div v-if="status === 'generating'" class="flex items-center gap-2 rounded-sm border-2 border-mustard bg-mustard/10 px-4 py-3 font-mono text-sm uppercase tracking-wide text-ink">
            <svg class="h-4 w-4 animate-spin text-mustard" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
            ИИ собирает маршрут по дням...
        </div>
        <div v-if="errorMessage" class="rounded-sm border-2 border-rust bg-rust/10 px-4 py-3 text-sm text-rust">
            {{ errorMessage }}
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="overflow-hidden rounded-sm border-2 border-ink lg:col-span-3">
                <RouteMap :days="days" />
            </div>
            <div class="flex items-center justify-center rounded-sm border-2 border-ink bg-white lg:col-span-2">
                <BudgetDonut :spent="spent" :budget="trip.budget" />
            </div>
        </div>

        <TransitionGroup tag="div" name="day-card" class="space-y-4">
            <DayCard v-for="day in days" :key="day.day_number" :day="day" />
        </TransitionGroup>
    </div>
</template>

<style scoped>
.day-card-enter-active {
    transition: all 0.5s ease;
}
.day-card-enter-from {
    opacity: 0;
    transform: translateY(16px);
}
</style>
