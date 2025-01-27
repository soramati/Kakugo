  <template>
    <div class="top_page">
      <loading-component v-if="isShowLoading" />
      <div>
        <graph-doughnut :time="time" :key="time" :start="deadline.int_full"  />
      </div>
      <div class="card">
             <div>
          <div class="card_title">
            <h4>
              <a href="/goals/{{ responseData.id }}">{{ responseData.goals_name }}</a>
            </h4>
          </div>
          <!-- <p>id{{ responseData.id }}</p> -->
          <p>期限：{{ formData(responseData.goals_deadline) }}</p>
          <p>条件：{{ responseData.goals_conditions }}</p>
          <p>ごほうび：{{ responseData.goals_reward }}</p>
        </div>
        <div class="card_footer">
          <button @click="toggleMenu">➖</button>
        </div>
        <div v-show="isShowMenu" class="pullMenu">
          <div class="content mt-10">
            <button class="button" type="button" @click="editGoal()">あとでやる</button>
          </div>
          <div class="content mt-10">
            <button class="button" type="button" @click="resetGoal()">あとでやる</button>
          </div>
          <div class="content">
            <button class="button" type="button" @click="deleteGoal()">delete</button>
          </div>
        </div>
      </div>
    </div>
    
  </template>

  <script>
  import { ref } from 'vue';
  import axios from 'axios';
import LoadingComponent from './LoadingComponent.vue';

  export default {
    data() {
      return {
        someDate: '2019-02-01',
        count: 1,
        time: this.getTime(),
        y: 0,
        m: 0,
        d: 0,
        s: 0,
        h: 0,
        responseData: [],
        isShowMenu: false,
        isShowLoading: true,
      };
    },
    mounted() {

      this.getData();
      setInterval(() => {
        this.time = this.time - 1;
        this.s = this.time % 60;
        this.m = Math.floor(this.time / 60) % 60;
        this.h = Math.floor(this.time / 60 / 60);
        this.d = Math.floor(this.time / 60 / 60 / 24);
        console.log(this.d);
      }, 1000);
      this.isShowLoading = false;
    },
    methods: {
      toggleMenu() {
        this.isShowMenu = !this.isShowMenu;
      },
      formData(date) {

        let dateData = new Date(date);
        let year = dateData.getFullYear();
        let month = dateData.getMonth() + 1;
        let day = dateData.getDate();
        let hour = dateData.getHours();
        let min = dateData.getMinutes();
        let sec = dateData.getSeconds();
        return year + '/' + month + '/' + day+ " " + hour + ':' + min;

      },
      counter() {
        this.count++;
      },
      getTime() {
        return this.deadline.int;
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
      editGoal() {
        axios.post('/edit/' + this.responseData.id)
          .then(response => {
            alert('編集しました');
            window.location.href = '/';
          })
          .catch(error => {
            alert(error);
          });
      },
      deleteGoal() {
        axios.post('/destroy/' + this.responseData.id)
          .then(response => {
            alert('削除しました');
            window.location.href = '/';
          })
          .catch(error => {
            alert(error);
          });
      },
      resetGoal() {
        axios.post('/reset/' + this.responseData.id)
          .then(response => {
            alert('リセットしました');
            window.location.href = '/';
          })
          .catch(error => {
            alert(error);
          });
      },

    },
    props: {
      deadline: {
        type: String,
        required: true
      },
      fullTime: {
        type: String,
        required: true
      }
    }
  }
  </script>

  <style scoped>
  .top_page{
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 50px;

  }
  @media  (max-width:768px){
    .top_page{
      display: flex;
      flex-direction: column;
      align-items: center;
    }
  }
  .btn {
    background-color: blue;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
  }

  .card_footer {
 width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  </style>
