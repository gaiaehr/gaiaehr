Ext.define('App.ux.grid.Button', {
	extend: 'Ext.grid.column.Column',
	alias: ['widget.gridbutton'],
	header: '&#160;',
	sortable: false,
	context: {},
	applicationProperties: {
		/*
		 * this is where the icons will be picked up.
		 */
		iconStore: ''
	},
	constructor: function(config){
		var me = this, cfg, items,
			i, item;
		cfg = Ext.apply({}, config);
		items = cfg.items || [me];
		me.callParent(arguments);
		me.items = items;
		me.context = cfg.scope;
		/*
		 * create a render for this special button.
		 */
		me.renderer = function(v, meta, rec, rowIndex, colIndex, store, view){
			/*
			 * iterate each item creating a div holder for each button
			 */
			Ext.Array.each(items, function(anItem){
				var anId;
				item = anItem;
				anId = Ext.id(); //generate an ID for multiple buttons
				/*
				 * simply defer instead of callback ensuring the basic div for the button is executed & previous call has completed.
				 */
				Ext.Function.defer(me.addButton, 100, me, [anId, rec, item, me.context]);
				/*
				 * create a place holder for the button.
				 */
				v ? v += '<div id="' + anId + '">&#160;</div>' : v = '<div id="' + anId + '">&#160;</div>';
			});
			return v;
		};
	},
	/*
	 * simple function when no hanlder click events are passed in.
	 */
	noHandler: function(){
		Ext.Msg.alert("Oops", "No Handler set up");
	},

	addButton: function(id, record, theItem, context){
		var me = this, target = Ext.get(id), btn, handler,
			menuItems, functionHandler, buttonConfig, menuIcon;

		if(target){
			if(theItem.menu){ //handle the split button
				menuItems = [];
				Ext.Array.each(theItem.menu, function(aMenuItem){
					aMenuItem.handler ? functionHandler = Ext.bind(aMenuItem.handler, me, [record, context]) : functionHandler = me.noHandler;
					aMenuItem.icon ? menuIcon = me.applicationProperties.iconStore + aMenuItem.icon : menuIcon = undefined;
					var newMenu = { text: aMenuItem.text, handler: functionHandler, icon: menuIcon};
					menuItems.push(newMenu);
				});
				buttonConfig = {
					text: theItem.text,
					icon: theItem.icon ? me.applicationProperties.iconStore + theItem.icon : "",
					menu: menuItems,
					renderTo: target.parent()
				};
			} //handle a standard button
			else{
				theItem.handler ? functionHandler = Ext.bind(theItem.handler, me, [record, context]) : functionHandler = me.noHandler;
				buttonConfig = {
					text: theItem.text,
					tooltip: theItem.tooltip,
					icon: theItem.icon ? me.applicationProperties.iconStore + theItem.icon : "",
					handler: functionHandler,
					cls: theItem.cls || null,
					listeners: theItem.listeners || null,
					record: record,
					width: theItem.width || 50,
					margin: theItem.margin || 0,
					renderTo: target.parent()
				};
			}
			btn = Ext.create("Ext.button.Button", buttonConfig);
			/*
			 * clean up the DIV
			 */
			Ext.get(id).remove();
		}
	},

	destroy: function(){
		delete this.items;
		delete this.renderer;
		this.callParent(arguments);
	},

	cascade: function(fn, scope){
		fn.call(scope || this, this);
	},

	getRefItems: function(){
		return [];
	}
});