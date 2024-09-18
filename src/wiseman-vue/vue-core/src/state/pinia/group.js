import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useGroupStore = defineStore('group', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        groups: [],
        group: null,
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
        perpage: 5,
        searchQuery: '',
    }),
    actions: {
        openForm(newAction, group) {
            this.modalAction.action = newAction
            this.group = group
        },
        async getGroups() {
            try {
                const url = `${this.apiUrl}/v1/groups?page=${this.current}&perPage=${this.perpage}&name=${this.searchQuery}`;
                const res = await axios.get(url);

                const groupsDataList = res.data.data.list
                this.groups = groupsDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async getGroupById(id) {
            try {
                const url = `${this.apiUrl}/v1/groups/${id}`;
                const res = await axios.get(url);

                const group = res.data.data
                this.group = group;
                
                return group;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async changePage(newPage) {
            this.current = newPage;
            await this.getGroups(); 
        },
        async searchGroups(query) {
            this.searchQuery = query;
            this.current = 1; 
            await this.getGroups(); 
        },
        async addGroups(groups) {
            try {
                const res = await axios.post(`${this.apiUrl}/v1/groups`, groups);
                this.response = {
                    status: res.status,
                    message: res.data.message
                };
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                    error: error.response.data.errors,
                };
            } finally {
                this.getGroups();
            }
        },
        async deleteGroup(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/groups/${id}`);
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
                this.getGroups();
            }
        },
        async updateGroup(group) {
            try {
                await axios.put(`${this.apiUrl}/v1/users/${group.id}`, group);
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