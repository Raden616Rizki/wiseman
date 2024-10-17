import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useGroupUserStore = defineStore('groupUser', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        groupUsers: [],
        groupUser: null,
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
        searchQuery: '',
    }),
    actions: {
        openForm(newAction, groupUser) {
            this.modalAction.action = newAction
            this.groupUser = groupUser
        },
        async getGroupUsers() {
            try {
                const url = `${this.apiUrl}/v1/group_users?page=${this.current}&perPage=${this.perPage}&name=${this.searchQuery}`;
                const res = await axios.get(url);

                const groupUsersDataList = res.data.data.list
                this.groupUsers = groupUsersDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async getGroupUserById(id) {
            try {
                const url = `${this.apiUrl}/v1/group_users/${id}`;
                const res = await axios.get(url);

                const groupUser = res.data.data
                this.groupUser = groupUser;
                
                return groupUser;
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
        async addGroupUsers(groupUsers) {
            try {
                const res = await axios.post(`${this.apiUrl}/v1/group_users`, groupUsers);
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
        async deleteGroupUser(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/group_users/${id}`);
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
        async updateGroup(groupUser) {
            try {
                await axios.put(`${this.apiUrl}/v1/group_users/${groupUser.id}`, groupUser);
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