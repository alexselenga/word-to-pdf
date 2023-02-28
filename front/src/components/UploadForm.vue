<template>
  <div class="columns">
    <form class="rows form" novalidate @submit.prevent="onSubmit">
      <div class="t-8">
        <div class="label">
          DOCX - файл
        </div>
        <file-upload v-model="form.wordDocument"></file-upload>
      </div>
      <div class="t-8">
        <div class="label">
          Наименование переменной
        </div>
        <input type="text" v-model="form.varName">
      </div>
      <div class="t-8">
        <div class="label">
          Значение переменной
        </div>
        <input type="text" v-model="form.varValue">
      </div>
      <div class="t-8">
        <button class="t-8" type="submit">Отправить</button>
      </div>
    </form>
    <div class="pdfArea">
      <object><embed :src=pdfSrc width="700" height="500" /></object>
    </div>
  </div>
</template>

<script>
import FileUpload from './FileUpload';
import axios from 'axios';

export default {
  components: { FileUpload },
  data: () => ({
    pdfSrc: '',
    form: {
      file: null,
      varName: '',
      varValue: '',
    }
  }),
  methods: {
    onSubmit() {
      if (this.form.wordDocument == null) {
        alert('Заполните необходимые поля');
        return;
      }

      axios.post('http://word/api',
        {
          'wordDocument': this.form.wordDocument,
          'varName': this.form.varName,
          'varValue': this.form.varValue,
        },
        {
        withCredentials: false,
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }).then(res => {
        this.form.wordDocument = null;
        this.form.varName = '';
        this.form.varValue = '';
        this.pdfSrc = res.data;
      })
    }
  }
}
</script>

<style>
.columns {
  display: flex;
  flex-direction: row;
}

.form {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  padding: 40px;
}

.t-8 {
  display: flex;
  flex-direction: row;
  margin-top: 8pt;
}

.label {
  width: 180px;
  font-size: 12px;
  padding-top: 3pt;
  text-align: left;
}

.pdfArea {
  width: 800px;
  height: 600px;
}
</style>