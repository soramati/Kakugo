<template>
  <div id="animation" class="none_title">
    <h1 class="">今日は、何をしよう</h1>
    <small>ひとつのタスクを選択しよう</small>
    <span class="top_span"></span>
    <button @click="showModal = !showModal" class="button-30">はじめる</button>
  </div>
  <div>
    <div>
      <div id="modal_animation" v-if="showModal" class="">
        <create-modal></create-modal>
        <div @click="toggleModal" class="modal_wrapper"></div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {

  setup() {
    return { showModal };
  },
  data() {
    return {
      responseData: [],
      showModal: ref(false),
    };
  },
  methods: {
    toggleModal() {
      this.showModal = !this.showModal;
    },
    getData() {
      axios.get('/testApi', {})
        .then(response => {
          this.responseData = response.data;
        })
        .catch(error => {
          alert('API ERROR');
        });
    },
    mounted() {
      this.getData();
    },
  },
};
</script>

<style scoped>
.modal_wrapper {
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50;
}

.btn {
  background-color: blue;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
}
</style>
