<script>
import simplebar from "simplebar-vue";
import SideNav from "./side-nav";
import { useAuthStore } from "@/state/pinia";
import { useRouter } from "vue-router";
import ImageCropper from "@/components/widgets/cropper";
import { ref, computed } from "vue";
import { useUserStore } from "@/state/pinia";
import {
  showSuccessToast,
  showErrorToast,
  // showDeleteConfirmationDialog,
} from "@/helpers/alert.js";

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
    const user = authStore.getUser();
    const router = useRouter();
    const groups = user.detailGroups;
    var imageUrl = "";
    var croppedImageUrl = "";
    var isOpenForm = ref(false);


    var formModel = {
      id: "",
      name: "",
      email: "",
      password: "",
      photo: "",
      phone_number: ""
    };

    const userStore = useUserStore();

    const statusCode = computed(() => userStore.response.status);
    const errorList = computed(() => userStore.response?.error || {});
    const errorMessage = computed(() => userStore.response?.message || "");

    return {
      user: user,
      router: router,
      groups: groups,
      formModel: formModel,
      imageUrl: imageUrl,
      croppedImageUrl: croppedImageUrl,
      isOpenForm: isOpenForm,
      userStore: userStore,
      statusCode: statusCode,
      errorList: errorList,
      errorMessage: errorMessage,
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
    logout() {
      this.router.push('/logout');
    },
    openFormModal() {
      this.isOpenForm = true;
      if (this.user) {
        this.formModel.id = this.user.id;
        this.formModel.name = this.user.name;
        this.formModel.email = this.user.email;
        this.formModel.password = "";
        this.formModel.photo = this.user.photo_url;
        this.formModel.phone_number = this.user.phone_number;
        this.imageUrl = this.user.photo_url;
      }
    },
    async saveUser() {
      try {
        if (this.formModel.id) {
          await this.userStore.updateUser(this.formModel);
          if (this.statusCode != 200) {
            showErrorToast("Failed to add user", this.errorMessage);
          } else {
            this.isOpenForm = false;
            // await getUsers();
            showSuccessToast("User Edited successfully!");
          }
        }
      } catch (error) {
        console.error(error);
        showErrorToast("Failed to add user", this.errorMessage);
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
  <BModal v-model="isOpenForm" size="lg" id="modal-standard" title="Update Profile" title-class="font-18"
    ok-title="Update Profile" @ok="saveUser" @hide.prevent @cancel="isOpenForm = false" @close="isOpenForm = false">
    <BRow>
      <BCol cols="12">
        <ImageCropper :aspectRatio="1 / 1" :uploadText="'Letakkan foto disini atau klik untuk mengunggah'"
          @update:imageUrl="imageUrl = $event" @update:croppedImageUrl="
            croppedImageUrl = $event;
          formModel.photo = $event;
          " />
      </BCol>
      <BCol cols="12" class="mt-4">
        <BForm class="form-horizontal" role="form">
          <BRow class="mb-3">
            <label class="col-md-2 col-form-label" for="form-name">Name</label>
            <BCol md="10">
              <input class="form-control" :class="{
                'is-invalid': !!(errorList && errorList.name),
              }" id="form-name" placeholder="Masukkan Nama" v-model="formModel.name" />
              <template v-if="!!(errorList && errorList.name)">
                <div class="invalid-feedback" v-for="(err, index) in errorList.name" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
          <BRow class="mb-3">
            <label for="form-email" class="col-md-2 col-form-label">Email</label>
            <BCol md="10">
              <input class="form-control" :class="{
                'is-invalid': !!(errorList && errorList.email),
              }" id="form-email" type="email" placeholder="Masukkan email" v-model="formModel.email" />

              <template v-if="!!(errorList && errorList.email)">
                <div class="invalid-feedback" v-for="(err, index) in errorList.email" :key="index">
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
                  errorList && errorList.password
                ),
              }" id="form-password" type="password" placeholder="Masukkan password" v-model="formModel.password" />

              <template v-if="!!(errorList && errorList.password)">
                <div class="invalid-feedback" v-for="(err, index) in errorList.password" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
          <BRow class="mb-3">
            <label for="form-phone" class="col-md-2 col-form-label">Phone</label>
            <BCol md="10">
              <input class="form-control" :class="{
                'is-invalid': !!(errorList && errorList.phone_number),
              }" id="form-phone" type="phone" placeholder="Masukkan phone" v-model="formModel.phone_number" />

              <template v-if="!!(errorList && errorList.phone_number)">
                <div class="invalid-feedback" v-for="(err, index) in errorList.phone_number" :key="index">
                  <span>{{ err }}</span>
                </div>
              </template>
            </BCol>
          </BRow>
        </BForm>
      </BCol>
    </BRow>
  </BModal>

  <!-- ========== Left Sidebar Start ========== -->
  <div class="vertical-menu sidebar-bg ws-sidebar">
    <simplebar v-if="!isCondensed" :settings="settings" class="h-100 ws-menu" ref="currentMenu" id="my-element">
      <div class="d-flex ms-2 align-items-center mt-3" @click="openFormModal">
        <img :src="user.photo_url" alt="User Photo" class="user-photo" />
        <h6 class="font-4 ms-2 mb-0">{{ user.name }}</h6>
      </div>
      <hr>
      <router-link to="/">
        <div class="p-2 palette-3 mb-3 ws-menu ws-main-menu shadow-sm">
          <h6 class="font-4 ms-2 mb-0">My Activities</h6>
        </div>
      </router-link>
      <div class="p-2 mb-2 palette-3 d-flex justify-content-between ws-menu ws-main-menu shadow-sm">
        <h6 class="font-4 ms-2 mb-0">Group</h6>
        <i class="bx bx-search" style="color: #EEEEEE; font-size: medium"></i>
      </div>
      <div v-for="group in groups" :key="group.id"
        class="p-2 list-group-item d-flex justify-content-between align-items-center ws-menu ws-main-menu">
        <h6 class="font-4-normal ms-2 mb-0">{{ group.name }}</h6>
        <i class="bx bx-dots-vertical-rounded" style="color: #EEEEEE; font-size: medium"></i>
      </div>
      <div class="p-2 ms-1 noti-icon d-flex align-items-center ws-menu">
        <i class="bx bx-plus" style="color: #EEEEEE;"></i>
        <h6 class="font-4 ms-2 mb-0">Create</h6>
      </div>
      <hr>
      <div class="ms-2 noti-icon d-flex align-items-center mt-4 ws-menu" @click="logout">
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
.ws-main-menu {
  width: 90%;
  border-top-right-radius: 32px;
  border-bottom-right-radius: 32px;
}

.ws-menu {
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

.sidebar-bg {
  background-color: #303841;
}

.palette-3 {
  background-color: #00ADB5;
}

.user-photo {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 10px;
  background-color: #EEEEEE;
}

.ws-sidebar {
  top: 0px;
}
</style>