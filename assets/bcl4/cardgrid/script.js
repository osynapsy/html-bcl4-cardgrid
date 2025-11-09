BclCardGrid =
{
    init : function()
    {
        $('.osy-cardgrid').parent().on('click','.osy-cardgrid-item',function(evt){
            if ($(evt.target).hasClass('osy-cardgrid-link')) {
                return;
            }
            var selected = $(this).hasClass('osy-cardgrid-item-selected');
            $('input[type=checkbox]', this).prop('checked',!selected);
            $(this).toggleClass('osy-cardgrid-item-selected');
        });
        $('.osy-addressbook').parent().on('click','a.osy-cardgrid-link',function(evt){
            //evt.stopPropagation();
        });
    }
}

if (window.Osynapsy){
    Osynapsy.plugin.register('bcl4-cardgrid',function() {
        BclCardGrid.init();
    });
}