<template>
  <Layout>
    <BRow class="justify-content-center">
      <BCol md="8" lg="6" xl="5">
        <BCard no-body class="overflow-hidden ws-form">
          <BCardBody class="pt-0">
            <BForm class="p-4">
              <div>
                <BRow>
                  <h5 class="form-title my-4">Login</h5>
                </BRow>
              </div>
              <BFormGroup class="mb-3" id="input-group-1" label="Email" label-for="input-1">
                <BFormInput id="input-1" v-model="formModel.email" type="text" placeholder="Enter email" required autocomplete="current-email"></BFormInput>
              </BFormGroup>

              <BFormGroup class="mb-3" id="input-group-2" label="Password" label-for="input-2">
                <BFormInput id="input-2" v-model="formModel.password" type="password" placeholder="Enter password" required autocomplete="current-password">
                </BFormInput>
              </BFormGroup>
              <div class="mt-4 d-grid">
                <BButton type="submit" @click="login" variant="primary" class="submit-button" :disabled="!isFormValid">Login</BButton>
              </div>
              <div class="mt-3 text-center">
                <p>
                  Don't have an account?
                  <router-link to="/register">Register</router-link>
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
import { reactive, computed } from "vue";
import { useAuthStore } from "@/state/pinia";
import { useRouter } from "vue-router";

import { useProgress } from "@/helpers/progress"; // Import custom progress function
const { startProgress, finishProgress, failProgress } = useProgress();

import { showSuccessToast, showErrorToast } from "@/helpers/alert.js";

const router = useRouter();
const authStore = useAuthStore();

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
  startProgress();
  try {
    await authStore.login(formModel);
    if (statusCode.value != 200) {
      failProgress()
      // showErrorToast("Login failed", errorMessage.value);
      showErrorToast("Login failed", "Email or password is incorrect");
    } else {
      finishProgress();
      showSuccessToast("User Logged in !");
      router.push("/");
    }
  } catch (error) {
    console.error(error);
    failProgress()
    // showErrorToast("Login failed", errorMessage.value);
    showErrorToast("Login failed", "Something went wrong!");
  }
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
  background-color: #00ADB5;
  font-weight: bold;
}

.ws-form {
  background-color: #303841;
  color: #EEEEEE;
}
</style>
