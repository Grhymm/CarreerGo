<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Icon from '@/Components/ui/Icon.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    },
});

const points = [
    { icon: 'search', text: 'Des offres d’emploi et de stage vérifiées par notre équipe avant publication.' },
    { icon: 'sparkle', text: 'Un score de compatibilité calculé à partir de vos compétences.' },
    { icon: 'inbox', text: 'Le suivi de chacune de vos candidatures, étape par étape.' },
];
</script>

<template>
    <div class="flex min-h-screen bg-cream">
        <aside class="relative hidden w-[44%] max-w-xl flex-col justify-between overflow-hidden bg-primary-600 p-12 text-white lg:flex">
            <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-primary-500/40" aria-hidden="true"></div>
            <div class="absolute -bottom-32 -left-16 h-80 w-80 rounded-full border-[40px] border-secondary-600/30" aria-hidden="true"></div>

            <p class="relative font-heading text-3xl font-bold">CareerGO</p>

            <div class="relative">
                <h2 class="text-3xl font-bold leading-tight">
                    Votre prochaine opportunité au Gabon commence ici.
                </h2>
                <ul class="mt-8 space-y-5">
                    <li v-for="point in points" :key="point.icon" class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/10">
                            <Icon :name="point.icon" />
                        </span>
                        <span class="pt-1.5 text-primary-50">{{ point.text }}</span>
                    </li>
                </ul>
            </div>

            <p class="relative text-sm text-primary-100">Plateforme d’emploi et de stage pour le Gabon.</p>
        </aside>

        <main class="flex flex-1 flex-col items-center px-4 py-8 sm:justify-center sm:px-6">
            <Link href="/" class="lg:hidden">
                <ApplicationLogo class="h-10 w-auto" />
            </Link>

            <div class="mt-8 w-full max-w-md lg:mt-0">
                <div v-if="title" class="mb-6">
                    <h1 class="text-2xl font-bold text-ink sm:text-3xl">{{ title }}</h1>
                    <p v-if="description" class="mt-2 text-sm text-gray-600">{{ description }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-card sm:p-8">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>
