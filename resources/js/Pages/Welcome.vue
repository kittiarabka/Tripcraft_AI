<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
});

const features = [
    {
        code: '01',
        title: 'ИИ пишет маршрут вживую',
        text: 'Опиши направление, бюджет и интересы — маршрут по дням появляется на экране в реальном времени, пока ИИ его генерирует.',
    },
    {
        code: '02',
        title: 'Карта с точками маршрута',
        text: 'Каждая активность отмечается на карте и соединяется линией — прямо по мере генерации.',
    },
    {
        code: '03',
        title: 'Контроль бюджета',
        text: 'Диаграмма показывает, сколько от бюджета уже занято, и обновляется по ходу генерации.',
    },
];
</script>

<template>
    <Head title="Добро пожаловать" />

    <div class="min-h-screen bg-paper">
        <header class="bg-navy">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
                <div class="flex items-center gap-2 font-display text-lg uppercase tracking-wide text-paper">
                    <span>✈</span>
                    <span>TripCraft AI</span>
                </div>

                <nav class="flex items-center gap-4 font-mono text-sm uppercase tracking-wide">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-sm border-2 border-paper bg-rust px-4 py-1.5 font-bold text-paper hover:bg-paper hover:text-navy"
                    >
                        В кабинет
                    </Link>
                    <template v-else>
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="text-paper/70 hover:text-paper"
                        >
                            Войти
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-sm border-2 border-paper bg-rust px-4 py-1.5 font-bold text-paper hover:bg-paper hover:text-navy"
                        >
                            Начать
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-6 pb-24 pt-16 text-center">
            <div class="font-mono text-xs uppercase tracking-[0.3em] text-rust">Boarding Pass · AI Itinerary</div>
            <h1 class="mt-4 font-display text-3xl uppercase leading-tight text-ink sm:text-5xl">
                Маршрут, который<br />
                <span class="text-rust">пишет ИИ</span>
                у тебя на глазах
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-ink/70">
                Укажи направление, число дней и бюджет — и смотри, как маршрут по дням, карта
                и бюджет-диаграмма собираются в реальном времени.
            </p>

            <div class="mt-10 flex justify-center gap-4">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="rounded-sm border-2 border-ink bg-rust px-6 py-3 text-base font-bold uppercase tracking-wide text-paper shadow-[4px_4px_0_0_#2A2420] hover:bg-ink"
                >
                    Спланировать поездку
                </Link>
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="rounded-sm border-2 border-ink bg-paper px-6 py-3 text-base font-bold uppercase tracking-wide text-ink hover:bg-ink/5"
                >
                    Войти
                </Link>
            </div>

            <div class="mt-20 grid gap-6 text-left sm:grid-cols-3">
                <div
                    v-for="feature in features"
                    :key="feature.code"
                    class="rounded-sm border-2 border-ink bg-white p-6"
                >
                    <div class="font-mono text-3xl font-bold text-rust">{{ feature.code }}</div>
                    <h2 class="mt-3 font-display text-sm uppercase leading-snug text-ink">{{ feature.title }}</h2>
                    <p class="mt-2 text-sm text-ink/60">{{ feature.text }}</p>
                </div>
            </div>
        </main>

        <footer class="border-t-2 border-dashed border-ink/20 py-8 text-center font-mono text-xs uppercase tracking-widest text-ink/40">
            TripCraft AI — pet-проект для портфолио
        </footer>
    </div>
</template>
