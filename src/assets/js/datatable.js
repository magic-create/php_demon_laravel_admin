//  页面载入完毕
$(function(){
    //  禁用表单自动提交
    $('.dataTables_wrapper').on('submit', 'form', function(){
        if($(this).attr('action') == undefined){
            return false;
        }
    });
    //  DataTable语言文本
    $.fn.dataTable.defaults.language = {
        processing:'处理中...',
        lengthMenu:'显示 _MENU_ 项结果',
        zeroRecords:'没有匹配结果',
        info:'显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项',
        infoEmpty:'显示第 0 至 0 项结果，共 0 项',
        infoFiltered:'(由 _MAX_ 项结果过滤)',
        infoPostFix:'',
        search:'<button class="dt-button btn btn-sm btn-info" style="height:calc(1.5em + .75rem + 2px)">搜索</button>',
        url:'',
        emptyTable:'表中数据为空',
        loadingRecords:'载入中...',
        thousands:',',
        infoThousands:',',
        paginate:{
            first:'首页',
            previous:'上页',
            next:'下页',
            last:'末页'
        },
        aria:{
            sortAscending:': 以升序排列此列',
            sortDescending:': 以降序排列此列'
        }
    };
    //  通用后端导出
    $.dataTable.buttons.export = {
        action:function(){
            if(!$.dataTable.config.exportUrl)
                return console.error('Not stated exportUrl!');
            window.open($.MainApp.form.url($.dataTable.config.exportUrl, $.dataTable.parmFormat($.dataTable.searchParams())));
        }
    };
    //  DataTable执行内容
    $.fn.dataTable.execute = function(){
        //  构建搜索参数
        $.dataTable.searchParams = function(){ return $.MainApp.form.value('#' + $.dataTable.config.searchForm); };
        //  初始化批量选择器
        $.dataTable.builder.batch = {list:[], count:{all:0, now:0}};
        //  绘制搜索区域
        $.dataTable.builder.one('xhr', function(table){
            //  批量选择器
            $('#' + $.dataTable.builder.id + '_wrapper ._batch').html('<input type="checkbox" class="i-checks"/>');
            //  搜索区域ID
            var searchPanel = '#' + $.dataTable.builder.id + '_filter';
            //  更新搜索区域内容
            $(searchPanel).html($('#' + $.dataTable.config.searchTemplate).html() + $.fn.dataTable.defaults.language.search)
                          .wrap($('<form id="' + $.dataTable.config.searchForm + '"></form>'))
                          .on('click', 'button', function(){ $.dataTable.builder.draw(); });
            //  移除模板区域
            $('#' + $.dataTable.config.searchTemplate).remove();
            //  时间控件渲染
            $(searchPanel + ' [data-time]').each(function(){
                var selector = searchPanel + ' [name="' + $(this).attr('name') + '"]';
                laydate.render({elem:selector, type:$(this).data('time')});
            });
            //  选择控件渲染
            $(searchPanel + ' [data-select]').each(function(){
                var selector = searchPanel + ' [name="' + $(this).attr('name') + '"]';
                $(selector).select2({dropdownAutoWidth:true});
            });
            //  加载完成事件
            if(typeof ($.dataTable.onLoad) == 'function')
                $.dataTable.onLoad(table, $.dataTable.parmFormat($.dataTable.searchParams()));
        });
        //  重绘事件监听
        $.dataTable.builder.on('draw', function(table){
            //  自定义复选框
            var id = '#' + table.target.id;
            $(id + ' .i-checks').iCheck({checkboxClass:'$dataTableConfig->batchClass'});
            $(id + ' th input:checkbox').iCheck('uncheck');
            var ids = id + ' td input[data-bind="batch"]:checkbox';
            //  刷新当前值
            $.dataTable.builder.batch.reset = function(){
                //  初始化
                $.dataTable.builder.batch.list = [];
                //  计量值
                $.dataTable.builder.batch.count = {all:0, now:0};
                //  遍历树
                $(ids).each(function(){
                    $.dataTable.builder.batch.count.all++;
                    if(true == $(this).is(':checked')){
                        $.dataTable.builder.batch.count.now++;
                        $.dataTable.builder.batch.list.push($(this).val());
                    }
                });
            };
            //  全选和反选功能
            $(id).on('ifChecked', 'th input:checkbox', function(){
                $(ids).iCheck('check');
                $.dataTable.builder.batch.reset();
            }).on('ifUnchecked', 'th input:checkbox', function(){
                $(ids).iCheck('uncheck');
                $.dataTable.builder.batch.reset();
            });
            //  单选和反选功能
            $(ids).on('ifChecked', function(){
                $.dataTable.builder.batch.reset();
            }).on('ifUnchecked', function(){
                $.dataTable.builder.batch.reset();
            });
            //  默认刷新一次
            $.dataTable.builder.batch.reset();
            //  绘制完成事件
            if(typeof ($.dataTable.onDraw) == 'function')
                $.dataTable.onDraw(table, $.dataTable.parmFormat($.dataTable.searchParams()));
        });
    };
});
