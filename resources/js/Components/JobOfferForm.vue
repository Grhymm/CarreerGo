<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import ChoiceChips from '@/Components/ui/ChoiceChips.vue';
import Icon from '@/Components/ui/Icon.vue';
import TagInput from '@/Components/ui/TagInput.vue';
import Toggle from '@/Components/ui/Toggle.vue';
import { formatSalaryRange } from '@/utils/format';
import { contractTypes } from '@/utils/status';
import { computed } from 'vue';

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    submitLabel: {
        type: String,
        required: true,
    },
    moderationNotice: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['submit']);

const contractOptions = contractTypes.map((type) => ({ value: type, label: type }));

const salaryPreview = computed(() => formatSalaryRange(props.form.salary_min, props.form.salary_max));
</script>

<template>
    <form class="grid gap-6 lg:grid-cols-[1fr_18rem]" @submit.prevent="$emit('submit')">
        <div class="space-y-6">
            <Card padding="lg">
                <h2 class="flex items-center gap-2 text-lg font-bold">
                    <Icon name="info" class="text-primary-700" />
                    Informations générales
                </h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <InputLabel for="title" value="Intitulé du poste" />
                        <TextInput
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1.5"
                            placeholder="ex. Comptable confirmé(e)"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description du poste" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="ui-input mt-1.5"
                            rows="8"
                            placeholder="Décrivez les missions, l’équipe, le profil recherché…"
                            required
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>
                </div>
            </Card>

            <div class="grid gap-6 md:grid-cols-2">
                <Card padding="lg">
                    <h2 class="flex items-center gap-2 text-lg font-bold">
                        <Icon name="sparkle" class="text-primary-700" />
                        Compétences
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">Elles servent à calculer la compatibilité des candidats.</p>
                    <div class="mt-4">
                        <TagInput id="skills" v-model="form.skills" label="Compétences demandées" />
                        <InputError class="mt-2" :message="form.errors.skills" />
                    </div>
                </Card>

                <Card padding="lg">
                    <h2 class="flex items-center gap-2 text-lg font-bold">
                        <Icon name="pin" class="text-primary-700" />
                        Cadre de travail
                    </h2>

                    <div class="mt-4 space-y-5">
                        <div>
                            <InputLabel for="location" value="Localisation" />
                            <TextInput
                                id="location"
                                v-model="form.location"
                                type="text"
                                class="mt-1.5"
                                placeholder="ex. Libreville"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>

                        <div>
                            <ChoiceChips
                                v-model="form.contract_type"
                                :options="contractOptions"
                                name="contract_type"
                                legend="Type de contrat"
                                layout="grid"
                            />
                            <InputError class="mt-2" :message="form.errors.contract_type" />
                        </div>
                    </div>
                </Card>
            </div>

            <Card v-if="form.contract_type === 'Stage'" padding="lg">
                <h2 class="flex items-center gap-2 text-lg font-bold">
                    <Icon name="calendar" class="text-primary-700" />
                    Modalités du stage
                </h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel for="stage_duration_months" value="Durée (en mois)" />
                        <TextInput id="stage_duration_months" v-model="form.stage_duration_months" type="number" min="1" class="mt-1.5" required />
                        <InputError class="mt-2" :message="form.errors.stage_duration_months" />
                    </div>
                    <div>
                        <InputLabel for="stage_stipend" value="Gratification (FCFA par mois)" />
                        <TextInput id="stage_stipend" v-model="form.stage_stipend" type="number" min="0" class="mt-1.5" required />
                        <InputError class="mt-2" :message="form.errors.stage_stipend" />
                    </div>
                </div>
            </Card>

            <Card padding="lg">
                <h2 class="flex items-center gap-2 text-lg font-bold">
                    <Icon name="chart" class="text-primary-700" />
                    Rémunération
                </h2>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel for="salary_min" value="Salaire minimum (FCFA par mois)" />
                        <TextInput id="salary_min" v-model="form.salary_min" type="number" min="0" class="mt-1.5" />
                        <InputError class="mt-2" :message="form.errors.salary_min" />
                    </div>
                    <div>
                        <InputLabel for="salary_max" value="Salaire maximum (FCFA par mois)" />
                        <TextInput id="salary_max" v-model="form.salary_max" type="number" min="0" class="mt-1.5" />
                        <InputError class="mt-2" :message="form.errors.salary_max" />
                    </div>
                </div>

                <div class="mt-5 rounded-xl bg-sand p-4">
                    <Toggle
                        id="show_salary"
                        v-model="form.show_salary"
                        label="Afficher le salaire sur l’offre"
                        description="Sinon, la fourchette n’est pas affichée aux candidats."
                    />
                </div>

                <label class="mt-5 flex items-start gap-3">
                    <Checkbox id="guaranteed_response" v-model:checked="form.guaranteed_response" class="mt-0.5" />
                    <span>
                        <span class="block text-sm font-semibold text-ink">Je m’engage à répondre à chaque candidat</span>
                        <span class="block text-sm text-gray-600">Le badge « Réponse garantie » sera affiché sur votre offre.</span>
                    </span>
                </label>
            </Card>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                <Button :href="route('job-offers.index')" variant="ghost">Annuler</Button>
                <Button type="submit" size="lg" icon="send" :loading="form.processing">{{ submitLabel }}</Button>
            </div>
        </div>

        <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
            <div class="rounded-2xl bg-primary-600 p-5 text-white">
                <Icon name="lightbulb" size="lg" />
                <p class="mt-3 font-heading font-bold">Conseil</p>
                <p class="mt-1 text-sm text-primary-50">
                    Une fourchette de salaire et une liste précise de compétences aident les candidats à savoir rapidement si le poste leur correspond.
                </p>
            </div>

            <Card padding="sm">
                <p class="ui-label-caps">Aperçu</p>
                <p class="mt-2 font-heading text-lg font-bold leading-snug">{{ form.title || 'Intitulé du poste' }}</p>
                <p class="mt-1 flex items-center gap-1 text-sm text-gray-600">
                    <Icon name="pin" size="sm" />
                    {{ form.location || 'Localisation' }}
                </p>
                <div class="mt-3 flex flex-wrap gap-1.5">
                    <Badge tone="tertiary">{{ form.contract_type }}</Badge>
                    <Badge v-if="form.guaranteed_response" tone="accent">Réponse garantie</Badge>
                </div>
                <p v-if="form.show_salary && salaryPreview" class="mt-3 text-sm font-semibold text-primary-700">{{ salaryPreview }}</p>
            </Card>

            <p v-if="moderationNotice" class="flex gap-2 rounded-xl bg-tertiary-50 p-4 text-sm text-tertiary-800">
                <Icon name="shield" class="mt-0.5" />
                Après envoi, votre offre est vérifiée par notre équipe avant d’être publiée.
            </p>
        </aside>
    </form>
</template>
