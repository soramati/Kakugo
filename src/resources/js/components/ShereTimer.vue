<template>

  <div class="top_page">
      <div>
        <graph-doughnut 
        :time="time" 
        :key="time" 
        :start="data.time.int_full" 
      />
      </div>
      <div class="card_shered">
        <div>
          <div class="box25">
            <p>しめきり</p>
            <p>{{fomateDate}} </p>
          
          </div>
          <span class="span"></span>
          <div class="box25">
            <p>残り時間</p>
            <p>{{  week}}Weeks </p>
            <p>{{ d }}Days</p>
          </div>
          <span class="span"></span>
          <div class="box25">

            
            <!-- <p>id{{ data.data.id }}</p> -->
            <p>クリア条件　</p>
            <p> {{ data.data.goals_conditions }}</p>

          </div>
          <span class="span"></span>
          <div class="box25">
            <p>ごほうび</p>
            <p>{{ data.data.goals_reward }}</p>
        
          </div>

        </div>
          
      </div>
    </div>
</template>

<script>
import { computed, ref } from 'vue';
import axios from 'axios';

export default {


  props: {
 data: {
   type: Array,
   required: true
  }
},
  data() {
    return {

      time: this.data.time.int,
      y: 0,
        m: 0,
        d: 0,
        s: 0,
        h: 0,
        week: 0,

    };
  },
  mounted() {
    setInterval(() => {
      this.time = this.time -1;
      this.s = this.time % 60;
        this.m = Math.floor(this.time / 60) % 60;
        this.h = Math.floor(this.time / 60 / 60);
        this.d = Number.parseFloat(this.time / 60 / 60 / 24).toFixed(2);
        let weeks = this.time / 60 / 60 / 24 / 7;
        this.week = Number.parseFloat(weeks).toFixed(2);
    }, 1000);
  },
  methods: {
    getHowManyDays() {
      const date = new Date(this.data.data.goals_deadline);
      const now = new Date();
      const diff = date - now;
      const diffDays = diff / (1000 * 60 * 60 * 24);
      console.log(diffDays);
      return Math.floor( diffDays);

    }
    
  },
  computed: {
    fomateDate() {
     const date = new Date(this.data.data.goals_deadline);
     console.log(this.data.data.goals_deadline);
     console.log(typeof(date));
      console.log(date.toLocaleDateString());
      let text = `${date.toLocaleDateString()}`;
      return text;
    }
  }


};
</script>

<style scoped>
.span{
  margin: 10px;
  width: 100%;
}
.card_shered {
  width: 86%;
  margin: 30px auto;
  min-height: 200px;
  

}
.card_textbox_shered {

  color: white;
  font-size: 1rem;

}
.top_page{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 50px;
}
.btn {
  background-color: blue;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
}
.box25{
    position: relative;
    background: #fff0cd;
    box-shadow: 0px 0px 0px 5px #fff0cd;
    border: dashed 2px white;
    padding: 0.2em 0.5em;
    color: #454545;
}
.box25:after{
    position: absolute;
    content: '';
    right: -7px;
    top: -7px;
    border-width: 0 15px 15px 0;
    border-style: solid;
    border-color: #ffdb88 #fff #ffdb88;
    box-shadow: -1px 1px 1px rgba(0, 0, 0, 0.15);
}
.box25 p {
    margin: 0; 
    padding: 0;
    color: #454545;
}
</style>
