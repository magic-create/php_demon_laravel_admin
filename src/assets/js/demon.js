layer.config({skin:'primary', shade:0.5, resize:false, scrollbar:false});
$.validator.addMethod('function', function(value, element, params){return params;}, $.validator.messages.remote);
!function($){
    "use strict";
    var modaler = {
        options:{
            id:'',
            title:'',
            content:'',
            className:'',
            width:'',
            height:'',
            size:'',
            target:'body',
            closeBtn:true,
            shadeClose:true,
            escape:false,
            autoFocus:true,
            show:true,
            shade:true,
            fade:true,
            isCenter:false,
            btns:[],
            onShowStart:function(){},
            onShowEnd:function(){},
            onHideStart:function(){},
            onHideEnd:function(){},
            onClose:function(){},
            lang:{
                info:'Info',
                alert:'Alert',
                confirm:'Confirm',
                prompt:'Prompt',
                ok:'OK',
                cancel:'Cancel'
            }
        },
        open:function(options){
            options = $.extend({}, modaler.options, options);
            var element = options.id !== '' ? $('#' + options.id) : undefined;
            if(element){
                element.modal('hide');
                element = null;
            }
            if(!element || !element.length) element = $('<div class="modal-admin modal ' + (options.fade ? 'fade' : '') + '" tabindex="-1" role="dialog" data-backdrop="'.concat(options.shade ? (options.shadeClose ? true : 'static') : false, '"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div></div>'));
            var $body = element.find('.modal-body');
            options.title = typeof (options.lang[options.title]) == 'string' ? options.lang[options.title] : options.title;
            if(options.closeBtn || options.title){
                var $header = $('<div class="modal-header"></div>');
                if(options.title) $header.append('<h5 class="modal-title">'.concat(options.title, '</h5>'));
                if(options.closeBtn) $header.append('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                $body.before($header);
            }
            if(options.btns.length){
                var $footer = $('<div class="modal-footer"></div>');
                options.btns.forEach(function(btn){
                    btn = $.extend(true, {label:'Button', className:'btn-primary', onClick:function onClick(callback){}}, btn);
                    var button = $('<button type="button" class="btn ' + btn.className + '">' + (options.lang[btn.label] || btn.label) + '</button>');
                    button.on('click', function(event){
                        event.hide = function(){element.modal('hide');};
                        if(btn.onClick(event) !== false) element.modal('hide');
                    });
                    $footer.append(button);
                });
                $body.after($footer);
            }
            if(typeof options.content === 'string') $body.html(options.content);
            else if(typeof (options.content) === 'object'){
                $body.empty();
                $(options.content).contents().appendTo($body);
            }
            options.id && element.attr('id', options.id);
            options.className && element.addClass(options.className);
            options.width && element.find('.modal-dialog').width(options.width).css('max-width', options.width);
            options.height && element.find('.modal-dialog').height(options.height);
            options.size && element.find('.modal-dialog').addClass('modal-' + options.size);
            options.isCenter && element.find('.modal-dialog').addClass('modal-dialog-centered');
            element.on('show.bs.modal', options.onShowStart);
            element.on('shown.bs.modal', options.onShowEnd);
            element.on('hide.bs.modal', options.onHideStart);
            element.on('hidden.bs.modal', function(){
                element.modal('dispose').remove();
                options.onHideEnd();
            });
            options.closeBtn && element.find('.close').on('click', function(){
                element.modal('hide');
                return options.onClose();
            });
            $(options.target).append(element);
            element.modal({backdrop:options.shade ? (options.shadeClose ? true : 'static') : false, keyboard:options.escape, focus:options.autoFocus, show:options.show});
            return element;
        },
        close:function(e){ $(e).modal('hide'); },
        closeAll:function(){
            $('.modal-admin[role="dialog"]').modal('hide');
        },
        alert:function(content, options, yes){
            var type = typeof options === 'function';
            if(type) yes = options;
            return modaler.open($.extend(true, {
                title:'alert',
                content:content,
                btns:[{label:'ok', onClick(){if(typeof (yes) == 'function') return yes();}}]
            }, type ? {} : options));
        },
        confirm:function(content, options, yes, cancel){
            var type = typeof options === 'function';
            if(type){
                cancel = yes;
                yes = options;
            }
            return modaler.open($.extend(true, {
                title:'confirm',
                content:content,
                btns:[
                    {label:'ok', onClick(){if(typeof (yes) == 'function') return yes();}},
                    {label:'cancel', className:'btn-secondary', onClick(){if(typeof (cancel) == 'function') return cancel();}}
                ]
            }, type ? {} : options));
        },
        prompt:function(options, yes, cancel){
            options = options || {};
            if(typeof options === 'function'){
                cancel = yes;
                yes = options;
            }
            options.content = $('<div>'.concat(options.content ? '<h6>' + options.content + '</h6>' : '', '<form><input type="text" class="form-control" value="'.concat(options.value || '', '"/></form></div>')));
            var form = options.content.find('form'), input = options.content.find('input');
            return modaler.open($.extend(true, {
                title:'prompt',
                btns:[
                    {label:'ok', onClick(){if(typeof (yes) == 'function') return yes(input.val());}},
                    {label:'cancel', className:'btn-secondary', onClick(){if(typeof (cancel) == 'function') return cancel();}}
                ]
            }, options, {
                onShowEnd:function(){
                    input.focus();
                    form.on('submit', function(){
                        $(this).modal('hide');
                        if(typeof (yes) == 'function') return yes(input.val());
                    }.bind(this));
                    if(typeof (options.onShowEnd) == 'function') options.onShowEnd();
                }
            }));
        }
    };
    var alert = {
        options:{
            //  primary, secondary, success, danger, warning, info, light, dark
            type:'primary',
            //  c, t, r, b , l, lt, lb, rt, rb
            pos:'rt',
            //  append, prepend
            appendType:'append',
            closeBtn:false,
            content:'',
            time:2000,
            zIndex:20210108,
            className:'',
            onClose:function(){},
            onClosed:function(){}
        },
        open:function(options){
            options = $.extend({}, alert.options, options);
            options.pos = options.pos || 'rt';
            var container = $('#popper-alert-' + options.pos);
            if(!container.length){
                container = $('<div id="popper-alert-' + options.pos + '" class="popper-alert" style="z-index:' + options.zIndex + '"></div>');
                $('body').append(container);
            }
            var element = $('<div class="alert fade alert-' + options.type + '" role="alert">' + options.content + '</div>');
            if(options.time){
                element.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>').addClass('alert-dismissible');
                setTimeout(function(){element.alert('close');}, options.time);
            }
            options.appendType === 'append' ? container.append(element) : container.prepend(element);
            setTimeout(function(){element.addClass('show');}, 50);
            element.on('close.bs.alert', options.onClose);
            element.on('closed.bs.alert', options.onClosed);
            return element;
        },
        close:function(e){
            return $(e).alert('close');
        },
        closeAll:function(){
            return $('.popper-alert .alert').alert('close');
        },
        primary:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.open($.extend(true, {
                type:'primary',
                content:content,
                onClose:callback
            }, type ? {} : options));
        },
        secondary:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.primary(content, $.extend(true, {type:'secondary'}, type ? {} : options), callback);
        },
        success:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.primary(content, $.extend(true, {type:'success'}, type ? {} : options), callback);
        },
        danger:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.primary(content, $.extend(true, {type:'danger'}, type ? {} : options), callback);
        },
        warning:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.primary(content, $.extend(true, {type:'warning'}, type ? {} : options), callback);
        },
        info:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.primary(content, $.extend(true, {type:'info'}, type ? {} : options), callback);
        },
        light:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.primary(content, $.extend(true, {type:'light'}, type ? {} : options), callback);
        },
        dark:function(content, options, callback){
            var type = typeof options === 'function';
            if(type) callback = options;
            return alert.primary(content, $.extend(true, {type:'dark'}, type ? {} : options), callback);
        }
    };
    $.extend({bootstrapModaler:modaler, bootstrapAlert:alert});
}(jQuery);
(function($){
    //  禁用表单自动提交
    $('.dataTables_wrapper').on('submit,reset', 'form', function(){
        if($(this).attr('action') == undefined){
            return false;
        }
    });
    //  获取表单内容
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
    //  扩展工具
    var BootstrapTable = $.fn.bootstrapTable.Constructor,
        _initHeader = BootstrapTable.prototype.initHeader,
        _initToolbar = BootstrapTable.prototype.initToolbar,
        _load = BootstrapTable.prototype.load,
        _initSearch = BootstrapTable.prototype.initSearch;
    //  初始化工具栏
    BootstrapTable.prototype.initToolbar = function(){
        var self = this;
        _initToolbar.apply(self, Array.prototype.slice.apply(arguments));
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
        var search = function(){ return value('#' + self.options.searchForm); };
        var url = function(url, parm){ return url + (url.search(/\?/) === -1 ? '?' : '&') + decodeURIComponent($.param(parm)); };
        //  扩展高级搜索
        if(self.options.searchPanel){
            var html = sprintf('<div class="columns columns-%s float-%s">', self.options.buttonsAlign, self.options.buttonsAlign);
            html += sprintf('<button class="btn btn-%s" type="button" name="%s" title="%s">', self.options.buttonsClass, self.options.searchTemplate, self.options.formatAdvancedSearch());
            html += sprintf('<i class="%s %s"></i>', self.options.iconsPrefix, self.options.icons.search);
            html += '</button></div>';
            if(self.$toolbar.find(".float-right").length > 0) $(html).insertBefore(self.$toolbar.find(".float-right:first"));
            else self.$toolbar.append(html);
            //  搜索表单
            var formId = '#' + self.options.searchForm;
            var template = $('#' + self.options.searchTemplate).prop('outerHTML');
            $('#' + self.options.searchTemplate).remove();
            self.$toolbar.before(template);
            //  面板展开或隐藏
            setTimeout(function(){ $('body').on('click', '[name="' + self.options.searchTemplate + '"]', function(){$('#' + self.options.searchTemplate).toggleClass('d-block'); });}, 0);
            //  是否默认展开
            if(self.options.searchPanelOpen) $('#' + self.options.searchTemplate).addClass('d-block');
            //  时间控件渲染
            $(formId + ' [data-time]').each(function(){$.admin.date(formId + ' [name="' + $(this).attr('name') + '"]', {format:$(this).data('time')});});
            //  选择控件渲染
            $(formId + ' [data-select]').each(function(){$.admin.select(formId + ' [name="' + $(this).attr('name') + '"]', {dropdownAutoWidth:true, width:'100%'});});
            $('#' + self.options.searchTemplate + '_button button[type="submit"]').html(self.options.searchSubmitText || 'Submit');
            $('#' + self.options.searchTemplate + '_button button[type="reset"]').html(self.options.searchResetText || 'Reset');
            //  创建好面板事件
            $(formId).trigger('search:created', [{$elem:$(formId), table:self}]);
            //  表单提交搜索;
            $('body').on('submit', formId, function(){
                $(formId).trigger('search:submit', [{$elem:$(formId), table:self}]);
                self.refresh({pageNumber:1});
            });
            //  增加参数
            self.options.queryParams = function(data){
                var searchs = search();
                for(const key in searchs){
                    if(searchs.hasOwnProperty(key) && searchs[key] === '')
                        delete searchs[key];
                }
                data[self.options.searchList] = searchs;
                return data;
            };
        }
        //  数据加载完毕
        eval(self.options.namespace).onDraw = function(data){};
        $('body').on('load-success.bs.table', function(e, data){
            $('#' + self.options.id + ' [data-toggle="tooltip"]' + ',' + self.options.toolbar + ' [data-toggle="tooltip"]').tooltip();
            //  关于开关的特殊处理
            $.admin.switch('#' + self.options.id + ' [data-bind="switch"]', {
                size:'small', onSwitchChange:function(e, o){
                    if(self.options.uniqueId){
                        var d = eval(self.options.namespace).getData($(this).closest('tr[data-uniqueid]').data('uniqueid'));
                        $(this).trigger('row:' + self.options.actionEvent, [{
                            $elem:$(this),
                            action:$(this).attr('action'),
                            row:d.row || {},
                            switch:o,
                            index:d.index || false
                        }]);
                    }
                }
            });
            //  关于图片的特殊处理
            $('#' + self.options.id + ' [data-bind="image"]').on('click', function(e){
                var name = $(this).attr('name') || '';
                $.admin.layer.photos({anim:5, photos:{title:name, data:[{alt:name, pid:0, src:$(this).attr('src')}]}});
            });
            if(data) eval(self.options.namespace).onDraw(data);
        });
        //  工具事件
        $('body').off('click').on('click', self.options.toolbar + ' [data-button-key]', function(){ $(this).trigger('toolbar:' + self.options.actionEvent, [{$elem:$(this), action:$(this).data('button-key')}]);});
        //  行内事件
        eval(self.options.namespace + '.' + self.options.actionEvent)['click [action]'] = function(e, v, r, i){
            var target = e.target;
            if(!$(e.target).attr('action')) target = $(e.target).closest('[action]');
            $(target).trigger('row:' + self.options.actionEvent, [{$elem:$(target), action:$(target).attr('action'), row:r, index:i}]);
        };
        //  获取多选
        eval(self.options.namespace).getBatch = function(key){
            var data = $('#' + self.options.id).bootstrapTable('getSelections');
            if(key){
                var uniqueIds = [];
                if(self.options.uniqueId) $.each(data, function(index, item){uniqueIds.push(item[self.options.uniqueId]);});
                return uniqueIds;
            }else return data;
        };
        eval(self.options.namespace).getUrl = function(action = 'null', parm = {}){
            self.refresh({silent:true, query:Object.assign({ignore:true, [self.options.actionName]:action}, parm)});
            return eval(self.options.namespace).builder[0].url;
        };
        eval(self.options.namespace).getData = function(key, type = 'key'){
            if(self.options.uniqueId){
                if(type == 'key'){
                    var row = $('#' + self.options.id).bootstrapTable('getRowByUniqueId', key);
                    var index = $('#' + self.options.id).find('tr[data-uniqueid="' + key + '"]').data('index');
                }else{
                    var row = $('#' + self.options.id).bootstrapTable('getRowByUniqueId', $('#' + self.options.id).find('tr[data-index="' + key + '"]').data('uniqueid'));
                    var index = key;
                }
                return {row:row, index:index};
            }else return null;
        };
        //  接管请求
        eval(self.options.namespace).ajax = function(data){
            var parm = JSON.parse(data.data);
            if(parm.ignore) eval(self.options.namespace).builder[0].url = url(data.url, parm);
            else $.ajax(data);
        };
    };
    //  扩展Layer上传头像
    layer.avatar = function(cropper, options, yes){
        cropper = cropper || [];
        var ready = cropper.ready || function(){};
        cropper.placeholder = cropper.placeholder || 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4Ij48cGF0aCBkPSJNMTAyNCA1MTJDMTAyNCAyMzAuNCA3OTMuNiAwIDUxMiAwUzAgMjMwLjQgMCA1MTJzMjMwLjQgNTEyIDUxMiA1MTIgNTEyLTIzMC40IDUxMi01MTJ6TTUxMiA5NjBDMjYyLjQgOTYwIDY0IDc2MS42IDY0IDUxMlMyNjIuNCA2NCA1MTIgNjRzNDQ4IDE5OC40IDQ0OCA0NDgtMTk4LjQgNDQ4LTQ0OCA0NDh6IiBmaWxsPSIjMDAwMDAwIiBvcGFjaXR5PSIwLjEiPjwvcGF0aD48cGF0aCBkPSJNNjI3LjIgNTA1LjZDNjcyIDQ2Ny4yIDcwNCA0MTYgNzA0IDM1MmMwLTEwOC44LTgzLjItMTkyLTE5Mi0xOTJzLTE5MiA4My4yLTE5MiAxOTJjMCA2NCAzMiAxMTUuMiA3Ni44IDE1My42QzI5NC40IDU1MC40IDIyNCA2NTIuOCAyMjQgNzY4YzAgMTkuMiAxMi44IDMyIDMyIDMyczMyLTEyLjggMzItMzJjMC0xMjEuNiAxMDIuNC0yMjQgMjI0LTIyNHMyMjQgMTAyLjQgMjI0IDIyNGMwIDE5LjIgMTIuOCAzMiAzMiAzMnMzMi0xMi44IDMyLTMyYzAtMTE1LjItNzAuNC0yMTcuNi0xNzIuOC0yNjIuNHpNNTEyIDQ4MGMtNzAuNCAwLTEyOC01Ny42LTEyOC0xMjhzNTcuNi0xMjggMTI4LTEyOCAxMjggNTcuNiAxMjggMTI4LTU3LjYgMTI4LTEyOCAxMjh6IiBmaWxsPSIjMDAwMDAwIiBvcGFjaXR5PSIwLjEiPjwvcGF0aD48L3N2Zz4=';
        cropper.image = cropper.image || cropper.placeholder;
        cropper.lock = cropper.lock || false;
        var rand = 'cropper-avatar-' + (new Date).getTime() + '' + parseInt(Math.random() * 1e5) + 1;
        var html = '<div class="row cropper-avatar" id="' + rand + '"><div class="col-sm-9 cropper-left"><div class="cropper-box"><img class="cropper" src="' + cropper.image + '"><div class="mt-2 cropper-tool"><button type="button" class="btn btn-sm btn-secondary" data-action="file"><i class="mb-0 fa fa-image"></i></button>\n<input type="file" style="display:none" accept=".png,.jpg,.jpeg,.gif,.webp,image/png,image/jpg,image/jpeg,image/gif,image/webp"><button type="button" class="btn btn-sm btn-secondary" data-action="reset"><i class="mb-0 fa fa-sync"></i></button>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="zoom+"><i class="mb-0 fa fa-search-plus"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="zoom-"><i class="mb-0 fa fa-search-minus"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_left"><i class="mb-0 fa fa-undo"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_right"><i class="mb-0 fa fa-redo"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="move_up"><i class="mb-0 fa fa-arrow-up"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_down"><i class="mb-0 fa fa-arrow-down"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_left"><i class="mb-0 fa fa-arrow-left"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_right"><i class="mb-0 fa fa-arrow-right"></i></button></div>\n</div></div></div><div class="col cropper-right pl-sm-2"><div class="row no-gutters"><div class="cropper-preview"></div><div class="cropper-preview rounded ml-3 ml-sm-0 mt-sm-2"></div><div class="cropper-preview rounded-circle ml-3 ml-sm-0 mt-sm-2"></div></div></div></div>';
        cropper = $.extend({viewMode:1, toggleDragModeOnDblclick:false, aspectRatio:1}, cropper, {
            preview:'#' + rand + ' .cropper-preview', ready:function(e){
                ready(e);
                var self = avatarLayer.cropper;
                var vessel = $('#' + rand);
                var fileInput = vessel.find('input[type="file"]');
                var isImageFile = function(file){
                    if(file.type) return /^image\/\w+$/.test(file.type);
                    else return /\.(jpg|jpeg|png|gif|webp)$/.test(file);
                };
                (vessel).on('click', 'button', function(){
                    switch($(this).data('action')){
                        case 'file':
                            fileInput.trigger('click');
                            fileInput.on('change', function(){
                                var fileList = $(this).prop('files');
                                if(typeof (fileList) == 'object' && fileList.length > 0){
                                    var fileContent = fileList[0];
                                    if(isImageFile(fileContent)){
                                        if(this.url) URL.revokeObjectURL(this.url);
                                        this.url = URL.createObjectURL(fileContent);
                                        self.replace(this.url);
                                    }
                                }
                            });
                            break;
                        case 'reset':
                            self.reset();
                            break;
                        case 'zoom+':
                            self.zoom(0.25);
                            break;
                        case 'zoom-':
                            self.zoom(-0.25);
                            break;
                        case 'rotate_left':
                            self.rotate(-18);
                            break;
                        case 'rotate_right':
                            self.rotate(18);
                            break;
                        case 'move_up':
                            self.move(0, 5);
                            break;
                        case 'move_down':
                            self.move(0, -5);
                            break;
                        case 'move_left':
                            self.move(-5, 0);
                            break;
                        case 'move_right':
                            self.move(5, 0);
                            break;
                        default:
                            break;
                    }
                });
            }
        });

        var avatarLayer = null;
        options = options || {};
        if(typeof options === 'function') yes = options;
        var success = options.success || function(){};
        var size = window.innerWidth < 600;
        options = $.extend({area:[size ? '100%' : '600px', size ? '100%' : 'auto'], title:'avatar'}, options, {
            success:function(layero, index){
                if(cropper.lock) $(layero).find('[data-action="file"]').remove();
                success(layero, index);
                avatarLayer = layero;
                avatarLayer.cropper = new Cropper($('#' + rand + ' .cropper')[0], cropper);
                avatarLayer.cropper.selectImage = function(){ $('#' + rand).find('input[type="file"]').trigger('click'); };
            },
            yes:function(index, layero){
                if(typeof (yes) == 'function') return yes(index, layero);
            }
        });

        return layer.confirm(html, options);
    };
    //  扩展Layer图片裁剪
    layer.cropper = function(cropper, options, yes){
        cropper = cropper || [];
        var ready = cropper.ready || function(){};
        cropper.placeholder = cropper.placeholder || 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEyNjIgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4Ij48cGF0aCBkPSJNMTE3MS42ODY0IDBDMTIyMi4wMzMwNjcgMCAxMjYyLjk5MzA2NyAzOS43MzEyIDEyNjIuOTkzMDY3IDg4LjU0MTg2N3Y4NDYuOTE2MjY2QzEyNjIuOTkzMDY3IDk4NC4yNjg4IDEyMjIuMDMzMDY3IDEwMjQgMTE3MS42ODY0IDEwMjRIOTEuMzY2NGMtNy45MTg5MzMgMC0xNS41OTg5MzMtMC45ODk4NjctMjIuOTAzNDY3LTIuODMzMDY3QTgzLjkzMzg2NyA4My45MzM4NjcgMCAwIDEgMC4wNTk3MzMgOTM1LjQ1ODEzM1Y4OC41NDE4NjdDMC4wNTk3MzMgMzkuNzMxMiA0MS4wMTk3MzMgMCA5MS4zNjY0IDBoMTA4MC4zMnogbS01NC4yNzIgMTEzLjIyMDI2N0gxNDIuMDIwMjY3Yy0xNS45NDAyNjcgMC0yOC45NDUwNjcgMTIuMjg4LTI4Ljk0NTA2NyAyNy4yNzI1MzNsLTAuMDM0MTMzIDY0NC40MzczMzMgMjI0LjMyNDI2Ni0yNDUuMDc3MzMzYzMzLjk5NjgtMzcuMTM3MDY3IDkzLjU1OTQ2Ny0zNy4xMzcwNjcgMTI3LjY1ODY2NyAwbDIyNy44MDU4NjcgMjQ4LjU5MzA2NyAyMDMuNzQxODY2LTI1MS42OTkyYzI2LjcyNjQtMzMuMDA2OTMzIDczLjU5MTQ2Ny0zMy4wMDY5MzMgMTAwLjMxNzg2NyAwbDE0OS40Njk4NjcgMTg0LjU1ODkzM1YxNDAuNDkyOGMwLTE1LjAxODY2Ny0xMy4wMDQ4LTI3LjI3MjUzMy0yOC45NDUwNjctMjcuMjcyNTMzeiBtLTM1Ni42OTMzMzMgNzYuNDkyOGExNDQuNjU3MDY3IDE0NC42NTcwNjcgMCAwIDEgMTQzLjQ5NjUzMyAwIDE0MS45OTQ2NjcgMTQxLjk5NDY2NyAwIDAgMSA3MS43NDgyNjcgMTIzLjE4NzIgMTQxLjk5NDY2NyAxNDEuOTk0NjY3IDAgMCAxLTcxLjc0ODI2NyAxMjMuMTUzMDY2IDE0NC42NTcwNjcgMTQ0LjY1NzA2NyAwIDAgMS0xNDMuNTMwNjY3IDAgMTQxLjk5NDY2NyAxNDEuOTk0NjY3IDAgMCAxLTcxLjc0ODI2Ni0xMjMuMTUzMDY2IDE0MS45OTQ2NjcgMTQxLjk5NDY2NyAwIDAgMSA3MS43NDgyNjYtMTIzLjE4NzJ6IiBmaWxsPSIjMDAwMDAwIiBvcGFjaXR5PSIwLjEiPjwvcGF0aD48L3N2Zz4=';
        cropper.image = cropper.image || cropper.placeholder;
        cropper.lock = typeof (cropper.lock) != 'undefined' ? cropper.lock : true;
        cropper.base64 = cropper.base64 || false;
        cropper.aspectRatio = cropper.aspectRatio || null;
        if(typeof (cropper.aspectRatio) != 'number')
            delete cropper.aspectRatio;
        var rand = 'cropper-image-' + (new Date).getTime() + '' + parseInt(Math.random() * 1e5) + 1;
        var html = '<div class="cropper-image" id="' + rand + '"><div class="cropper-box"><img class="cropper" src="' + cropper.image + '"><div class="mt-2 cropper-tool"><button type="button" class="btn btn-sm btn-secondary" data-action="file"><i class="mb-0 fa fa-image"></i></button>\n<input type="file" style="display:none" accept=".png,.jpg,.jpeg,.gif,.webp,image/png,image/jpg,image/jpeg,image/gif,image/webp"><button type="button" class="btn btn-sm btn-secondary" data-action="reset"><i class="mb-0 fa fa-sync"></i></button>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="zoom+"><i class="mb-0 fa fa-search-plus"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="zoom-"><i class="mb-0 fa fa-search-minus"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_left"><i class="mb-0 fa fa-undo"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_right"><i class="mb-0 fa fa-redo"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="move_up"><i class="mb-0 fa fa-arrow-up"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_down"><i class="mb-0 fa fa-arrow-down"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_left"><i class="mb-0 fa fa-arrow-left"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_right"><i class="mb-0 fa fa-arrow-right"></i></button></div>\n</div></div></div>';
        cropper = $.extend({toggleDragModeOnDblclick:false}, cropper, {
            ready:function(e){
                ready(e);
                var self = avatarLayer.cropper;
                var vessel = $('#' + rand);
                var fileInput = vessel.find('input[type="file"]');
                var isImageFile = function(file){
                    if(file.type) return /^image\/\w+$/.test(file.type);
                    else return /\.(jpg|jpeg|png|gif|webp)$/.test(file);
                };
                (vessel).on('click', 'button', function(){
                    switch($(this).data('action')){
                        case 'file':
                            fileInput.trigger('click');
                            fileInput.on('change', function(){
                                var fileList = $(this).prop('files');
                                if(typeof (fileList) == 'object' && fileList.length > 0){
                                    var fileContent = fileList[0];
                                    if(isImageFile(fileContent)){
                                        if(this.url) URL.revokeObjectURL(this.url);
                                        this.url = URL.createObjectURL(fileContent);
                                        self.replace(this.url);
                                    }
                                }
                            });
                            break;
                        case 'reset':
                            self.reset();
                            break;
                        case 'zoom+':
                            self.zoom(0.25);
                            break;
                        case 'zoom-':
                            self.zoom(-0.25);
                            break;
                        case 'rotate_left':
                            self.rotate(-18);
                            break;
                        case 'rotate_right':
                            self.rotate(18);
                            break;
                        case 'move_up':
                            self.move(0, 5);
                            break;
                        case 'move_down':
                            self.move(0, -5);
                            break;
                        case 'move_left':
                            self.move(-5, 0);
                            break;
                        case 'move_right':
                            self.move(5, 0);
                            break;
                        default:
                            break;
                    }
                });
            }
        });

        var avatarLayer = null;
        options = options || {};
        if(typeof options === 'function') yes = options;
        var success = options.success || function(){};
        var size = window.innerWidth < 600;
        options = $.extend({area:[size ? '100%' : '500px', size ? '100%' : '500px'], title:'cropper'}, options, {
            success:function(layero, index){
                if(cropper.lock) $(layero).find('[data-action="file"]').remove();
                success(layero, index);
                avatarLayer = layero;
                avatarLayer.cropper = new Cropper($('#' + rand + ' .cropper')[0], cropper);
                avatarLayer.cropper.selectImage = function(){ $('#' + rand).find('input[type="file"]').trigger('click'); };
            },
            yes:function(index, layero){
                if(typeof (yes) == 'function') return yes(index, layero);
            }
        });

        return layer.confirm(html, options);
    };
    //  扩展Layer图片选择
    layer.image = function(choose, options, yes){
        choose = choose || [];
        var change = choose.change || function(){};
        choose.loading = choose.loading || 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4Ij48cGF0aCBkPSJNNDg3LjM2MTMwNSAxOTMuNzU2ODYzaC00Ni4xODAzOTJ2MzU2LjM5MjE1N2wzMzEuMjk0MTE4IDE2OC42NTg4MjMgMjEuMDgyMzUzLTQxLjE2MDc4NC0zMDcuMi0xNTUuNjA3ODQzdi0zMjguMjgyMzUzeiBtNDQyLjcyOTQxMiAxMjcuNDk4MDM5bDQyLjE2NDcwNi0xOC4wNzA1ODhjLTQuMDE1Njg2LTkuMDM1Mjk0LTguMDMxMzczLTE4LjA3MDU4OC0xMy4wNTA5ODEtMjcuMTA1ODgzbC00MS4xNjA3ODQgMjAuMDc4NDMyYzQuMDE1Njg2IDkuMDM1Mjk0IDguMDMxMzczIDE3LjA2NjY2NyAxMi4wNDcwNTkgMjUuMDk4MDM5eiBtMzcuMTQ1MDk4IDE0OS41ODQzMTRsNDYuMTgwMzkyLTMuMDExNzY1Yy0xLjAwMzkyMi0xMC4wMzkyMTYtMi4wMDc4NDMtMjEuMDgyMzUzLTMuMDExNzY1LTMxLjEyMTU2OWwtNDUuMTc2NDcgNi4wMjM1M2MxLjAwMzkyMiAxMC4wMzkyMTYgMi4wMDc4NDMgMTkuMDc0NTEgMi4wMDc4NDMgMjguMTA5ODA0eiBtLTExMS40MzUyOTQtMzMwLjI5MDE5NmwtMzEuMTIxNTY5IDM0LjEzMzMzM2M2LjAyMzUyOSA2LjAyMzUyOSAxMy4wNTA5OCAxMi4wNDcwNTkgMTkuMDc0NTEgMTkuMDc0NTFsMzQuMTMzMzMzLTMxLjEyMTU2OWMtNi4wMjM1MjktOC4wMzEzNzMtMTMuMDUwOTgtMTUuMDU4ODI0LTIyLjA4NjI3NC0yMi4wODYyNzR6IG05Ni4zNzY0NyA0ODQuODk0MTE3bDQ0LjE3MjU0OSAxNC4wNTQ5MDJjMy4wMTE3NjUtMTAuMDM5MjE2IDYuMDIzNTI5LTIwLjA3ODQzMSA3LjAyNzQ1MS0zMS4xMjE1NjhsLTQ1LjE3NjQ3LTkuMDM1Mjk1Yy0yLjAwNzg0MyA5LjAzNTI5NC00LjAxNTY4NiAxOC4wNzA1ODgtNi4wMjM1MyAyNi4xMDE5NjF6IG0tMzYwLjQwNzg0My02MjEuNDI3NDUxYy0xMC4wMzkyMTYtMS4wMDM5MjItMjAuMDc4NDMxLTIuMDA3ODQzLTI5LjExMzcyNS0zLjAxMTc2NGgtMS4wMDM5MjJsLTQuMDE1Njg2IDQ1LjE3NjQ3aDEuMDAzOTIxYzkuMDM1Mjk0IDEuMDAzOTIyIDE4LjA3MDU4OCAyLjAwNzg0MyAyNy4xMDU4ODMgMi4wMDc4NDMgMCAxLjAwMzkyMiA2LjAyMzUyOS00NC4xNzI1NDkgNi4wMjM1MjktNDQuMTcyNTQ5eiBtMTQ5LjU4NDMxNCA1Ny4yMjM1M2MtOS4wMzUyOTQtNC4wMTU2ODYtMTguMDcwNTg4LTkuMDM1Mjk0LTI3LjEwNTg4Mi0xMi4wNDcwNTlsLTEuMDAzOTIyLTEuMDAzOTIyLTE4LjA3MDU4OCA0Mi4xNjQ3MDYgMS4wMDM5MjEgMS4wMDM5MjJjOS4wMzUyOTQgMy4wMTE3NjUgMTcuMDY2NjY3IDcuMDI3NDUxIDI1LjA5ODA0IDExLjA0MzEzN2wyMC4wNzg0MzEtNDEuMTYwNzg0eiBtMTYyLjYzNTI5NCA2NzguNjUwOThjLTMuMDExNzY1IDMuMDExNzY1LTEuMDAzOTIyIDIuMDA3ODQzLTIuMDA3ODQzIDQuMDE1Njg2bC0xLjAwMzkyMiAxLjAwMzkyMmMtNS4wMTk2MDggOS4wMzUyOTQtMTAuMDM5MjE2IDE2LjA2Mjc0NS0xNC4wNTQ5MDIgMjMuMDkwMTk2bDM3LjE0NTA5OCAyNy4xMDU4ODJjNi4wMjM1MjktOC4wMzEzNzMgMTEuMDQzMTM3LTE2LjA2Mjc0NSAxNy4wNjY2NjctMjYuMTAxOTYgMS4wMDM5MjItMS4wMDM5MjIgMS4wMDM5MjItMS4wMDM5MjIgMS4wMDM5MjItMi4wMDc4NDRsLTEuMDAzOTIyIDEuMDAzOTIyLTM3LjE0NTA5OC0yOC4xMDk4MDR6IiBmaWxsPSIjYmZiZmJmIiBwLWlkPSIxNTIwMCI+PC9wYXRoPjxwYXRoIGQ9Ik00MTMuMDcxMTA5IDIyLjMyNzIxNmMtOTUuMzcyNTQ5IDIxLjA4MjM1My0xODEuNzA5ODA0IDY4LjI2NjY2Ny0yNTAuOTgwMzkyIDEzOC41NDExNzZhNTE5LjgzMDU4OCA1MTkuODMwNTg4IDAgMCAwLTE0Mi41NTY4NjMgMjY2LjAzOTIxNmwtMS4wMDM5MjEgMi4wMDc4NDNjLTExLjA0MzEzNyA1OC4yMjc0NTEtMTIuMDQ3MDU5IDExNi40NTQ5MDItMi4wMDc4NDQgMTczLjY3ODQzMSAyMS4wODIzNTMgMTMyLjUxNzY0NyA5My4zNjQ3MDYgMjQ2Ljk2NDcwNiAyMDIuNzkyMTU3IDMyMy4yNjI3NDUgMTA5LjQyNzQ1MSA3Ni4yOTgwMzkgMjQxLjk0NTA5OCAxMDQuNDA3ODQzIDM3NC40NjI3NDUgODEuMzE3NjQ4IDE4LjA3MDU4OC0zLjAxMTc2NSAzNi4xNDExNzYtNy4wMjc0NTEgNTIuMjAzOTIyLTEyLjA0NzA1OWw5LjAzNTI5NC0yLjAwNzg0MyAxLjAwMzkyMi0xLjAwMzkyMmM3LjAyNzQ1MS0yLjAwNzg0MyAxMy4wNTA5OC01LjAxOTYwOCAyMC4wNzg0MzEtNy4wMjc0NTFoMS4wMDM5MjJsNC4wMTU2ODYtMi4wMDc4NDNjNDEuMTYwNzg0LTE1LjA1ODgyNCA4MC4zMTM3MjUtMzYuMTQxMTc2IDExNS40NTA5OC02MC4yMzUyOTRsLTI2LjEwMTk2MS0zOC4xNDkwMmMtMzAuMTE3NjQ3IDIxLjA4MjM1My02NC4yNTA5OCA0MC4xNTY4NjMtMTAwLjM5MjE1NiA1My4yMDc4NDNoLTYuMDIzNTNsLTMuMDExNzY1IDMuMDExNzY1Yy03LjAyNzQ1MSAyLjAwNzg0My0xNC4wNTQ5MDIgNS4wMTk2MDgtMjEuMDgyMzUyIDcuMDI3NDUxbC02LjAyMzUzIDEuMDAzOTIyLTEuMDAzOTIxIDEuMDAzOTIxYy0xNC4wNTQ5MDIgNC4wMTU2ODYtMjkuMTEzNzI1IDcuMDI3NDUxLTQ1LjE3NjQ3MSAxMC4wMzkyMTYtMTIwLjQ3MDU4OCAyMS4wODIzNTMtMjQwLjk0MTE3Ni01LjAxOTYwOC0zMzkuMzI1NDktNzMuMjg2Mjc1LTk5LjM4ODIzNS02OC4yNjY2NjctMTYzLjYzOTIxNi0xNzIuNjc0NTEtMTgyLjcxMzcyNi0yOTIuMTQxMTc2LTkuMDM1Mjk0LTUyLjIwMzkyMi04LjAzMTM3My0xMDQuNDA3ODQzIDIuMDA3ODQzLTE1Ny42MTU2ODZsMS4wMDM5MjItMi4wMDc4NDRjMTguMDcwNTg4LTkyLjM2MDc4NCA2Mi4yNDMxMzctMTc1LjY4NjI3NSAxMjkuNTA1ODgyLTI0Mi45NDkwMTkgNjMuMjQ3MDU5LTYzLjI0NzA1OSAxNDIuNTU2ODYzLTEwNi40MTU2ODYgMjI4Ljg5NDExOC0xMjUuNDkwMTk2bC0xNC4wNTQ5MDItNDQuMTcyNTQ5eiIgZmlsbD0iIzAwMDAwMCIgb3BhY2l0eT0iMC4xIj48L3BhdGg+PC9zdmc+';
        choose.placeholder = choose.placeholder || 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEyNjIgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4Ij48cGF0aCBkPSJNMTE3MS42ODY0IDBDMTIyMi4wMzMwNjcgMCAxMjYyLjk5MzA2NyAzOS43MzEyIDEyNjIuOTkzMDY3IDg4LjU0MTg2N3Y4NDYuOTE2MjY2QzEyNjIuOTkzMDY3IDk4NC4yNjg4IDEyMjIuMDMzMDY3IDEwMjQgMTE3MS42ODY0IDEwMjRIOTEuMzY2NGMtNy45MTg5MzMgMC0xNS41OTg5MzMtMC45ODk4NjctMjIuOTAzNDY3LTIuODMzMDY3QTgzLjkzMzg2NyA4My45MzM4NjcgMCAwIDEgMC4wNTk3MzMgOTM1LjQ1ODEzM1Y4OC41NDE4NjdDMC4wNTk3MzMgMzkuNzMxMiA0MS4wMTk3MzMgMCA5MS4zNjY0IDBoMTA4MC4zMnogbS01NC4yNzIgMTEzLjIyMDI2N0gxNDIuMDIwMjY3Yy0xNS45NDAyNjcgMC0yOC45NDUwNjcgMTIuMjg4LTI4Ljk0NTA2NyAyNy4yNzI1MzNsLTAuMDM0MTMzIDY0NC40MzczMzMgMjI0LjMyNDI2Ni0yNDUuMDc3MzMzYzMzLjk5NjgtMzcuMTM3MDY3IDkzLjU1OTQ2Ny0zNy4xMzcwNjcgMTI3LjY1ODY2NyAwbDIyNy44MDU4NjcgMjQ4LjU5MzA2NyAyMDMuNzQxODY2LTI1MS42OTkyYzI2LjcyNjQtMzMuMDA2OTMzIDczLjU5MTQ2Ny0zMy4wMDY5MzMgMTAwLjMxNzg2NyAwbDE0OS40Njk4NjcgMTg0LjU1ODkzM1YxNDAuNDkyOGMwLTE1LjAxODY2Ny0xMy4wMDQ4LTI3LjI3MjUzMy0yOC45NDUwNjctMjcuMjcyNTMzeiBtLTM1Ni42OTMzMzMgNzYuNDkyOGExNDQuNjU3MDY3IDE0NC42NTcwNjcgMCAwIDEgMTQzLjQ5NjUzMyAwIDE0MS45OTQ2NjcgMTQxLjk5NDY2NyAwIDAgMSA3MS43NDgyNjcgMTIzLjE4NzIgMTQxLjk5NDY2NyAxNDEuOTk0NjY3IDAgMCAxLTcxLjc0ODI2NyAxMjMuMTUzMDY2IDE0NC42NTcwNjcgMTQ0LjY1NzA2NyAwIDAgMS0xNDMuNTMwNjY3IDAgMTQxLjk5NDY2NyAxNDEuOTk0NjY3IDAgMCAxLTcxLjc0ODI2Ni0xMjMuMTUzMDY2IDE0MS45OTQ2NjcgMTQxLjk5NDY2NyAwIDAgMSA3MS43NDgyNjYtMTIzLjE4NzJ6IiBmaWxsPSIjMDAwMDAwIiBvcGFjaXR5PSIwLjEiPjwvcGF0aD48L3N2Zz4=';
        choose.image = choose.image || '';
        choose.local = typeof (choose.local) != 'undefined' ? choose.local : true;
        choose.dump = choose.dump || '';
        choose.dispose = typeof (choose.dispose) != 'undefined' ? choose.dispose : true;
        choose.quality = choose.quality || 0.9;
        choose.aspectRatio = typeof (choose.aspectRatio) != 'undefined' ? choose.aspectRatio : true;
        choose.width = choose.width || 0;
        choose.height = choose.height || 0;
        choose.maxWidth = choose.maxWidth || Infinity;
        choose.maxHeight = choose.maxHeight || Infinity;
        choose.minWidth = choose.minWidth || 0;
        choose.minHeight = choose.minHeight || 0;
        choose.type = choose.type || '';
        choose.base64 = choose.base64 || false;
        choose.cropper = choose.cropper || false;
        choose.uploader = choose.uploader || null;
        var appendClass = [!choose.local ? 'hidden-local' : '', !choose.dump ? 'hidden-url' : ''];
        var html = '<div class="layer-image-select ' + appendClass.join(' ') + '"><div class="form-group"><div class="preview"><div class="alert alert-danger" style="position:absolute;right:10px;display:none" role="alert"></div><img src="' + (choose.image || choose.placeholder) + '"><i></i></div></div><div class="form-group input-file"><label>' + ($.bootstrapModaler.options.lang.file || 'Select File') + '</label><input type="file" name="file" class="form-control file" accept=".png,.jpg,.jpeg,.gif,.webp,image/png,image/jpg,image/jpeg,image/gif,image/webp"></div><form class="form-group input-url"><label>' + ($.bootstrapModaler.options.lang.imageUrl || 'Image Url') + '</label><div class="input-group"><input type="url" name="url" class="form-control" value="' + choose.image + '"><span class="input-group-append"><button type="reset" class="btn btn-secondary">' + ($.bootstrapModaler.options.lang.reset || 'Reset') + '</button><button type="button" class="btn btn-secondary url-dump">' + ($.bootstrapModaler.options.lang.imageDump || 'Image Dump') + '</button></span></form></div></div>';
        var imageLayer, imagePreview, imageAlert;
        options = options || {};
        if(typeof options === 'function') yes = options;
        var success = options.success || function(){};
        var size = window.innerWidth < 600;
        var error = function(status){
            imageLayer.preview = '';
            if(status) imagePreview.attr('src', choose[status]);
            imageAlert.html($.bootstrapModaler.options.lang.imageNone || 'Image None').show().fadeOut(2000);
        };
        options = $.extend({area:size ? '100%' : '600px', title:'image'}, options, {
            success:function(layero, index){
                success(layero, index);
                imageLayer = layero;
                imageLayer.preview = '';
                imageLayer.name = '';
                imageLayer.canvas = null;
                imageLayer.result = null;
                imagePreview = $(layero).find('img');
                imageAlert = $(layero).find('.alert');
                var fileInput = $(layero).find('input[type="file"]');
                var urlInput = $(layero).find('input[name="url"]');
                var urlDump = $(layero).find('button.url-dump');
                var src, data, canvas, mime;
                var isImageFile = function(file){
                    if(file.type) return /^image\/\w+$/.test(file.type);
                    else return /\.(jpg|jpeg|png|gif|webp)$/.test(file);
                };
                if(choose.type && !isImageFile('.' + choose.type)) choose.type = 'jpeg';
                var dump = function(url){
                    imageLayer.preview = '';
                    imagePreview.attr('src', choose.loading);
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', choose.dump + (choose.dump.indexOf('?') < 0 ? '?' : '&') + 'url=' + url);
                    xhr.responseType = 'blob';
                    xhr.onload = function(){
                        imageLayer.result = xhr.response;
                        mime = xhr.getResponseHeader('Content-Type');
                        imageLayer.data = {type:'url', value:url, mime:choose.type || mime, quality:choose.quality};
                        src = URL.createObjectURL(imageLayer.result);
                        imageLayer.name = url.replace(/(.*\/)*([^.]+)/i, '$2');
                        fileInput.val('');
                        dispose();
                    };
                    xhr.onerror = function(){
                        urlInput.val('');
                        error('placeholder');
                    };
                    xhr.onloadend = function(){
                        if(xhr.status != 200){
                            urlInput.val('');
                            error('placeholder');
                        }
                    };
                    xhr.send();
                };
                var preview = function(obj){
                    imageLayer.result = data = obj;
                    imageLayer.preview = typeof (data) == 'object' ? URL.createObjectURL(data) : data;
                    imagePreview.attr('src', imageLayer.preview);
                };
                var dispose = function(){
                    var type = typeof (choose.dispose);
                    if(type == 'boolean' && choose.dispose){
                        var img = new Image;
                        canvas = document.createElement('canvas');
                        var drawer = canvas.getContext('2d');
                        img.src = src;
                        img.onerror = function(){error('placeholder');};
                        img.onload = function(){
                            //  获取纵横比
                            var aspectRatio = img.width / img.height;
                            //  获取最终宽度
                            var width = Math.max(Math.min(choose.width ? choose.width : img.width, choose.maxWidth), choose.minWidth);
                            //  限制最大高度
                            var height = Math.min(choose.height ? choose.height : img.height, choose.maxHeight);
                            //  需要保持比例
                            if(choose.aspectRatio)
                                height = typeof (choose.aspectRatio) == 'boolean' ? width / aspectRatio : width * choose.aspectRatio;
                            //  限制最低高度
                            height = Math.max(height, choose.minHeight);
                            //  设置画布尺寸
                            canvas.width = width;
                            canvas.height = height;
                            drawer.drawImage(img, 0, 0, canvas.width, canvas.height);
                            if(choose.base64){
                                if(choose.type || mime) preview(canvas.toDataURL((choose.type ? 'image/' + choose.type : mime), choose.quality));
                                else preview(canvas.toDataURL());
                            }else{
                                if(choose.type || mime) canvas.toBlob(preview, (choose.type ? 'image/' + choose.type : mime), choose.quality);
                                else canvas.toBlob(preview);
                            }
                            imageLayer.canvas = canvas;
                        };
                    }else if(type == 'function') preview(choose.dispose(imageLayer.result));
                    else preview(imageLayer.result);
                };
                if(choose.image && choose.image != choose.placeholder && choose.dump) dump(choose.image);
                if(choose.local){
                    fileInput.filestyle();
                    $(layero).find('.bootstrap-filestyle .group-span-filestyle').removeClass('input-group-btn').addClass('input-group-append');
                    fileInput.on('change', function(){
                        var fileList = $(this).prop('files');
                        if(typeof (fileList) == 'object' && fileList.length > 0){
                            imageLayer.preview = '';
                            imagePreview.attr('src', choose.loading);
                            var fileContent = fileList[0];
                            if(isImageFile(fileContent)){
                                if(this.url) URL.revokeObjectURL(this.url);
                                this.url = URL.createObjectURL(fileContent);
                                imageLayer.result = fileContent;
                                mime = fileContent.type;
                                imageLayer.data = {type:'file', value:fileContent, mime:choose.type || mime, quality:choose.quality};
                                src = this.url;
                                imageLayer.name = fileContent.name;
                                urlInput.val('');
                                dispose();
                            }else error('placeholder');
                        }
                    });
                }else $(layero).find('.input-file').remove();
                if(choose.dump){
                    urlDump.on('click', function(){ dump(urlInput.val()); });
                }else $(layero).find('.input-url').remove();
            },
            yes:function(index, layero){
                if(layero.preview){
                    if(choose.cropper){
                        $.admin.layer.cropper(
                            $.extend({viewMode:1}, choose, typeof (choose.cropper) == 'object' ? choose.cropper : {}, {image:layero.preview, lock:true}),
                            function(index, layero){
                                if(typeof (yes) == 'function'){
                                    layero.name = imageLayer.name;
                                    layero.data = imageLayer.data;
                                    return yes(index, layero);
                                }
                            }
                        );
                        $.admin.layer.close(index);
                    }else if(typeof (yes) == 'function') return yes(index, layero);
                }else error();
            }
        });

        return layer.open($.extend({btn:['ok', 'cancel']}, options, {content:html}));
    };
    //  扩展方法
    $.extend($, {
        admin:{
            layer:layer,
            modal:$.bootstrapModaler,
            alert:$.bootstrapAlert,
            api:{
                fail:function(xhr, callback = true, alert = true){
                    var code = (xhr.responseJSON && xhr.responseJSON.code || null) ? xhr.responseJSON.code : xhr.status;
                    code = code || (xhr.code || 400);
                    var message = (xhr.responseJSON && xhr.responseJSON.message || null) ? xhr.responseJSON.message : xhr.statusText;
                    message = message || (typeof (xhr) == 'string' ? xhr : (xhr.message || ''));
                    var info = $.extend({code:0, message:''}, xhr.responseJSON || {}, {code:code, message:message});
                    console.info(info);
                    if(typeof (callback) != 'function'){
                        alert = callback;
                        callback = null;
                    }
                    if(callback) return callback(info, this);
                    $.admin.layer.closeAll('loading');
                    if(alert) $.admin.alert.danger(message, {pos:(typeof (alert) == 'string' && alert.length <= 2) ? alert : null});
                },
                open:function(title, url, options, yes, report){
                    options = options || {};
                    if(typeof options === 'function'){
                        report = yes;
                        yes = options;
                    }
                    var success = options.success || function(){};
                    options.success = function(layero, index){
                        layero.body = layer.getChildFrame('body', index);
                        layero.iframe = window[layero.find('iframe')[0]['name']];
                        layero.body.find('a').remove();
                        layero.body.find('footer').remove();
                        success(layero, index);
                    };
                    options.yes = function(index, layero){
                        yes(index, layero);
                    };
                    var btn = options.btn || (report ? ['ok', 'cancel'] : ['ok']);
                    var size = window.innerWidth < 960;
                    var open = $.admin.layer.open($.extend({title:title, type:2, maxmin:true, resize:true, area:[size ? '100%' : '960px', size ? '100%' : '720px'], content:url, btn:btn}, options));
                    if(report){
                        $.admin.layer.report = $.admin.layer.report || {};
                        $.admin.layer.report['layui-layer-iframe' + open] = report;
                    }
                    return open;
                },
                upload:function(url, data, callback){
                    var formData = new FormData();
                    var blob = data.blob || null;
                    delete data.blob;
                    if(!(blob instanceof Blob))
                        return $.admin.api.fail({code:400, message:$.bootstrapModaler.options.lang.fileNone || 'File None'});
                    var name = data.name || '';
                    delete data.name;
                    var key = data.key || 'file';
                    delete data.key;
                    $.each(data, function(k, v){ formData.append(k, v);});
                    formData.append(key, blob, name);
                    $.ajax({type:'POST', url:url, data:formData, processData:false, contentType:false, success:function(data){ callback(data); }}).fail($.admin.api.fail);
                },
                report:function(data, status, xhr){
                    if(typeof (status) == 'string' && typeof (xhr) == 'string'){
                        status = false;
                        xhr = data;
                    }else status = true;
                    if(typeof (data) != 'object' || Object.prototype.toString.call(data).toLowerCase() != '[object object]'){
                        status = false;
                        xhr = $.extend(xhr, {status:510, statusText:'Not a JSON'});
                    }
                    if(parent.$.admin.layer.report && parent.$.admin.layer.report[window.name]){
                        var res = parent.$.admin.layer.report[window.name](parent.$.admin.layer.getFrameIndex(window.name), {data:data, status:status, xhr:xhr});
                        if(res) delete parent.$.admin.layer.report[window.name];
                        return res;
                    }
                }
            },
            select:function(selector, options){
                //  组合参数
                options = $.extend(options, {dropdownAutoWidth:true, width:'100%'});
                options.theme = options.color || 'primary';
                return $(selector).select2(options);
            },
            checkbox:function(selector, options){
                //  组合参数
                options = $.extend(options, {checkboxClass:'icheckbox_square-blue'});
                options.color = options.color || 'primary';
                options.checkboxClass += ' fa icheckbox_color-' + options.color;
                return $(selector).iCheck(options);
            },
            switch:function(selector, options){
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
            radio:function(selector, options){
                //  组合参数
                options = $.extend(options, {radioClass:'iradio_square-blue'});
                options.color = options.color || 'primary';
                options.radioClass += ' fa iradio_color-' + options.color;
                return $(selector).iCheck(options);
            },
            date:function(selector, options){
                //  组合参数
                options = options || {};
                options.format = options.format || 'YYYY-MM-DD HH:mm:ss';
                options.showTodayButton = typeof (options.showTodayButton) != 'undefined' ? options.showTodayButton : true;
                options.showClear = typeof (options.showClear) != 'undefined' ? options.showClear : true;
                options.showClose = typeof (options.showClose) != 'undefined' ? options.showClose : true;
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
            color:function(selector, options){
                //  组合参数
                options = $.extend(options, {addon:'.color-addon'});
                $(selector).find(options.addon).addClass('colorpicker-input-addon').append('<i></i>');
                return $(selector).colorpicker(options);
            },
            file:function(selector, options){
                //  组合参数
                options = $.extend(options, {});
                $(selector).filestyle(options);
                return $(selector).parent().find('.bootstrap-filestyle .group-span-filestyle').removeClass('input-group-btn').addClass('input-group-append');
            },
            slider:function(selector, options){
                //  组合参数
                options = $.extend(options, {});
                options.skin = options.color || 'primary';
                options.min = options.min || 0;
                options.input_values_separator = options.input_values_separator || ',';
                return $(selector).ionRangeSlider(options);
            },
            editor:function(selector, options){
                //  组合参数
                options = $.extend({
                    branding:false,
                    statusbar:false,
                    convert_urls:false,
                    toolbar_mode:'floating',
                    plugins:'preview fullscreen lists advlist table autolink link dimage hr',
                    menubar:'file edit insert format table',
                    toolbar:'styleselect | bullist numlist outdent indent | dimage | preview fullscreen',
                    contextmenu:'',
                    table_responsive_width:true,
                    readonly:$(selector).attr('readonly') || $(selector).attr('disabled')
                }, options || {});
                var setup = options.setup || function(){};
                options.setup = function(editor){
                    editor.on('change', function(){ editor.save(); });
                    setup(editor);
                };
                return tinymce.init($.extend({selector:selector}, options));
            },
            form:function(selector, options){
                //  选项
                options = options || {rules:{}, message:{}, commit:function(){}};
                options.list = options.list || {};
                options.rules = options.rules || {};
                options.messages = options.messages || {};
                options.errors = typeof (options.errors) != 'undefined' ? options.errors : true;
                options.debug = options.debug || false;
                options.protect = options.protect || 500;
                //  回调
                options.callback = options.callback || {complete:null, right:null, fail:null, build:null};
                options.callback.complete = options.callback.complete || null;
                options.callback.success = options.callback.success || null;
                options.callback.fail = options.callback.fail || null;
                options.callback.build = options.callback.build || null;
                options.render = options.render || false;
                if(options.render){
                    $(selector).find('.radio').each(function(k, v){ $.admin.radio(v, $(v).attr('disabled', $(v).attr('disabled') || $(v).attr('readonly')).data());});
                    $(selector).find('.switch').each(function(k, v){ $.admin.switch(v, $(v).attr('disabled', $(v).attr('disabled') || $(v).attr('readonly')).data());});
                    $(selector).find('.checkbox').each(function(k, v){ $.admin.checkbox(v, $(v).data());});
                    $(selector).find('.select').each(function(k, v){ $.admin.select(v, $(v).attr('disabled', $(v).attr('disabled') || $(v).attr('readonly')).data());});
                    $(selector).find('.color').each(function(k, v){ $.admin.color(v, $(v).attr('disabled', $(v).attr('disabled') || $(v).attr('readonly')).data());});
                    $(selector).find('.file').each(function(k, v){ $.admin.file(v, $(v).attr('disabled', $(v).attr('disabled') || $(v).attr('readonly')).data());});
                    $(selector).find('.slider').each(function(k, v){ $.admin.slider(v, $.extend({disable:$(v).attr('disabled') || $(v).attr('readonly')}, $(v).data()));});
                    $(selector).find('.date').each(function(k, v){ $.admin.date(v, $(v).data());});
                }
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
                            //  判断短时拦截器
                            var time = (new Date()).getTime();
                            if(time - ($(form).data('lastSubmitTime') || 0) >= options.protect){
                                //  设置短时拦截器
                                $(form).data('lastSubmitTime', time);
                                //  成功回调
                                if(typeof (options.callback.success) === 'function') options.callback.success(self);
                            }else console.warn('The submit interval is too short');
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
            },
            cropper:function(selector, options){
                //  没有选择器则直接返回
                if(!selector)
                    return false;
                //  循环匹配选择器
                var list = [];
                $(selector).each(function(i, v){
                    //  非有效标签则不处理
                    if(!v || !/^img|canvas$/i.test(v.tagName))
                        return true;
                    list.push(v.cropper = new Cropper(v, options));
                });
                //  单条数据返回值否则返回数组
                return list.length == 0 ? false : (list.length == 1 ? list[0] : list);
            }
        }
    });
})(jQuery);
