<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/ui/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout
        title="Vérifiez votre adresse e-mail"
        description="Merci pour votre inscription ! Avant de commencer, cliquez sur le lien que nous venons de vous envoyer par e-mail. Vous ne l'avez pas reçu ? Nous pouvons vous en envoyer un nouveau."
    >
        <Head title="Vérification de l'adresse e-mail" />

        <div
            v-if="verificationLinkSent"
            role="status"
            class="mb-4 rounded-xl bg-primary-50 px-4 py-3 text-sm font-semibold text-primary-800"
        >
            Un nouveau lien de vérification vient d'être envoyé à l'adresse indiquée lors de votre inscription.
        </div>

        <form class="space-y-4" @submit.prevent="submit">
            <Button type="submit" block size="lg" :loading="form.processing">
                Renvoyer l'e-mail de vérification
            </Button>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="block w-full text-center text-sm font-semibold text-primary-700 underline-offset-2 hover:underline"
            >
                Se déconnecter
            </Link>
        </form>
    </GuestLayout>
</template>
