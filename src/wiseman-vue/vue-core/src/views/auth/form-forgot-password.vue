<script>
import Layout from "../../layouts/auth";
import { ref, onMounted, computed } from 'vue';
import { useUserStore } from "../../state/pinia";
import { showSuccessToast, showErrorToast, showLoadingToast } from "@/helpers/alert.js";
import { useRouter } from "vue-router";
import wisemanIcon from "@/assets/images/wiseman-icon.svg";

export default {
    setup() {
        const email = ref("");
        const password = ref("");
        const confirmPassword = ref("");
        const tryingToReset = ref(false);
        const isValidId = ref(false);
        const showPassword = ref(false); // Control visibility of password
        const userStore = useUserStore();
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get("id");
        const userEmail = ref('');
        const router = useRouter();
        const passwordError = ref('');
        const confirmPasswordError = ref('');
        const statusCode = computed(() => userStore.response.status);

        if (userId) {
            isValidId.value = true;
        }

        const getUserById = async (userId) => {
            try {
                if (!userId) {
                    throw new Error('ID pengguna tidak valid.');
                }
                await userStore.getUserById(userId);
                const user = userStore.user;
                if (user && user.email) {
                    userEmail.value = user.email;
                } else {
                    userEmail.value = '';
                }
            } catch (err) {
                userEmail.value = '';
            }
        };

        onMounted(() => {
            if (userId) {
                getUserById(userId);
            }
        });

        const togglePasswordVisibility = () => {
            showPassword.value = !showPassword.value;
        };

        const validatePassword = () => {
            return password.value.length >= 6;
        };

        const validateConfirmPassword = () => {
            return password.value === confirmPassword.value;
        };

        const updatePassword = async () => {
            tryingToReset.value = true;
            passwordError.value = "";
            confirmPasswordError.value = "";

            if (!validatePassword()) {
                passwordError.value = "Password harus terdiri dari minimal 6 karakter";
                tryingToReset.value = false;
                return;
            }

            if (!validateConfirmPassword()) {
                confirmPasswordError.value = "Konfirmasi Password tidak cocok dengan Password";
                tryingToReset.value = false;
                return;
            }

            try {
                const loadingToast = await showLoadingToast();
                await userStore.updateUser({
                    id: userId,
                    email: userEmail.value,
                    password: password.value,
                });

                if (statusCode.value === 200) {
                    loadingToast.close()
                    showSuccessToast('Password Berhasil Diubah');
                    password.value = "";
                    confirmPassword.value = "";
                    setTimeout(() => {
                        router.push({ name: "login" });
                    }, 1500);
                } else {
                    passwordError.value = 'Terjadi kesalahan saat memperbarui pengguna';
                }
            } catch (err) {
                showErrorToast('Terjadi kesalahan saat mengubah password');
            } finally {
                tryingToReset.value = false;
            }
        };

        return { email, userEmail, password, confirmPassword, passwordError, confirmPasswordError, tryingToReset, updatePassword, togglePasswordVisibility, showPassword, wisemanIcon };
    },
    components: {
        Layout,
    },
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
                            <BAlert v-if="error" class="mb-4" variant="danger" dismissible>{{ error }}</BAlert>
                            <BForm @submit.prevent="updatePassword">
                                <div class="mb-3">
                                    <label class="ws-label" for="useremail">Email</label>
                                    <input type="email" v-model="userEmail" class="form-control" id="useremail"
                                        placeholder="Enter email" readonly />
                                </div>

                                <div class="mb-3">
                                    <label class="ws-label" for="password">Password</label>
                                    <div class="input-group">
                                        <input :type="showPassword ? 'text' : 'password'" v-model="password"
                                            class="form-control" id="password" placeholder="Enter new password" />
                                        <span class="input-group-text" @click="togglePasswordVisibility"
                                            style="cursor: pointer;">
                                            <i :class="showPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                        </span>
                                    </div>
                                    <div v-if="passwordError" class="text-danger mb-2">{{ passwordError }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="ws-label" for="confirmPassword">Confirm Password</label>
                                    <div class="input-group">
                                        <input :type="showPassword ? 'text' : 'password'" v-model="confirmPassword"
                                            class="form-control" id="confirmPassword"
                                            placeholder="Confirm new password" />
                                        <span class="input-group-text" @click="togglePasswordVisibility"
                                            style="cursor: pointer;">
                                            <i :class="showPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                        </span>
                                    </div>
                                    <div v-if="confirmPasswordError" class="text-danger mb-2">{{ confirmPasswordError }}
                                    </div>
                                </div>

                                <BRow class="mb-3 mb-0">
                                    <BCol cols="12" class="text-end">
                                        <BButton @click="updatePassword" variant="dark" class="w-md"
                                            :disabled="tryingToReset">
                                            Reset
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