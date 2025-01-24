<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: "martin@test.com",
  password: "11111111",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <div class="flex min-h-screen">
      <div
        class="w-full lg:w-1/2 p-6 flex items-center justify-center bg-white"
      >
        <form @submit.prevent="submit" class="w-full max-w-sm">
          <div class="flex items-center justify-center mb-2">
            <img src="/img/bag.png" alt="my icon" width="50" />
            <span class="text-2xl font-bold text-grey-600"> SIMS WEB APP</span>
          </div>

          <div class="text-center text-2xl font-semibold mb-6">
            Masuk atau buat akun untuk memulai
          </div>

          <div>
            <InputLabel for="email" value="Email" />

            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full"
              v-model="form.email"
              required
              autofocus
              autocomplete="username"
            />

            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
              id="password"
              type="password"
              class="mt-1 block w-full"
              v-model="form.password"
              required
              autocomplete="current-password"
            />

            <InputError class="mt-2" :message="form.errors.password" />
          </div>

          <div class="flex items-center justify-end mt-4">
            <Link
              :href="route('register')"
              class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Create an account
            </Link>
            <PrimaryButton
              class="ml-4 bg-red-500"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Log in
            </PrimaryButton>
          </div>
        </form>
      </div>

      <!-- Gambar (Right Side) -->
      <div
        class="hidden lg:block w-1/2 bg-cover bg-center"
        style="background-image: url('/img/bg98699.png')"
      ></div>
    </div>
  </GuestLayout>
</template>

<style scoped>
.bg-cover {
  background-image: url("/img/bg98699.png");
  background-size: cover;
  background-position: center;
}
</style>