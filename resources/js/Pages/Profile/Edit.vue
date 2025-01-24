<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
});

const user = usePage().props.auth.user;

// Form data
const form = useForm({
  name: user.name,
  position: user.position,
  photo: null, // Tambahkan untuk menyimpan gambar
});

// State untuk gambar preview
const previewImage = ref(user.photo_url || "/img/Frame98700.png"); // Default jika tidak ada gambar dari database

// Fungsi untuk menangani perubahan input file
const handleImageChange = (event) => {
  console.log(user, "USER");

  const file = event.target.files[0];
  if (file) {
    form.photo = file; // Simpan file gambar di form
    previewImage.value = URL.createObjectURL(file); // Tampilkan gambar preview
  }
};
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </template>

    <div class="py-6 px-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Image Preview with Upload Button -->
        <div class="flex items-center space-x-4">
          <!-- Gambar Preview -->
          <div class="relative">
            <img
              :src="previewImage"
              alt="Profile Picture"
              class="w-32 h-32 rounded-full object-cover shadow-md"
            />
            <!-- Icon Pencil -->
            <label
              for="photo"
              class="absolute bottom-0 right-0 bg-white p-2 rounded-full border shadow cursor-pointer"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-5 h-5 text-gray-700"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.89 18.823a4.5 4.5 0 01-1.591 1.033l-4.02 1.608a.75.75 0 01-.98-.98l1.608-4.02a4.5 4.5 0 011.033-1.591L16.862 3.487z"
                />
              </svg>
            </label>
            <!-- Input File (Hidden) -->
            <input
              type="file"
              id="photo"
              accept="image/*"
              @change="handleImageChange"
              class="hidden"
            />
          </div>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ form.name }}
        </h2>
        <div class="flex-1 grid grid-cols-5 gap-4">
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
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-red-300 focus:border-red-500 text-sm"
              disabled
            />
          </div>

          <div class="col-span-2">
            <label
              for="position"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Posisi Kandidat
            </label>
            <input
              disabled
              type="text"
              id="position"
              v-model="form.position"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-red-300 focus:border-red-500 text-sm"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
