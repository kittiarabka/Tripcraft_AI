<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const userName = computed(() => usePage().props.auth.user.name);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-display text-lg uppercase tracking-wide text-ink">Дашборд</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-sm border-2 border-ink bg-white p-10 text-center shadow-[6px_6px_0_0_#123B3A]">
                    <div class="font-mono text-xs uppercase tracking-[0.3em] text-rust">Welcome Aboard</div>
                    <h3 class="mt-3 font-display text-2xl uppercase text-ink">Привет, {{ userName }}!</h3>
                    <p class="mt-2 text-ink/60">
                        Опиши направление — и ИИ соберёт маршрут по дням прямо у тебя на глазах.
                    </p>

                    <div class="mt-8 flex justify-center gap-3">
                        <Link
                            :href="route('trips.create')"
                            class="rounded-sm border-2 border-ink bg-rust px-5 py-2.5 text-sm font-bold uppercase tracking-wide text-paper hover:bg-ink"
                        >
                            ✈ Спланировать поездку
                        </Link>
                        <Link
                            :href="route('trips.index')"
                            class="rounded-sm border-2 border-ink bg-paper px-5 py-2.5 text-sm font-bold uppercase tracking-wide text-ink hover:bg-ink/5"
                        >
                            Мои поездки
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
