<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateExperiencesForm from './Partials/UpdateExperiencesForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateSkillsForm from './Partials/UpdateSkillsForm.vue';
import { Head, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    skills: {
        type: Array,
        default: () => [],
    },
    experiences: {
        type: Array,
        default: () => [],
    },
});

const isCandidat = usePage().props.auth.user.role === 'candidat';
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    v-if="isCandidat"
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateSkillsForm :skills="skills" class="max-w-xl" />
                </div>

                <div
                    v-if="isCandidat"
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateExperiencesForm :experiences="experiences" class="max-w-xl" />
                </div>

                <div
                    v-if="isCandidat"
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <section class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">CV</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Téléchargez un CV généré automatiquement à partir de votre profil.
                            </p>
                        </header>

                        <a
                            :href="route('profile.cv.download')"
                            class="mt-4 inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
                        >
                            Télécharger mon CV en PDF
                        </a>
                    </section>
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
