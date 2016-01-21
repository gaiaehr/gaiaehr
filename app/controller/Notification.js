Ext.define('App.controller.Notification', {
	extend: 'Ext.app.Controller',
	requires: [
		'App.view.notifications.Grid'
	],
	refs: [
		{
			ref: 'UserSplitButton',
			selector: '#userSplitButton'
		},
		{
			ref: 'NotificationsGrid',
			selector: 'notificationsgrid'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'viewport': {
				beforerender: me.onViewportBeforeRender
			},
			'#userSplitButton': {
				badgeclick: me.onUserSplitButtonBadgeClick
			},
			'notificationsgrid': {
				itemclick: me.onNotificationsGridItemClick
			},
			'notificationsgrid > header': {
				click: me.onNotificationsGridHeaderClick
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			fields: [
				{
					name: 'id',
					type: 'string'
				},
				{
					name: 'description',
					type: 'string'
				},
				{
					name: 'data',
					type: 'auto'
				},
				{
					name: 'controller',
					type: 'string'
				},
				{
					name: 'method',
					type: 'string'
				}
			]

		});

		me.store.on('add', me.onNotificationAdd, me);
		me.store.on('remove', me.onNotificationRemove, me);

	},

	onNotificationAdd:function(store, records){
		this.setBadgeText(store.count());
	},

	onNotificationRemove:function(store, record){
		this.setBadgeText(store.count());
	},

	onNotificationsGridItemClick: function(grid, record){
		var me = this,
			controller = App.app.getController(record.data.controller),
			method = record.data.method,
			data = record.data.data;

		if(typeof controller == 'object' && typeof controller[method] == 'function'){

			if(data){
				controller[method](data, function(success){
					if(success){
						me.store.remove(record);
					}
					me.doHideNotifications();
				});
			}else{
				controller[method](function(success){
					if(success){
						me.store.remove(record);
					}
					me.doHideNotifications();
				});
			}

		}else{
			me.store.remove(record);
			me.doHideNotifications();
			app.msg(_('oops'), _('notification_handler_error'), true);
		}

	},

	onNotificationsGridHeaderClick: function(){
		this.doHideNotifications();
	},

	onUserSplitButtonBadgeClick: function(btn, text){
		this.doShowNotifications();
	},

	onViewportBeforeRender: function(viewport){
		var me = this;

		me.grid = viewport.add(Ext.widget('notificationsgrid',{
			floatable: true,
			floating: true,
			store: me.store
		}));
	},



	setBadgeText: function(text){
		this.getUserSplitButton().setBadgeText(text);
	},

	getBadgeText: function(){
		this.getUserSplitButton().getBadgeText();
	},

	doHideNotifications: function(){
		this.getNotificationsGrid().collapse(Ext.Component.DIRECTION_TOP, true);
		this.getNotificationsGrid().hide();
		Ext.getBody().un('click', this.doHideNotifications, this);
	},

	doShowNotifications: function(){
		this.grid.showBy(this.getUserSplitButton(), 'tr-br', [-1, 4]);
		this.grid.expand(true);
		this.grid.toFront();
		Ext.getBody().on('click', this.doHideNotifications, this);
	},

	add: function(id, description, data, controller, method){
		var record;
		if(this.store.getById(id)){
			record = this.store.getById(id);
			record.set({
				description: description,
				data: data,
				controller: controller,
				method: method
			});
			record.commit();
			return record;
		}

		record = this.store.add({
			id: id,
			description: description,
			data: data,
			controller: controller,
			method: method
		})[0];

		app.msg(_('new_notification'), description, 'yellow');

		return record;
	},

	remove: function(id){
		if(!this.store.getById(id)) return;
		this.store.remove(this.store.getById(id));
	},

	testNotification:function(data, callback){
		if(typeof callback == 'function'){
			callback(true);
		}
	}

});
