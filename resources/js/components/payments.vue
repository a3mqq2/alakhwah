<template>
    <div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for=""> الشهر </label>
                        <input type="month" @input="changeMonth" v-model="month" name="month" id="" class="form-control">
                    </div>
                    <div class="col-md-6" v-if="month">
                      <label for="">المصرف</label>
                      <v-select @input="getCustomers" :options="banks" v-model="bank" label="name" dir="rtl" />
                    </div>
                    <div class="col-md-6" v-if="customers.length">
                      <label for="">الزبون</label>
                      <v-select @input="getContracts" :options="customers" v-model="customer" label="name" dir="rtl" />
                    </div>
                    <div class="col-md-12 mt-3" v-if="contracts.length">
                      <label for="">العقد</label>
                      <v-select
                      dir="rtl"
                      @input="setContract"
                      v-model="contract"
                      :options="contracts">
                      <template slot="option" slot-scope="option">
                          {{ option.description }} - {{ option.monthly_deduction }}
                      </template>
                      
                      <template slot="selected-option" slot-scope="option">
                        {{ option.description }} - {{ option.monthly_deduction }}
                      </template>
                  </v-select>
                    </div>
                  </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 mt-3" v-if="contract">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">وصف العقد: {{ contract.description }}</h5>
              <p class="card-text"><strong> رقم  الهاتف :</strong> {{ customer.phone }}</p>
              <p class="card-text"><strong> رقم البطاقه الشخصيه :</strong> {{ customer.identifier_number }}</p>
              <p class="card-text"><strong> رقم  حساب المصرف :</strong> {{ customer.bank_number }}</p>
              <p class="card-text"><strong>بداية العقد:</strong> {{ contract.start_month }}</p>
              <p class="card-text"><strong>نهايه العقد:</strong> {{ contract.end_month }}</p>
            </div>
          </div>
          <div class="card mt-2">
            <div class="card-body">
              <h5 class="text-primary">البيانات الماليه</h5>
              <p class="card-text mt-3"><strong class="text-primary">اجمالي قيمة القسط : </strong> {{ contract.installments }} د.ل </p>
              <p class="card-text mt-3"><strong class="text-primary">  الاستقطاع الشهري : </strong> {{ contract.monthly_deduction }} د.ل </p>
              <p class="card-text"><strong class="text-success">اجمالي القيمه المدفوعه : </strong> {{ contract.paid }} د.ل </p>
              <p class="card-text"><strong class="text-danger">اجمالي القيمه المتبقية  : </strong> {{ contract.due }} د.ل </p>
            </div>
          </div>
          <div class="card">
              <div class="card-body">
                <h5 class="text-primary">المدفوعات السابقة</h5>
                <div class="col-md-12 mb-3">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <th class="text-center bg-primary">#</th>
                        <th class="text-center bg-primary">الشهر</th>
                        <th class="text-center bg-primary">القيمة</th>
                        <th class="text-center bg-primary">المدفوع</th>
                        <th class="text-center bg-primary">المتبقي</th>
                      </thead>
                      <tbody>
                        <tr v-for="(payment,index) in contract.payments" :key="payment.id">
                          <td class="text-center">{{++index}}</td>
                          <td class="text-center">{{payment.month}}</td>
                          <td class="text-center">{{payment.amount}}</td>
                          <td class="text-center">{{payment.paid}}</td>
                          <td class="text-center">{{payment.due}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="mt-3">
                    <label for="">القيمه التي سيتم دفعها</label>
                    <input type="number" name="" v-model="amount" id="" class="form-control">
                  </div>
                  <div class="mt-2">
                    <label for="">بإمكانك ترك ملاحظه </label>
                    <textarea name="" id="" cols="30" rows="5" v-model="notes" class="form-control" ></textarea>
                  </div>
                </div>
              </div>
          </div>
          <button class="btn btn-primary mt-4" @click="saveData"> دفع </button>
        </div>
      </div>
  
      <div v-if="loading" class="text-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import moment from 'moment';
  export default {
    data() {
      return {
        bank:null,
        customer:null,
        contract:null,
        banks: [],
        customers: [],
        contracts:[],
        notes:null,
        loading: false,
        month:null,
        calculateBankFees: false,
        amount:0,
      };
    },
    methods: {
      init() {
        this.loading = true; // Show loader before making the request
        this.$instance.get('/payments/init').then((res) => {
          this.banks = res.data.banks;
          this.loading = false; 
        });
      },

      saveData() {
        if(!this.month) {
          return this.$toastr.error('يجب عليك اختيار شهر');
        }


        if(!this.contract) {
          return this.$toastr.error('يجب عليك اختيار عقد');
        }

        if(!this.amount) {
          return this.$toastr.error('يجب عليك كتابه   قيمه الدفع');
        }

        let _this = this;
        _this.loading = true;
        let formData = new FormData();
        formData.append('month', this.month);
        formData.append('is_bank_fee', this.calculateBankFees);
        formData.append('amount', this.amount);
        formData.append('contract_id', this.contract.id);
        _this.$instance.post('/payments/store', formData).then(res => {
            this.$toastr.success('تم الاضافه بنجاح');
            this.contract = null;
            this.bank = null;
            this.customer = null;
            this.notes = null;
            this.amount = null;
        }).catch(err => {
          if(err.response.data.errors) {
            err.response.data.errors.forEach(e => {
              this.$toastr.error(e);
            });
          }
        }).then(() => {
          this.loading = false;
        });
      },
  
      getCustomers() {
        this.loading = true; // Show loader before making the request
          this.$instance.get(`/customers/${this.bank.id}`).then((res) => {
            this.customers = res.data.customers;
            this.loading = false; 
          });
      },


      getContracts() {
        if(!this.month) {
          return this.$toastr.error('يجب تحديد شهر');
        }
        this.loading = true; // Show loader before making the request
        this.contracts = [];
        this.contract = null;
          this.$instance.get(`/contracts/${this.customer.id}?month=${this.month}`).then((res) => {
            this.contracts = res.data.contracts.map(contract => ({
            ...contract,
            start_month: moment(contract.start_month).format('Y-M'),
            end_month: moment(contract.end_month).format('Y-M'),
          }));
            this.loading = false; 
          });
      },

      changeMonth() {
        if(this.customer) {
          this.getContracts();
        }
      },

      setContract() {
        this.amount = this.contract.monthly_deduction;
      }
    },
    mounted() {
      this.init();
    },
  };
  </script>
  