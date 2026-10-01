<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/ui/Button.vue';
import ChoiceChips from '@/Components/ui/ChoiceChips.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'candidat',
    company_name: '',
});

const roles = [
    { value: 'candidat', label: 'Je cherche un emploi' },
    { value: 'recruteur', label: 'Je recrute' },
];

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout title="Créer votre compte" description="Quelques informations suffisent pour commencer.">
        <Head title="Inscription" />

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <ChoiceChips v-model="form.role" :options="roles" name="role" legend="Vous êtes ici pour…" layout="grid" />
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <div>
                <InputLabel for="name" value="Nom complet" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1.5"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div v-if="form.role === 'recruteur'">
                <InputLabel for="company_name" value="Nom de l'entreprise" />
                <TextInput
                    id="company_name"
                    type="text"
                    class="mt-1.5"
                    v-model="form.company_name"
                    required
                    autocomplete="organization"
                />
                <InputError class="mt-2" :message="form.errors.company_name" />
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

            <div>
                <InputLabel for="password" value="Mot de passe" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1.5"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirmez le mot de passe" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1.5"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <Button type="submit" block size="lg" :loading="form.processing">
                Créer mon compte
            </Button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Vous avez déjà un compte ?
            <Link :href="route('login')" class="font-semibold text-primary-700 underline-offset-2 hover:underline">
                Se connecter
            </Link>
        </p>
    </GuestLayout>
</template>
