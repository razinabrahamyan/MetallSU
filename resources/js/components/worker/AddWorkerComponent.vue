<template>
    <div>
        <div v-show="!items_loaded">
            <div class="before_loading"><div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span></div></div>
        </div>
        <div v-show="items_loaded" class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#salary_tab" role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-add-to-queue font-18 me-1"></i>
                                </div>
                                <div class="tab-title">Добавить Нового Сотрудника</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content py-3 ">
                    <div class="tab-pane fade active show costing_tab" id="salary_tab" role="tabpanel">
                        <div id="cast_wrapper" class="row">
                            <div  class="cost_cost_part  order-2 order-lg-1">
                                <div>
                                    <div id="main_worker_edit_form" >
                                        <div class="row m-0">
                                            <div class="col-12 m-0 p-2">
                                                <div class="card position_adding_card ">
                                                    <div class="card-body">
                                                        <div class="card-title">
                                                            <input type="hidden" id="pos_id" value="1">
                                                            <div class=" align-items-end vers_2_main">
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-sm-6 col-12">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <div class="row date_main_part">
                                                                                    <div class="col-lg-6 col-12">
                                                                                        <div class="form-group ">
                                                                                            <label for="base">База<span
                                                                                                class="required_label">*</span></label>
                                                                                            <vue-select ref="base_select" id="base" :options="bases_to_select" v-model="base_id">
                                                                                                <option disabled value="0">Select one</option>
                                                                                            </vue-select>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-6 col-12">
                                                                                        <div class="form-group">
                                                                                            <div class="position-relative">
                                                                                                <label for="category">Отдел<span
                                                                                                    class="required_label">*</span></label>
                                                                                                <vue-select ref="category_select" id="category" :options="categories_to_select" v-model="category_id">
                                                                                                    <option disabled value="0">Select one</option>
                                                                                                </vue-select>
                                                                                                <span v-if="!categories_to_select.length" class="unique_title_alarm">в данной базе нет отделов</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <label for="name">Имя<span
                                                                                            class="required_label">*</span>
                                                                                        </label>

                                                                                    </div>
                                                                                    <div class="position-relative">
                                                                                        <input autocomplete="off" v-model="name" required ref="name_input" type="text"
                                                                                               class="form-control" id="name">
                                                                                    </div>

                                                                                </div>
                                                                                <div class="row date_main_part">
                                                                                    <div class="col-12 col-lg-4">
                                                                                        <div class="form-group">
                                                                                            <div class="d-flex justify-content-between">
                                                                                                <label for="salary">Зарплата<span
                                                                                                    class="required_label">*</span> </label>
                                                                                            </div>
                                                                                            <div class="position-relative">
                                                                                                <input v-model="salary" required ref="salary_input" type="text"
                                                                                                       placeholder="Введите сумму"
                                                                                                       class="form-control sum_input" id="salary"
                                                                                                       @keyup="salaryChanged"
                                                                                                       >
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-lg-4">
                                                                                        <div class="form-group">
                                                                                            <div class="d-flex justify-content-between">
                                                                                                <label for="official_salary">Офиц. Зарплата </label>
                                                                                            </div>
                                                                                            <div class="position-relative">
                                                                                                <input v-model="official_salary" required type="text"
                                                                                                       placeholder="Введите сумму"
                                                                                                       class="form-control sum_input" id="official_salary"
                                                                                                       @keyup="salaryChanged"
                                                                                                >
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-lg-4">
                                                                                        <div class="form-group ">
                                                                                            <label >Дата Принятия<span
                                                                                                class="required_label">*</span></label>
                                                                                            <datepicker
                                                                                                :id="'worker_adding_date'"
                                                                                                ref="date_input"
                                                                                                :placeholder="'Выберите дату'"
                                                                                                :language="language"
                                                                                                :monday-first="true"
                                                                                                v-model="date">

                                                                                            </datepicker>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <label for="comment">Дополнительно</label>
                                                                                            <textarea id="comment" name="comment"
                                                                                                      v-model="additional"
                                                                                                      class="form-control comment_input" cols="30"
                                                                                                      rows="4"></textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>


                                                            </div>
                                                            <div class="d-flex mt-4 justify-content-between">
                                                                <div>
                                                                    <button @click="submitForm" type="button" class="btn btn-light pos_save text-white ms-3 ">
                                                                        Сохранить<i class="bx bx-save"></i></button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cost_table_part order-1 order-lg-2">

                                <data-table @click="clicked" :url="'/axios/workers/lazyLoad'" :params="data_table_params" :columns="['База', 'Отдел', 'Имя', 'Зарплата', 'Доп','Статус']"></data-table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Select2Component from "../select2/Select2Component";
