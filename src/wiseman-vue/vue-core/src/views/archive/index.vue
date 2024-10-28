<template>
    <Layout>
        <div>
            <input v-model="searchByName" type="text" class="form-control input-archive ms-0 mb-4" placeholder="Cari arsip..." @keydown.enter="handleFilterByName" style="margin-left: 100px;">
        </div>
        <div class="card main-bg py-3 px-4">
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../layouts/main";
import { ref, onMounted } from "vue";
import { useArchiveStore } from "@/state/pinia";
import { useProgress } from "@/helpers/progress";

const { startProgress, finishProgress, failProgress } = useProgress();

const archiveStore = useArchiveStore();
const searchByName = ref('');
const archives = ref([]);

const handleFilterByName = async () => {
    archiveStore.searchQuery = searchByName.value;
    await getArchives();
}

const getArchives = async () => {
    startProgress();
    await archiveStore.getArchives();

    if (archiveStore.archives) {
        archives.value = archiveStore.archives;
        finishProgress()
    } else {
        failProgress();
    }
}

onMounted(async () => {
    await getArchives();
})

</script>

<style scoped>

.input-archive {
    border-radius: 4px;
    width: 240px;
    cursor: pointer;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}

.main-bg {
  background-color: #3A4750;
  box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25);
}

</style>