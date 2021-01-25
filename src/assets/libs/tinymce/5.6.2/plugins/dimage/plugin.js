(function(){
    'use strict';
    var global = tinymce.util.Tools.resolve('tinymce.PluginManager');
    var dimageChoose = function(editor){
        return editor.getParam('dimageChoose', {}, 'object');
    };
    var dimageOptions = function(editor){
        return editor.getParam('dimageOptions', {}, 'object');
    };
    var dimageUploader = function(editor){
        return editor.getParam('dimageUploader', null, 'function');
    };
    var Settings = {dimageChoose:dimageChoose, dimageOptions:dimageOptions, dimageUploader:dimageUploader};
    var open = function(editor){
        var dimageChoose = Settings.dimageChoose(editor);
        var dimageOptions = Settings.dimageOptions(editor);
        var dimageUploader = Settings.dimageUploader(editor);
        if(dimageUploader && (dimageChoose.uploadUrl || '') == '')
            return editor.notificationManager.open({text:'UploadUrl is undefined', type:'error', closeButton:false, timeout:3000});
        $.admin.layer.image(dimageChoose, dimageOptions, function(index, layero){
            var data, src;
            var setData = function(obj){
                data = obj;
                var insert = function(src){
                    $.admin.layer.close(index);
                    if(src instanceof Blob || src instanceof String) editor.insertContent('<img src="' + (typeof (src) == 'object' ? URL.createObjectURL(src) : src) + '">');
                    else if(typeof (src) == 'object') $.each(src, function(k, v){ editor.insertContent('<img src="' + v + '">'); });
                };
                src = dimageUploader ? dimageUploader(dimageChoose.uploadUrl, data, layero, insert) : data;
                if(src){
                    $.admin.layer.close(index);
                    editor.insertContent('<img src="' + (typeof (src) == 'object' ? URL.createObjectURL(src) : src) + '">');
                }
            };
            if(dimageChoose.cropper){
                if(dimageChoose.base64) setData(layero.cropper.getCroppedCanvas().toDataURL(layero.data.mime, dimageChoose.cropper.quality || 0.9));
                else layero.cropper.getCroppedCanvas().toBlob(setData, layero.data.mime, dimageChoose.cropper.quality || 0.9);
            }else setData(layero.result);
        });
    };

    var register = function(editor){
        editor.addCommand('mceDimage', function(){
            open(editor);
        });
    };
    var Commands = {register:register};

    var register$1 = function(editor){
        editor.ui.registry.addButton('dimage', {
            icon:'image',
            tooltip:'Insert/edit image',
            onAction:function(){
                return editor.execCommand('mceDimage');
            }
        });
        editor.ui.registry.addMenuItem('dimage', {
            icon:'image',
            text:'Image...',
            onAction:function(){
                return editor.execCommand('mceDimage');
            }
        });
    };
    var Buttons = {register:register$1};

    function Plugin(){
        global.add('dimage', function(editor){
            setTimeout(function(){editor.dom.addStyle('p img {max-width:100%}');});
            Commands.register(editor);
            Buttons.register(editor);
        });
    }

    Plugin();

}());
