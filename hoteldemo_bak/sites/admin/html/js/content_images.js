Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	var ContentImages = Ext.data.Record.create([
		{name: 'site_id'},
		{name: 'content_images_id'},
		{name: 'source_system_id'},
		{name: 'title'},
		{name: 'caption'},
		{name: 'keywords'},
		{name: 'content_gallery_type_id'},
		{name: 'show_gallery_in_multimedia'},
		{name: 'views'}
	]);
	
	var connObjContentImages = new Ext.data.Connection({
	    timeout : 120000,
	    url : '/admin/content/getcontentimages',
	    method : 'GET'
	});
	
	// ContentImages :: Data Store
	var contentImagesStore = new Ext.data.Store({
		autoLoad: false,
		proxy : new Ext.data.HttpProxy(connObjContentImages),
		reader  : new Ext.data.JsonReader( {
			root: "data",					// The property which contains an Array of row objects
			totalProperty: 'total'
		}, ContentImages)
	});
	
	var show_gallery_in_multimedia = new Ext.grid.CheckColumn({ header: "Show Gallery in Multimedia"   , width:140  ,  dataIndex: 'show_gallery_in_multimedia' , readonly: true });
	
	// ContentImages :: Column Model
	var contentImagesColModel = new Ext.grid.ColumnModel([
		{id:'content_images_id' , header:"Id"   , width:75 , sortable:true, locked:true, dataIndex:'content_images_id'},
		{id:'source_system_id' 	, header:"Source id"    , width:200 , sortable:true, locked:true, dataIndex:'source_system_id'},
		{id:'caption'     		, header:"Caption"      , width:480 , sortable:true, locked:true, dataIndex:'caption'},
		show_gallery_in_multimedia,
		{id:'views'     		, header:"Views"      , width:50 , sortable:true, locked:true, dataIndex:'views'}
	]);	
	
	var arrContentImagesFields =  [
		["Any field","Any field"],
	    ["content_images_id","content_images_id"],
	    ["source_system_id","source_system_id"],
	    ["title","title"],
	    ["caption","caption"],
	    ["keywords","keywords"],
	    ["credits","credits"],
	    ["views","views"],
	    ["modify_date_time","modify_date_time"],
	    ["create_date_time","create_date_time"]
	];
	
	var contentImagesFieldStore = new Ext.data.SimpleStore({
		id    : 0,
	    fields: ['fieldid', 'desc'],
	    data  : arrContentImagesFields
	});
	
	var contentimagesfieldsearch = new Ext.form.ComboBox ({
		id			  : 'contentImagesFieldSearch',
		name          : 'contentImagesFieldSearch',
		hiddenName    : 'fieldid',
		store         : contentImagesFieldStore,
		allowBlank    : false,
		displayField  : 'desc',
		valueField    : 'fieldid',
		emptyText     : "Select field..",
		forceSelection: true,
		mode          : 'local',
		selectOnFocus : true,
		triggerAction : 'all',
		typeAhead     : false,
		width		  : 140,
		value		  : contentImagesFieldStore.getAt(0).get('desc')
	});
	
	var arrContentImagesOptions =  [
		["Contains","Contains"],
	    ["Equals","Equals"],
	    ["Starts with ...","Starts with ..."],
	    ["More than ...","More than ..."],
	    ["Less than ...","Less than ..."],
	    ["Equal or more than ...","Equal or more than ..."],
	    ["Equal or less than ...","Equal or less than ..."],
	    ["Empty","Empty"]
	];
	
	var contentImagesOptionStore = new Ext.data.SimpleStore({
		id    : 0,
	    fields: ['optionid', 'desc'],
	    data  : arrContentImagesOptions
	});
	
	var contentimagesoptionsearch = new Ext.form.ComboBox ({
		id			  : 'contentImagesOptionSearch',
		name          : 'contentImagesOptionSearch',
		hiddenName    : 'optionid',
		store         : contentImagesOptionStore,
		allowBlank    : false,
		displayField  : 'desc',
		valueField    : 'optionid',
		emptyText     : "Select option..",
		forceSelection: true,
		mode          : 'local',
		selectOnFocus : true,
		triggerAction : 'all',
		typeAhead     : false,
		width		  : 140,
		value		  : contentImagesOptionStore.getAt(0).get('desc')
	});
	
	var contentimagestextsearch = new Ext.form.TextField ({
		name: 'scontentimages',
		id: 'scontentimages',
		emptyText : 'Search content images...',
		width:150,
		listeners:{
			render: function(f){
				this.el.on('keyup', ContentImagesSearchItem, this, {buffer: 350});
			}
		}
	});
	
	var ContentImagesSearchItem = function (f, e){
		var filter = f.target.value;
	 	contentImagesStore.load({ params:{ start:0,limit:16,keyword:filter } });
	};
	
	contentImagesStore.on ('beforeload', function (ds, e){
		ds.baseParams.field = contentimagesfieldsearch.getValue();
		ds.baseParams.option = contentimagesoptionsearch.getValue();
		ds.baseParams.keyword = contentimagestextsearch.getValue();
	});
	
	
	var contentImagesGrid = new Ext.grid.GridPanel({ 
		store		: contentImagesStore,
		colModel	: contentImagesColModel,
		//selModel    : contentImagesCheckbox,
		height      : 413,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
	 	tbar: [
	 		new Ext.form.Label({
		        html: 'Search for :'       
		    }),' ',
		    contentimagesfieldsearch,' ',
			contentimagesoptionsearch,' ',
		    contentimagestextsearch],
		bbar		: new Ext.PagingToolbar({
		        pageSize: 16,
		        displayInfo: true,
		        displayMsg: 'Total Records {0} - {1} of {2}',
		        store: contentImagesStore,
		       	emptyMsg: "No topics to display"        
		    })
	});
	
	var contentImagesForm = new Ext.FormPanel({
		id        : 'content-images-panel',
		method    : 'POST',
		url       : '/admin/comments/setcomment',
		title	  : 'Gallery & Images',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'contentImagesRender',
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [
			contentImagesGrid
		]
	});
	
	/*** EDIT CONTENT IMAGES ***/
	
	var contentGalleryTypeStore = new Ext.data.JsonStore(
    {    
	    root: 'data',
	    id:'content_gallery_type_id',// see json output    
	    url	: '/admin/content/getcontentgallerytype',          
        fields:
        [
            {name: 'content_gallery_type_id', type:'int', mapping:'content_gallery_type_id'},
            {name:'content_gallery_type'}
        ]
    }); 
	
	var contentGalleryTypeCombo = new Ext.form.ComboBox(
    {   
    	xtype : 'combo',
        store: contentGalleryTypeStore,   
        fieldLabel: 'Content Gallery Type',   
        displayField:'content_gallery_type',  
        valueField: 'content_gallery_type_id',          
        hiddenName: 'content_gallery_type_id',  
        name: 'content_gallery_type_id',  
        allowBlank: true,     
        editable: false,  
        triggerAction: 'all',   
        valueNotFoundText:'Please Select',  
        emptyText:'Please Select',
        forceSelection: true,
		//mode: 'local',
        selectOnFocus:true,
        listWidth: 177,
        width: 177
    });
	
	var editContentImagesForm = new Ext.FormPanel({
		id         : 'edit-content-images-form',
		title	   : 'Edit Gallery & Images',
		method     : 'POST',
		url        : '/admin/content/setcontentimagesbyid',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'editContentImagesRender',
		width	   : 520,
		labelWidth : 180,
		items: [{
			xtype: 'hidden',
			id   : 'content_images_id',
			name : 'content_images_id'
		 },{
			fieldLabel: 'Source ID',
			id: 'source_system_id',
			name: 'source_system_id',
			width: 300,
			disabled: true
		},{
			fieldLabel: 'Title',
			id: 'title',
			name: 'title',
			width: 300
		},{
			xtype: 'textarea',
			fieldLabel: 'Caption',
			id: 'caption',
			name: 'caption',
			width: 300,
			height:80
		},{
			xtype: 'textarea',
			fieldLabel: 'Keywords',
			id: 'keywords',
			name: 'keywords',
			width: 300,
			height:80
		},{
			xtype: 'textarea',
			fieldLabel: 'Credits',
			id: 'credits',
			name: 'credits',
			width: 300,
			height:80
		},
		contentGalleryTypeCombo,
		{
			xtype: 'checkbox',
			fieldLabel: 'Show Gallery in Multimedia?',
			name: 'show_gallery_in_multimedia'
		},{
			fieldLabel: 'Views',
			id: 'views',
			name: 'views',
			width: 300,
			disabled: true
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {						
					editContentImagesForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, editContentImagesResp){	
							document.getElementById('editContentImagesRender').style.visibility = 'hidden';		
							contentImagesStore.load({ params:{ start:0,limit:16 } });					
						}	
					})
				}
			},{
				text:'Close',
				handler:function() {
					document.getElementById('editContentImagesRender').style.visibility = 'hidden';
				}
			}
		]
	});


	contentImagesGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		var content_images_id = gridPanel.getStore().getAt(rowIndex).get('content_images_id');
		editContentImagesForm.form.load({
			url:'/admin/content/getcontentimagesbyid',
		    method:'GET',
		    params:{ content_images_id:content_images_id },
		    waitmsg: 'Loading...',
		    success: function(login, editContentImagesResp){	
				document.getElementById('editContentImagesRender').style.visibility = 'visible';			
			}
		});
		
	});
	
	contentImagesStore.load({params:{start:0, limit:16}}); 
	contentGalleryTypeStore.load();

}); 
