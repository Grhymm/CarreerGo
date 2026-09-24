<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    applications: Array,
});

const columns = [
    { status: 'new', label: 'Nouvelles' },
    { status: 'reviewing', label: 'À l\'étude' },
    { status: 'interview', label: 'Entretien' },
];

const statusOptions = [
    { value: 'new', label: 'Nouvelle' },
    { value: 'reviewing', label: 'À l\'étude' },
    { value: 'interview', label: 'Entretien' },
    { value: 'rejected', label: 'Refusée' },
    { value: 'accepted', label: 'Acceptée' },
];

const applicationsByStatus = computed(() => (status) =>
    props.applications.filter((application) => application.status === status)
);

const changeStatus = (application, event) => {
    router.patch(
        route('recruiter.applications.update', application.id),
        { status: event.target.value },
        { preserveScroll: true }
    );
};
</script>

<template>
    <Head title="Candidatures reçues" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Candidatures reçues
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div v-for="column in columns" :key="column.status">
                        <h3 class="mb-3 text-sm font-semibold uppercase text-gray-500">
                            {{ column.label }}
                            <span class="text-gray-400">({{ applicationsByStatus(column.status).length }})</span>
                        </h3>

                        <div class="space-y-3">
                            <div
                                v-for="application in applicationsByStatus(column.status)"
                                :key="application.id"
                                class="bg-white p-4 shadow-sm sm:rounded-lg"
                            >
                                <p class="font-medium text-gray-900">{{ application.candidate_name }}</p>
                                <p class="mt-1 text-sm text-gray-600">{{ application.job_offer_title }}</p>

                                <div class="mt-3 flex items-center gap-2">
                                    <div class="h-2 w-full rounded-full bg-gray-200">
                                        <div
                                            class="h-2 rounded-full bg-primary-600"
                                            :style="{ width: application.compatibility_score + '%' }"
                                        ></div>
                                    </div>
                                    <span class="whitespace-nowrap text-xs font-medium text-gray-600">
                                        {{ application.compatibility_score }}%
                                    </span>
                                </div>

                                <select
                                    class="mt-3 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    :value="application.status"
                                    @change="changeStatus(application, $event)"
                                >
                                    <option
                                        v-for="option in statusOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>

                            <p v-if="applicationsByStatus(column.status).length === 0" class="text-sm text-gray-500">
                                Aucune candidature.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
