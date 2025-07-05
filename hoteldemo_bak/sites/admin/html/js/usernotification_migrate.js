Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	//var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Deleting datas, please wait..."});
	
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
	
	var userNotificationTestStore = new Ext.data.Store({
		url     : '/admin/user/getusernotifications',
		autoLoad: true,
		reader  : new Ext.data.JsonReader( {
			root: "data",					// The property which contains an Array of row objects
			totalProperty: 'total'
		}, UserNotification)
	});
	
	var userNotificationTestCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	var userNotificationTestColModel = new Ext.grid.ColumnModel([
		userNotificationTestCheckbox,
		{id:'userid'			, header:"User ID" 	, width:150 , sortable:true, locked:true, dataIndex:'userid', hidden:true},
		{id:'adminusername'		, header:"Username" 	, width:150 , sortable:true, locked:true, dataIndex:'adminusername'},
		{id:'email'				, header:"Email" 	, width:150 , sortable:true, locked:true, dataIndex:'email'},
		{id:'notification'		, header:"Notification" 	, width:150 , sortable:true, locked:true, dataIndex:'notification'},
		{id:'description'		, header:"Notification Method" 	, width:150 , sortable:true, locked:true, dataIndex:'description'}
	]);	
	
	
	var userNotificationTestGrid = new Ext.grid.GridPanel({ 
		store		: userNotificationTestStore,
		colModel	: userNotificationTestColModel,
		selModel    : userNotificationTestCheckbox,
		height      : 410,
		width		: "100%",
		title       : "Test",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
		stripeRows      : true,
		buttons: [{
        	text   : 'Copy',
        	hidden:modReadOnly,
        	handler:function() {
        		migrateUserNotificationForm.getForm().reset();
        		document.getElementById('userNotificationCopyRender').style.visibility = 'visible';
			}
        },{
        	text   : 'Migrate -->',
        	hidden:modReadOnly,
        	handler:function() {
        		Ext.MessageBox.confirm('Confirm', 'Are you sure you want to migrate these user notification?', function(btn) {
	            	if ( btn == 'yes' ) {
		        		var keyData = [];
		        		
		        		var keyRecords = userNotificationTestGrid.getSelections();
						Ext.each(keyRecords, function(thisRecord) {
							// We set the primary key field equal to whatever it is already set to.  The data store interprets this as a change and sends the data in the POST.
							var oldRecordid = thisRecord.get('user_notification_id');
							thisRecord.set('user_notification_id', '');
							thisRecord.set('user_notification_id', oldRecordid);
							migrateUserNotificationForm.getForm().setValues([{id:'logic', value:'migrate'}]);
							
							keyData.push(thisRecord.getChanges());
						});
						var keyRecords = userNotificationTestGrid.getStore().commitChanges();
		        		
						migrateUserNotificationForm.getForm().submit({
							waitTitle: 'Connecting to the database...',
							waitMsg  : 'Please Wait...',
							params   : {data: Ext.encode(keyData), logic: 'migrate'},
							success: function(login, userNotificationResp){
									Ext.Msg.alert("Information", "User Notification has been successfully migrated.");
									userNotificationLiveStore.loadData(userNotificationResp.result);
								}
						});
	            	}
        		});
			}
        }],
        buttonAlign:'center'
	});
	
	
	/*** USER NOTIFICATION LIVE ***/	
	var userNotificationLiveStore = new Ext.data.Store({
		url     : '/admin/user/getusernotificationlive',
		autoLoad: true,
		reader  : new Ext.data.JsonReader( {
			root: "data"					// The property which contains an Array of row objects
		}, UserNotification)
	});

	var userNotificationLiveColModel = new Ext.grid.ColumnModel([
		{id:'userid'			, header:"User ID" 	, width:150 , sortable:true, locked:true, dataIndex:'userid', hidden:true},
		{id:'adminusername'		, header:"Username" 	, width:150 , sortable:true, locked:true, dataIndex:'adminusername'},
		{id:'email'				, header:"Email" 	, width:150 , sortable:true, locked:true, dataIndex:'email'},
		{id:'notification'		, header:"Notification" 	, width:150 , sortable:true, locked:true, dataIndex:'notification'},
		{id:'description'		, header:"Notification Method" 	, width:150 , sortable:true, locked:true, dataIndex:'description'}
	]);

	var userNotificationLivesGrid = new Ext.grid.GridPanel({ 
		store		: userNotificationLiveStore,
		colModel	: userNotificationLiveColModel,
		height      : 410,
		width		: "100%",
		title       : "Live",
		border      : true,
		stripeRows  : true,
	    loadMask	: true
	});
	
	var migrateUserNotificationForm = new Ext.FormPanel({
		id        : 'migrate-usernotification-panel',
		method    : 'POST',
		url       : '/admin/user/dousernotificationmigrate',
		title	  : 'Migrate User Notification',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'userNotificationMigrateRender',
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [{
			xtype      : 'fieldset',
			width: 485,
			labelWidth : 150,
			defaultType: 'textfield',
			autoHeight : true,
			bodyStyle  : Ext.isIE ? 'padding:0 0 0px 0px;' : 'padding: 5px 1px;',
			border     : false,
			layout	   : 'column',
			style      : {
				"margin-left": "0px", 													// when you add custom margin in IE 6...
				"margin-right": "5px",	// you have to adjust for it somewhere else
				"margin-bottom": "0px",
				"padding": "0px 5px"
			},
			items      : [
					userNotificationTestGrid
				]
			},{
				xtype      : 'fieldset',
				width: 455,
				labelWidth : 150,
				defaultType: 'textfield',
				autoHeight : true,
				bodyStyle  : Ext.isIE ? 'padding:0 0 0px 0px;' : 'padding: 5px 1px;',
				border     : false,
				layout	   : 'column',
				style      : {
					"margin-left": "0px", 													// when you add custom margin in IE 6...
					"margin-right": Ext.isIE6 ? (Ext.isStrict ? "-10px" : "-13px") : "0",	// you have to adjust for it somewhere else
					"margin-bottom": "0px",
					"padding": "0px 5px"
				},
				items      : [
						userNotificationLivesGrid
				]
			}
		]
	});
	
	var copyToSiteStore = new Ext.data.JsonStore(
    {    
	    root: 'data',
	    id:'site_id',// see json output    
	    url	: '/admin/site/getsites',          
        fields:
        [
            {name: 'site_id', type:'int', mapping:'site_id'},
            {name:'name'}
        ]
    });
	
	var copyUserNotificationToSite = new Ext.form.ComboBox({
		allowBlank    : false,
		displayField  : 'name',
		fieldLabel    : 'Copy To Site',
		forceSelection: true,
		hiddenName    : 'copySiteid',
		lazyRender    : true,
		listWidth     : 250,
		name          : 'copySiteid',
		selectOnFocus : true,
		store         : copyToSiteStore,
		triggerAction : 'all',
		typeAhead     : true,
		valueField    : 'site_id',
		width         : 250
	});
	
	
	var userNotificationCopyForm = new Ext.FormPanel({
		id         : 'UserNotificationcopy-form',
		method     : 'POST',
		url        : '/admin/user/dousernotificationmigrate',
		frame      : true,
		labelAlign : 'left',
		renderTo   : 'userNotificationCopyRender',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		items      : [copyUserNotificationToSite],
		buttonAlign: 'center',
		width	   : '400',
		buttons    : [{
        	text    : 'Copy',
        	handler : function() {
        		var keyData = [];
        		
        		var keyRecords = userNotificationTestGrid.getSelections();
				Ext.each(keyRecords, function(thisRecord) {
					// We set the primary key field equal to whatever it is already set to.  The data store interprets this as a change and sends the data in the POST.
					var oldRecordid = thisRecord.get('event_id');
					thisRecord.set('event_id', '');
					thisRecord.set('event_id', oldRecordid);
					//userNotificationCopyForm.getForm().setValues([{id:'copySiteid', value:copyUserNotificationToSite.getValue()}]);
					
					keyData.push(thisRecord.getChanges());
				});
				var keyRecords = userNotificationTestGrid.getStore().commitChanges();
        		
				userNotificationCopyForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg  : 'Please Wait...',
					params   : {data: Ext.encode(keyData), logic: 'copy'},
					success  : function(login, userNotificationResp){
							userNotificationLiveStore.loadData(userNotificationResp.result);
						}
				});
				
				document.getElementById('userNotificationCopyRender').style.visibility = 'hidden';
			}
        },{
			text   : 'Cancel',
			handler: function(){
				document.getElementById('userNotificationCopyRender').style.visibility = 'hidden';
			}
		}]
	});	
	
});
