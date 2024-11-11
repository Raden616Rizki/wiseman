<template>
  <Layout>
    <BRow class="justify-content-center align-items-center">
      <BCol xl="5">
        <BCard no-body class="overflow-hidden ws-form mt-5 mt-md-0">
          <BCardBody class="pt-0 mt-5">
            <BForm class="p-4">
              <div>
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="form-title my-4">Masuk</h5>
                  <img :src="wisemanIcon" alt="Wiseman Logo" style="width: 10%;">
                </div>
              </div>
              <BFormGroup class="mb-3" id="input-group-1" label="Email" label-for="input-1">
                <BFormInput id="input-1" v-model="formModel.email" type="text" placeholder="Masukkan email" required
                  autocomplete="current-email"></BFormInput>
                <div v-if="emailError" class="text-danger mt-1">{{ emailError }}</div>
              </BFormGroup>
              <div class="mb-3">
                <label class="ws-label" for="password">Password</label>
                <div class="input-group">
                  <input :type="showPassword ? 'text' : 'password'" v-model="formModel.password" class="form-control"
                    id="loginPassword" placeholder="Masukkan Password" required autocomplete="current-password" />
                  <span class="input-group-text" @click="togglePasswordVisibility" style="cursor: pointer;">
                    <i :class="showPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                  </span>
                </div>
                <div v-if="passwordError" class="text-danger mb-2">{{ passwordError }}</div>
              </div>

              <div class="mt-4 d-grid">
                <BButton type="submit" @click="login" variant="info" class="submit-button" :disabled="!isFormValid">
                  Masuk</BButton>
              </div>
              <div class="mt-4 text-center">
                <p>
                  <router-link to="/forgot-password">Lupa Password?</router-link>
                </p>
              </div>
              <div class="mt-4 text-center">
                <p>
                  Belum punya akun?
                  <router-link to="/register">Daftar</router-link>
                </p>
              </div>
            </BForm>
          </BCardBody>
        </BCard>
      </BCol>
    </BRow>
  </Layout>
</template>


<script setup>
import Layout from "../../layouts/auth";
import { reactive, computed, ref } from "vue";
import { useAuthStore } from "@/state/pinia";
import { useRouter } from "vue-router";

import { useProgress } from "@/helpers/progress"; // Import custom progress function
const { startProgress, finishProgress, failProgress } = useProgress();

import { showSuccessToast, showErrorToast } from "@/helpers/alert.js";
import wisemanIcon from "@/assets/images/wiseman-icon-1.svg";

const router = useRouter();
const authStore = useAuthStore();
const showPassword = ref(false);
const emailError = ref('');
const passwordError = ref('');

const formModel = reactive({
  email: "",
  password: "",
});

const isFormValid = computed(() => {
  return formModel.email && formModel.password;
});

const statusCode = computed(() => authStore.response.status);
// const errorList = computed(() => authStore.response?.list || {});
// const errorMessage = computed(() => authStore.response?.message || "");
const login = async () => {

  if (!validateEmail(formModel.email)) {
    emailError.value = 'Masukkan alamat email yang valid';
    return;
  } else {
    emailError.value = null;
  }

  if (!validatePassword()) {
    passwordError.value = "Password harus terdiri dari minimal 6 karakter";
    return;
  } else {
    passwordError.value = null;
  }

  startProgress();
  try {
    await authStore.login(formModel);
    if (statusCode.value != 200) {
      failProgress()
      // showErrorToast("Login failed", errorMessage.value);
      showErrorToast("Gagal Masuk", "Email atau Password tidak dikenali");
    } else {
      finishProgress();
      showSuccessToast("Berhasil Login!");
      router.push("/");
    }
  } catch (error) {
    console.error(error);
    failProgress()
    // showErrorToast("Login failed", errorMessage.value);
    showErrorToast("Gagal Masuk", "Terjadi kesalahan!");
  }
};

const validateEmail = (email) => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
};

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const validatePassword = () => {
  return formModel.password.length >= 6;
};
</script>
<style>
.form-title {
  font-weight: bold;
}

.palette-3 {
  background-color: #303841;
}

.submit-button {
  /* background-color: #00ADB5; */
  font-weight: bold;
}

.ws-form {
  background-color: #303841;
  color: #EEEEEE;
}
</style>
