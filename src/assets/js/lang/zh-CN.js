!function(){
    $.locale = window._locale || 'zh-CN';
    //  Filestyle默认语言
    if(typeof ($.fn.filestyle) != 'undefined'){
        $.fn.filestyle.defaults.text = '选择文件';
    }
    //  Layer默认语言
    if(typeof (layer) != 'undefined'){
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
                errspic:function(){return '当前图片地址异常<br>是否继续查看下一张？';},
                errpic:function(){return '当前图片地址异常';},
                nextpic:function(){return '下一张';},
                closepic:function(){return '不看了';},
                avatar:function(){return '选择头像';},
                cropper:function(){return '裁剪图片';},
                image:function(){return '选择图片';},
                icon:function(){return '选择图标';},
                radio:function(){return '单项选择';},
            }
        });
    }
    //  Modal默认语言
    if(typeof ($.bootstrapModaler) != 'undefined'){
        $.bootstrapModaler.options.lang = {
            info:'信息',
            alert:'提示信息',
            confirm:'确认对话框',
            prompt:'输入框',
            ok:'确定',
            cancel:'取消',
            reset:'重置',
            file:'文件选择',
            imageUrl:'图片地址',
            imageDump:'外链转换',
            imageNone:'没有图片',
            fileNone:'没有文件',
            loading:'加载中'
        };
    }
    //  Select2默认语言
    if(typeof ($.fn.select2) != 'undefined'){
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
    }
    //  Validator默认语言
    if(typeof ($.validator) != 'undefined'){
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
    }
    //  Datetimepicker默认语言
    if(typeof ($.fn.datetimepicker) != 'undefined'){
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
    }
    //  Moment默认语言
    if(typeof (moment) != 'undefined'){
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
    }
    //  BootstrapTable
    if(typeof ($.fn.bootstrapTable) != 'undefined'){
        $.extend($.fn.bootstrapTable.defaults, {
            paginationPreText:'上一页',
            paginationNextText:'下一页',
            searchSubmitText:'提交搜索',
            searchResetText:'重置条件',
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
    }
    //  TinyMCE默认语言
    if(typeof (tinymce) != 'undefined'){
        tinymce.addI18n($.locale, {
            "Redo":"重做",
            "Undo":"撤销",
            "Cut":"剪切",
            "Copy":"复制",
            "Paste":"粘贴",
            "Select all":"全选",
            "New document":"清空重建",
            "Ok":"确定",
            "Cancel":"取消",
            "Visual aids":"网格线",
            "Bold":"粗体",
            "Italic":"斜体",
            "Underline":"下划线",
            "Strikethrough":"删除线",
            "Superscript":"上标",
            "Subscript":"下标",
            "Clear formatting":"清除格式",
            "Align left":"左边对齐",
            "Align center":"中间对齐",
            "Align right":"右边对齐",
            "Justify":"两端对齐",
            "Bullet list":"项目符号",
            "Numbered list":"编号列表",
            "Decrease indent":"减少缩进",
            "Increase indent":"增加缩进",
            "Close":"关闭",
            "Formats":"格式",
            "Your browser doesn't support direct access to the clipboard. Please use the Ctrl+X\/C\/V keyboard shortcuts instead.":"你的浏览器不支持打开剪贴板，请使用Ctrl+X\/C\/V等快捷键。",
            "Headers":"标题",
            "Header 1":"标题1",
            "Header 2":"标题2",
            "Header 3":"标题3",
            "Header 4":"标题4",
            "Header 5":"标题5",
            "Header 6":"标题6",
            "Headings":"标题",
            "Heading 1":"标题1",
            "Heading 2":"标题2",
            "Heading 3":"标题3",
            "Heading 4":"标题4",
            "Heading 5":"标题5",
            "Heading 6":"标题6",
            "Preformatted":"预定义格式",
            "Div":"Div",
            "Pre":"Pre",
            "Code":"代码",
            "Paragraph":"段落",
            "Blockquote":"引文区块",
            "Inline":"文本",
            "Blocks":"基块",
            "Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.":"当前为纯文本粘贴模式，再次点击可以回到普通粘贴模式。",
            "Fonts":"字体",
            "Font Sizes":"字号",
            "Class":"类型",
            "Browse for an image":"浏览图像",
            "OR":"或",
            "Drop an image here":"拖放一张图像至此",
            "Upload":"上传",
            "Block":"块",
            "Align":"对齐",
            "Default":"默认",
            "Circle":"空心圆",
            "Disc":"实心圆",
            "Square":"方块",
            "Lower Alpha":"小写英文字母",
            "Lower Greek":"小写希腊字母",
            "Lower Roman":"小写罗马字母",
            "Upper Alpha":"大写英文字母",
            "Upper Roman":"大写罗马字母",
            "Anchor...":"锚点...",
            "Name":"名称",
            "Id":"标识符",
            "Id should start with a letter, followed only by letters, numbers, dashes, dots, colons or underscores.":"标识符应该以字母开头，后跟字母、数字、破折号、点、冒号或下划线。",
            "You have unsaved changes are you sure you want to navigate away?":"你还有文档尚未保存，确定要离开？",
            "Restore last draft":"恢复草稿",
            "Special character...":"特殊字符...",
            "Source code":"源代码",
            "Insert\/Edit code sample":"插入\/编辑代码示例",
            "Language":"语言",
            "Code sample...":"示例代码...",
            "Color Picker":"选色器",
            "R":"R",
            "G":"G",
            "B":"B",
            "Left to right":"从左到右",
            "Right to left":"从右到左",
            "Emoticons...":"表情符号...",
            "Metadata and Document Properties":"元数据和文档属性",
            "Title":"标题",
            "Keywords":"关键词",
            "Description":"描述",
            "Robots":"机器人",
            "Author":"作者",
            "Encoding":"编码",
            "Fullscreen":"全屏",
            "Action":"操作",
            "Shortcut":"快捷键",
            "Help":"帮助",
            "Address":"地址",
            "Focus to menubar":"移动焦点到菜单栏",
            "Focus to toolbar":"移动焦点到工具栏",
            "Focus to element path":"移动焦点到元素路径",
            "Focus to contextual toolbar":"移动焦点到上下文菜单",
            "Insert link (if link plugin activated)":"插入链接 (如果链接插件已激活)",
            "Save (if save plugin activated)":"保存(如果保存插件已激活)",
            "Find (if searchreplace plugin activated)":"查找(如果查找替换插件已激活)",
            "Plugins installed ({0}):":"已安装插件 ({0}):",
            "Premium plugins:":"优秀插件：",
            "Learn more...":"了解更多...",
            "You are using {0}":"你正在使用 {0}",
            "Plugins":"插件",
            "Handy Shortcuts":"快捷键",
            "Horizontal line":"水平分割线",
            "Insert\/edit image":"插入\/编辑图片",
            "Image description":"图片描述",
            "Source":"地址",
            "Dimensions":"大小",
            "Constrain proportions":"保持纵横比",
            "General":"普通",
            "Advanced":"高级",
            "Style":"样式",
            "Vertical space":"垂直边距",
            "Horizontal space":"水平边距",
            "Border":"边框",
            "Insert image":"插入图片",
            "Image...":"图片...",
            "Image list":"图片列表",
            "Rotate counterclockwise":"逆时针旋转",
            "Rotate clockwise":"顺时针旋转",
            "Flip vertically":"垂直翻转",
            "Flip horizontally":"水平翻转",
            "Edit image":"编辑图片",
            "Image options":"图片选项",
            "Zoom in":"放大",
            "Zoom out":"缩小",
            "Crop":"裁剪",
            "Resize":"调整大小",
            "Orientation":"方向",
            "Brightness":"亮度",
            "Sharpen":"锐化",
            "Contrast":"对比度",
            "Color levels":"颜色层次",
            "Gamma":"伽马值",
            "Invert":"反转",
            "Apply":"应用",
            "Back":"后退",
            "Insert date\/time":"插入日期\/时间",
            "Date\/time":"日期\/时间",
            "Insert\/Edit Link":"插入\/编辑链接",
            "Insert\/edit link":"插入\/编辑链接",
            "Text to display":"显示文字",
            "Url":"地址",
            "Open link in...":"链接打开位置...",
            "Current window":"当前窗口",
            "None":"无",
            "New window":"在新窗口打开",
            "Remove link":"删除链接",
            "Anchors":"锚点",
            "Link...":"链接...",
            "Paste or type a link":"粘贴或输入链接",
            "The URL you entered seems to be an email address. Do you want to add the required mailto: prefix?":"你所填写的URL地址为邮件地址，需要加上mailto:前缀吗？",
            "The URL you entered seems to be an external link. Do you want to add the required http:\/\/ prefix?":"你所填写的URL地址属于外部链接，需要加上http:\/\/:前缀吗？",
            "Link list":"链接列表",
            "Insert video":"插入视频",
            "Insert\/edit video":"插入\/编辑视频",
            "Insert\/edit media":"插入\/编辑媒体",
            "Alternative source":"镜像",
            "Alternative source URL":"替代来源网址",
            "Media poster (Image URL)":"封面(图片地址)",
            "Paste your embed code below:":"将内嵌代码粘贴在下面:",
            "Embed":"内嵌",
            "Media...":"多媒体...",
            "Nonbreaking space":"不间断空格",
            "Page break":"分页符",
            "Paste as text":"粘贴为文本",
            "Preview":"预览",
            "Print...":"打印...",
            "Save":"保存",
            "Find":"查找",
            "Replace with":"替换为",
            "Replace":"替换",
            "Replace all":"全部替换",
            "Previous":"上一个",
            "Next":"下一个",
            "Find and replace...":"查找并替换...",
            "Could not find the specified string.":"未找到搜索内容.",
            "Match case":"区分大小写",
            "Find whole words only":"全字匹配",
            "Spell check":"拼写检查",
            "Ignore":"忽略",
            "Ignore all":"全部忽略",
            "Finish":"完成",
            "Add to Dictionary":"添加到字典",
            "Insert table":"插入表格",
            "Table properties":"表格属性",
            "Delete table":"删除表格",
            "Cell":"单元格",
            "Row":"行",
            "Column":"列",
            "Cell properties":"单元格属性",
            "Merge cells":"合并单元格",
            "Split cell":"拆分单元格",
            "Insert row before":"在上方插入",
            "Insert row after":"在下方插入",
            "Delete row":"删除行",
            "Row properties":"行属性",
            "Cut row":"剪切行",
            "Copy row":"复制行",
            "Paste row before":"粘贴到上方",
            "Paste row after":"粘贴到下方",
            "Insert column before":"在左侧插入",
            "Insert column after":"在右侧插入",
            "Delete column":"删除列",
            "Cols":"列",
            "Rows":"行",
            "Width":"宽",
            "Height":"高",
            "Cell spacing":"单元格外间距",
            "Cell padding":"单元格内边距",
            "Show caption":"显示标题",
            "Left":"左对齐",
            "Center":"居中",
            "Right":"右对齐",
            "Cell type":"单元格类型",
            "Scope":"范围",
            "Alignment":"对齐方式",
            "H Align":"水平对齐",
            "V Align":"垂直对齐",
            "Top":"顶部对齐",
            "Middle":"垂直居中",
            "Bottom":"底部对齐",
            "Header cell":"表头单元格",
            "Row group":"行组",
            "Column group":"列组",
            "Row type":"行类型",
            "Header":"表头",
            "Body":"表体",
            "Footer":"表尾",
            "Border color":"边框颜色",
            "Insert template...":"插入模板...",
            "Templates":"模板",
            "Template":"模板",
            "Text color":"文字颜色",
            "Background color":"背景色",
            "Custom...":"自定义...",
            "Custom color":"自定义颜色",
            "No color":"无",
            "Remove color":"移除颜色",
            "Table of Contents":"内容列表",
            "Show blocks":"显示区块边框",
            "Show invisible characters":"显示不可见字符",
            "Word count":"字数",
            "Count":"计数",
            "Document":"文档",
            "Selection":"选择",
            "Words":"单词",
            "Words: {0}":"字数：{0}",
            "{0} words":"{0} 字",
            "File":"文件",
            "Edit":"编辑",
            "Insert":"插入",
            "View":"视图",
            "Format":"格式",
            "Table":"表格",
            "Tools":"工具",
            "Powered by {0}":"由{0}驱动",
            "Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help":"在编辑区按ALT-F9打开菜单，按ALT-F10打开工具栏，按ALT-0查看帮助",
            "Image title":"图片标题",
            "Border width":"边框宽度",
            "Border style":"边框样式",
            "Error":"错误",
            "Warn":"警告",
            "Valid":"有效",
            "To open the popup, press Shift+Enter":"按Shitf+Enter键打开对话框",
            "Rich Text Area. Press ALT-0 for help.":"编辑区。按Alt+0键打开帮助。",
            "System Font":"系统字体",
            "Failed to upload image: {0}":"图片上传失败: {0}",
            "Failed to load plugin: {0} from url {1}":"插件加载失败: {0} 来自链接 {1}",
            "Failed to load plugin url: {0}":"插件加载失败 链接: {0}",
            "Failed to initialize plugin: {0}":"插件初始化失败: {0}",
            "example":"示例",
            "Search":"搜索",
            "All":"全部",
            "Currency":"货币",
            "Text":"文字",
            "Quotations":"引用",
            "Mathematical":"数学",
            "Extended Latin":"拉丁语扩充",
            "Symbols":"符号",
            "Arrows":"箭头",
            "User Defined":"自定义",
            "dollar sign":"美元符号",
            "currency sign":"货币符号",
            "euro-currency sign":"欧元符号",
            "colon sign":"冒号",
            "cruzeiro sign":"克鲁赛罗币符号",
            "french franc sign":"法郎符号",
            "lira sign":"里拉符号",
            "mill sign":"密尔符号",
            "naira sign":"奈拉符号",
            "peseta sign":"比塞塔符号",
            "rupee sign":"卢比符号",
            "won sign":"韩元符号",
            "new sheqel sign":"新谢克尔符号",
            "dong sign":"越南盾符号",
            "kip sign":"老挝基普符号",
            "tugrik sign":"图格里克符号",
            "drachma sign":"德拉克马符号",
            "german penny symbol":"德国便士符号",
            "peso sign":"比索符号",
            "guarani sign":"瓜拉尼符号",
            "austral sign":"澳元符号",
            "hryvnia sign":"格里夫尼亚符号",
            "cedi sign":"塞地符号",
            "livre tournois sign":"里弗弗尔符号",
            "spesmilo sign":"spesmilo符号",
            "tenge sign":"坚戈符号",
            "indian rupee sign":"印度卢比",
            "turkish lira sign":"土耳其里拉",
            "nordic mark sign":"北欧马克",
            "manat sign":"马纳特符号",
            "ruble sign":"卢布符号",
            "yen character":"日元字样",
            "yuan character":"人民币元字样",
            "yuan character, in hong kong and taiwan":"元字样（港台地区）",
            "yen\/yuan character variant one":"元字样（大写）",
            "Loading emoticons...":"加载表情符号...",
            "Could not load emoticons":"不能加载表情符号",
            "People":"人类",
            "Animals and Nature":"动物和自然",
            "Food and Drink":"食物和饮品",
            "Activity":"活动",
            "Travel and Places":"旅游和地点",
            "Objects":"物件",
            "Flags":"旗帜",
            "Characters":"字符",
            "Characters (no spaces)":"字符(无空格)",
            "{0} characters":"{0} 个字符",
            "Error: Form submit field collision.":"错误: 表单提交字段冲突。",
            "Error: No form element found.":"错误: 没有表单控件。",
            "Update":"更新",
            "Color swatch":"颜色样本",
            "Turquoise":"青绿色",
            "Green":"绿色",
            "Blue":"蓝色",
            "Purple":"紫色",
            "Navy Blue":"海军蓝",
            "Dark Turquoise":"深蓝绿色",
            "Dark Green":"深绿色",
            "Medium Blue":"中蓝色",
            "Medium Purple":"中紫色",
            "Midnight Blue":"深蓝色",
            "Yellow":"黄色",
            "Orange":"橙色",
            "Red":"红色",
            "Light Gray":"浅灰色",
            "Gray":"灰色",
            "Dark Yellow":"暗黄色",
            "Dark Orange":"深橙色",
            "Dark Red":"深红色",
            "Medium Gray":"中灰色",
            "Dark Gray":"深灰色",
            "Light Green":"浅绿色",
            "Light Yellow":"浅黄色",
            "Light Red":"浅红色",
            "Light Purple":"浅紫色",
            "Light Blue":"浅蓝色",
            "Dark Purple":"深紫色",
            "Dark Blue":"深蓝色",
            "Black":"黑色",
            "White":"白色",
            "Switch to or from fullscreen mode":"切换全屏模式",
            "Open help dialog":"打开帮助对话框",
            "history":"历史",
            "styles":"样式",
            "formatting":"格式化",
            "alignment":"对齐",
            "indentation":"缩进",
            "permanent pen":"记号笔",
            "comments":"备注",
            "Format Painter":"格式刷",
            "Insert\/edit iframe":"插入\/编辑框架",
            "Capitalization":"大写",
            "lowercase":"小写",
            "UPPERCASE":"大写",
            "Title Case":"首字母大写",
            "Permanent Pen Properties":"永久笔属性",
            "Permanent pen properties...":"永久笔属性...",
            "Font":"字体",
            "Size":"字号",
            "More...":"更多...",
            "Spellcheck Language":"拼写检查语言",
            "Select...":"选择...",
            "Preferences":"首选项",
            "Yes":"是",
            "No":"否",
            "Keyboard Navigation":"键盘指引",
            "Version":"版本",
            "Anchor":"锚点",
            "Special character":"特殊符号",
            "Code sample":"代码示例",
            "Color":"颜色",
            "Emoticons":"表情",
            "Document properties":"文档属性",
            "Image":"图片",
            "Insert link":"插入链接",
            "Target":"打开方式",
            "Link":"链接",
            "Poster":"封面",
            "Media":"媒体",
            "Print":"打印",
            "Prev":"上一个",
            "Find and replace":"查找和替换",
            "Whole words":"全字匹配",
            "Spellcheck":"拼写检查",
            "Caption":"标题",
            "Insert template":"插入模板",
            "Alternative description":"图片描述"
        });
    }
    //  Editor.md默认语言
    if(typeof (editormd) != 'undefined'){
        $.extend(editormd.defaults, {
            lang:{
                name:$.locale,
                description:"开源在线Markdown编辑器，由Comingdemon二次开发",
                tocTitle:"目录",
                toolbar:{
                    undo:"撤销（Ctrl+Z）",
                    redo:"重做（Ctrl+Y）",
                    bold:"粗体",
                    del:"删除线",
                    italic:"斜体",
                    quote:"引用",
                    ucwords:"将每个单词首字母转成大写",
                    uppercase:"将所选转换成大写",
                    lowercase:"将所选转换成小写",
                    h1:"标题1",
                    h2:"标题2",
                    h3:"标题3",
                    h4:"标题4",
                    h5:"标题5",
                    h6:"标题6",
                    "list-ul":"无序列表",
                    "list-ol":"有序列表",
                    hr:"横线",
                    link:"链接",
                    "reference-link":"引用链接",
                    image:"添加图片",
                    dimage:"添加图片",
                    code:"行内代码",
                    "preformatted-text":"预格式文本 / 代码块（缩进风格）",
                    "code-block":"代码块（多语言风格）",
                    table:"添加表格",
                    datetime:"日期时间",
                    emoji:"Emoji表情",
                    "html-entities":"HTML实体字符",
                    pagebreak:"插入分页符",
                    "goto-line":"跳转到行",
                    watch:"关闭实时预览",
                    unwatch:"开启实时预览",
                    preview:"全窗口预览HTML（按 Shift + ESC还原）",
                    fullscreen:"全屏（按ESC还原）",
                    clear:"清空",
                    search:"搜索",
                    help:"使用帮助",
                    info:"关于" + editormd.title
                },
                buttons:{enter:"确定", cancel:"取消", close:"关闭"},
                dialog:{
                    link:{
                        title:"添加链接",
                        url:"链接地址",
                        urlTitle:"链接标题",
                        urlEmpty:"错误：请填写链接地址。"
                    },
                    referenceLink:{
                        title:"添加引用链接",
                        name:"引用名称",
                        url:"链接地址",
                        urlId:"链接ID",
                        urlTitle:"链接标题",
                        nameEmpty:"错误：引用链接的名称不能为空。",
                        idEmpty:"错误：请填写引用链接的ID。",
                        urlEmpty:"错误：请填写引用链接的URL地址。"
                    },
                    image:{
                        title:"添加图片",
                        url:"图片地址",
                        link:"图片链接",
                        alt:"图片描述",
                        uploadButton:"图片上传",
                        imageURLEmpty:"错误：图片地址不能为空。",
                        uploadFileEmpty:"错误：上传的图片不能为空。",
                        formatNotAllowed:"错误：只允许上传图片文件，允许上传的图片文件格式有："
                    },
                    table:{
                        title:"添加表格",
                        cellsLabel:"单元格数",
                        alignLabel:"对齐方式",
                        rows:"行数",
                        cols:"列数",
                        aligns:["默认", "左对齐", "居中对齐", "右对齐"]
                    },
                    preformattedText:{
                        title:"添加预格式文本或代码块",
                        emptyAlert:"错误：请填写预格式文本或代码的内容。"
                    },
                    codeBlock:{
                        title:"添加代码块",
                        selectLabel:"代码语言：",
                        selectDefaultText:"请选择代码语言",
                        otherLanguage:"其他语言",
                        unselectedLanguageAlert:"错误：请选择代码所属的语言类型。",
                        codeEmptyAlert:"错误：请填写代码内容。"
                    },
                    htmlEntities:{title:"HTML 实体字符"},
                    emoji:{title:"Emoji 表情"},
                    help:{title:"使用帮助"},
                    "goto-line":{
                        title:"跳转到行",
                        label:"请输入行号",
                        error:"错误："
                    }
                }
            }
        });
    }
}();
