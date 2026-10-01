<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import Tabs from '@/Components/ui/Tabs.vue';
import { formatAmount } from '@/utils/format';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    publishedOffersCount: {
        type: Number,
        default: 0,
    },
});

const audience = ref('candidat');

const audiences = [
    { value: 'candidat', label: 'Je cherche un emploi' },
    { value: 'recruteur', label: 'Je recrute' },
];

const steps = {
    candidat: [
        {
            icon: 'user',
            title: 'Créez votre profil',
            text: 'Renseignez vos compétences et vos expériences. Un CV au format PDF est généré à partir de votre profil.',
        },
        {
            icon: 'search',
            title: 'Trouvez une offre',
            text: 'Recherchez par poste, ville ou type de contrat, et consultez votre score de compatibilité pour chaque offre.',
        },
        {
            icon: 'send',
            title: 'Postulez et suivez',
            text: 'Postulez en un clic, puis suivez l’étape où en est chacune de vos candidatures.',
        },
    ],
    recruteur: [
        {
            icon: 'building',
            title: 'Créez votre compte recruteur',
            text: 'Indiquez le nom de votre entreprise lors de l’inscription.',
        },
        {
            icon: 'briefcase',
            title: 'Soumettez une offre',
            text: 'Notre équipe la vérifie avant sa publication auprès des candidats.',
        },
        {
            icon: 'users',
            title: 'Gérez les candidatures',
            text: 'Faites avancer chaque candidat dans votre processus et voyez sa compatibilité avec le poste.',
        },
    ],
};

const commitments = [
    {
        icon: 'check',
        title: 'Réponse garantie',
        text: 'Quand un recruteur s’engage à répondre à chaque candidat, son offre porte le badge « Réponse garantie ». Cet engagement est déclaré par le recruteur.',
    },
    {
        icon: 'badgeCheck',
        title: 'Entreprises vérifiées',
        text: 'Le badge « Entreprise vérifiée » n’est attribué que par un administrateur de CareerGO.',
    },
    {
        icon: 'sparkle',
        title: 'Score de compatibilité',
        text: 'Pour chaque offre, nous calculons la part des compétences demandées qui figurent sur votre profil.',
    },
];

const year = new Date().getFullYear();
</script>

