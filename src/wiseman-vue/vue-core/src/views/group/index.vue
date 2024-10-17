<template>
    <Layout>
        <div>
            <input v-model="searchByName" type="text" class="form-control input-group shadow-sm ml-0 mb-4" placeholder="Cari group..." @keydown.enter="handleFilterByName" style="margin-left: 100px;">
        </div>
        <div class="card main-bg py-3 px-4">
            <div v-for="group in groups" :key="group.id">
                <div class="card p-2">
                    <p> {{ group.name }} </p>
                    <button class="btn btn-sm shadow-sm palette-3 text-white join-button " >
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
.input-group {
    border-radius: 4px;
    border: solid 1px black;
    width: 240px;
    cursor: pointer;
}

.join-button {
    width: 100px;
}

.main-bg {
  background-color: #3A4750;
}

.palette-3 {
  background-color: #00ADB5;
}

</style>