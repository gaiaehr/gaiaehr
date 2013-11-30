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

Ext.define('App.view.patient.EncounterDocumentsGrid', {
	extend     : 'Ext.grid.Panel',
	alias:'widget.documentsimplegrid',
	title: i18n('documents'),
    split:true,
	initComponent: function() {
		var me = this;

		me.store = Ext.create('App.store.patient.PatientDocuments');
        me.columns = [
            {
                xtype: 'actioncolumn',
                width:26,
                items: [
                    {
	                    icon: 'resources/images/icons/preview.png',
	                    tooltip: i18n('view_document'),
	                    handler: me.onDocumentView,
	                    getClass:function(){
		                    return 'x-grid-icon-padding';
	                    }
                    }
                ]
            },
            {
                header: i18n('type'),
                flex:1,
                dataIndex:'docType'
            }
        ];

		me.callParent(arguments);
	},

	onDocumentView:function(grid, rowIndex){
		var rec = grid.getStore().getAt(rowIndex),
			src = rec.data.url;
		app.onDocumentView(src);
	},

	loadDocs:function(eid){
		this.store.load({params:{eid:eid}})
	}
});