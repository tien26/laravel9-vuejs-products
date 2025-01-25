<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Product List
      </h2>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-4 flex justify-between items-center">
              <form @submit.prevent="searchProducts" class="flex space-x-4">
                <input
                  type="text"
                  v-model="filters.search"
                  placeholder="Cari Barang"
                  class="border px-4 py-1 text-sm"
                />
                <select
                  v-model="filters.category"
                  class="border px-4 py-1 text-sm"
                  @change="searchProducts"
                >
                  <option value="">Semua</option>
                  <option
                    v-for="category in categories"
                    :key="category.name"
                    :value="category.name"
                  >
                    {{ category.name }}
                  </option>
                </select>
              </form>

              <div class="flex space-x-2">
                <button
                  @click="exportToExcel"
                  class="bg-green-600 text-white px-2 py-1 text-sm"
                >
                  Export Excel
                </button>

                <Link
                  :href="route('products.form')"
                  class="bg-red-500 text-white px-2 py-1 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 hover:bg-red-600"
                >
                  Tambah Produk
                </Link>
              </div>
            </div>

            <table
              class="table-auto w-full border-collapse border border-gray-200 text-sm"
            >
              <thead>
                <tr>
                  <th class="border px-4 py-2">#</th>
                  <th class="border px-2 py-1">Image</th>
                  <th class="border px-4 py-2">Name</th>
                  <th class="border px-4 py-2">Category</th>
                  <th class="border px-4 py-2">Price</th>
                  <th class="border px-4 py-2">Price Buy</th>
                  <th class="border px-4 py-2">Stok Produk</th>
                  <th class="border px-2 py-1">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(product, index) in products.data" :key="product.id">
                  <td class="border px-4 py-2">{{ index + 1 }}</td>
                  <td class="border px-2 py-1">
                    <img
                      :src="`${
                        product.image
                          ? '/storage/' + product.image
                          : '/img/image.png'
                      }`"
                      alt="Product Image"
                      class="w-10 object-cover"
                    />
                  </td>
                  <td class="border px-4 py-2">{{ product.name }}</td>
                  <td class="border px-4 py-2">{{ product.category.name }}</td>
                  <td class="border px-4 py-2">
                    {{ formatRupiah(product.price) }}
                  </td>
                  <td class="border px-4 py-2">
                    {{ formatRupiah(product.price_buy) }}
                  </td>
                  <td class="border px-4 py-2">{{ product.stock }}</td>
                  <td class="border px-2 py-1">
                    <div class="inline-flex space-x-2">
                      <button
                        @click="deleteProduct(product.id)"
                        class="px-1 text-xs rounded"
                      >
                        <img src="/img/delete.png" alt="Delete" class="w-4" />
                      </button>
                      <Link
                        :href="route('products.edit', { id: product.id })"
                        class="px-1 text-xs rounded"
                      >
                        <img src="/img/edit.png" alt="Edit" class="w-4" />
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="mt-4 flex justify-between items-center">
              <div class="text-sm">
                Show {{ products.per_page }} From {{ products.total }}
              </div>
              <button
                v-if="products.prev_page_url"
                @click="fetchProducts(products.prev_page_url)"
                class="mr-2 bg-gray-500 text-white px-4 py-1 text-sm"
              >
                Previous
              </button>
              <button
                v-if="products.next_page_url"
                @click="fetchProducts(products.next_page_url)"
                class="bg-gray-500 text-white px-4 py-1 text-sm"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";

const products = ref([]);
const filters = ref({
  search: "",
  category: "",
});
const categories = ref([]);
const pagination = ref({
  prev_page_url: null,
  next_page_url: null,
});

const fetchCategories = async () => {
  try {
    const response = await axios.get("/api/categories");
    categories.value = response.data;
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

const exportToExcel = async () => {
  let url = "/api/products/export";
  const queryParams = new URLSearchParams();

  if (filters.value.search) {
    queryParams.append("search", filters.value.search);
  }

  if (filters.value.category) {
    queryParams.append("category", filters.value.category);
  }

  if (queryParams.toString()) {
    url = `${url}?${queryParams.toString()}`;
  }

  try {
    const response = await axios.get(url, { responseType: "blob" });
    const fileName = "products.xlsx";
    const blob = new Blob([response.data], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = fileName;
    link.click();
  } catch (error) {
    console.error("Error exporting data:", error);
  }
};

const addProduct = (id) => {
  console.log(id, "ADD");
};

const deleteProduct = async (productId) => {
  if (confirm("Apakah anda yakin data akan dihapus ?")) {
    try {
      await axios.delete(`/api/products/${productId}`);
      fetchProducts();
      alert("Product deleted successfully");
    } catch (error) {
      console.error("Error deleting product:", error);
      alert("Failed to delete product");
    }
  }
};

const formatRupiah = (angka) => {
  const formatter = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  });
  return formatter.format(angka);
};

const fetchProducts = async (url = "/api/products") => {
  let finalUrl = url;

  if (filters.value.search || filters.value.category) {
    finalUrl = `${url}?search=${encodeURIComponent(
      filters.value.search
    )}&category=${encodeURIComponent(filters.value.category)}`;
  }

  try {
    const response = await axios.get(finalUrl);
    products.value = response.data;
    pagination.value = {
      prev_page_url: response.data.prev_page_url,
      next_page_url: response.data.next_page_url,
    };
  } catch (error) {
    console.error("Error fetching products:", error);
  }
};

const searchProducts = () => {
  pagination.value = { prev_page_url: null, next_page_url: null };
  fetchProducts();
};

onMounted(() => {
  fetchProducts();
  fetchCategories();
});
</script>

<style scoped>
table {
  width: 100%;
}
th,
td {
  text-align: left;
}
</style>
