//判断浏览器是否支持html5本地存储
function localStorageSupport(){
    return (('localStorage' in window) && window['localStorage'] !== null);
}

//  启动JS
!function($){
    //  启动严格模式
    "use strict";
    //  申明应用
    var MainApp = function(){};
    //  菜单配置
    MainApp.prototype.initNavbar = function(){
        $('.navbar-toggle').on('click', function(){
            $(this).toggleClass('open');
            $('#navigation').slideToggle(400);
        });
        $('.navigation-menu>li').slice(-2).addClass('last-elements');
        $('.navigation-menu li.has-submenu a[href="javascript:"]').on('click', function(e){
            if($(window).width() < 992){
                e.preventDefault();
                $(this).parent('li').toggleClass('open').find('.submenu:first').toggleClass('open');
            }
        });
    };
    MainApp.prototype.intSlimscrollmenu = function(){
        $('.slimscroll-menu').slimscroll({
            height:'auto',
            position:'right',
            size:"5px",
            color:'#9ea5ab',
            wheelStep:5,
            touchScrollStep:50
        });
    };
    MainApp.prototype.initSlimscroll = function(){
        $('.slimscroll').slimscroll({
            height:'auto',
            position:'right',
            size:"5px",
            color:'#9ea5ab',
            touchScrollStep:50
        });
    };
    MainApp.prototype.initMetisMenu = function(){
        $('#side-menu').metisMenu();
    };
    MainApp.prototype.initLeftMenuCollapse = function(){
        $('.button-menu-mobile').on('click', function(event){
            event.preventDefault();
            if(localStorageSupport)
                localStorage.setItem("admin.config.enlarged", !$('body').hasClass('enlarged') ? 'on' : 'off');
            $('body').toggleClass('enlarged');
            if(!$('body').hasClass('enlarged'))
                $('.active').find('> ul').addClass('in');
            else
                $('.submenu').removeClass('in');
        });
    };
    //  响应式
    MainApp.prototype.initEnlarge = function(){
        var _delay = 0;
        var _resize = function(){
            $('body').removeClass('d-none');
            if($(window).width() < 1025 || (localStorageSupport && localStorage.getItem('admin.config.enlarged') == 'on')){
                $('body').addClass('enlarged');
                $('.submenu').removeClass('in');
            }else{
                if(localStorageSupport && localStorage.getItem('admin.config.enlarged') != 'on'){
                    $('body').removeClass('enlarged');
                    $('.active').find('> ul').addClass('in');
                }
            }
            _delay = null;
        };
        _resize();
        $(window).resize(function(){
            if(!_delay)
                _delay = setTimeout(function(){
                    _resize();
                }, 300);
        });
    };
    //  组件
    MainApp.prototype.initComponents = function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    };
    //  初始化
    MainApp.prototype.init = function(){
        this.initNavbar();
        this.intSlimscrollmenu();
        this.initSlimscroll();
        this.initMetisMenu();
        this.initLeftMenuCollapse();
        this.initEnlarge();
        this.initComponents();
        Waves.init();
    };
    //  附加方法
    MainApp.prototype.form = {
        value:function(element){
            var value = {};
            $.each($(element).serializeArray(), function(){
                if(value[this['name']]){
                    if($.isArray(value[this['name']])) value[this['name']].push(this['value']);
                    else value[this['name']] = [value[this['name']], this['value']];
                }else value[this['name']] = this['value'];
            });
            return value;
        },
        clear:function(element){
            $(element)[0].reset();
        },
        join:function(element){
            var params = $(element).serialize();
            return '&' + params;
        },
        url:function(url, parm){
            return url + (url.search(/\?/) === -1 ? '?' : '&') + decodeURIComponent($.param(parm));
        }
    };
    //  初始化
    $.MainApp = new MainApp;
    $.MainApp.Constructor = MainApp;
}(window.jQuery);

//  页面载入完毕
$(function(){
    //  启动
    $.MainApp.init();
    //  禁用表单自动提交
    $('body').on('submit', 'form', function(){
        if($(this).attr('action') == undefined){
            return false;
        }
    });
    //  Select2全局定义
    $.fn.select2.defaults.set('dropdownAutoWidth', true);
    $.fn.select2.defaults.set('language', {
        errorLoading:function(){return "无法载入结果。";},
        inputTooLong:function(n){return "请删除" + (n.input.length - n.maximum) + "个字符";},
        inputTooShort:function(n){return "请再输入至少" + (n.minimum - n.input.length) + "个字符";},
        loadingMore:function(){return "载入更多结果…";},
        maximumSelected:function(n){return "最多只能选择" + n.maximum + "个项目";},
        noResults:function(){return "未找到结果";},
        searching:function(){return "搜索中…";},
        removeAllItems:function(){return "删除所有项目";}
    });
});
