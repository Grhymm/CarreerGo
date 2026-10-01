<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/ui/Button.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold">Supprimer mon compte</h2>
            <p class="mt-1 text-sm text-gray-600">
                La suppression est définitive : toutes les données liées à votre compte seront effacées.
            </p>
        </header>

        <Button variant="danger" class="mt-5" icon="trash" @click="confirmUserDeletion">
            Supprimer mon compte
        </Button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-bold">Voulez-vous vraiment supprimer votre compte ?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Cette action est irréversible. Saisissez votre mot de passe pour confirmer la suppression définitive de votre compte.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Mot de passe" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1.5"
                        autocomplete="current-password"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <Button variant="outlined" @click="closeModal">Annuler</Button>
                    <Button variant="danger" :loading="form.processing" @click="deleteUser">
                        Supprimer définitivement
                    </Button>
                </div>
            </div>
        </Modal>
    </section>
</template>
