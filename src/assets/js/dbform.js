$.validator.addMethod('function', function(value, element, params){return params;}, $.validator.messages.remote);
(function($){
    $.fn.serializeObject = function(){
        var serializeObj = {};
        var temp = this.serializeArray();
        var not_checked_object = $('input[type=checkbox]:not(:checked)', this);
        $.each(not_checked_object, function(){temp.push({name:this.name, value:""});});
        var not_radio_object = $('input[type=radio]:not(:checked)', this);
        $.each(not_radio_object, function(){if(!$('input[type=radio][name=' + this.name + ']:checked').val()) temp.push({name:this.name, value:""});});
        var selects_object = $('select[multiple]', this);
        $.each(selects_object, function(){
            var value = $(this).val();
            if(!value.length) temp.push({name:this.name, value:[]});
            else if(value.length == 1) $.each(temp, function(i, v){if(v.name == this.name) v.value = [v.value];}.bind(this));
        });
        $(temp).each(function(){
            if(serializeObj[this.name]){
                if($.isArray(serializeObj[this.name])) serializeObj[this.name].push(this.value);
                else serializeObj[this.name] = [serializeObj[this.name], this.value];
            }else serializeObj[this.name] = this.value;
        });
        $.each(serializeObj, function(i, v){
            var n = i, a = false;
            if(/\[\]$/i.test(i)){
                a = true;
                n = i.replace(/\[\]$/i, "");
            }
            if($.isArray(v)){
                var f = [];
                $.each(v, function(k, s){if(s !== '') f.push(s);});
                serializeObj[n] = f;
            }
            if(a){
                delete serializeObj[i];
                serializeObj[n] = serializeObj[n] || [];
            }
        });
        return serializeObj;
    };
    $.extend($, {
        dbSelect:function(selector, options){
            //  组合参数
            options = $.extend(options, {dropdownAutoWidth:true, width:'100%'});
            options.theme = options.color || 'primary';
            return $(selector).select2(options);
        },
        dbCheck:function(selector, options){
            //  组合参数
            options = $.extend(options, {checkboxClass:'icheckbox_square-blue'});
            options.color = options.color || 'primary';
            options.checkboxClass += ' fa icheckbox_color-' + options.color;
            return $(selector).iCheck(options);
        },
        dbSwitch:function(selector, options){
            //  组合参数
            options = $.extend(options, {baseClass:'bootstrap-switch', wrapperClass:['circle']});
            options.color = options.color || $.fn.bootstrapSwitch.defaults.onColor;
            options.wrapperClass.push(options.color);
            switch(options.size){
                case 'small':
                    options.labelWidth = 'calc(0.85rem / 1.2 * 2)';
                    options.handleWidth = 'calc(0.85rem / 1.2 * 1.6)';
                    break;
                case 'large':
                    options.labelWidth = 'calc(0.85rem * 1.2 * 2)';
                    options.handleWidth = 'calc(0.85rem * 1.2 * 1.6)';
                    break;
                default:
                    options.labelWidth = 'calc(0.85rem * 2)';
                    options.handleWidth = 'calc(0.85rem * 1.6)';
                    break;
            }
            return $(selector).bootstrapSwitch(options);
        },
        dbRadio:function(selector, options){
            //  组合参数
            options = $.extend(options, {radioClass:'iradio_square-blue'});
            options.color = options.color || 'primary';
            options.radioClass += ' fa iradio_color-' + options.color;
            return $(selector).iCheck(options);
        },
        dbDate:function(selector, options){
            //  组合参数
            options = options || {};
            options.format = options.format || 'YYYY-MM-DD HH:mm:ss';
            options.showTodayButton = options.showTodayButton || true;
            options.showClear = options.showClear || true;
            options.showClose = options.showClose || true;
            options.icons = options.icons || {
                time:'far fa-clock',
                date:'far fa-calendar',
                up:'fas fa-arrow-up',
                down:'fas fa-arrow-down',
                previous:'fas fa-chevron-left',
                next:'fas fa-chevron-right',
                today:'far fa-calendar-check',
                clear:'far fa-trash-alt',
                close:'far fa-times-circle'
            };
            options = $.extend(options, {});
            return $(selector).datetimepicker(options);
        },
        dbColor:function(selector, options){
            //  组合参数
            options = $.extend(options, {addon:'.color-addon'});
            $(selector).find(options.addon).addClass('colorpicker-input-addon').append('<i></i>');
            return $(selector).colorpicker(options);
        },
        dbFile:function(selector, options){
            //  组合参数
            options = $.extend(options, {});
            $(selector).filestyle(options);
            return $(selector).parent().find('.bootstrap-filestyle .group-span-filestyle').removeClass('input-group-btn').addClass('input-group-append');
        },
        dbSlider:function(selector, options){
            //  组合参数
            options = $.extend(options, {});
            options.skin = options.color || 'primary';
            options.min = options.min || 0;
            options.input_values_separator = options.input_values_separator || ',';
            return $(selector).ionRangeSlider(options);
        },
        dbForm:function(selector, options){
            //  选项
            options = options || {rules:{}, message:{}, commit:function(){}, list:{}, debug:false};
            options.list = options.list || {};
            options.rules = options.rules || {};
            options.messages = options.messages || {};
            options.errors = options.errors || true;
            //  回调
            options.callback = options.callback || {complete:null, right:null, fail:null, build:null};
            options.callback.complete = options.callback.complete || null;
            options.callback.success = options.callback.success || null;
            options.callback.fail = options.callback.fail || null;
            options.callback.build = options.callback.build || null;
            //  列表拆解
            if(Object.keys(options.list).length){
                $.each(options.list, function(i, v){
                    //	初始化对象
                    options.rules[i] = options.rules[i] || {};
                    options.messages[i] = options.messages[i] || {};
                    //  增加其他规则
                    if(typeof (v) == 'object'){
                        $.each(v, function(k, s){
                            options.rules[i].required = typeof (v.required) != 'undefined' ? v.required : true;
                            if(typeof (s) != 'object' || typeof (s.rule) == 'undefined') s = {rule:s};
                            options.rules[i][k] = typeof (s.rule) == 'undefined' ? true : s.rule;
                            if(typeof (s.message) == 'string'){
                                options.messages[i][k] = s.message;
                                if(typeof (v.required) == 'undefined') options.messages[i].required = s.message;
                            }
                        });
                    }else options.rules[i].required = v;
                });
            }
            //  组合参数
            var data = $.extend({
                //  错误内容输出
                errorPlacement:function(error, element){
                    if(element.is(':radio') || element.is(':checkbox')) error.appendTo(element.parent().parent().parent());
                    else error.appendTo(element.parent());
                }
            }, options, {
                //  错误样式类名
                errorClass:'has-error',
                errorElement:'label',
                //  没有表面错误，进行下一轮过滤
                submitHandler:function(form){
                    //  提交前验证
                    if(typeof (options.commit) == 'function' && options.commit(self) === false){
                        //  函数通用回调（不分成功失败）
                        if(typeof (options.callback.complete) === 'function') options.callback.complete(self);
                        //  错误回调
                        if(typeof (options.callback.fail) === 'function') options.callback.fail({elem:form, message:null, type:'other'});
                        //  返回
                        return false;
                    }
                    //  通过表单验证之后是直接执行回调还是调试输出
                    if(options.debug){
                        if(typeof (options.debug) == 'function') options.debug(self);
                        else console.debug(self);
                    }else{
                        //  函数通用回调（不分成功失败）
                        if(typeof (options.callback.complete) === 'function') options.callback.complete(self);
                        //  成功回调
                        if(typeof (options.callback.success) === 'function') options.callback.success(self);
                    }
                }
            });
            //  不处理错误
            if(!options.errors){
                $.extend(data, {
                    onfocusout:false,
                    onkeyup:false,
                    onclick:false,
                    showErrors:function(errorMap, errorList){
                        $.each(errorList, function(i, v){
                            //  函数通用回调（不分成功失败）
                            if(typeof (options.callback.complete) === 'function') options.callback.complete(self);
                            //  错误回调
                            if(v.message){
                                if(typeof (options.callback.fail) === 'function') options.callback.fail({elem:v.element, message:v.message, type:v.method});
                                else alert(v.message);
                            }
                            //  跳出
                            return false;
                        });
                    }
                });
            }
            //  组合信息
            var self = {selector:selector, options:options, validate:$(selector).validate(data), value:function(){ return $(selector).serializeObject(); }};
            if(self.validate && typeof (options.callback.build) === 'function') options.callback.build(self);
            return self.validate;
        }
    });
})(jQuery);
