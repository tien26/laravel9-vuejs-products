<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Product List
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="searchProducts" class="mb-4 flex space-x-4">
              <input
                type="text"
                v-model="filters.search"
                placeholder="Search Product Name"
                class="border px-4 py-2"
              />
              <select
                v-model="filters.category"
                class="border px-4 py-2"
                @change="searchProducts"
              >
                <option value="">All</option>
                <option
                  v-for="category in categories"
                  :key="category.name"
                  :value="category.name"
                >
                  {{ category.name }}
                </option>
              </select>
              <button type="submit" class="bg-blue-500 text-white px-4 py-2">
                Search
              </button>
            </form>

            <!-- Tombol Export untuk data yang difilter -->
            <button
              @click="exportToExcel()"
              class="bg-green-500 text-white px-4 py-2 mb-4"
            >
              Export Filtered Data
            </button>

            <table
              class="table-auto w-full border-collapse border border-gray-200"
            >
              <thead>
                <tr>
                  <th class="border px-4 py-2">#</th>
                  <th class="border px-4 py-2">Name</th>
                  <th class="border px-4 py-2">Category</th>
                  <th class="border px-4 py-2">Price</th>
                  <th class="border px-4 py-2">Price Buy</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(product, index) in products.data" :key="product.id">
                  <td class="border px-4 py-2">{{ index + 1 }}</td>
                  <td class="border px-4 py-2">{{ product.name }}</td>
                  <td class="border px-4 py-2">{{ product.category.name }}</td>
                  <td class="border px-4 py-2">
                    {{ formatRupiah(product.price) }}
                  </td>
                  <td class="border px-4 py-2">
                    {{ formatRupiah(product.price_buy) }}
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="mt-4">
              <button
                v-if="products.prev_page_url"
                @click="fetchProducts(products.prev_page_url)"
                class="mr-2 bg-gray-500 text-white px-4 py-2"
              >
                Previous
              </button>
              <button
                v-if="products.next_page_url"
                @click="fetchProducts(products.next_page_url)"
                class="bg-gray-500 text-white px-4 py-2"
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
  
  <script>
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { ref } from "vue";
import axios from "axios";

export default {
  components: {
    AuthenticatedLayout,
  },
  setup() {
    const products = ref([]);
    const filters = ref({
      search: "",
      category: "",
    });
    const categories = ref([]);

    // Fungsi untuk fetch kategori
    const fetchCategories = async () => {
      try {
        const response = await axios.get("/api/categories");
        categories.value = response.data;
      } catch (error) {
        console.error("Error fetching categories:", error);
      }
    };

    // Menyimpan URL pagination
    const pagination = ref({
      prev_page_url: null,
      next_page_url: null,
    });

    const exportToExcel = async () => {
      let url = "/api/products/export";

      // Menambahkan query parameter jika ada filter yang diterapkan
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

    const formatRupiah = (angka) => {
      const formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        // Hapus angka di belakang koma
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      });
      return formatter.format(angka);
    };

    // Fungsi untuk fetch data produk dan pagination
    const fetchProducts = async (url = "/api/products") => {
      let finalUrl = url;

      // Cek apakah search dan category sudah diisi
      if (filters.value.search || filters.value.category) {
        finalUrl = `${url}?search=${encodeURIComponent(
          filters.value.search
        )}&category=${encodeURIComponent(filters.value.category)}`;
      }

      try {
        const response = await axios.get(finalUrl);
        products.value = response.data;

        // Update URL pagination setelah data di-fetch
        pagination.value = {
          prev_page_url: response.data.prev_page_url,
          next_page_url: response.data.next_page_url,
        };
      } catch (error) {
        console.error("Error fetching products:", error);
      }
    };

    // Fungsi search (untuk reset pagination ke halaman pertama)
    const searchProducts = () => {
      pagination.value = {
        prev_page_url: null,
        next_page_url: null,
      };
      fetchProducts(); // fetch data produk berdasarkan filter
    };

    // Panggil load data
    fetchProducts();
    fetchCategories();

    return {
      products,
      filters,
      pagination,
      fetchProducts,
      searchProducts,
      formatRupiah,
      exportToExcel,
      categories,
    };
  },
};
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
  