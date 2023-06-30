<template>
    <div>
        <div v-if="!items_loaded">
            <div class="before_loading"><div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span></div></div>
        </div>
        <div v-else class="card-body p-0">
            <div class="card-title">
                <div class="d-flex flex-wrap p-3 align-items-center ">
                    <h5 class="me-2 ">Предметы/Услуги</h5>
                    <button class="btn btn-light show_all_children me-2 " type="button" @click="openAllChildren">
                        <i class="bx bx-exclude"></i>
                        <span class="show_all_span">Показать все подкатегории</span>
                        <span class="hide_all_span">Скрыть все подкатегории</span>
                    </button>
                    <button class="btn btn-light show_group_search_desk">
                        <i class="bx bx-search me-0"></i>
                    </button>

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

                    <ul class="nav pt-3 nav-tabs" role="tablist">

                        <li class="nav-item" role="presentation" v-for="(position,index) in positions">
                            <a @click="tabClick(position.id)" :class="[{ 'active' : position.id == active_tab },{'error':position.has_error} ,'nav-link tab_change_button']"
                               data-bs-toggle="tab" role="tab" aria-selected="false" :href="'#position_' + position.id + '_tab'">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-add-to-queue font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">{{ position.title }}</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-warning" role="tab" href="/edit-groups">
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
                        <div  v-for="(position,name,group_index) in positions"
                              :class="[position.id == active_tab ? 'active show' : '', 'tab-pane fade costing_tab']"
                              :data-name="position.title"
                              :id="'position_' + position.id + '_tab'"
                              role="tabpanel">
                            <div class="d-flex" v-if="position.loaded">
                                <div class="w-100">
                                    <div v-if="position.new_items" v-for="(item,loop_index) in position.new_items">
                                        <item-component
                                            :bind_item="item"
                                            :bind_responsibles = 'responsibles'
                                            :bind_export_groups="export_groups"
                                            :bind_positions="positions"
                                            :bind_group_index="group_index"
                                            :bind_loop_index="loop_index"
                                            @item-updated="itemUpdated"
                                        ></item-component>
                                    </div>
                                    <div v-if="position.items" v-for="(item,loop_index) in position.items">
                                        <item-component
                                            :key="item.id"
                                            :bind_item="item"
                                            :bind_responsibles = 'responsibles'
                                            :bind_export_groups="export_groups"
                                            :bind_positions="positions"
                                            :bind_group_index="group_index"
                                            :bind_loop_index="loop_index"
                                            @item-updated="itemUpdated"
                                        ></item-component>
                                    </div>

                                    <div v-if="!position.items.length && (!position.new_items || !position.new_items.length)">
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
import ItemComponent from "./ItemComponent";
export default {
    name: "EditCardComponent",
    data:function () {
        return {
            positions: {},
            active_tab:0,
            export_groups:[],
            responsibles:[],
            items_loaded:false,
            edited_items:[],
            errors:[],
            form_disabled:false,
            amount: 100,
            ITEMS_EDITED:{
                items:{},
                children:{},
                new_items:{}
            },
        }

    },
    components: {
        ItemComponent
    },
    mounted() {

        axios.get('/axios/get-tab-items', {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }).then(response => {
            Object.values(response.data.positions).map(value => {
                this.positions[value.id] = value;
            })
            if(response.data.current){
                let CURRENT_POSITION = response.data.current;
                CURRENT_POSITION.loaded = true;
                this.positions[response.data.current.id] = CURRENT_POSITION;
            }

            this.active_tab = response.data.active_tab;
            this.responsibles = response.data.responsibles;
            this.export_groups = response.data.export_groups;
            this.items_loaded = true;
            console.log(this.positions)


        });
    },
    methods: {
        itemUpdated(data,indexes){
            if(data.id){
                this.ITEMS_EDITED.items[data.id] = data;
                if(data.type === 'item'){
                    this.positions[indexes.position_id].items[indexes.item_index] = data
                }

            }else{
                if(data.parent_id){
                    if(!this.ITEMS_EDITED.children[data.parent_id]){
                        this.ITEMS_EDITED.children[data.parent_id] = {}
                    }
                    this.ITEMS_EDITED.children[data.parent_id][data.index] = data
                }else{
                    if(!this.ITEMS_EDITED.new_items[indexes.position_id]){
                        this.ITEMS_EDITED.new_items[indexes.position_id] = {}
                    }
                    if(data.type === 'item'){
                        this.ITEMS_EDITED.new_items[indexes.position_id][indexes.item_index] = data
                    }else{
                        this.ITEMS_EDITED.new_items[indexes.position_id][indexes.item_index].children[data.index] = data
                    }
                }
            }
        },
        tabClick(id){
            this.active_tab = id;
            if(!this.positions[id].loaded){
                axios.get('/axios/get-tab-items/' + id, {
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                }).then(response => {
                    this.positions[response.data.current.id] = response.data.current;
                    this.positions[response.data.current.id].loaded = true;
                    this.$forceUpdate();
                    console.log('positions___ ',this.positions)
                    console.log('response_data ',response.data)
                });
            }else{
                axios.get('/set-active-tab/' + id, {
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                }).then(response => {
                });
            }
        },
        openAllChildren(event,pos_id = null){
            if(!pos_id){
                pos_id = this.active_tab;
            }

            Object.values(this.positions[pos_id].items).map((item) => {
                if(item.sub_items.length || (item.children && item.children.length) ){
                    item.children_open = true
                }
            })
            this.$forceUpdate();
        },

        addItem(){
            let position = this.positions[this.active_tab];
            if(!position.new_items){
                position.new_items = [];
            }
            let new_item = {
                type:'new_item',
                group_id: 'no_group',
                groupedResponsibles: [],
                required_count: false,
                required_responsible: null,
                editable:true,
                sub_items:[],
                children_open:false,
                children:[],
                position_id:this.active_tab,
                title:'',
                id:null
            };
            position.new_items.push(new_item)
            this.$forceUpdate();
        },

        checkFormValidity(){

            this.errors = [];
            let has_changes = false;
            Object.values(this.ITEMS_EDITED.items).map(item => {
                has_changes = true;
                if(!item.deleted){
                    if(item.has_match || !item.title){
                        this.errors.push({
                            position_id:item.position_id
                        })
                    }
                }
            })
            Object.values(this.ITEMS_EDITED.children).map(item => {
                Object.values(item).map(child => {
                    has_changes = true;
                    if(!child.deleted){
                        if(child.has_match || !child.title){
                            this.errors.push({
                                position_id:child.position_id
                            })
                        }
                    }
                })
            })
            Object.values(this.ITEMS_EDITED.new_items).map(position => {
                Object.values(position).map(item => {
                    has_changes = true;
                    if(!item.deleted){
                        if(item.has_match || !item.title){
                            this.errors.push({
                                position_id:item.position_id
                            })
                        }
                        Object.values(item.children).map(child => {
                            has_changes = true;
                            if(!child.deleted){
                                if(child.has_match || !child.title){
                                    this.errors.push({
                                        position_id:child.position_id
                                    })
                                }
                            }
                        })
                    }

                })

            })
            return has_changes;

        },
        submitForm(e){
            this.form_disabled = false;
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
                    Object.values(this.positions).map(value => value.has_error = false)
                    Object.values(this.errors).map(value => {
                        this.positions[value.position_id].has_error = true;
                    })
                    Lobibox.notify('error', {
                        title:'Ошибка',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-x-circle',
                        msg: 'в форме есть пустые либо повторяющиеся названия'
                    });
                    this.$forceUpdate()


                }else{

                    let data = this.ITEMS_EDITED;
                    let positions = this.positions;
                    axios.post('/axios/update-items', data, config).then(response => {
                        if(response.data.updated_positions){
                            Object.values(response.data.updated_positions).map((position) => {
                                position.loaded = true;
                                this.positions[position.id].items = position.items

                            })
                        }
                        console.log(response.data)
                        this.form_disabled = false;
                        this.ITEMS_EDITED = {
                            items:{},
                            children:{},
                            new_items:{}
                        };
                        Object.values(this.positions).map(value => {value.new_items = []; value.has_error = false})
                        Lobibox.notify('success', {
                            title:'Сохранено',
                            pauseDelayOnHover: true,
                            continueDelayOnInactiveTab: false,
                            position: 'top right',
                            icon: 'bx bx-check-circle',
                            msg: 'все изменения успешно сохранены'
                        });
                        this.$forceUpdate();

                    }).catch(e => {
                        this.form_disabled = false;
                        console.log(e);
                    });
                }
            }else{
                this.form_disabled = false;
                Lobibox.notify('info', {
                    title:'Внимание',
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-info-circle',
                    msg: 'изменений не выявлено'
                });
            }




        },


    },
    created() {

    },

}
</script>

<style scoped>

</style>
