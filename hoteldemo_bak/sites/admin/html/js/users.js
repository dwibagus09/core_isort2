Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	var curAdminUserId=0;
	
	var SendItViaAjax = function (val, url){
		var AjaxLoad = Ext.Ajax.request({
			url: url,
			params: {  datas : val  },
			success: function(){
				//console.log(val);
			},
		    failure: function(form,action){
				Ext.MessageBox.alert('Error', '');
		   }
		  
		});
	};	
	
	var Users = Ext.data.Record.create([
		{name: 'adminuserid'},
		{name: 'site_id'},
		{name: 'adminusername'},
		{name: 'adminpassword'},
		{name: 'role'}
	]);
	
	// Users :: Data Store
	var usersStore = new Ext.data.Store({
		url     : '/admin/user/getusers',
		autoLoad: true,
		reader  : new Ext.data.JsonReader( {
			root: "data"
		}, Users)
	});
	
	var usersCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	// Users :: Column Model
	var usersColModel = new Ext.grid.ColumnModel([
		usersCheckbox,
		{id:'site_id'    	, header:"Site Id"	 , width:50 , sortable:true, locked:true, dataIndex:'site_id'},
		{id:'adminuserid'   , header:"User Id"	 , width:60 , sortable:true, locked:true, dataIndex:'adminuserid'},
		{id:'adminusername' , header:"User Name" , width:150 , sortable:true, locked:true, dataIndex:'adminusername'},
		{id:'adminpassword' , header:"Password"  , width:150 , sortable:true, locked:true, dataIndex:'adminpassword'}
	]);	
	
	
	var usersGrid = new Ext.grid.GridPanel({ 
		store		: usersStore,
		colModel	: usersColModel,
		selModel    : usersCheckbox,
		title		: 'Users',
		height      : 410,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
	    tbar: [
	 		{
				text   : 'Add New User',
				tooltip: 'Add a new user',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
	            	document.getElementById('editUserRender').style.visibility = 'hidden';
					document.getElementById('addUserRender').style.visibility = 'visible';
				}
			},'-',{
				text   : 'Delete Users',
				tooltip: 'Delete selected users',
				iconCls: 'delete',
				hidden :modReadOnly,
				handler: function() {
	            	document.getElementById('addUserRender').style.visibility = 'hidden';
	            	document.getElementById('editUserRender').style.visibility = 'hidden';
	            	
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete these users?', function(btn) {
	            		if ( btn == 'yes' ) {
	            			var selection = usersGrid.getSelectionModel().getSelections();
							var countRows = usersGrid.getSelectionModel().getCount();	
							var store = usersGrid.getStore();											
							if(countRows > 0){	
								var strings = '';
								for(i = 0 ;i < countRows;i++){										
									strings += selection[i].get('adminuserid').toString()+',';	
									store.remove(selection[i]);																																		
								}															
								strings = strings.substring(0,strings.length-1);
								SendItViaAjax(strings, '/admin/user/deleteusers');									
							}
							usersStore.reload();
	            		}
	            	});
				}
			}
		]
	});
	
	var userModules = Ext.data.Record.create([
		{name: 'admin_user_module_id'},
		{name: 'adminuserid'},
		{name: 'admin_module_id'},
		{name: 'privilege'},
		{name: 'group_name'},
		{name: 'module_name'},
		{name: 'is_view'},
		{name: 'is_readonly'}
	]);
	
	var userModulesStore = new Ext.data.Store({
		url     : '/admin/user/getusermodules',
		autoLoad: false,
		reader  : new Ext.data.JsonReader( {
			root: "rows"					// The property which contains an Array of row objects
		}, userModules)
	});
	
	var userModulesCheck_isView = new Ext.grid.CheckColumn({ header: "View"  , width:40 , dataIndex: 'is_view' });
	var userModulesCheck_isReadOnly = new Ext.grid.CheckColumn({ header: "Read Only?"  , width:65 , dataIndex: 'is_readonly' });

	var userModulesColModel = new Ext.grid.ColumnModel([
		{id:'admin_user_module_id', header:"id"          , width:40 , sortable:true, locked:true , dataIndex:'admin_user_module_id'},
		{id:'group_name'        , header:"Module Group Name" , width:150, sortable:true, dataIndex:'group_name', locked:true},
		{id:'module_name'        , header:"Module Name" , width:150, sortable:true, dataIndex:'module_name', locked:true},
		userModulesCheck_isView,
		userModulesCheck_isReadOnly
	]);
	
	var userModulesSelect = new Ext.grid.RowSelectionModel({
		singleSelect: true
	});

	var userModulesGrid = new Ext.grid.EditorGridPanel({
		store           : userModulesStore,
		colModel        : userModulesColModel,
		selModel        : userModulesSelect,
		plugins			: [userModulesCheck_isView,userModulesCheck_isReadOnly],
		title           : "User Module Privileges",
		height			: 410,
		/*collapsible     : true,
		animCollapse    : false,
		border          : true,*/
		stripeRows      : true,
		clicksToEdit    : 1,
		listeners       : {
			delay : 10,
			render: function(g) {
				userModulesSelect.selectRow(0);
			}
		},
		tbar: [{
			text   : 'Save Changes',
			tooltip: 'Save Changes',
			hidden:modReadOnly,
			iconCls: 'save',
			handler: function() {
				var userModulesRecords = userModulesGrid.getStore().getModifiedRecords();
				var userModulesData = [];
				Ext.each(userModulesRecords, function(thisRecord) {
					var oldRecordid = thisRecord.get('admin_user_module_id');
					thisRecord.set('admin_user_module_id', '');
					thisRecord.set('admin_user_module_id', oldRecordid);
					var adminModuleId = thisRecord.get('admin_module_id');
					thisRecord.set('admin_module_id', '');
					thisRecord.set('admin_module_id', adminModuleId);
					var isview = thisRecord.get('is_view');
					thisRecord.set('is_view', false);
					thisRecord.set('is_view', isview);
					
					var isreadonly = thisRecord.get('is_readonly');
					thisRecord.set('is_readonly', false);
					thisRecord.set('is_readonly', isreadonly);
					
					//thisRecord.set('is_dollar', false);
					
					userModulesData.push(thisRecord.getChanges());
				});
				var userModulesRecords = userModulesGrid.getStore().commitChanges();
				
				usersForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg: 'Please Wait...',
					params: {data: Ext.encode(userModulesData), adminuserid:curAdminUserId },
					success: function(login, userModulesResp){
						userModulesStore.loadData(userModulesResp.result);
					}
				});	
			}
		}]
	});
	
	var usersForm = new Ext.FormPanel({
		id        : 'users-panel',
		method    : 'POST',
		url       : '/admin/user/setusermodules',
		title	  : 'Users',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'usersRender',
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [{
			xtype      : 'fieldset',
			columnWidth: 0.5,
			labelWidth : 150,
			defaultType: 'textfield',
			autoHeight : true,
			bodyStyle  : Ext.isIE ? 'padding:0 0 0px 0px;' : 'padding: 5px 5px;',
			border     : false,
			layout	   : 'column',
			style      : {
				"margin-left": "0px", 													// when you add custom margin in IE 6...
				"margin-right": Ext.isIE6 ? (Ext.isStrict ? "-10px" : "-13px") : "0",	// you have to adjust for it somewhere else
				"margin-bottom": "0px",
				"padding": "0px 5px"
			},
			items      : [
					usersGrid
				]
			},{
				xtype      : 'fieldset',
				columnWidth: 0.5,
				labelWidth : 150,
				defaultType: 'textfield',
				autoHeight : true,
				bodyStyle  : Ext.isIE ? 'padding:0 0 0px 0px;' : 'padding: 5px 5px;',
				border     : false,
				layout	   : 'column',
				style      : {
					"margin-left": "0px", 													// when you add custom margin in IE 6...
					"margin-right": Ext.isIE6 ? (Ext.isStrict ? "-10px" : "-13px") : "0",	// you have to adjust for it somewhere else
					"margin-bottom": "0px",
					"padding": "0px 5px"
				},
				items      : [
						userModulesGrid
				]
			}
		]
	});

	/*** ADD AND EDIT USER ***/
	
	var addUserForm = new Ext.FormPanel({
		id         : 'add-user-form',
		title	   : 'Add User',
		method     : 'POST',
		url        : '/admin/user/adduser',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'addUserRender',
		width	   : 400,
		labelWidth : 130,
		items: [{
			fieldLabel: 'Username',
			name: 'adminusername',
			width: 195
		},{
			fieldLabel: 'Password',
			name: 'adminpassword',
			width: 195
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Add',
				hidden:modReadOnly,
				handler:function() {						
					addUserForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, addUserResp){	
							document.getElementById('addUserRender').style.visibility = 'hidden';		
							usersStore.load();	
							addUserForm.getForm().reset();				
						}	
					})
				}
			},{
				text:'Cancel',
				hidden:modReadOnly,
				handler:function() {
					document.getElementById('addUserRender').style.visibility = 'hidden';
					addUserForm.getForm().reset();
				}
			}
		]
	});
	
	var editUserForm = new Ext.FormPanel({
		id         : 'edit-user-form',
		title	   : 'Edit User',
		method     : 'POST',
		url        : '/admin/user/setuserbyid',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'editUserRender',
		width	   : 400,
		labelWidth : 130,
		items: [{
			xtype: 'hidden',
			id   : 'adminuserid',
			name : 'adminuserid'
		 },{
			fieldLabel: 'Username',
			id: 'adminusername',
			name: 'adminusername',
			width: 195
		},{
			fieldLabel: 'Password',
			id: 'adminpassword',
			name: 'adminpassword',
			width: 195
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden:modReadOnly,
				handler:function() {						
					editUserForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, editUserResp){	
							document.getElementById('editUserRender').style.visibility = 'hidden';		
							usersStore.load();					
						}	
					})
				}
			},{
				text:'Close',
				handler:function() {
					document.getElementById('editUserRender').style.visibility = 'hidden';
				}
			}
		]
	});


	usersGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		var adminuserid = gridPanel.getStore().getAt(rowIndex).get('adminuserid');
		editUserForm.form.load({
			url:'/admin/user/getuserbyid',
		    method:'GET',
		    params:{ adminuserid:adminuserid },
		    waitmsg: 'Loading...',
		    success: function(login, editUserResp){	
            	document.getElementById('addUserRender').style.visibility = 'hidden';
				document.getElementById('editUserRender').style.visibility = 'visible';				
			}
		});				
	});
	
	usersGrid.on('rowclick', function(grd, rowIndex, e) { 
		var adminuserid = grd.getStore().getAt(rowIndex).get('adminuserid');
		curAdminUserId = adminuserid;
		userModulesStore.load({ params:{ adminuserid:adminuserid } });
	});
	
});