<template>
    <Head title="Accueil" />

    <div class="min-h-screen bg-cream text-ink">
        <a
            href="#contenu"
            class="sr-only z-50 rounded-lg bg-ink px-4 py-2 text-white focus:not-sr-only focus:fixed focus:left-4 focus:top-4"
        >
            Aller au contenu
        </a>

        <header class="border-b border-gray-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <Link href="/" aria-label="CareerGO, accueil">
                    <ApplicationLogo class="h-7 w-auto sm:h-8" />
                </Link>
                <nav aria-label="Compte" class="flex items-center gap-2">
                    <Link
                        :href="route('login')"
                        class="inline-flex min-h-11 items-center rounded-lg px-3 text-sm font-semibold text-primary-700 hover:bg-primary-50"
                    >
                        Se connecter
                    </Link>
                    <Button :href="route('register')" size="sm" class="hidden sm:inline-flex">Créer un compte</Button>
                </nav>
            </div>
        </header>

        <main id="contenu">
            <section class="relative overflow-hidden">
                <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-12 sm:px-6 sm:py-16 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-20">
                    <div>
                        <p class="inline-flex items-center gap-2 rounded-full bg-sand px-3 py-1 text-sm font-semibold text-primary-700">
                            <Icon name="pin" size="sm" />
                            Emploi et stage au Gabon
                        </p>
                        <h1 class="mt-5 text-4xl font-bold leading-tight sm:text-5xl">
                            Votre prochaine opportunité commence ici.
                        </h1>
                        <p class="mt-5 max-w-xl text-lg text-gray-700">
                            CareerGO rapproche les candidats et les recruteurs : des offres vérifiées avant publication,
                            et un suivi clair de chaque candidature.
                        </p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <Button :href="route('register')" size="lg" icon="search">
                                Je cherche un emploi ou un stage
                            </Button>
                            <Button :href="route('register', { role: 'recruteur' })" size="lg" variant="outlined" icon="briefcase">
                                Je recrute
                            </Button>
                        </div>
                        <p class="mt-6 text-sm text-gray-700" aria-live="polite">
                            <template v-if="publishedOffersCount > 0">
                                <span class="font-heading text-xl font-bold text-primary-700">{{ formatAmount(publishedOffersCount) }}</span>
                                offre{{ publishedOffersCount > 1 ? 's' : '' }} publiée{{ publishedOffersCount > 1 ? 's' : '' }} en ce moment.
                            </template>
                            <template v-else>
                                Aucune offre n’est publiée pour le moment.
                            </template>
                        </p>
                    </div>

                    <svg
                        viewBox="0 0 400 360"
                        class="mx-auto w-full max-w-md"
                        role="img"
                        aria-label="Illustration : une fiche d’offre d’emploi entourée de formes aux couleurs de CareerGO"
                    >
                        <circle cx="300" cy="90" r="80" fill="#C89B3C" opacity="0.25" />
                        <circle cx="90" cy="280" r="70" fill="#14607A" opacity="0.15" />
                        <path d="M40 120 Q120 40 220 70" fill="none" stroke="#0F4C3A" stroke-width="3" stroke-dasharray="6 8" opacity="0.4" />
                        <rect x="80" y="70" width="240" height="220" rx="24" fill="#FFFFFF" stroke="#DEE0DE" stroke-width="2" />
                        <rect x="108" y="100" width="52" height="52" rx="14" fill="#0F4C3A" />
                        <path d="M122 122h24v18h-24z M128 122v-5h12v5" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linejoin="round" />
                        <rect x="174" y="106" width="118" height="12" rx="6" fill="#1B2B25" />
                        <rect x="174" y="128" width="80" height="10" rx="5" fill="#9C9F9C" />
                        <rect x="108" y="172" width="70" height="24" rx="12" fill="#F1E9D8" />
                        <rect x="186" y="172" width="56" height="24" rx="12" fill="#E3ECEF" />
                        <rect x="108" y="212" width="184" height="10" rx="5" fill="#DEE0DE" />
                        <rect x="108" y="212" width="132" height="10" rx="5" fill="#0F4C3A" />
                        <rect x="108" y="244" width="184" height="30" rx="10" fill="#0F4C3A" />
                        <rect x="160" y="255" width="80" height="8" rx="4" fill="#FFFFFF" />
                        <circle cx="318" cy="250" r="30" fill="#C89B3C" />
                        <path d="M305 250l9 9 17-18" fill="none" stroke="#1B2B25" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M60 60c0-13 10-23 23-23s23 10 23 23c0 18-23 38-23 38S60 78 60 60z" fill="#14607A" />
                        <circle cx="83" cy="60" r="8" fill="#FFFFFF" />
                    </svg>
                </div>
            </section>

            <section class="border-y border-gray-200 bg-white" aria-labelledby="comment-ca-marche">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
                    <h2 id="comment-ca-marche" class="text-3xl font-bold">Comment ça marche</h2>
                    <p class="mt-2 text-gray-700">Trois étapes, que vous cherchiez un poste ou que vous recrutiez.</p>

                    <Tabs v-model="audience" :options="audiences" label="Choisir votre profil" class="mt-6" />

                    <ol class="mt-8 grid gap-6 md:grid-cols-3">
                        <li v-for="(step, index) in steps[audience]" :key="step.title" class="rounded-2xl bg-cream p-6">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 font-heading font-bold text-white">
                                    {{ index + 1 }}
                                </span>
                                <Icon :name="step.icon" class="text-primary-700" />
                            </div>
                            <h3 class="mt-4 text-lg font-bold">{{ step.title }}</h3>
                            <p class="mt-2 text-sm text-gray-700">{{ step.text }}</p>
                        </li>
                    </ol>
                </div>
            </section>

            <section aria-labelledby="engagements">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
                    <h2 id="engagements" class="text-3xl font-bold">Nos engagements</h2>
                    <p class="mt-2 text-gray-700">Ce que la plateforme fait concrètement pour vous.</p>

                    <ul class="mt-8 grid gap-6 md:grid-cols-3">
                        <li v-for="commitment in commitments" :key="commitment.title" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-card">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-sand text-primary-700">
                                <Icon :name="commitment.icon" />
                            </span>
                            <h3 class="mt-4 text-lg font-bold">{{ commitment.title }}</h3>
                            <p class="mt-2 text-sm text-gray-700">{{ commitment.text }}</p>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="px-4 pb-14 sm:px-6 lg:px-8" aria-labelledby="appel-final">
                <div class="relative mx-auto max-w-6xl overflow-hidden rounded-3xl bg-primary-600 px-6 py-12 text-white sm:px-12">
                    <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full border-[32px] border-secondary-600/40" aria-hidden="true"></div>
                    <h2 id="appel-final" class="relative max-w-xl text-3xl font-bold">Prêt à commencer ?</h2>
                    <p class="relative mt-3 max-w-xl text-primary-50">
                        L’inscription est gratuite et ne prend que quelques minutes.
                    </p>
                    <div class="relative mt-8 flex flex-col gap-3 sm:flex-row">
                        <Button :href="route('register')" size="lg" variant="accent">Créer mon compte candidat</Button>
                        <Button :href="route('register', { role: 'recruteur' })" size="lg" variant="inverted">Créer mon compte recruteur</Button>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-8 text-sm text-gray-700 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <div>
                    <ApplicationLogo class="h-6 w-auto" />
                    <p class="mt-2">Plateforme d’emploi et de stage pour le Gabon.</p>
                </div>
                <nav aria-label="Liens du pied de page" class="flex flex-wrap gap-x-6 gap-y-2">
                    <Link :href="route('login')" class="font-semibold hover:text-primary-700">Se connecter</Link>
                    <Link :href="route('register')" class="font-semibold hover:text-primary-700">Créer un compte</Link>
                </nav>
                <p>© {{ year }} CareerGO</p>
            </div>
        </footer>
    </div>
</template>
