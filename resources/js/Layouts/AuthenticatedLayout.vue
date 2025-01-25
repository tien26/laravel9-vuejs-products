<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const isSidebarOpen = ref(true);

const logout = async () => {
  try {
    await axios.post(
      "/api/logout",
      {},
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    localStorage.removeItem("token");
    localStorage.removeItem("user");

    window.location.href = "/login";
  } catch (error) {
    console.error("Logout error:", error);
    alert("Logout failed. Please try again.");
  }
};
</script>

<template>
  <div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <div
      :class="{
        'w-60': isSidebarOpen,
        'w-16': !isSidebarOpen,
      }"
      class="bg-red-500 text-white flex flex-col transition-all duration-300 ease-in-out"
    >
      <div class="h-16 flex items-center justify-between px-4">
        <span
          v-if="isSidebarOpen"
          class="flex items-center text-sm font-semibold"
        >
          <img class="m-2" src="/img/Handbag.png" alt="My Icon" width="20" />
          SIMS WEB APP
        </span>
        <button
          @click="isSidebarOpen = !isSidebarOpen"
          class="p-2 rounded-md hover:bg-red-600"
        >
          <svg
            v-if="isSidebarOpen"
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </button>
      </div>

      <!-- Sidebar Navigation -->
      <nav class="mt-4 flex flex-col space-y-2 text-sm">
        <!-- Dashboard Menu -->
        <Link
          :href="route('products')"
          :class="{
            'bg-white/50 text-white-500': route().current('products'),
            'hover:bg-red-600': !route().current('products'),
          }"
          class="flex items-center space-x-2 px-4 py-2 rounded transition"
        >
          <img src="/img/Package.png" alt="My Icon" width="20" />
          <span v-if="isSidebarOpen">Produk</span>
        </Link>

        <!-- Profile Menu -->
        <Link
          :href="route('profile.edit')"
          :class="{
            'bg-white/50 text-white-500': route().current('profile.edit'),
            'hover:bg-red-600': !route().current('profile.edit'),
          }"
          class="flex items-center space-x-2 px-4 py-2 rounded transition"
        >
          <img src="/img/User.png" alt="My Icon" width="20" />
          <span v-if="isSidebarOpen">Profil</span>
        </Link>

        <button
          @click="logout"
          :class="{
            'bg-white/50 text-white-500': route().current('logout'),
            'hover:bg-red-600': !route().current('logout'),
          }"
          class="flex items-center space-x-2 px-4 py-2 rounded transition"
        >
          <img src="/img/SignOut.png" alt="My Icon" width="20" />
          <span v-if="isSidebarOpen">Logout</span>
        </button>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1">
      <header v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
