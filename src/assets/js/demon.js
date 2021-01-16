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
            pos:'t',
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
            //  面板展开或隐藏
            $('body').on('click', '[name="' + self.options.searchTemplate + '"]', function(){ $('#' + self.options.searchTemplate).toggleClass('d-block'); });
            //  是否默认展开
            if(self.options.searchPanelOpen) $('#' + self.options.searchTemplate).addClass('d-block');
            //  时间控件渲染
            $(formId + ' [data-time]').each(function(){$.admin.date(formId + ' [name="' + $(this).attr('name') + '"]', {format:$(this).data('time')});});
            //  选择控件渲染
            $(formId + ' [data-select]').each(function(){$.admin.select(formId + ' [name="' + $(this).attr('name') + '"]', {dropdownAutoWidth:true, width:'100%'});});
            $('#' + self.options.searchTemplate + '_button button[type="submit"]').html(self.options.searchSubmitText || 'Submit');
            $('#' + self.options.searchTemplate + '_button button[type="reset"]').html(self.options.searchResetText || 'Reset');
            //  表单提交搜索;
            $('body').on('submit', formId, function(){ self.refresh({pageNumber:1}); });
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
        eval(self.options.namespace).getBatch = function(){return $('#' + self.options.id).bootstrapTable('getSelections');};
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
        cropper.placeholder = cropper.placeholder || 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAAtJREFUCNdjIBEAAAAwAAFletZ8AAAAAElFTkSuQmCC';
        cropper.image = cropper.image || cropper.placeholder;
        cropper.lock = cropper.lock || true;
        var rand = 'cropper-avatar-' + (new Date).getTime() + '' + parseInt(Math.random() * 1e5) + 1;
        var html = '<div class="row cropper-avatar" id="' + rand + '"><div class="col-sm-9 cropper-left"><div class="cropper-box"><img class="cropper" src="' + cropper.image + '"><div class="m-t-2 cropper-tool"><button type="button" class="btn btn-sm btn-secondary" data-action="file"><i class="m-b-0 fa fa-image"></i></button>\n<input type="file" style="display:none" accept=".png,.jpg,.jpeg,.gif,.webp,image/png,image/jpg,image/jpeg,image/gif,image/webp"><button type="button" class="btn btn-sm btn-secondary" data-action="reset"><i class="m-b-0 fa fa-sync"></i></button>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="zoom+"><i class="m-b-0 fa fa-search-plus"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="zoom-"><i class="m-b-0 fa fa-search-minus"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_left"><i class="m-b-0 fa fa-undo"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_right"><i class="m-b-0 fa fa-redo"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="move_up"><i class="m-b-0 fa fa-arrow-up"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_down"><i class="m-b-0 fa fa-arrow-down"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_left"><i class="m-b-0 fa fa-arrow-left"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_right"><i class="m-b-0 fa fa-arrow-right"></i></button></div>\n</div></div></div><div class="col cropper-right p-l-sm-2"><div class="row no-gutters"><div class="cropper-preview"></div><div class="cropper-preview rounded m-l-3 m-l-sm-0 m-t-sm-2"></div><div class="cropper-preview rounded-circle m-l-3 m-l-sm-0 m-t-sm-2"></div></div></div></div>';
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
        options = $.extend({area:window.innerWidth < 600 ? '95%' : '600px', title:'avatar'}, options, {
            success:function(layero, index){
                if(cropper.lock) $(layero).find('[data-action="file"]').remove();
                success(layero, index);
                avatarLayer = layero;
                avatarLayer.cropper = new Cropper($('#' + rand + ' .cropper')[0], cropper);
                avatarLayer.cropper.selectImage = function(){ $('#' + rand).find('input[type="file"]').trigger('click'); };
            },
            yes:function(index, layero){
                yes(index, layero);
            }
        });

        return layer.confirm(html, options);
    };
    //  扩展Layer图片裁剪
    layer.cropper = function(cropper, options, yes){
        cropper = cropper || [];
        var ready = cropper.ready || function(){};
        cropper.placeholder = cropper.placeholder || 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAAtJREFUCNdjIBEAAAAwAAFletZ8AAAAAElFTkSuQmCC';
        cropper.image = cropper.image || cropper.placeholder;
        cropper.lock = cropper.lock || true;
        var rand = 'cropper-image-' + (new Date).getTime() + '' + parseInt(Math.random() * 1e5) + 1;
        var html = '<div class="cropper-image" id="' + rand + '"><div class="cropper-box"><img class="cropper" src="' + cropper.image + '"><div class="m-t-2 cropper-tool"><button type="button" class="btn btn-sm btn-secondary" data-action="file"><i class="m-b-0 fa fa-image"></i></button>\n<input type="file" style="display:none" accept=".png,.jpg,.jpeg,.gif,.webp,image/png,image/jpg,image/jpeg,image/gif,image/webp"><button type="button" class="btn btn-sm btn-secondary" data-action="reset"><i class="m-b-0 fa fa-sync"></i></button>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="zoom+"><i class="m-b-0 fa fa-search-plus"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="zoom-"><i class="m-b-0 fa fa-search-minus"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_left"><i class="m-b-0 fa fa-undo"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="rotate_right"><i class="m-b-0 fa fa-redo"></i></button></div>\n<div class="btn-group"><button type="button" class="btn btn-sm btn-secondary" data-action="move_up"><i class="m-b-0 fa fa-arrow-up"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_down"><i class="m-b-0 fa fa-arrow-down"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_left"><i class="m-b-0 fa fa-arrow-left"></i></button><button type="button" class="btn btn-sm btn-secondary" data-action="move_right"><i class="m-b-0 fa fa-arrow-right"></i></button></div>\n</div></div></div>';
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
        options = $.extend({area:[window.innerWidth < 600 ? '95%' : '500px', '500px'], title:'cropper'}, options, {
            success:function(layero, index){
                if(cropper.lock) $(layero).find('[data-action="file"]').remove();
                success(layero, index);
                avatarLayer = layero;
                avatarLayer.cropper = new Cropper($('#' + rand + ' .cropper')[0], cropper);
                avatarLayer.cropper.selectImage = function(){ $('#' + rand).find('input[type="file"]').trigger('click'); };
            },
            yes:function(index, layero){
                yes(index, layero);
            }
        });

        return layer.confirm(html, options);
    };
    //  扩展Layer图片选择
    layer.image = function(choose, options, yes){
        choose = choose || [];
        var change = choose.change || function(){};
        choose.placeholder = choose.placeholder || 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTAgMGgxMDB2MTAwSDB6Ii8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTlFOUU5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTMwKSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iIzk4OTY5NyIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgzMCAxMDUuOTggNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjOUI5OTlBIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDYwIDc1Ljk4IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0EzQTFBMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSg5MCA2NSA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNBQkE5QUEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoMTIwIDU4LjY2IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0IyQjJCMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgxNTAgNTQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjQkFCOEI5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDE4MCA1MCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDMkMwQzEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTE1MCA0NS45OCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDQkNCQ0IiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTEyMCA0MS4zNCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNEMkQyRDIiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTkwIDM1IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0RBREFEQSIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgtNjAgMjQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTJFMkUyIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKC0zMCAtNS45OCA2NSkiLz48L3N2Zz4=';
        choose.image = choose.image || '';
        choose.local = choose.local || true;
        choose.dump = choose.dump || '';
        choose.dispose = choose.dispose || true;
        choose.quality = choose.quality || 0.9;
        choose.force = choose.force || false;
        choose.scale = choose.scale || false;
        choose.width = choose.width || 0;
        choose.height = choose.height || 0;
        choose.type = choose.type || '';
        var appendClass = [!choose.local ? 'hidden-local' : '', !choose.dump ? 'hidden-url' : ''];
        var html = '<div class="layer-image-select ' + appendClass.join(' ') + '"><div class="form-group"><div class="preview"><img src="' + (choose.image || choose.placeholder) + '"><i></i></div></div><div class="form-group input-file"><label>' + ($.bootstrapModaler.options.lang.file || 'Select File') + '</label><input type="file" name="file" class="form-control file" accept=".png,.jpg,.jpeg,.gif,.webp,image/png,image/jpg,image/jpeg,image/gif,image/webp"></div><div class="form-group input-url"><label>' + ($.bootstrapModaler.options.lang.imageUrl || 'Image Url') + '</label><div class="input-group"><input type="url" name="url" class="form-control" value="' + choose.image + '"><span class="input-group-append"><button type="reset" class="btn btn-secondary">' + ($.bootstrapModaler.options.lang.reset || 'Reset') + '</button><button type="button" class="btn btn-secondary url-dump">' + ($.bootstrapModaler.options.lang.imageDump || 'Image Dump') + '</button></span></div></div></div>';
        var imageLayer = null;
        options = options || {};
        if(typeof options === 'function') yes = options;
        var success = options.success || function(){};
        options = $.extend({area:[window.innerWidth < 600 ? '95%' : '600px'], title:'image'}, options, {
            success:function(layero, index){
                success(layero, index);
                imageLayer = layero;
                var imagePreview = $(layero).find('img');
                imageLayer.preview = imagePreview, imageLayer.canvas;
                var fileInput = $(layero).find('input[type="file"]');
                var urlInput = $(layero).find('input[name="url"]');
                var urlDump = $(layero).find('button.url-dump');
                var file, src, data, canvas, mime;
                var isImageFile = function(file){
                    if(file.type) return /^image\/\w+$/.test(file.type);
                    else return /\.(jpg|jpeg|png|gif|webp)$/.test(file);
                };
                if(choose.type && !isImageFile('.' + choose.type)) choose.type = 'jpeg';
                var dump = function(url){
                    imagePreview.attr('src', choose.placeholder);
                    $.get(choose.dump, {url:url}, function(data, status, xhr){
                        src = this.url;
                        fileInput.val('');
                        imageLayer.data = {type:'url', value:url};
                        mime = xhr.getResponseHeader('Content-Type');
                        dispose();
                    }).fail(function(e){
                        urlInput.val('');
                    });
                };
                var dispose = function(){
                    var type = typeof (choose.dispose);
                    if(type != 'function'){
                        var img = new Image;
                        canvas = document.createElement('canvas');
                        var drawer = canvas.getContext('2d');
                        img.src = src;
                        img.onload = function(){
                            canvas.width = choose.width ? (choose.force ? choose.width : Math.min(choose.width, img.width)) : img.width;
                            if(choose.scale) canvas.height = choose.width * (img.height / img.width);
                            else canvas.height = choose.height ? (choose.force ? choose.height : Math.min(choose.height, img.height)) : img.height;
                            drawer.drawImage(img, 0, 0, canvas.width, canvas.height);
                            if(choose.dispose && (choose.type || mime)) var src = canvas.toDataURL((choose.type ? 'image/' + choose.type : mime), choose.quality);
                            else var src = canvas.toDataURL();
                            imageLayer.canvas = canvas;
                            imagePreview.attr('src', src);
                        };
                    }else if(type == 'function') imagePreview.attr('src', choose.dispose(url));
                };
                if(choose.image && choose.dump) dump(choose.image);
                if(choose.local){
                    fileInput.filestyle();
                    $(layero).find('.bootstrap-filestyle .group-span-filestyle').removeClass('input-group-btn').addClass('input-group-append');
                    fileInput.on('change', function(){
                        var fileList = $(this).prop('files');
                        if(typeof (fileList) == 'object' && fileList.length > 0){
                            var fileContent = fileList[0];
                            if(isImageFile(fileContent)){
                                imagePreview.attr('src', choose.placeholder);
                                if(this.url) URL.revokeObjectURL(this.url);
                                this.url = URL.createObjectURL(fileContent);
                                imageLayer.data = {type:'file', value:fileContent};
                                src = this.url;
                                mime = fileContent.type;
                                urlInput.val('');
                                dispose();
                            }
                        }
                    });
                }else $(layero).find('.input-file').remove();
                if(choose.dump){
                    urlDump.on('click', function(){ dump(urlInput.val()); });
                }else $(layero).find('.input-url').remove();
            },
            yes:function(index, layero){
                yes(index, layero);
            }
        });

        return layer.confirm(html, options);
    };
    //  扩展方法
    $.extend($, {
        admin:{
            layer:layer,
            modal:$.bootstrapModaler,
            alert:$.bootstrapAlert,
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
            form:function(selector, options){
                //  选项
                options = options || {rules:{}, message:{}, commit:function(){}};
                options.list = options.list || {};
                options.rules = options.rules || {};
                options.messages = options.messages || {};
                options.errors = options.errors || true;
                options.debug = options.debug || false;
                options.protect = options.protect || 300;
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
