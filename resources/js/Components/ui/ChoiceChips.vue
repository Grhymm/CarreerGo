<script setup>
const model = defineModel({ type: [String, Number], default: '' });

defineEmits(['select']);

defineProps({
    options: {
        type: Array,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    legend: {
        type: String,
        required: true,
    },
    hideLegend: {
        type: Boolean,
        default: false,
    },
    layout: {
        type: String,
        default: 'scroll',
    },
});
</script>

<template>
    <fieldset>
        <legend :class="hideLegend ? 'sr-only' : 'ui-label-caps mb-2'">{{ legend }}</legend>

        <div :class="layout === 'grid' ? 'grid grid-cols-2 gap-2' : '-mx-1 flex gap-2 overflow-x-auto px-1 pb-1'">
            <label v-for="option in options" :key="option.value" class="relative">
                <input
                    v-model="model"
                    type="radio"
                    class="peer sr-only"
                    :name="name"
                    :value="option.value"
                    @change="$emit('select', option.value)"
                />
                <span
                    class="flex min-h-10 cursor-pointer items-center justify-center whitespace-nowrap border px-4 text-sm font-semibold transition-colors peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-tertiary-600"
                    :class="[
                        layout === 'grid' ? 'rounded-lg' : 'rounded-full',
                        model === option.value
                            ? 'border-primary-600 bg-primary-600 text-white'
                            : 'border-gray-300 bg-white text-ink hover:border-primary-600',
                    ]"
                >
                    {{ option.label }}
                </span>
            </label>
        </div>
    </fieldset>
</template>
