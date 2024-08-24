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
    this.contracts = [
        { bank_number: "0950110136553014", amount: 380.000 },
        { bank_number: "0950110135761102", amount: 175.000 },
        { bank_number: "0950110135693445", amount: 130.000 },
        { bank_number: "0950110135965503", amount: 585.000 },
        { bank_number: "0950110136203140", amount: 155.000 },
        { bank_number: "1240100112396304", amount: 110.000 },
        { bank_number: "1350101130738026", amount: 165.000 },
        { bank_number: "1355240240370008", amount: 170.000 },
        { bank_number: "1355240240350106", amount: 205.000 },
        { bank_number: "1355240240350909", amount: 205.000 },
        { bank_number: "1355240240351900", amount: 255.000 },
        { bank_number: "1350111652820016", amount: 170.000 },
        { bank_number: "1355240240231007", amount: 205.000 },
        { bank_number: "0540100113922101", amount: 255.000 },
        { bank_number: "0950110058953502", amount: 180.000 },
        { bank_number: "0950110096270634", amount: 135.000 },
        { bank_number: "0732190000070255", amount: 195.000 },
        { bank_number: "0730110239539901", amount: 075.000 },
        { bank_number: "0680100110002950", amount: 070.000 },
        { bank_number: "1355240240350005", amount: 125.000 },
        { bank_number: "1355240241010002", amount: 145.000 },
        { bank_number: "0542100000098936", amount: 085.000 },
        { bank_number: "0680100110002950", amount: 185.000 },
        { bank_number: "1355240234160014", amount: 265.000 },
        { bank_number: "0730110081004172", amount: 150.000 },
        { bank_number: "0680100110003843", amount: 135.000 },
        { bank_number: "1355240231340018", amount: 095.000 },
        { bank_number: "0950101000099901", amount: 095.000 },
    ];
    this.init();
  },

  };
  </script>
  