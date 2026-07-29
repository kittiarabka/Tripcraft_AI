<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block font-mono text-xs font-semibold uppercase tracking-widest text-ink/60">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
