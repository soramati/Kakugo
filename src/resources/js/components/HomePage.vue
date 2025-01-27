<template>
  <loading-component v-if="isShowLoading" />
  <div class="home">

    <div class="count_page_wrapper">
      <div class="count_page_frame">

        <!-- {{ followersList }} -->
        <!-- <button @click="getFollowers()">更新</button> -->
        <p>2025年の目標</p>
        <small>{{ user.name }}さん
        </small>
        <!-- <small>{{ user }} </small> -->

        <div class="count_page">
          <div class="left_box">
            <div class="limit_timer">
              <p class="title_text">2025年は残り</p>
              <h3 class="time_text">{{ timeText }}</h3>
              <div class="br"> </div>
              <div class="weeks">
                <p>{{ weeks }}weeks</p>
                <p>{{ perTime }}</p>
              </div>
            </div>
            <div v-if="isSetGoal" class="count_down">

              <a src="" class="names_list" v-for="goal in responseData.data" :key="goal.id">
                <div v-if="goal.goals_is_achieved == 1">✔️</div>
                <div v-else>
                  <div>⬜︎</div>
                </div>
                <div @click="onShowGoalModal(goal.id, goal.goals_name, goal.goals_description, goal.goals_is_achieved)">
                  {{ goal.goals_name }}
                </div>

              </a>
              <template>
                <div class="btn_box">
                  <button class="add_btn" @click="toggleShowModal()">追加する</button>
                  <span></span>

                </div>

              </template>
              <template v-if="user.isPunlish === 0">

                <div class="btn_box">
                  <button class="add_btn" @click="toggleShowModal()">追加する</button>

                  <span></span>
                </div>


              </template>
              <template v-else>

                <div class="btn_box">
                  <button class="add_btn" @click="reset()">リセット</button>

                  <span></span>
                </div>


              </template>
            </div>

          </div>
          <div class="right_box">
            <count-down-timer :data="count" :key="count" />
          </div>
        </div>
        <div class="center create_bar">
          <!-- <p>{{ user.isPunlish }}desu</p> -->
          <template v-if="user.isPunlish === 0">

            <button class="kakugo_btn" @click="publish()">覚悟を決めて登録する
            </button>

            <!-- <button class="kakugo_btn" @click="">編集中..</button> -->
          </template>
          <template v-else>
            <a :href="'show/' + user.hashed_id" class="add_btn">公開ページ</a>
          </template>

        </div>
        <div class="img_studing">
          <!-- <small class="mode_text">編集中...</small> -->
          <img :src="'img/choju9_0024_10.png'" alt="">
        </div>
        <p class="newTaskTitle">ー新着タスクー</p>
        <div class="viewList">

          <div v-for="user in publishedList" :key="user.id" class="card">
            <div class="card_top">

              <p>{{ user.name }}さんの目標</p>
              <!-- <button @click="addGood(user.id)">♡</button> -->
              <!-- <p>0</p> -->
            </div>
            <div class="list">
              <div v-for="goal in user.goals" :key="goal.id">
                <p>{{ goal.goals_name }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ↓ここからモーダル -->
  <div class="modal_wrapper" v-if="showModal" @click="toggleShowModal">
  </div>
  <div v-if="showModal" class="modal">
    <create-modal :deadline="deadline" />
  </div>

  <div class="modal_wrapper" v-if="showGoalsModal" @click="toggleGoajShowModal">
  </div>
  <div v-if="showGoalsModal" class="modal">
    <div class="add_modal">
      <div class="add_modal_text_box">
        <p>{{ modalTitle }}</p>
        <p>{{ modalDescription }}</p>
      </div>
      <div class="img_studing">
        <img :src="'/img/choju82_0021_11.png'" alt="">
      </div>
      <div class="btn_box">
        <button class="add_btn" @click="toggleGoajShowModal">閉じる</button>
        <template v-if="!is_achieved">
          <button class="add_btn" @click="priDone = true">できた！</button>
        </template>
      </div>

    </div>
    <div v-if="priDone" class="modal">
      <div class="add_modal">
        <p>本当にできた？</p>
        <div class="img_studing">

          <img :src="'/img/choju91_0022_7.png'" alt="">
        </div>
        <div class="mar-top2rem"></div>
        <button class="add_btn" @click="done(modalId)">本当にできた！ </button>
      </div>

    </div>
  </div>
  <div v-if="showPublshed" class="modal publish_modal">
    <p> 公開中...</p>
    <p> {{ num5 }}</p>
    <p>{{ modalId }}</p>


    <div v-if="priDone" class="modal">
      <div class="img_studing">

        <p>本当にできた？</p>
        <button @click="done(modalId)">本当にできた！ </button>
      </div>

    </div>
  </div>
  <!-- {{ publishedList }} -->
  <!-- <button @click="getPublishedGoals()">更新</button> -->

</template>

<script>
import { ref, computed } from 'vue';
import axios from 'axios';
import LoadingComponent from './LoadingComponent.vue';

export default {
  data() {
    return {
      responseData: ref([]),
      showModal: ref(false),
      count: ref(0),
      isShowLoading: true,
      isSetGoal: false,
      time: ref(50),
      timeText: ref(' 100'),
      perTime: ref(100),
      weeks: ref(0),
      user: [],
      isModal: false,
      showGoalsModal: false,
      modalTitle: '',
      modalDescription: '',
      modalId: null,
      priDone: false,
      showPublshed: false,
      num5: ref(5),
      publishedList: [],
      is_achieved: false,
      followersList: ref([]),
      isPublished: true
    }
  },
  props: {
    deadline: {
      type: Object,
      required: true
    }

  },
  mounted() {
    this.getData();
    this.getPublishedGoals();
    this.getUserName();


    setInterval(() => {
      this.howLognTimesForEndYear()
      this.getRemainingPercentage()
    }, 1000);




  },
  computed: {


  },
  methods: {
    publish() {
      // this.showModal = true;
      this.showPublshed = true;

      axios.post('/api/publish', {})
        .then(response => {

        })
        .catch(error => {
          console.log(error);
        });
      setInterval(() => {
        this.num5 = this.num5 - 1;
        if (this.num5 === 0) {

          this.showPublshed = false;
          window.location.reload();
          return;
        }
      }, 1000);
    },
    addGood(id) {
      console.log('good' + id);
      axios.post('/api/addGood/' + id, {})
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
    },

    done(id) {
      this.showGoalsModal = false;
      this.responseData.data.forEach((goal) => {
        if (goal.id === id) {
          goal.goals_is_achieved = 1;
        }
      });
      axios.post('/api/achieved/' + id, {})
        .then(response => {
          this.getData();
        })
        .catch(error => {
          console.log(error);
        });
    },
    toggleGoajShowModal() {
      this.showGoalsModal = !this.showGoalsModal;
      this.priDone = false;
    },

    onShowGoalModal(id, name, description, is_achieved) {
      this.showGoalsModal = true;
      this.modalTitle = name;
      this.modalDescription = description;
      this.modalId = id;
      this.is_achieved = is_achieved;

    },
    getUserName() {
      axios.get('/api/getUserName', {})
        .then(response => {
          this.user = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    howLognTimesForEndYear() {
      // 今日の日付を取得
      const now = new Date();
      // 年末の日付を作成 (今年の12月31日 23:59:59)
      const yearEnd = new Date(now.getFullYear(), 11, 31, 23, 59, 59);
      // 今日から年末までのミリ秒を計算
      const timeDiff = yearEnd - now;
      // ミリ秒を秒に変換
      const seconds = Math.floor((timeDiff / 1000) % 60);
      const minutes = Math.floor((timeDiff / 1000 / 60) % 60);
      const hours = Math.floor((timeDiff / 1000 / 60 / 60) % 24);
      const days = Math.floor(timeDiff / 1000 / 60 / 60 / 24);

      this.timeText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
      const weeks = days / 7;

      // 小数点以下1桁までフォーマット
      this.weeks = Math.floor(weeks);
      return {
        days,
        hours,
        minutes,
        seconds

      };

    },
    getRemainingPercentage() {
      // 現在時刻から年末までの残り時間をパーセンテージで計算
      const now = new Date(); // 現在時刻
      const yearStart = new Date(now.getFullYear(), 0, 1, 0, 0, 0); // 年初 (1月1日 00:00:00)
      const yearEnd = new Date(now.getFullYear(), 11, 31, 23, 59, 59); // 年末 (12月31日 23:59:59)

      const totalYearTime = yearEnd - yearStart; // 1年間のミリ秒数
      const elapsedTime = now - yearStart; // 年初から現在までの経過時間

      // 残り時間の割合を計算
      const remainingPercentage = ((1 - elapsedTime / totalYearTime) * 100).toFixed(1);
      this.perTime = `${remainingPercentage}%`;
    },
    onAchieved(id) {
      console.log(id);
      this.responseData.data.forEach((goal) => {
        if (goal.id === id) {
          goal.goals_is_achieved = 1;
        }
      });
    },
    is_set_goals(int) {
      return int === 1;
    },
    toggleShowModal() {
      this.showModal = !this.showModal;
    },
    getDonePersent() {
      const done = this.responseData.data.filter((goal) => {
        return goal.goals_is_achieved === 1;
      });
      return done.length / this.responseData.data.length * 100;
    },
    getData() {
      axios.get('/api/get', {})
        .then(response => {
          this.responseData = response.data;
          console.log(this.responseData);
          this.isSetGoal = true;
          this.isShowLoading = false;
          const done = this.responseData.data.filter((goal) => {
            return goal.goals_is_achieved === 1;
          });
          this.count = Math.floor(done.length / this.responseData.data.length * 100);
        })
        .catch(error => {
          this.isShowLoading = false;
        });
    },
    getPublishedGoals() {
      axios.get('/api/users/getPublishedGoals', {})
        .then(response => {
          console.log(response);
          this.publishedList = response.data.data;


        })
        .catch(error => {
          this.isShowLoading = false;
        });
    },
    getFollowers() {
      axios.get('/api/users/getFollowers')
        .then(response => {
          console.log(response.data);
          this.followersList = response.data;
        })
        .catch(error => {
          this.isShowLoading = false;
        });
    },
    reset() {
      axios.get('/api/reset', {})
        .then(response => {

          window.location.reload();
        })
        .catch(error => {
          console.log(error);
        });
    }

  }
};
</script>

<style scoped>
.publish_modal {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 80%;
  max-width: 600px;
  height: 90%;
  max-height: 600px;
  background: #fff;
  z-index: 100;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.publish_modal p {
  font-size: 1.5rem;
  color: #333;
  text-align: center;
  margin-top: 2rem;
}

.newTaskTitle {
  font-size: 1rem;
  color: #333;
  text-align: center;
  margin-top: 4rem;
}

.mode_text {
  width: 100%;
  font-size: 0.8rem;
  color: #333;
  text-align: center;
}

.mar-top2rem {
  margin-top: 2rem;
}

.add_modal {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  height: 100%;
}

.time_text {
  font-size: 1.5rem;
  /* font-weight: bold; */
  color: #333;
}

.count_page_frame {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 2rem;
  width: 100%;
  max-width: 567px;
}

.img_studing {
  margin-top: 2rem;
  max-width: 200px;
}

.viewList {
  margin-top: 3rem;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  width: 300px;
}

.card_top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.card {
  display: flex;
  flex-direction: column;

  align-items: center;
  width: 250px;
  height: 230px;
  border-radius: 5px 5px 5px 5px;
  border: #333 solid 3px;
  margin: 1rem;
  padding: 1rem;
}

.list {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: start;
  margin: 1rem;
  padding: 1rem;
  /* border: #333 solid 3px; */
  width: 100%;
}

.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 80%;
  max-width: 600px;
  height: 90%;
  max-height: 600px;
  background: #fff;
  z-index: 100;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.modal_wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(161, 158, 158, 0.5);
  z-index: 100;
  display: flex;
  justify-content: center;
  align-items: center;
}

.count_page_wrapper {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  margin-top: 2rem;
}


.limit_timer {
  margin-bottom: 1rem;
}

.left_box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: start;
  height: 100%;

}

.right_box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.br {
  /* margin-bottom: 1rem; */
  border: #c0bebe solid 3px;
}

.title_text {
  font-size: 0.8rem;
  /* font-weight: bold; */
  color: #333;
}



.btn_box {
  margin-top: 2rem;
  width: 80%;
}

.add_btn {
  text-align: center;
  width: 100%;
  min-width: 100px;
  padding: 3px;
  border: #333 solid 3px;
  margin-bottom: 1rem;
}

.add_btn:hover {
  background: #333;
  color: #fff;
}

.kakugo_btn {
  width: 100%;
  padding: 7px;
  border: #333 solid 3px;
  margin: 5rem 0;
}

.kakugo_btn:hover {
  background: #333;
  color: #fff;

}

.names_list {
  display: flex;

}

.names_list :hover {
  background: #333;
  color: #fff;
}

.col {

  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100vh;
  margin: bottom 50px;
}

.count_page {


  width: 100%;

  display: flex;
  justify-content: space-between;
  align-items: center;
}

@media (max-width: 768px) {
  .count_page {
    width: 100%;
    display: flex;
    flex-direction: column-reverse;
    ;
  }

  .top {
    padding: 3rem 0;
    background: #5CE5B4;
    height: 100vh;
  }
}
</style>
