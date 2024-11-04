import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useUserVotingStore = defineStore('userVoting', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        userVotings: [],
        userVoting: null,
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
        groupId: '',
        userId: '',
    }),
    actions: {
        openForm(newAction, userVoting) {
            this.modalAction.action = newAction
            this.userVoting = userVoting
        },
        async getUserVotings() {
            try {
                const url = `${this.apiUrl}/v1/user_votings?page=${this.current}&perPage=${this.perPage}&description=${this.searchQuery}&groupId=${this.groupId}&userId=${this.userId}`;
                const res = await axios.get(url);
                
                const userVotingsDataList = res.data.data.list
                this.userVotings = userVotingsDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async getUserVotingById(id) {
            try {
                const url = `${this.apiUrl}/v1/user_votings/${id}`;
                const res = await axios.get(url);

                const userVoting = res.data.data
                this.userVoting = userVoting;
                
                return userVoting;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async changePage(newPage) {
            this.current = newPage;
            await this.getUserVotings(); 
        },
        async searchUserVotings(query) {
            this.searchQuery = query;
            this.current = 1; 
            await this.getUserVotings(); 
        },
        async addUserVotings(userVotings) {
            try {
                const url = `${this.apiUrl}/v1/user_votings`;
                const res = await axios.post(url, userVotings);

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
                this.getUserVotings();
            }
        },
        async deleteUserVoting(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/user_votings/${id}`);
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
                this.getUserVotings();
            }
        },
        async updateUserVoting(userVotings) {
            try {
                const url = `${this.apiUrl}/v1/user_votings/${userVotings.id}`;
                await axios.put(url, userVotings);
                
                this.response = {
                    status: '200',
                };
            } catch (error) {
                this.response = {
                    status: error.status,
                    message: error.message,
                };
            }
        },
    },
    getters: {
        message(state) {
            return state.modalAction.action;
        },
    },
})