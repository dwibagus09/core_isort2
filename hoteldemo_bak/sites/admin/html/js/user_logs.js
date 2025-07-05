Ext.onReady(function() {
	
	Ext.QuickTips.init();
	
	var Logs = Ext.data.Record.create([
		{name: 'log_id'},
		{name: 'site_id'},
		{name: 'user_id'},
		{name: 'adminusername'},
		{name: 'log_date'},
		{name: 'description'},
		{name: 'from_ip'}
	]);
	
	var connObjEvents = new Ext.data.Connection({
	    timeout : 120000,
	    url : '/admin/user/getlogs',
	    method : 'GET'
	});
	
	// Logs :: Data Store
	var logStore = new Ext.data.Store({
		autoLoad: false,
		proxy : new Ext.data.HttpProxy(connObjEvents),
		reader  : new Ext.data.JsonReader( {
			root: "data",					// The property which contains an Array of row objects
			totalProperty: 'total'
		}, Logs)
	});
	
	//Logs :: Column Model
	var logColModel = new Ext.grid.ColumnModel([
		{id:'log_date'     		, header:"Log Timestamp"      , width:120 , sortable:true, locked:true, dataIndex:'log_date'},
		{id:'username'     		, header:"Username"		, width:120 , sortable:true, locked:true, dataIndex:'adminusername'},
		{id:'description'     	, header:"Description"   , width:400 , sortable:true, locked:true, dataIndex:'description'},
		{id:'from_ip'     		, header:"From IP"   , width:110 , sortable:true, locked:true, dataIndex:'from_ip'}
	]);	
	
	var pagingToolbar = new Ext.PagingToolbar({
        pageSize: 16,
        displayInfo: true,
        displayMsg: 'Total Records {0} - {1} of {2}',
        store: logStore,
       	emptyMsg: "No log to display"        
    });
	
	var logGrid = new Ext.grid.GridPanel({ 
		store		: logStore,
		colModel	: logColModel,
		height      : 413,
		width		: "100%",
		border      : true,
		stripeRows  : true,
	    loadMask	: true,
		bbar		: pagingToolbar
	});
	
	var logsForm = new Ext.FormPanel({
		id        : 'logs-panel',
		method    : 'POST',
		title	  : 'User Logs',
		frame     : true,
		labelAlign: 'left',
		renderTo  : 'logsRender',
		layout    : 'fit',
		hideMode  :'offsets',
		defaults  :{hideMode:'offsets'},
		items	  : [
			logGrid
		]
	});
	
	logStore.load({params:{start:0, limit:16}}); 

}); 
