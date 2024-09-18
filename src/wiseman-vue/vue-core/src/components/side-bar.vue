<script>
import simplebar from "simplebar-vue";

import SideNav from "./side-nav";

import { useAuthStore } from "@/state/pinia";

import { useRouter } from "vue-router";

/**
 * Sidebar component
 */
export default {
  components: { simplebar, SideNav },
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

    return { user: user, router: router, groups: groups };
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
    }
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
  <!-- ========== Left Sidebar Start ========== -->
  <div class="vertical-menu sidebar-bg ws-sidebar">
    <simplebar v-if="!isCondensed" :settings="settings" class="h-100" ref="currentMenu" id="my-element">
      <div class="d-flex ms-2 align-items-center mt-3">
        <img :src="user.photo_url" alt="User Photo" class="user-photo" />
        <h6 class="font-4 ms-2 mb-0">{{ user.name }}</h6>
      </div>
      <hr>
      <router-link to="/">
        <div class="p-2 palette-3 mb-3 ws-menu ws-main-menu shadow-sm">
          <h6 class="font-4 ms-2 mb-0">My Activities</h6>
        </div>
      </router-link>
      <div class="p-2 mb-3 palette-3 d-flex justify-content-between ws-menu ws-main-menu shadow-sm">
        <h6 class="font-4 ms-2 mb-0">Group</h6>
        <i class="bx bx-search" style="color: #EEEEEE; font-size: medium"></i>
      </div>
      <div v-for="group in groups" :key="group.id"
        class="p-2 list-group-item d-flex justify-content-between align-items-center ws-menu ws-main-menu">
        <h6 class="font-4 ms-2 mb-0">{{ group.name }}</h6>
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