<template>
    <select :id="id">
        <slot></slot>
    </select>
</template>

<script>
export default {
    name: "Select2Component",
    props: ["options", "value", "type", "id", "force_value"],
    template: "#select2-template",
    mounted: function() {
        var vm = this;
        $(this.$el)
            .select2({
                data: this.options.length ? this.options : [{id:'no_id', text:'Нет Отделов'}],
                minimumResultsForSearch: 5
            })
            .val(this.value)
            .trigger("change")
            // emit event on change.
            .on("change", function() {
                vm.$emit("input", this.value);
            });
    },
    watch: {
        value: function(value) {


            // update value
            $(this.$el)
                .val(value)
                .trigger("change");
        },
        options: function(options) {
            // update options
            $(this.$el)
                .empty()
                .select2({
                    data: this.options.length ? this.options : [{id:'no_id', text:'Нет Отделов'}],
                    minimumResultsForSearch: 5

                });
            if(options.length){
                if(!this.force_value || !this.options.find(item =>  item.id == this.force_value)){
                    $(this.$el).val(options[0].id).trigger('change')
                }else{
                    $(this.$el).val(this.force_value).trigger('change')
                }

            }
        },
        force_value:function(value){
            if(value && this.options.find(item =>  item.id == value)){
                $(this.$el)
                    .val(value)
                    .trigger("change");
            }

        }
    },
    destroyed: function() {
        $(this.$el)
            .off()
            .select2("destroy");
    }
}
</script>

<style scoped>

</style>
