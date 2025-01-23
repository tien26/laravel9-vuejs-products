<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <div
      :class="{
        'w-64': isSidebarOpen,
        'w-16': !isSidebarOpen,
      }"
      class="bg-red-500 text-white flex flex-col transition-all duration-300 ease-in-out"
    >
      <div class="h-16 flex items-center justify-between px-4">
        <span v-if="isSidebarOpen" class="text-lg font-semibold"> MyApp </span>
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

      <nav class="mt-4 flex flex-col space-y-2">
        <Link
          :href="route('dashboard')"
          class="flex items-center space-x-2 px-4 py-2 hover:bg-red-600 transition"
        >
          <svg
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
              d="M3 10h11M9 21v-4a3 3 0 00-3-3H5a3 3 0 00-3 3v4h14z"
            />
          </svg>
          <span v-if="isSidebarOpen">Dashboard</span>
        </Link>

        <Link
          :href="route('profile.edit')"
          class="flex items-center space-x-2 px-4 py-2 hover:bg-red-600 transition"
        >
          <svg
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
              d="M15 11a3 3 0 11-6 0 3 3 0 016 0zm4 8a8 8 0 10-8 8 8 8 0 008-8z"
            />
          </svg>
          <span v-if="isSidebarOpen">Profile</span>
        </Link>

        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="flex items-center space-x-2 px-4 py-2 hover:bg-red-600 transition"
        >
          <svg
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
              d="M15 12H3m12 0l-4-4m4 4l-4 4"
            />
          </svg>
          <span v-if="isSidebarOpen">Log Out</span>
        </Link>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1">
      <header class="bg-white shadow" v-if="$slots.header">
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
