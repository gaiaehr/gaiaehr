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
            ref:'mainNavPanel',
            selector:'panel[action=mainNavPanel]'
        },
        {
            ref:'mainNav',
            selector:'treepanel[action=mainNav]'
        },
        {
            ref:'patientPoolArea',
            selector:'panel[action=patientPoolArea]'
        },
        {
            ref:'appFooter',
            selector:'container[action=appFooter]'
        },
        {
            ref:'appFooterDataView',
            selector:'container[action=appFooter] > dataview'
        }
	],

	navKey: 'ALT',
	enableNavKeys: false,

	init: function() {
		var me = this;

		me.navKey = this.setNavKey(this.navKey);

		me.activePanel = null;
		me.altIsDown = false;

		Ext.util.History.init();
		Ext.util.History.on('change', me.urlChange, me);

		if(me.enableNavKeys) me.initFunctionKeyNav();

		me.control({
			'viewport':{
				patientset: me.onPatientSet,
				patientunset: me.onPatientUnset
			},
			'treepanel[action=mainNav]':{
				selectionchange: me.onNavigationNodeSelected,
				beforerender: me.onNavigationBeforeRender
			},
			'panel[action=mainNavPanel]':{
				beforecollapse: me.onNavCollapsed,
				beforeexpand: me.onNavExpanded
			}
		});

	},

	getTopNavigation:function(){
		return app
	},

	/**
	 *
	 * @param {object} treepanel
	 */
	onNavigationBeforeRender:function(treepanel){
		treepanel.getStore().on('load', this.afterNavigationLoad, this);
	},

	navigateToDefault: function(){
		this.navigateTo('App.view.dashboard.Dashboard');
	},

	/**
	 *
	 * @param {string} cls  - example: 'App.view.ExamplePanel'
	 * @param {function} [callback] - callback function
	 * @param {bool} resetParams true to reset all params
	 */
	navigateTo: function(cls, callback, resetParams){
		var params = resetParams !== true ? this.getUrlParams() : [];
		params[0] = cls;
		this.setUrlParams(params);
		if(typeof callback == 'function') callback(true);
	},


	goTo: function(cls, callback, resetParams){
		this.navigateTo(cls, callback, resetParams);
	},

	/**
	 *
	 * @param {object} model
	 * @param {array} selected
	 */
	onNavigationNodeSelected: function(model, selected){
		if(0 < selected.length){
			if(selected[0].data.leaf){
				this.navigateTo(selected[0].data.id);
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

	setUrlParams:function(params){
		var url = './#!/';
		if(params.length > 0) url += params.join('/');
		window.location = url;
	},

	getUrlParams:function(){
		if(window.location.hash){
			return window.location.hash.substring(1).replace(/!\//, '').split('/');
		}
		return [];
	},

	/**
	 * this method handle the card layout when the URL changes
	 * @param {string} url
	 */
	urlChange:function(url){
		var me = this,
			tree = me.getMainNav(),
			treeStore = tree.getStore(),
			cls = me.getUrlParams()[0],
			ref = me.getNavRefByClass(cls),
			layout = me.getViewport().MainPanel.getLayout(),
			sm = tree.getSelectionModel(),
			node = treeStore.getNodeById(cls);

		this.url = url;
		sm.select(node);

		// ignore the Login
		if(cls == 'App.view.login.Login') return;

		// if the panel is 'undefined' added to MainPanel
		if (typeof me[ref] == 'undefined') {
			me.getViewport().MainPanel.el.mask();
			me[ref] = me.getViewport().MainPanel.add(Ext.create(cls));

		// if the class is destroyed then render it
		} else {
			if (me[ref].isDestroyed) me[ref].render();
		}

		// fire global event
		me.getViewport().fireEvent('beforenavigation', me[ref]);

		// call panel onActive method
		me[ref].onActive(function(success){
			me.getViewport().MainPanel.el.unmask();
			if(success){
				me.activePanel = layout.setActiveItem(me[ref]);
			}
		});

		// fire global event
		me.getViewport().fireEvent('afternavigation', me[ref]);

	},

	/**
	 * this method acts as pressing the browser back btn
	 */
	goBack: function(){
		Ext.util.History.back();
	},

	/**
	 * this method gets the instance reference of a main panel class
	 * @param {string} cls
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
	 * @param {string} cls
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
	},

	/**
	 * this method shows the footer poolarea
	 */
	onNavCollapsed: function(){
		var me = this,
			navView = me.getPatientPoolArea(),
			foot = me.getAppFooter(),
			footView = me.getAppFooterDataView();

		if(footView){
			foot.setHeight(60);
			footView.show();
		}

		me.getMainNavPanel().isCollapsed = true;
		navView.hide();
	},

	/**
	 * this method hides the footer poolarea
	 */
	onNavExpanded: function(){
		var me = this,
			navView = me.getPatientPoolArea(),
			foot = me.getAppFooter(),
			footView = me.getAppFooterDataView();

		if(footView){
			foot.setHeight(30);
			footView.hide();
		}

		me.getMainNavPanel().isCollapsed = false;
		navView.show();
	},

	/**
	 *
	 * @param viewport
	 * @param patient
	 */
	onPatientSet:function(viewport, patient){
//		say('onPatientSet');
//		say(patient);
	},

	/**
	 *
	 * @param viewport
	 */
	onPatientUnset:function(viewport){
//		say('onPatientUnset');
	},

	initFunctionKeyNav:function(){
		Ext.getBody().on('keydown', this.captureDownKey, this);
		Ext.getBody().on('keyup', this.captureUpKey, this);
	},

	captureDownKey:function(e){
		if(e.getKey() == e.ALT){
			this.altIsDown = true;
			return;
		}
		if(!this.altIsDown) return;
		this.getViewport().fireEvent('navkey', e, e.getKey());
//		say('navkey');
	},

	captureUpKey:function(e){
		if(e.getKey() == e.ALT) this.altIsDown = false;
	},

	setNavKey:function(key){
		return this.navKey = Ext.EventObjectImpl[key];
	},

	getNavKey:function(){
		return this.navKey;
	},

	getExtraParams: function(){
		var params = this.getUrlParams();
		for(var i=0; i < params.length; i++){
			if(params[i].match(/^{.*}$/)) return eval('('+params[i]+')');
		}
		return false;
	}

});