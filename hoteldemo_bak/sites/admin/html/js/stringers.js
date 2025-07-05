Ext.apply(Ext.util.Format, {
	numberFormat: {
		decimalSeparator: '.',
		decimalPrecision: 2,
		groupingSeparator: ',',
		groupingSize: 3,
		currencySymbol: '$'
	},
	formatNumber: function(value, numberFormat) {
		var format = Ext.apply(Ext.apply({}, Ext.util.Format.numberFormat), numberFormat);
		if (typeof value !== 'number') {
			value = String(value);
			if (format.currencySymbol) {
				value = value.replace(format.currencySymbol, '');
			}
			if (format.groupingSeparator) {
				value = value.replace(new RegExp(format.groupingSeparator, 'g'), '');
			}
			if (format.decimalSeparator !== '.') {
				value = value.replace(format.decimalSeparator, '.');
			}
			value = parseFloat(value);
		}
		var neg = value < 0;
		value = Math.abs(value).toFixed(format.decimalPrecision);
		var i = value.indexOf('.');
		if (i >= 0) {
			if (format.decimalSeparator !== '.') {
				value = value.slice(0, i) + format.decimalSeparator + value.slice(i + 1);
			}
		} else {
			i = value.length;
		}
		if (format.groupingSeparator) {
			while (i > format.groupingSize) {
				i -= format.groupingSize;
				value = value.slice(0, i) + format.groupingSeparator + value.slice(i);
			}
		}
		if (format.currencySymbol) {
			value = format.currencySymbol + value;
		}
		if (neg) {
			value = '-' + value;
		}
		return value;
	}
});

