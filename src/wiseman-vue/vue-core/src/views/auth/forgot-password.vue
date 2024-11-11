<script setup>
import { ref, computed } from 'vue';
import { useUserStore } from "../../state/pinia";
import Layout from "../../layouts/auth";
import { showErrorToast, showAlertDialog } from "@/helpers/alert.js";
import Swal from 'sweetalert2';
import wisemanIcon from "@/assets/images/wiseman-icon.svg";
import { useProgress } from "@/helpers/progress"; // Import custom progress function
const { startProgress, finishProgress, failProgress } = useProgress();

const userStore = useUserStore();
const email = ref('');
const submitted = ref(false);
const emailError = ref(null);
const tryingToReset = ref(false);
const isResetError = ref(false);
const statusCode = computed(() => userStore.response.status);

const validateEmail = (email) => {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
};

// Ubah link ke endpoint reset password yang tepat
const link = `${window.location.origin}/forgot-password/form`; // Pastikan path ini sesuai dengan endpoint di API Anda

const tryToReset = async () => {
  submitted.value = true;
  emailError.value = null;
  tryingToReset.value = true;

  if (!email.value) {
    emailError.value = 'Email harus diisi.';
    tryingToReset.value = false;
    return;
  }

  if (!validateEmail(email.value)) {
    emailError.value = 'Masukkan alamat email yang valid.';
    tryingToReset.value = false;
    return;
  }

  startProgress();
  try {
    await userStore.forgotPassword({
      email: email.value,
      link: link
    });
    if (statusCode.value === 200) {
      showAlertDialog();
      isResetError.value = false;
      finishProgress();
    } else if (statusCode.value === 404) {
      showErrorToast("Gagal mengirim permintaan reset password", "Email Anda Tidak Terdaftar.");
      isResetError.value = true;
      failProgress();
    } else {
      let customMessage = "";
      if (userStore.response && typeof userStore.response === 'object') {
        const errorMessage = userStore.response.message || userStore.response.errors[Object.keys(userStore.response.errors)[0]][0];
        switch (errorMessage) {
          case "The email field is required.":
            customMessage = "Mohon isi email Anda untuk melanjutkan.";
            break;
          case "The email field must be a valid email address.":
            customMessage = "Mohon cek kembali alamat email Anda.";
            break;
          default:
            customMessage = errorMessage;
        }
      } else {
        customMessage = "Terjadi kesalahan yang tidak terduga.";
      }
      showErrorToast("Gagal mengirim permintaan reset password", customMessage);
      isResetError.value = true;
      failProgress();
    }
  } catch (error) {
    if (error.response) {
      const apiErrorResponse = error.response.data;
      if (apiErrorResponse.errors) {
        Swal.close();
        const firstErrorKey = Object.keys(apiErrorResponse.errors)[0];
        const firstErrorMessage = apiErrorResponse.errors[firstErrorKey][0];
        let customMessage = firstErrorMessage === "Email Tidak Ditemukan." ? "Email Anda Tidak Terdaftar." : firstErrorMessage;
        showErrorToast("Gagal mengirim permintaan reset password", customMessage);
      } else {
        Swal.close();
        showErrorToast("Terjadi kesalahan", "Terjadi kesalahan yang tidak terduga.");
      }
    }
    failProgress();
  } finally {
    tryingToReset.value = false;
  }
};
</script>

<template>
  <Layout>
    <BRow class="justify-content-center">
      <BCol md="8" lg="6" xl="5">
        <BCard no-body class="overflow-hidden">
          <div class="ws-form">
            <BRow>
              <BCol cols="7">
                <div class="text-primary p-4">
                  <h5 class="text-white">Reset Password</h5>
                </div>
              </BCol>
              <BCol cols="5" class="align-self-end">
                <img src="@/assets/images/profile-img.png" alt class="img-fluid" />
              </BCol>
            </BRow>
          </div>
          <BCardBody class="pt-0 palette-3">
            <div>
              <router-link to="/">
                <div class="avatar-md profile-user-wid mb-4">
                  <span class="avatar-title rounded-circle bg-light">
                    <img :src="wisemanIcon" alt height="34" />
                  </span>
                </div>
              </router-link>
            </div>
            <div class="p-2">
              <BForm @submit.prevent="tryToReset">
                <div class="mb-3">
                  <label for="useremail" class="ws-label">Email</label>
                  <input type="email" v-model="email" class="form-control" id="useremail" placeholder="Masukkan email"
                    :class="{ 'is-invalid': submitted && emailError }" />
                  <span v-if="submitted && emailError" class="invalid-feedback">{{ emailError }}</span>
                </div>
                <BRow class="mb-3 mb-0">
                  <BCol cols="12" class="text-end">
                    <BButton variant="dark" class="w-md" type="submit" :disabled="tryingToReset">
                      Kirim Email
                    </BButton>
                  </BCol>
                </BRow>
              </BForm>
            </div>
          </BCardBody>
        </BCard>
        <div class="mt-5 text-center">
          <p>
            Ingat kata sandi?
            <router-link to="/login" class="fw-medium text-primary">Masuk</router-link>
          </p>
        </div>
      </BCol>
    </BRow>
  </Layout>
</template>

<style scoped >
.palette-3 {
  background-color: #067e82;
}
.ws-form {
  background-color: #303841;
  color: #EEEEEE;
}
.ws-label {
  color: white;
  font-weight: bold;
}
</style>