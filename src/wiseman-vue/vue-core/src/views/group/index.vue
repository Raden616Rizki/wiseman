<template>
    <Layout>
        <div>
            <input v-model="searchByName" type="text" class="form-control input-group ms-0 mb-4" placeholder="Cari group..." @keydown.enter="handleFilterByName" style="margin-left: 100px;">
        </div>
        <div class="card main-bg py-3 px-4" style="min-height: 440px;">
            <div v-for="group in groups" :key="group.id">
                <div class="p-2 bg-white d-flex justify-content-between mb-3 align-items-center card-group">
                    <div>
                        <p class="mb-0 ms-2 group-text"> {{ group.name }} </p>
                        <p class="mb-0 ms-2"> {{ group.description }} </p>
                    </div>
                    <button class="btn btn-sm palette-3 text-white join-button " >
                        Join
                    </button>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../layouts/main";
import { ref, onMounted } from "vue";
import { useGroupStore } from "@/state/pinia";
import { useProgress } from "@/helpers/progress";

const { startProgress, finishProgress, failProgress } = useProgress();

const groupStore = useGroupStore();
const searchByName = ref('');
const groups = ref([]);

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

onMounted(async () => {
    await getGroups();
})

</script>

<style scoped>
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