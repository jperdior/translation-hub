<template>
  <b-container>
    <b-row>
      <b-col>
        <h1>Signa Translator</h1>
        <b-form-group label="Introduce the text to translate" label-for="input-1">
          <b-input id="input-1" v-model="sourceText" type="text" placeholder="Introduce the text to translate"></b-input>
        </b-form-group>
        <b-form-group label="Source language">
          <b-form-select v-model="sourceLanguage" :options="sourceLanguages"></b-form-select>
        </b-form-group>
        <b-form-group label="Target language">
          <b-form-select v-model="targetLanguage" :options="targetLanguages"></b-form-select>
        </b-form-group>
          <b-button variant="primary" @click="search">Search</b-button>
      </b-col>
      <b-modal ref="translation-modal" :ok-only="true">
        <b-modal-body>
          <h1>Translation</h1>
          <table class="table" v-if="status=='Translated'">
            <tr>
              <td><b>Source text</b></td>
              <td>{{ sourceText }}</td>
            </tr>
            <tr>
              <td><b>Source language</b></td>
              <td>{{ sourceLanguage }}</td>
            </tr>
            <tr>
              <td><b>Target language</b></td>
              <td>{{ targetLanguage }}</td>
            </tr>
            <tr>
              <td><b>Translation</b></td>
              <td>{{ translatedText }}</td>
            </tr>
          </table>
          <p v-else>
            Your translation is being processed. Please wait and try again later.
          </p>
        </b-modal-body>
      </b-modal>
    </b-row>
  </b-container>
</template>

<script>
import axios from 'axios';

export default {
  name: 'SearchTranslation',
  methods: {
    searchAction() {
      console.log('searching for ' + this.sourceText);

    },
    async search(){
      const axiosInstance = axios.create();

      const response = await axiosInstance.post('http://localhost:8080/api/translate', {
        sourceText: this.sourceText,
        sourceLanguage: this.sourceLanguage,
        targetLanguage: this.targetLanguage
      });

      this.translatedText = response.data.translatedText;
      this.status = response.data.status;

      this.$refs['translation-modal'].show();
    }
  },
  data() {
    return {
      sourceText: '',
      sourceLanguage: '',
      targetLanguage: '',
      translatedText: '',
      status: '',
      sourceLanguages: [
        'BG',
        'CS',
        'DA',
        'DE',
        'EL',
        'EN',
        'ES',
        'ET',
        'FI',
        'FR',
        'HU',
        'ID',
        'IT',
        'JA',
        'LT',
        'LV',
        'NL',
        'PL',
        'PT',
        'RO',
        'RU',
        'SK',
        'SL',
        'SV',
        'TR',
        'UK',
        'ZH',
      ],
      targetLanguages: [
        'BG',
        'CS',
        'DA',
        'DE',
        'EL',
        'EN-GB',
        'ES',
        'ET',
        'FI',
        'FR',
        'HU',
        'ID',
        'IT',
        'JA',
        'LT',
        'LV',
        'NL',
        'PL',
        'PT-BR',
        'PT-PT',
        'RO',
        'RU',
        'SK',
        'SL',
        'SV',
        'TR',
        'UK',
        'ZH',
      ],
    }
  }
}


</script>
