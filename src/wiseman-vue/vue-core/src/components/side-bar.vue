<script>
import simplebar from "simplebar-vue";
import SideNav from "./side-nav";
import { useAuthStore } from "@/state/pinia";
import { useRouter, useRoute } from "vue-router";
import ImageCropper from "@/components/widgets/cropper";
import { ref, computed, reactive, onMounted } from "vue";
import {
  useUserStore,
  useGroupStore,
  useGroupUserStore,
  useEnrollmentStore,
  useActivityStore
} from "@/state/pinia";
import {
  showSuccessToast,
  showErrorToast,
  showDeleteConfirmationDialog,
} from "@/helpers/alert.js";
import { useProgress } from "@/helpers/progress";
import defaultAvatar from "@/assets/images/users/user-dummy-img.jpg";
import { Date } from "core-js";

/**
 * Sidebar component
 */
export default {
  components: { simplebar, SideNav, ImageCropper },
  props: {
    isCondensed: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      required: true
    },
    width: {
      type: String,
      required: true
    },
    mode: {
      type: String,
      required: true
    }
  },
  setup() {
    const authStore = useAuthStore();
    const router = useRouter();
    const route = useRoute();
    const imageUrl = ref("");
    const croppedImageUrl = ref("");
    const isFormUserOpen = ref(false);
    const isFormGroupOpen = ref(false);
    const formGroupTitle = ref("Create");
    const groupId = ref(route.query.id);

    const user = ref(null);
    user.value = authStore.getUser();
    const userId = user.value.id;
    const isAdmin = ref(false);
    const isAdminStatus = ref([]);
    const groups = computed(() => {
      return user.value?.groupUsers.map(groupUser => ({
        groupUserId: groupUser.id,
        group: groupUser.group
      })) || [];
    });
    const group = ref("");
    const enrollments = ref([]);

    const isActivityFormOpen = ref(false);

    const formUser = reactive({
      id: "",
      name: "",
      email: "",
      password: "",
      photo: "",
      phone_number: ""
    });

    const formGroup = reactive({
      id: "",
      name: "",
      description: "",
    });

    const formActivity = reactive({
      group_id: "",
      user_id: "",
      description: "",
      start_time: "",
      end_time: "",
      is_priority: 0,
      is_finished: 0,
    });

    const userStore = useUserStore();

    const userStatusCode = computed(() => userStore.response.status);
    const userErrorList = computed(() => userStore.response?.error || {});
    const userErrorMessage = computed(() => userStore.response?.message || "");

    const groupStore = useGroupStore();

    const groupStatusCode = computed(() => groupStore.response.status);
    const groupErrorList = computed(() => groupStore.response?.error || {});
    const groupErrorMessage = computed(() => groupStore.response?.message || "");

    const groupUserStore = useGroupUserStore();

    const groupUserStatusCode = computed(() => groupUserStore.response.status);
    const groupUserErrorList = computed(() => groupUserStore.response?.error || {});
    const groupUserErrorMessage = computed(() => groupUserStore.response?.message || "");

    const enrollmentStore = useEnrollmentStore();

    const enrollmentStatusCode = computed(() => enrollmentStore.response.status);
    const enrollmentErrorList = computed(() => enrollmentStore.response?.error || {});
    const enrollmentErrorMessage = computed(() => enrollmentStore.response?.message || "");

    const activityStore = useActivityStore();

    const activityStatusCode = computed(() => activityStore.response.status);
    const activityErrorList = computed(() => activityStore.response?.error || {});
    const activityErrorMessage = computed(() => activityStore.response?.message || "");

    const { startProgress, finishProgress, failProgress } = useProgress();

    const date = ref('');

    const getAuthUser = async () => {
      try {
        const userId = authStore.getUser().id || '0';
        const fetchedUser = await userStore.getUserById(userId);
        authStore.saveUser(fetchedUser);
        user.value = authStore.getUser();
      } catch (error) {
        console.error('Error fetching user:', error);
      }
    };

    const getFormattedDate = (dateData) => {
      const year = dateData.getFullYear();
      const month = String(dateData.getMonth() + 1).padStart(2, '0');
      const day = String(dateData.getDate()).padStart(2, '0');

      return `${year}-${month}-${day}`;
    };

    const checkIsAdmin = (groupData) => {
      const isAdminValue = groupData.groupDetails.some(
        (detail) => detail.user.id === userId && detail.isAdmin === 1
      );

      return isAdminValue;
    };

    const checkIsAdminById = async (groupIdData) => {
      const groupData = await groupStore.getGroupById(groupIdData);
      const isAdminValue = checkIsAdmin(groupData);

      return isAdminValue;
    }

    onMounted(async () => {
      await getAuthUser();

      for (var i = 0; i < groups.value.length; i++) {
        const isAdmin = await checkIsAdminById(groups.value[i].group.id);
        isAdminStatus.value.push(isAdmin);
      }

      if (groupId.value) {
        group.value = await groupStore.getGroupById(groupId.value);

        isAdmin.value = checkIsAdmin(group.value);

        enrollmentStore.groupId = groupId.value
        await enrollmentStore.getEnrollments();
        enrollments.value = enrollmentStore.enrollments;

        const today = new Date();
        date.value = getFormattedDate(today);
      }
    });

    return {
      getAuthUser: getAuthUser,
      getFormattedDate: getFormattedDate,
      checkIsAdmin: checkIsAdmin,
      user: user,
      router: router,
      groups: groups,
      group: group,
      formUser: formUser,
      imageUrl: imageUrl,
      croppedImageUrl: croppedImageUrl,
      isFormUserOpen: isFormUserOpen,
      userStore: userStore,
      userStatusCode: userStatusCode,
      userErrorList: userErrorList,
      userErrorMessage: userErrorMessage,
      groupStore: groupStore,
      isFormGroupOpen: isFormGroupOpen,
      formGroup: formGroup,
      groupStatusCode: groupStatusCode,
      groupErrorList: groupErrorList,
      groupErrorMessage: groupErrorMessage,
      formGroupTitle: formGroupTitle,
      groupUserStore: groupUserStore,
      groupUserStatusCode: groupUserStatusCode,
      groupUserErrorList: groupUserErrorList,
      groupUserErrorMessage: groupUserErrorMessage,
      authStore: authStore,
      startProgress: startProgress,
      finishProgress: finishProgress,
      failProgress: failProgress,
      defaultAvatar: defaultAvatar,
      route: route,
      groupId: groupId,
      enrollmentStore: enrollmentStore,
      enrollmentStatusCode: enrollmentStatusCode,
      enrollmentErrorList: enrollmentErrorList,
      enrollmentErrorMessage: enrollmentErrorMessage,
      enrollments: enrollments,
      activityStore: activityStore,
      activityStatusCode: activityStatusCode,
      activityErrorList: activityErrorList,
      activityErrorMessage: activityErrorMessage,
      isActivityFormOpen: isActivityFormOpen,
      formActivity: formActivity,
      date: date,
      userId: userId,
      isAdmin: isAdmin,
      isAdminStatus: isAdminStatus,
    };
  },
  data() {
    return {
      settings: {
        minScrollbarLength: 60
      }
    };
  },
  methods: {
    onRoutechange() {
      setTimeout(() => {
        if (document.getElementsByClassName("mm-active").length > 0) {
          const currentPosition =
            document.getElementsByClassName("mm-active")[0].offsetTop;
          if (currentPosition > 500)
            if (this.$refs.isSimplebar)
              this.$refs.isSimplebar.value.getScrollElement().scrollTop =
                currentPosition + 300;
        }
      }, 300);
    },
    goToDashboard() {
      this.groupId = null;
      this.router.push('/');
      this.isAdmin = false;
    },
    logout() {
      this.router.push('/logout');
    },
    openUserFormModal() {
      this.isFormUserOpen = true;
      if (this.user) {
        this.formUser.id = this.user.id;
        this.formUser.name = this.user.name;
        this.formUser.email = this.user.email;
        this.formUser.password = "";
        this.formUser.phone_number = this.user.phone_number;
        this.imageUrl = this.user.photo_url;
      }
    },
    async saveUser() {
      try {
        if (this.formUser.id) {
          await this.userStore.updateUser(this.formUser);
          if (this.userStatusCode != 200) {
            showErrorToast("Failed to add user", this.userErrorMessage);
          } else {
            this.isFormUserOpen = false;
            await this.getAuthUser();

            showSuccessToast("User Edited successfully!");
          }
        }
      } catch (error) {
        console.error(error);
        showErrorToast("Failed to add user", this.userErrorMessage);
      }
    },
    async openGroupFormModal(groupId) {
      this.isFormGroupOpen = true;
      if (groupId != 'add') {
        this.formGroupTitle = 'Update';

        const group = await this.groupStore.getGroupById(groupId)

        this.formGroup.id = group.id || '0';
        this.formGroup.name = group.name;
        this.formGroup.description = group.description;
      } else {
        this.formGroupTitle = 'Create';

        this.formGroup.id = '';
        this.formGroup.name = '';
        this.formGroup.description = '';
      }
    },
    async saveGroup() {
      this.startProgress();
      try {
        if (this.formGroup.id) {
          await this.groupStore.updateGroup(this.formGroup);
          if (this.groupStatusCode != 200) {
            this.failProgress();
            showErrorToast("Failed to update group", this.groupErrorMessage);
          } else {
            this.isFormGroupOpen = false;
            this.finishProgress();
            showSuccessToast("Group updated successfully!");
          }
        } else {
          const group = await this.groupStore.addGroups(this.formGroup);
          if (this.groupStatusCode != 200) {
            this.failProgress();
            showErrorToast("Failed to add group", this.groupErrorMessage);
          } else {
            this.isFormGroupOpen = false;

            if (group) {
              const groupId = group.id;
              const userId = this.user.id || '0';
              const isAdmin = 1;

              await this.saveGroupUser(groupId, userId, isAdmin);
              this.finishProgress();

              showSuccessToast("Group added successfully!");
            }
          }
        }
      } catch (error) {
        console.error(error);
        showErrorToast("Failed to saved group", this.groupErrorMessage);
        this.failProgress();
      }

      await this.getAuthUser();
    },
    async addAsAdmin(groupUserData) {
      this.startProgress();
      try {
        const id = groupUserData.id;
        const groupId = this.groupId;
        const userId = groupUserData.user.id;
        const isAdmin = 1;

        const formGroupUser = {
          id: id,
          group_id: groupId,
          user_id: userId,
          is_admin: isAdmin
        }
        await this.groupUserStore.updateGroupUser(formGroupUser);
        
        this.group = await this.groupStore.getGroupById(this.groupId);
        await this.getAuthUser();
        this.finishProgress();
        showSuccessToast('Admin added successfully')
      } catch (error) {
        error.log(error);
        showErrorToast('Failed to add as admin')
      }
    },
    async saveGroupUser(groupId, userId, isAdmin) {
      try {
        const formGroupUser = {
          group_id: groupId,
          user_id: userId,
          is_admin: isAdmin
        }
        await this.groupUserStore.addGroupUsers(formGroupUser);
        if (this.groupUserStatusCode != 200) {
          showErrorToast("Failed to saved group user", this.groupUserErrorMessage);
        }
      } catch (error) {
        console.error(error);
        showErrorToast("Failed to saved group user", this.groupUserErrorMessage);
      }
    },
    async leaveGroup(groupUserId) {
      const confirmed = await showDeleteConfirmationDialog();

      if (confirmed) {
        this.startProgress();
        try {
          await this.groupUserStore.deleteGroupUser(groupUserId);
          this.group = await this.groupStore.getGroupById(this.groupId);
          await this.getAuthUser();
          this.finishProgress();
          showSuccessToast("Leave group successfully");
        } catch (error) {
          console.error(error);
          this.failProgress();
          showSuccessToast("Leave group failed");
        }
      }
    },
    onImageProfileError(event) {
      event.target.src = defaultAvatar;
    },
    async openGroup(groupId) {
      this.groupId = groupId;

      this.router.push({ name: 'default', query: { id: groupId } });

      this.startProgress();
      this.group = await this.groupStore.getGroupById(this.groupId);

      this.isAdmin = this.checkIsAdmin(this.group);

      this.enrollmentStore.groupId = this.groupId
      await this.enrollmentStore.getEnrollments();
      this.enrollments = this.enrollmentStore.enrollments;
      this.finishProgress();
    },
    async acceptRequest(enrollment) {
      this.startProgress();
      try {
        // Add group user
        const groupId = enrollment.group_id;
        const userId = enrollment.user_id;
        const isAdmin = 0;

        await this.saveGroupUser(groupId, userId, isAdmin);

        // delete enrollment
        const enrollmentId = enrollment.id;

        this.enrollmentStore.deleteEnrollment(enrollmentId);

        await this.enrollmentStore.getEnrollments();
        this.enrollments = this.enrollmentStore.enrollments;

        this.finishProgress();
        showSuccessToast("User diterima");
      } catch (error) {
        console.error(error);
        this.failProgress();
        showErrorToast("Gagal menerima user");
      }
      this.group = await this.groupStore.getGroupById(this.groupId);
      await this.getAuthUser();
    },
    clearEditImage() {
      this.imageUrl = null;
    },
    openActivityFormModal(userId) {
      this.isActivityFormOpen = true;

      this.formActivity.group_id = '';
      this.formActivity.user_id = userId;
      this.formActivity.description = '';
      this.formActivity.start_time = '';
      this.formActivity.end_time = '';
      this.formActivity.is_priority = 0;
      this.formActivity.is_finished = 0;
    },
    getFormattedTime(dateData, timeData) {
      return `${dateData} ${timeData}:00`;
    },
    async saveActivityMember() {
      this.startProgress();
      try {
        this.formActivity.start_time = this.getFormattedTime(this.date, this.formActivity.start_time);
        this.formActivity.end_time = this.getFormattedTime(this.date, this.formActivity.end_time);

        await this.activityStore.addActivities(this.formActivity);

        this.isActivityFormOpen = false;

        this.finishProgress();
        showSuccessToast("Aktivitas anggota berhasil ditambahkan");
      } catch (error) {
        error.log(error);
        this.failProgress();
        showErrorToast("Gagal menambah aktivitas anggota", error);
      }
    },
  },
  watch: {
    $route: {
      handler: "onRoutechange",
      immediate: true,
      deep: true
    },
    type: {
      immediate: true,
      handler(newVal, oldVal) {
        if (newVal !== oldVal) {
          switch (newVal) {
            case "dark":
              document.body.setAttribute("data-sidebar", "dark");
              document.body.removeAttribute("data-topbar");
              document.body.removeAttribute("data-sidebar-size");
              document.body.removeAttribute("data-keep-enlarged");
              document.body.classList.remove("vertical-collpsed");
              break;
            case "light":
              document.body.setAttribute("data-topbar", "light");
              document.body.removeAttribute("data-sidebar");
              document.body.removeAttribute("data-sidebar-size");
              document.body.classList.remove("vertical-collpsed");
              break;
            case "compact":
              document.body.setAttribute("data-sidebar-size", "small");
              document.body.setAttribute("data-sidebar", "dark");
              document.body.classList.remove("vertical-collpsed");
              document.body.removeAttribute("data-topbar", "dark");
              break;
            case "icon":
              document.body.setAttribute("data-keep-enlarged", "true");
              document.body.classList.add("vertical-collpsed");
              document.body.setAttribute("data-sidebar", "dark");
              document.body.removeAttribute("data-topbar", "dark");
              break;
            case "colored":
              document.body.setAttribute("data-sidebar", "colored");
              document.body.removeAttribute("data-keep-enlarged");
              document.body.classList.remove("vertical-collpsed");
              document.body.removeAttribute("data-sidebar-size");
              break;
            default:
              // document.body.setAttribute("data-sidebar", "dark");
              break;
          }
        }
      }
    },
    width: {
      immediate: true,
      handler(newVal, oldVal) {
        if (newVal !== oldVal) {
          switch (newVal) {
            case "boxed":
              document.body.setAttribute("data-layout-size", "boxed");
              document.body.removeAttribute("data-layout-scrollable");
              break;
            case "fluid":
              document.body.setAttribute("data-layout-mode", "fluid");
              document.body.removeAttribute("data-layout-size");
              document.body.removeAttribute("data-layout-scrollable");
              break;
            case "scrollable":
              document.body.setAttribute("data-layout-scrollable", "true");
              document.body.removeAttribute("data-layout-mode");
              document.body.removeAttribute("data-layout-size");
              break;
            default:
              document.body.setAttribute("data-layout-mode", "fluid");
              break;
          }
        }
      }
    },
    mode: {
      immediate: true,
      handler(newVal, oldVal) {
        if (newVal !== oldVal) {
          switch (newVal) {
            case "light":
              document.body.setAttribute("data-bs-theme", "light");
              break;
            case "dark":
              document.body.setAttribute("data-bs-theme", "dark");
              break;
            default:
              document.body.setAttribute("data-bs-theme", "light");
              break;
          }
        }
      }
    }
  }
};
</script>

