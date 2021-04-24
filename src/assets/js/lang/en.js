!function(){
    $.locale = window._locale || 'en';
    //  Filestyle默认语言
    if(typeof ($.fn.filestyle) != 'undefined'){
        $.fn.filestyle.defaults.text = 'Choose file';
    }
    //  Layer默认语言
    if(typeof (layer) != 'undefined'){
        layer.config({
            lang:{
                info:function(){return 'Info';},
                alert:function(){return 'Alert';},
                confirm:function(){return 'Confirm';},
                prompt:function(){return 'Prompt';},
                ok:function(){return 'OK';},
                cancel:function(){return 'Cancel'; },
                maxlength:function(length){return 'Please enter a string up to ' + length + ' in length '; },
                nopic:function(){return 'No Pic';},
                errspic:function(){return 'The current picture address is abnormal<br>Whether to continue to view the next one ?';},
                errpic:function(){return 'The current picture address is abnormal';},
                nextpic:function(){return 'Next';},
                closepic:function(){return 'Leave';},
                avatar:function(){return 'Avatar';},
                cropper:function(){return 'Cropper';},
                image:function(){return 'Image';},
                icon:function(){return 'Icon';},
                radio:function(){return 'Radio';}
            }
        });
    }
    //  Modal默认语言
    if(typeof ($.bootstrapModaler) != 'undefined'){
        $.bootstrapModaler.options.lang = {
            info:'Info',
            alert:'Alert',
            confirm:'Confirm',
            prompt:'Prompt',
            ok:'OK',
            cancel:'Cancel',
            reset:'Reset',
            file:'Choose',
            imageUrl:'Image Url',
            imageDump:'Image Dump',
            imageNone:'No Image',
            fileNone:'No File',
            loading:'loading'
        };
    }
    //  Select2默认语言
    if(typeof ($.fn.select2) != 'undefined'){
        $.fn.select2.amd.define('select2/i18n/' + $.locale, [], function(){
            return {
                errorLoading:function(){ return 'The results could not be loaded.'; },
                inputTooLong:function(args){return 'Please delete ' + (args.input.length - args.maximum) + ' character' + ((args.input.length - args.maximum) != 1 ? 's' : '');},
                inputTooShort:function(args){return 'Please enter ' + (args.minimum - args.input.length) + ' or more characters';},
                loadingMore:function(){ return 'Loading more results…'; },
                maximumSelected:function(args){return 'You can only select ' + args.maximum + ' item' + (args.maximum != 1 ? 's' : '');},
                noResults:function(){ return 'No results found'; },
                searching:function(){ return 'Searching…'; },
                removeAllItems:function(){ return 'Remove all items'; }
            };
        });
    }
    //  Validator默认语言
    if(typeof ($.validator) != 'undefined'){
        $.extend($.validator.messages, {
            required:"This field is required.",
            remote:"Please fix this field.",
            email:"Please enter a valid email address.",
            url:"Please enter a valid URL.",
            date:"Please enter a valid date.",
            dateISO:"Please enter a valid date (ISO).",
            number:"Please enter a valid number.",
            digits:"Please enter only digits.",
            creditcard:"Please enter a valid credit card number.",
            equalTo:"Please enter the same value again.",
            accept:"Please enter a value with a valid extension.",
            maxlength:$.validator.format("Please enter no more than {0} characters."),
            minlength:$.validator.format("Please enter at least {0} characters."),
            rangelength:$.validator.format("Please enter a value between {0} and {1} characters long."),
            range:$.validator.format("Please enter a value between {0} and {1}."),
            max:$.validator.format("Please enter a value less than or equal to {0}."),
            min:$.validator.format("Please enter a value greater than or equal to {0}.")
        });
    }
    //  Datetimepicker默认语言
    if(typeof ($.fn.datetimepicker) != 'undefined'){
        $.fn.datetimepicker.defaults.locale = $.locale;
        $.fn.datetimepicker.defaults.tooltips = {
            today:'Go to today',
            clear:'Clear selection',
            close:'Close the picker',
            selectMonth:'Select Month',
            prevMonth:'Previous Month',
            nextMonth:'Next Month',
            selectYear:'Select Year',
            prevYear:'Previous Year',
            nextYear:'Next Year',
            selectDecade:'Select Decade',
            prevDecade:'Previous Decade',
            nextDecade:'Next Decade',
            prevCentury:'Previous Century',
            nextCentury:'Next Century',
            pickHour:'Pick Hour',
            incrementHour:'Increment Hour',
            decrementHour:'Decrement Hour',
            pickMinute:'Pick Minute',
            incrementMinute:'Increment Minute',
            decrementMinute:'Decrement Minute',
            pickSecond:'Pick Second',
            incrementSecond:'Increment Second',
            decrementSecond:'Decrement Second',
            togglePeriod:'Toggle Period',
            selectTime:'Select Time'
        };
    }
    //  Moment默认语言
    if(typeof (moment) != 'undefined'){
        moment.updateLocale($.locale, {
            months:'January_February_March_April_May_June_July_August_September_October_November_December'.split('_'),
            monthsShort:'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
            weekdays:'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split('_'),
            weekdaysShort:'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
            weekdaysMin:'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
            longDateFormat:{LTS:'h:mm:ss A', LT:'h:mm A', L:'MM/DD/YYYY', LL:'MMMM D, YYYY', LLL:'MMMM D, YYYY h:mm A', LLLL:'dddd, MMMM D, YYYY h:mm A'},
            meridiemParse:/[ap]\.?m?\.?/i,
            meridiem:function(hours, minutes, isLower){if(hours > 11) return isLower ? 'pm' : 'PM'; else return isLower ? 'am' : 'AM';},
            calendar:{
                sameDay:'[Today at] LT',
                nextDay:'[Tomorrow at] LT',
                nextWeek:'dddd [at] LT',
                lastDay:'[Yesterday at] LT',
                lastWeek:'[Last] dddd [at] LT',
                sameElse:'L'
            },
            dayOfMonthOrdinalParse:/\d{1,2}/,
            ordinal:'%d',
            relativeTime:{future:'in %s', past:'%s ago', s:'a few seconds', ss:'%d seconds', m:'a minute', mm:'%d minutes', h:'an hour', hh:'%d hours', d:'a day', dd:'%d days', w:'a week', ww:'%d weeks', M:'a month', MM:'%d months', y:'a year', yy:'%d years'},
            week:{dow:0, doy:6}
        });
    }
    //  BootstrapTable
    if(typeof ($.fn.bootstrapTable) != 'undefined'){
        $.extend($.fn.bootstrapTable.defaults, {
            paginationPreText:'previous page',
            paginationNextText:'next page',
            searchSubmitText:'Submit',
            searchResetText:'Reset',
            clipboardSuccessTips:'Copy success!',
            clipboardErrorTips:'Copy error!',
            formatCopyRows:function formatCopyRows(){ return 'Copy Rows'; },
            formatPrint:function formatPrint(){ return 'Print'; },
            formatLoadingMessage:function formatLoadingMessage(){ return 'Loading, please wait'; },
            formatRecordsPerPage:function formatRecordsPerPage(pageNumber){ return "".concat(pageNumber, " rows per page"); },
            formatShowingRows:function formatShowingRows(pageFrom, pageTo, totalRows, totalNotFiltered){
                if(totalNotFiltered !== undefined && totalNotFiltered > 0 && totalNotFiltered > totalRows) return "Showing ".concat(pageFrom, " to ").concat(pageTo, " of ").concat(totalRows, " rows (filtered from ").concat(totalNotFiltered, " total rows)");
                return "Showing ".concat(pageFrom, " to ").concat(pageTo, " of ").concat(totalRows, " rows");
            },
            formatSRPaginationPreText:function formatSRPaginationPreText(){ return 'previous page'; },
            formatSRPaginationPageText:function formatSRPaginationPageText(page){ return "to page ".concat(page); },
            formatSRPaginationNextText:function formatSRPaginationNextText(){ return 'next page'; },
            formatDetailPagination:function formatDetailPagination(totalRows){ return "Showing ".concat(totalRows, " rows"); },
            formatClearSearch:function formatClearSearch(){ return 'Clear Search'; },
            formatSearch:function formatSearch(){ return 'Search'; },
            formatNoMatches:function formatNoMatches(){ return 'No matching records found'; },
            formatPaginationSwitch:function formatPaginationSwitch(){ return 'Hide/Show pagination'; },
            formatPaginationSwitchDown:function formatPaginationSwitchDown(){ return 'Show pagination'; },
            formatPaginationSwitchUp:function formatPaginationSwitchUp(){ return 'Hide pagination'; },
            formatRefresh:function formatRefresh(){ return 'Refresh'; },
            formatToggle:function formatToggle(){ return 'Toggle'; },
            formatToggleOn:function formatToggleOn(){ return 'Show card view'; },
            formatToggleOff:function formatToggleOff(){ return 'Hide card view'; },
            formatColumns:function formatColumns(){ return 'Columns'; },
            formatColumnsToggleAll:function formatColumnsToggleAll(){ return 'Toggle all'; },
            formatFullscreen:function formatFullscreen(){ return 'Fullscreen'; },
            formatAllRows:function formatAllRows(){ return 'All'; },
            formatAutoRefresh:function formatAutoRefresh(){ return 'Auto Refresh'; },
            formatExport:function formatExport(){ return 'Export data'; },
            formatJumpTo:function formatJumpTo(){ return 'GO'; },
            formatAdvancedSearch:function formatAdvancedSearch(){ return 'Advanced search'; },
            formatAdvancedCloseButton:function formatAdvancedCloseButton(){ return 'Close'; },
            formatFilterControlSwitch:function formatFilterControlSwitch(){ return 'Hide/Show controls'; },
            formatFilterControlSwitchHide:function formatFilterControlSwitchHide(){ return 'Hide controls'; },
            formatFilterControlSwitchShow:function formatFilterControlSwitchShow(){ return 'Show controls'; }
        });
    }
    //  Editor.md默认语言
    if(typeof (editormd) != 'undefined'){
        $.extend(editormd.defaults, {
            lang:{
                name:$.locale,
                description:"Open source online Markdown editor, secondary development by Comingdemon.",
                tocTitle:"Catalogue",
                toolbar:{
                    undo:"Undo (Ctrl+Z)",
                    redo:"Redo (Ctrl+Y)",
                    bold:"Bold",
                    del:"strikethrough",
                    italic:"Italic",
                    quote:"quote",
                    ucwords:"Turn the first letter of each word to uppercase",
                    uppercase:"Convert selected to uppercase",
                    lowercase:"Convert selected to lowercase",
                    h1:"Title 1",
                    h2:"Title 2",
                    h3:"Title 3",
                    h4:"Title 4",
                    h5:"Title 5",
                    h6:"Title 6",
                    "list-ul":"Unordered List",
                    "list-ol":"Ordered List",
                    hr:"Horizontal Line",
                    link:"Link",
                    "reference-link":"reference link",
                    image:"Add Picture",
                    dimage:"Add Picture",
                    code:"inline code",
                    "preformatted-text":"Preformatted text / code block (indented style)",
                    "code-block":"Code block (multilingual style)",
                    table:"Add Table",
                    datetime:"Date Time",
                    emoji:"Emoji expression",
                    "html-entities":"HTML entity characters",
                    pagebreak:"Insert a page break",
                    "goto-line":"Go to line",
                    watch:"Turn off real-time preview",
                    unwatch:"Enable real-time preview",
                    preview:"Full window preview HTML (press Shift + ESC to restore)",
                    fullscreen:"Full screen (press ESC to restore)",
                    clear:"Empty",
                    search:"Search",
                    help:"Use Help",
                    info:"About" + editormd.title
                },
                buttons:{enter:"OK", cancel:"Cancel", close:"Close"},
                dialog:{
                    link:{
                        title:"Add link",
                        url:"url",
                        urlTitle:"title",
                        urlEmpty:"Error: Please fill in the link address."
                    },
                    referenceLink:{
                        title:"Add reference link",
                        name:"reference",
                        url:"url",
                        urlId:"ID",
                        urlTitle:"title",
                        nameEmpty:"Error: The name of the reference link cannot be empty.",
                        idEmpty:"Error: Please fill in the ID of the reference link.",
                        urlEmpty:"Error: Please fill in the URL address of the reference link."
                    },
                    image:{
                        title:"Add Picture",
                        url:"url",
                        link:"link",
                        alt:"alt",
                        uploadButton:"Upload",
                        imageURLEmpty:"Error: The image address cannot be empty.",
                        uploadFileEmpty:"Error: The uploaded picture cannot be empty.",
                        formatNotAllowed:"Error: Only image files are allowed to be uploaded. The allowed image file formats are:"
                    },
                    table:{
                        title:"Tables",
                        cellsLabel:"Cells",
                        alignLabel:"Align",
                        rows:"Rows",
                        cols:"Cols",
                        aligns:["Default", "Left align", "Center align", "Right align"]
                    },
                    preformattedText:{
                        title:"Add preformatted text or code block",
                        emptyAlert:"Error: Please fill in the content of the preformatted text or code."
                    },
                    codeBlock:{
                        title:"Add code block",
                        selectLabel:"Code language : &nbsp;",
                        selectDefaultText:"Please select the code language",
                        otherLanguage:"Other Languages",
                        unselectedLanguageAlert:"Error: Please select the language type of the code.",
                        codeEmptyAlert:"Error: Please fill in the code content."
                    },
                    htmlEntities:{title:"HTML entity characters"},
                    emoji:{title:"Emoji"},
                    help:{title:"Use Help"},
                    "goto-line":{
                        title:"Goto line",
                        label:"Enter a line number, range ",
                        error:"Error: "
                    }
                }
            }
        });
    }
    //  AdminTabs默认语言
    if(typeof ($.admin.tabs) != 'undefined'){
        $.extend($.admin.tabs.settings, {
            local:{
                refreshLabel:'Refresh',
                closeThisLabel:'Close',
                closeOtherLabel:'Close Other',
                closeLeftLabel:'Close Left',
                closeRightLabel:'Close Right',
                loadbar:'Loading, please wait...'
            }
        });
    }
    //  Dual默认语言
    if(typeof ($.DemonDualListbox) != 'undefined'){
        $.extend($.DemonDualListbox.settings, {
            filterPlaceHolder:'Search',
            moveAllLabel:'Select All',
            moveSelectedLabel:'Select Checked',
            removeAllLabel:'Remove All',
            removeSelectedLabel:'Remove Checked',
            itemUpLabel:'Move Up',
            itemDownLabel:'Move Down'
        });
    }
}();
