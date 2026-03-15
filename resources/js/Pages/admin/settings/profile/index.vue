<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import { Head, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed } from "vue";
import axios from "axios";
import { notify } from "notiwind";
import AppLayout from '@/layouts/apps.vue';
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VInput from '@/components/VInput/index.vue';
import VButton from '@/components/VButton/index.vue';

const user = computed(() => usePage().props.value.admin_data);
const props = defineProps({
    title: String
});

const breadcrumb = [
    { name: "Dashboard", active: false, to: route('dashboard.index') },
    { name: "Settings", active: false, to: '#' },
    { name: "Profile", active: true, to: route('settings.profile.index') },
];

const profileForm = ref({
    name: user.value.name,
    email: user.value.email
});
const profileErrors = ref({});
const profileLoading = ref(false);

const passwordForm = ref({
    current_password: '',
    password: '',
    password_confirmation: ''
});
const passwordErrors = ref({});
const passwordLoading = ref(false);

const updateProfile = () => {
    profileLoading.value = true;
    profileErrors.value = {};
    axios.post(route('settings.profile.update'), profileForm.value)
        .then(res => {
            notify({ type: "success", group: "top", text: "Profile updated successfully" }, 2500);
        })
        .catch(err => {
            if (err.response?.data?.errors) profileErrors.value = err.response.data.errors;
        })
        .finally(() => profileLoading.value = false);
};

const updatePassword = () => {
    passwordLoading.value = true;
    passwordErrors.value = {};
    axios.post(route('settings.profile.update-password'), passwordForm.value)
        .then(res => {
            notify({ type: "success", group: "top", text: "Password updated successfully" }, 2500);
            passwordForm.value = { current_password: '', password: '', password_confirmation: '' };
        })
        .catch(err => {
            if (err.response?.data?.errors) passwordErrors.value = err.response.data.errors;
        })
        .finally(() => passwordLoading.value = false);
};
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Profile Settings</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Info -->
        <div class="bg-white p-6 shadow-sm rounded-sm border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Account Information</h2>
            <div class="space-y-4">
                <VInput label="Name" v-model="profileForm.name" :errorMessage="profileErrors.name" />
                <VInput label="Email" v-model="profileForm.email" :errorMessage="profileErrors.email" />
                <div class="flex justify-end mt-6">
                    <VButton label="Update Profile" type="primary" :isLoading="profileLoading" @click="updateProfile" />
                </div>
            </div>
        </div>

        <!-- Password Update -->
        <div class="bg-white p-6 shadow-sm rounded-sm border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Update Password</h2>
            <div class="space-y-4">
                <VInput label="Current Password" type="password" v-model="passwordForm.current_password" :errorMessage="passwordErrors.current_password" />
                <VInput label="New Password" type="password" v-model="passwordForm.password" :errorMessage="passwordErrors.password" />
                <VInput label="Confirm Password" type="password" v-model="passwordForm.password_confirmation" :errorMessage="passwordErrors.password_confirmation" />
                <div class="flex justify-end mt-6">
                    <VButton label="Change Password" type="primary" :isLoading="passwordLoading" @click="updatePassword" />
                </div>
            </div>
        </div>
    </div>
</template>
