Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	//var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Deleting datas, please wait..."});
	
	var AdminUsers = Ext.data.Record.create([
		{name: 'stringer_user_id'},
		{name: 'site_id'},
		{name: 'stringer_username'},
		{name: 'stringer_password'},
		{name: 'stringer_fullname'},
		{name: 'staff_code'}
	]);
	
	var adminUsersTestStore = new Ext.data.Store({
		url     : '/admin/gallery/getstringers',
		autoLoad: true,
		reader  : new Ext.data.JsonReader( {
			root: "data"
		}, AdminUsers)
	});
	
	var adminUsersTestCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	var adminUsersTestColModel = new Ext.grid.ColumnModel([
		adminUsersTestCheckbox,
		{id:'site_id'    	, header:"Site Id"	 , width:40 , sortable:true, locked:true, dataIndex:'site_id'},
		{id:'stringer_user_id'   , header:"User Id"	 , width:45 , sortable:true, locked:true, dataIndex:'stringer_user_id'},
		{id:'stringer_username' , header:"User Name" , width:100 , sortable:true, locked:true, dataIndex:'stringer_username'},
		{id:'stringer_password' , header:"Password"  , width:100 , sortable:true, locked:true, dataIndex:'stringer_password'},
		{id:'stringer_fullname' 		, header:"Full Name" 	 , width:125 , sortable:true, locked:true, dataIndex:'stringer_fullname'},
		{id:'staff_code' 		, header:"Staff Code" 	 , width:80 , sortable:true, locked:true, dataIndex:'staff_code'}
	]);	
	
	
	var adminUsersTestGrid = new Ext.grid.GridPanel({ 
		store		: adminUsersTestStore,
		colModel	: adminUsersTestColModel,
		selModel    : adminUsersTestCheckbox,
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
        		migrateAdminUsersForm.getForm().reset();
        		document.getElementById('stringersCopyRender').style.visibility = 'visible';
			}
        },{
        	text   : 'Migrate -->',
        	hidden:modReadOnly,
			menu:[{
	        		text:'Migrate and Remove All Existing Stringers',
					handler:function() {	
						Ext.MessageBox.confirm('Confirm', 'Are you sure you want to migrate these stringers?', function(btn) {
							if ( btn == 'yes' ) {
								var keyData = [];
								
								var keyRecords = adminUsersTestGrid.getSelections();
								Ext.each(keyRecords, function(thisRecord) {
									// We set the primary key field equal to whatever it is already set to.  The data store interprets this as a change and sends the data in the POST.
									var oldRecordid = thisRecord.get('stringer_user_id');
									thisRecord.set('stringer_user_id', '');
									thisRecord.set('stringer_user_id', oldRecordid);
									migrateAdminUsersForm.getForm().setValues([{id:'logic', value:'migrate'}]);
									
									keyData.push(thisRecord.getChanges());
								});
								var keyRecords = adminUsersTestGrid.getStore().commitChanges();
								
								migrateAdminUsersForm.getForm().submit({
									waitTitle: 'Connecting to the database...',
									waitMsg  : 'Please Wait...',
									params   : {data: Ext.encode(keyData), logic: 'migrate'},
									success: function(login, adminUsersResp){
										Ext.Msg.alert("Information", "Stringers has been successfully migrated.");
										adminUsersLiveStore.loadData(adminUsersResp.result);
									}
								});
							}
						});
					}
	        	},
	        	{
	        		text:'Migrate and Remove only Selected Stringers',
	        		handler: function() {
						Ext.MessageBox.confirm('Confirm', 'Are you sure you want to migrate these stringers?', function(btn) {
							if ( btn == 'yes' ) {
								var keyData = [];
								
								var keyRecords = adminUsersTestGrid.getSelections();
								Ext.each(keyRecords, function(thisRecord) {
									// We set the primary key field equal to whatever it is already set to.  The data store interprets this as a change and sends the data in the POST.
									var oldRecordid = thisRecord.get('stringer_user_id');
									thisRecord.set('stringer_user_id', '');
									thisRecord.set('stringer_user_id', oldRecordid);
									migrateAdminUsersForm.getForm().setValues([{id:'logic', value:'migrate'}]);
									
									keyData.push(thisRecord.getChanges());
								});
								var keyRecords = adminUsersTestGrid.getStore().commitChanges();
								
								migrateAdminUsersForm.getForm().submit({
									waitTitle: 'Connecting to the database...',
									waitMsg  : 'Please Wait...',
									url      : '/admin/gallery/dostringersmigrate/donotremoveexisting/1',
									params   : {data: Ext.encode(keyData), logic: 'migrate'},
									success: function(login, adminUsersResp){
										Ext.Msg.alert("Information", "Stringers has been successfully migrated.");
										adminUsersLiveStore.loadData(adminUsersResp.result);
									}
								});
							}
						});
	        		}
	        	}
        	]
        }],
        buttonAlign:'center'
	});
	
	
	/*** ADMIN USERS LIVE ***/	
	var adminUsersLiveStore = new Ext.data.Store({
		url     : '/admin/gallery/getstringerslive',
		autoLoad: true,
		reader  : new Ext.data.JsonReader( {
			root: "data"					// The property which contains an Array of row objects
		}, AdminUsers)
	});

	var adminUsersLiveColModel = new Ext.grid.ColumnModel([
		{id:'site_id'    	, header:"Site Id"	 , width:40 , sortable:true, locked:true, dataIndex:'site_id'},
		{id:'stringer_user_id'   , header:"User Id"	 , width:45 , sortable:true, locked:true, dataIndex:'stringer_user_id'},
		{id:'stringer_username' , header:"User Name" , width:100 , sortable:true, locked:true, dataIndex:'stringer_username'},
		{id:'stringer_password' , header:"Password"  , width:100 , sortable:true, locked:true, dataIndex:'stringer_password'},
		{id:'stringer_fullname' 		, header:"Full Name" 	 , width:125 , sortable:true, locked:true, dataIndex:'stringer_fullname'},
		{id:'staff_code' 		, header:"Staff Code" 	 , width:80 , sortable:true, locked:true, dataIndex:'staff_code'}
	]);

	var adminUsersLivesGrid = new Ext.grid.GridPanel({ 
		store		: adminUsersLiveStore,
		colModel	: adminUsersLiveColModel,
		height      : 410,
		width		: "100%",
		title       : "Live",
		border      : true,
		stripeRows  : true,
	    loadMask	: true
	});
	
	var migrateAdminUsersForm = new Ext.FormPanel({
		id        : 'migrate-adminUsers-panel',
		method    : 'POST',
		url       : '/admin/gallery/dostringersmigrate',
		title	  : 'Migrate Stringers',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'stringersMigrateRender',
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
					adminUsersTestGrid
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
						adminUsersLivesGrid
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
	
	var copyAdminUsersToSite = new Ext.form.ComboBox({
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
	
	
	var adminUsersCopyForm = new Ext.FormPanel({
		id         : 'AdminUserscopy-form',
		method     : 'POST',
		url        : '/admin/gallery/dostringersmigrate',
		frame      : true,
		labelAlign : 'left',
		renderTo   : 'stringersCopyRender',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		items      : [copyAdminUsersToSite],
		buttonAlign: 'center',
		width	   : '400',
		buttons    : [{
        	text    : 'Copy',
        	handler : function() {
        		var keyData = [];
        		
        		var keyRecords = adminUsersTestGrid.getSelections();
				Ext.each(keyRecords, function(thisRecord) {
					// We set the primary key field equal to whatever it is already set to.  The data store interprets this as a change and sends the data in the POST.
					var oldRecordid = thisRecord.get('stringer_user_id');
					thisRecord.set('stringer_user_id', '');
					thisRecord.set('stringer_user_id', oldRecordid);
					//adminUsersCopyForm.getForm().setValues([{id:'copySiteid', value:copyAdminUsersToSite.getValue()}]);
					
					keyData.push(thisRecord.getChanges());
				});
				var keyRecords = adminUsersTestGrid.getStore().commitChanges();
        		
				adminUsersCopyForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg  : 'Please Wait...',
					params   : {data: Ext.encode(keyData), logic: 'copy'},
					success  : function(login, adminUsersResp){
							adminUsersLiveStore.loadData(adminUsersResp.result);
						}
				});
				
				document.getElementById('stringersCopyRender').style.visibility = 'hidden';
			}
        },{
			text   : 'Cancel',
			handler: function(){
				document.getElementById('stringersCopyRender').style.visibility = 'hidden';
			}
		}]
	});	
	
});
