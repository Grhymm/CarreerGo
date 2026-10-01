<script setup>
const model = defineModel({ type: Boolean, default: false });

defineProps({
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <div class="flex items-start justify-between gap-4">
        <div>
            <span :id="`${id}-label`" class="text-sm font-semibold text-ink">{{ label }}</span>
            <p v-if="description" :id="`${id}-description`" class="mt-0.5 text-sm text-gray-600">{{ description }}</p>
        </div>
        <button
            :id="id"
            type="button"
            role="switch"
            :aria-checked="model"
            :aria-labelledby="`${id}-label`"
            :aria-describedby="description ? `${id}-description` : undefined"
            class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors"
            :class="model ? 'bg-primary-600' : 'bg-gray-400'"
            @click="model = !model"
        >
            <span
                class="inline-block h-5 w-5 rounded-full bg-white shadow transition-transform"
                :class="model ? 'translate-x-6' : 'translate-x-1'"
            ></span>
        </button>
    </div>
</template>
