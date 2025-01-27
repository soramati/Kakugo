<template>
  <div>


    <div>
      
      <div id="modal_animation" v-if="showModal" class="" >
        <create-modal :data="responseData" ></create-modal>
        <div  @click="toggleModal"  class="modal_wrapper">
        </div>
      </div>
    </div>  
    <button @click="showModal = !showModal" class="button create create_button" >新しくつくる</button>
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
      }
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
            })
            .finally()
        },
        mounted() {
      
    },

    }
    }

    

  </script>

  <style scoped>


.btn {
    background-color: blue;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
  }
  </style>
