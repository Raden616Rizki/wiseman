<template>
    <BRow :style="{ opacity: 1 - level * 0.1 }" class="mt-0">
        <div :style="{ marginLeft: level * 20 + 'px' }" class="main-bg rounded text-white me-2 mt-2 p-2 d-flex justify-content-start" style="cursor: pointer;" @click="handleMove(folder.id)">
            <p class="mb-0">{{ folder.name }}</p>
        </div>

        <FolderItem v-for="subFolder in folder.subFolders || []" :key="subFolder.id" :folder="subFolder"
            :level="level + 1" @move="handleMove" />
    </BRow>
</template>

<script>
import FolderItem from "./folder-item.vue";

export default {
    name: "FolderItem",
    components: { FolderItem },
    props: {
        folder: {
            type: Object,
            required: true,
        },
        level: {
            type: Number,
            default: 0,
        },
    },
    methods: {
        handleMove(id) {
            this.$emit("move", id);
        },
    },
};
</script>

<style scoped>
.main-bg {
    background-color: #3A4750;
}
</style>