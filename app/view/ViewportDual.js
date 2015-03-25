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

Ext.define('App.view.ViewportDual', {
    extend: 'Ext.Viewport',
    requires: [
	    'App.view.patient.AdvanceDirectives',
	    'App.view.patient.Allergies',
	    'App.view.patient.DoctorsNotes',
	    'App.view.patient.Documents',
	    'App.view.patient.Immunizations',
	    'App.view.patient.LabOrders',
	    'App.view.patient.ActiveProblems',
	    'App.view.patient.Medications',
	    'App.view.patient.RadOrders',
	    'App.view.patient.Referrals',
	    'App.view.patient.Results',
	    'App.view.patient.RxOrders',
	    'App.view.patient.SocialPanel'
    ],
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	itemId:'dualViewport',
	style: {
		'background-color': '#DFE8F6'
	},
	items:[
		{
			xtype: 'container',
			cls: 'RenderPanel-header',
			itemId: 'RenderPanel-header',
			region: 'north',
			margin: '5 0 0 0',
			height: 33
		},
		{
			xtype:'tabpanel',
			activeTab: 0,
			frame: true,
			margin: '0 5 5 5',
			flex: 1,
			items:[
				{
					xtype:'patientdocumentspanel'
				},
				{
					xtype:'patientimmunizationspanel'
				},
				{
					xtype:'patientallergiespanel'
				},
				{
					xtype:'patientactiveproblemspanel'
				},
				{
					xtype: 'patientadvancedirectivepanel'
				},
				{
					xtype:'patientmedicationspanel'
				},
				{
					xtype:'patientsocialpanel'
				},
				{
					xtype:'patientresultspanel'
				},
				{
					xtype:'patientreferralspanel'
				},
				{
					xtype:'patientlaborderspanel'
				},
				{
					xtype:'patientradorderspanel'
				},
				{
					xtype:'patientrxorderspanel'
				},
				{
					xtype:'patientdoctorsnotepanel'
				}
			]
		}
	],

	onDocumentView: function(id, type){
		var windows = Ext.ComponentQuery.query('documentviewerwindow'),
			src = 'dataProvider/DocumentViewer.php?site='+ site +'&id='+id,
			win;

		if(typeof type != 'undefined') src += '&temp=' + type;

		win = Ext.create('App.view.patient.windows.DocumentViewer',{
			documentType: type,
			documentId: id,
			items:[
				{
					xtype:'miframe',
					autoMask:false,
					src: src
				}
			]
		});

		if(windows.length > 0){
			var last = windows[(windows.length - 1)];
			for(var i=0; i < windows.length; i++){
				windows[i].toFront();
			}
			win.showAt((last.x + 25), (last.y + 5));

		}else{
			win.show();
		}
	},

	msg: function(title, format, error, persistent) {
		var msgBgCls = (error === true) ? 'msg-red' : 'msg-green';
		this.msgCt = Ext.get('msg-div');
		if(!this.msgCt) this.msgCt = Ext.fly('msg-div');
		this.msgCt.alignTo(document, 't-t');
		var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1)),
			m = Ext.core.DomHelper.append(this.msgCt, {
				html: '<div class="flyMsg ' + msgBgCls + '"><h3>' + (title || '') + '</h3><p>' + s + '</p></div>'
			}, true);
		if (persistent === true) return m; // if persitent return the message element without the fade animation
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
				afteranimate: function() {
					m.destroy();
				}
			}
		});
		return true;
	}

});
