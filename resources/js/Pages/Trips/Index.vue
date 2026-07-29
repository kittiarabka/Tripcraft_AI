<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    trips: { type: Array, required: true },
});

const statusLabels = {
    generating: 'В пути',
    completed: 'Готово',
    failed: 'Ошибка',
};
</script>

<template>
    <Head title="Мои поездки" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-display text-lg uppercase tracking-wide text-ink">Мои поездки</h2>
                <Link
                    :href="route('trips.create')"
                    class="rounded-sm border-2 border-ink bg-rust px-4 py-2 text-sm font-bold uppercase tracking-wide text-paper hover:bg-ink"
                >
                    + Новая поездка
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div
                    v-if="trips.length === 0"
                    class="rounded-sm border-2 border-ink bg-white p-10 text-center"
                >
                    <div class="font-mono text-xs uppercase tracking-[0.3em] text-rust">Empty Manifest</div>
                    <p class="mt-3 text-ink/60">Пока нет ни одной поездки — самое время создать первую.</p>
                    <Link
                        :href="route('trips.create')"
                        class="mt-5 inline-block rounded-sm border-2 border-ink bg-rust px-4 py-2 text-sm font-bold uppercase tracking-wide text-paper hover:bg-ink"
                    >
                        + Новая поездка
                    </Link>
                </div>
                <ul v-else class="space-y-4">
                    <li v-for="trip in trips" :key="trip.id" class="ticket-notch relative" style="--notch-bg: #f3ecda">
                        <Link
                            :href="route('trips.show', trip.id)"
                            class="flex items-center overflow-hidden rounded-sm border-2 border-ink bg-white transition hover:-translate-y-0.5 hover:shadow-[4px_4px_0_0_#123B3A]"
                        >
                            <div class="flex w-20 shrink-0 flex-col items-center justify-center gap-1 bg-navy py-4 font-mono text-paper">
                                <span class="text-lg">✈</span>
                                <span class="text-[10px] uppercase tracking-widest">TCA</span>
                            </div>

                            <div class="flex flex-1 items-center gap-4 border-l-2 border-dashed border-ink/30 px-5 py-4">
                                <div class="min-w-0 flex-1">
                                    <div class="font-display text-base uppercase text-ink">{{ trip.destination }}</div>
                                    <div class="font-mono text-xs uppercase tracking-wide text-ink/50">
                                        {{ trip.days }} дней · бюджет {{ Number(trip.budget).toLocaleString() }}
                                    </div>
                                </div>
                                <span
                                    class="shrink-0 rounded-sm border-2 px-3 py-1 font-mono text-xs font-bold uppercase tracking-widest"
                                    :class="{
                                        'border-mustard text-mustard': trip.status === 'generating',
                                        'border-navy text-navy': trip.status === 'completed',
                                        'border-rust text-rust': trip.status === 'failed',
                                    }"
                                >
                                    {{ statusLabels[trip.status] }}
                                </span>
                            </div>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
