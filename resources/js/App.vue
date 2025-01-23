<script setup>
import { useTheme } from 'vuetify'
import ScrollToTop from '@core/components/ScrollToTop.vue'
import initCore from '@core/initCore'
import {
  initConfigStore,
  useConfigStore,
} from '@core/stores/config'
import { hexToRgb } from '@layouts/utils'

const { global } = useTheme()

// ℹ️ Sync current theme with initial loader theme
initCore()
initConfigStore()

const configStore = useConfigStore()
</script>

<template>
  <div>
    <h1>Translation Management</h1>
    <form @submit.prevent="createTranslation">
      <div>
        <label>Key:</label>
        <input v-model="form.key" required />
      </div>
      <div>
        <label>Locale:</label>
        <input v-model="form.locale" required />
      </div>
      <div>
        <label>Content:</label>
        <textarea v-model="form.content" required></textarea>
      </div>
      <div>
        <label>Tags:</label>
        <input v-model="form.tags" />
      </div>
      <button type="submit">Create Translation</button>
    </form>

    <div v-if="translations.length">
      <h2>Translations</h2>
      <ul>
        <li v-for="translation in translations" :key="translation.id">
          {{ translation.locale }} - {{ translation.key }}: {{ translation.content }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
const API_URL = 'http://dev.translation-management.com/api';

export default {
  data() {
    return {
      form: {
        key: '',
        locale: '',
        content: '',
        tags: ''
      },
      translations: []
    };
  },
  methods: {
    async createTranslation() {
      try {
        const response = await axios.post('${API_URL}/translations', this.form);
        alert(response.data.message);
        this.loadTranslations();
      } catch (error) {
        console.error(error);
      }
    },
    async loadTranslations() {
      try {
        const response = await axios.get('${API_URL}/api/translations');
        this.translations = response.data;
      } catch (error) {
        console.error(error);
      }
    }
  },
  mounted() {
    this.loadTranslations();
  }
};
</script>
