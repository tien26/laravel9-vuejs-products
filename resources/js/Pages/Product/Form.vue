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
              <div class="flex-1 grid grid-cols-6 gap-4">
                <div class="col-span-3">
                  <label
                    for="name"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Nama Kandidat
                  </label>
                  <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    required
                  />
                </div>

                <div class="col-span-3">
                  <label
                    for="catgeory"
                    class="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Category
                  </label>
                  <select
                    v-model="form.category_id"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    required
                  >
                    <option value="">Pilih Category</option>
                    <option
                      v-for="category in categories"
                      :key="category.name"
                      :value="category.id"
                    >
                      {{ category.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
                    disabled
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

                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                  <label
                    for="image"
                    class="block text-sm font-medium text-gray-700"
                    >Image</label
                  >

                  <label
                    for="image"
                    class="relative cursor-pointer w-full h-32 border border-gray-300 rounded px-3 py-2 flex justify-center items-center"
                  >
                    <div v-if="form.image_preview" class="mt-2">
                      <img
                        :src="form.image_preview"
                        alt="Preview"
                        class="w-20 h-20 object-contain"
                      />
                    </div>
                    <img
                      v-else
                      src="/img/image.png"
                      alt="Upload Icon"
                      class="w-12 h-12 object-contain"
                    />
                    <span class="sr-only">Upload Image</span>
                    <input
                      id="image"
                      type="file"
                      @change="handleImageUpload"
                      class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    />
                  </label>
                </div>
              </div>

              <div class="flex justify-end space-x-2">
                <Link
                  :href="route('products')"
                  class="bg-white text-blue-500 border border-blue-500 px-5 py-1 text-sm rounded-md outline-none focus:ring-2 focus:ring-blue-500 hover:bg-blue-500 hover:text-white"
                >
                  Batalkan
                </Link>
                <button
                  type="submit"
                  class="bg-blue-500 px-5 py-1 text-white text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:bg-blue-600"
                >
                  Simpan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";

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

const price_buy = computed(() => {
  return form.price * 1.3;
});

watch(price_buy, (newPriceBuy) => {
  form.price_buy = newPriceBuy;
});

const fetchCategories = async () => {
  try {
    const response = await axios.get("/api/categories", {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`, // Menambahkan token di header Authorization
      },
    });
    categories.value = response.data;
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    form.image_preview = URL.createObjectURL(file);
  }
};

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
  if (isEdit.value) {
    formData.append("_method", "PUT");
  }

  try {
    if (isEdit.value) {
      await axios.post(`/api/products/${form.id}`, formData, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          "Content-Type": "multipart/form-data",
        },
      });
      alert("Product updated successfully");
    } else {
      await axios.post("/api/products", formData, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          "Content-Type": "multipart/form-data",
        },
      });
      alert("Product added successfully");
    }
  } catch (error) {
    console.error("Error submitting form:", error);
    alert("Failed to save product");
  }
};

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
    form._method = "PUT";
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
