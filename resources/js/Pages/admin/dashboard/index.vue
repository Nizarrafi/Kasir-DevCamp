<script>
export default {
    layout: AppLayout
}
</script>
<script setup>
import { Head } from "@inertiajs/inertia-vue3"
import { array, object, string } from 'vue-types';
import AppLayout from '@/layouts/apps.vue';
import VBreadcrumb from '@/components/VBreadcrumb/index.vue';
import number_format from "@/composables/formatting"

const props = defineProps({
    filter: object(),
    query: array(),
    modules: array(),
    title: string(),
    additional: object(),
})

const breadcrumb = [
    {
        name: "Dashboard",
        active: true,
        to: route('dashboard.index')
    },
]

const statCards = [
    {
        key: 'total_revenue',
        label: 'Total Revenue',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
        color: 'bg-blue-500',
        format: 'currency',
    },
    {
        key: 'total_transactions',
        label: 'Total Transactions',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>`,
        color: 'bg-green-500',
        format: 'number',
    },
    {
        key: 'total_products',
        label: 'Total Products',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>`,
        color: 'bg-purple-500',
        format: 'number',
    },
    {
        key: 'total_categories',
        label: 'Total Categories',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>`,
        color: 'bg-yellow-500',
        format: 'number',
    },
]

const formatValue = (key, format) => {
    const val = props.additional?.[key] ?? 0
    if (format === 'currency') {
        return 'Rp' + number_format(val, 0, ',', '.')
    }
    return val
}
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />

    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Dashboard</h1>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <div
            v-for="card in statCards"
            :key="card.key"
            class="bg-white rounded-lg shadow-md border border-slate-200 p-5 flex items-center space-x-4"
        >
            <div :class="card.color" class="rounded-full p-3 text-white flex-shrink-0" v-html="card.icon"></div>
            <div>
                <p class="text-sm text-slate-500 font-medium">{{ card.label }}</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ formatValue(card.key, card.format) }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white shadow-md rounded-lg border border-slate-200">
        <header class="py-5 px-6 border-b border-slate-200">
            <h2 class="font-semibold text-slate-800 text-lg">Recent Transactions</h2>
            <p class="text-sm text-slate-500 mt-1">Last 5 transactions from all cashiers</p>
        </header>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Invoice</th>
                        <th class="px-6 py-3">Cashier</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-if="!additional?.recent_transactions?.length">
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            No transactions yet.
                        </td>
                    </tr>
                    <tr
                        v-for="trx in additional?.recent_transactions"
                        :key="trx.id"
                        class="hover:bg-slate-50 transition-colors"
                    >
                        <td class="px-6 py-4 font-medium text-slate-800">{{ trx.invoice_code }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ trx.cashier_name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ trx.created_at }}</td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-800">
                            Rp{{ trx.grand_total_formatted }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
