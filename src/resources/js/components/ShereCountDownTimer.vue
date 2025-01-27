  <template>
    <div class="top_page">
      <div>
        <div class="nokori_box">
          <template v-if="time >= 0">
            <p class="nokoei_small">{{ shereData.goals_name }}<br>まで残り...</p>
            <div class="sec_box d-flex">
              <p class="animate__animated animate__tada nokori sec">{{ time }}</p>
              <small class="tani">秒</small>
            </div>
            <p class="nokori parsent nokoei_small">{{ nokoriParsent }}%</p>
            <p class="nokori min nokoei_small">{{ nokoriMin }}分</p>
            <p class="nokori week nokoei_small">{{ nokoriWeek }}週間</p>
            <p class="nokori day nokoei_small">{{ nokoriDay }}日</p>
            <span></span>
            <div class="reward_box">
              <p class="nokori day nokoei_small">ごほうび</p>
              <p class="nokori day nokoei_small">{{ shereData.goals_reward }}</p>
              <p class="nokori day nokoei_small">{{ shereData.goals_reward }}</p>
              <p class="nokori day nokoei_small">総いいね！:{{ shereData.goals_good }}</p>
              <keep-alive>
                <button :disabled="disabledGood" @click="goodApi" class="button-30 done_btn">いいね！</button>
              </keep-alive>
            </div>
          </template>
          <template v-else>
            <p v-if="isDone" class="animate_gema_animated animate__tada nokori sec">Congratulations!!</p>
            <p v-else class="animate__animated animate__tada nokori sec">TIMEUP!</p>
            <button @click="reload" class="button-30 done_btn">もどる</button>
          </template>
        </div>
        <graph-doughnut :time="time" :key="time" :start="getCreatedToDeadline()" />
      </div>
    </div>
  </template>

<script>
import { ref, computed, defineProps } from 'vue';
import axios from 'axios';

export default {
  data() {
    return {
      shereUrl: 'shere/',
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
      goodCountUser: 0,
      goodDisabled: false,
      shereData: {},
    };
  },
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  computed: {
    disabledGood() {
      if (this.goodCountUser >= 10) {
        return true;
      } else {
        return false;
      }
    },
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
    this.getLocalStorages();
    let object = JSON.parse(this.data);
    this.shereData = object;
    this.shereUrl = '/shere/' + object.hashed_id;
    this.getdiffSec();
    this.setTime(0);
    setInterval(() => {
      this.setTime(1);
    }, 1000);

  },
  methods: {
    getLocalStorages() {
      if (!localStorage.getItem('count')) {

        localStorage.setItem('count', '0');
      } else {

      }
      this.goodCountUser = localStorage.getItem('count');

    },
    setTime(int) {
      this.time = this.time - int;
      this.s = this.time % 60;
      this.m = Math.floor(this.time / 60) % 60;
      this.h = Math.floor(this.time / 60 / 60);
      this.d = Math.floor(this.time / 60 / 60 / 24);
    },
    goodApi() {
      axios.post('/good/' + this.shereData.hashed_id)
        .then(response => {
          this.shereData.goals_good++;
          this.goodCountUser++;
          localStorage.setItem('count', this.goodCountUser);
          if (this.goodCountUser >= 10) {
            alert('「いいね」が上限に到達しました！');
          }
        })
        .catch(error => {
          alert(error);
        });
    },
    smoothReduceToZero(value, duration) {
      const interval = 10;
      const step = value / (duration * 1000 / interval);
      let currentValue = value;
      const timer = setInterval(() => {
        currentValue -= step;
        if (currentValue <= 0) {
          currentValue = 0;
          clearInterval(timer);
        }
        this.time = currentValue.toFixed(2);
      }, interval);
      this.time = 0;
    },
    reload() {
      window.location.href = '/';
    },
    getCreatedToDeadline() {
      const date = new Date(this.shereData.goals_deadline);
      const now = new Date(this.shereData.created_at);
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
      const deadline = new Date(this.shereData.goals_deadline);
      const now = new Date();
      const diff = deadline - now;
      this.millitime = Math.ceil(diff / 100);
      this.time = Math.ceil(diff / 1000);
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
  flex-direction: column;
  align-items: center;
  margin-top: 2rem;
}

.top_page {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 50px;
}

@media (max-width: 768px) {
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
