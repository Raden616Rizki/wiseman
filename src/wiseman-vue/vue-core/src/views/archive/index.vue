<template>
    <Layout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <input v-model="searchByName" type="text" class="form-control input-archive ms-0"
                placeholder="Cari arsip..." @keydown.enter="handleFilterByName" style="margin-left: 100px;">
            <i class="bx bx-plus memo-bold-font" style="font-size: 24px; cursor: pointer;"
                @click="openArchiveFormModal('create')"></i>
        </div>
        <div class="card main-bg py-3 px-4" style="min-height: 440px;"
            @contextmenu.prevent="openArchiveFormModal('create')">
            <div class="palette-3 py-1 px-3 rounded mb-4 d-flex flex-wrap">
                <p class="archive-path mb-0" @click="changePath('home')">Home</p>
                <div v-for="(path, index) in pathArchive" :key="index" class="d-flex">
                    <p class="text-white mb-0 mx-2">></p>
                    <p class="archive-path mb-0" @click="changePath(path, index)"> {{ path.name }} </p>
                </div>
            </div>
            <div v-if="archives.length != 0" class="d-flex flex-wrap" style="padding-left: 13.3px;">
                <div v-for="archiveData in archives" :key="archiveData.id">
                    <div v-if="archiveData.file" class="file me-4 my-1 d-flex flex-column align-items-center"
                        @contextmenu.prevent="showContextMenu($event, archiveData)">
                        <img :src="archiveData.file || fileIcon" class="file-icon" alt="file-icon"
                            @error="onImageError">
                        <p class="file-name" v-b-tooltip.hover
                            :title="archiveData.name + '.' + archiveData.file.split('.').pop()">{{ archiveData.name
                            }}.{{ archiveData.file.split('.').pop() }}</p>
                    </div>
                    <div v-else class="folder me-4 my-1 flex-column align-items-center"
                        @contextmenu.prevent="showContextMenu($event, archiveData)" @click="changePath(archiveData)">
                        <img :src="folderIcon" class="folder-icon" alt="folder-icon">
                        <p class="folder-name" v-b-tooltip.hover :title="archiveData.name">{{ archiveData.name }}</p>
                    </div>
                </div>
            </div>
            <div v-else class="pt-3 d-flex justify-content-center align-items-center">
                <img :src="emptyImage" alt="No Data">
            </div>
            <div v-if="isContextMenuVisible" class="context-menu" :style="contextMenuStyle">
                <ul>
                    <li v-if="archive.file" @click="copyFile()"
                        class="d-flex justify-content-between align-items-center">
                        Salin
                        <i class="bx bx-copy"></i>
                    </li>
                    <li v-if="archive.file" @click="downloadFile(archive.file)"
                        class="d-flex justify-content-between align-items-center">
                        Unduh
                        <i class="bx bx-download"></i>
                    </li>
                    <li @click="openListFolderModal('home')" class="d-flex justify-content-between align-items-center">
                        Pindah
                        <i class="bx bx-move"></i>
                    </li>
                    <li @click="openArchiveFormModal('update')"
                        class="d-flex justify-content-between align-items-center">
                        Ganti Nama
                        <i class="bx bx-edit"></i>
                    </li>
                    <li @click="deleteArchive()" class="d-flex justify-content-between align-items-center">
                        Hapus
                        <i class="bx bx-trash"></i>
                    </li>
                </ul>
            </div>
        </div>

        <!-- ========== Archive Form Modal ========== -->
        <BModal v-model="isArchiveFormOpen" id="modal-standard" :title="archiveFormTitle + ' Archive'"
            title-class="font-18" :ok-title="archiveFormTitle" @ok="saveArchive" @hide.prevent
            @cancel="isArchiveFormOpen = false" @close="isArchiveFormOpen = false">
            <BRow>
                <BCol cols="12" class="mt-1">
                    <BForm class="form-horizontal" role="form">
                        <BRow class="mb-3" v-if="uploadType === 'File'">
                            <BCol md="10" class="d-flex align-items-center">
                                <BFormRadioGroup v-model="uploadType" :options="uploadOptions"
                                    name="uploadTypeOptions" />
                            </BCol>
                        </BRow>
                        <BRow class="mb-3" v-if="uploadType === 'File'">
                            <label class="col-md-2 col-form-label" for="form-file-archive">File</label>
                            <BCol md="10">
                                <input class="form-control" type="file" :class="{
                                    'is-invalid': !!(archiveErrorList && archiveErrorList.file),
                                }" id="form-file-archive" placeholder="Masukkan file" @change="handleFileChange" />
                                <template v-if="!!(archiveErrorList && archiveErrorList.file)">
                                    <div class="invalid-feedback" v-for="(err, index) in archiveErrorList.file"
                                        :key="index">
                                        <span>{{ err }}</span>
                                    </div>
                                </template>
                            </BCol>
                        </BRow>
                        <BRow class="mb-1">
                            <label class="col-md-2 col-form-label" for="form-name-archive">Nama</label>
                            <BCol md="10">
                                <input class="form-control" :class="{
                                    'is-invalid': !!(archiveErrorList && archiveErrorList.name),
                                }" id="form-name-archive" placeholder="Masukkan nama file"
                                    v-model="archiveForm.name" />
                                <template v-if="!!(archiveErrorList && archiveErrorList.name)">
                                    <div class="invalid-feedback" v-for="(err, index) in archiveErrorList.name"
                                        :key="index">
                                        <span>{{ err }}</span>
                                    </div>
                                </template>
                            </BCol>
                        </BRow>
                    </BForm>
                </BCol>
            </BRow>
        </BModal>

        <!-- ========== List Folder Modal ========== -->
        <BModal v-model="isListFolderOpen" id="modal-standard" title="Daftar Folder" title-class="font-18" ok-title="ok"
            @ok="saveArchive" @hide.prevent @cancel="isListFolderOpen = false" @close="isListFolderOpen = false"
            hide-footer>
            <div v-if="isDone" class="px-2">
                <BRow class="mt-0">
                    <div class="palette-3 rounded text-white me-2 mt-2 p-2 d-flex justify-content-start"
                        style="cursor: pointer;" @click="moveArchive('home')">
                        <p class="mb-0">Home</p>
                    </div>
                </BRow>
                <FolderItem v-for="folder in listFolder" :key="folder.id" :folder="folder" :level="0"
                    @move="moveArchive" />
            </div>
        </BModal>
    </Layout>
