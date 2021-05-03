@extends('admin::layout.vessel.container')
@section('container.link.before')
    <script src="{{admin_static('libs/tinymce/5.6.2/tinymce.min.js')}}"></script>
@endsection
@section('container.content')
    <h4 class="mt-0 header-title">Rich Text Editor</h4>
    <p class="text-muted mb-4">使用$.admin.editor来构建（基于TinyMCE）</p>
    <form id="validate">
        <div class="form-group">
            <label>简单示例</label>
            <textarea name="simple" class="form-control" rows="20" placeholder="请输入内容"></textarea>
        </div>
        <div class="form-group">
            <label>复杂示例</label>
            <textarea name="complex" class="form-control" rows="20" placeholder="请输入内容"></textarea>
        </div>
        <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">提交</button>
            <button type="reset" class="btn btn-secondary waves-effect">重置</button>
        </div>
    </form>
@endsection
@section('container.script')
    <script>
        var dimage = {
            dimageChoose:{maxWidth:800, cropper:true, dispose:false, dump:'{{admin_url('extend/image/dump')}}', uploadUrl:'{{admin_url('extend/image/upload')}}'},
            dimageUploader:function(url, result, data, call){$.admin.api.upload(url, {blob:result, name:data.name, dir:'upload/admin/example/editor/' + moment().format('YYYYMMDD')}, function(data){ call(data); });}
        };
        $.admin.editor('#validate [name="simple"]', $.extend({menubar:false}, dimage));
        $.admin.editor('#validate [name="complex"]', $.extend({
            min_height:400,
            max_height:1600,
            plugins:'dimage print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template code codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount imagetools textpattern help emoticons autosave autoresize',
            toolbar:'code undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify outdent indent | styleselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | table image dimage media charmap emoticons hr pagebreak insertdatetime print preview | fullscreen |  lineheight',
            menubar:'file edit insert view format table tools help',
            fontsize_formats:'12px 14px 16px 18px 24px 36px 48px 56px 72px',
            font_formats:'微软雅黑=Microsoft YaHei,Helvetica Neue,PingFang SC,sans-serif;苹果苹方=PingFang SC,Microsoft YaHei,sans-serif;宋体=simsun,serif;仿宋体=FangSong,serif;黑体=SimHei,sans-serif;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats;知乎配置=BlinkMacSystemFont, Helvetica Neue, PingFang SC, Microsoft YaHei, Source Han Sans SC, Noto Sans CJK SC, WenQuanYi Micro Hei, sans-serif;小米配置=Helvetica Neue,Helvetica,Arial,Microsoft Yahei,Hiragino Sans GB,Heiti SC,WenQuanYi Micro Hei,sans-serif',
            link_list:[
                {title:'预置链接1', value:'http://www.tinymce.com'},
                {title:'预置链接2', value:'http://tinymce.ax-z.cn'}
            ],
            image_list:[
                {title:'预置图片1', value:'https://www.tiny.cloud/images/glyph-tinymce@2x.png'},
                {title:'预置图片2', value:'https://www.baidu.com/img/bd_logo1.png'}
            ],
            image_class_list:[
                {title:'None', value:''},
                {title:'Some class', value:'class-name'}
            ],
            file_picker_callback:function(callback, value, meta){
                if(meta.filetype === 'file'){
                    callback('https://www.baidu.com/img/bd_logo1.png', {text:'My text'});
                }
                if(meta.filetype === 'image'){
                    callback('https://www.baidu.com/img/bd_logo1.png', {alt:'My alt text'});
                }
                if(meta.filetype === 'media'){
                    callback('movie.mp4', {source2:'alt.ogg', poster:'https://www.baidu.com/img/bd_logo1.png'});
                }
            },
            templates:[
                {title:'模板1', description:'介绍文字1', content:'模板内容'},
                {title:'模板2', description:'介绍文字2', content:'<div class="mceTmpl"><span class="cdate">CDATE</span>，<span class="mdate">MDATE</span>，我的内容</div>'}
            ],
            extended_valid_elements:'script[src]',
            template_cdate_format:'[CDATE: %m/%d/%Y : %H:%M:%S]',
            template_mdate_format:'[MDATE: %m/%d/%Y : %H:%M:%S]',
            autosave_ask_before_unload:false,
            toolbar_mode:'wrap',
            images_upload_handler:function(blobInfo, succFun, failFun){
                succFun(URL.createObjectURL(blobInfo.blob()));
            }
        }, dimage));
        $.admin.form('#validate', {
            callback:{
                success:function(e){
                    var data = e.value();
                    var html = '';
                    html += '<h5>简单示例</h5>';
                    html += data.simple;
                    html += '<hr>';
                    html += '<h5>复杂示例</h5>';
                    html += data.complex;
                    var res = $('<div>').html(html);
                    res.find('table').addClass('table table-striped table-hover table-bordered');
                    $.admin.modal.open({title:'Editor', content:res.prop('outerHTML'), size:'lg'});
                }
            }
        });
    </script>
@endsection
