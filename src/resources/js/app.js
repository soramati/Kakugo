import './bootstrap';
import { createApp } from 'vue';
import CountDownTimer from './components/CountDownTimer.vue';
import BarChart from './components/BarChart.vue';
import GraphDoughnut from './components/GraphDoughnut.vue';
import CreateModal from './components/CreateModal.vue';
import HeaderNav from './components/HeaderNav.vue';
import TaskCard from './components/TaskCard.vue';
import CreatePage from './components/CreatePage.vue';
import GoalList from './components/GoalList.vue';
import ShereTimer from './components/ShereTimer.vue';
import TopPage from './components/TopPage.vue';
import LoadingComponent from './components/LoadingComponent.vue';
import HomePage from './components/HomePage.vue';
import animate from 'animate.css';
import SherePage from './components/SherePage.vue';
import WelcomePage from './components/WelcomePage.vue';
import ShereCountDownTimer from './components/ShereCountDownTimer.vue';




const app = createApp({});
app
    .component('count-down-timer', CountDownTimer)
    .component('bar-chart', BarChart)
    .component('graph-doughnut', GraphDoughnut)
    .component('create-modal', CreateModal)
    .component('header-nav', HeaderNav)
    .component('task-card', TaskCard)
    .component('create-page', CreatePage)
    .component('goal-list', GoalList)
    .component('shere-timer', ShereTimer)
    .component('top-page', TopPage)
    .component('loading-component', LoadingComponent)
    .component('home-page', HomePage)
    .component('shere-page', SherePage)
    .component('shere-count-down-timer', ShereCountDownTimer)
    .component('welcome-page', WelcomePage)


    .mount('#app');