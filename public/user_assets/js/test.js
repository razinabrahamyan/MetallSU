$('.select-ajax-search').selectpicker({
    liveSearch: true
})
    .ajaxSelectPicker({
        ajax: {
            url: '/vayvay',
            method: 'GET',
            data: function () {
                return {
                    q: '{{{q}}}'
                };
            }
        },
        cache: false,
        clearOnEmpty: true,
        preserveSelected: true,
        preprocessData: function(){
            var data = [];
            data.push(
                {
                    value: 'TestThree',
                    text: 'Test Three'
                }
            );
            return data;
        }
    });
