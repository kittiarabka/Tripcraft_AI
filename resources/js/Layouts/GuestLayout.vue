<script setup>
import { Link } from '@inertiajs/vue3';

const ticketNumber = Math.floor(Math.random() * 900 + 100);
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-paper pt-10 sm:justify-center sm:pt-0">
        <Link href="/" class="flex items-center gap-2 font-display text-xl uppercase tracking-wide text-navy">
            <span>✈</span>
            <span>TripCraft AI</span>
        </Link>
        <div class="mt-1 font-mono text-xs uppercase tracking-[0.3em] text-ink/40">Boarding Pass · Trip Planner</div>

        <div class="relative mt-8 w-full sm:max-w-md">
            <div class="relative overflow-hidden rounded-sm border-2 border-ink bg-white px-6 pb-6 pt-5 shadow-[6px_6px_0_0_#123B3A]">
                <div class="mb-4 flex items-center justify-between font-mono text-xs uppercase tracking-widest text-ink/40">
                    <span>№ TCA-{{ ticketNumber }}</span>
                    <span>Class · Traveler</span>
                </div>
                <slot />
            </div>
            <div class="ticket-perforation"></div>
        </div>
    </div>
</template>
