  <template>
    <div class="top_page">
      <div>
        <div class="nokori_box ">
          <template v-if="time >= 0">
            <p class="nokoei_small">{{ data.goals_name }}<br>まで残り...</p>
            <div class="sec_box d-flex">
              <p class="animate__animated animate__tada nokori sec ">{{ time }}</p>
              <small class="tani">秒</small>
            </div>
            <p class="nokori parsent nokoei_small">{{ nokoriParsent }}%</p>
            <p class="nokori min nokoei_small">{{ nokoriMin }}分</p>
            <p class="nokori week nokoei_small">{{ nokoriWeek }}週間</p>
            <p class="nokori day nokoei_small">{{ nokoriDay }}日</p>
            <span></span>
            <div class="reward_box">
              <p class="nokori day nokoei_small">ごほうび：</p>
              <p class="nokori day nokoei_small">{{ data.goals_reward }}</p>
            </div>
          </template>
          <template v-else>
            <!-- <p v-if="isDone" class="animate_gema_animated animate__tada nokori sec ">Congratulations!! </p> -->
            <!-- <p v-else class="animate__animated animate__tada nokori sec ">TIMEUP!</p> -->
            <!-- <button @click="reload" class="button-30 done_btn">もどる</button> -->
          </template>


        </div>
        <graph-doughnut :time="data" :key="data" :start="getCreatedToDeadline()" />
      </div>
    </div>


    <template v-if="!isDone">

      <!-- <button @click="doneApi" class="button-30 done_btn">できた！</button> -->
      <!-- <button @click="toggleShowModal()" class="button-30 done_btn shereLink">編集する</button> -->
      <!-- <a class="button-30 shereLink" :href=shereUrl>公開する</a> -->
    </template>
    <!-- <button @click="toggleMenu">➖</button> -->
    <div v-show="isShowMenu" class="pullMenu">
      <div class="content mt-10">
        <button class="button" type="button" @click="resetGoal()">あとでやる</button>
      </div>
      <div class="content">
        <button class="button" type="button" @click="deleteGoal()">delete</button>
      </div>
    </div>
    <create-modal v-if="showModal" :responseData="responseData" :key="responseData"></create-modal>
    <div @click="toggleShowModal()" v-show="showModal" class="modal_wrapper">
    </div>
  </template>

<script>
import { ref, computed, defineProps } from 'vue';
import axios from 'axios';
// import animate from 'animate.css';


export default {
  data() {
    return {
      shereUrl: 'shere/' + this.data.hashed_id,
      someDate: '2019-02-01',
      count: 1,
      time: 0,
      y: 0,
      m: 0,
      d: 0,
      s: 0,
      h: 0,
      responseData: [],
      isShowMenu: false,
      start: ref(0),
      nowPercent: 0,
      showModal: ref(false),
      isShowLoading: true,
      isDone: false,
    };
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  computed: {

    nokoriParsent() {
      return Math.ceil(this.time / this.getCreatedToDeadline() * 100 * 100) / 100;
    },
    nokoriSec() {
      return this.time;
    },
    nokoriMin() {
      return Math.floor(this.time / 60);
    },
    nokoriDay() {
      return Math.floor(this.time / 60 / 60 / 24);
    },
    nokoriWeek() {
      return Math.floor(this.time / 60 / 60 / 24 / 7);
    },
    timeMsg() {
      let timeMsg = `${this.d}日${this.h}時間${this.m}分${this.s}秒`;

      return timeMsg;
    }
  },

  mounted() {
    this.getdiffSec();
    this.setTime(0);
    setInterval(() => {
      this.setTime(1);

    }, 1000);

  },
  methods: {
    setTime(int) {
      this.time = this.time - int;
      this.s = this.time % 60;
      this.m = Math.floor(this.time / 60) % 60;
      this.h = Math.floor(this.time / 60 / 60);
      this.d = Math.floor(this.time / 60 / 60 / 24);
    },
    smoothReduceToZero(value, duration) {
      const interval = 10; // 10msごとに更新
      const step = value / (duration * 1000 / interval); // 1回の減少量
      let currentValue = value;

      const timer = setInterval(() => {
        currentValue -= step;
        if (currentValue <= 0) {
          currentValue = 0; // 0を下回らないように
          clearInterval(timer); // タイマーを停止
        }
        this.time = currentValue.toFixed(2); // 現在の値を表示（小数点以下2桁まで）
      }, interval);
      this.time = 0;
    },
    reload() {
      window.location.href = '/';
    },

    doneApi() {

      axios.post('/done/' + this.data.id)
        .then(response => {
          // this.time = 0;
          // alert('おめでとうございます！');
          this.isDone = true;
          this.smoothReduceToZero(this.time, 2);



        })
        .catch(error => {
          alert(error);
        });
    },
    getCreatedToDeadline() {
      const date = new Date(this.data.goals_deadline);
      const now = new Date(this.data.created_at);
      const diff = date - now;
      const diffSec = diff;
      return Math.ceil(diffSec / 1000);
    },


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
      return year + '/' + month + '/' + day + " " + hour + ':' + min;

    },
    counter() {
      this.count++;
    },
    getdiffSec() {
      const deadline = new Date(this.data.goals_deadline);
      const now = new Date();
      const diff = deadline - now;
      this.millitime = Math.ceil(diff / 100);
      this.time = Math.ceil(diff / 1000);

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
    toggleShowModal() {

      this.showModal = !this.showModal;
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

  }
}
</script>

<style scoped>
.reward_box {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 2rem;
}

.top_page {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 50px;

}

@media (max-width:768px) {
  .top_page {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .top {

    padding: 3rem 0;
    background: #5CE5B4;
    height: 100vh;
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

.done_btn {
  margin-top: 2rem;
}
</style>
