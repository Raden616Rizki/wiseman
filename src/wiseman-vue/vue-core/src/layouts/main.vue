<script>
import Vertical from "./vertical";
import Horizontal from "./horizontal";

import { useLayoutStore } from "@/state/pinia";
const layoutStore = useLayoutStore();

export default {
  components: {
    Vertical,
    Horizontal
  },
  setup() {
    layoutStore.changeLayoutType('vertical');
    layoutStore.changeLeftSidebarType('palette-1');

    return {
      layoutType: layoutStore.layoutType,
      leftSidebarType: layoutStore.leftSidebarType,
    };
  },
};
</script>

<template>
  <div>
    <Vertical v-if="layoutType === 'vertical'" :layout="layoutType">
    </Vertical>

    <Horizontal v-if="layoutType === 'horizontal'" :layout="layoutType">
    </Horizontal>
  </div>
</template>
