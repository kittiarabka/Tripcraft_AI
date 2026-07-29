<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-sm border-2 border-ink/20 bg-paper font-mono text-ink shadow-none focus:border-navy focus:ring-navy"
        v-model="model"
        ref="input"
    />
</template>
