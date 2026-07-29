<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TripContent from '@/Components/TripContent.vue';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    trip: { type: Object, required: true },
    days: { type: Array, default: () => [] },
    shared: { type: Boolean, default: false },
});

const days = ref([...props.days]);
const status = ref(props.trip.status);
const errorMessage = ref('');
const copied = ref(false);

const spent = computed(() => days.value.reduce((sum, day) => sum + Number(day.estimated_cost || 0), 0));
const shareUrl = computed(() => `${window.location.origin}/share/${props.trip.share_token}`);

async function startStream() {
    try {
        const response = await fetch(`/trips/${props.trip.id}/stream`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            buffer += decoder.decode(value, { stream: true });
            let newlineIndex;
            while ((newlineIndex = buffer.indexOf('\n')) !== -1) {
                const line = buffer.slice(0, newlineIndex).trim();
                buffer = buffer.slice(newlineIndex + 1);
                if (!line) continue;

                const parsed = JSON.parse(line);
                if (parsed.error) {
                    errorMessage.value = parsed.message || 'Что-то пошло не так';
                    status.value = 'failed';
                    continue;
                }
                days.value.push(parsed);
            }
        }

        if (status.value === 'generating') {
            status.value = 'completed';
        }
    } catch (e) {
        errorMessage.value = 'Не удалось получить маршрут';
        status.value = 'failed';
    }
}

async function copyShareLink() {
    await navigator.clipboard.writeText(shareUrl.value);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
}

onMounted(() => {
    if (!props.shared && status.value === 'generating' && days.value.length === 0) {
        startStream();
    }
});
</script>

<template>
    <Head :title="trip.destination" />

    <AuthenticatedLayout v-if="!shared">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-display text-lg uppercase tracking-wide text-ink">{{ trip.destination }}</h2>
                <button
                    @click="copyShareLink"
                    class="rounded-sm border-2 border-ink bg-paper px-3 py-1.5 font-mono text-xs font-bold uppercase tracking-wide text-ink hover:bg-ink/5"
                >
                    {{ copied ? 'Скопировано!' : 'Скопировать ссылку' }}
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
                <TripContent
                    :trip="trip"
                    :days="days"
                    :spent="spent"
                    :status="status"
                    :error-message="errorMessage"
                />
            </div>
        </div>
    </AuthenticatedLayout>

    <div v-else class="min-h-screen bg-paper py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-2 font-mono text-xs uppercase tracking-[0.3em] text-rust">Boarding Pass · Shared Itinerary</div>
            <h1 class="mb-6 font-display text-2xl uppercase text-ink">{{ trip.destination }} — маршрут от TripCraft AI</h1>
            <TripContent
                :trip="trip"
                :days="days"
                :spent="spent"
                :status="status"
                :error-message="errorMessage"
            />
        </div>
    </div>
</template>
