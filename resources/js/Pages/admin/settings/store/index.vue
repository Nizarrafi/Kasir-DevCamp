<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import axios from "axios";
import { notify } from "notiwind";
import AppLayout from '@/layouts/apps.vue';
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import VInput from '@/components/VInput/index.vue';
import VButton from '@/components/VButton/index.vue';

const props = defineProps({
    title: String,
    additional: Object
});

const breadcrumb = [
    { name: "Dashboard", active: false, to: route('dashboard.index') },
    { name: "Settings", active: false, to: '#' },
    { name: "Store Settings", active: true, to: route('settings.store.index') },
];

const form = ref({
    name: props.additional.setting?.name || '',
    address: props.additional.setting?.address || '',
    phone: props.additional.setting?.phone || ''
});
const errors = ref({});
const isLoading = ref(false);

const submit = () => {
    isLoading.value = true;
    errors.value = {};
    axios.post(route('settings.store.update'), form.value)
        .then(res => {
            notify({ type: "success", group: "top", text: "Store settings updated successfully" }, 2500);
        })
        .catch(err => {
            if (err.response?.data?.errors) errors.value = err.response.data.errors;
        })
        .finally(() => isLoading.value = false);
};
</script>

<template>
    <Head :title="title" />
    <VBreadcrumb :routes="breadcrumb" />

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Store Settings</h1>
        <p class="text-slate-500 text-sm mt-1">Configure your store information shown on receipts.</p>
    </div>

    <div class="max-w-2xl bg-white p-8 shadow-sm rounded-sm border border-slate-200">
        <div class="space-y-5">
            <VInput label="Store Name" v-model="form.name" :errorMessage="errors.name" placeholder="e.g. My Awesome Shop" />
            <VInput label="Address" v-model="form.address" :errorMessage="errors.address" placeholder="Store full address" />
            <VInput label="Phone Number" v-model="form.phone" :errorMessage="errors.phone" placeholder="e.g. 08123456789" />
            
            <div class="flex justify-end mt-8 border-t pt-6">
                <VButton label="Save Changes" type="primary" :isLoading="isLoading" @click="submit" />
            </div>
        </div>
    </div>
</template>
