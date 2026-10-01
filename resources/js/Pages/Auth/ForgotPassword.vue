<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/ui/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout
        title="Mot de passe oublié ?"
        description="Indiquez votre adresse e-mail : nous vous enverrons un lien pour choisir un nouveau mot de passe."
    >
        <Head title="Mot de passe oublié" />

        <div v-if="status" role="status" class="mb-4 rounded-xl bg-primary-50 px-4 py-3 text-sm font-semibold text-primary-800">
            {{ status }}
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Adresse e-mail" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <Button type="submit" block size="lg" :loading="form.processing">
                Recevoir le lien de réinitialisation
            </Button>
        </form>

        <p class="mt-6 text-center text-sm">
            <Link :href="route('login')" class="font-semibold text-primary-700 underline-offset-2 hover:underline">
                Retour à la connexion
            </Link>
        </p>
    </GuestLayout>
</template>
