/* Author：mingyuhisoft@163.com
 * Github:https://github.com/imingyu/jquery.mloading
 * Npm:npm install jquery.mloading.js
 * Date：2016-7-4
 */

;(function (root, factory) {
    'use strict';

    if (typeof module === 'object' && typeof module.exports === 'object') {
        factory(require('jquery'),root);
    } if(typeof define ==="function"){
        if(define.cmd){
            define(function(require, exports, module){
                var $ = require("jquery");
                factory($,root);
            });
        }else{
            define(["jquery"],function($){
                factory($,root);
            });
        }
    }else {
        factory(root.jQuery,root);
    }
} (typeof window !=="undefined" ? window : this, function ($, root, undefined) {
    'use strict';
    if(!$){
        $ = root.jQuery || null;
    }
    if(!$){
        throw new TypeError("必须引入jquery库方可正常使用！");
    }

    var arraySlice = Array.prototype.slice,
        comparison=function (obj1,obj2) {
            var result=true;
            for(var pro in obj1){
                if(obj1[pro] !== obj2[obj1]){
                    result=true;
                    break;
                }
            }
            return result;
        }

    function MLoading(dom,options) {
        options=options||{};
        this.dom=dom;
        this.options=$.extend(true,{},MLoading.defaultOptions,options);
        this.curtain=null;
        this.render().show();
    }
    MLoading.prototype={
        constructor:MLoading,
        initElement:function () {
            var dom=this.dom,
                ops=this.options;
            var curtainElement=dom.children(".mloading"),
                bodyElement = curtainElement.children('.mloading-body'),
                barElement = bodyElement.children('.mloading-bar'),
                iconElement = barElement.children('.mloading-icon'),
                textElement = barElement.find(".mloading-text");
            if (curtainElement.length == 0) {
                curtainElement = $('<div class="mloading"></div>');
                dom.append(curtainElement);
            }
            if (bodyElement.length == 0) {
                bodyElement = $('<div class="mloading-body"></div>');
                curtainElement.append(bodyElement);
            }
            if (barElement.length == 0) {
                barElement = $('<div class="mloading-bar"></div>');
                bodyElement.append(barElement);
            }
            if (iconElement.length == 0) {
                var _iconElement=document.createElement(ops.iconTag);
                iconElement = $(_iconElement);
                iconElement.addClass("mloading-icon");
                barElement.append(iconElement);
            }
            if (textElement.length == 0) {
                textElement = $('<span class="mloading-text"></span>');
                barElement.append(textElement);
            }
            
            this.curtainElement=curtainElement;
            this.bodyElement = bodyElement;
            this.barElement = barElement;
            this.iconElement = iconElement;
            this.textElement = textElement;
            return this;
        },
        render:function () {
            var dom=this.dom,
                ops=this.options;
            this.initElement();
            if(dom.is("html") || dom.is("body")){
                this.curtainElement.addClass("mloading-full");
            }else{
                this.curtainElement.removeClass("mloading-full");

                if(!dom.hasClass("mloading-container")){
                    dom.addClass("mloading-container");
                }
            }
            if(ops.mask){
                this.curtainElement.addClass("mloading-mask");
            }else{
                this.curtainElement.removeClass("mloading-mask");
            }
            if(ops.content!="" && typeof ops.content!="undefined"){
                if(ops.html){
                    this.bodyElement.html(ops.content);
                }else{
                    this.bodyElement.text(ops.content);
                }
            }else{
                this.iconElement.attr("src",ops.icon);
                if(ops.html){
                    this.textElement.html(ops.text);
                }else{
                    this.textElement.text(ops.text);
                }
            }

            return this;
        },
        setOptions:function (options) {
            options=options||{};
            var oldOptions = this.options;
            this.options = $.extend(true,{},this.options,options);
            if(!comparison(oldOptions,this.options)) this.render();
        },
        show:function () {
            var dom=this.dom,
                ops=this.options,
                barElement=this.barElement;
            this.curtainElement.addClass("active");
            barElement.css({
                "marginTop":"-"+barElement.outerHeight()/2+"px",
                "marginLeft":"-"+barElement.outerWidth()/2+"px"
            });

            return this;
        },
        hide:function () {
            var dom=this.dom,
                ops=this.options;
            this.curtainElement.removeClass("active");
            if(!dom.is("html") && !dom.is("body")){
                dom.removeClass("mloading-container");
            }
            return this;
        },
        destroy:function () {
            var dom=this.dom,
                ops=this.options;
            this.curtainElement.remove();
            if(!dom.is("html") && !dom.is("body")){
                dom.removeClass("mloading-container");
            }
            dom.removeData(MLoading.dataKey);
            return this;
        }
    };
    MLoading.dataKey="MLoading";
    MLoading.defaultOptions = {
        text:"",
        iconTag:"img",
        icon:"/images/loading1.gif",
        html:false,
        content:"",//设置content后，text和icon设置将无效
        mask:true//是否显示遮罩（半透明背景）
    };

    $.fn.mLoading=function (options) {
        var ops={},
            funName="",
            funArgs=[];
        if(typeof options==="object"){
            ops = options;
        }else if(typeof options ==="string"){
            funName=options;
            funArgs = arraySlice.call(arguments).splice(0,1);
        }
        return this.each(function (i,element) {
            var dom = $(element),
                plsInc=dom.data(MLoading.dataKey);
            if(!plsInc){
                plsInc=new MLoading(dom,ops);
            }

            if(funName){
                var fun = plsInc[funName];
                if(typeof fun==="function"){
                    fun.apply(plsInc,funArgs);
                }
            }
        });
    }
}));