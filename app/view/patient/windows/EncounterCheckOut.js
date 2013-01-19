/**
 * Created with IntelliJ IDEA.
 * User: ernesto
 * Date: 1/17/13
 * Time: 5:14 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.windows.EncounterCheckOut', {
	extend:'App.ux.window.Window',
	title:i18n('checkout_and_signing'),
	closeAction:'hide',
	modal:true,
	layout:'border',
	width:1000,
	height:660,
	bodyPadding:5,

	pid:null,
	eid:null,

	initComponent:function(){
		var me = this;

		me.EncounterOrdersStore = Ext.create('App.store.patient.EncounterCPTsICDs');
		me.checkoutAlertArea = Ext.create('App.store.patient.CheckoutAlertArea');

		Ext.apply(me,{
			items:[
				{
					xtype:'grid',
					title:i18n('services_diagnostics'),
					region:'center',
					store:me.EncounterOrdersStore,
					columns:[
						{
							header:i18n('code'),
							width:60,
							dataIndex:'code'
						},
						{
							header:i18n('description'),
							flex:1,
							dataIndex:'code_text'
						},
						{
							header:i18n('type'),
							flex:1,
							dataIndex:'type'
						}
					]
				},
				me.documentsimplegrid = Ext.create('App.view.patient.EncounterDocumentsGrid', {
					title:i18n('documents'),
					region:'east',
					width:485
				}),
				{
					xtype:'form',
					title:i18n('additional_info'),
					region:'south',
					split:true,
					height:245,
					layout:'column',
					defaults:{
						xtype:'fieldset',
						padding:8
					},
					items:[
						{
							xtype:'fieldcontainer',
							columnWidth:.5,
							defaults:{
								xtype:'fieldset',
								padding:8
							},
							items:[
								{
									xtype:'fieldset',
									margin:'5 1 5 5',
									padding:8,
									columnWidth:.5,
									height:115,
									title:i18n('messages_notes_and_reminders'),
									items:[
										{
											xtype:'textfield',
											name:'message',
											fieldLabel:i18n('message'),
											anchor:'100%'
										},
										{
											xtype:'textfield',
											name:'reminder',
											fieldLabel:i18n('reminder'),
											anchor:'100%'
										},
										{
											xtype:'textfield',
											grow:true,
											name:'note',
											fieldLabel:i18n('note'),
											anchor:'100%'
										}
									]
								},
								{
									title:'Follow Up',
									margin:'5 1 5 5',
									defaults:{
										anchor:'100%'
									},
									items:[
										{
											xtype:'mitos.followupcombo',
											fieldLabel:i18n('time_interval'),
											name:'followup_time'
										},
										{
											fieldLabel:i18n('facility'),
											xtype:'mitos.activefacilitiescombo',
											name:'followup_facility'
										}
									]
								}
							]
						},
						{
							xtype:'fieldset',
							margin:5,
							padding:8,
							columnWidth:.5,
							layout:'fit',
							height:208,
							title:i18n('warnings_alerts'),
							items:[
								{
									xtype:'grid',
									hideHeaders:true,
									store:me.checkoutAlertArea,
									border:false,
									rowLines:false,
									header:false,
									viewConfig:{
										stripeRows:false,
										disableSelection:true
									},
									columns:[
										{
											dataIndex:'alertType',
											width:30,
											renderer:me.alertIconRenderer
										},
										{
											dataIndex:'alert',
											flex:1
										}
									]
								}
							]
						}
					]
				}
			],
			buttons:[
				{
					text:i18n('co_sign'),
					action:'encounter',
					scope:me,
					handler:me.coSignEncounter
				},
				{
					text:i18n('sign'),
					action:'encounter',
					scope:me,
					handler:me.signEncounter
				},
				{
					text:i18n('cancel'),
					scope:me,
					handler:me.cancelCheckout

				}
			],
			listeners:{
				scope:me,
				show:me.onWindowShow
			}
		});

		me.callParent();


	},

	coSignEncounter:function(){

	},

	signEncounter:function(){
		this.enc.closeEncounter();
		this.close();
	},

	cancelCheckout:function(){
		this.close();
		this.down('form').getForm().reset();
	},

	onWindowShow:function(){
		var me = this;
		me.EncounterOrdersStore.load({params:{eid:app.patient.eid}});
		if(acl['access_encounter_checkout']) me.checkoutAlertArea.load({params:{eid:app.patient.eid}});
		me.documentsimplegrid.loadDocs(app.patient.eid);
	},

	alertIconRenderer:function(v){
		if(v == 1){
			return '<img src="resources/images/icons/icoLessImportant.png" />'
		}else if(v == 2){
			return '<img src="resources/images/icons/icoImportant.png" />'
		}
		return v;
	}

});