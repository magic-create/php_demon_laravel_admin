@extends('admin::layout.vessel.blank')
@section('container.content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-2">
                <div class="card-body">
                    <h4 class="header-title">Form Validation</h4>
                    <p class="text-muted mb-3">使用$.admin.form来验证（基于jquery.validate）</p>
                    <form id="validate">
                        <div class="form-group">
                            <label>必填内容</label>
                            <input type="text" name="required" class="form-control" placeholder="请输入任意内容"/>
                        </div>
                        <div class="form-group">
                            <label>正整数</label>
                            <input type="number" name="digits" class="form-control" placeholder="请输入任意正整数"/>
                        </div>
                        <div class="form-group">
                            <label>最大值限制</label>
                            <input type="number" name="max" class="form-control" placeholder="请输入不大于10的数字"/>
                        </div>
                        <div class="form-group">
                            <label>长度范围</label>
                            <textarea name="rangelength" class="form-control" rows="5" placeholder="请输入10至30个字之间的任意内容"></textarea>
                        </div>
                        <div class="form-group">
                            <label>URL链接</label>
                            <input type="url" name="url" class="form-control" placeholder="请输入http开头的URL地址"/>
                        </div>
                        <div class="form-group">
                            <label>二次确认密码</label>
                            <div><input type="password" name="ref" class="form-control" placeholder="请输入任意密码"/></div>
                            <div class="mt-1"><input type="password" name="password" class="form-control" placeholder="请再次输入相同的密码"/></div>
                        </div>
                        <div class="form-group">
                            <label>开关</label>
                            <div class="check-group">
                                <input name="switch" class="switch" type="checkbox">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>复选框</label>
                            <div class="check-group">
                                @for($i=1;$i<=10;$i++)
                                    <label><input name="checkbox[]" class="checkbox" type="checkbox" value="{{$i}}">选项-{{$i}}</label>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label>单选框</label>
                            <div class="check-group">
                                @for($i=1;$i<=5;$i++)
                                    <label><input name="radio" class="radio" type="radio" value="{{$i}}">选项-{{$i}}</label>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label>下拉框</label>
                            <select name="select" class="form-control select">
                                <option value="">请选择</option>
                                @for($i=1;$i<=10;$i++)
                                    <option value="{{$i}}">选项-{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>多选下拉框</label>
                            <select name="selects" class="form-control select" multiple>
                                @for($i=1;$i<=10;$i++)
                                    <option value="{{$i}}">选项-{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>二级联动</label>
                            <div class="form-row">
                                <div class="col-md-6 mb-1 mb-md-0">
                                    <select name="linkage2[1]" data-level="1" class="form-control select"></select>
                                </div>
                                <div class="col-md-6">
                                    <select name="linkage2[2]" data-level="2" class="form-control select"></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>三级联动</label>
                            <div class="form-row">
                                <div class="col-md-4 mb-1 mb-md-0">
                                    <select name="linkage3[1]" data-level="1" class="form-control select"></select>
                                </div>
                                <div class="col-md-4 mb-1 mb-md-0">
                                    <select name="linkage3[2]" data-level="2" class="form-control select"></select>
                                </div>
                                <div class="col-md-4">
                                    <select name="linkage3[3]" data-level="3" class="form-control select"></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>自定义</label>
                            <input type="text" name="custom" class="form-control" placeholder="请输入当前年份，如：2021"/>
                        </div>
                        <div class="form-group">
                            <label>后台校验</label>
                            <input type="text" name="api" class="form-control" placeholder="输入任意内容，不输入的话由后台校验"/>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">提交</button>
                            <button type="reset" class="btn btn-secondary waves-effect">重置</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-2">
                <div class="card-body">
                    <h4 class="header-title">More Advanced</h4>
                    <p class="text-muted mb-3">扩展表单元素（基于各类jquery插件）</p>
                    <form id="advanced">
                        <div class="form-group">
                            <label>日期选择</label>
                            <input type="text" name="date" class="form-control date" placeholder="点击选择日期"/>
                        </div>
                        <div class="form-group">
                            <label>日期和时间选择</label>
                            <input type="text" name="datetime" class="form-control date" placeholder="点击选择日期和时间"/>
                        </div>
                        <div class="form-group">
                            <label>时间选择</label>
                            <input type="text" name="time" class="form-control date" placeholder="点击选择时间"/>
                        </div>
                        <div class="form-group">
                            <label>颜色选择</label>
                            <div class="input-group color">
                                <input type="text" class="form-control" value="#000" placeholder="点击选择颜色"/>
                                <div class="input-group-append"><span class="input-group-text color-addon"></span></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>文件选择</label>
                            <input type="file" name="file" class="form-control file">
                        </div>
                        <div class="form-group">
                            <label>数字滑块</label>
                            <input type="text" name="slider" class="form-control slider"/>
                        </div>
                        <div class="form-group">
                            <label>区间滑块</label>
                            <input type="text" name="sliders" class="form-control slider"/>
                        </div>
                        <div class="form-group">
                            <label>复制内容</label>
                            <div class="input-group">
                                @php($rand = bomber()->rand())
                                <input type="text" class="form-control" placeholder="点击右侧按钮可复制" readonly/>
                                <div class="input-group-append">
                                    <button class="btn btn-secondary clipboard" data-clipboard-text="{{$rand}}" type="button">开始复制</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">复制的内容是：{{$rand}}</small>
                        </div>
                        <div class="form-group">
                            <label>穿梭框</label>
                            <select name="dual" class="form-control dual" multiple>
                                @for($i=1;$i<=10;$i++)
                                    <option value="{{$i}}">选项-{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Markdown</label>
                            <div class="row">
                                <div class="col-6">
                                    <textarea name="markdown" class="form-control markdown" rows="15" placeholder="请输入Markdown代码"></textarea>
                                </div>
                                <div id="markdown" class="col-6"></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">提交</button>
                            <button type="reset" class="btn btn-secondary waves-effect">重置</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('container.script')
    <script>
        // Form Validation
        $.admin.switch('#validate .switch');
        $.admin.checkbox('#validate .checkbox');
        $.admin.radio('#validate .radio');
        $.admin.select('#validate .select');
        $.admin.linkage(['#validate [name="linkage2[1]"][data-level="1"]', '#validate [name="linkage2[2]"][data-level="2"]'], {
            data:JSON.parse('{!!json_encode($linkage)!!}'),
            value:[889, 978]
        });
        $.admin.linkage(['#validate [name="linkage3[1]"][data-level="1"]', '#validate [name="linkage3[2]"][data-level="2"]', '#validate [name="linkage3[3]"][data-level="3"]'], {
            data:JSON.parse('{!!json_encode($linkage)!!}'),
            value:[223, 268, 272],
            placeholderStatus:true,
            placeholderList:['请选择一级', '请选择二级', '请选择三级']
        });
        var modalContent = function(status, data){return '<p>' + status + ':</p><textarea class="form-control" rows="10">' + JSON.stringify(data) + '</textarea>';};
        $.admin.form('#validate', {
            list:{
                required:true,
                digits:{digits:true},
                max:{max:10},
                rangelength:{rangelength:[10, 30]},
                url:{url:true},
                ref:true,
                password:{equalTo:'[name="ref"]'},
                switch:true,
                'checkbox[]':{rangelength:{rule:[1, 5], message:'请选择1至5个选项'}},
                radio:true,
                select:{min:{rule:1, message:'请选择1个选项'}},
                selects:{minlength:{rule:1, message:'至少需要选择1个选项'}},
                custom:{function:{rule:function(e){return $(e).val() == (new Date).getFullYear(); }, message:'输入的年份不正确'}}
            },
            callback:{
                success:function(e){
                    var prompt = $.admin.modal.prompt({content:'请输入和自定义相同的内容(年份)'}, function(value){
                        var data = e.value();
                        if(value != data.custom) return $.admin.layer.alert('输入的内容和自定义的内容不相同', {icon:0});
                        $.admin.modal.close(prompt);
                        $.post('', data, function(data){$.admin.modal.alert(modalContent('success', data));}).fail(function(xhr){$.admin.modal.alert(modalContent('fail', JSON.parse(xhr.responseText)));});
                    }, function(){$.admin.layer.alert('取消提交', {icon:0});});
                }
            }
        });
        //  More Advanced
        $.admin.date('#advanced .date[name="date"]', {format:'YYYY-MM-DD'});
        $.admin.date('#advanced .date[name="datetime"]', {format:'YYYY-MM-DD HH:mm:ss'});
        $.admin.date('#advanced .date[name="time"]', {format:'HH:mm:ss'});
        $.admin.color('#advanced .color');
        $.admin.file('#advanced .file');
        $.admin.slider('#advanced .slider[name="slider"]');
        $.admin.slider('#advanced .slider[name="sliders"]', {type:'double', grid:true, grid_num:10, prefix:"￥", max:1000, step:0.01, from:150, to:600});
        $.admin.clipboard('#advanced .clipboard', function(){$.admin.layer.msg('复制成功', {icon:6, time:2000});}, function(){$.admin.layer.msg('复制失败', {icon:5, time:2000});});
        $.admin.dualbox('#advanced .dual');
        var height = function(){ return $('#markdown').height($('#advanced .markdown').height());};
        height().css('overflow-y', 'auto');
        $('#advanced .markdown').on("keyup blur", function(){$('#markdown').html(marked($(this).val()));}).on('resize', height);
        $.admin.form('#advanced', {callback:{success:function(e){$.admin.modal.alert(modalContent('advanced', e.value()));}}});
    </script>
@endsection
