//  启动JS
!function($){
    //  启动严格模式
    "use strict";
    //  申明应用
    var MainApp = function(){};
    //  菜单配置
    MainApp.prototype.initNavbar = function(){
        $('.topbar-toggle').on('click', function(){
            $(this).toggleClass('open');
            $('.sidebar-body').slideToggle(350);
        });
    };
    MainApp.prototype.intSlimscrollmenu = function(){
        if(window._layout == 'vertical')
            $('.slimscroll-menu').slimscroll({
                height:$(window).height() - 80,
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
        $('#side-menu').metisMenu({});
    };
    MainApp.prototype.initLeftMenuCollapse = function(){
        $('.enlarged-toggle').on('click', function(event){
            event.preventDefault();
            $('html').toggleClass('enlarged');
            if(window.localStorage && $(window).width() >= 1025) localStorage.setItem("admin.config.enlarged", $('html').hasClass('enlarged') ? 'on' : 'off');
        });
    };
    //  响应式
    MainApp.prototype.initEnlarge = function(){
        var _delay;
        var _resize = function(){
            if($(window).width() < 1025 || (window.localStorage && localStorage.getItem('admin.config.enlarged') == 'on')) $('html').addClass('enlarged');
            else if(window.localStorage && localStorage.getItem('admin.config.enlarged') != 'on') $('html').removeClass('enlarged');
            if($(window).width() > 991) $('.sidebar-body').show();
            _delay = clearTimeout(_delay);
        }.bind(this);
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
        this.initComponents();
        Waves.init();
    };
    //  初始化
    $.MainApp = new MainApp;
    $.MainApp.initEnlarge();
    $.MainApp.Constructor = MainApp;
}(window.jQuery);

//  页面载入完毕
$(function(){
    //  启动
    $.MainApp.init();
    //  禁用表单自动提交
    $('body').on('submit', 'form', function(){ if($(this).attr('action') == undefined) return false; });
});
