<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Button from '@/Components/ui/Button.vue';
import FlashMessages from '@/Components/ui/FlashMessages.vue';
import Icon from '@/Components/ui/Icon.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const spaces = {
    candidat: 'Espace candidat',
    recruteur: 'Espace recruteur',
    admin: 'Administration',
};

const navigation = computed(() => {
    const home = { label: 'Accueil', route: 'dashboard', active: 'dashboard', icon: 'home' };
    const jobs = { label: 'Offres', route: 'jobs.index', active: 'jobs.*', icon: 'search' };

    return {
        candidat: [
            home,
            jobs,
            { label: 'Candidatures', route: 'applications.index', active: 'applications.*', icon: 'inbox' },
        ],
        recruteur: [
            home,
            { label: 'Mes offres', route: 'job-offers.index', active: 'job-offers.*', icon: 'briefcase' },
            { label: 'Candidatures', route: 'recruiter.applications.index', active: 'recruiter.applications.*', icon: 'users' },
            jobs,
        ],
        admin: [
            { label: 'Modération', route: 'admin.moderation.index', active: 'admin.moderation.*', icon: 'shield' },
            { label: 'Utilisateurs', route: 'admin.users.index', active: 'admin.users.*', icon: 'users' },
            { label: 'Signalements', route: 'admin.reports.index', active: 'admin.reports.*', icon: 'flag' },
            jobs,
        ],
    }[user.value.role] ?? [home, jobs];
});

const profileItem = { label: 'Profil', route: 'profile.edit', active: 'profile.*', icon: 'user' };

const isActive = (item) => route().current(item.active);
</script>

<template>
    <div class="min-h-screen bg-cream">
        <a
            href="#contenu"
            class="sr-only z-[60] rounded-lg bg-ink px-4 py-2 text-white focus:not-sr-only focus:fixed focus:left-4 focus:top-4"
        >
            Aller au contenu
        </a>

        <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-gray-200 bg-white lg:flex">
            <div class="px-6 pb-6 pt-7">
                <Link :href="route('dashboard')" class="inline-block">
                    <ApplicationLogo class="h-8 w-auto" />
                </Link>
                <p class="mt-1 text-xs font-semibold text-gray-600">{{ spaces[user.role] }}</p>
            </div>

            <nav aria-label="Navigation principale" class="flex-1 space-y-1 px-3">
                <Link
                    v-for="item in navigation"
                    :key="item.route"
                    :href="route(item.route)"
                    :aria-current="isActive(item) ? 'page' : undefined"
                    class="flex min-h-11 items-center gap-3 rounded-lg border-r-4 px-3 text-sm font-semibold transition-colors"
                    :class="isActive(item)
                        ? 'border-primary-600 bg-primary-50 text-primary-700'
                        : 'border-transparent text-gray-700 hover:bg-sand hover:text-ink'"
                >
                    <Icon :name="item.icon" />
                    {{ item.label }}
                </Link>
            </nav>

            <div class="space-y-1 border-t border-gray-200 p-3">
                <Button
                    v-if="user.role === 'recruteur'"
                    :href="route('job-offers.create')"
                    icon="plus"
                    block
                    class="mb-3"
                >
                    Publier une offre
                </Button>
                <Link
                    :href="route('profile.edit')"
                    :aria-current="isActive(profileItem) ? 'page' : undefined"
                    class="flex min-h-11 items-center gap-3 rounded-lg px-3 text-sm font-semibold transition-colors"
                    :class="isActive(profileItem) ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-sand hover:text-ink'"
                >
                    <Icon name="user" />
                    Mon profil
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex min-h-11 w-full items-center gap-3 rounded-lg px-3 text-sm font-semibold text-gray-700 transition-colors hover:bg-sand hover:text-ink"
                >
                    <Icon name="logout" />
                    Se déconnecter
                </Link>
            </div>
        </aside>

        <div class="lg:pl-64">
            <header class="sticky top-0 z-20 border-b border-gray-200 bg-white/95 backdrop-blur">
                <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <Link :href="route('dashboard')" class="lg:hidden">
                        <ApplicationLogo class="h-7 w-auto" />
                    </Link>
                    <p class="hidden text-sm text-gray-600 lg:block">
                        Bonjour, <span class="font-semibold text-ink">{{ user.name }}</span>
                    </p>

                    <Dropdown align="right" width="48" content-classes="bg-white py-1">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex min-h-11 items-center gap-2 rounded-full p-1 pe-2 text-sm font-semibold text-ink hover:bg-sand"
                            >
                                <Avatar :name="user.name" size="sm" />
                                <span class="hidden max-w-[10rem] truncate sm:inline">{{ user.name }}</span>
                                <Icon name="chevronDown" size="sm" />
                                <span class="sr-only">Ouvrir le menu du compte</span>
                            </button>
                        </template>

                        <template #content>
                            <div class="border-b border-gray-200 px-4 py-3">
                                <p class="truncate text-sm font-semibold text-ink">{{ user.name }}</p>
                                <p class="truncate text-xs text-gray-600">{{ user.email }}</p>
                            </div>
                            <DropdownLink :href="route('profile.edit')">
                                <Icon name="user" size="sm" />
                                Mon profil
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                <Icon name="logout" size="sm" />
                                Se déconnecter
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <main id="contenu" class="mx-auto max-w-6xl px-4 pb-28 pt-6 sm:px-6 lg:px-8 lg:pb-12 lg:pt-8">
                <div v-if="$slots.header" class="mb-6">
                    <slot name="header" />
                </div>

                <FlashMessages class="mb-6" />

                <slot />
            </main>
        </div>

        <nav
            aria-label="Navigation principale"
            class="fixed inset-x-0 bottom-0 z-30 border-t border-gray-200 bg-white pb-[env(safe-area-inset-bottom)] lg:hidden"
        >
            <ul class="mx-auto flex max-w-md justify-around">
                <li v-for="item in [...navigation, profileItem]" :key="item.route" class="flex-1">
                    <Link
                        :href="route(item.route)"
                        :aria-current="isActive(item) ? 'page' : undefined"
                        class="flex min-h-16 flex-col items-center justify-center gap-1 text-[0.6875rem] font-semibold"
                        :class="isActive(item) ? 'text-primary-700' : 'text-gray-600'"
                    >
                        <span
                            class="inline-flex h-8 w-12 items-center justify-center rounded-full transition-colors"
                            :class="isActive(item) ? 'bg-primary-600 text-white' : ''"
                        >
                            <Icon :name="item.icon" />
                        </span>
                        {{ item.label }}
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
</template>
