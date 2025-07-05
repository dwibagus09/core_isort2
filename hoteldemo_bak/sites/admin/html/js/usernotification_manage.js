Ext.onReady(function() {
	Ext.QuickTips.init();
	Ext.form.Field.prototype.msgTarget = 'side';
	
	Ext.QuickTips.init();
	Ext.form.Field.prototype.msgTarget = 'side';
		
	var UserNotification = Ext.data.Record.create([
		{name: 'user_notification_id'},
		{name: 'userid'},
		{name: 'notification_id'},
		{name: 'notification'},
		{name: 'notification_method_id'},
		{name: 'site_id'},
		{name: 'adminusername'},
		{name: 'email'},
		{name: 'description'}
	]);
	
	var userNotificationStore = new Ext.data.Store({
		url     : '/admin/user/getusernotifications',
		autoLoad: true,
		reader  : new Ext.data.JsonReader( {
			root: "data",					// The property which contains an Array of row objects
			totalProperty: 'total'
		}, UserNotification)
	});
	
	var userNotificationCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	var userNotificationColModel = new Ext.grid.ColumnModel([
		userNotificationCheckbox,
		{id:'userid'			, header:"User ID" 	, width:150 , sortable:true, locked:true, dataIndex:'userid', hidden:true},
		{id:'adminusername'		, header:"Username" 	, width:150 , sortable:true, locked:true, dataIndex:'adminusername'},
		{id:'email'				, header:"Email" 	, width:150 , sortable:true, locked:true, dataIndex:'email'},
		{id:'notification'		, header:"Notification" 	, width:150 , sortable:true, locked:true, dataIndex:'notification'},
		{id:'description'		, header:"Notification Method" 	, width:150 , sortable:true, locked:true, dataIndex:'description'}
	]);	
	
	var userNotificationGrid = new Ext.grid.GridPanel({ 
		store		: userNotificationStore,
		colModel	: userNotificationColModel,
		selModel    : userNotificationCheckbox,
		height      : 400,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
		tbar: [{
				text   : 'Add',
				tooltip: 'Add  user notification',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
					//document.getElementById('editUserNotificationRender').style.visibility = 'visible';
					editUserNotificationWin.show();
					editUserNotificationForm.getForm().reset();
				}
			},'-',{
				text   : 'Delete',
				tooltip: 'Delete selected user notification',
				iconCls: 'delete',
				hidden :modReadOnly,
				handler: function() {
	            	//document.getElementById('editUserNotificationRender').style.visibility = 'hidden';
	            	var selection = userNotificationGrid.getSelectionModel().getSelections();
					var countRows = userNotificationGrid.getSelectionModel().getCount();	
					if(countRows==0) Ext.MessageBox.alert('Error', 'Please choose at least one user notification to delete');
					else
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete these user notifications?', function(btn) {
	            		if ( btn == 'yes' ) {
	            			var selection = userNotificationGrid.getSelectionModel().getSelections();
							var countRows = userNotificationGrid.getSelectionModel().getCount();	
							var store = userNotificationGrid.getStore();											
							if(countRows > 0){	
								var strings = '';
								for(i = 0 ;i < countRows;i++){										
									strings += selection[i].get('user_notification_id').toString()+',';	
								}															
								strings = strings.substring(0,strings.length-1);
								SendItViaAjax(strings, '/admin/user/deleteusernotification');									
							}
	            		}
	            	});
				}
			}
		]
	});
	
	var userNotificationForm = new Ext.FormPanel({
		id        : 'user-notification-form',
		title	  : 'User Notification',
		frame     : true,
		labelAlign: 'left',
		width     : '100%',
		height	  : 450,
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		renderTo  : 'userNotificationRender',
		items	  : [
			userNotificationGrid
		]
	});	
	
	var usernameStore = new Ext.data.JsonStore({
		url: '/admin/user/getadminuserbyusername',
	    root: 'data',
	    autoLoad:true,
	    fields: ['adminuserid','adminusername']
	});
	
	var cmbUsername = new Ext.form.ComboBox(
    {   
    	allowBlank    : false,
		displayField  : 'adminusername',
		fieldLabel    : 'Username',
		forceSelection: true,
		hiddenName    : 'adminuserid',
		listWidth     : 220,
		name          : 'adminuserid',
		selectOnFocus : true,
		store         : usernameStore,
		minChars	  : 1,
		triggerAction : 'all',
		hideTrigger	  : true,
		typeAhead     : true,
		id			  : 'usernamecmb',
		valueField    : 'adminuserid',
		hiddenValue	  : 'adminuserid',
		width         : 220,
		forceSelection:true,
		enableKeyEvents:true,
    	listeners:{
			select:function(combo, record, index) {
				this.setRawValue(record.get('adminusername'));
				
			},
			
			blur:function() {
				var val = this.getRawValue();
				this.setRawValue.defer(0, this, [val]);
			},
			 
			keypress:{buffer:100, fn:function() {
				if(!this.getRawValue()) {
					this.doQuery('', true);
				}
			}}
		}
    });	
	
	var cmbNotification = new Ext.form.ComboBox({
		allowBlank    : false,
		displayField  : 'description',
		fieldLabel    : 'Notification',
		forceSelection: true,
		hiddenName    : 'notification_id',
		listWidth     : 220,
		name          : 'notification_id',
		selectOnFocus : true,
		store         : new Ext.data.JsonStore({
			url: '/admin/user/getnotifications',
		    root: 'data',
		    autoLoad:true,
		    fields: ['notification_id','description']
		}),
		triggerAction : 'all',
		typeAhead     : true,
		id			  : 'notificationcmb',
		valueField    : 'notification_id',
		hiddenValue	  : 'notification_id',
		width         : 220
	});
	
	var cmbNotificationMethod = new Ext.form.ComboBox({
		allowBlank    : false,
		displayField  : 'description',
		fieldLabel    : 'Notification Method',
		forceSelection: true,
		hiddenName    : 'notification_method_id',
		listWidth     : 220,
		name          : 'notification_method_id',
		selectOnFocus : true,
		store         : new Ext.data.JsonStore({
			url: '/admin/user/getnotificationmethod',
		    root: 'data',
		    autoLoad:true,
		    fields: ['notification_method_id','description']
		}),
		triggerAction : 'all',
		typeAhead     : true,
		id			  : 'notificationmethodcmb',
		valueField    : 'notification_method_id',
		hiddenValue	  : 'notification_method_id',
		width         : 220
	});
	
	var fieldItems = [{
			xtype: 'hidden',
			id   : 'user_notification_id',
			name : 'user_notification_id'
		 },
		 cmbUsername,
		 cmbNotification,
		 cmbNotificationMethod
	];
		
	var editUserNotificationForm = new Ext.FormPanel({
		id         : 'edit-user-notification-form',
		//title	   : 'Edit User Notifications',
		method     : 'POST',
		url        : '/admin/user/setusernotification',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		//renderTo   : 'editUserNotificationRender',
		//width	   : 400,
		labelWidth : 130,
		items: fieldItems/*,	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {						
					editUserNotificationForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, editUserNotificationResp){	
							document.getElementById('editUserNotificationRender').style.visibility = 'hidden';
							editUserNotificationForm.getForm().reset();		
							userNotificationStore.load();					
						}	
					})
				}
			},{
				text:'Close',
				handler:function() {
					document.getElementById('editUserNotificationRender').style.visibility = 'hidden';
				}
			}
		]*/
	});
	
	var editUserNotificationWin = new Ext.Window({
		layout:'fit',		
		title: 'User',
		width:450,
		height:180,
		closeAction:'hide',
		plain: true,		
		items: editUserNotificationForm,
		buttonAlign: 'center',
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {
					editUserNotificationForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, editUserNotificationResp){	
							//document.getElementById('editUserNotificationRender').style.visibility = 'hidden';
							editUserNotificationWin.hide();
							editUserNotificationForm.getForm().reset();		
							userNotificationStore.load();					
						}	
					});
				}
			},{
				text:'Cancel',
				hidden :modReadOnly,
				handler:function() {
					editUserNotificationForm.getForm().reset();
					editUserNotificationWin.hide();
				}
			}
		]
	});
	
	userNotificationGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		var user_notification_id = gridPanel.getStore().getAt(rowIndex).get('user_notification_id');
		var userid = gridPanel.getStore().getAt(rowIndex).get('userid');
		usernameStore.load({params:{ adminuserid:userid }});
		editUserNotificationWin.show();
		editUserNotificationForm.form.load({
			url:'/admin/user/getusernotificationbyid',
		    method:'GET',
		    params:{ user_notification_id:user_notification_id },
		    waitmsg: 'Loading...',
		    success: function(login, editUserNotificationResp){	
				//document.getElementById('editUserNotificationRender').style.visibility = 'visible';				
			}
		});				
	});
	
	var SendItViaAjax = function (val, url){
		var AjaxLoad = Ext.Ajax.request({
			url: url,
			params: {  datas : val  },
			success: function(){
				//console.log(val);
				userNotificationStore.reload();
			},
		    failure: function(form,action){
		    	userNotificationStore.reload();
				Ext.MessageBox.alert('Error', '');
		   }
		  
		});
	};	
	
});
