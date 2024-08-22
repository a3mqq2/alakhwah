<template>
    <div>
      <div class="card" v-if="!loading">
        <div class="card-header">   انشاء كشف لشهر معين </div>
        <div class="card-body">
          <form @submit.prevent="onSubmit">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bank">اختر المصرف:</label>
                        <v-select v-model="bank" :options="banks" label="name" dir="rtl" />
                      </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="month">اختر الشهر:</label>
                        <input type="month" v-model="month" class="form-control" id="month">
                      </div>
                </div>
                <hr>
                <div class="col-md-12">
                    <button class="btn btn-success mb-3" type="button" @click="addContract">اضافه صف جديد <i class="fe fe-plus"></i></button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-primary">
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">رقم الحساب</th>
                                <th scope="col" class="text-center">القيمة المسحوبة</th>
                                <th class="text-center">الاعدادات</th>
                            </thead>
                            <tbody>
                                <tr v-for="(contract, index) in contracts">
                                    <td class="text-center">{{++index}}</td>
                                    <td>
                                        <input type="text" name="" v-model="contract.bank_number" id="" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" name="" id="" v-model="contract.amount" class="form-control">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" @click="deleteItem(contract)" class="btn btn-danger"><i class="fe fe-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>  
                </div>
                <div class="col-md-12">
                    <label for="">ملاحظات (اختياري) </label>
                    <textarea name="" id="" cols="30" rows="4" v-model="notes" class="form-control"></textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <button class="btn btn-primary" >حفظ </button>
                </div>
            </div>
          </form>
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
        contracts: [{bank_number:"",amount:""}],
        loading: false,
        notes:null,
      };
    },
    methods: {
        

    deleteItem(contract) {
      this.contracts = this.contracts.filter(c => c.bank_number != contract.bank_number);
    },
    onSubmit(e) {
        e.preventDefault();
        
        let formData = new FormData();
        if(!this.bank) {
            return this.$toastr.error('يجب تحديد مصرف اولاََ');
        }

        if(!this.month) {
            return this.$toastr.error('يجب تحديد شهر اولاََ');
        }




        this.loading = true;
        formData.append('contracts', JSON.stringify(this.contracts));
        formData.append('bank_id', this.bank.id);
        formData.append('month', this.month);
        formData.append('notes', this.notes);
        this.$instance.post('/state/store', formData).then(r => {
            this.contracts = [];
            this.$toastr.success('تم تسجيل الاقساط بنجاح');
        }).catch(e => {
            this.loading = false;
            if(e.response) {
                e.response.data.forEach(e => {
                    this.$toastr.error(e);
                });
            }

            this.loading = false;
        }).then(() => {
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

      addContract() {
        this.contracts.push({
            id:Math.random(4),
            bank_number: "",
            amount: ""
        });
      }
  
    },
  
    mounted() {
      this.init();
    },
  };
  </script>
  