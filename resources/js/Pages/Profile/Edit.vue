<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import { roleLabels } from '@/utils/status';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateExperiencesForm from './Partials/UpdateExperiencesForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateSkillsForm from './Partials/UpdateSkillsForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

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

const user = computed(() => usePage().props.auth.user);
const isCandidat = computed(() => user.value.role === 'candidat');
</script>

<template>
    <Head title="Mon profil" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-3xl space-y-6">
            <Card padding="lg" class="flex flex-col items-center text-center sm:flex-row sm:text-left">
                <Avatar :name="user.name" size="xl" />
                <div class="mt-4 min-w-0 sm:ml-6 sm:mt-0">
                    <h1 class="truncate text-2xl font-bold">{{ user.name }}</h1>
                    <p class="truncate text-sm text-gray-600">{{ user.email }}</p>
                    <Badge v-if="roleLabels[user.role]" :tone="roleLabels[user.role].tone" class="mt-2">
                        {{ roleLabels[user.role].label }}
                    </Badge>
                </div>
            </Card>

            <template v-if="isCandidat">
                <Card padding="lg">
                    <h2 class="text-lg font-bold">Curriculum vitae</h2>
                    <div class="mt-4 flex flex-col gap-4 rounded-xl bg-sand p-4 sm:flex-row sm:items-center">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white text-red-700">
                            <Icon name="document" />
                        </span>
                        <p class="flex-1 text-sm text-ink">
                            Un CV au format PDF est généré automatiquement à partir de votre profil : compétences et expériences.
                        </p>
                    </div>
                    <Button :href="route('profile.cv.download')" native variant="outlined" icon="download" block class="mt-4">
                        Télécharger mon CV en PDF
                    </Button>
                </Card>

                <Card padding="lg">
                    <UpdateSkillsForm :skills="skills" />
                </Card>

                <Card padding="lg">
                    <UpdateExperiencesForm :experiences="experiences" />
                </Card>
            </template>

            <Card padding="lg">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
            </Card>

            <Card padding="lg">
                <UpdatePasswordForm />
            </Card>

            <Card padding="lg">
                <DeleteUserForm />
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
