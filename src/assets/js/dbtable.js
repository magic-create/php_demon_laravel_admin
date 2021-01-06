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
        var value = function(element){
            var value = {};
            $.each($(element).serializeArray(), function(){
                if(value[this['name']]){
                    if($.isArray(value[this['name']])) value[this['name']].push(this['value']);
                    else value[this['name']] = [value[this['name']], this['value']];
                }else value[this['name']] = this['value'];
            });
            return value;
        };
        var search = function(){ return value('#' + this.options.searchForm); }.bind(this);
        var url = function(url, parm){ return url + (url.search(/\?/) === -1 ? '?' : '&') + decodeURIComponent($.param(parm)); };
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
            $(formId + ' [data-time]').each(function(){$.dbDate(formId + ' [name="' + $(this).attr('name') + '"]', {format:$(this).data('format')});});
            //  选择控件渲染
            $(formId + ' [data-select]').each(function(){$.dbSelect(formId + ' [name="' + $(this).attr('name') + '"]', {dropdownAutoWidth:true, width:'100%'});});
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
            eval(this.options.namespace).getUrl = function(action = 'null', parm = {}){
                this.refresh({silent:true, query:Object.assign({ignore:true, [this.options.actionName]:action}, parm)});
                return eval(this.options.namespace).builder[0].url;
            }.bind(this);
            //  接管请求
            eval(this.options.namespace).ajax = function(data){
                var parm = JSON.parse(data.data);
                if(parm.ignore) eval(this.options.namespace).builder[0].url = url(data.url, parm);
                else $.ajax(data);
            }.bind(this);
        }
    };
});
