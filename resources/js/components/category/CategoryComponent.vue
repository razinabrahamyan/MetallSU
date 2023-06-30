<template>
    <div :class="['category_edit_block d-flex position-relative justify-content-between align-items-center my-2',{'deleted':deleted}]">
        <div class="pt-4">
            <div class="top_signs">
                <div v-if="!id" class="new_sign">новый отдел</div>
            </div>
            <div v-if="deleted" class="fuck_this_shit deleted">
                удалено
            </div>
            <div class="d-flex align-items-center">
                <div class="pe-2">
                    <div v-if="id">{{index + 1}}</div>

                    <div v-else>*</div>
                </div>
                <input :disabled="!editable || deleted" type="text" :class="['form-control main_value',{'error':!title}]" v-model="title">
                <button v-if="!editable" class="btn btn-light ms-2" @click="editCategory()">
                    <i class="bx bx-edit"></i>
                </button>
<!--                <button v-if="editable" :class="['btn exist_button btn-light ms-2',{'deleted':deleted}]" @click="deleteCategory()">
                    <i v-if="deleted" class="bx bx-undo"></i>
                    <i v-else class="bx bx-trash text-danger"></i>
                </button>-->
            </div>
        </div>
        <div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CategoryComponent",
    props: ['value', 'index'],
    data:function (){
        return {
            title:this.value.title,
            editable:this.value.editable,
            deleted:this.value.deleted,
            id:this.value.id
        }
    },
    watch:{
        value:function (value){
            this.title = value.title
            this.editable = value.editable
            this.deleted = value.deleted
            this.id = value.id
        },
        title:function (value){
            this.value.title = value
        },
        editable:function (value){
            this.value.editable = value
            this.value.modified = true;
        },
        deleted:function (value){
            this.value.deleted = value
        }
    },
    mounted() {
        console.log('value mounted')
    },
    methods:{
        editCategory(){
            this.editable = true
        },
        deleteCategory(){
            this.deleted = !this.deleted
        }

    }
}
</script>

<style scoped>

</style>
