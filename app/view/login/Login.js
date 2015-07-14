/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


Ext.define('App.view.login.Login', {
	extend: 'Ext.Viewport',
	requires: [
		'App.ux.combo.Languages',
		'App.ux.combo.ActiveFacilities'
	],

	initComponent: function(){
		var me = this;
		me.currSite = null;
		me.siteLang = window['lang']['lang_code'];

		// setting to show site field
		me.showSite = false;

		me.siteError = window.site === false || window.site === '';

		me.logged = false;

		/**
		 * The Copyright Notice Window
		 */
		me.winCopyright = Ext.create('widget.window', {
			id: 'winCopyright',
			title: 'GaiaEHR Copyright Notice',
			bodyStyle: 'background-color: #ffffff; padding: 5px;',
			autoLoad: 'gpl-licence-en.html',
			closeAction: 'hide',
			width: 900,
			height: '75%',
			modal: false,
			resizable: true,
			draggable: true,
			closable: true
		});

		/**
		 * Form Layout [Login]
		 */
		me.formLogin = {
			xtype: 'form',
			bodyStyle: 'background: #ffffff; padding:5px 5px 0',
			defaultType: 'textfield',
			waitMsgTarget: true,
			frame: false,
			border: false,
			width: 483,
			padding: '0 0 5 0',
			bodyPadding: '5 5 0 5',
			baseParams: {
				auth: 'true'
			},
			fieldDefaults: {
				msgTarget: 'side',
				labelWidth: 300
			},
			defaults: {
				anchor: '100%'
			},
			items: [
				{
					xtype: 'textfield',
					fieldLabel: _('username'),
					blankText: 'Enter your username',
					name: 'authUser',
					itemId: 'authUser',
					minLengthText: 'Username must be at least 3 characters long.',
					minLength: 2,
					maxLength: 12,
					allowBlank: false,
					validationEvent: false,
					listeners: {
						scope: me,
						specialkey: me.onEnter
					}
				},
				{
					xtype: 'textfield',
					blankText: 'Enter your password',
					inputType: 'password',
					name: 'authPass',
					fieldLabel: _('password'),
					minLengthText: 'Password must be at least 4 characters long.',
					validationEvent: false,
					allowBlank: false,
					minLength: 4,
					maxLength: 12,
					listeners: {
						scope: me,
						specialkey: me.onEnter
					}
				},
				{
					xtype: 'activefacilitiescombo',
					name: 'facility',
					itemId: 'facility',
					fieldLabel: _('facility'),
					allowBlank: false,
					editable: false,
					hidden: true,
					storeAutoLoad: false,
					listeners: {
						scope: me,
						specialkey: me.onEnter,
						beforerender: me.onFacilityCmbBeforeRender
					}
				},
				{
					xtype: 'languagescombo',
					name: 'lang',
					itemId: 'lang',
					fieldLabel: _('language'),
					allowBlank: false,
					editable: false,
					listeners: {
						scope: me,
						specialkey: me.onEnter,
						select: me.onLangSelect
					}
				}
			],
			buttons: [
				{
					xtype: 'checkbox',
					name: 'checkin'
				},
				'Check-In Mode',
				'->',
				{
					text: _('login'),
					name: 'btn_login',
					scope: me,
					handler: me.loginSubmit
				},
				'-',
				{
					text: _('reset'),
					name: 'btn_reset',
					scope: me,
					handler: me.onFormReset
				}
			]
		};

		if(me.showSite){
			Ext.Array.insert(me.formLogin.items, 3, [{
				xtype: 'combobox',
				name: 'site',
				itemId: 'site',
				displayField: 'site',
				valueField: 'site',
				queryMode: 'local',
				fieldLabel: 'Site',
				store: me.storeSites = Ext.create('App.store.login.Sites'),
				allowBlank: false,
				editable: false,
				msgTarget: 'side',
				labelWidth: 300,
				anchor: '100%',
				listeners: {
					scope: me,
					specialkey: me.onEnter,
					select: me.onSiteSelect
				}
			}]);

		}else{
			Ext.Array.insert(me.formLogin.items, 3, [{
				xtype: 'textfield',
				name: 'site',
				itemId: 'site',
				hidden: true,
				value: window.site
			}]);
		}

		var windowItems = [
			{
				xtype: 'box',
				width: 483,
				height: 135,
				html: '<img src="resources/images/logon_header.png" />'
			}
		];

		windowItems.push(me.siteError ? {
			xtype: 'container',
			padding: 15,
			html: 'Sorry no site configuration file found. Please contact Support Desk'
		} : me.formLogin);

		/**
		 * The Logon Window
		 */
		me.winLogon = Ext.create('widget.window', {
			title: 'GaiaEHR Logon',
			closeAction: 'hide',
			plain: true,
			modal: false,
			resizable: false,
			draggable: false,
			closable: false,
			width: 495,
			bodyStyle: 'background: #ffffff;',
			autoShow: true,
			layout: {
				type: 'vbox',
				align: 'stretch'
			},
			items: windowItems,
			listeners: {
				scope: me,
				afterrender: me.afterAppRender
			}
		});

		//me.notice1 = Ext.create('Ext.Container', {
		//	floating: true,
		//	cls: 'logout-warning-window',
		//	style: 'text-align:center; width:800',
		//	html: 'This demo version is 300% slower because files are not fully minified (compressed) or compiled.<br>' + 'Please allow about 10sec for the app to download. Compiled version loads between 1 - 2 seconds.',
		//	seconds: 10
		//}).show();
		//me.notice1.alignTo(Ext.getBody(), 't-t', [0, 10]);

//		if(Ext.isIE){
//			me.notice2 = Ext.create('Ext.Container', {
//				floating: true,
//				cls: 'logout-warning-window',
//				style: 'text-align:center; width:800',
//				html: '<span style="font-size: 18px;">WAIT!!! There is a known bug with Internet Explorer - <a href="http://gaiaehr.org:8181/browse/GAIAEH-119" target="_blank" style="color: white;">more info...</a></span><br>' + 'Please, access the application through any of these browsers... ' + '<span style="text-decoration: underline;"><a href="https://www.google.com/intl/en/chrome/" target="_blank" style="color: white;">Google Chrome</a></span>, ' + '<span style="text-decoration: underline;"><a href="http://www.mozilla.org/en-US/firefox/new/" target="_blank" style="color: white;">Firefox</a></span>, or ' + '<span style="text-decoration: underline;"><a href="http://www.opera.com/" target="_blank" style="color: white;">Opera</a></span>'
//			}).show();
//			me.notice2.alignTo(Ext.getBody(), 't-t', [0, 85]);
//		}
//		else if(!Ext.isChrome && !Ext.isOpera && !Ext.isGecko){
//			me.notice2 = Ext.create('Ext.Container', {
//				floating: true,
//				cls: 'logout-warning-window',
//				style: 'text-align:center; width:800',
//				html: 'GaiaEHR rely heavily on javascript and web 2.0 / ajax requests, although any browser will do the work<br>' + 'we strongly recommend to use any of the fastest browsers to day, <span style="text-decoration: underline;">' + '<span style="text-decoration: underline;"><a href="https://www.google.com/intl/en/chrome/" target="_blank" style="color: white;">Google Chrome</a></span>, ' + '<span style="text-decoration: underline;"><a href="http://www.mozilla.org/en-US/firefox/new/" target="_blank" style="color: white;">Firefox</a></span>, or ' + '<span style="text-decoration: underline;"><a href="http://www.opera.com/" target="_blank" style="color: white;">Opera</a></span>'
//			}).show();
//			me.notice2.alignTo(Ext.getBody(), 't-t', [0, 85]);
//		}

		me.listeners = {
			resize: me.onAppResize
		};

		me.callParent(arguments);
	},
	/**
	 * when keyboard ENTER key press
	 * @param field
	 * @param e
	 */
	onEnter: function(field, e){
		if(e.getKey() == e.ENTER){
			this.loginSubmit();
		}
	},

	onFacilityCmbBeforeRender: function(cmb){
		var me = this;
		cmb.getStore().on('load', me.onFacilityLoad, me);
		cmb.getStore().load();
	},
	/**
	 * Form Submit/Logon function
	 */
	loginSubmit: function(){

		var me = this,
			formPanel = me.winLogon.down('form'),
			form = formPanel.getForm(),
			params = form.getValues(),
			checkInMode = formPanel.query('checkbox')[0].getValue();

		if(form.isValid()){
			formPanel.el.mask('Sending credentials...');
			params.checkInMode = checkInMode;

			authProcedures.login(params, function(provider, response){
				if(response.result.success){
					window.location.reload();
				}else{
					Ext.Msg.show({
						title: 'Oops!',
						msg: response.result.message,
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
					me.onFormReset();
					formPanel.el.unmask();
				}
			});
		}else{
			this.msg('Oops!', 'Username And Password are required.');
		}
	},
	/**
	 * gets the site combobox value and store it in currSite
	 * @param combo
	 * @param value
	 */
	onSiteSelect: function(combo, value){
		this.currSite = value[0].data.site;
	},

	onLangSelect: function(combo, value){
		this.siteLang = value[0].data.value;
	},

	onFacilityLoad: function(store, records){
		var cmb = this.winLogon.down('form').getComponent('facility');
		store.insert(0, {
			option_name: 'Default',
			option_value: '0'
		});

		cmb.setVisible(records.length > 1);
		cmb.select(0);
	},

	/**
	 * form rest function
	 */
	onFormReset: function(){
		var me = this,
			form = me.winLogon.down('form').getForm();

		form.setValues({
			site: window.site,
			authUser: '',
			authPass: '',
			lang: me.siteLang
		});
		me.winLogon.down('form').getComponent('authUser').focus();
	},
	/**
	 * After form is render load store
	 */
	afterAppRender: function(win){
		var me = this,
			form = win.down('form'),
			langCmb = form.getComponent('lang');

		if(!me.siteError){
			if(me.showSite){
				me.storeSites.load({
					scope: me,
					callback: function(records, operation, success){
						if(success === true){
							/**
							 * Lets add a delay to make sure the page is fully render.
							 * This is to compensate for slow browser.
							 */
							Ext.Function.defer(function(){
								me.currSite = records[0].data.site;
								if(me.showSite){
									form.getComponent('site').setValue(me.currSite);
								}
							}, 500, this);
						}
						else{
							this.msg('Opps! Something went wrong...', 'No site found.');
						}
					}
				});
			}

			langCmb.store.load({
				callback: function(){
					langCmb.setValue(me.siteLang);

				}
			});

			Ext.Function.defer(function(){
				form.getComponent('authUser').inputEl.focus();
			}, 200);

		}

		win.doLayout();
	},
	/**
	 *  animated msg alert
	 * @param title
	 * @param format
	 * @param error
	 * @param persistent
	 */
	msg: function(title, format, error, persistent){
		var msgBgCls = (error === true) ? 'msg-red' : 'msg-green';
		this.msgCt = Ext.get('msg-div');
		this.msgCt.alignTo(document, 't-t');
		var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1)),
			m = Ext.core.DomHelper.append(this.msgCt, {
				html: '<div class="flyMsg ' + msgBgCls + '"><h3>' + (title || '') + '</h3><p>' + s + '</p></div>'
			}, true);
		if(persistent === true) return m; // if persistent return the message element without the fade animation
		m.addCls('fadeded');
		Ext.create('Ext.fx.Animator', {
			target: m,
			duration: error ? 7000 : 2000,
			keyframes: {
				0: { opacity: 0 },
				20: { opacity: 1 },
				80: { opacity: 1 },
				100: { opacity: 0, height: 0 }
			},
			listeners: {
				afteranimate: function(){
					m.destroy();
				}
			}
		});
		return true;
	},

	onAppResize: function(){
		this.winLogon.alignTo(this, 'c-c');
		if(this.notice1)
			this.notice1.alignTo(Ext.getBody(), 't-t', [0, 10]);
		if(this.notice2)
			this.notice2.alignTo(Ext.getBody(), 't-t', [0, 85]);
	}
});