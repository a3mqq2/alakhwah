<template>
  <div>
    <div class="card" v-if="!loading">
      <div class="card-header"> عرض كشف لشهر </div>
      <div class="card-body">
        <form @submit.prevent="showSettlement">
          <div class="form-group">
            <label for="bank">اختر المصرف:</label>
            <v-select v-model="bank" :options="banks" label="name" dir="rtl" />
          </div>
          <div class="form-group">
            <label for="month">اختر الشهر:</label>
            <input type="month" v-model="month" class="form-control" id="month">
          </div>
          <button class="btn btn-primary">عرض الكشف</button>
          <button type="button" class="btn btn-secondary ml-2" @click="exportPdf">تصدير PDF</button>
        </form>
      </div>
    </div>

    <!-- Loading Spinner -->
    <div v-if="loading" class="d-flex justify-content-center mt-3">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <table v-if="contracts.length > 0" class="table table-bordered mt-3" id="contracts-table">
      <thead>
        <tr class="bg-primary text-light text-center">
          <th>رقم العقد</th>
          <th>الوصف</th>
          <th>الزبون</th>
          <th>رقم الهاتف</th>
          <th>رقم حساب المصرف</th>
          <th>شهر بدء الاستقطاع</th>
          <th>شهر الانتهاء</th>
          <th>الاقساط الكلية</th>
          <th>قيمه الاستقطاع</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="contract in contracts" :key="contract.id" :style="{ backgroundColor: contract.is_payment ? 'green' : 'red', color: contract.is_payment ? 'white' : 'black' }">
          <td>{{ contract.id }}</td>
          <td>{{ contract.description }}</td>
          <td>{{ contract.customer ? contract.customer.name : '' }}</td>
          <td>{{ contract.customer ? contract.customer.phone : '' }}</td>
          <td>{{ contract.customer ? contract.customer.bank_number : '' }}</td>
          <td>{{ contract.start_month }}</td>
          <td>{{ contract.end_month }}</td>
          <td>{{ contract.installments }} د.ل</td>
          <td>{{ contract.monthly_deduction }}</td>
        </tr>
      </tbody>
    </table>
    
  </div>
</template>

<script>
import moment from 'moment';
import html2pdf from 'html2pdf.js';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import { format } from 'date-fns';

export default {
  data() {
    return {
      bank: null,
      month: null,
      banks: [],
      contracts: [],
      loading: false,
    };
  },
  methods: {
    calculateMonthDifference(startMonth, endMonth) {
      const start = moment(startMonth, 'Y-M');
      const end = moment(endMonth, 'Y-M');
      return end.diff(start, 'months') == 0 ? 1 : end.diff(start, 'months');
    },

    showSettlement() {
      if (!this.bank) {
        return this.$toastr.error('يجب عليك اختيار مصرف');
      }

      if (!this.month) {
        return this.$toastr.error('يجب عليك اختيار شهر');
      }

      this.loading = true;
      this.$instance
        .get('/statements/show?bank=' + this.bank.id + '&month=' + this.month)
        .then((res) => {
          this.contracts = res.data.contracts.map((contract) => ({
            ...contract,
            start_month: moment(contract.start_month).format('Y-M'),
            end_month: moment(contract.end_month).format('Y-M'),
          }));
        })
        .finally(() => {
          this.loading = false;
        });
    },



    exportPdf() {
  const bankName = this.bank.id;
  const month = this.month ? new Date(this.month).toISOString().split('T')[0] : 'غير محدد';
  const url = `/export-pdf?bankName=${bankName}&month=${month}`;
  window.open(url, '_blank');
},












    init() {
      this.loading = true;
      this.$instance.get('/statements/init').then((r) => {
        this.banks = r.data.banks;
      }).finally(() => {
        this.loading = false;
      });
    },
  },

  mounted() {
    this.init();
  },
};
</script>
