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
          <button   class="btn btn-primary">عرض الكشف</button>
        </form>
      </div>
    </div>

    <!-- Loading Spinner -->
    <div v-if="loading" class="d-flex justify-content-center mt-3">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>

    <div v-for="contract in contracts" class="card mb-3 mt-3" :class="{ 'bg-success': contract.is_payment }">
      <div class="card-body">
        <h4 class="font-weight-bold">{{contract.description}}</h4>
        <p class="card-text"><strong> الزبون : <span :class="{ 'text-light': contract.is_payment, 'text-secondary': !contract.is_payment }">{{ contract.customer ? contract.customer.name : '' }}</span> </strong> </p>
        <p class="card-text"><strong> رقم الهاتف : <span :class="{ 'text-light': contract.is_payment, 'text-secondary': !contract.is_payment }">{{ contract.customer ? contract.customer.phone : '' }}</span> </strong> </p>
        <p class="card-text"><strong>   رقم حساب المصرف  : <span :class="{ 'text-light': contract.is_payment, 'text-secondary': !contract.is_payment }">{{ contract.customer ? contract.customer.bank_number : '' }}</span> </strong> </p>
        <hr>
        <p class="card-text"><strong>   شهر بدء الاستقطاع : <span :class="{ 'text-light': contract.is_payment, 'text-secondary': !contract.is_payment }">{{ contract.start_month }}</span> </strong> </p>
        <p class="card-text"><strong>   شهر الانتهاء : <span :class="{ 'text-light': contract.is_payment, 'text-secondary': !contract.is_payment }">{{ contract.end_month }}</span> </strong> </p>
        <p class="card-text"><strong>    الاقساط الكلية : <span :class="{ 'text-light': contract.is_payment, 'text-secondary': !contract.is_payment }">{{ contract.installments }} د.ل </span> </strong> </p>
        <hr>
        <div class="form-group">
          <label :class="{ 'text-light': contract.is_payment, 'text-secondary': !contract.is_payment }">قيمه الاستقطاع</label>
          <input type="number" name="" disabled id=" " v-model="contract.monthly_deduction" class="form-control">
        </div>
      </div>
    </div>
    
  </div>
</template>
<script>
import moment from 'moment';
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

    openModal(contract) {
      $(`#${contract}`).modal('show');
    },

    closeModal(contract) {
      $(`#${contract}`).modal('hide');
    },

    showSettlement() {
      if(!this.bank) {
        return this.$toastr.error('يجب عليك اختيار مصرف');
      }

      if(!this.month) {
        return this.$toastr.error('يجب عليك اختيار شهر');
      }


      this.loading = true;
      this.$instance.get('/statements/show?bank=' + this.bank.id + '&month=' + this.month).then(res => {
        this.contracts = res.data.contracts.map(contract => ({
          ...contract,
          start_month: moment(contract.start_month).format('Y-M'),
          end_month: moment(contract.end_month).format('Y-M'),
        }));
      }).then(res => {
        this.loading = false;
      });
    },

    init() {
      this.loading = true;
      this.$instance.get('/statements/init').then((r) => {
        this.banks = r.data.banks;
      }).finally(() => {
        this.loading = false;
      });
    },

    accept_contract()
    {
      
    }
  },

  mounted() {
    this.init();
  },
};
</script>
