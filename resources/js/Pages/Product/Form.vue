<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ isEdit ? "Edit Product" : "Add Product" }}
      </h2>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submitForm" class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                <!-- Kolom pertama: Product Name dan Category -->
                <div>
                  <label
                    for="name"
                    class="block text-sm font-medium text-gray-700"
                    >Product Name</label
                  >
                  <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    required
                  />
                </div>

                <div>
                  <label
                    for="category"
                    class="block text-sm font-medium text-gray-700"
                    >Category</label
                  >
                  <select
                    id="category"
                    v-model="form.category_id"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    required
                  >
                    <option value="" disabled>Select a category</option>
                    <option
                      v-for="category in categories"
                      :key="category.id"
                      :value="category.id"
                    >
                      {{ category.name }}
                    </option>
                  </select>
                </div>

                <!-- Kolom kedua: Price, Price Buy, dan Stock -->
                <div>
                  <label
                    for="price"
                    class="block text-sm font-medium text-gray-700"
                    >Price</label
                  >
                  <input
                    id="price"
                    type="number"
                    v-model="form.price"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    required
                  />
                </div>

                <div>
                  <label
                    for="price_buy"
                    class="block text-sm font-medium text-gray-700"
                    >Price Buy</label
                  >
                  <input
                    id="price_buy"
                    type="number"
                    v-model="form.price_buy"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    required
                  />
                </div>

                <div>
                  <label
                    for="stock"
                    class="block text-sm font-medium text-gray-700"
                    >Stock</label
                  >
                  <input
                    id="stock"
                    type="number"
                    v-model="form.stock"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    required
                  />
                </div>

                <!-- Bagian upload gambar, lebar penuh -->
                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                  <label
                    for="image"
                    class="block text-sm font-medium text-gray-700"
                    >Image</label
                  >
                  <input
                    id="image"
                    type="file"
                    @change="handleImageUpload"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                  />
                  <div v-if="form.image_preview" class="mt-2">
                    <img
                      :src="form.image_preview"
                      alt="Preview"
                      class="w-20 h-20 object-cover"
                    />
                  </div>
                </div>
              </div>

              <div class="flex justify-end space-x-2">
                <Link
                  :href="route('products')"
                  class="bg-white text-blue-500 border border-blue-500 px-5 py-1 text-sm rounded-md outline-none focus:ring-2 focus:ring-blue-500 hover:bg-blue-500 hover:text-white"
                >
                  Batalkan
                </Link>
                <Link
                  :href="route('products')"
                  class="bg-blue-500 px-5 py-1 text-white text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:bg-blue-600"
                >
                  Simpan
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";

// Reactive form state
const form = reactive({
  id: null,
  name: "",
  category_id: "",
  price: "",
  price_buy: "",
  stock: "",
  image: null,
  image_preview: null,
});

const categories = ref([]);
const isEdit = ref(false);

// Fetch categories from API
const fetchCategories = async () => {
  try {
    const response = await axios.get("/api/categories");
    categories.value = response.data;
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

// Handle image upload
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    form.image_preview = URL.createObjectURL(file);
  }
};

// Submit form data
const submitForm = async () => {
  const formData = new FormData();
  formData.append("name", form.name);
  formData.append("category_id", form.category_id);
  formData.append("price", form.price);
  formData.append("price_buy", form.price_buy);
  formData.append("stock", form.stock);
  if (form.image) {
    formData.append("image", form.image);
  }

  try {
    if (isEdit.value) {
      await axios.post(`/api/products/${form.id}`, formData);
      alert("Product updated successfully");
    } else {
      await axios.post("/api/products", formData);
      alert("Product added successfully");
    }
  } catch (error) {
    console.error("Error submitting form:", error);
    alert("Failed to save product");
  }
};

// Cancel action (go back)
const cancel = () => {
  window.history.back();
};

// Populate form data for edit
const page = usePage();
onMounted(() => {
  fetchCategories();

  if (page.props.product) {
    const { product } = page.props;
    isEdit.value = true;
    form.id = product.id;
    form.name = product.name;
    form.category_id = product.category_id;
    form.price = product.price;
    form.price_buy = product.price_buy;
    form.stock = product.stock;
    form.image_preview = `/storage/${product.image}`;
  }
});
</script>

<style scoped>
img {
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
