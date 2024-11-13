<template>
    <Layout>
        <div>
            <input v-model="searchByName" type="text" class="form-control input-group ms-0 mb-4 w-100 w-30"
                placeholder="Cari group..." @keydown.enter="handleFilterByName" style="margin-left: 100px;">
        </div>
        <div v-if="groups.length != 0" class="card main-bg py-4 px-4" style="min-height: 440px; height: 500px; overflow-y: auto; padding-right: 10px">
            <div v-for="group in groups" :key="group.id">
                <div class="p-2 bg-white d-flex justify-content-between mb-3 align-items-center card-group">
                    <div>
                        <p class="mb-0 ms-2 group-text"> {{ group.name }} </p>
                        <p class="mb-0 ms-2"> {{ group.description }} </p>
                    </div>
                    <button class="btn btn-sm palette-3 text-white join-button d-none d-md-block"
                        @click="joinGroup(group)">
                        Masuk
                    </button>
                    <div class="d-block d-md-none" >
                        <i class="bx bx-right-arrow-circle mt-1 ms-3 me-2" @click="joinGroup(group)"
                            v-b-tooltip.hover :title="'Bergabung ke ' + group.name"
                            style="font-size: 20px; cursor: pointer; font-weight: bold;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="card main-bg py-4 px-4" style="min-height: 440px;">
            <div class="pt-5 d-flex justify-content-center align-items-center">
                <img :src="emptyImage" alt="No Data">
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../layouts/main";
import { ref, onMounted } from "vue";
import { useGroupStore, useEnrollmentStore, useAuthStore } from "@/state/pinia";
import { useProgress } from "@/helpers/progress";
import {
    showSuccessToast,
    showErrorToast,
} from "@/helpers/alert.js";
import emptyImage from "@/assets/images/empty-icon.svg";

const { startProgress, finishProgress, failProgress } = useProgress();

const groupStore = useGroupStore();
const searchByName = ref('');
const groups = ref([]);

const enrollmentStore = useEnrollmentStore();

const authStore = useAuthStore();
const user = authStore.getUser();
const userId = user.id;

const handleFilterByName = async () => {
    groupStore.searchQuery = searchByName.value;
    await getGroups();
}

const getGroups = async () => {
    startProgress();
    await groupStore.getGroups();

    if (groupStore.groups) {
        groups.value = groupStore.groups;
        finishProgress()
    } else {
        failProgress();
    }
}

const joinGroup = async (group) => {
    startProgress();
    try {
        const groupId = group.id;
        const enrollmentForm = {
            group_id: groupId,
            user_id: userId
        }
        await enrollmentStore.addEnrollments(enrollmentForm);
        showSuccessToast('Berhasil mendaftar ke Group ' + group.name, 'Mohon tunggu konfirmasi admin');
        finishProgress();
    } catch (error) {
        error.log(error);
        showErrorToast('Gagal mendaftar ke Group ' + group.name, 'Tunggu beberapa saat lagi');
        failProgress();
    }
}

onMounted(async () => {
    await getGroups();
})

</script>

<style scoped>
@media (min-width: 992px) {
    .w-30 {
        width: 30% !important;
    }
}

.card-group {
    border-radius: 4px;
}

.group-text {
    font-size: small;
    font-weight: bold;
}

.input-group {
    border-radius: 4px;
    width: 240px;
    cursor: pointer;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}

.join-button {
    font-size: small;
    font-weight: bold;
    width: 70px;
    box-shadow: 2px 4px 4px rgba(0, 0, 0, 0.25);
}

.main-bg {
    background-color: #3A4750;
    box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25);
}

.palette-3 {
    background-color: #00ADB5;
}
</style>