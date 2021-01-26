/*!
 * Image (upload) dialog plugin for Editor.md
 *
 * @file        image-dialog.js
 * @author      pandao
 * @version     1.3.4
 * @updateTime  2015-06-09
 * {@link       https://github.com/pandao/editor.md}
 * @license     MIT
 */

(function(){

    var factory = function(exports){

        var pluginName = "dimage-dialog";

        exports.fn.dimageDialog = function(){
            var cm = this.cm;
            var lang = this.lang;
            var editor = this.editor;
            var settings = this.settings;
            var cursor = cm.getCursor();
            var selection = cm.getSelection();
            var imageLang = lang.dialog.image;
            var classPrefix = this.classPrefix;
            var dialogName = classPrefix + pluginName, dialog;

            cm.focus();

            if(editor.find("." + dialogName).length < 1){
                var guid = (new Date).getTime();
                var dialogContent = "<div class=\"" + classPrefix + "form\">" +
                    "<label>" + imageLang.url + "</label><input type='text' data-url />" + (function(){
                        return (settings.imageUpload) ? "<div class='" + classPrefix + "file-input'>" + "<button type='button'>" + imageLang.uploadButton + "</button>" + "</div>" : "";
                    })() + "<br/><label>" + imageLang.alt + "</label><input type='text' value='" + selection + "' data-alt /><br/><label>" + imageLang.link + "</label><input type='text' value='http://' data-link /><br/></div>";

                dialog = this.createDialog({
                    title:imageLang.title,
                    width:(settings.imageUpload) ? 465 : 380,
                    height:254,
                    name:dialogName,
                    content:dialogContent,
                    mask:settings.dialogShowMask,
                    drag:settings.dialogDraggable,
                    lockScreen:settings.dialogLockScreen,
                    maskStyle:{
                        opacity:settings.dialogMaskOpacity,
                        backgroundColor:settings.dialogMaskBgColor
                    },
                    buttons:{
                        enter:[lang.buttons.enter, function(){
                            var url = this.find("[data-url]").val();
                            var alt = this.find("[data-alt]").val();
                            var link = this.find("[data-link]").val();

                            if(url === ""){
                                $.admin.alert.danger(imageLang.imageURLEmpty);
                                return false;
                            }

                            var altAttr = (alt !== "") ? " \"" + alt + "\"" : "";

                            if(link === "" || link === "http://"){
                                cm.replaceSelection("![" + alt + "](" + url + altAttr + ")");
                            }else{
                                cm.replaceSelection("[![" + alt + "](" + url + altAttr + ")](" + link + altAttr + ")");
                            }

                            if(alt === ""){
                                cm.setCursor(cursor.line, cursor.ch + 2);
                            }

                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }],

                        cancel:[lang.buttons.cancel, function(){
                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }]
                    }
                });

                dialog.attr("id", classPrefix + "image-dialog-" + guid);

                dialog.find('.' + classPrefix + 'file-input button').on('click', function(){
                    var dimageChoose = settings.dimageChoose || {};
                    var dimageOptions = settings.dimageOptions || {};
                    var dimageUploader = settings.dimageUploader;
                    if(dimageUploader && (dimageChoose.uploadUrl || '') == '')
                        return $.admin.alert.danger('UploadUrl is undefined');
                    $.admin.layer.image(dimageChoose, dimageOptions, function(index, layero){
                        var data, src;
                        var setData = function(obj){
                            data = obj;
                            var insert = function(src){
                                $.admin.layer.close(index);
                                if(src instanceof Blob || src instanceof String) dialog.find("[data-url]").val((typeof (src) == 'object' ? URL.createObjectURL(src) : src));
                                else if(typeof (src) == 'object') $.each(src, function(k, v){ dialog.find("[data-url]").val(v); });
                            };
                            src = dimageUploader ? dimageUploader(dimageChoose.uploadUrl, data, layero, insert) : data;
                            if(src){
                                $.admin.layer.close(index);
                                dialog.find("[data-url]").val((typeof (src) == 'object' ? URL.createObjectURL(src) : src));
                            }
                        };
                        if(dimageChoose.cropper){
                            if(dimageChoose.base64) setData(layero.cropper.getCroppedCanvas().toDataURL(layero.data.mime, dimageChoose.cropper.quality || 0.9));
                            else layero.cropper.getCroppedCanvas().toBlob(setData, layero.data.mime, dimageChoose.cropper.quality || 0.9);
                        }else setData(layero.result);
                    });
                });
            }

            dialog = editor.find("." + dialogName);
            dialog.find("[type=\"text\"]").val("");
            dialog.find("[type=\"file\"]").val("");
            dialog.find("[data-link]").val("http://");

            this.dialogShowMask(dialog);
            this.dialogLockScreen();
            dialog.show();

        };

    };

    // CommonJS/Node.js
    if(typeof require === "function" && typeof exports === "object" && typeof module === "object"){
        module.exports = factory;
    }else if(typeof define === "function")  // AMD/CMD/Sea.js
    {
        if(define.amd){ // for Require.js

            define(["editormd"], function(editormd){
                factory(editormd);
            });

        }else{ // for Sea.js
            define(function(require){
                var editormd = require("./../../editormd");
                factory(editormd);
            });
        }
    }else{
        factory(window.editormd);
    }

})();
