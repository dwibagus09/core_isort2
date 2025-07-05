Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	var SendItViaAjax = function (val, url){
		var AjaxLoad = Ext.Ajax.request({
			url: url,
			params: {  datas : val  },
			success: function(){
			},
		    failure: function(form,action){
				Ext.MessageBox.alert('Error', '');
		   }
		  
		});
	};	
	
	var stringerStore = new Ext.data.JsonStore({
   		root:'data',
   		fields:[{name:'stringer_user_id', type: 'int'},'stringer_username','stringer_fullname'],
   		url:'/admin/gallery/getstringers',
   		autoLoad:true
   	});

   	var stringerCombo = new Ext.form.ComboBox({
		allowBlank    : false,
		forceSelection: true,
		lazyRender    : true,
		listClass     : 'x-combo-list-small',
		transform     : 'stringer_user_id',
		triggerAction : 'all',
		editable	  : false,
		typeAhead     : false
	});
	
	var smugmugCategoryCombo = new Ext.form.ComboBox({
		allowBlank    : true,
		forceSelection: true,
		lazyRender    : true,
		hidden		  : ((useSmugMug==1)? false: true),
		fieldLabel	  : 'Smugmug Category',
		listClass     : 'x-combo-list-small',
		transform     : 'smugmug_categoryid',
		triggerAction : 'all',
		editable	  : false,
		typeAhead     : false
	});
	
	var Assignments = Ext.data.Record.create([
		{name: 'stringer_gallery_id'},
		{name: 'site_id'},
		{name: 'content_gallery_id'},
		{name: 'content_gallery_type_id'},
		{name: 'content_gallery_type'},
		{name: 'content_gallery'},
		{name: 'smugmug_id'},
		{name: 'smugmug_key'},
		{name: 'smugmug_categoryid'},
		{name: 'show_gallery_in_multimedia'},
		{name: 'users'},
		{name: 'sites'},
		{name: 'keywords'},
		{name: 'for_sale'}
	]);
	
	// Assignments :: Data Store
	var assignmentsStore = new Ext.data.Store({
		url     : '/admin/gallery/getassignments',
		autoLoad: false,
		reader  : new Ext.data.JsonReader( {
			root: "data",
			totalProperty: 'total'
		}, Assignments)
	});
	
	var assignmentsCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	// Assignments :: Column Model
	var assignmentsColModel = new Ext.grid.ColumnModel([
		{id:'stringer_gallery_id' , header:"Assignment ID"	, width:100 , sortable:true, locked:true, dataIndex:'stringer_gallery_id'},
		{id:'content_gallery_type'  , header:"Gallery Type" , width:100 , sortable:true, locked:true, dataIndex:'content_gallery_type'},
		{id:'smugmug_categoryid'  , header:"Smugmug Category" , width:100, hidden:((useSmugMug==1)? false: true), sortable:true, locked:true, dataIndex:'smugmug_categoryid',
			renderer:function(val) {
			    var rec = smugmugCategoryOptionsStore.getById(val);
				return rec ? rec.get('category_name'):'';
			}
		},
		{id:'content_gallery'  		, header:"Gallery Title" , width:250, sortable:true, locked:true, dataIndex:'content_gallery'},
		{id:'show_gallery_in_multimedia', header:"Show in Multimedia"   , width:100 , sortable:true, locked:true, dataIndex:'show_gallery_in_multimedia', renderer:function(val) { if(val==1) return 'Yes'; else return 'No';}},
		{id:'for_sale1', header:"For Sale?"   , width:80 , sortable:true, locked:true, dataIndex:'for_sale', renderer:function(val) { if(val==1) return 'Yes'; else return 'No';}},
		{id:'users'  		, header:"Users/Stringers" , width:250, sortable:true, locked:true, dataIndex:'users'}
	]);	
	
	
	var assignmentsGrid = new Ext.grid.GridPanel({ 
		store		: assignmentsStore,
		colModel	: assignmentsColModel,
		selModel    : assignmentsCheckbox,
		height      : 410,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
	    tbar: [
	 		{
				text   : 'Add New Assignment',
				tooltip: 'Add a new assignment/user',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
					//document.getElementById('addAssignmentRender').style.visibility = 'visible';
					addAssignmentWin.show();
					addAssignmentForm.getForm().reset();
					Ext.getCmp('for_sale').setValue(true);
					Ext.getCmp('userassignment_grid').store.removeAll();
					Ext.getCmp('sites1').clearValue();
            		Ext.getCmp('sites1').setValue(defSiteId);
				}
			},'-',{
				text   : 'Delete Assignments',
				tooltip: 'Delete selected assignments',
				iconCls: 'delete',
				hidden :modReadOnly,
				handler: function() {
	            	//document.getElementById('addAssignmentRender').style.visibility = 'hidden';
	            	
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete these assignments?', function(btn) {
	            		if ( btn == 'yes' ) {
	            			var selection = assignmentsGrid.getSelectionModel().getSelections();
							var countRows = assignmentsGrid.getSelectionModel().getCount();	
							var store = assignmentsGrid.getStore();											
							if(countRows > 0){	
								var strings = '';
								for(i = 0 ;i < countRows;i++){										
									strings += selection[i].get('stringer_gallery_id').toString()+',';	
									store.remove(selection[i]);																																		
								}															
								strings = strings.substring(0,strings.length-1);
								SendItViaAjax(strings, '/admin/gallery/deleteassignments');									
							}
							assignmentsStore.reload();
	            		}
	            	});
				}
			},'-',
			'Keyword: ',
		    {
				xtype:'textfield',
				id: 'query',
				width:300,
				listeners:{
					render: function(f){
						this.el.on('keyup', function (f, e){
					    	var query = Ext.getCmp('query').getValue();
			            	assignmentsStore.load({ params:{ start:0,limit:14, query:query } });
						}, this, {buffer: 500});
					}
				}
			}
		],
		bbar		: new Ext.PagingToolbar({
	        pageSize: 14,
	        displayInfo: true,
	        displayMsg: 'Total Records {0} - {1} of {2}',
	        store: assignmentsStore,
	       	emptyMsg: "No assignment to display"        
	    })
	});
	
	
	var assignmentsForm = new Ext.FormPanel({
		id        : 'assignments-panel',
		method    : 'POST',
		url       : '/admin/gallery/setassignment',
		title	  : 'Assignments',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'assignmentsRender',
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [
			assignmentsGrid
		]
	});

	/*** ADD AND EDIT ASSIGNMENT ***/
	var galleryTypeStore = new Ext.data.JsonStore(
    {    
	    root: 'data',
	    id:'content_gallery_type_id',// see json output    
	    url	: '/admin/content/getcontentgallerytype',          
	    autoLoad: true,
        fields:
        [
            {name: 'content_gallery_type_id', type:'int', mapping:'content_gallery_type_id'},
            {name:'content_gallery_type'}
        ]
    }); 
    
	var addGalleryTypeCombo = new Ext.form.ComboBox(
    {   
    	xtype : 'combo',
        store: galleryTypeStore,   
        fieldLabel: 'Gallery Type',   
        displayField:'content_gallery_type',  
        valueField: 'content_gallery_type_id',          
        hiddenName: 'content_gallery_type_id',  
        name: 'content_gallery_type_id',  
        allowBlank: false,     
        editable: false,  
        triggerAction: 'all',   
        valueNotFoundText:'Please Select',  
        emptyText:'Please Select',
        forceSelection: true,
        selectOnFocus:true,
        listWidth: 177,
        width: 177
    });
    
    var StringerAssignment = Ext.data.Record.create([
		{name:'stringer_assignment_id', type:'int'}, 
		{name:'stringer_gallery_id', type:'int'}, 
		{name:'stringer_user_id', type:'int'}
  	]);
    
    var tbadduser = new Ext.Toolbar.Button ({
        text:'Add User/Stringer',
        tooltip:'Add user/stringers',
        iconCls:'add',
        handler: function(){
            var sa = new StringerAssignment({
            	stringer_assignment_id: 0,
				stringer_gallery_id: Ext.getCmp('stringer_gallery_id').getValue(),
                stringer_user_id: 0
            });
            var grid = Ext.getCmp('userassignment_grid');
            grid.stopEditing();
            grid.store.insert(0, sa);
            grid.startEditing(0, 1);
        }
    });
    
    var tbremoveuser = new Ext.Toolbar.Button ({
        text:'Remove User/Stringer',
        tooltip: 'Remove User/Stringers',
        iconCls:'delete',
        handler: function(){
        	Ext.Msg.confirm('Confirmation', 'Are you sure to delete selected item(s)?', function(btn, text){
			    if (btn == 'yes'){
			    	var grid = Ext.getCmp('userassignment_grid');
			    	var selection = grid.getSelectionModel().getSelections();
			    	var assignmentId = selection[0].get("stringer_assignment_id");
			    	Ext.Ajax.request({
	  					url: '/admin/gallery/deleteuserassignment',
						params: {stringer_assignment_id:assignmentId },
						success: function(){
							grid.getStore().remove(selection[0]);
						},
	   					failure: function(form,action){
						}
	  					
					});
			    }
	        });
        }
    });
	var sm = new Ext.grid.CheckboxSelectionModel({singleSelect :true});
	
	var msSites = new Ext.ux.Andrie.Select({
		allowBlank:true,
		fieldLabel:'Sites',
		multiSelect:true,
		store: sitesStore,
		name:'sites',
		id:'sites1',
		valueField:'site_id',
		displayField:'name',
		triggerAction:'all',
		dataIndex:'sites',
		width:200,
		listWidth:200,
		mode:'local'
	});
    
	var addAssignmentForm = new Ext.FormPanel({
		id         : 'add-assignment-form',
		method     : 'POST',
		url        : '/admin/gallery/saveassignment',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		//renderTo   : 'addAssignmentRender',
		//width	   : 400,
		labelWidth : 130,
		items: [
		{
			xtype:'hidden',
			id: 'stringer_gallery_id',
			name: 'stringer_gallery_id'
		},
		{
			xtype:'hidden',
			id: 'content_gallery_id',
			name: 'content_gallery_id'
		},
		{
			xtype:'hidden',
			id: 'smugmug_id',
			name: 'smugmug_id'
		},
		{
			xtype:'hidden',
			name:'ausers_list',
			id: 'ausers_list'
		},
			addGalleryTypeCombo,
			smugmugCategoryCombo,
			{
				fieldLabel: 'Title',
				name: 'content_gallery',
				width: 200
			},{
				xtype: 'checkbox',
				fieldLabel: 'Show Gallery In Multimedia',
				name: 'show_gallery_in_multimedia'
			},{
				xtype: 'checkbox',
				fieldLabel: 'For Sale?',
				name: 'for_sale',
				id:'for_sale'
			}, {
				xtype:'textarea',
				fieldLabel: 'Keywords',
				width: 200,
				name: 'keywords'
			},
			msSites,
			{
			    xtype: 'editorgrid',
			    id:'userassignment_grid',
			    ds: new Ext.data.JsonStore({
					url:'/admin/gallery/getusersinassignment',
					id: 'dsstringer_gallery_id',
					totalProperty: 'total',
					root: 'data',
					fields: [
						{name:'stringer_gallery_id'}, 
						{name:'stringer_user_id'}, 
						{name:'stringer_username'}, 
						{name:'stringer_fullname'}
					]
				}),
				tbar:new Ext.Toolbar([
					tbadduser,'-',tbremoveuser
				]),
	            cm: new Ext.grid.ColumnModel([
	            	sm,
		            {
		            	id:'vote_id',
		            	sortable: true,
		            	dataIndex:'vote_id',
		            	hidden:true,
		            	header:'Vote ID'
		            },
		            {
		            	id:'stringer_user_id',
		            	sortable: true,
		            	dataIndex:'stringer_user_id',
		            	width:250,
		            	editor:stringerCombo,/*new Ext.form.ComboBox({
		            		typeAhead: false,
		            		forceSelection: true,
		            		lazyRender    : true,
			               	triggerAction: 'all',
			               	editable	  : false,
			               	displayField:'stringer_fullname',
			               	tpl:'<tpl for="."><div class="x-combo-list-item">{stringer_username} - {stringer_fullname}</div></tpl>',
			               	listClass: 'x-combo-list-small',
			               	store:stringerStore,
			               	listeners:{
								// sets raw value to concatenated last and first names
								select:function(combo, record, index) {
									this.setRawValue(record.get('stringer_fullname'));
									this.setValue(record.get('stringer_user_id'));
									var selection = Ext.getCmp('userassignment_grid').getSelectionModel().getSelections();
									selection = selection[0];
									selection.set('stringer_user_id', record.get('stringer_user_id'));
									selection.set('stringer_fullname', record.get('stringer_fullname'));
									Ext.getCmp('userassignment_grid').getStore().commitChanges();
									var recorddata = Ext.getCmp('userassignment_grid').getStore().commitChanges();
								},
								blur:function() {
									var val = this.getRawValue();
									this.setRawValue.defer(1, this, [val]);
								},
								render:function() {
									//this.validate();
								},
								keypress:{
									buffer:100, fn:function() {
										if(!this.getRawValue()) {
											this.doQuery('', true);
										}
									}
								}
				            }
			            }),*/
			            renderer:function(val) {
			            	var rec = stringerOptionsStore.getById(val);
							return rec ? rec.get('stringer_username') + ' - '+ rec.get('stringer_fullname') : '';
			            },
		            	header:'User/Stringers'
		            }
				]),
	            sm: sm,
	            height: 200,
	            border:false,
	            frame:true,
	            clicksToEdit:1
			}
		]
	});
	
	var addAssignmentWin = new Ext.Window({
		layout:'fit',		
		title: 'Add Assignment',
		width:400,
		height:550,
		closeAction:'hide',
		plain: true,		
		items: addAssignmentForm,	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {
					var store = Ext.getCmp('userassignment_grid').getStore();
					records = store.data.items;
					data = [];
					Ext.each(records, function(thisRecord) {
						data.push(thisRecord.data);
					});
					Ext.getCmp('ausers_list').setValue(Ext.encode(data));
					addAssignmentForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, addAssignmentResp){	
							//document.getElementById('addAssignmentRender').style.visibility = 'hidden';		
							addAssignmentWin.hide();
							assignmentsStore.load();	
							addAssignmentForm.getForm().reset();
						},
						failure: function(login, addAssignmentResp){
							var resp = addAssignmentResp.response.responseText;
							var obj = Ext.util.JSON.decode(resp);
							Ext.Msg.show({
							   title:'Error',
							   msg: obj.msg,
							   buttons: Ext.Msg.OK,
							   icon: Ext.MessageBox.ERROR
							});
						}
					})
				}
			},{
				text:'Cancel',
				hidden :modReadOnly,
				handler:function() {
					//document.getElementById('addAssignmentRender').style.visibility = 'hidden';
					addAssignmentWin.hide();
					addAssignmentForm.getForm().reset();
				}
			}
		]
	});
	
	assignmentsGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		var assignment_id = gridPanel.getStore().getAt(rowIndex).get('stringer_gallery_id');
		Ext.getCmp('userassignment_grid').store.removeAll();
		addAssignmentWin.show();
		addAssignmentForm.form.load({
			url:'/admin/gallery/getassignmentbyid',
		    method:'GET',
		    params:{ stringer_gallery_id:assignment_id },
		    waitmsg: 'Loading...',
		    success: function(login, editAssignmentResp){
            	//document.getElementById('addAssignmentRender').style.visibility = 'visible';
            	Ext.getCmp('userassignment_grid').store.baseParams.stringer_gallery_id=assignment_id; 
				Ext.getCmp('userassignment_grid').store.reload();
				Ext.getCmp('sites1').clearValue();
				Ext.getCmp('sites1').setValue(editAssignmentResp.result.data.sites);
			}
		});				
	});

	
	assignmentsStore.load();
	
});