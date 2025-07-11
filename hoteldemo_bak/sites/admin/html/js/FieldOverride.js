Ext.override(Ext.form.Field, {
	getForm : function() {
        var form;
        this.ownerCt.bubble(function(container){
            if (container.form) {
                form = container.form;
                return false;
            }
        }, this);
        
        return form;
    },
    onRender : function(ct, position) {
        Ext.form.Field.superclass.onRender.call(this, ct, position);
        if(!this.el){
            var cfg = this.getAutoCreate();
            if(!cfg.name){
                cfg.name = this.name || this.id;
            }
            if(this.inputType){
                cfg.type = this.inputType;
            }
            this.el = ct.createChild(cfg, position);
        }
        var type = this.el.dom.type;
        if(type){
            if(type == 'password'){
                type = 'text';
            }
            this.el.addClass('x-form-'+type);
        }
        if(this.readOnly){
            this.el.dom.readOnly = true;
        }
        if(this.tabIndex !== undefined){
            this.el.dom.setAttribute('tabIndex', this.tabIndex);
        }
        this.el.addClass([this.fieldClass, this.cls]);
        this.initValue();
        // everything above is from the original onRender function

        // create the appended fields
        var ac = this.append || [];
        if (ac.length > 0) {
            var form = this.getForm();
            
            // create a wrap for all the fields including the one created above
            this.wrap = this.el.wrap({ tag: 'div' });
            // also, wrap the field create above with the same div as the appending fields
            this.el.wrap({ tag: 'div', cls: 'x-form-append', style: 'position: relative' });
            for (var i = 0, len = ac.length; i < len; i++) {
                // if the append field has append fields, delete this
                delete ac[i].append;
                // create a div wrapper with the new field within it.
                var cell = this.wrap.createChild({ tag: 'div', cls: 'x-form-append', style: 'position: relative' });
                var field = new Ext.ComponentMgr.create(ac[i], 'button');
                // render the field
                field.render(cell);
                
                if (form && field.isFormField) {
                    form.items.add(field);
                }
            }
        }
    },
    afterRender: function(){
        if (this.helpText) {
        
            var label = findLabel(this);
            
            if (label) {
                var helpImage = label.createChild({
                    tag: 'img',
                    src: '/images/toolbar/help.png',
                    style: 'margin-bottom: -3px; margin-left: 5px; padding: 0px;' //,
                    //width: 10,
                    //height: 11
                });
                
                Ext.QuickTips.register({
                    target: helpImage,
                    title: '',
                    text: this.helpText,
                    enabled: true
                });
            }
        }
        Ext.form.Field.superclass.afterRender.call(this);
        this.initEvents();
    }
});
Ext.QuickTips.init();
var findLabel = function(field){

    var wrapDiv = null;
    var label = null
    
    //find form-item and label
    wrapDiv = field.getEl().up('div.x-form-item');
    if (wrapDiv) {
        label = wrapDiv.child('label');
    }
    if (label) {
        return label;
    }
} 
