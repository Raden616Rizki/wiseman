// src/stores/authStore.js
import {
  defineStore
} from 'pinia';
import {
  useGroupStore
} from './group';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    apiUrl: process.env.VUE_APP_APIURL,
    userLogin: {},
    csrfToken: '',
    bearerToken: '',
    response: {
      status: null,
      message: null,
      error: []
    },
  }),
  actions: {
    async login(credential) {
      try {
        const res = await axios.post(`${this.apiUrl}/v1/auth/login`, credential);
        this.response = {
          status: res.status,
          message: res.data.message
        };
        this.saveToken(res.data.data.access_token)
        await this.saveUser(res.data.data.user)
      } catch (error) {
        this.response = {
          status: error.response ?.status,
          message: error.message,
          error: error.response.data.errors,
        };
      }
    },
    async register(credential) {
      try {
        await axios.post(`${this.apiUrl}/v1/users`, credential);
        const res = await axios.post(`${this.apiUrl}/v1/auth/login`, credential);
        this.response = {
          status: res.status,
          message: res.data.message
        };
        this.saveToken(res.data.data.access_token)
        await this.saveUser(res.data.data.user)
      } catch (error) {
        this.response = {
          status: error.response ?.status,
          message: error.message,
          error: error.response.data.errors,
        };
      }
    },
    async logout() {
      await this.removeToken();
      this.userLogin = {};
    },
    async saveUserLogin() {
      try {
        const response = await axios.get('/v1/auth/profile');
        this.userLogin = response.data.data;
      } catch (error) {
        console.error('Failed to fetch user profile', error);
      }
    },
    async saveToken(token) {
      localStorage.setItem('token', token);
    },
    async removeToken() {
      localStorage.removeItem('token');
    },
    getToken() {
      return localStorage.getItem('token') || '';
    },
    async saveUser(user) {
      try {
        const detailGroups = await this.getGroupData(user.groupUsers);

        user.detailGroups = detailGroups;
        localStorage.setItem('user', JSON.stringify(user));
      } catch (error) {
        console.log(error);
      }
    },
    async removeUser() {
      localStorage.removeItem('user');
    },
    getUser() {
      return JSON.parse(localStorage.getItem('user') || '');
    },
    async getGroupData(groups) {
      const groupStore = useGroupStore();
      var detailGroups = [];

      for (let i = 0; i < groups.length; i++) {
        const group = await groupStore.getGroupById(groups[i].group_id);
        detailGroups.push(group);
      }
      return detailGroups;
    }
  }
});