</template>

<script setup>
import Layout from "../../layouts/main";
import FolderItem from "./folder-item.vue";
import { ref, onMounted, reactive, computed, watch } from "vue";
import { useArchiveStore } from "@/state/pinia";
import { useProgress } from "@/helpers/progress";
import folderIcon from "@/assets/images/folder-icon.svg";
import fileIcon from "@/assets/images/file-icon.svg";
import emptyImage from "@/assets/images/empty-icon.svg";
import {
    showSuccessToast,
    showErrorToast,
    showDeleteConfirmationDialog,
} from "@/helpers/alert.js";
import { useRoute } from 'vue-router';

const route = useRoute();
const groupId = ref(route.params.id);
const isDone = ref(false);

watch(() => route.params.id, async (newVal) => {
    groupId.value = newVal;

    if (!groupId.value) {
        groupId.value = '';
    }
    archiveStore.groupId = groupId.value;

    await getArchives();
});

const { startProgress, finishProgress, failProgress } = useProgress();

const archiveStore = useArchiveStore();
const searchByName = ref('');
const archives = ref([]);
const archive = ref({});
archiveStore.groupId = groupId.value;

const archiveStatusCode = computed(() => archiveStore.response.status);
const archiveErrorList = computed(() => archiveStore.response?.error || {});
const archiveErrorMessage = computed(() => archiveStore.response?.message || "");

const isArchiveFormOpen = ref(false);
const isListFolderOpen = ref(false);
const archiveFormTitle = ref('Tambah');
const listFolder = ref([]);
// const clicked = ref(false);

const archiveForm = reactive({
    id: "",
    file: null,
    name: "",
    group_id: groupId.value,
    parent_id: "",
});

const isContextMenuVisible = ref(false);
const contextMenuStyle = ref({
    top: '0px',
    left: '0px'
});

const uploadType = ref('File');
const uploadOptions = [
    { text: 'File', value: 'File' },
    { text: 'Folder', value: 'Folder' }
];

const pathArchive = ref([]);

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

const showContextMenu = (event, archiveData) => {
    contextMenuStyle.value = {
        top: `${event.clientY - 90}px`,
        left: `${event.clientX - 280}px`
    };

    archive.value = archiveData;
    isContextMenuVisible.value = true;
}

const hideContextMenu = () => {
    isContextMenuVisible.value = false;
}

const openArchiveFormModal = async (method) => {
    if (method == 'update') {
        isContextMenuVisible.value = false;
    }
    
    if (!isContextMenuVisible.value) {
        isArchiveFormOpen.value = true;
        if (method == 'update') {
            uploadType.value = 'Folder';

            archiveForm.id = archive.value.id;
            archiveForm.name = archive.value.name;
            archiveForm.group_id = archive.value.group_id;
            archiveForm.parent_id = archive.value.parent_id;

            archiveFormTitle.value = 'Perbarui';
        } else {
            uploadType.value = 'File';

            archiveForm.id = "";
            archiveForm.file = null;
            archiveForm.name = "";
            archiveForm.group_id = groupId.value || "";
            archiveForm.parent_id = archiveStore.parentId || "home";

            archiveFormTitle.value = 'Tambah';
        }
    }
}

