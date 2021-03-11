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
            if($(this).hasClass('open')) $('.sidebar-body').slideDown(350);
            else $('.sidebar-body').slideUp(350);
        });
    };
    MainApp.prototype.intSlimscrollmenu = function(){
        if(window._layout == 'vertical')
            $('.slimscroll-menu').slimscroll({
                height:$(window).height() - 80,
                position:'right',
                size:'5px',
                color:'#9ea5ab',
                wheelStep:5,
                touchScrollStep:50
            });
    };
    MainApp.prototype.initSlimscroll = function(){
        $('.slimscroll').slimscroll({
            height:'auto',
            position:'right',
            size:'5px',
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
            if(window.localStorage && $(window).width() >= 1025) localStorage.setItem('admin.config.enlarged', $('html').hasClass('enlarged') ? 'on' : 'off');
        });
    };
    //  响应式
    MainApp.prototype.initEnlarge = function(delay = true){
        try{
            var _delay;
            var _resize = function(){
                if($(window).width() < 1025 || (window.localStorage && localStorage.getItem('admin.config.enlarged') == 'on')) $('html').addClass('enlarged');
                else if(window.localStorage && localStorage.getItem('admin.config.enlarged') != 'on') $('html').removeClass('enlarged');
                if(window._layout == 'horizontal'){
                    if($('.topbar-toggle').hasClass('open')) $('.topbar-toggle').click();
                    if($(window).width() > 991) $('.sidebar-body').show();
                    else $('.sidebar-body').hide();
                }
                if(window._layout == 'vertical') $('.slimscroll-menu').height($(window).height() - 80);
                if(window._layout == 'horizontal' && $('html').attr('tabs') !== undefined) $('.container-fluid').css('padding-right', this.getScrollbarWidth() ? '12px' : '15px');
                _delay = clearTimeout(_delay);
            }.bind(this);
            _resize();
            $(window).resize(function(){
                if(!_delay)
                    _delay = setTimeout(function(){
                        _resize();
                    }, delay ? 300 : 0);
            });
        }catch(e){

        }
    };
    //  组件
    MainApp.prototype.initComponents = function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    };
    //  获取滚动条宽度
    MainApp.prototype.getScrollbarWidth = function(){
        var scrollbarWidth = 0;
        try{
            if($('body').height() > $(window).height()){
                var odiv = document.createElement('div'), styles = {width:'100px', height:'100px', overflowY:'scroll'}, i;
                for(i in styles) odiv.style[i] = styles[i];
                document.body.appendChild(odiv);
                scrollbarWidth = odiv.offsetWidth - odiv.clientWidth;
                odiv.remove();
            }
            return scrollbarWidth;
        }catch(e){
            return scrollbarWidth;
        }
    };
    //  初始化
    MainApp.prototype.init = function(){
        this.initEnlarge(false);
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
