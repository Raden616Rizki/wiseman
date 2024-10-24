import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useVotingStore = defineStore('voting', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        votings: [],
        voting: null,
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
        limitTime: '',
    }),
    actions: {
        openForm(newAction, user) {
            this.modalAction.action = newAction
            this.user = user
        },
        async getVotings() {
            try {
                const url = `${this.apiUrl}/v1/votings?page=${this.current}&perPage=${this.perPage}&description=${this.searchQuery}&groupId=${this.groupId}&limitTime=${this.limitTime}`;
                const res = await axios.get(url);
                
                const votingsDataList = res.data.data.list
                this.votings = votingsDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async getVotingById(id) {
            try {
                const url = `${this.apiUrl}/v1/votings/${id}`;
                const res = await axios.get(url);

                const voting = res.data.data
                this.voting = voting;
                
                return voting;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async changePage(newPage) {
            this.current = newPage;
            await this.getVotings(); 
        },
        async searchVotings(query) {
            this.searchQuery = query;
            this.current = 1; 
            await this.getVotings(); 
        },
        async addVotings(votings) {
            try {
                const url = `${this.apiUrl}/v1/votings`;
                const res = await axios.post(url, votings);

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
                this.getVotings();
            }
        },
        async deleteVoting(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/votings/${id}`);
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
                this.getVotings();
            }
        },
        async updateVoting(votings) {
            try {
                const url = `${this.apiUrl}/v1/votings/${votings.id}`;
                await axios.put(url, votings);
                
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