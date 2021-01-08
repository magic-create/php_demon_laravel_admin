!function(){
    $.locale = 'zh-CN';
    //  Filestyle默认语言
    $.fn.filestyle.defaults.text = '选择文件';
    //  Layer默认语言
    layer.config({
        lang:{
            info:function(){return '信息';},
            alert:function(){return '提示信息';},
            confirm:function(){return '确认对话框';},
            prompt:function(){return '输入框';},
            ok:function(){return '确定';},
            cancel:function(){return '取消'; },
            maxlength:function(length){return '请输入一个长度最多是 ' + length + ' 的字符串'; },
            nopic:function(){return '没有图片';},
            errpic:function(){return '当前图片地址异常<br>是否继续查看下一张？';},
            nextpic:function(){return '下一张';},
            closepic:function(){return '不看了';}
        }
    });
    //  Modal默认语言
    $.bootstrapModaler.options.lang = {
        info:'信息',
        alert:'提示信息',
        confirm:'确认对话框',
        prompt:'输入框',
        ok:'确定',
        cancel:'取消'
    };
    //  Select2默认语言
    $.fn.select2.amd.define('select2/i18n/' + $.locale, [], function(){
        return {
            errorLoading:function(){return "无法载入结果。";},
            inputTooLong:function(n){return "请删除" + (n.input.length - n.maximum) + "个字符";},
            inputTooShort:function(n){return "请再输入至少" + (n.minimum - n.input.length) + "个字符";},
            loadingMore:function(){return "载入更多结果…";},
            maximumSelected:function(n){return "最多只能选择" + n.maximum + "个项目";},
            noResults:function(){return "未找到结果";},
            searching:function(){return "搜索中…";},
            removeAllItems:function(){return "删除所有项目";}
        };
    });
    //  Validator默认语言
    $.extend($.validator.messages, {
        required:"必选字段",
        remote:"请修正该字段",
        email:"请输入正确格式的电子邮件",
        url:"请输入合法的网址",
        date:"请输入合法的日期",
        dateISO:"请输入合法的日期 (ISO).",
        number:"请输入合法的数字",
        digits:"只能输入正整数",
        creditcard:"请输入合法的信用卡号",
        equalTo:"请再次输入相同的值",
        accept:"请输入拥有合法后缀名的字符串",
        maxlength:$.validator.format("请输入一个长度最多是 {0} 的字符串"),
        minlength:$.validator.format("请输入一个长度最少是 {0} 的字符串"),
        rangelength:$.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
        range:$.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
        max:$.validator.format("请输入一个最大为 {0} 的值"),
        min:$.validator.format("请输入一个最小为 {0} 的值")
    });
    //  Datetimepicker默认语言
    $.fn.datetimepicker.defaults.locale = $.locale;
    $.fn.datetimepicker.defaults.tooltips = {
        today:'现在',
        clear:'清除',
        close:'关闭',
        selectMonth:'选择月份',
        prevMonth:'上个月',
        nextMonth:'下个月',
        selectYear:'选择年份',
        prevYear:'前一年',
        nextYear:'后一年',
        selectDecade:'选择十年',
        prevDecade:'上个十年',
        nextDecade:'下个十年',
        prevCentury:'上个世纪',
        nextCentury:'下个世纪',
        pickHour:'选择小时',
        incrementHour:'增加小时',
        decrementHour:'减少小时',
        pickMinute:'选择分钟',
        incrementMinute:'增加分钟',
        decrementMinute:'减少分钟',
        pickSecond:'选择秒数',
        incrementSecond:'增加秒数',
        decrementSecond:'减少秒数',
        togglePeriod:'切换时期',
        selectTime:'选择时间'
    };
    //  Moment默认语言
    moment.defineLocale($.locale, {
        months:'一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月'.split('_'),
        monthsShort:'1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split('_'),
        weekdays:'星期日_星期一_星期二_星期三_星期四_星期五_星期六'.split('_'),
        weekdaysShort:'周日_周一_周二_周三_周四_周五_周六'.split('_'),
        weekdaysMin:'日_一_二_三_四_五_六'.split('_'),
        longDateFormat:{LT:'HH:mm', LTS:'HH:mm:ss', L:'YYYY/MM/DD', LL:'YYYY年M月D日', LLL:'YYYY年M月D日Ah点mm分', LLLL:'YYYY年M月D日ddddAh点mm分', l:'YYYY/M/D', ll:'YYYY年M月D日', lll:'YYYY年M月D日 HH:mm', llll:'YYYY年M月D日dddd HH:mm'},
        meridiemParse:/凌晨|早上|上午|中午|下午|晚上/,
        meridiemHour:function(hour, meridiem){
            if(hour === 12) hour = 0;
            if(meridiem === '凌晨' || meridiem === '早上' || meridiem === '上午') return hour;
            else if(meridiem === '下午' || meridiem === '晚上') return hour + 12;
            else return hour >= 11 ? hour : hour + 12;
        },
        meridiem:function(hour){ if(hour < 12) return '上午'; else return '下午'; },
        calendar:{
            sameDay:'[今天]LT',
            nextDay:'[明天]LT',
            nextWeek:function(now){if(now.week() !== this.week()) return '[下]dddLT'; else return '[本]dddLT';},
            lastDay:'[昨天]LT',
            lastWeek:function(now){if(this.week() !== now.week()) return '[上]dddLT'; else return '[本]dddLT';},
            sameElse:'L'
        },
        dayOfMonthOrdinalParse:/\d{1,2}([日月周])/,
        ordinal:function(number, period){
            switch(period){
                case 'd':
                case 'D':
                case 'DDD':
                    return number + '日';
                case 'M':
                    return number + '月';
                case 'w':
                case 'W':
                    return number + '周';
                default:
                    return number;
            }
        },
        relativeTime:{future:'%s后', past:'%s前', s:'几秒', ss:'%d 秒', m:'1 分钟', mm:'%d 分钟', h:'1 小时', hh:'%d 小时', d:'1 天', dd:'%d 天', w:'1 周', ww:'%d 周', M:'1 个月', MM:'%d 个月', y:'1 年', yy:'%d 年'},
        week:{dow:1, doy:4}
    });
    //  BootstrapTable
    $.extend($.fn.bootstrapTable.defaults, {
        formatCopyRows:function formatCopyRows(){ return '复制'; },
        formatPrint:function formatPrint(){ return '打印'; },
        formatLoadingMessage:function formatLoadingMessage(){ return '正在努力地加载数据中，请稍候'; },
        formatRecordsPerPage:function formatRecordsPerPage(pageNumber){ return "每页显示 ".concat(pageNumber, " 条记录"); },
        formatShowingRows:function formatShowingRows(pageFrom, pageTo, totalRows, totalNotFiltered){
            if(totalNotFiltered !== undefined && totalNotFiltered > 0 && totalNotFiltered > totalRows) return "显示第 ".concat(pageFrom, " 到第 ").concat(pageTo, " 条记录，总共 ").concat(totalRows, " 条记录（从 ").concat(totalNotFiltered, " 总记录中过滤）");
            return "显示第 ".concat(pageFrom, " 到第 ").concat(pageTo, " 条记录，总共 ").concat(totalRows, " 条记录");
        },
        formatSRPaginationPreText:function formatSRPaginationPreText(){ return '上一页'; },
        formatSRPaginationPageText:function formatSRPaginationPageText(page){ return "第".concat(page, "页"); },
        formatSRPaginationNextText:function formatSRPaginationNextText(){ return '下一页'; },
        formatDetailPagination:function formatDetailPagination(totalRows){ return "总共 ".concat(totalRows, " 条记录"); },
        formatClearSearch:function formatClearSearch(){ return '清空过滤'; },
        formatSearch:function formatSearch(){ return '搜索'; },
        formatNoMatches:function formatNoMatches(){ return '没有找到匹配的记录'; },
        formatPaginationSwitch:function formatPaginationSwitch(){ return '隐藏/显示分页'; },
        formatPaginationSwitchDown:function formatPaginationSwitchDown(){ return '显示分页'; },
        formatPaginationSwitchUp:function formatPaginationSwitchUp(){ return '隐藏分页'; },
        formatRefresh:function formatRefresh(){ return '刷新'; },
        formatToggle:function formatToggle(){ return '切换'; },
        formatToggleOn:function formatToggleOn(){ return '显示卡片视图'; },
        formatToggleOff:function formatToggleOff(){ return '隐藏卡片视图'; },
        formatColumns:function formatColumns(){ return '列'; },
        formatColumnsToggleAll:function formatColumnsToggleAll(){ return '切换所有'; },
        formatFullscreen:function formatFullscreen(){ return '全屏'; },
        formatAllRows:function formatAllRows(){ return '所有'; },
        formatAutoRefresh:function formatAutoRefresh(){ return '自动刷新'; },
        formatExport:function formatExport(){ return '导出数据'; },
        formatJumpTo:function formatJumpTo(){ return '跳转'; },
        formatAdvancedSearch:function formatAdvancedSearch(){ return '高级搜索'; },
        formatAdvancedCloseButton:function formatAdvancedCloseButton(){ return '关闭'; },
        formatFilterControlSwitch:function formatFilterControlSwitch(){ return '隐藏/显示过滤控制'; },
        formatFilterControlSwitchHide:function formatFilterControlSwitchHide(){ return '隐藏过滤控制'; },
        formatFilterControlSwitchShow:function formatFilterControlSwitchShow(){ return '显示过滤控制'; }
    });
}();
