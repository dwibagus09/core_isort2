var loadMaskUpload;
var date = new Date();
var sessionUniqueId = date.getHours() + '' + date.getMinutes() + '' + date.getSeconds() + '' + date.getUTCMilliseconds();
Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	var cur_content_gallery_id;
	var onUpload = false;
	var flag;
	var curFile;
	
	var image;
	
	var start = 0;
	var isUploaderCompatible = true;

	loadMaskUpload = new Ext.LoadMask(Ext.getBody(), {msg:"Uploading Files, please wait..."});
	var loadMaskDelete = new Ext.LoadMask(Ext.getBody(), {msg:"Deleting Data, please wait..."});
	
	var SendItViaAjax = function (val, url, flag){
		var AjaxLoad = Ext.Ajax.request({
			url: url,
			params: {  datas : val  },
			success: function(){
				loadMaskDelete.hide();
				if(flag == 0)
				{
					var t = contentGalleryGrid.getBottomToolbar();
					start = t.cursor;
					var typesearch = document.getElementById('typesearch').value;
			    	var startdatesearch = document.getElementById('startdatesearch').value;
			    	var enddatesearch = document.getElementById('enddatesearch').value;
			    	var query = Ext.getCmp('query').getValue();
	            	contentGalleryStore.load({ params:{ start: start,limit:14, content_gallery_type_id:typesearch, startdatesearch:startdatesearch, enddatesearch:enddatesearch, query:query } });
				}
				imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });		
				//console.log(val);
			},
		    failure: function(form,action){
		    	loadMaskDelete.hide();
				Ext.MessageBox.alert('Error', '');
		   }
		  
		});
	};
	
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
	var smugmugCategoryCombo1 = new Ext.form.ComboBox({
		allowBlank    : true,
		forceSelection: true,
		lazyRender    : true,
		hidden		  : ((useSmugMug==1)? false: true),
		fieldLabel	  : 'Smugmug Category',
		listClass     : 'x-combo-list-small',
		transform     : 'smugmug_categoryid1',
		triggerAction : 'all',
		editable	  : false,
		typeAhead     : false
	});
	
	var ContentGallery = Ext.data.Record.create([
		{name: 'site_id'},
		{name: 'content_gallery_id'},
		{name: 'content_gallery_type_id'},
		{name: 'content_gallery'},
		{name: 'views'},
		{name: 'image_count'},
		{name: 'thumbnail_content_images_id'},
		{name: 'show_gallery_in_multimedia'},
		{name: 'modify_date_time'},
		{name: 'create_date_time'},
		{name: 'content_gallery_type'},
		{name: 'headline'},
		{name: 'smugmug_id'},
		{name: 'smugmug_key'},
		{name: 'smugmug_categoryid'},
		{name: 'keywords'},
		{name: 'sites'},
		{name: 'for_sale'},
		{name: 'public'}
	]);
	
	var connObjContentGallery = new Ext.data.Connection({
	    timeout : 120000,
	    url : '/admin/content/getcontentgallery',
	    method : 'GET'
	});
	
	// ContentGallery :: Data Store
	var contentGalleryStore = new Ext.data.Store({
		autoLoad: false,
		proxy : new Ext.data.HttpProxy(connObjContentGallery),
		reader  : new Ext.data.JsonReader( {
			root: "data",					// The property which contains an Array of row objects
			totalProperty: 'total'
		}, ContentGallery)
	});
	
	
	//var show_gallery_in_multimedia = new Ext.grid.CheckColumn({ header: "Show in Multimedia"   , width:100  ,  dataIndex: 'show_gallery_in_multimedia' , readonly: true });
	
	var contentGalleryCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	// ContentGallery :: Column Model
	var contentGalleryColModel = new Ext.grid.ColumnModel([
	 	contentGalleryCheckbox,
		{id:'content_gallery_id' , header:"Id"   , width:40 , sortable:true, locked:true, dataIndex:'content_gallery_id'},
		{id:'content_gallery_type' 	, header:"Type"    , width:50 , sortable:true, locked:true, dataIndex:'content_gallery_type'},
		{id:'smugmug_categoryid'  , header:"Smugmug Category" , width:80, hidden:((useSmugMug==1)? false: true), sortable:true, locked:true, dataIndex:'smugmug_categoryid',
			renderer:function(val) {
			    var rec = smugmugCategoryOptionsStore.getById(val);
				return rec ? rec.get('category_name'):'Other';
			}
		},
		{id:'content_gallery'     		, header:"Content Gallery"      , width:150 , sortable:true, locked:true, dataIndex:'content_gallery'},
		{id:'show_gallery_in_multimedia', header:"Show in Multimedia"   , width:70 , sortable:true, locked:true, dataIndex:'show_gallery_in_multimedia'},
		{id:'headline'     		, header:"Articles"      , width:255 , sortable:true, locked:true, dataIndex:'headline'}
	]);	
	
	var galleryTypeStore = new Ext.data.JsonStore(
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

    var typeSearch = new Ext.form.ComboBox(
    {   
        store: galleryTypeStore,   
        fieldLabel: 'Gallery Type',   
        displayField:'content_gallery_type',  
        valueField: 'content_gallery_type_id',          
        hiddenName: 'typesearch', 
        allowBlank: true,     
        editable: false,  
        triggerAction: 'all',   
        valueNotFoundText:'Please Select',  
        emptyText:'Please Select',
        forceSelection: true,
        selectOnFocus:true,
        listWidth: 100,
        width: 100
    });	

    var startdatesearch = new Ext.form.DateField ({
		id			  : 'startdatesearch',
		name          : 'startdatesearch',
		format		  : 'Y-m-d'
	});
	
	var enddatesearch = new Ext.form.DateField ({
		id			  : 'enddatesearch',
		name          : 'enddatesearch',
		format		  : 'Y-m-d'
	});
	
	var galleryPagingToolbar = new Ext.PagingToolbar({
        pageSize: 14,
        displayInfo: true,
        displayMsg: 'Total Records {0} - {1} of {2}',
        store: contentGalleryStore,
       	emptyMsg: "No topics to display"        
    });
	
	var contentGalleryGrid = new Ext.grid.GridPanel({ 
		store		: contentGalleryStore,
		colModel	: contentGalleryColModel,
		selModel    : contentGalleryCheckbox,
		height      : 422,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
	    title       : "Gallery",
	 	tbar: [{
				text   : 'Add New Gallery',
				tooltip: 'Add a new gallery',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
	            	/*document.getElementById('editContentGalleryRender').style.visibility = 'hidden';
					document.getElementById('addContentGalleryRender').style.visibility = 'visible';*/
					addContentGalleryWin.show();
					Ext.getCmp('sites2').clearValue();
					Ext.getCmp('sites2').setValue(defSiteId);
				}
			},'-',{
				text   : 'Delete Gallery',
				tooltip: 'Delete selected gallery',
				iconCls: 'delete',
				hidden :modReadOnly,
				handler: function() {
	            	/*document.getElementById('addContentGalleryRender').style.visibility = 'hidden';
	            	document.getElementById('editContentGalleryRender').style.visibility = 'hidden';*/
					
					Ext.MessageBox.confirm('Confirm', 'Are you sure want to delete the selected gallery?', function(btn) {
	            		if ( btn == 'yes' ) {
	            			var selection = contentGalleryGrid.getSelectionModel().getSelections();
							var countRows = contentGalleryGrid.getSelectionModel().getCount();	
							var store = contentGalleryGrid.getStore();											
							if(countRows > 0){	
								var strings = '';
								for(i = 0 ;i < countRows;i++){										
									strings += selection[i].get('content_gallery_id').toString()+',';	
									store.remove(selection[i]);																																		
								}															
								strings = strings.substring(0,strings.length-1);
								loadMaskDelete.show();
								SendItViaAjax(strings, '/admin/content/deletegallery', 0);									
							}
	            		}
	            	});
				}
			}],
		bbar		: galleryPagingToolbar
	});
	
	contentGalleryGrid.on('render',function(){
		new Ext.Toolbar({
			renderTo: this.tbar,
			items: [new Ext.form.Label({
		        html: 'Type :',
		        style : 'color:white'       
		    }),' ',
		    typeSearch,' ',
		    new Ext.form.Label({
		        html: '&nbsp;&nbsp;&nbsp;Start :',
		        style : 'color:white'       
		    }),
			startdatesearch,' ',
			new Ext.form.Label({
		        html: '&nbsp;&nbsp;&nbsp;End :',
		        style : 'color:white'       
		    }),
		    enddatesearch
		    ]
		});
		new Ext.Toolbar({
			renderTo: this.tbar,
			items: [
			'Keyword: ',
		    {
				xtype:'textfield',
				id: 'query',
				width:300,
				listeners:{
					render: function(f){
						this.el.on('keyup', function (f, e){
							var typesearch = document.getElementById('typesearch').value;
					    	var startdatesearch = document.getElementById('startdatesearch').value;
					    	var enddatesearch = document.getElementById('enddatesearch').value;
					    	var query = Ext.getCmp('query').getValue();
			            	contentGalleryStore.load({ params:{ start:0,limit:14, content_gallery_type_id:typesearch, startdatesearch:startdatesearch, enddatesearch:enddatesearch, query:query } });
						}, this, {buffer: 500});
					}
				}
			},
		    {
				xtype: 'button',
				id:'btnSearchGal',
			    iconCls: 'search',
			    handler: function() {
			    	var typesearch = document.getElementById('typesearch').value;
			    	var startdatesearch = document.getElementById('startdatesearch').value;
			    	var enddatesearch = document.getElementById('enddatesearch').value;
			    	var query = Ext.getCmp('query').getValue();
	            	contentGalleryStore.load({ params:{ start:0,limit:14, content_gallery_type_id:typesearch, startdatesearch:startdatesearch, enddatesearch:enddatesearch, query:query } });
				}
		    }]
		});
		//this.syncSize();
	});
	
	/*** CONTENT IMAGES ***/
	
	var Images = Ext.data.Record.create([
		{name: 'site_id'},
		{name: 'content_gallery_images_id'},
		{name: 'content_images_id'},
		{name: 'source_system_id'},
		{name: 'title'},
		{name: 'caption'},
		{name: 'keywords'},
		{name: 'credits'},
		{name: 'video_tag'},
		{name: 'views'},
		{name: 'modify_date_time'},
		{name: 'create_date_time'},
		{name: 'image_id'},
		{name: 'image'},
		{name: 'image_source'},
		{name: 'image_type'},
		{name: 'file_type'},
		{name: 'file_name'},
		{name: 'image_class'},
		{name: 'sequence'}
	]);
	
	var imagesStore = new Ext.data.Store({
		url     : '/admin/content/getimages',
		autoLoad: false,
		reader  : new Ext.data.JsonReader( {
			root: "data"
		}, Images)
	});
	
	var imagesCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	var imagesColModel = new Ext.grid.ColumnModel([
		imagesCheckbox,
		{id:'source_system_id' , header:"Image" , width:100 , sortable:true, locked:true, dataIndex:'source_system_id', renderer:function (value, field, rec) {	
			var url = value;
			var temp = [];
			if(typeof url == "string") var temp = url.split('/');
			var filename=value;
			if(temp.length > 0)
				filename = temp[temp.length-1];
			if(rec.data.image_class=="Video" && rec.data.video_tag) {
				if(rec.data.video_tag.indexOf('BrightcoveExperience') && !rec.data.video_tag.indexOf('<iframe') && !rec.data.video_tag.indexOf('<video')) {
					return value ? '<a href="#" onclick="new ShowPicture({url:\'' + value + '\',title:\'' + ((rec.data.file_name)?rec.data.file_name:filename) + '\'});  return false;">' + ((rec.data.file_name)?rec.data.file_name:filename)+ '</a>' : '';
				}
			}
			else if(rec.data.video_tag && rec.data.video_tag.length > 0) {
				rec.data.video_tag = rec.data.video_tag.replace(/\"/g, "'");
				rec.data.video_tag = rec.data.video_tag.replace(/\'/g, "\\'");
				rec.data.video_tag = rec.data.video_tag.replace(/(\r\n|\n|\r)/gm, '');
				return value ? '<a href="#" onclick="new ShowPicture({title:\'\', url:\'\',tag:\'' + rec.data.video_tag + '\'});  return false;">' + ((rec.data.file_name)?rec.data.file_name:((temp[temp.length-2])?temp[temp.length-2]:rec.data.image_id)) + '</a>' : '';
			} else if(rec.data.image_class=="Video" && rec.data.source_system_id.indexOf('youtube')) {
				/*var youtubeURI = rec.data.source_system_id;
				youtubeURI = youtubeURI.substr(youtubeURI.indexOf('/vi/')+4, youtubeURI.length-youtubeURI.indexOf('/vi/')-10);
				return value ? '<a href="#" onclick="new ShowPicture({youtube:\'' + youtubeURI + '\',url:\'' + imageURL + value + '\',title:\'' + value + '\'});  return false;">' + filename + '</a>' : '';*/
				return value ? '<a href="#" onclick="new ShowPicture({url:\'' + imageURL + value + '\',title:\'' + value + '\'});  return false;">' + ((rec.data.file_name)?rec.data.file_name:temp[temp.length-2]) + '</a>' : '';
			} else {
				return value ? '<a href="#" onclick="new ShowPicture({url:\'' + imageURL + value + '\',title:\'' + value + '\'});  return false;">' + ((rec.data.file_name)?rec.data.file_name:filename)+ '</a>' : '';		
			}
		}},
		{id:'title' , header:"Title" , width:200 , sortable:true, locked:true, dataIndex:'title'},
		{id:'sequence' , header:"Sequence" , width:60 , sortable:true, locked:true, dataIndex:'sequence'}
	]);	
	
	var imagesGrid = new Ext.grid.GridPanel({ 
		store		: imagesStore,
		colModel	: imagesColModel,
		selModel    : imagesCheckbox,
		height      : 422,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
	    title       : "Images",
	 	tbar: [{
				text   : 'Add New Image',
				tooltip: 'Add a new image',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
					$('#rsmAddPhotoUl').children().remove();
					$('#rsmAddPhotoDiv').show();
					$('#rsmAddPhotoProgress').hide();
					if(r && r.files) r.files.pop();
	            	document.getElementById('editImageRender').style.visibility = 'hidden';
					document.getElementById('addImageRender').style.visibility = 'visible';
					document.getElementById('addImagesRender').style.visibility = 'hidden';
				}
			},'-',{
				text   : 'Upload Multiple Images',
				tooltip: 'Upload multiple photos at once',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
					$('#rsmAddPhotosUl').children().remove();
					$('#rsmAddPhotosDiv').show();
					$('#rsmAddPhotosProgress').hide();
					if(r2 && r2.files) r2.files.pop();
	            	document.getElementById('editImageRender').style.visibility = 'hidden';
					document.getElementById('addImageRender').style.visibility = 'hidden';
					document.getElementById('addImagesRender').style.visibility = 'visible';
				}
			},'-',{
				text   : 'Delete Images',
				tooltip: 'Delete selected images',
				iconCls: 'delete',
				hidden :modReadOnly,
				handler: function() {
	            	document.getElementById('addImageRender').style.visibility = 'hidden';
	            	document.getElementById('editImageRender').style.visibility = 'hidden';
					document.getElementById('addImagesRender').style.visibility = 'hidden';
					
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete these images?', function(btn) {
	            		if ( btn == 'yes' ) {
	            			var selection = imagesGrid.getSelectionModel().getSelections();
							var countRows = imagesGrid.getSelectionModel().getCount();	
							var store = imagesGrid.getStore();											
							if(countRows > 0){	
								var strings = '';
								for(i = 0 ;i < countRows;i++){										
									strings += selection[i].get('content_gallery_images_id').toString()+',';	
									store.remove(selection[i]);																																		
								}															
								strings = strings.substring(0,strings.length-1);
								loadMaskDelete.show();
								SendItViaAjax(strings, '/admin/content/deleteimages', 1);									
							}
	            		}
	            	});
				}
			}
		]
	});
	
	var contentGalleryForm = new Ext.FormPanel({
		id        : 'content-gallery-panel',
		method    : 'POST',
		//url       : '',
		title	  : 'Content Gallery',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'contentGalleryRender',
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [{
			xtype      : 'fieldset',
			width	   : 500,
			labelWidth : 150,
			defaultType: 'textfield',
			autoHeight : true,
			bodyStyle  : Ext.isIE ? 'padding:0 0 0px 0px;' : 'padding: 5px 5px;',
			border     : false,
			layout	   : 'column',
			style      : {
				"margin-left": "0px", 													// when you add custom margin in IE 6...
				"margin-right": "5px",	// you have to adjust for it somewhere else
				"margin-bottom": "0px",
				"padding": "0px 5px"
			},
			items      : [
					contentGalleryGrid
				]
			},{
				xtype      : 'fieldset',
				width	   : 425,
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
						imagesGrid
				]
			}	
		]
	});
	
	/*** ADD & EDIT CONTENT GALLERY ***/
    
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
    
    var editGalleryTypeCombo = new Ext.form.ComboBox(
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
	
    var msSites1 = new Ext.ux.Andrie.Select({
		allowBlank:true,
		fieldLabel:'Sites',
		multiSelect:true,
		store: sitesStore,
		name:'sites',
		id:'sites2',
		valueField:'site_id',
		displayField:'name',
		triggerAction:'all',
		dataIndex:'sites',
		width:300,
		listWidth:300,
		mode:'local'
	});
    
	var addContentGalleryForm = new Ext.FormPanel({
		id         : 'add-content-gallery-form',
		//title	   : 'Add Content Gallery',
		method     : 'POST',
		url        : '/admin/content/addcontentgallery',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		//renderTo   : 'addContentGalleryRender',
		//width	   : 500,
		labelWidth : 160,
		items: [addGalleryTypeCombo,smugmugCategoryCombo1,
		{
			fieldLabel: 'Title',
			name: 'content_gallery',
			width: 300
		},{
			xtype: 'checkbox',
			fieldLabel: 'Show Gallery In Multimedia',
			name: 'show_gallery_in_multimedia'
		},{
			xtype: 'checkbox',
			fieldLabel: 'For Sale?',
			name: 'for_sale',
			checked: true
		},{
			xtype: 'checkbox',
			fieldLabel: 'Public?',
			name: 'public',
			checked: true
		},{
			xtype:'textarea',
			fieldLabel: 'Keywords',
			width: 300,
			name: 'keywords'
		},
		msSites1]
	});
	
	var addContentGalleryWin = new Ext.Window({
		layout:'fit',		
		title: 'Add Content Gallery',
		width:520,
		height:370,
		closeAction:'hide',
		plain: true,		
		items: addContentGalleryForm,	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {						
					addContentGalleryForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, addContentGalleryResp){	
							//document.getElementById('addContentGalleryRender').style.visibility = 'hidden';		
							addContentGalleryWin.hide();
							addContentGalleryForm.getForm().reset();
							Ext.getCmp('sites2').clearValue();
							var typesearch = document.getElementById('typesearch').value;
					    	var startdatesearch = document.getElementById('startdatesearch').value;
					    	var enddatesearch = document.getElementById('enddatesearch').value;
					    	var query = Ext.getCmp('query').getValue();
			            	contentGalleryStore.load({ params:{ start:0,limit:14, content_gallery_type_id:typesearch, startdatesearch:startdatesearch, enddatesearch:enddatesearch, query:query } });
						}	
					})
				}
			},{
				text:'Close',
				handler:function() {
					//document.getElementById('addContentGalleryRender').style.visibility = 'hidden';
					addContentGalleryWin.hide();
					addContentGalleryForm.getForm().reset();
				}
			}
		]
	});
	
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
		width:300,
		listWidth:300,
		mode:'local'
	});
		
	var editContentGalleryForm = new Ext.FormPanel({
		id         : 'edit-content-gallery-form',
		//title	   : 'Edit Content Gallery',
		method     : 'POST',
		url        : '/admin/content/updatecontentgallery',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		//renderTo   : 'editContentGalleryRender',
		//width	   : 500,
		labelWidth : 160,
		items: [{
			xtype: 'hidden',
			id   : 'content_gallery_id',
			name : 'content_gallery_id'
		 },{
			xtype: 'hidden',
			id   : 'smugmug_id',
			name : 'smugmug_id'
		 },{
			xtype: 'hidden',
			id   : 'smugmug_key',
			name : 'smugmug_key'
		 },editGalleryTypeCombo,smugmugCategoryCombo,{
			fieldLabel: 'Title',
			name: 'content_gallery',
			width: 300
		},{
			fieldLabel: 'Views',
			id: 'views',
			name: 'views',
			width: 300,
			disabled: true
		},{
			fieldLabel: 'Image Count',
			id: 'image_count',
			name: 'image_count',
			width: 300,
			disabled: true
		},{
			fieldLabel: 'Content Image Id',
			id: 'thumbnail_content_images_id',
			name: 'thumbnail_content_images_id',
			width: 300,
			disabled: true
		},{
			xtype: 'checkbox',
			fieldLabel: 'Show Gallery In Multimedia?',
			name: 'show_gallery_in_multimedia'
		},{
			xtype: 'checkbox',
			fieldLabel: 'For Sale?',
			name: 'for_sale'
		},{
			xtype: 'checkbox',
			fieldLabel: 'Public?',
			name: 'public'
		},
		{
			xtype:'textarea',
			fieldLabel: 'Keywords',
			width: 300,
			name: 'keywords'
		},
		msSites
		
		]
	});

	var editContentGalleryWin = new Ext.Window({
		layout:'fit',		
		title: 'Edit Content Gallery',
		width:520,
		height:430,
		closeAction:'hide',
		plain: true,		
		items: editContentGalleryForm,	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {						
					editContentGalleryForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, editContentGalleryResp){	
							//document.getElementById('editContentGalleryRender').style.visibility = 'hidden';		
							editContentGalleryWin.hide();
							var typesearch = document.getElementById('typesearch').value;
					    	var startdatesearch = document.getElementById('startdatesearch').value;
					    	var enddatesearch = document.getElementById('enddatesearch').value;
					    	var query = Ext.getCmp('query').getValue();
			            	contentGalleryStore.load({ params:{ start: start,limit:14, content_gallery_type_id:typesearch, startdatesearch:startdatesearch, enddatesearch:enddatesearch, query:query } });
						}	
					})
				}
			},{
				text:'Close',
				handler:function() {
					//document.getElementById('editContentGalleryRender').style.visibility = 'hidden';
					editContentGalleryWin.hide();
				}
			}
		]
	});

	contentGalleryGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		start = galleryPagingToolbar.store.lastOptions.params.start;
		var content_gallery_id = gridPanel.getStore().getAt(rowIndex).get('content_gallery_id');
		galleryTypeStore.load();
		editContentGalleryWin.show();
		editContentGalleryForm.form.load({
			url:'/admin/content/getcontentgallerybyid',
		    method:'GET',
		    params:{ content_gallery_id:content_gallery_id },
		    waitmsg: 'Loading...',
		    success: function(login, editContentGalleryResp){	
				//document.getElementById('editContentGalleryRender').style.visibility = 'visible';
				Ext.getCmp('sites1').clearValue();
				Ext.getCmp('sites1').setValue(editContentGalleryResp.result.data.sites);
			}
		});
		
	});
	
	contentGalleryGrid.on('rowclick', function(grd, rowIndex, e) { 
		var content_gallery_id = grd.getStore().getAt(rowIndex).get('content_gallery_id');
		cur_content_gallery_id = content_gallery_id;
		imagesStore.load({ params:{ content_gallery_id:content_gallery_id } });
	});
	
	
	contentGalleryStore.on ('beforeload', function (ds, e){
		var typesearch = document.getElementById('typesearch').value;
    	var startdatesearch = document.getElementById('startdatesearch').value;
    	var enddatesearch = document.getElementById('enddatesearch').value;
    	var query = Ext.getCmp('query').getValue();
    	ds.baseParams.limit = 14;
    	ds.baseParams.content_gallery_type_id = typesearch;
    	ds.baseParams.startdatesearch = startdatesearch;
    	ds.baseParams.enddatesearch = enddatesearch;
    	ds.baseParams.query = query;
	});
	
	contentGalleryStore.load({params:{start:0, limit:14}});
	
	/*** ADD & EDIT IMAGES ***/
	
	var imgstr = null;

	imgstr = new Ext.data.JsonStore({
	    url: "/admin/content/getimage",
	    method : "post",
	    root: 'result',
	    fields: ['fileName','filePath']
	});
	
	function SetIsImgUpload(imgobj, imgnameobj, value){
		imgobj.setValue(value);
		if (imgobj.getValue() == 0){
			imgnameobj.setValue("");
		}
	}
		
	var img = new Ext.form.Hidden({id:"img",name:"img"});
	var imgname = new Ext.form.Hidden({id:"imgname",name:"imgname"});
	
	function callback(options, success, response) {
		var res = Ext.util.JSON.decode(response.responseText);
		
		if(res.success == false)
		{
			loadMaskUpload.hide();
			Ext.MessageBox.alert('Result', res.errors.reason);
		}
		else
		{			
			var filename = res.filename;
			loadMaskUpload.hide();
			if(flag == 0)
			{
				addImageForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg: 'Please Wait...',
					params: {filename:filename, content_gallery_id:cur_content_gallery_id},
					success: function(login, addImageResp){	
						document.getElementById('addImageRender').style.visibility = 'hidden';		
						document.getElementById('addImagesRender').style.visibility = 'hidden';
						addImageForm.getForm().reset();
						imgupload.clearAllFiles();	
						imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });						
					}	
				})
			}
			else if(flag == 1)
			{	
				editImageForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg: 'Please Wait...',
					params: {filename:filename, content_gallery_id:cur_content_gallery_id},
					success: function(login, editImageResp){	
						document.getElementById('editImageRender').style.visibility = 'hidden';
						document.getElementById('addImagesRender').style.visibility = 'hidden';
						editImageForm.getForm().reset();
						editimgupload.clearAllFiles();	
						imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });						
					}	
				})
			}
			img.setValue(0);
			return true;
		}
	}
	
	var imgupload = new Ext.ux.UploadPanel ({
		buttonsAt:'tbar',
		id:'imgpanel',
		url:'/admin/content/uploadimage',
		path:'root',
		maxFileSize:21474836480,
		maxTotalFile:1,
		style:'min-height:50px; margin:5px 0px;',
		panelHeaderText:"Image/Video ( Max 1 file, Max Size:20GB, format : jpg, png and all supported video files )",
		singleUpload:true,
		isUsingMsgBoxForError:true,
		enableProgress: true,
		singleUpload: true,
		progressInterval:4000,
		progressUrl: '/admin/content/progress',
		width :460
	});
	
	var editimgupload = new Ext.ux.UploadPanel ({
		buttonsAt:'tbar',
		id:'editimgpanel',
		url:'/admin/content/uploadimage',
		path:'root',
		maxFileSize:21474836480,
		maxTotalFile:1,
		style:'min-height:50px; margin:5px 0px;',
		panelHeaderText:"Image/Video ( Max 1 Pic, Max Size:20GB, format : jpg, png and all supported video files )",
		singleUpload:true,
		isUsingMsgBoxForError:true,
		enableProgress: true,
		singleUpload: true,
		progressInterval:4000,
		progressUrl: '/admin/content/progress',
		width :460
	});
	
	editimgupload.on ('fileadd', function(){SetIsImgUpload(img, imgname, 1);} );
	editimgupload.on ('fileremove', function(){SetIsImgUpload(img, imgname, 0);} );
	editimgupload.on ('queueclear', function(){SetIsImgUpload(img, imgname, 0);} );
	
	imgupload.on ('fileadd', function(){SetIsImgUpload(img, imgname, 1);} );
	imgupload.uploader.on ('progress', function(uploader, data, record) {
		var totalSize = 0;
		if(data.bytesTotal >= 1024*1024*1024){
			totalSize = Math.round(data.bytesTotal/(1204*1024*1024)) + 'GB';
		}
		else if(data.bytesTotal >= 1024*1024) {
			totalSize = Math.round(data.bytesTotal/(1024*1024)) + 'MB';
		}
		else {
			totalSize = Math.round(data.bytesTotal/1024) + 'KB';
		}
		loadMaskUpload.msg = parseInt((data.bytesUploaded/data.bytesTotal)*100) + '% of ' + totalSize + ' uploaded. Average speed: ' + parseInt(data.speedAverage/1024) + 'KB/s';
		loadMaskUpload.show();
	});
	imgupload.uploader.on ('allfinished', function() {
		loadMaskUpload.hide();
	});
	editimgupload.uploader.on ('progress', function(uploader, data, record) {
		var totalSize = 0;
		if(data.bytesTotal >= 1024*1024*1024){
			totalSize = Math.round(data.bytesTotal/(1204*1024*1024)) + 'GB';
		}
		else if(data.bytesTotal >= 1024*1024) {
			totalSize = Math.round(data.bytesTotal/(1024*1024)) + 'MB';
		}
		else {
			totalSize = Math.round(data.bytesTotal/1024) + 'KB';
		}
		loadMaskUpload.msg = parseInt((data.bytesUploaded/data.bytesTotal)*100) + '% of ' + totalSize + ' uploaded. Average speed: ' + parseInt(data.speedAverage/1024) + 'KB/s';
		loadMaskUpload.show();
	});
	editimgupload.uploader.on ('allfinished', function() {
		loadMaskUpload.hide();
	});
	
	var addImageForm = new Ext.FormPanel({
		id         : 'add-image-form',
		title	   : 'Add Image',
		method     : 'POST',
		url        : '/admin/content/addimage',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'addImageRender',
		width	   : 500,
		labelWidth : 100,
		items: [imgupload,
		{
			xtype:'label',
			html:'<div id="rsmAddPhotoDiv" class="resumable-drop" ondragenter="jQuery(this).addClass(\'resumable-dragover\');" ondragend="jQuery(this).removeClass(\'resumable-dragover\');" ondrop="jQuery(this).removeClass(\'resumable-dragover\');">' +
      '  Drop video file here to upload or <a class="resumable-browse"><u>select from your computer</u></a>' +
      '</div>' +      
      '<div id="rsmAddPhotoProgress" class="resumable-progress">' +
      '  <table>' +
      '    <tr>' +
      '      <td width="100%"><div class="progress-container"><div class="progress-bar"></div></div></td>' +
      '      <td class="progress-text" nowrap="nowrap"></td>' +
      '      <td class="progress-pause" nowrap="nowrap">' +
      '        <a href="#" onclick="r.upload(); return(false);" class="progress-resume-link"><img border="0" src="/js/resume.png" title="Resume upload" /></a>' +
      '        <a href="#" onclick="r.pause(); return(false);" class="progress-pause-link"><img border="0" src="/js/pause.png" title="Pause upload" /></a>' +
      '        <a href="#" onclick="r.cancel(); return(false);" class="progress-cancel-link"><img border="0" src="/js/stop.png" title="Pause upload" /></a>' +
      '      </td>' +
      '    </tr>' +
      '  </table>' +
      '</div>' +      
      '<ul id="rsmAddPhotoUl" class="resumable-list"></ul>'
		},
		{
			fieldLabel: 'Title',
			name: 'title',
			id:'title1',
			width: 350
		},{
			fieldLabel: 'Caption',
			name: 'caption',
			id:'caption1',
			xtype: 'textarea',
			width: 350,
			height: 100
		},{
			xtype: 'textarea',
			fieldLabel: 'Keywords',
			name: 'keywords',
			id:'keywords1',
			width: 350,
			height: 100
		},{
			xtype: 'textarea',
			fieldLabel: 'Video Tag',
			name: 'video_tag',
			id:'video_tag1',
			width: 350,
			height: 100
		},{
			fieldLabel: 'Credits',
			name: 'credits',
			width: 350
		},{
			fieldLabel: 'Sequence',
			name: 'sequence',
			width: 350,
			maskRe    : /^[0-9]+$/,
			allowBlank: true
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {
					if(onUpload) {
						alert('Uploading files, please wait...');
						return false;
					}
					if(img.value == 1 || isUploaderCompatible)
					{
						flag = 0;				
						imgupload.setUrl('/admin/content/uploadimage');
						var iTitle = Ext.getCmp('title1').getValue();
						var iCaption = Ext.getCmp('caption1').getValue();
						var iKeywords = Ext.getCmp('keywords1').getValue();
						var iVideoTag = Ext.getCmp('video_tag').getValue();
						if(!isUploaderCompatible || img.value == 1) {
							loadMaskUpload.msg = 'Uploading file. Please wait.';
							loadMaskUpload.show();	
							imgupload.uploadFileManually({caption:iCaption, title:iTitle, keywords:iKeywords, content_gallery_id:cur_content_gallery_id, video_tag: iVideoTag}, callback);
						}
						else if(r.files.length > 0) {
							/*
							// Show progress pabr
							$('.resumable-progress, .resumable-list').show();
							// Show pause, hide resume
							$('.resumable-progress .progress-resume-link').hide();
							$('.resumable-progress .progress-pause-link').show();
							$('.resumable-progress .progress-cancel-link').hide();
							*/
							// Show progress pabr
							$('#rsmAddPhotoProgress, #rsmAddPhotoUl').show();
							// Show pause, hide resume
							$('#rsmAddPhotoProgress .progress-resume-link').hide();
							$('#rsmAddPhotoProgress .progress-pause-link').show();
							$('#rsmAddPhotoProgress .progress-cancel-link').hide();
				          // Actually start the upload
				          r.opts.extraParams = {caption:iCaption, title:iTitle, keywords:iKeywords, content_gallery_id:cur_content_gallery_id};
				          r.upload();
				          onUpload = true;
				          
				          $.ajax({
				         	type: "POST",
				         	timeout: 300000,
				         	data: {
				         		uniqueId: sessionUniqueId,
				         		title: iTitle
				         	},
				         	url:'/admin/content/logstart'
				          }).done(function() { });
						}
						else {
							addImageForm.getForm().submit({
								waitTitle: 'Connecting to the database...',
								waitMsg: 'Please Wait...',
								params: { content_gallery_id:cur_content_gallery_id},
								success: function(login, addImageResp){	
									document.getElementById('addImageRender').style.visibility = 'hidden';
									imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });
								}	
							})
						}
					}
					else
					{
						Ext.MessageBox.alert('Warning', "Image should not be empty");
					}
				}
			},{
				text:'Close',
				handler:function() {
					if(onUpload) {
						alert('Uploading files, please wait...');
						return false;
					}
					document.getElementById('addImageRender').style.visibility = 'hidden';
					imgupload.clearAllFiles();	
					addImageForm.getForm().reset();
				}
			}
		]
	});
	
	var addImagesForm = new Ext.FormPanel({
		id         : 'add-images-form',
		title	   : 'Add Multiple Photos',
		method     : 'POST',
		url        : '/admin/content/addimage',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'addImagesRender',
		width	   : 500,
		labelWidth : 100,
		items: [imgupload,
		{
			xtype:'label',
			html:'<div id="rsmAddPhotosDiv" class="resumable-drop" ondragenter="jQuery(this).addClass(\'resumable-dragover\');" ondragend="jQuery(this).removeClass(\'resumable-dragover\');" ondrop="jQuery(this).removeClass(\'resumable-dragover\');">' +
      '  Drop photos file here to upload or <a class="resumable-browse"><u>select from your computer</u></a>' +
      '</div>' +      
      '<div id="rsmAddPhotosProgress" class="resumable-progress">' +
      '  <table>' +
      '    <tr>' +
      '      <td width="100%"><div class="progress-container"><div class="progress-bar"></div></div></td>' +
      '      <td class="progress-text" nowrap="nowrap"></td>' +
      '      <td class="progress-pause" nowrap="nowrap">' +
      '        <a href="#" onclick="r2.upload(); return(false);" class="progress-resume-link"><img border="0" src="/js/resume.png" title="Resume upload" /></a>' +
      '        <a href="#" onclick="r2.pause(); return(false);" class="progress-pause-link"><img border="0" src="/js/pause.png" title="Pause upload" /></a>' +
      '        <a href="#" onclick="r2.cancel(); return(false);" class="progress-cancel-link"><img border="0" src="/js/stop.png" title="Pause upload" /></a>' +
      '      </td>' +
      '    </tr>' +
      '  </table>' +
      '</div>' +      
      '<ul id="rsmAddPhotosUl" class="resumable-list"></ul>'
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Upload',
				hidden :modReadOnly,
				handler:function() {
					if(onUpload) {
						alert('Uploading files, please wait...');
						return false;
					}
					if(img.value == 1 || isUploaderCompatible)
					{
						flag = 0;				
						
						if(r2.files.length > 0) {
							// Show progress pabr
							$('#rsmAddPhotosProgress, #rsmAddPhotosUl').show();
							// Show pause, hide resume
							$('#rsmAddPhotosProgress .progress-resume-link').hide();
							$('#rsmAddPhotosProgress .progress-pause-link').show();
							$('#rsmAddPhotosProgress .progress-cancel-link').hide();
							// Actually start the upload
							r2.opts.extraParams = {content_gallery_id:cur_content_gallery_id};
							r2.upload();
							onUpload = true;
						}
						else {
							Ext.MessageBox.alert('Warning', "Please select photos to upload.");
						}
					}
					else
					{
						Ext.MessageBox.alert('Warning', "Image should not be empty");
					}
				}
			},{
				text:'Close',
				handler:function() {
					if(onUpload) {
						alert('Uploading files, please wait...');
						return false;
					}
					document.getElementById('addImagesRender').style.visibility = 'hidden';
				}
			}
		]
	});
	
	var editImageForm = new Ext.FormPanel({
		id         : 'edit-image-form',
		title	   : 'Edit Image',
		method     : 'POST',
		url        : '/admin/content/updateimage',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'editImageRender',
		width	   : 500,
		labelWidth : 100,
		items: [{
			xtype: 'hidden',
			id   : 'content_images_id',
			name : 'content_images_id'
		},{
			xtype: 'hidden',
			id   : 'image_id',
			name : 'image_id'
		},{
			xtype: 'hidden',
			id   : 'content_gallery_images_id',
			name : 'content_gallery_images_id'
		},editimgupload,
		{
			fieldLabel: 'Title',
			id: 'title',
			name: 'title',
			width: 350
		},{
			fieldLabel: 'Caption',
			id: 'caption',
			name: 'caption',
			xtype: 'textarea',
			width: 350,
			height: 100
		},{
			xtype: 'textarea',
			fieldLabel: 'Keywords',
			id: 'keywords',
			name: 'keywords',
			width: 350,
			height: 100
		},{
			xtype: 'textarea',
			fieldLabel: 'Video Tag',
			name: 'video_tag',
			id:'video_tag',
			width: 350,
			height: 100
		},{
			fieldLabel: 'Credits',
			id: 'credits',
			name: 'credits',
			width: 350
		},{
			fieldLabel: 'Sequence',
			id: 'sequence',
			name: 'sequence',
			width: 350,
			maskRe    : /^[0-9]+$/,
			allowBlank: true
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {	
					if(img.value == 1)
					{
						loadMaskUpload.msg = 'Uploading file. Please wait.';
						loadMaskUpload.show();	
						flag = 1;				
						editimgupload.setUrl('/admin/content/uploadimage');
						var iTitle = Ext.getCmp('title').getValue();
						var iCaption = Ext.getCmp('caption').getValue();
						var iKeywords = Ext.getCmp('keywords').getValue();
						var iVideoTag = Ext.getCmp('video_tag').getValue();
						editimgupload.uploadFileManually({caption:iCaption, title:iTitle, keywords:iKeywords, video_tag: iVideoTag}, callback);
					}
					else
					{				
						editImageForm.getForm().submit({
							waitTitle: 'Connecting to the database...',
							waitMsg: 'Please Wait...',
							params: {content_gallery_id:cur_content_gallery_id},
							success: function(login, editImageResp){	
								document.getElementById('editImageRender').style.visibility = 'hidden';		
								document.getElementById('addImagesRender').style.visibility = 'hidden';
								imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });						
							}	
						})
					}
				}
			},{
				text:'Close',
				handler:function() {
					document.getElementById('editImageRender').style.visibility = 'hidden';
					document.getElementById('addImagesRender').style.visibility = 'hidden';
					editimgupload.clearAllFiles();	
					editImageForm.getForm().reset();
				}
			}
		]
	});
	
	imagesGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		var content_images_id = gridPanel.getStore().getAt(rowIndex).get('content_images_id');
		
		editImageForm.form.load({
			url:'/admin/content/getimagebyid',
		    method:'GET',
		    params:{ content_images_id:content_images_id },
		    waitmsg: 'Loading...',
		    success: function(login, editImageResp){	
		    	imgstr.load({					
					params:{content_images_id:content_images_id},
					callback: function (str, r, o) {
						editimgupload.setUploadFile(imgstr);  
				    }		
				});	
				$('.resumable-list').children().remove();
				$('.resumable-drop').show();
				$('.resumable-progress').hide();
				if(r.files) r.files.pop();
				document.getElementById('editImageRender').style.visibility = 'visible';
			}
		});
		
	});
	
	r = new Resumable({
        target:'/admin/content/chunkupload',
        chunkSize:1*1024*1024,
        simultaneousUploads:4,
        testChunks:false,
        throttleProgressCallbacks:1
    });
	
	r2 = new Resumable({
        target:'/admin/content/photosupload',
        chunkSize:50*1024*1024,
        simultaneousUploads:4,
        testChunks:false,
        throttleProgressCallbacks:1
    });
	
    if(!r.support) {
    	isUploaderCompatible = false;
    } else {
		/*
		// Show a place for dropping/selecting files
		$('.resumable-drop').show();
		r.assignDrop($('.resumable-drop')[0]);
		r.assignBrowse($('.resumable-browse')[0]);
		$('.resumable-progress .progress-cancel-link').hide();
		// Handle file add event
		r.on('fileAdded', function(file){
			curFile = file;
			var curFileName = file.fileName;
			var temp = curFileName.split('.');
			var ext = temp[temp.length-1];
			ext = ext.toLowerCase();
			if(ext != "avi" && ext != "mpeg" && ext != "mov" && ext != "mpg" && ext != "mp4" && ext !=  "wmv" && ext !=  "ogg" && ext !=  "webm" && ext !=  "m1v" && ext !=  "m4v" && ext !=  "flv" && ext != "3gp") {
				r.files.pop();
				alert('Only video file is allowed.');
				return false;
			}
			$('.resumable-list').append('<li class="resumable-file-'+curFile.uniqueIdentifier+'"><span class="resumable-file-name">' + file.fileName + '</span> <span class="resumable-file-progress"></span>'+ '<a href="#" onclick="r.removeFile(\'' + file.fileName + '\');r.files.pop();$(this).parent().remove();$(\'.resumable-drop\').show();return false;"> Cancel</a>');
			$('.resumable-list').show();
			$('.resumable-drop').hide();
        });
		r.on('pause', function(){
			// Show resume, hide pause
			$('.resumable-progress .progress-resume-link').show();
			$('.resumable-progress .progress-pause-link').hide();
			$('.resumable-progress .progress-cancel-link').hide();
		});
		r.on('complete', function(){
			// Hide pause/resume when the upload has completed
			$('.resumable-progress .progress-resume-link, .resumable-progress .progress-pause-link, .resumable-progress .progress-cancel-link').hide();
			filename = curFile.fileName;
          	onUpload = false;
         	if(flag == 0)
			{
				addImageForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg: 'Please Wait...',
					params: {filename:filename, content_gallery_id:cur_content_gallery_id, uniqueId: sessionUniqueId},
					success: function(login, addImageResp){	
						document.getElementById('addImageRender').style.visibility = 'hidden';		
						imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });
					}	
				})
			}
			else if(flag == 1)
			{	
				editImageForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg: 'Please Wait...',
					params: {filename:filename, content_gallery_id:cur_content_gallery_id, uniqueId: sessionUniqueId},
					success: function(login, editImageResp){	
						document.getElementById('editImageRender').style.visibility = 'hidden';
						imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });
					}	
				})
			}
        });
		r.on('fileSuccess', function(file,message){
			// Reflect that the file upload has completed
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-name').html(file.fileName + ' (completed, uploading to youtube. Please be patient...)');
          
			$('.resumable-progress, .resumable-list').show();
			// Show pause, hide resume
			$('.resumable-progress .progress-resume-link').hide();
			$('.resumable-progress .progress-pause-link').hide();
			$('.resumable-progress .progress-cancel-link').hide();
          
			onUpload = false;
        });
		r.on('fileError', function(file, message){
			// Reflect that the file upload has resulted in error
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-name').html(file.fileName + ' (file could not be uploaded: '+message+')');
          
			$('.resumable-progress, .resumable-list').hide();
			// Show pause, hide resume
			$('.resumable-progress .progress-resume-link').hide();
			$('.resumable-progress .progress-pause-link').hide();
			$('.resumable-progress .progress-cancel-link').hide();
			onUpload = false;
          
			$.ajax({
				type: "POST",
				timeout: 300000,
				data: {
					uniqueId: sessionUniqueId,
					fileName: file.fileName
				},
				url:'/admin/content/fileuploadfail'
			}).done(function() { });
		});
		r.on('fileProgress', function(file){
			// Handle progress for both the file and the overall upload
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-progress').html(Math.floor(file.progress()*100) + '%');
			$('.progress-bar').css({width:Math.floor(r.progress()*100) + '%'});
        });*/
		$('#rsmAddPhotoDiv').show();
		r.assignDrop($('#rsmAddPhotoDiv'));
		r.assignBrowse($('#rsmAddPhotoDiv .resumable-browse')[0]);
		$('#rsmAddPhotoProgress .progress-cancel-link').hide();
		// Handle file add event
		r.on('fileAdded', function(file){
			curFile = file;
			var curFileName = file.fileName;
			var temp = curFileName.split('.');
			var ext = temp[temp.length-1];
			ext = ext.toLowerCase();
			if(ext != "avi" && ext != "mpeg" && ext != "mov" && ext != "mpg" && ext != "mp4" && ext !=  "wmv" && ext !=  "ogg" && ext !=  "webm" && ext !=  "m1v" && ext !=  "m4v" && ext !=  "flv" && ext != "3gp") {
				r.files.pop();
				alert('Only video file is allowed.');
				return false;
			}
			$('#rsmAddPhotoUl').append('<li class="resumable-file-'+curFile.uniqueIdentifier+'"><span class="resumable-file-name">' + file.fileName + '</span> <span class="resumable-file-progress"></span>'+ '<a href="#" onclick="r.removeFile(\'' + file.fileName + '\');r.files.pop();$(this).parent().remove();$(\'#rsmAddPhotoDiv\').show();return false;"> Cancel</a>');
			$('#rsmAddPhotoUl').show();
			$('#rsmAddPhotoDiv').hide();
        });
		r.on('pause', function(){
			// Show resume, hide pause
			$('#rsmAddPhotoProgress .progress-resume-link').show();
			$('#rsmAddPhotoProgress .progress-pause-link').hide();
			$('#rsmAddPhotoProgress .progress-cancel-link').hide();
		});
		r.on('complete', function(){
			// Hide pause/resume when the upload has completed
			$('#rsmAddPhotoProgress .progress-resume-link, #rsmAddPhotoProgress .progress-pause-link, #rsmAddPhotoProgress .progress-cancel-link').hide();
			filename = curFile.fileName;
          	onUpload = false;
         	if(flag == 0)
			{
				addImageForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg: 'Please Wait...',
					params: {filename:filename, content_gallery_id:cur_content_gallery_id, uniqueId: sessionUniqueId},
					success: function(login, addImageResp){	
						document.getElementById('addImageRender').style.visibility = 'hidden';		
						imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });
					}	
				})
			}
			else if(flag == 1)
			{	
				editImageForm.getForm().submit({
					waitTitle: 'Connecting to the database...',
					waitMsg: 'Please Wait...',
					params: {filename:filename, content_gallery_id:cur_content_gallery_id, uniqueId: sessionUniqueId},
					success: function(login, editImageResp){	
						document.getElementById('editImageRender').style.visibility = 'hidden';
						imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });
					}	
				})
			}
        });
		r.on('fileSuccess', function(file,message){
			// Reflect that the file upload has completed
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-name').html(file.fileName + ' (completed, uploading to youtube. Please be patient...)');
          
			$('#rsmAddPhotoProgress, .resumable-list').show();
			// Show pause, hide resume
			$('#rsmAddPhotoProgress .progress-resume-link').hide();
			$('#rsmAddPhotoProgress .progress-pause-link').hide();
			$('#rsmAddPhotoProgress .progress-cancel-link').hide();
          
			onUpload = false;
        });
		r.on('fileError', function(file, message){
			// Reflect that the file upload has resulted in error
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-name').html(file.fileName + ' (file could not be uploaded: '+message+')');
          
			$('#rsmAddPhotoProgress, #rsmAddPhotoUl').hide();
			// Show pause, hide resume
			$('#rsmAddPhotoProgress .progress-resume-link').hide();
			$('#rsmAddPhotoProgress .progress-pause-link').hide();
			$('#rsmAddPhotoProgress .progress-cancel-link').hide();
			onUpload = false;
          
			$.ajax({
				type: "POST",
				timeout: 300000,
				data: {
					uniqueId: sessionUniqueId,
					fileName: file.fileName
				},
				url:'/admin/content/fileuploadfail'
			}).done(function() { });
		});
		r.on('fileProgress', function(file){
			// Handle progress for both the file and the overall upload
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-progress').html(Math.floor(file.progress()*100) + '%');
			$('#rsmAddPhotoProgress .progress-bar').css({width:Math.floor(r.progress()*100) + '%'});
        });
		
		
		$('#rsmAddPhotosDiv').show();
		r2.assignDrop($('#rsmAddPhotosDiv'));
		r2.assignBrowse($('#rsmAddPhotosDiv .resumable-browse')[0]);
		$('#rsmAddPhotosProgress .progress-cancel-link').hide();
		// Handle file add event
		r2.on('fileAdded', function(file){
			curFile = file;
			var curFileName = file.fileName;
			var temp = curFileName.split('.');
			var ext = temp[temp.length-1];
			ext = ext.toLowerCase();
			if(ext != "jpg" && ext != "jpeg") {
				r.files.pop();
				alert('Only JPEG file is allowed.');
				return false;
			}
			$('#rsmAddPhotosUl').append('<li class="resumable-file-'+curFile.uniqueIdentifier+'"><span class="resumable-file-name">' + file.fileName + '</span> <span class="resumable-file-progress"></span>'+ '<a href="#" onclick="r2.removeFile(\'' + file.fileName + '\');r2.files.pop();$(this).parent().remove();$(\'#rsmAddPhotosDiv\').show();return false;"> Cancel</a>');
			$('#rsmAddPhotosUl').show();
			$('#rsmAddPhotosDiv').hide();
        });
		r2.on('pause', function(){
			// Show resume, hide pause
			$('#rsmAddPhotosProgress .progress-resume-link').show();
			$('#rsmAddPhotosProgress .progress-pause-link').hide();
			$('#rsmAddPhotosProgress .progress-cancel-link').hide();
		});
		r2.on('complete', function(){
			// Hide pause/resume when the upload has completed
			$('#rsmAddPhotosProgress .progress-resume-link, #rsmAddPhotosProgress .progress-pause-link, #rsmAddPhotosProgress .progress-cancel-link').hide();
			filename = curFile.fileName;
          	onUpload = false;
			
			document.getElementById('addImagesRender').style.visibility = 'hidden';
			imagesStore.load({ params:{ content_gallery_id:cur_content_gallery_id } });
        });
		r2.on('fileSuccess', function(file,message){
			// Reflect that the file upload has completed
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-name').html(file.fileName + ' (completed)');
          
			$('#rsmAddPhotosProgress, .resumable-list').show();
			// Show pause, hide resume
			$('#rsmAddPhotosProgress .progress-resume-link').hide();
			$('#rsmAddPhotosProgress .progress-pause-link').hide();
			$('#rsmAddPhotosProgress .progress-cancel-link').hide();
			$('.resumable-file-'+file.uniqueIdentifier+' a').hide();
          
			onUpload = false;
        });
		r2.on('fileError', function(file, message){
			// Reflect that the file upload has resulted in error
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-name').html(file.fileName + ' (file could not be uploaded: '+message+')');
          
			$('#rsmAddPhotosProgress, #rsmAddPhotosUl').hide();
			// Show pause, hide resume
			$('#rsmAddPhotosProgress .progress-resume-link').hide();
			$('#rsmAddPhotosProgress .progress-pause-link').hide();
			$('#rsmAddPhotosProgress .progress-cancel-link').hide();
			onUpload = false;
		});
		r2.on('fileProgress', function(file){
			// Handle progress for both the file and the overall upload
			$('.resumable-file-'+file.uniqueIdentifier+' .resumable-file-progress').html(Math.floor(file.progress()*100) + '%');
			$('#rsmAddPhotosProgress .progress-bar').css({width:Math.floor(r2.progress()*100) + '%'});
        });
    }

}); 
