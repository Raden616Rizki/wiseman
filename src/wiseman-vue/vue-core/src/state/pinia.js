import { useLayoutStore } from "./pinia/layout";
import { useAuthStore } from "./pinia/auth";
import { useUserStore } from "./pinia/user";
import { useGroupStore } from "./pinia/group";
import { createPinia } from "pinia";

const pinia = createPinia();
export default pinia;

export { useLayoutStore, useAuthStore, useUserStore, useGroupStore };
