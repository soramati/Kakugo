<script setup lang="ts">
import { Chart as ChartJS, ArcElement, Tooltip, Title, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
import { defineProps, onMounted } from 'vue'
import { ref } from 'vue'

ChartJS.register(ArcElement, Tooltip, Title, Legend)

let props = defineProps(['time', 'start', 'text']);
let time = props.time as number;


// チャート用のデータ
const data = {
  datasets: [{
    data: [time, 100 - time],
    backgroundColor: [
      'RGB(98, 98, 98)',
      'RGB(0, 0, 0)',
    ],
  }]
};

// チャート描画のオプション
const options = {
  responsive: true,
  maintainAspectRatio: false,
  animation: {
    animateScale: true,
    animateRotate: true
  },
  cutout: '85%',
  plugins: {
    title: {
      display: true,
      text: '',
      font: {
        weight: 'bold',
        size: 16
      },
    },
    legend: {
      display: true,
      position: 'bottom',
      reverse: true,
      labels: {
        font: {
          size: 20
        }
      }
    },
  }
}

// ドーナツチャートの中央に表示させるプラグインを定義する
const ratioText = {
  id: 'ratio-text',
  beforeDraw(chart: any) {
    const { ctx, chartArea: { top, width, height } } = chart
    ctx.save()
    //チャート描画部分の中央を指定
    ctx.fillRect(width / 2, top + (height / 2), 0, 0)
    //フォントのスタイル指定
    ctx.font = 'bold 1.5rem Roboto'
    ctx.fillStyle = '#333'
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    // ctx.fillText(day, width / 2, top + (height / 1.8))
    // ctx.fillText(text, width / 2, top + (height / 2.2))
  }
}
</script>

<template>
  <div>
    <div class="doughnut-graph">
      <!-- ドーナツチャートの描画 -->
      <div class="count_box">
        <small class="count_small">現在の達成率</small>
        <h3 class="count_Large">{{ time }}%</h3>
      </div>

      <Doughnut class="doughnut" :data="data" :options="options" :plugins="[ratioText]" />
    </div>
  </div>
</template>

<style>
.count_small {
  font-size: 0.5rem;
  color: #333;
}

.doughnut-graph {
  position: relative;
  width: 100%;
  max-width: 1100px;
}

.count_box {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #333;
}

.count_Large {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
}

@media (max-width: 768px) {
  .doughnut-graph {
    min-height: 100dvw;
  }
}
</style>
