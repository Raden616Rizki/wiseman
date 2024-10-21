import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useActivityStore = defineStore('activity', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        activities: [],
        activity: null,
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
        userId: '',
        groupId: '',
    }),
    actions: {
        openForm(newAction, user) {
            this.modalAction.action = newAction
            this.user = user
        },
        async getActivities() {
            try {
                const url = `${this.apiUrl}/v1/activities?page=${this.current}&perPage=${this.perPage}&description=${this.searchQuery}&userId=${this.userId}&groupId=${this.groupId}`;
                const res = await axios.get(url);

                const activitiesDataList = res.data.data.list
                this.activities = activitiesDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async getActivityById(id) {
            try {
                const url = `${this.apiUrl}/v1/activities/${id}`;
                const res = await axios.get(url);

                const activity = res.data.data
                this.activity = activity;
                
                return activity;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async changePage(newPage) {
            this.current = newPage;
            await this.getActivities(); 
        },
        async searchUsers(query) {
            this.searchQuery = query;
            this.current = 1; 
            await this.getActivities(); 
        },
        async addActivities(activities) {
            try {
                const url = `${this.apiUrl}/v1/activities`;
                const res = await axios.post(url, activities);

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
                this.getActivities();
            }
        },
        async deleteActivity(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/activities/${id}`);
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
                this.getActivities();
            }
        },
        async updateActivity(activities) {
            try {
                const url = `${this.apiUrl}/v1/activities/${activities.id}`;
                await axios.put(url, activities);
                
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