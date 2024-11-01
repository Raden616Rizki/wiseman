<script>
import Layout from "../../layouts/auth";

/**
 * Login component
 */
export default {
  components: {
    Layout,
  },
  mounted() {
    const selectedOption = localStorage.getItem('selectedOption');
    localStorage.clear();
    sessionStorage.clear();
    if (selectedOption) {
      localStorage.setItem('selectedOption', selectedOption);
    }

    window.location.href = "/login"
    this.$router.push({ name: 'login' })
  },
};
</script>

<template>
  <Layout>
  </Layout>
</template>