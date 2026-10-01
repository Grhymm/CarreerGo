<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatCard from '@/Components/ui/StatCard.vue';
import { formatDate } from '@/utils/format';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    jobOffers: Array,
});

const moderate = (jobOffer, status) => {
    router.patch(route('admin.moderation.update', jobOffer.id), { status });
};
</script>

<template>
    <Head title="Modération" />

    <AuthenticatedLayout>
        <PageHeader
            title="Modération des offres"
            description="Vérifiez chaque offre avant sa mise en ligne : légitimité de l’entreprise, clarté de la description, conditions annoncées."
        />

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <StatCard tone="primary" icon="shield" :value="jobOffers.length" label="Offres en attente de validation" />
        </div>

        <section class="mt-8" aria-labelledby="titre-file">
            <h2 id="titre-file" class="mb-4 text-xl font-bold">File d’attente</h2>

            <EmptyState
                v-if="jobOffers.length === 0"
                icon="check"
                title="Tout est à jour"
                description="Aucune offre n’attend de modération pour le moment."
            />

            <div v-else class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-card">
                <table class="hidden w-full text-left text-sm md:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="ui-label-caps px-5 py-3">Offre</th>
                            <th scope="col" class="ui-label-caps px-5 py-3">Entreprise</th>
                            <th scope="col" class="ui-label-caps px-5 py-3">Soumise le</th>
                            <th scope="col" class="ui-label-caps px-5 py-3">Statut</th>
                            <th scope="col" class="px-5 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="jobOffer in jobOffers" :key="jobOffer.id" class="align-middle">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-ink">{{ jobOffer.title }}</p>
                                <p class="text-xs text-gray-600">{{ jobOffer.contract_type }} · {{ jobOffer.location }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <Avatar :name="jobOffer.company.name" size="sm" square />
                                    <div>
                                        <p class="font-semibold text-ink">{{ jobOffer.company.name }}</p>
                                        <p class="text-xs text-gray-600">par {{ jobOffer.user.name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ formatDate(jobOffer.created_at) }}</td>
                            <td class="px-5 py-4"><Badge tone="accent" dot>En attente</Badge></td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <Button size="sm" icon="check" @click="moderate(jobOffer, 'published')">Valider</Button>
                                    <Button size="sm" variant="outlined" icon="x" @click="moderate(jobOffer, 'rejected')">Refuser</Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <ul class="divide-y divide-gray-100 md:hidden">
                    <li v-for="jobOffer in jobOffers" :key="jobOffer.id" class="p-4">
                        <div class="flex items-start gap-3">
                            <Avatar :name="jobOffer.company.name" square />
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-ink">{{ jobOffer.title }}</p>
                                <p class="text-sm text-gray-600">{{ jobOffer.company.name }} · par {{ jobOffer.user.name }}</p>
                                <p class="mt-1 text-xs text-gray-600">
                                    {{ jobOffer.contract_type }} · {{ jobOffer.location }} · soumise le {{ formatDate(jobOffer.created_at) }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <Button icon="check" @click="moderate(jobOffer, 'published')">Valider</Button>
                            <Button variant="outlined" icon="x" @click="moderate(jobOffer, 'rejected')">Refuser</Button>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <aside class="mt-8 flex gap-4 rounded-2xl bg-primary-600 p-5 text-white sm:p-6">
            <Icon name="shield" size="lg" class="mt-0.5" />
            <div>
                <h2 class="font-heading text-lg font-bold">Guide de modération</h2>
                <p class="mt-1 text-sm text-primary-50">
                    Une offre validée devient immédiatement visible par tous les candidats. Une offre refusée n’est plus proposée à la modération.
                </p>
            </div>
        </aside>
    </AuthenticatedLayout>
</template>