import Datepicker from "vuejs-datepicker"
import DataTableComponent from "../datatable/DataTableComponent";
export default {
    name: "AddWorkerComponent",
    data:function () {
        return {
            items_loaded:false,
            language: vdp_translation_ru.js,
            bases: {},
            base_id:null,
            category_id:null,
            name:'',
            date:new Date(),
            salary:null,
            official_salary:null,
            bases_to_select:[],
            categories_to_select:[{id:'no_id', text:'Нет Отделов'}],
            additional:'',
            data_table_params:{base:null,category:null}
        }

    },
    watch:{
        base_id:function (value){
            this.categories_to_select = this.bases[value].options
            this.data_table_params = {
                base: this.base_id,
                category: this.category_id
            }
        },
        category_id:function (){
            this.data_table_params = {
                base: this.base_id,
                category: this.category_id
            }
        }
    },
    components: {
        Select2Component,
        Datepicker,
        DataTableComponent
    },
    mounted() {

        this.data_table_params = {
            base: this.base_id,
            category: this.category_id
        }
        axios.get('/axios/workers/get-select-bases', {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }).then(response => {

            let select_bases = [];
            Object.values(response.data.bases).map((value,index) => {
                let options = [];
                this.bases[value.id] = {
                    options:[]
                }
                Object.values(value.categories).map(category => {
                    options.push({ id:category.id, text: category.title})
                })
                if(index === 0){
                    this.categories_to_select = options;
                    this.category_id = options[0].id;

                }
                this.bases[value.id] = {
                    options:options
                }
                select_bases.push({ id:value.id, text: value.title})
            })
            this.bases_to_select = select_bases;
            this.items_loaded = true;

        });
    },
    methods: {
        clicked(){
            console.log(8888888888888)
        },
        exportData(){
            return{
                name:this.name,
                category_id:this.category_id,
                salary:this.salary.replace(/\D/g, ''),
                date:this.date,
                additional:this.additional,
                official_salary:this.official_salary.replace(/\D/g, ''),
            }
        },
        submitForm(){
            let check = [
                {
                    value:this.name,
                    ref:'name_input',
                    title:'Имя'
                },
                {
                    value:this.salary,
                    ref:'salary_input',
                    title:'Зарплата'
                },
                {
                    value:this.date,
                    ref:'date_input',
                    title:'Дата Принятия'
                },

            ]
            if(this.checkValidity(check)){
                let data = this.exportData()
                const config = {
                    headers: {
                        Accept: 'application/json',
                    }
                }
                axios.post('/axios/workers/add-worker', data, config).then(response => {
                    Lobibox.notify('success', {
                        title:'Сохранено',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: 'все изменения успешно сохранены'
                    });
                    this.data_table_params = {
                        base: this.base_id,
                        category: this.category_id
                    }
                    this.name = ''
                    this.salary = null
                    this.date = new Date()
                    this.$forceUpdate();

                }).catch(e => {
                    this.form_disabled = false;
                    console.log(e);
                });
            }


        },
        salaryChanged(ev){
            ev.target.value = this.makeMoney(ev.target.value)
        },
        checkValidity(values_to_check){
            for(let i = 0; i < values_to_check.length; i++){
                if(!values_to_check[i].value){
                    this.requiredAlert(values_to_check[i].ref,values_to_check[i].title)
                    return false;
                }
            }
            return true
        },
        makeMoney(value, with_sign = false) {
            value += ''
            let minus = false;
            if (value[0] === '-') {
                minus = true;
            }
            value = value.replace(/\D/g, '')

            if (value[0] === '0') {
                value = value.substr(1, value.length);
            }
            let result = '';
            let check = true;
            while (check) {
                if (value.length > 3) {
                    result = ', ' + value.substr(value.length - 3, 3) + result;
                    value = value.substr(0, value.length - 3)
                } else {
                    result = value + result;
                    check = false;
                }
            }
            if(with_sign){
                if(result.length){
                    result += ' ₽'
                }else{
                    result = '0 ₽'
                }
            }
            if(minus){
                result = '- ' + result
            }


            return result;
        },
        requiredAlert(ref,name){

            if(ref === 'date_input'){
                this.$refs[ref].isOpen ||  this.$refs[ref].showCalendar()
            }else{
                this.$refs[ref].focus()
            }
            Lobibox.notify('info', {
                title:'Внимание',
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-info-circle',
                msg: 'поле ' + name + ' обязателно к заполнению'
            });
        }


    },
}
</script>

<style scoped>

</style>
