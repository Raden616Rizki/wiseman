import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useArchiveStore = defineStore('archive', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        archives: [],
        archive: null,
        response: {
            status: null,
            message: null,
            error: []
        },
        modalAction: {
            'action': "",
            'modal_title': "",
            'modal_button': ""
        },
        totalData: 0,
        current: 1,
        perPage: 5,
        groupId: '',
        searchQuery: '',
    }),
    actions: {
        openForm(newAction, archive) {
            this.modalAction.action = newAction
            this.archive = archive
        },
        async getArchives() {
            try {
                const url = `${this.apiUrl}/v1/archives?page=${this.current}&perPage=${this.perPage}&name=${this.searchQuery}&groupId=${this.groupId}`;
                const res = await axios.get(url);

                const archivesDataList = res.data.data.list
                this.archives = archivesDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async getArchiveById(id) {
            try {
                const url = `${this.apiUrl}/v1/archives/${id}`;
                const res = await axios.get(url);

                const archive = res.data.data
                this.archive = archive;
                
                return archive;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async changePage(newPage) {
            this.current = newPage;
            await this.getArchives(); 
        },
        async searchArchives(query) {
            this.searchQuery = query;
            this.current = 1; 
            await this.getArchives(); 
        },
        async addArchives(archives) {
            try {
                const url = `${this.apiUrl}/v1/archives`
                const res = await axios.post(url, archives);

                this.response = {
                    status: res.status,
                    message: res.data.message
                };

                const archive = res.data.data;
                
                return archive;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                    error: error.response.data.errors,
                };
            } finally {
                this.getArchives();
            }
        },
        async deleteArchive(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/archives/${id}`);
                this.response = {
                    status: '200',
                };
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                    error: error.response.data.errors,
                };
            } finally {
                this.getArchives();
            }
        },
        async updateArchive(archive) {
            try {
                await axios.put(`${this.apiUrl}/v1/archives/${archive.id}`, archive);
                this.response = {
                    status: '200',
                };
            } catch (error) {
                this.response = {
                    status: error.status,
                    message: error.message,
                };
            }
        }
    },
    getters: {
        message(state) {
            return state.modalAction.action;
        },
    },
})