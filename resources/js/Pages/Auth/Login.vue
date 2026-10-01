<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/ui/Button.vue';
import FlashMessages from '@/Components/ui/FlashMessages.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout title="Bon retour parmi nous" description="Connectez-vous pour retrouver vos offres et vos candidatures.">
        <Head title="Connexion" />

        <div v-if="status" role="status" class="mb-4 rounded-xl bg-primary-50 px-4 py-3 text-sm font-semibold text-primary-800">
            {{ status }}
        </div>

        <FlashMessages class="mb-4" />

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

            <div>
                <div class="flex items-center justify-between">
                    <InputLabel for="password" value="Mot de passe" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-semibold text-tertiary-700 underline-offset-2 hover:underline"
                    >
                        Mot de passe oublié ?
                    </Link>
                </div>
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1.5"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <label class="flex min-h-11 items-center gap-3">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="text-sm text-ink">Rester connecté sur cet appareil</span>
            </label>

            <Button type="submit" block size="lg" :loading="form.processing">
                Se connecter
            </Button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Pas encore de compte ?
            <Link :href="route('register')" class="font-semibold text-primary-700 underline-offset-2 hover:underline">
                Créer un compte
            </Link>
        </p>
    </GuestLayout>
</template>
