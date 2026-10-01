<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const user = computed(() => usePage().props.auth.user);

const shortcuts = computed(() => ({
    candidat: [
        { route: 'jobs.index', icon: 'search', title: 'Explorer les offres', text: 'Recherchez par poste, ville ou type de contrat.' },
        { route: 'applications.index', icon: 'inbox', title: 'Suivre mes candidatures', text: 'Consultez l’étape où en est chacune de vos candidatures.' },
        { route: 'profile.edit', icon: 'user', title: 'Compléter mon profil', text: 'Vos compétences servent à calculer votre compatibilité avec les offres.' },
    ],
    recruteur: [
        { route: 'job-offers.create', icon: 'plus', title: 'Publier une offre', text: 'Votre offre sera vérifiée par notre équipe avant sa mise en ligne.' },
        { route: 'job-offers.index', icon: 'briefcase', title: 'Gérer mes offres', text: 'Modifiez ou retirez vos offres en cours.' },
        { route: 'recruiter.applications.index', icon: 'users', title: 'Candidatures reçues', text: 'Faites avancer les candidats dans votre processus.' },
    ],
    admin: [
        { route: 'admin.moderation.index', icon: 'shield', title: 'Modérer les offres', text: 'Validez ou refusez les offres en attente.' },
        { route: 'admin.users.index', icon: 'users', title: 'Gérer les utilisateurs', text: 'Suspendez des comptes ou vérifiez des entreprises.' },
        { route: 'admin.reports.index', icon: 'flag', title: 'Traiter les signalements', text: 'Examinez les offres signalées par les candidats.' },
    ],
}[user.value.role] ?? []));

const firstName = computed(() => user.value.name.split(' ')[0]);
</script>

<template>
    <Head title="Accueil" />

    <AuthenticatedLayout>
        <PageHeader
            :title="`Bonjour ${firstName} !`"
            description="Heureux de vous revoir sur CareerGO. Que souhaitez-vous faire aujourd’hui ?"
        />

        <ul class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <li v-for="(shortcut, index) in shortcuts" :key="shortcut.route">
                <Link
                    :href="route(shortcut.route)"
                    class="group flex h-full flex-col rounded-2xl border p-5 shadow-card transition-colors"
                    :class="index === 0
                        ? 'border-primary-600 bg-primary-600 text-white hover:bg-primary-700'
                        : 'border-gray-200 bg-white text-ink hover:border-primary-600'"
                >
                    <span
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl"
                        :class="index === 0 ? 'bg-white/10' : 'bg-sand text-primary-700'"
                    >
                        <Icon :name="shortcut.icon" />
                    </span>
                    <span class="mt-4 flex items-center gap-1 font-heading text-lg font-bold">
                        {{ shortcut.title }}
                        <Icon name="chevronRight" size="sm" class="transition-transform group-hover:translate-x-0.5" />
                    </span>
                    <span class="mt-1 text-sm" :class="index === 0 ? 'text-primary-50' : 'text-gray-600'">
                        {{ shortcut.text }}
                    </span>
                </Link>
            </li>
        </ul>
    </AuthenticatedLayout>
</template>
