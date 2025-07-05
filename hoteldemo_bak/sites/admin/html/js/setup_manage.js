Ext.onReady(function() {
	Ext.QuickTips.init();
	Ext.form.Field.prototype.msgTarget = 'side';
	
	var siteSetupForm = new Ext.FormPanel({
		id        : 'setup-form',
		title	  : 'Set Up Site',
		url       : '/admin/site/setupsite',
		frame     : true,
		labelAlign: 'left',
		bodyStyle : 'padding:0px',
		//width     : 450,
		height      : 408,
		//layout    : 'column',					// Specifies that the items will now be arranged in columns
		renderTo  : 'setupRender',
		items	  : [{
				xtype:'label',
				id:'label',
				html:'<span style="color:red; font-weight:bold;">This feature will be migrating the files on prototype folder to this site folder.<br/>If the files already exist in this site folder, it will be replaced.</span><br/><br/>'
		},
		{
				xtype:'button',
				text :'Copy from prototype',
				hidden :modReadOnly,
				handler:function() {
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to copy files from prototype to this site?', function(btn) {
						if ( btn == 'yes' ) {
	            			siteSetupForm.getForm().submit({
								waitTitle: 'Connecting to the database...',
								waitMsg  : 'Please Wait...',
								params	 : {logic: 'restore'},
								success:function(form, action){
		                           	Ext.Msg.alert("Information", "Copying files from prototype is successful");                  
		                        },
		                        failure:function(form, action){
		                            Ext.Msg.alert("Error", "Failed to copy files from prototype, please retry again." + action.result.errorInfo);
		                        }
							});
	            		}
					});
				}
		}]/*,		
		buttonAlign: 'left',	 
		buttons:[
			{
				text:'Copy from prototype',
				handler:function() {
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to copy showads files from prototype to this site?', function(btn) {
						if ( btn == 'yes' ) {
	            			showadsSetupForm.getForm().submit({
								waitTitle: 'Connecting to the database...',
								waitMsg  : 'Please Wait...',
								params	 : {logic: 'restore'},
								success:function(form, action){
		                           	Ext.Msg.alert("Information", "Copying file from prototype is successful");                  
		                        },
		                        failure:function(form, action){
		                            Ext.Msg.alert("Error", "Failed to copy showads file from prototype, please retry again." + action.result.errorInfo);
		                        }
							});
	            		}
					});
				}
			}
		]*/
	});	
});