Ext.onReady(function() {
	Ext.Ajax.timeout = 90000;
	
	Ext.QuickTips.init();
	var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Updating, please wait..."});
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
	
	var Stringers = Ext.data.Record.create([
		{name: 'stringer_user_id'},
		{name: 'site_id'},
		{name: 'stringer_username'},
		{name: 'stringer_password'},
		{name: 'stringer_fullname'},
		{name: 'created_date'},
		{name: 'staff_code'},
		{name: 'original_qty'},
		{name: 'original_sales'},
		{name: 'total_qty'},
		{name: 'total_sales'},
		{name: 'commission_percentage'},
		{name: 'this_month_sales'},
		{name: 'this_month_qty'},
		{name: 'this_month_profit'}
	]);
	
	// Stringers :: Data Store
	var stringersStore = new Ext.data.Store({
		url     : '/admin/gallery/getstringers',
		autoLoad: false,
		reader  : new Ext.data.JsonReader( {
			root: "data"
		}, Stringers)
	});
	
	var stringersCheckbox = new Ext.grid.CheckboxSelectionModel();
	
	// Stringers :: Column Model
	var stringersColModel = new Ext.grid.ColumnModel([
		{id:'stringer_user_id'    , header:"Stringer ID"	, width:70 , sortable:true, locked:true, dataIndex:'stringer_user_id'},
		{id:'stringer_fullname'  , header:"Stringer/User Name" , width:150, sortable:true, locked:true, dataIndex:'stringer_fullname'},
		{id:'stringer_username'  , header:"Username" , width:120, sortable:true, locked:true, dataIndex:'stringer_username'},
		{id:'stringer_password'  , header:"Password" , width:90 , sortable:true, locked:true, dataIndex:'stringer_password'},
		{id:'staff_code' 		 , header:"Smugmug Code" , width:90 , sortable:true, locked:true, dataIndex:'staff_code'},
		{id:'commission_percentage'  , header:"% Commission" , width:100 , sortable:true, locked:true, dataIndex:'commission_percentage' , renderer:function(val){ return val + '%'; }},
		{id:'total_qty'  		, header:"Sales Qty" , width:95 , sortable:true, locked:true, dataIndex:'total_qty'},
		{id:'total_sales'  		, header:"Total Sales" , width:95 , sortable:true, locked:true, dataIndex:'total_sales', renderer:function(val) { if(val) return Ext.util.Format.formatNumber(val, {currencySymbol:''}); else return 0; }},
		{id:'this_month_qty'  		, header:"This Month Qty" , width:110 , sortable:true, locked:true, dataIndex:'this_month_qty'},
		{id:'this_month_sales'  		, header:"This Month Sales" , width:110 , sortable:true, locked:true, dataIndex:'this_month_sales', renderer:function(val) { if(val) return Ext.util.Format.formatNumber(val, {currencySymbol:''}); else return 0; }},
		{id:'this_month_profit'  		, header:"This Month Profit" , width:110 , sortable:true, locked:true, dataIndex:'this_month_profit', renderer:function(val) { if(val) return Ext.util.Format.formatNumber(val, {currencySymbol:''}); else return 0; }}
	]);	
	
	var dtpStartDate = new Ext.form.DateField({id:"start_date",name:'start_date',allowBlank:false, value:orig_start_date, format:'Y-m-d'});
	var dtpEndDate = new Ext.form.DateField({id:"end_date",name:'end_date',allowBlank:false, value:orig_end_date, format:'Y-m-d'});
	
	var salesGrid = new Ext.grid.GridPanel({
		store:new Ext.data.JsonStore({
			id:'salestoreid',
			root:'rows',
			totalProperty:'totalCount',
			url:'/admin/gallery/getsales',
			/*baseParams:{start_date:Ext.getCmp('start_date').getValue(), end_date:Ext.getCmp('end_date').getValue(), stringer_user_id: Ext.getCmp('stringer_user_id').getValue() },*/
			fields:[
				{name:'order_id'},
				{name:'order_date',type:'date', dateFormat: 'Y-m-d H:i:s'},
				{name:'quantity'},
				{name:'currency'},
				{name:'base_price'},
				{name:'price_charged'},
				{name:'profit'},
				{name:'charges'},
				{name:'sales_tax'},
				{name:'shipping_cost'},
				{name:'smugmug_name'},
				{name:'payment_status'},
				{name:'file_name'},
				{name:'gallery_title'},
				{name:'commission_percentage'}
			]
		}),
		columns:[
			{dataIndex:'order_id', header:'Order ID', width:50},
			{dataIndex:'order_date', header:'Order Date', width:60, renderer: Ext.util.Format.dateRenderer('m/d/Y h:ma')},
			{dataIndex:'quantity', header:'Quantity', width:50},
			{dataIndex:'currency', header:'Currency', width:50},
			{dataIndex:'base_price', header:'Base Price', width:50},
			{dataIndex:'price_charged', header:'Price Charged', width:50},
			{dataIndex:'profit', header:'Profit', width:50},
			{dataIndex:'charges', header:'Charges', width:50},
			{dataIndex:'sales_tax', header:'Tax', width:50},
			{dataIndex:'shipping_cost', header:'Shipping Cost', width:50},
			{dataIndex:'smugmug_name', header:'Name', width:100},
			{dataIndex:'payment_status', header:'Payment Status', width:50},
			{dataIndex:'file_name', header:'FileName', width:70},
			{dataIndex:'gallery_title', header:'Gallery', width:100},
			{dataIndex:'commission_percentage', header:'% Commission', width:50}
		],
		viewConfig:{forceFit:true, scrollOffset:0},
		frame:false,
		border:false,
		tbar:[
			'Sales Period:',
			dtpStartDate,'-',
			dtpEndDate, ' ',
			{xtype:'button',text:'View',handler:function() {
				salesGrid.store.load();
			}}
		]	
	});
	
	var winSales = new Ext.Window({
		width:980,
		height:500,
		id:'sales-det-win',
		layout:'fit',
		border:false,
		autoScroll:true,
		title:'Sales Detail',
		closeAction: 'hide',
		items:[salesGrid]
	});
	
	var exportForm = new Ext.FormPanel({
		monitorValid:true,
		buttonAlign:'center',
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		frame:false,
		border:false,
		autoHeight:true,
        items:[
			new Ext.form.DateField({name:'start_date',id:'expstart_date',maxValue:maxDate,value:orig_start_date, format:'Y-m-d', width:150, fieldLabel:'Start Period', allowBlank:false}),
			new Ext.form.DateField({name:'end_date',id:'expend_date', maxValue:maxDate,value:maxDate, format:'Y-m-d', width:150, fieldLabel:'End Period', allowBlank:false})
        ],
        buttons:[
			{
                text:'Export',
				handler:function(){
					var start_date = Ext.getCmp('expstart_date').getRawValue();
					var end_date = Ext.getCmp('expend_date').getRawValue();
					window.open('/admin/gallery/exportsales/start_date/' + start_date + '/end_date/' + end_date, 'onlinecclogs', 'width=800,height=600,scrollbars=yes, toolbars=no, resizable=yes');
                }
			},
			{
                text:'Close',
				handler:function(){
				    winExport.hide();
                }
			}
		]
	});
	
	var winExport = new Ext.Window({
		width:300,
		height:140,
		id:'sales-export-win',
		layout:'fit',
		frame:true,
		border:false,
		autoScroll:true,
		title:'Sales Detail Export',
		closeAction: 'hide',
		items:[exportForm]
	});
	
	var exportForm1 = new Ext.FormPanel({
		monitorValid:true,
		buttonAlign:'center',
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		frame:false,
		border:false,
		autoHeight:true,
        items:[
			new Ext.form.DateField({name:'start_date',id:'expstart_date1',maxValue:maxDate,value:orig_start_date, format:'Y-m-d', width:150, fieldLabel:'Start Period', allowBlank:false}),
			new Ext.form.DateField({name:'end_date',id:'expend_date1', maxValue:maxDate,value:maxDate, format:'Y-m-d', width:150, fieldLabel:'End Period', allowBlank:false})
        ],
        buttons:[
			{
                text:'Export',
				handler:function(){
					var start_date = Ext.getCmp('expstart_date1').getRawValue();
					var end_date = Ext.getCmp('expend_date1').getRawValue();
					window.open('/admin/gallery/exportsummarysales/start_date/' + start_date + '/end_date/' + end_date, 'summarysales', 'width=800,height=600,scrollbars=yes, toolbars=no, resizable=yes');
                }
			},
			{
                text:'Close',
				handler:function(){
				    winExport1.hide();
                }
			}
		]
	});
	
	var winExport1 = new Ext.Window({
		width:300,
		height:140,
		id:'sales-export-win1',
		layout:'fit',
		frame:true,
		border:false,
		autoScroll:true,
		title:'Sales Summary Export',
		closeAction: 'hide',
		items:[exportForm1]
	});
	
	var stringersGrid = new Ext.grid.GridPanel({ 
		store		: stringersStore,
		colModel	: stringersColModel,
		selModel    : stringersCheckbox,
		height      : 410,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
	    tbar: [
	 		{
				text   : 'Add New Stringer',
				tooltip: 'Add a new stringer/user',
				iconCls: 'add',
				hidden :modReadOnly,
				handler: function() {
	            	/*document.getElementById('editStringerRender').style.visibility = 'hidden';
					document.getElementById('addStringerRender').style.visibility = 'visible';*/
					Ext.getCmp('view_sales').hide();
					editStringerWin.show();
				}
			},{
				text   : 'Delete Stringers',
				tooltip: 'Delete selected stringers',
				iconCls: 'delete',
				hidden :modReadOnly,
				handler: function() {
	            	/*document.getElementById('addStringerRender').style.visibility = 'hidden';
	            	document.getElementById('editStringerRender').style.visibility = 'hidden';*/
					
	            	
					Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete these stringers?', function(btn) {
	            		if ( btn == 'yes' ) {
	            			var selection = stringersGrid.getSelectionModel().getSelections();
							var countRows = stringersGrid.getSelectionModel().getCount();	
							var store = stringersGrid.getStore();											
							if(countRows > 0){	
								var strings = '';
								for(i = 0 ;i < countRows;i++){										
									strings += selection[i].get('stringer_user_id').toString()+',';	
									store.remove(selection[i]);																																		
								}															
								strings = strings.substring(0,strings.length-1);
								SendItViaAjax(strings, '/admin/gallery/deletestringers');									
							}
							stringersStore.reload();
	            		}
	            	});
				}
			},'-',{
				text   : 'Refresh Last Month Sales',
				tooltip: 'Refresh Last Month Sales',
				iconCls: 'refresh',
				hidden :modReadOnly,
				handler: function() {
					loadMask.show();
					Ext.Ajax.request({
				        method: 'GET',
				        url: '/admin/gallery/updatestringerlastmonthsales',
				        success: function( result, request ){
				        	stringersStore.reload();
				            loadMask.hide();
				        }
				    });
				}
			},{
				text   : 'Refresh This Month Sales',
				tooltip: 'Refresh This Month Sales',
				iconCls: 'refresh',
				hidden :modReadOnly,
				handler: function() {
					loadMask.show();
					Ext.Ajax.request({
				        method: 'GET',
				        url: '/admin/gallery/updatestringermonthlysales',
				        success: function( result, request ){
				        	stringersStore.reload();
				            loadMask.hide();
				        }
				    });
				}
			},'-', {
				text   : 'Export Summary Sales',
				tooltip: 'Export Summary Sales',
				iconCls: 'export',
				hidden :modReadOnly,
				handler: function() {
					winExport1.show();
				}
			}, '-', {
				text   : 'Export Detail Sales',
				tooltip: 'Export Detail Sales',
				iconCls: 'export',
				hidden :modReadOnly,
				handler: function() {
					winExport.show();
				}
			}, '-', {
				text   : 'Export Stringers',
				tooltip: 'Export Stringers/Users',
				iconCls: 'export',
				hidden :modReadOnly,
				handler: function() {
					window.open('/admin/gallery/exportstringers', 'exportstringers', 'width=800,height=600,scrollbars=yes, toolbars=no, resizable=yes');
				}
			}
		]
	});
	
	
	var stringersForm = new Ext.FormPanel({
		id        : 'stringers-panel',
		method    : 'POST',
		url       : '/admin/gallery/setstringer',
		title	  : 'Stringers',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'stringersRender',
		layout    : 'column',					// Specifies that the items will now be arranged in columns
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [
			stringersGrid
		]
	});

	/*** ADD AND EDIT SECTION ***/
	
	/*var addStringerForm = new Ext.FormPanel({
		id         : 'add-stringer-form',
		title	   : 'Add Stringer',
		method     : 'POST',
		url        : '/admin/gallery/addstringer',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		renderTo   : 'addStringerRender',
		width	   : 400,
		labelWidth : 130,
		items: [{
			fieldLabel: 'Stringer Name',
			name: 'stringer_fullname',
			id: 'stringer_fullname1',
			width: 195
		},{
			fieldLabel: 'Username',
			name: 'stringer_username',
			id: 'stringer_username1',
			width: 195,
			maskRe: /^[0-9a-z\.\_]+$/
		},{
			fieldLabel: 'Password',
			name: 'stringer_password',
			width: 195
		},{
			fieldLabel: 'User Smugmug Code',
			name: 'staff_code',
			id: 'staff_code1',
			width: 195,
			maxLength: 4
		},{
			fieldLabel: 'Origin Qty',
			name: 'original_qty',
			id: 'original_qty1',
			width: 195,
			maskRe: /^[0-9]+$/
		},{
			fieldLabel: 'Origin Sales',
			name: 'original_sales',
			id: 'original_sales1',
			width: 195,
			maskRe: /^[0-9\.]+$/
		},{
			fieldLabel: 'Total Qty',
			name: 'total_qty',
			id: 'total_qty1',
			width: 195,
			maskRe: /^[0-9]+$/
		},{
			fieldLabel: 'Total Sales',
			name: 'total_sales',
			id: 'total_sales1',
			width: 195,
			maskRe: /^[0-9\.]+$/
		},{
			fieldLabel: '% Commision',
			name: 'commission_percentage',
			id: 'commission_percentage1',
			width: 195,
			maskRe: /^[0-9]+$/
		}],	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Add',
				hidden :modReadOnly,
				handler:function() {						
					addStringerForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, addStringerResp){	
							document.getElementById('addStringerRender').style.visibility = 'hidden';		
							stringersStore.load();	
							addStringerForm.getForm().reset();
						},
						failure: function(login, addStringerResp){
							var resp = addStringerResp.response.responseText;
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
					document.getElementById('addStringerRender').style.visibility = 'hidden';
					addStringerForm.getForm().reset();
				}
			}
		]
	});*/
	
	var editStringerForm = new Ext.FormPanel({
		id         : 'edit-stringer-form',
		//title	   : 'Edit Stringer',
		method     : 'POST',
		//url        : '/admin/gallery/setstringerbyid',
		frame      : true,
		labelAlign : 'left',
		bodyStyle  : 'padding:5px;',
		defaultType: 'textfield',
		//renderTo   : 'editStringerRender',
		//width	   : 400,
		labelWidth : 130,
		items: [{
			xtype: 'hidden',
			id   : 'stringer_user_id',
			name : 'stringer_user_id'
		 },{
			fieldLabel: 'Stringer Name',
			name: 'stringer_fullname',
			id: 'stringer_fullname2',
			width: 195
		},{
			fieldLabel: 'Username',
			name: 'stringer_username',
			id: 'stringer_username2',
			width: 195,
			maskRe: /^[0-9a-z\.\_]+$/
		},{
			fieldLabel: 'Password',
			name: 'stringer_password',
			width: 195
		},{
			fieldLabel: 'User Smugmug Code',
			name: 'staff_code',
			id: 'staff_code2',
			width: 195,
			maxLength: 4
		},{
			fieldLabel: 'Origin Qty',
			name: 'original_qty',
			id: 'original_qty2',
			width: 195,
			maskRe: /^[0-9]+$/
		},{
			fieldLabel: 'Origin Sales',
			name: 'original_sales',
			id: 'original_sales2',
			width: 195,
			maskRe: /^[0-9\.]+$/
		},{
			fieldLabel: 'Total Qty',
			name: 'total_qty',
			id: 'total_qty2',
			width: 195,
			maskRe: /^[0-9]+$/
		},{
			fieldLabel: 'Total Sales',
			name: 'total_sales',
			id: 'total_sales2',
			width: 195,
			maskRe: /^[0-9\.]+$/
		},{
			fieldLabel: '% Commision',
			name: 'commission_percentage',
			id: 'commission_percentage2',
			width: 195,
			maskRe: /^[0-9]+$/
		}]/*,	
		buttonAlign: 'center',	 
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {						
					editStringerForm.getForm().submit({
						waitTitle: 'Connecting to the database...',
						waitMsg: 'Please Wait...',
						success: function(login, editStringerResp){	
							document.getElementById('editStringerRender').style.visibility = 'hidden';		
							stringersStore.load();					
						},
						failure: function(login, addStringerResp){
							var resp = addStringerResp.response.responseText;
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
				text:'View Sales',
				hidden :modReadOnly,
				handler:function() {
					winSales.show();
					salesGrid.store.load();
				}
			},{
				text:'Close',
				handler:function() {
					document.getElementById('editStringerRender').style.visibility = 'hidden';
				}
			}
		]*/
	});
	
	var editStringerWin = new Ext.Window({
		layout:'fit',		
		title: 'Stringer',
		width:400,
		height:330,
		closeAction:'hide',
		plain: true,		
		items: editStringerForm,
		buttonAlign: 'center',
		buttons:[{
				text:'Save',
				hidden :modReadOnly,
				handler:function() {
					if(Ext.getCmp('stringer_user_id').value > 0) {
						editStringerForm.getForm().submit({
							url:'/admin/gallery/setstringerbyid',
							waitTitle: 'Connecting to the database...',
							waitMsg: 'Please Wait...',
							success: function(login, editStringerResp){		
								editStringerForm.getForm().reset();	
								editStringerWin.hide();
								stringersStore.load();					
							},
							failure: function(login, editStringerResp){
								var resp = editStringerResp.response.responseText;
								var obj = Ext.util.JSON.decode(resp);
								Ext.Msg.show({
								   title:'Error',
								   msg: obj.msg,
								   buttons: Ext.Msg.OK,
								   icon: Ext.MessageBox.ERROR
								});
							}
						});
					}
					else {
						editStringerForm.getForm().submit({
							url:'/admin/gallery/addstringer',
							waitTitle: 'Connecting to the database...',
							waitMsg: 'Please Wait...',
							success: function(login, editStringerResp){		
								editStringerWin.hide();
								stringersStore.load();	
								editStringerForm.getForm().reset();				
							},
							failure: function(login, addStringerResp){
								var resp = addStringerResp.response.responseText;
								var obj = Ext.util.JSON.decode(resp);
								Ext.Msg.show({
								   title:'Error',
								   msg: obj.msg,
								   buttons: Ext.Msg.OK,
								   icon: Ext.MessageBox.ERROR
								});
							}
						});
					}
				}
			},{
				text:'View Sales',
				id: 'view_sales',
				hidden :modReadOnly,
				handler:function() {
					winSales.show();
					salesGrid.store.load();
				}
			},{
				text:'Close',
				hidden :modReadOnly,
				handler:function() {
					editStringerForm.getForm().reset();
					editStringerWin.hide();
				}
			}
		]
	});


	stringersGrid.on('rowdblclick', function(gridPanel, rowIndex, e) {
		var stringer_id = gridPanel.getStore().getAt(rowIndex).get('stringer_user_id');
		Ext.getCmp('view_sales').show();
		editStringerWin.show();
		editStringerForm.form.load({
			url:'/admin/gallery/getstringerbyid',
		    method:'GET',
		    params:{ stringer_user_id:stringer_id },
		    waitmsg: 'Loading...',
		    success: function(login, editStringerResp){	
            	/*document.getElementById('addStringerRender').style.visibility = 'hidden';
				document.getElementById('editStringerRender').style.visibility = 'visible';*/				
			}
		});				
	});

	/*Ext.getCmp('stringer_fullname1').on('change', function(txt, newVal, oldVal) {
		newVal = newVal.replace(/[^0-9a-z\.\_]+$/g, '');
		newVal = newVal.toLowerCase();
		newVal = newVal.replace(/ /g, '.');
		var curVal = Ext.getCmp('stringer_username1').getValue();
		if(curVal=='') Ext.getCmp('stringer_username1').setValue(newVal);
	});*/
	
	Ext.getCmp('stringer_fullname2').on('change', function(txt, newVal, oldVal) {
		newVal = newVal.replace(/[^0-9a-z\.\_]+$/g, '');
		newVal = newVal.toLowerCase();
		newVal = newVal.replace(/ /g, '.');
		var curVal = Ext.getCmp('stringer_username2').getValue();
		if(curVal=='') Ext.getCmp('stringer_username2').setValue(newVal);
	});
	
	stringersStore.load();
	
	salesGrid.store.on ('beforeload', function (ds, e){
		ds.baseParams.start_date = Ext.getCmp('start_date').getRawValue();
		ds.baseParams.end_date = Ext.getCmp('end_date').getRawValue();
		ds.baseParams.stringer_user_id = Ext.getCmp('stringer_user_id').getValue();
	});
});