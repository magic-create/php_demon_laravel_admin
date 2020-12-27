//  页面载入完毕
$(function(){
    //  禁用表单自动提交
    $('.dataTables_wrapper').on('submit,reset', 'form', function(){
        if($(this).attr('action') == undefined){
            return false;
        }
    });
    //  扩展工具
    var BootstrapTable = $.fn.bootstrapTable.Constructor,
        _initHeader = BootstrapTable.prototype.initHeader,
        _initToolbar = BootstrapTable.prototype.initToolbar,
        _load = BootstrapTable.prototype.load,
        _initSearch = BootstrapTable.prototype.initSearch;
    //  初始化工具栏
    BootstrapTable.prototype.initToolbar = function(){
        _initToolbar.apply(this, Array.prototype.slice.apply(arguments));
        //  文本格式化
        var sprintf = $.fn.bootstrapTable.utils.sprintf;
        //  搜索表单
        var search = function(){ return $.MainApp.form.value('#' + this.options.searchForm); }.bind(this);
        //  扩展高级搜索
        if(this.options.searchPanel){
            var html = sprintf('<div class="columns columns-%s float-%s">', this.options.buttonsAlign, this.options.buttonsAlign);
            html += sprintf('<button class="btn btn-%s" type="button" name="%s" title="%s">', this.options.buttonsClass, this.options.searchTemplate, this.options.formatAdvancedSearch());
            html += sprintf('<i class="%s %s"></i>', this.options.iconsPrefix, this.options.icons.search);
            html += '</button></div>';
            if(this.$toolbar.find(".float-right").length > 0) $(html).insertBefore(this.$toolbar.find(".float-right:first"));
            else this.$toolbar.append(html);
            //  搜索表单
            var formId = '#' + this.options.searchForm;
            //  面板展开或隐藏
            $('body').on('click', '[name="' + this.options.searchTemplate + '"]', function(){ $('#' + this.options.searchTemplate).toggleClass('d-block'); }.bind(this));
            //  是否默认展开
            if(this.options.searchPanelOpen) $('#' + this.options.searchTemplate).addClass('d-block');
            //  时间控件渲染
            $(formId + ' [data-time]').each(function(){laydate.render({elem:formId + ' [name="' + $(this).attr('name') + '"]', type:$(this).data('time')});});
            //  选择控件渲染
            $(formId + ' [data-select]').each(function(){ $(formId + ' [name="' + $(this).attr('name') + '"]').select2({dropdownAutoWidth:true, width:'100%'});});
            //  表单提交搜索;
            $('body').on('submit', formId, function(){ this.refresh({pageNumber:1}); }.bind(this));
            //  增加参数
            this.options.queryParams = function(data){
                var searchs = search();
                for(const key in searchs){
                    if(searchs.hasOwnProperty(key) && searchs[key] === '')
                        delete searchs[key];
                }
                data[this.options.searchList] = searchs;
                return data;
            }.bind(this);
            //  数据加载完毕
            eval(this.options.namespace).onDraw = function(data){};
            $('body').on('load-success.bs.table', function(e, data){ if(data) eval(this.options.namespace).onDraw(data); }.bind(this));
            //  获取多选
            eval(this.options.namespace).getBatch = function(){return $('#' + this.options.id).bootstrapTable('getSelections');}.bind(this);
            eval(this.options.namespace).getUrl = function(action = 'null'){
                this.refresh({silent:true, query:{ignore:true, [this.options.actionName]:action}});
                return eval(this.options.namespace).builder[0].url;
            }.bind(this);
            //  接管请求
            eval(this.options.namespace).ajax = function(data){
                var parm = JSON.parse(data.data);
                if(parm.ignore)
                    eval(this.options.namespace).builder[0].url = $.MainApp.form.url(data.url, parm);
                else
                    $.ajax(data);
            }.bind(this);
        }
    };
});