<template>
  <!-- ========== User Profile Modal ========== -->
  <BModal v-model="isFormUserOpen" id="modal-standard" title="Update Profile" title-class="font-18"
    ok-title="Update Profile" @ok="saveUser" @hide.prevent @cancel="isFormUserOpen = false"
    @close="isFormUserOpen = false">
    <BRow>
      <!-- <BCol v-if="imageUrl" cols="12">
        <div class="preview d-flex">
          <div class="delete-button" @click="clearEditImage">
            <BButton class="btn btn-sm btn-danger"><i class="mdi mdi-delete-outline"></i></BButton>
          </div>
          <img :src="imageUrl" alt="Cropped Image" class="mx-auto" />
        </div>
      </BCol> -->
      <BCol cols="12">
        <ImageCropper :aspectRatio="1 / 1" :uploadText="'Letakkan foto disini atau klik untuk mengunggah'"
          @update:imageUrl="imageUrl = $event" @update:croppedImageUrl="
            croppedImageUrl = $event;
          formUser.photo = $event;
          " />
      </BCol>
      <BCol cols="12" class="mt-4">
        <BForm class="form-horizontal" role="form">
          <BRow class="mb-3">
            <label class="col-md-2 col-form-label" for="form-name-user">Name</label>
            <BCol md="10">
              <input class="form-control" :class="{
                'is-invalid': !!(userErrorList && userErrorList.name),
              }" id="form-name-user" placeholder="Masukkan Nama" v-model="formUser.name" />
              <template v-if="!!(userErrorList && userErrorList.name)">
                <div class="invalid-feedback" v-for="(err, index) in userErrorList.name" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
          <BRow class="mb-3">
            <label for="form-email" class="col-md-2 col-form-label">Email</label>
            <BCol md="10">
              <input class="form-control" :class="{
                'is-invalid': !!(userErrorList && userErrorList.email),
              }" id="form-email" type="email" placeholder="Masukkan email" v-model="formUser.email"
                autocomplete="email" />

              <template v-if="!!(userErrorList && userErrorList.email)">
                <div class="invalid-feedback" v-for="(err, index) in userErrorList.email" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
          <BRow class="mb-3">
            <label for="form-password" class="col-md-2 col-form-label">Password</label>
            <BCol md="10">
              <input class="form-control" :class="{
                'is-invalid': !!(
                  userErrorList && userErrorList.password
                ),
              }" id="form-password" type="password" placeholder="Masukkan password" v-model="formUser.password"
                autocomplete="current-password" />

              <template v-if="!!(userErrorList && userErrorList.password)">
                <div class="invalid-feedback" v-for="(err, index) in userErrorList.password" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
          <BRow class="mb-3">
            <label for="form-phone" class="col-md-2 col-form-label">Phone</label>
            <BCol md="10">
              <input class="form-control" :class="{
                'is-invalid': !!(userErrorList && userErrorList.phone_number),
              }" id="form-phone" type="phone" placeholder="Masukkan phone" v-model="formUser.phone_number" />

              <template v-if="!!(userErrorList && userErrorList.phone_number)">
                <div class="invalid-feedback" v-for="(err, index) in userErrorList.phone_number" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
        </BForm>
      </BCol>
    </BRow>
  </BModal>

  <!-- ========== Group Modal ========== -->
  <BModal v-model="isFormGroupOpen" id="modal-standard" :title="formGroupTitle + ' Group'" title-class="font-18"
    :ok-title="formGroupTitle" @ok="saveGroup" @hide.prevent @cancel="isFormGroupOpen = false"
    @close="isFormGroupOpen = false">
    <BRow>
      <BCol cols="12" class="mt-2">
        <BForm class="form-horizontal" role="form">
          <BRow class="mb-3">
            <label for="form-name-group">Name</label>
            <BCol>
              <input class="form-control" :class="{
                'is-invalid': !!(groupErrorList && groupErrorList.name),
              }" id="form-name-group" placeholder="Masukkan nama group" v-model="formGroup.name" />
              <template v-if="!!(groupErrorList && groupErrorList.name)">
                <div class="invalid-feedback" v-for="(err, index) in groupErrorList.name" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
          <BRow class="mb-2">
            <BCol>
              <textarea class="form-control" :class="{
                'is-invalid': !!(groupErrorList && groupErrorList.description),
              }" id="form-description" type="text" placeholder="Masukkan deskripsi group ..."
                v-model="formGroup.description" />

              <template v-if="!!(groupErrorList && groupErrorList.description)">
                <div class="invalid-feedback" v-for="(err, index) in groupErrorList.description" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
        </BForm>
      </BCol>
    </BRow>
  </BModal>

  <!-- ========== Activity Modal ========== -->
  <BModal v-model="isActivityFormOpen" id="modal-standard" title="Create Member Activity" title-class="font-18"
    ok-title="Create" @ok="saveActivityMember" @hide.prevent @cancel="isActivityFormOpen = false"
    @close="isActivityFormOpen = false"
    :ok-disabled="!formActivity.description || !date || !formActivity.start_time || !formActivity.end_time">
    <BRow>
      <BCol cols="12" class="mt-2">
        <BForm class="form-horizontal" role="form">
          <BRow class="mb-3">
            <BCol>
              <textarea class="form-control" :class="{
                'is-invalid': !!(activityErrorList && activityErrorList.description),
              }" id="form-activity-description-member" type="text" placeholder="Masukkan deskripsi aktivitas ..."
                v-model="formActivity.description" required />

              <template v-if="!!(activityErrorList && activityErrorList.description)">
                <div class="invalid-feedback" v-for="(err, index) in activityErrorList.description" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
          <div>
            <input v-model="date" class="form-control mb-3" type="date" placeholder="Masukkan tanggal">
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <BCol md="6">
                <input class="form-control" :class="{
                  'is-invalid': !!(activityErrorList && activityErrorList.start_time),
                }" id="form-start-activity-member" placeholder="Start time" v-model="formActivity.start_time"
                  type="time" required />
                <template v-if="!!(activityErrorList && activityErrorList.start_time)">
                  <div class="invalid-feedback" v-for="(err, index) in activityErrorList.start_time" :key="index">
                    <span>{{ err }}</span>
                  </div>
                </template>
              </BCol>
              <BCol md="0">
                <p class="activity-time mb-0 mx-2">-</p>
              </BCol>
              <BCol md="6">
                <input class="form-control" :class="{
                  'is-invalid': !!(activityErrorList && activityErrorList.end_time),
                }" id="form-end-activity-member" placeholder="End ime" v-model="formActivity.end_time" type="time"
                  required />
                <template v-if="!!(activityErrorList && activityErrorList.end_time)">
                  <div class="invalid-feedback" v-for="(err, index) in activityErrorList.end_time" :key="index">
                    <span>{{ err }}</span>
                  </div>
                </template>
              </BCol>
            </div>
            <BCol md="2 d-flex">
              <input class="form-check-input activity-check-form" type="checkbox" id="form-priority-activity-member"
                @change="updatePriority" :checked="formActivity.is_priority === 1 || false" />
              <label class="form-check-label mt-1 ms-1" for="form-priority-activity">
                Prioritas
              </label>
            </BCol>
          </div>
        </BForm>
      </BCol>
    </BRow>
  </BModal>

  <!-- ========== Left Sidebar Start ========== -->
  <div class="vertical-menu sidebar-bg ws-sidebar" style="box-shadow: 4px 0px 4px rgba(0, 0, 0, 0.25);">
    <simplebar v-if="!isCondensed" :settings="settings" class="h-100" ref="currentMenu" id="my-element">
      <div class="d-flex ms-3 align-items-center my-4 ws-menu" @click="openUserFormModal">
        <img :src="user.photo_url || defaultAvatar" alt="User Photo" class="user-photo" @error="onImageProfileError" />
        <h6 class="font-4 ms-2 mb-0">{{ user.name }}</h6>
      </div>
      <hr>
      <div class="p-2 palette-3 my-4 ws-menu ws-main-menu shadow-sm" @click="goToDashboard">
        <p class="font-4 ms-2 mb-0 sidebar-title">My Activities</p>
      </div>
      <div v-if="groupId" class="p-2 mb-2 mt-2 palette-3 d-flex justify-content-between ws-main-menu shadow-sm">
        <p class="font-4 ms-2 mb-0 sidebar-title"> {{ group.name }} </p>
      </div>
      <div v-if="groupId">
        <div v-for="member in group.groupDetails" :key="member.id"
          class="p-2 list-group-item d-flex justify-content-between align-items-center ws-main-menu">
          <h6 class="font-4-normal ms-2 mb-0">
            {{ member.user.name }}
          </h6>
          <div v-if="userId != member.user.id && isAdmin" class="d-flex justify-content-center align-items-center">
            <i class="bx bx-log-out ws-menu me-2" style="color: #EEEEEE;" v-b-tooltip.hover title="Delete Member"
              @click="leaveGroup(member.id)"></i>
            <i v-if="member.isAdmin == 0" class='bx bx-user ws-menu me-1' ws-menu me-2 style="color: #EEEEEE;"
              v-b-tooltip.hover title="Add as Admin" @click="addAsAdmin(member)"></i>
            <i class="bx bx-plus ws-menu" style="color: #EEEEEE;" v-b-tooltip.hover title="Add task"
              @click="openActivityFormModal(member.user.id)"></i>
          </div>
        </div>
      </div>
      <div v-if="groupId && enrollments.length > 0 && isAdmin"
        class="p-2 mb-2 mt-4 palette-3 d-flex justify-content-between ws-main-menu shadow-sm">
        <p class="font-4 ms-2 mb-0 sidebar-title"> Request </p>
      </div>
      <div v-if="groupId && enrollments && isAdmin">
        <div v-for="enrollment in enrollments" :key="enrollment.id"
          class="p-2 list-group-item d-flex justify-content-between align-items-center ws-main-menu mb-2">
          <h6 class="font-4-normal ms-2 mb-0">
            {{ enrollment.user.name }}
          </h6>
          <button class="btn btn-success btn-sm" @click="acceptRequest(enrollment)">
            accept
          </button>
        </div>
      </div>
      <router-link to="/group" v-if="!groupId">
        <div class="p-2 mb-2 mt-3 palette-3 d-flex justify-content-between align-items-center ws-main-menu shadow-sm">
          <p class="font-4 ms-2 mb-0 sidebar-title">Group</p>
          <i class="bx bx-search ws-menu mt-1 me-2" style="color: #EEEEEE; font-size: medium"></i>
        </div>
      </router-link>
      <div v-if="!groupId">
        <div v-for="(group, index) in groups" :key="group.id"
          class="p-2 list-group-item d-flex justify-content-between align-items-center ws-main-menu">
          <h6 class="font-4-normal ms-2 mb-0" @click="openGroup(group.group.id)" role="button">{{ group.group.name }}
          </h6>
          <div class="d-flex justify-content-center align-items-center">
            <i class="bx bx-log-out ws-menu me-2" style="color: #EEEEEE;" @click="leaveGroup(group.groupUserId)"
              v-b-tooltip.hover title="Leave group"></i>
            <i v-if="isAdminStatus[index]" class="bx bx-edit ws-menu me-2" style="color: #EEEEEE;"
              @click="openGroupFormModal(group.group.id)" v-b-tooltip.hover title="Edit group"></i>
            <router-link :to="`/archive/${group.group.id}`">
              <i class="bx bx-folder ws-menu mt-1" style="color: #EEEEEE;" v-b-tooltip.hover title="Arsip group"></i>
            </router-link>
          </div>
        </div>
      </div>
      <div v-if="!groupId" class="p-2 ms-1 noti-icon d-flex align-items-center ws-menu"
        @click="openGroupFormModal('add')">
        <i class="bx bx-plus" style="color: #EEEEEE;"></i>
        <h6 class="font-4 ms-2 mb-0">Create</h6>
      </div>
      <hr class="mt-4">
      <div class="noti-icon d-flex align-items-center ws-menu my-4 logout-button" @click="logout">
        <i class="bx bx-log-out-circle" style="color: #EEEEEE;"></i>
        <h6 class="font-4 ms-2 mb-0">Logout</h6>
      </div>
    </simplebar>

    <simplebar v-else class="h-100">
      <SideNav />
    </simplebar>
  </div>
  <!-- Left Sidebar End -->
</template>

<style>
.delete-button {
  position: absolute;
  top: 10px;
  right: 10px;
  border: none;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.font-4 {
  font-weight: bold;
  color: #EEEEEE
}

.font-4-normal {
  font-weight: normal;
  color: #EEEEEE
}

.logout-button {
  margin-left: 24%;
}

.palette-3 {
  /* background-color: #00ADB5; */
  background-color: #067e82;
}

.preview {
  border: 2px dashed var(--bs-border-color) !important;
  border-radius: 6px;
  margin-top: 20px;
}

.preview img {
  max-width: 100%;
}

.sidebar-bg {
  background-color: #303841;
}

.sidebar-title {
  font-size: 14px;
}

.user-photo {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 10px;
  background-color: #EEEEEE;
}

.ws-main-menu {
  width: 90%;
  border-top-right-radius: 32px;
  border-bottom-right-radius: 32px;
}

.ws-menu {
  cursor: pointer;
}

.ws-sidebar {
  top: 0px;
}
</style>