<template>
  <div>
    <div class="modal_content">
      <div class="modal_text">

        <h1>今年やり切ること。</h1>
        <div v-show="modal_num === 1" class="change_text">
          <h1>今年の目標（{{ goals_name.length }}/20字）</h1>
          <div class="relative z-0 w-full mb-5 group">
            <input v-model="goals_name" type="text" id="title" class="input_text" name="goal[goals_name]"
              :value="goals_name" placeholder="" required />
            <!-- <label for="title" class="">タイトル</label> -->
            <small class="error_msg">{{ errormsg(goals_name.length) }}</small>
            <p class="title__error" style="color:red"></p>

          </div>
          <div class="">

            <h1>詳しい説明 「〇〇したら達成したとする」など{{ goals_description.length }}/240字）</h1>
            <textarea v-model="goals_description" type="textarea" id="title" class="des_textarea "
              name="goal[goals_description]" :value="goals_description" placeholder="" required></textarea>


            <!-- <small class="error_msg">{{ errormsg(goals_description.length) }}</small> -->
            <p class="title__error" style="color:red"></p>


            <div class="btn_wrapper">
              <button type="submit" @click="beforePabe"
                class="text-white bg-gray-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">もどる</button>
              <button :disabled="showBtn(goals_name)" type="submit" @click="nextPage"
                class="text-white bg-green-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">できた</button>
            </div>
          </div>

        </div>



        <div v-show="modal_num === 2" class="change_text">
          <h1>今年の目標</h1>
          <div class="relative z-0 w-full mb-5 group">
            <p>目標1:{{ goals_name }}</p>
            <p class="body__error" style="color:red"></p>
          </div>

          <div class="relative z-0 w-full mb-5 group">
            <p>詳しい説明:{{ goals_description }}</p>
            <p class="body__error" style="color:red"></p>
          </div>
          <div class="btn_wrapper">
            <button type="submit" @click="beforePabe"
              class="text-white bg-gray-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">もどる</button>
            <button type="submit" @click="submit"
              class="text-white bg-green-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">スタート</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, defineProps } from 'vue';
import axios from 'axios';

let showModal = ref(false);

export default {
  setup() {
    return { showModal };
  },
  props: {
    responseData: {
      type: Object,
      required: false
    }
  },
  data() {
    return {

      modal_num: 1,
      goals_name: ref(''),
      goals_description: ref(''),
      goals_deadline: ref(''),
      goals_conditions: ref(''),
      goals_reward: ref(''),
      isVri: ref(false),

    }

  },
  mounted() {
    //次の日を設定する '2025-01-01T00:00'の形式で設定

    let tomorrow = new Date();
    const day = tomorrow.getDate() + 1;
    const month = tomorrow.getMonth() + 1;
    const year = tomorrow.getFullYear();
    let text = `${year}-${month}-${day}T12:00`;
    console.log(text);
    this.goals_deadline = text;

    if (this.responseData.length === 0) {


    } else {
      this.goals_name = this.responseData.goals_name;
      this.goals_description = this.responseData.goals_description;


    }


  },
  methods: {

    isPastDate(date) {
      let now = new Date();
      let inputDate = new Date(date);
      if (inputDate < now) {
        return true;
      }
      return false;
    },
    showBtn(str) {
      if (str.length < 1) {
        return true;
      }
      if (str.length > 20) {
        return true;
      }
      return false;
    },
    errormsg(lng) {
      let msg = '';
      if (lng > 20) {
        return '文字数オーバーです';
      }
      if (lng === 0) {
        return '入力してください';
      }
      return msg;
    },
    toggleModal() {
      showModal.value = !showModal.value;
    },
    nextPage() {
      if (this.modal_num < 5) {
        this.modal_num++;
      }
    },
    beforePabe() {
      if (this.modal_num > 1) {
        this.modal_num--;
      }
    },
    submit() {
      let data = {
        goals_name: this.goals_name,
        goals_description: this.goals_description,
      };
      axios.post('/api/store', data).catch(error => {
        alert(error);
      });
      console.log(data);
      window.location.reload();
    },

  },
};
</script>

<style scoped>
.modal_text {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  width: 100%;
  height: 100%;
  padding: 10px;

}

.input_text {
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #8e8d8d;
}

.des_textarea {
  height: 200px;
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #8e8d8d;
}

.error_msg {
  color: red;
}

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}

.btn {
  background-color: blue;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
}

.hidden {
  display: none;
}

.create_page {
  width: 95%;
  min-height: 80vh;
  margin-top: 10vh;
  margin-left: auto;
  margin-right: auto;
}

.d_col {
  display: flex;
  flex-direction: column;
}

.btn_wrapper {
  display: flex;
  justify-content: space-between;
  width: 100%;
}

h1 {
  color: #333;
}

input {
  color: #333;
}

@media (max-width: 768px) {
  .modal_content {
    min-width: 98dvw;
    min-height: 66vh;
  }

  .modal_text {
    width: 90%;
  }
}
</style>
