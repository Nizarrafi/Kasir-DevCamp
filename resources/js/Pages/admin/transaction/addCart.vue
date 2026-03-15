<script setup>
import axios from "axios";
import { notify } from "notiwind";
import { object } from "vue-types";
import { ref, onMounted, computed } from "vue";
import VInput from '@/components/VInput/index.vue';
import VSelect from '@/components/VSelect/index.vue';
import VButton from '@/components/VButton/index.vue';

const props = defineProps({
    additional: object().def({})
})

const emit = defineEmits(['successSubmit'])

const form = ref({
    category_id: '',
    product_id: '',
    qty: 1
})
const formError = ref({})
const categorySelectHandle = ref()
const productOptions = ref({}) // Use object for VSelect mapping
const productsRaw = ref([]) // Store raw objects to access stock
const productPlaceholder = ref('Select Product')
const productSelectHandle = ref()
const productLoading = ref(true)
const isLoading = ref(false)

const selectedProductStock = computed(() => {
    if (!form.value.product_id || !productsRaw.value.length) return null;
    const prod = productsRaw.value.find(p => p.id === form.value.product_id);
    return prod ? prod.stock : null;
})

const categorySelected = () => {
    formError.value.category_id = ''
    productLoading.value = true
    if (productSelectHandle.value) {
        productSelectHandle.value.clearSelected()
    }
    form.value.product_id = ''
    getProductPlaceholder()

    productLoading.value = true
    getProduct()
}

const getProductPlaceholder = () => {
    if (productLoading.value) {
        productPlaceholder.value = 'Loading...';
    } else {
        productPlaceholder.value = 'Select Product';
    }
}

const getProduct = async () => {
    axios.get(route('transaction.getproductdata'), {
        params: {
            category_id: form.value.category_id
        }
    }).then((res) => {
        productsRaw.value = res.data
        // Convert array of objects to key-value for VSelect
        const options = {}
        res.data.forEach(item => {
            options[item.id] = `${item.name} (Stock: ${item.stock})`
        })
        productOptions.value = options
    }).catch((res) => {
        notify({
            type: "error",
            group: "top",
            text: res.response?.data?.message || 'Failed to fetch products'
        }, 2500)
    }).finally(() => {
        productLoading.value = false
        getProductPlaceholder()
    })
};

const submit = async () => {
    // Client-side stock check
    if (selectedProductStock.value !== null && form.value.qty > selectedProductStock.value) {
        formError.value.qty = `Only ${selectedProductStock.value} items available in stock`
        return
    }

    isLoading.value = true
    axios.post(route('transaction.addtocart'), form.value)
        .then((res) => {
            emit('successSubmit')
            form.value = {
                category_id: '',
                product_id: '',
                qty: 1
            }
            if (categorySelectHandle.value) categorySelectHandle.value.clearSelected()
            if (productSelectHandle.value) productSelectHandle.value.clearSelected()
            
            notify({
                type: "success",
                group: "top",
                text: res.data.meta.message
            }, 2500)
        }).catch((res) => {
            const result = res.response.data
            const metaError = res.response.data.meta?.error
            if (result.hasOwnProperty('errors')) {
                formError.value = {};
                Object.keys(result.errors).map((key) => {
                    formError.value[key] = result.errors[key].toString();
                });
            }

            if (metaError) {
                notify({
                    type: "error",
                    group: "top",
                    text: metaError
                }, 2500)
            } else {
                notify({
                    type: "error",
                    group: "top",
                    text: result.message
                }, 2500)
            }
        }).finally(() => isLoading.value = false)
}

onMounted(() => {
    getProduct();
});
</script>

<template>
    <div class="w-full md:w-1/3 rounded-lg bg-white px-4 py-6 border drop-shadow-sm h-fit">
        <div class="grid grid-cols-1 gap-3">
            <VSelect placeholder="Select Category" v-model="form.category_id" :options="additional.category_list"
                label="Category" :errorMessage="formError.category_id" @update:modelValue="categorySelected" ref="categorySelectHandle"/>
            
            <VSelect :placeholder="productPlaceholder" :required="true" v-model="form.product_id" :options="productOptions"
                label="Product" :errorMessage="formError.product_id" @update:modelValue="formError.product_id = '', formError.qty = ''"
                ref="productSelectHandle" :disabled="productLoading" />
            
            <div v-if="selectedProductStock !== null" class="text-xs text-slate-500 -mt-2 ml-1">
                Available Stock: <span class="font-bold" :class="selectedProductStock < 10 ? 'text-red-500' : 'text-green-600'">{{ selectedProductStock }}</span>
            </div>

            <VInput placeholder="Input Qty" label="Qty" :required="true" v-model="form.qty"
                :errorMessage="formError.qty" @update:modelValue="formError.qty = ''" type="number" />
        </div>
        <div class="flex flex-wrap justify-end space-x-2 mt-3">
            <VButton :is-loading="isLoading" label="Add to Cart" type="primary" @click="submit" :disabled="!form.product_id || !form.qty || form.qty < 1" />
        </div>
    </div>
</template>
