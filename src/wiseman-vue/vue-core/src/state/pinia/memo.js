import {
    defineStore
} from "pinia";
import axios from 'axios';

export const useMemoStore = defineStore('memo', {
    state: () => ({
        apiUrl: process.env.VUE_APP_APIURL,
        memos: [],
        memo: null,
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
        openForm(newAction, memo) {
            this.modalAction.action = newAction
            this.memo = memo
        },
        async getmemos() {
            try {
                const url = `${this.apiUrl}/v1/memos?page=${this.current}&perPage=${this.perPage}&name=${this.searchQuery}&groupId=${this.groupId}`;
                const res = await axios.get(url);

                const memosDataList = res.data.data.list
                this.memos = memosDataList
                this.totalData = res.data.data.meta.total
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            }
        },
        async getMemoById(id) {
            try {
                const url = `${this.apiUrl}/v1/memos/${id}`;
                const res = await axios.get(url);

                const memo = res.data.data
                this.memo = memo;

                return memo;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                };
            }
        },
        async changePage(newPage) {
            this.current = newPage;
            await this.getMemos();
        },
        async searchMemos(query) {
            this.searchQuery = query;
            this.current = 1;
            await this.getMemos();
        },
        async addMemos(memos) {
            try {
                const url = `${this.apiUrl}/v1/memos`
                const res = await axios.post(url, memos);

                this.response = {
                    status: res.status,
                    message: res.data.message
                };

                const memo = res.data.data;

                return memo;
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                    error: error.response.data.errors,
                };
            }
        },
        async deleteMemo(id) {
            this.loading = true;
            try {
                await axios.delete(`${this.apiUrl}/v1/memos/${id}`);
                this.response = {
                    status: '200',
                };
            } catch (error) {
                this.response = {
                    status: error.response ?.status,
                    message: error.message,
                    error: error.response.data.errors,
                };
            }
        },
        async updateMemo(memo) {
            try {
                await axios.put(`${this.apiUrl}/v1/memos/${memo.id}`, memo);
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