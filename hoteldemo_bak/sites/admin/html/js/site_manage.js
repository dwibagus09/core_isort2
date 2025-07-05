Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Deleting sites, please wait..."});
	
	var SendItViaAjax = function (val, url){
		var AjaxLoad = Ext.Ajax.request({
			url: url,
			params: {  datas : val  },
			success: function(){
				loadMask.hide();
				siteStore.reload();
				siteForm.getForm().reset();
			},
		    failure: function(form,action){
		    	loadMask.hide();
				Ext.MessageBox.alert('Error', '');
		   }
		  
		});
	};	

	var Sites = Ext.data.Record.create([
		{name: 'site_id'},
		{name: 'name'},
		{name: 'email'},
		{name: 'newspaper_name'}
	]);
	
	var connObjSites = new Ext.data.Connection({
	    timeout : 120000,
	    url : '/admin/site/getsites',
	    method : 'GET'
	});
	
	// Sites :: Data Store
	var siteStore = new Ext.data.Store({
		url     : '/admin/site/getsites',
		autoLoad: true,
		proxy : new Ext.data.HttpProxy(connObjSites),
		reader  : new Ext.data.JsonReader( {
			root: "data",					// The property which contains an Array of row objects
			totalProperty: 'total'
		}, Sites)
	});
	
	var siteCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	// Sites :: Column Model
	var siteColModel = new Ext.grid.ColumnModel([
	 	siteCheckbox,
		{id:'site_id'     		, header:"Site Id"		, width:50 , sortable:true, locked:true, dataIndex:'site_id'},
		{id:'name'     			, header:"Name"   , width:100 , sortable:true, locked:true, dataIndex:'name'},
		{id:'email' 			, header:"Email"    , width:250 , sortable:true, locked:true, dataIndex:'email'},
		{id:'newspaper_name'    , header:"Newspaper Name"      , width:250 , sortable:true, locked:true, dataIndex:'newspaper_name'}
	]);	

	
	var siteGrid = new Ext.grid.GridPanel({ 
		store		: siteStore,
		colModel	: siteColModel,
		selModel    : siteCheckbox,
		height      : 413,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true
	});
	
	var siteForm = new Ext.FormPanel({
		id        : 'site-panel',
		method    : 'POST',
		url       : '/admin/site/setsite',
		title	  : 'Sites',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'siteRender',
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [
			siteGrid
		],
		tbar: [{
				text   : 'Add New Site',
				tooltip: 'Add a new site',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
	            	/*document.getElementById('editSiteRender').style.visibility = 'hidden';
					document.getElementById('addSiteRender').style.visibility = 'visible';*/
					editSiteWin.show();
				}
			},'-',{
				text   : 'Delete Site',
				tooltip: 'Delete selected site',
				iconCls: 'delete',
				hidden :modReadOnly,
				handler: function() {
	            	/*document.getElementById('addSiteRender').style.visibility = 'hidden';
	            	document.getElementById('editSiteRender').style.visibility = 'hidden';*/
					
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete these site?', function(btn) {
	            		if ( btn == 'yes' ) {
	            			loadMask.show();
	            			
	            			var selection = siteGrid.getSelectionModel().getSelections();
							var countRows = siteGrid.getSelectionModel().getCount();	
							var store = siteGrid.getStore();											
							if(countRows > 0){	
								var strings = '';
								for(i = 0 ;i < countRows;i++){										
									strings += selection[i].get('site_id').toString()+',';	
									store.remove(selection[i]);																																		
								}															
								strings = strings.substring(0,strings.length-1);
								SendItViaAjax(strings, '/admin/site/deletesites');									
							}
							
	            		}
	            	});
				}
			}
		]
	});
	
	/*** ADD SITE ***/
	
	/*
	var addSiteForm = new Ext.FormPanel({
		id         : 'add-site-form',
		title	   : 'Add Site',
		method     : 'POST',
		url        : '/admin/site/addsite',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'addSiteRender',
		width	   : 350,
		labelWidth : 120,
		items: [{
			fieldLabel: 'Site Name',
			name: 'name',
			width: 200
		},{
			fieldLabel: 'Email',
			name: 'email',
			width: 200
		},{
			fieldLabel: 'Newspaper Name',
			name: 'newspaper_name',
			width: 200
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {						
					addSiteForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, addSiteResp){	
							document.getElementById('addSiteRender').style.visibility = 'hidden';		
							siteStore.load({ params:{ start:0,limit:16 } });					
						}	
					})
				}
			},{
				text:'Close',
				handler:function() {
					document.getElementById('addSiteRender').style.visibility = 'hidden';
				}
			}
		]
	});*/
	
	
	/*** EDIT SITE ***/
	  
	
	var editSiteForm = new Ext.FormPanel({
		id         : 'edit-site-form',
		//title	   : 'Edit Site',
		method     : 'POST',
		//url        : '/admin/site/setsitebyid',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		//renderTo   : 'editSiteRender',
		//width	   : 350,
		labelWidth : 120,
		items: [{
			xtype: 'hidden',
			id   : 'site_id',
			name : 'site_id'
		 },{
			fieldLabel: 'Site Name',
			id: 'name',
			name: 'name',
			width: 200
		},{
			fieldLabel: 'Email',
			name: 'id',
			name: 'email',
			width: 200
		},{
			fieldLabel: 'Newspaper Name',
			id: 'newspaper_name',
			name: 'newspaper_name',
			width: 200
		}]/*,	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {						
					editSiteForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, editSiteResp){	
							document.getElementById('editSiteRender').style.visibility = 'hidden';		
							siteStore.load({ params:{ start:0,limit:16 } });					
						}	
					})
				}
			},{
				text:'Close',
				handler:function() {
					document.getElementById('editSiteRender').style.visibility = 'hidden';
				}
			}
		]*/
	});

	var editSiteWin = new Ext.Window({
		layout:'fit',		
		title: 'Site',
		width:400,
		height:180,
		closeAction:'hide',
		plain: true,		
		items: editSiteForm,
		buttonAlign: 'center', 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {
					if(Ext.getCmp('site_id').value > 0) {
						editSiteForm.getForm().submit({
							url:'/admin/site/setsitebyid',
							waitTitle: 'Connecting to the database...',
							waitMsg: 'Please Wait...',
							success: function(login, editSiteResp){	
								//document.getElementById('editSectionRender').style.visibility = 'hidden';		
								editSiteForm.getForm().reset();	
								editSiteWin.hide();
								siteStore.load();					
							}	
						});
					}
					else {
						editSiteForm.getForm().submit({
							url:'/admin/site/addsite',
							waitTitle: 'Connecting to the database...',
							waitMsg: 'Please Wait...',
							success: function(login, editSiteResp){	
								//document.getElementById('addSectionRender').style.visibility = 'hidden';		
								editSiteWin.hide();
								siteStore.load();	
								editSiteForm.getForm().reset();				
							},
							failure: function(form, editSiteResp){
								if(editSiteResp.result.msg)
									Ext.Msg.alert('Error', editSiteResp.result.msg);
							}
						});
					}
				}
			},{
				text:'Cancel',
				hidden :modReadOnly,
				handler:function() {
					editSiteForm.getForm().reset();
					editSiteWin.hide();
				}
			}
		]
	});

	siteGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		var site_id = gridPanel.getStore().getAt(rowIndex).get('site_id');
		editSiteWin.show();
		editSiteForm.form.load({
			url:'/admin/site/getsitebyid',
		    method:'GET',
		    params:{ site_id:site_id },
		    waitmsg: 'Loading...',
		    success: function(login, editSiteResp){	
				//document.getElementById('editSiteRender').style.visibility = 'visible';			
			}
		});
		
	});
	
}); 
