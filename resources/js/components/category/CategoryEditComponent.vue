<template>
    <div>
        <div v-if="!items_loaded">
            <div class="before_loading"><div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span></div></div>
        </div>
        <div v-else class="card-body p-0">
            <div class="card-title">
                <div class="d-flex flex-wrap p-3 align-items-center ">
                    <h5 class="me-2 ">Площадки/Отделы</h5>
                </div>

                <div class="p-3 border radius-10 m-3 mb-0 group_search_desk">
                    <div class="d-flex">
                        <input type="text" class="form-control w-auto" :ref="'input'" id="group_find_input" placeholder="поиск группы">
                        <button class="btn btn-light button_no_nothing empty_group_results">
                            <i class="me-0 bx bx-x"></i>
                        </button>
                    </div>
                    <div id="group_find_results" class="pt-2">

                    </div>
                </div>

                <div  id="items_edit_form" class="p-2 p-lg-3">

                    <input type="hidden" id="current_tab_input" name="current_tag_id"
                           :value="active_tab">
                    <ul class="nav pt-3 nav-tabs" role="tablist">

                        <li class="nav-item" role="presentation" v-for="(base,index) in bases">
                            <a @click="tabClick(base.id)"
                               :class="[{ 'active' : base.id == active_tab },{'error':base.has_error} ,'nav-link tab_change_button']"
                               aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-add-to-queue font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">{{ base.title }}</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-warning" role="tab" href="/bases/edit">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-edit font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Ред.</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="new_items_added">
                    </div>
                    <div class="tab-content py-3 ">
                        <div
                              class="active show tab-pane fade costing_tab"
                              role="tabpanel">
                            <div class="d-flex" v-if="items_loaded">
                                <div class="w-100">
                                    <h4>Отделы Сотрудников</h4>
                                    <div v-if="current_base.new_categories" v-for="(category,loop_index) in current_base.new_categories">
                                        <category-component v-model="current_base.new_categories[loop_index]"></category-component>
                                    </div>
                                    <div v-if="current_base.categories" v-for="(category,loop_index) in current_base.categories">
                                        <category-component v-model="current_base.categories[loop_index]" :index="loop_index"></category-component>
                                    </div>

                                    <div v-if="!current_base.categories.length && (!current_base.new_categories || !current_base.new_categories.length)">
                                        <h4 class="p-3">Пусто</h4>
                                    </div>
                                </div>


                            </div>
                            <div v-else>
                                <div class="before_loading"><div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span></div></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="p-1 new_item_button_handler"><button type="button" @click="addItem" class="btn btn-light add_main_block">Добавить поле <i class="bx bx-plus"></i></button></div>
                    </div>
                    <div class="edit_form_submit">
                        <div>
                            <button :disabled="form_disabled" type="submit" @click="submitForm" id="submit_button" class="btn btn-light pos_save save_items  text-white">Сохранить<i class="bx bx-save"></i></button>
                            <a href="/" class="btn text-decoration-underline text-white ">Отмена</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CategoryComponent from "./CategoryComponent";
export default {
    name: "WorkerEditComponent",
    data:function () {
        return {
            bases: {},
            current_base:{
                categories:{},
                new_categories:{}
            },
            active_tab:0,
            items_loaded:false,
            errors:[],
            form_disabled:false,
            categories_info:{},
            changes:[]
        }
    },
    components: {
        CategoryComponent
    },
    mounted() {

        axios.get('/axios/get-base-tab-categories', {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }).then(response => {
            this.updateAllData(response)
            console.log(this.bases)
            console.log(this.active_tab)
            console.log(this.current_base)


        });
    },
    watch:{
        bases:{
            handler:function (){
                console.log('bases changed')
            },
            deep:true,
        }
    },
    methods: {
        updateAllData(response){
            Object.values(response.data.bases).map((value,index) => {
                this.bases[value.id] = value
                if(index === 0){
                    this.active_tab = value.id;
                }

            })
            if(response.data.active_tab && this.bases[response.data.active_tab]){
                this.current_base = this.bases[response.data.active_tab]
            }
            this.active_tab = response.data.active_tab;
            this.items_loaded = true;
            console.log(response.data)
        },
        tabClick(id){
            this.active_tab = id;
            this.current_base = this.bases[id]
            axios.get('/axios/set-base-active-tab/' + id, {
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            }).then(response => {
                console.log(response.data)
            });
        },

        addItem(){
            let base = this.current_base;
            if(!base.new_categories){
                base.new_categories = [];
            }
            console.log(base)
            let new_category = {
                type:'new_category',
                editable:true,
                workers:[],
                base_id:this.active_tab,
                title:'',
                id:null
            };
            base.new_categories.push(new_category)
            this.$forceUpdate();
        },

        checkFormValidity(){
            this.errors = [];
            this.changes = [];
            Object.values(this.bases).map(base => {
                Object.values(base.categories).filter(value => value.modified).map(category => {
                    this.changes.push(category)
                    if(!category.title){
                        this.errors.push({base_id:base.id})
                    }
                })
                if(base.new_categories){
                    Object.values(base.new_categories).map(category => {
                        this.changes.push(category)
                        if(!category.title){
                            this.errors.push({base_id:base.id})
                        }
                    })
                }
            })
            return this.changes.length
        },
        submitForm(e){
            this.form_disabled = true;
            e.preventDefault();
            const config = {
                headers: {
                    Accept: 'application/json',
                }
            }

            let changes = this.checkFormValidity();
            if(changes){
                if(this.errors.length){
                    this.form_disabled = false;
                    Object.values(this.bases).map(value => value.has_error = false)
                    Object.values(this.errors).map(value => {
                        this.bases[value.base_id].has_error = true;
                    })
                    this.$forceUpdate()
                    AlertNotification.alertError('Проверьте форму')
                }else{
                    let data = {
                        categories:this.changes
                    };
                    let bases = this.bases;
                    console.log(data)
                    axios.post('/axios/update-categories', data, config).then(response => {

                        this.form_disabled = false;
                        this.updateAllData(response)
                        Object.values(this.bases).map(value => {value.new_categories = []; value.has_error = false})
                        this.$forceUpdate();
                        AlertNotification.alertSuccess('Сохранено')
                    }).catch(e => {
                        this.form_disabled = false;
                        console.log(e);
                    });
                }
            }else{
                this.form_disabled = false;
                AlertNotification.alertInfo('Нет изменений')
            }




        },


    },
    created() {

    },

}
</script>

<style scoped>

</style>
