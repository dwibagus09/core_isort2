Ext.onReady(function() {
	Ext.QuickTips.init();


	// The toolbar
	var tb = new Ext.Toolbar('menu');
	
	tb.add({
		text:'Content',
		menu: new Ext.menu.Menu({id:'mnuContent',items: [
			new Ext.menu.Item({
				text:'Manage Gallery & Images',
				handler: function(){ menuRedirect("/admin/content/contentgallery"); }
			})
		]})
	},'-',{
		text:'Sites',
		menu: new Ext.menu.Menu({id:'mnuSite',items: [
			new Ext.menu.Item({
				text:'Site Config',
				handler: function(){ menuRedirect("/admin/config/manage"); }
			})
		]})
	},'-',{
		text:'Users',
		menu: new Ext.menu.Menu({id:'mnuUser',items: [
			new Ext.menu.Item({
				text:'Manage Admin Users',
				handler: function(){ menuRedirect("/admin/user/admin"); }
			}),
			new Ext.menu.Item({
				text:'Migrate Admin Users',
				handler: function(){ menuRedirect("/admin/user/migrateadminusers"); }
			}),
			new Ext.menu.Item({
				text:'Manage User Notification',
				handler: function(){ menuRedirect("/admin/user/managenotification"); }
			}),
			new Ext.menu.Item({
				text:'Migrate User Notification',
				handler: function(){ menuRedirect("/admin/user/migrateusernotification"); }
			}),
			new Ext.menu.Item({
				text:'View User Logs',
				handler: function(){ menuRedirect("/admin/user/log"); }
			})
		]})
	},'-',{
		text:'Gallery Assignment',
		menu: new Ext.menu.Menu({id:'mnuUser',items: [
			new Ext.menu.Item({
				text:'Manage Users/Stringers',
				handler: function(){ menuRedirect("/admin/gallery/user"); }
			}),
			new Ext.menu.Item({
				text:'Migrate Users/Stringers',
				handler: function(){ menuRedirect("/admin/gallery/migrateuser"); }
			}),
			new Ext.menu.Item({
				text:'Manage Assignments',
				handler: function(){ menuRedirect("/admin/gallery/assignment"); }
			})
		]})
	});
});

function menuRedirect(goToURL) {
	window.location = goToURL;
}