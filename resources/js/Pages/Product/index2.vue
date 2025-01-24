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
            <!-- Form and buttons container -->
            <div class="mb-4 flex justify-between items-center">
              <!-- Left: Search form and select box -->
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

              <!-- Right: Export and Add buttons -->
              <div class="flex space-x-2">
                <button
                  @click="exportToExcel()"
                  class="bg-green-600 text-white px-2 py-1 text-sm"
                >
                  Export Excle
                </button>
                <button
                  @click="toFormCreate"
                  class="bg-red-500 text-white px-2 py-1 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 hover:bg-red-600"
                >
                  Tambah Produk
                </button>
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
                      :src="`/storage/${product.image}`"
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
                    <!-- Tombol Edit dan Hapus -->
                    <button
                      @click="deleteProduct(product.id)"
                      class="px-1 text-xs rounded"
                    >
                      <img src="/img/delete.png" alt="Delete" class="w-4" />
                    </button>
                    <button
                      @click="addProduct(product.id)"
                      class="px-1 text-xs rounded ml-2"
                    >
                      <img src="/img/edit.png" alt="Edit" class="w-4" />
                    </button>
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
    
    <script>
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { ref } from "vue";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

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
    const iconUrl = "/img/Package.png";

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

    const addProduct = (params) => {
      console.log(params, "ADD");
    };

    const toFormCreate = () => {
      // Inertia.visit(route("products.form"));
      Inertia.get("/products/form");
      return;
    };

    const deleteProduct = async (productId) => {
      console.log("Delete product", productId);
      try {
        // Menampilkan konfirmasi sebelum menghapus
        if (confirm(`Apakah anda yakin data akan dihapus ?`)) {
          const response = await axios.delete(`/api/products/${productId}`);
          // jika berhasil panggil products
          fetchProducts();

          alert("Product deleted successfully");
        }
      } catch (error) {
        console.error("Error deleting product:", error);
        alert("Failed to delete product");
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
      addProduct,
      iconUrl,
      deleteProduct,
      toFormCreate,
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

              <div>
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

              <div class="flex justify-end space-x-2">
                <button
                  type="button"
                  @click="cancel"
                  class="bg-gray-500 text-white px-4 py-2 rounded"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="bg-blue-500 text-white px-4 py-2 rounded"
                >
                  {{ isEdit ? "Update Product" : "Add Product" }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  
  <script>
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

export default {
  components: {
    AuthenticatedLayout,
  },
  setup() {
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

    const fetchCategories = async () => {
      try {
        const response = await axios.get("/api/categories");
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

    return {
      form,
      categories,
      isEdit,
      handleImageUpload,
      submitForm,
      cancel,
    };
  },
};
</script>
  
  <style scoped>
img {
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
  