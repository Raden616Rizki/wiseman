<script>
import { useRoute } from "vue-router";
import { ref, onMounted, watch } from "vue";
import {
  useGroupStore,
} from "@/state/pinia";

/**
 * Horizontal-topbar component
 */
export default {
  props: {
    type: {
      type: String,
      required: true,
    },
    width: {
      type: String,
      required: true,
    },
    mode: {
      type: String,
      required: true,
    },
    isMemoOpen: {
      type: Boolean,
      required: true,
    }
  },
  components: {},
  data() {
    const route = useRoute();
    const groupStore = useGroupStore();

    const groupId = ref(route.query.id);
    const group = ref('');

    onMounted( async () => {
      if (groupId.value) {
        group.value = await groupStore.getGroupById(groupId.value);
      } else {
        groupId.value = null;
        group.value = null;
      }
    });

    watch(
      () => route.query.id,
      async (newId) => {
        groupId.value = newId;
        if (newId) {
          group.value = await groupStore.getGroupById(groupId.value);
        } else {
          groupId.value = null;
          group.value = null;
        }
      }
    );

    return {
      groupId: groupId,
      group: group,
    };
  },
  mounted() { },
  methods: {
    toggleMenu() {
      this.$parent.toggleMenu();
    },
    handleMemo() {
      this.$parent.handleMemo();
    },
    initFullScreen() {
      document.body.classList.toggle("fullscreen-enable");
      if (
        !document.fullscreenElement &&
        /* alternative standard method */ !document.mozFullScreenElement &&
        !document.webkitFullscreenElement
      ) {
        // current working methods
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(
            Element.ALLOW_KEYBOARD_INPUT
          );
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      }
    },
  },
  watch: {
    type: {
      immediate: true,
      handler(newVal, oldVal) {
        if (newVal !== oldVal) {
          switch (newVal) {
            case "dark":
              document.body.setAttribute("data-topbar", "dark");
              document.body.removeAttribute("data-layout-scrollable");
              break;
            case "light":
              document.body.setAttribute("data-topbar", "light");
              document.body.removeAttribute("data-layout-size", "boxed");
              document.body.removeAttribute("data-layout-scrollable");
              break;
            case "colored":
              document.body.setAttribute("data-topbar", "colored");
              document.body.removeAttribute("data-layout-size", "boxed");
              break;
            default:
              document.body.setAttribute("data-topbar", "dark");
              break;
          }
        }
      },
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
              document.body.removeAttribute("data-layout-scrollable");
              document.body.removeAttribute("data-layout-size");
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
      },
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
      },
    },
  },
};
</script>

<template>
  <header id="page-topbar">
    <div class="navbar-header align-items-center h-top-bg">
      <div class="d-flex">
        <BButton variant="white" id="toggle" type="button" class="btn btn-sm font-size-16 d-lg-none header-item"
          @click="toggleMenu">
          <i class="fa fa-fw fa-bars text-white ms-2"></i>
        </BButton>
      </div>
      <div class="d-flex align-items-center">
        <h2 v-if="group" class="mb-0 text-white">{{ group.name }}</h2>
        <h2 v-else class="mb-0 text-white">Aktivitasku</h2>
        <h2 v-if="!group" class="mx-1"></h2>
      </div>
      <div v-if="groupId" class="d-flex">
        <BButton variant="white" id="toggle" type="button" class="btn btn-sm me-2 font-size-16 d-lg-none header-item">
          <i v-if="!isMemoOpen" class="fas fa-bell text-white mb-0 mt-1" v-b-tooltip.hover title="Buka memo"
            @click="handleMemo"></i>
          <i v-else class="far fa-bell text-white mb-0 mt-1" v-b-tooltip.hover title="Tutup memo"
            @click="handleMemo"></i>
        </BButton>
      </div>
    </div>
  </header>
</template>

<style>
.h-top-bg {
  background-color: #303841;
}
</style>