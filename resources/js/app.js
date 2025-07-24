import '../css/app.css';
import './bootstrap';
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import i18n from './i18n/index.js';

import BasicTest from './components/BasicTest.vue'
import LandingPage from './components/LandingPage.vue'
import RootComponent from './components/RootComponent.vue'
import ProfileSelector from './components/ProfileSelector.vue'

console.log('🚀 Starting simple Vue app...');

const appEl = document.getElementById('app');
if (appEl) {
  const routes = [
    { path: '/', name: 'home', component: LandingPage },
    { path: '/simple-test', name: 'simple-test', component: BasicTest },
    // DTN Manager module (lazy loaded)
    {
      path: '/dtn',
      name: 'dtn',
      component: () => import('./modules/DTNManager/views/DTNDashboard.vue')
    },
    // RPM module (lazy loaded)
    {
      path: '/rpm',
      name: 'rpm',
      component: () => import('./modules/RPM/views/RPMPortal.vue')
    }
  ];
  const router = createRouter({
    history: createWebHistory(),
    routes
  });
  const pinia = createPinia();
  const app = createApp(RootComponent);
  app.use(router);
  app.use(pinia);
  app.use(i18n);
  console.log('🚀 Mounting simple Vue app...');
  app.mount('#app');
  setTimeout(() => {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
      loadingScreen.style.opacity = '0';
      loadingScreen.style.pointerEvents = 'none';
      setTimeout(() => {
        loadingScreen.style.display = 'none';
      }, 300);
    }
  }, 100);
  console.log('✅ Simple Vue app mounted successfully!');
}

const profileSelectorEl = document.getElementById('profile-selector-app');
if (profileSelectorEl) {
  const selectorApp = createApp(ProfileSelector);
  selectorApp.mount('#profile-selector-app');
}
