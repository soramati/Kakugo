<script>



export default {
  data() {
    return {
      responseData: [],
    };
  },
  methods: {
    logout() {
      console.log("logout");
      axios.post('/logout', {})
        .catch(error => {
          alert('API ERROR');
        });
    },
  },
}
</script>

<template>
  <header>
    <div class="logo_container">
      <h1 class="logo_top">Creator's</h1>
      <h1 class="logo_bottom">motivation</h1>
      <p>ver1.0</p>
    </div>
    <!-- Responsive Settings Options -->

    <div class="right_container">
      <p class="user_name">ゲストさん</p>
      <!-- <a class="logout" onclick="logout()">Logout</a> -->
      <button @click="logout">logout</button>
    </div>
  </header>

</template>
<style scoped>
.user_name {
  color: white;
  font-size: 0.8rem;
  margin-right: 1rem;
}

.logout {
  color: white;
  font-size: 0.8rem;

}

.right_container {
  color: white;
  display: flex;
  margin-right: 1rem;
}

header {

  width: 100%;
  height: 3rem;
  /* box-shadow: 0px 4px 4px 0px #00000040; */
  position: absolute;
  top: 0;
  left: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;

}

.logo_container h1 {
  font-size: 0.8rem;
  color: white;
  margin-left: 1rem;
  line-height: 0.9rem;

}

header a {
  color: white;
}

header a:hover {
  text-decoration: underline;
}
</style>