import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useEnrollmentStore = defineStore('enrollment', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        enrollments: [],
        enrollment: null,
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
        openForm(newAction, enrollment) {
            this.modalAction.action = newAction
            this.enrollment = enrollment
        },
        async getEnrollments() {
            try {
                const url = `${this.apiUrl}/v1/enrollments?page=${this.current}&perPage=${this.perPage}&name=${this.searchQuery}&groupId=${this.groupId}`;
                const res = await axios.get(url);

                const enrollmentsDataList = res.data.data.list
                this.enrollments = enrollmentsDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async getEnrollmentById(id) {
            try {
                const url = `${this.apiUrl}/v1/enrollments/${id}`;
                const res = await axios.get(url);

                const enrollment = res.data.data
                this.enrollment = enrollment;
                
                return enrollment;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            } 
        },
        async changePage(newPage) {
            this.current = newPage;
            await this.getEnrollments(); 
        },
        async searchEnrollments(query) {
            this.searchQuery = query;
            this.current = 1; 
            await this.getEnrollments(); 
        },
        async addEnrollments(enrollments) {
            try {
                const url = `${this.apiUrl}/v1/enrollments`
                const res = await axios.post(url, enrollments);

                this.response = {
                    status: res.status,
                    message: res.data.message
                };

                const enrollment = res.data.data;
                
                return enrollment;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                    error: error.response.data.errors,
                };
            } finally {
                this.getEnrollments();
            }
        },
        async deleteEnrollment(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/enrollments/${id}`);
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
                this.getEnrollments();
            }
        },
        async updateEnrollment(enrollment) {
            try {
                await axios.put(`${this.apiUrl}/v1/enrollments/${enrollment.id}`, enrollment);
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