const openListFolderModal = async (id) => {
    isDone.value = false;
    startProgress();
    isListFolderOpen.value = true;
    listFolder.value = await archiveStore.getListFolder(id);

    listFolder.value = await fetchSubFolders(listFolder.value);

    finishProgress();
    isDone.value = true;
}

const fetchSubFolders = async (folders) => {
    for (const folder of folders) {
        const subFolder = await archiveStore.getListFolder(folder.id);
        if (subFolder && subFolder.length > 0) {
            folder.subFolders = subFolder;
            await fetchSubFolders(subFolder);
        }
    }

    return folders;
};

const saveArchive = async () => {
    startProgress();
    try {
        if (archiveForm.id) {
            await archiveStore.updateArchive(archiveForm);
            if (archiveStatusCode.value != 200) {
                failProgress();
                showErrorToast("Gagal memperbarui arsip", archiveErrorMessage);
            } else {
                isArchiveFormOpen.value = false;
                finishProgress();
                showSuccessToast("Berhasil memperbarui arsip!");
            }
        } else {
            await archiveStore.addArchives(archiveForm);
            if (archiveStatusCode.value != 200) {
                failProgress();
                showErrorToast("Gagal menambahkan arsip", archiveErrorMessage);
            } else {
                isArchiveFormOpen.value = false;

                finishProgress();
                showSuccessToast("Berhasil menambahkan arsip!");
            }
        }
    } catch (error) {
        console.error(error);
        showErrorToast("Gagal menyimpan arsip", archiveErrorMessage);
        failProgress();
    }

    await getArchives();
}

const deleteArchive = async () => {
    const confirmed = await showDeleteConfirmationDialog();

    if (confirmed) {
        try {
            await archiveStore.deleteArchive(archive.value.id);
            await getArchives();

            showSuccessToast("berhasil menghapus arsip");
        } catch (error) {
            console.error(error);
            showErrorToast("Gagal menghapus arsip");
        }
    }
}

const moveArchive = async (newParentId) => {
    archiveForm.id = archive.value.id;
    archiveForm.name = archive.value.name;
    archiveForm.group_id = archive.value.group_id;
    archiveForm.parent_id = newParentId;

    await saveArchive();
    isListFolderOpen.value = false;
}

const onImageError = (event) => {
    event.target.src = fileIcon;
};

const downloadFile = (fileUrl) => {
    if (!fileUrl) return;

    const link = document.createElement("a");
    link.href = fileUrl;
    link.target = "_blank";
    link.download = fileUrl.split("/").pop();

    document.body.appendChild(link);
    link.click();

    document.body.removeChild(link);
};

const copyFile = async () => {
    const archiveId = archive.value.id;

    try {
        await archiveStore.copyArchive(archiveId);
        await getArchives();
    } catch (error) {
        console.error(error);
    }
}

const handleFileChange = (event) => {
    const file = event.target.files[0];
    const fileName = file.name.split('.')[0];

    if (file) {
        const reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = () => {
            archiveForm.file = reader.result;
            archiveForm.name = fileName;
        };

        reader.onerror = (error) => {
            console.error("Gagal merubah file ke base64:", error);
            archiveForm.file = null;
        };
    } else {
        archiveForm.file = null;
    }
}

const changePath = async (folderData, index) => {
    // clicked.value = true;

    if (folderData === 'home') {
        archiveStore.parentId = 'home';
        await getArchives();
        pathArchive.value = [];
    } else if (index >= 0) {
        archiveStore.parentId = folderData.id;
        await getArchives();
        pathArchive.value = pathArchive.value.slice(0, index + 1);
    } else {
        archiveStore.parentId = folderData.id;
        await getArchives();
        pathArchive.value.push(folderData);
    }
}

onMounted(async () => {
    archiveStore.parentId = 'home';
    await getArchives();
})

document.addEventListener('click', hideContextMenu);

</script>

<style scoped>
.archive-path {
    color: white;
    cursor: pointer;
}

.context-menu {
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    width: 120px;
}

.context-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.context-menu li {
    padding: 5px;
    cursor: pointer;
}

.context-menu i {
    font-size: 16px;
    cursor: pointer;
    color: #067e82;
    font-weight: bold;
}

.context-menu li:hover {
    background-color: #f0f0f0;
}

.file {
    cursor: pointer;
    width: 56px;
}

.file-icon {
    width: 56px;
    height: 56px;
}

.file-name {
    margin-top: 9px;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
    display: block;
}

.folder {
    width: 64px;
    cursor: pointer;
}

.folder-icon {
    width: 64px;
    height: 64px;
}

.folder-name {
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

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

.palette-3 {
    /* background-color: #00ADB5; */
    background-color: #067e82;
}
</style>