<script setup>
import Icon from '@/Components/ui/Icon.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const flash = computed(() => page.props.flash ?? {});
</script>

<template>
    <div class="space-y-3 empty:hidden">
        <div
            v-if="flash.success"
            role="status"
            class="flex items-start gap-3 rounded-xl border border-primary-200 bg-primary-50 px-4 py-3 text-sm text-primary-800"
        >
            <Icon name="check" class="mt-0.5" />
            <p>{{ flash.success }}</p>
        </div>
        <div
            v-if="flash.error"
            role="alert"
            class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
        >
            <Icon name="alert" class="mt-0.5" />
            <p>{{ flash.error }}</p>
        </div>
    </div>
</template>
