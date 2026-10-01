<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/ui/Button.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold">Informations du compte</h2>
            <p class="mt-1 text-sm text-gray-600">Modifiez votre nom et votre adresse e-mail.</p>
        </header>

        <form class="mt-6 space-y-5" @submit.prevent="form.patch(route('profile.update'))">
            <div>
                <InputLabel for="name" value="Nom complet" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1.5"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Adresse e-mail" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="rounded-xl bg-sand p-4 text-sm text-ink">
                <p>
                    Votre adresse e-mail n’est pas encore vérifiée.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="font-semibold text-primary-700 underline"
                    >
                        Renvoyer l’e-mail de vérification
                    </Link>
                </p>

                <p v-show="status === 'verification-link-sent'" role="status" class="mt-2 font-semibold text-primary-700">
                    Un nouveau lien de vérification vient de vous être envoyé.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" :loading="form.processing">Enregistrer</Button>
                <Transition enter-active-class="transition-opacity" enter-from-class="opacity-0" leave-active-class="transition-opacity" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" role="status" class="text-sm font-semibold text-primary-700">
                        Modifications enregistrées.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
