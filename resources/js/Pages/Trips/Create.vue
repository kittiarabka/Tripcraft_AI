<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    destination: '',
    days: 3,
    budget: 50000,
    interests: '',
});

function submit() {
    form.post(route('trips.store'));
}
</script>

<template>
    <Head title="Новая поездка" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-display text-lg uppercase tracking-wide text-ink">Новая поездка</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-sm border-2 border-ink bg-white p-8 shadow-[6px_6px_0_0_#123B3A]">
                    <div class="mb-6 flex items-center justify-between border-b-2 border-dashed border-ink/20 pb-4">
                        <div>
                            <div class="font-display text-sm uppercase text-ink">Расскажи о поездке</div>
                            <div class="mt-1 font-mono text-xs uppercase tracking-wide text-ink/50">ИИ соберёт маршрут в реальном времени</div>
                        </div>
                        <div class="font-mono text-xs uppercase tracking-widest text-rust">Form 04-B</div>
                    </div>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-widest text-ink/60">Куда едем</label>
                            <input
                                v-model="form.destination"
                                type="text"
                                placeholder="Стамбул"
                                class="mt-1 block w-full rounded-sm border-2 border-ink/20 bg-paper font-mono text-ink focus:border-navy focus:ring-navy"
                            />
                            <p v-if="form.errors.destination" class="mt-1 text-sm text-rust">{{ form.errors.destination }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-widest text-ink/60">Дней</label>
                                <input
                                    v-model.number="form.days"
                                    type="number"
                                    min="1"
                                    max="14"
                                    class="mt-1 block w-full rounded-sm border-2 border-ink/20 bg-paper font-mono text-ink focus:border-navy focus:ring-navy"
                                />
                                <p v-if="form.errors.days" class="mt-1 text-sm text-rust">{{ form.errors.days }}</p>
                            </div>
                            <div>
                                <label class="block font-mono text-xs font-semibold uppercase tracking-widest text-ink/60">Бюджет (₽)</label>
                                <input
                                    v-model.number="form.budget"
                                    type="number"
                                    min="1"
                                    class="mt-1 block w-full rounded-sm border-2 border-ink/20 bg-paper font-mono text-ink focus:border-navy focus:ring-navy"
                                />
                                <p v-if="form.errors.budget" class="mt-1 text-sm text-rust">{{ form.errors.budget }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block font-mono text-xs font-semibold uppercase tracking-widest text-ink/60">Интересы</label>
                            <input
                                v-model="form.interests"
                                type="text"
                                placeholder="история, еда, пляжи"
                                class="mt-1 block w-full rounded-sm border-2 border-ink/20 bg-paper font-mono text-ink focus:border-navy focus:ring-navy"
                            />
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full rounded-sm border-2 border-ink bg-rust px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-paper hover:bg-ink disabled:opacity-50"
                        >
                            ✦ Сгенерировать маршрут
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
