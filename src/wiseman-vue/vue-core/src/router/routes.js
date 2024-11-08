import { useAuthStore } from "@/state/pinia";
export default [
  {
    path: "/",
    name: "default",
    meta: { title: "Dashboard", authRequired: true },
    component: () => import("../views/dashboards/default")
  },
  // {
  //   path: "/user",
  //   name: "user",
  //   meta: { title: "Master User", authRequired: true },
  //   component: () => import("../views/user")
  // },
  {
    path: "/group",
    name: "group",
    meta: { title: "Search Group", authRequired: true },
    component: () => import("../views/group")
  },
  {
    path: "/archive/:id",
    name: "archive",
    meta: { title: "Master archive", authRequired: true },
    component: () => import("../views/archive")
  },
  {
    path: "/login",
    name: "login",
    component: () => import("../views/auth/login"),
    meta: {
      title: "Login",
      beforeResolve(routeTo, routeFrom, next) {
        const auth = useAuthStore();
        if (auth.getToken) {
          next({ name: "default" });
        } else {
          next();
        }
      }
    }
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("../views/auth/register"),
    meta: {
      title: "Register",
      beforeResolve(routeTo, routeFrom, next) {
        const auth = useAuthStore();
        if (auth.getToken) {
          next({ name: "default" });
        } else {
          next();
        }
      }
    }
  },
  {
    path: "/forgot-password",
    name: "Forgot password",
    component: () => import("../views/auth/forgot-password"),
    meta: {
      title: "Forgot password",
      beforeResolve(routeTo, routeFrom, next) {
        const auth = useAuthStore();
        if (auth.getToken) {
          next({ name: "default" });
        } else {
          next();
        }
      }
    }
  },
  {
    path: "/forgot-password/form",
    name: "FormForgotPassword",
    component: () => import("../views/auth/form-forgot-password.vue"),
    meta: {
      title: "Form password",
      beforeResolve(routeTo, routeFrom, next) {
        const auth = useAuthStore();
        if (auth.getToken) {
          next({ name: "default" });
        } else {
          next();
        }
      }
    }
  },

  {
    path: "/logout",
    name: "logout",
    component: () => import("../views/auth/logout"),
    meta: {
      title: "Logout",
      authRequired: false,
      beforeResolve(routeTo, routeFrom, next) {
        const authRequiredOnPreviousRoute = routeFrom.matched.some((route) =>
          route.push("/login")
        );
        next(
          authRequiredOnPreviousRoute ? { name: "default" } : { ...routeFrom }
        );
      }
    }
  },
  // Redirect any unmatched routes to the 404 page. This may
  // require some server configuration to work in production:
  // https://router.vuejs.org/en/essentials/history-mode.html#example-server-configurations
  {
    path: "/404",
    name: "NotFound",
    component: () => import("../views/utility/404.vue"),
    meta: {
      title: "404 Not Found",
    },
  },
  // Catch-all route using a dynamic param with a custom regexp
  {
    path: "/:catchAll(.*)", // This replaces the previous wildcard
    redirect: { name: "NotFound" },
  },

];
