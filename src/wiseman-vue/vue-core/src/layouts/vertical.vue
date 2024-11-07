<script>
import router from "@/router";

import SideBar from "@/components/side-bar";
import HorizontalTopbar from "@/components/horizontal-topbar";

import { useLayoutStore } from "@/state/pinia";
const layoutStore = useLayoutStore();

/**
 * Vertical layout
 */
export default {
  components: {
    SideBar,
    HorizontalTopbar,
  },
  data() {
    var isOpen = false;
    if (window.screen.width >= 992) {
      isOpen = true;
    }
    return {
      type: layoutStore.leftSidebarType,
      isMenuCondensed: false,
      isOpen: isOpen,
      isMemoOpen: false,
    };
  },
  computed: {
    layoutWidth() {
      return layoutStore.layoutWidth;
    },
    leftSidebarType() {
      return layoutStore.leftSidebarType;
    },
    topbar() {
      return layoutStore.topbar;
    },
    loader() {
      return layoutStore.loader;
    },
    mode() {
      return layoutStore.mode
    }
  },
  created: () => {
    document.body.removeAttribute("data-layout", "horizontal");
    document.body.removeAttribute("data-topbar", "dark");
    document.body.removeAttribute("data-layout-size", "boxed");
    document.body.classList.remove("auth-body-bg");
  },
  methods: {
    toggleMenu() {
      document.body.classList.toggle("sidebar-enable");

      if (window.screen.width >= 992) {
        // eslint-disable-next-line no-unused-vars
        router.afterEach((routeTo, routeFrom) => {
          document.body.classList.remove("sidebar-enable");
          document.body.classList.remove("vertical-collpsed");
        });
        document.body.classList.toggle("vertical-collpsed");
      } else {
        // eslint-disable-next-line no-unused-vars
        router.afterEach((routeTo, routeFrom) => {
          document.body.classList.remove("sidebar-enable");
        });
        document.body.classList.remove("vertical-collpsed");
      }
      this.isMenuCondensed = !this.isMenuCondensed;
      this.isOpen = true;
    },
    closeSideBar() {
      if (window.screen.width >= 992) {
        this.isOpen = true;
      } else {
        this.isOpen = false;
      }
    },
    handleMemo() {
      this.isMemoOpen = !this.isMemoOpen;
    }
  },
  mounted() {
    if (this.loader === true) {
      document.getElementById("preloader").style.display = "block";
      document.getElementById("status").style.display = "block";

      setTimeout(function () {
        document.getElementById("preloader").style.display = "none";
        document.getElementById("status").style.display = "none";
      }, 2500);
    } else {
      document.getElementById("preloader").style.display = "none";
      document.getElementById("status").style.display = "none";
    }
  }
};
</script>

<template>
  <div>
    <div id="preloader">
      <div id="status">
        <div class="spinner-chase">
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
          <div class="chase-dot"></div>
        </div>
      </div>
    </div>
    <div id="layout-wrapper">
      <HorizontalTopbar :type="topbar" :width="layoutWidth" :mode="mode" :isMemoOpen="isMemoOpen"
        v-bind:class="{ 'd-block': true, 'd-md-none': true }" />
      <SideBar v-if="isOpen" :is-condensed="isMenuCondensed" :type="leftSidebarType" :width="layoutWidth"
        :mode="mode" />
      <!-- ============================================================== -->
      <!-- Start Page Content here -->
      <!-- ============================================================== -->

      <div class="main-content">
        <div class="page-content content-top-gap" @click="closeSideBar">
          <!-- Start Content-->
          <BContainer fluid>
            <slot />
          </BContainer>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.content-top-gap {
  margin-top: -70px;
}

@media (max-width: 768px) {
  .content-top-gap {
    margin-top: 0;
  }
}
</style>