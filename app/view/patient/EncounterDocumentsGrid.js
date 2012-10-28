/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/15/12
 * Time: 4:30 PM
 *
 * @namespace Immunization.getImmunizationsList
 * @namespace Immunization.getPatientImmunizations
 * @namespace Immunization.addPatientImmunization
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