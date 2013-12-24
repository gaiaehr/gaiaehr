Ext.define('App.controller.Navigation', {
    extend: 'Ext.app.Controller',
	requires:[
		'Ext.util.History'
	],
	refs: [
        {
            ref:'viewport',
            selector:'viewport'
        },
        {
            ref:'mainNav',
            selector:'treepanel[action=mainNav]'
        }
	],

	init: function() {
		var me = this;

		Ext.util.History.init();
		Ext.util.History.on('change', me.urlChange, me);

		me.control({
			'treepanel[action=mainNav]':{
				selectionchange: me.onNavigationNodeSelected,
				beforerender: me.onNavigationBeforeRender
			}
		});

	},

	onNavigationBeforeRender:function(treepanel){
		treepanel.getStore().on('load', this.afterNavigationLoad, this);
	},

	navigateToDefault: function(){
		this.navigateTo('App.view.dashboard.Dashboard');
	},

	navigateTo: function(cls, callback){
		var tree = this.getMainNav(),
			treeStore = tree.getStore(),
			sm = tree.getSelectionModel(),
			node = treeStore.getNodeById(cls);

		sm.select(node);
		if(typeof callback == 'function') callback(true);
	},

	onNavigationNodeSelected: function(model, selected){
		if(0 < selected.length){
			if(selected[0].data.leaf){
				window.location = './#!/' + selected[0].data.id;
			}
		}
	},

	/**
	 * this logic can be move to here eventually...
	 */
	afterNavigationLoad: function(){
		app.fullMode ? app.navColumn.expand() : app.navColumn.collapse();
		app.removeAppMask();

		this.navigateToDefault();
	},

	/**
	 * this method handle the card layout when the URL changes
	 * @param url
	 */
	urlChange:function(url){
		var me = this,
			cls = url.replace(/!\//, ''),
			ref = me.getNavRefByClass(cls),
			layout = me.getViewport().MainPanel.getLayout();

		if (typeof me[ref] == 'undefined') {
			app.MainPanel.el.mask('Loading...');
			Ext.Function.defer(function() {
				me[ref] = me.getViewport().MainPanel.add(Ext.create(cls));
				me[ref].onActive(function(success){
					me.getViewport().MainPanel.el.unmask();
					if(success)	layout.setActiveItem(me[ref]);
				});
			}, 100);

		} else {
			if (me[ref].isDestroyed) me[ref].render();
			me[ref].onActive(function(success){
				me.getViewport().MainPanel.el.unmask();
				if(success) layout.setActiveItem(me[ref]);
			});
		}
	},

	/**
	 * this method acts as pressing the browser back btn
	 */
	goBack: function(){
		Ext.util.History.back();
	},

	/**
	 * this method gets the instance reference of a main panel class
	 * @param cls
	 * @returns {*}
	 */
	getPanelByCls:function(cls){
		var me = this,
			ref = me.getNavRefByClass(cls);
		if (typeof me[ref] == 'undefined') {
			return me[ref] = me.getViewport().MainPanel.add(Ext.create(cls));
		}else{
			return me[ref];
		}
	},

	/**
	 * this method gets the reference string of the class string App.view.Panel => App_view_Panel
	 * @param cls
	 * @returns {XML|Ext.dom.AbstractElement|Object|Ext.dom.Element|string|*}
	 */
	getNavRefByClass: function(cls) {
		return  cls.replace(/\./g, '_');
	},

	/**
	 * this method gets the class string of a reference string App_view_Panel => App.view.Panel
	 * @param ref
	 * @returns {XML|Ext.dom.AbstractElement|Object|Ext.dom.Element|string|*}
	 */
	getClassByNavRef: function(ref) {
		return ref.replace(/_/g, '.');
	}

});