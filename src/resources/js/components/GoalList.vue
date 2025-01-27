  <template>
    <div v-if="responseData.isData === '0'">
      <p>データがありません</p>
    </div>

    <div class="section_bar">
      <h2>達成済み</h2>
    </div>
    <div class="card_content grid gap-7 sm:grid-cols-2 md:gap-8 xl:grid-cols-3">

      <div v-for="goal in doneList" :key="goal.id">
        <div class="card">
          <div>
            <div class="card_title">
              <h4>
                <p class="card_title_text">{{ goal.goals_name }}</p>
              </h4>
            </div>
            <p>期限：{{ goal.goals_deadline }}</p>
            <p>条件：{{ goal.goals_conditions }}</p>
            <p>ごほうび：{{ goal.goals_reward }}</p>
          </div>

          <div class="content">
            <button class="button" type="button" @click="deleteGoal(goal.id)">delete</button>
          </div>
        </div>
      </div>
    </div>


  </template>

<script>
import axios from 'axios';
import { ref, computed } from 'vue';

export default {
  data() {
    return {
      responseData: [],
      noneSetList: ref([]),
    };
  },
  computed: {
    doneList() {
      const list = this.responseData.data;
      if (list.length === 0) {
        return 'データがありません';
      }
      let newList = [];
      list.forEach((item) => {
        if (item.goals_is_achieved === 1) {
          newList.push(item);
        }
      });
      return newList;
    },
    unSetedList() {
      const list = this.responseData.data;
      if (list.length === 0) {
        return 'データがありません';
      }
      let newList = [];
      list.forEach((item) => {
        if (item.goals_is_set === 0) {
          newList.push(item);
        }
      });
      return newList;
    },
  },
  mounted() {
    this.getAllGoals();
  },
  methods: {
    startGoal(id) {
      alert('セットしますか？');
      axios.post('/api/start/' + id)
        .then(response => {
          alert('セットしました');
          window.location.href = '/';
        })
        .catch(error => {
          alert(error);
        });
    },
    getListNone() {
      console.log(this.responseData);
      this.noneSetList = this.responseData.data.filter((item) => {
        return item.goals_is_set === 0;
      });
    },
    getAllGoals() {
      axios.get('/api/get', {})
        .then(response => {
          this.responseData = response.data;
        })
        .catch(error => {
          alert(error);
        });
      this.getListNone();
    },
    deleteGoal(id) {
      axios.post('/destroy/' + id)
        .then(response => {
          alert('削除しました');
          window.location.href = '/';
        })
        .catch(error => {
          alert(error);
        });
    },
    resetGoal(id) {
      axios.post('/reset/' + id)
        .then(response => {
          alert('リセットしました');
          window.location.href = '/';
        })
        .catch(error => {
          this.responseData = null;
        });
    },
  },
};
</script>

<style scoped>
.card_title_text {

  font-weight: bold;
  font-size: 1.3rem;
  color: #f8f8f8;
}

.card_title {
  display: flex;
  align-items: center;
}

.section_bar {
  margin-top: 5rem;
  margin-bottom: 1rem;
  margin: 5rem auto;
  padding: 0.5rem;

  border-radius: 5px;
  border: #ffffff solid 3px;
  width: 95%;
  text-align: center;
  color: #f8f8f8;
  font-size: 1.5rem;
  font-weight: bold;
}

.btn {
  background-color: blue;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
}
</style>
