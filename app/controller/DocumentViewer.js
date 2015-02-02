Ext.define('App.controller.DocumentViewer', {
    extend: 'Ext.app.Controller',
	requires:[
		'App.view.patient.windows.ArchiveDocument'
	],
	refs: [
        {
            ref:'DocumentViewerWindow',
            selector:'documentviewerwindow'
        },
        {
            ref:'DocumentViewerWindow',
            selector:'documentviewerwindow > form'
        },
		{
			ref:'ArchiveDocumentBtn',
			selector:'documentviewerwindow #archiveDocumentBtn'
		},
        {
            ref:'ArchiveWindow',
            selector:'patientarchivedocumentwindow'
        },
        {
            ref:'ArchiveForm',
            selector:'patientarchivedocumentwindow > form'
        }
	],

	init: function() {
		var me = this;

		me.control({
			'documentviewerwindow':{
				close: me.onViewerDocumentsWinClose
			},
			'documentviewerwindow #archiveDocumentBtn': {
				click: me.onArchiveDocumentBtnClick
			},
			'patientarchivedocumentwindow #archiveBtn': {
				click: me.onArchiveBtnClick
			}
		});
	},

	onArchiveBtnClick: function(btn){
		var win = btn.up('window'),
			form = win.down('form').getForm(),
			values = form.getValues();

		if(form.isValid()){
			values.pid = app.patient.pid;
			values.eid = app.patient.eid;
			values.uid = app.user.id;
			DocumentHandler.transferTempDocument(values, function(provider, response){

				if(response.result.success){
					if(dual){
						dual.msg(i18n('sweet'), 'document_transferred');
					}else{
						app.msg(i18n('sweet'), 'document_transferred');
					}
					win.documentWindow.close();
					win.close();
				}else{
					if(dual){
						dual.msg(i18n('oops'), 'document_transfer_failed', true);
					}else{
						app.msg(i18n('oops'), 'document_transfer_failed', true);
					}
				}
			});
		}
	},

	onArchiveDocumentBtnClick: function(btn){
		var win = btn.up('window'),
			values = {
				id: win.documentId,
				docType: win.documentType,
				title: win.documentType +  ' ' + i18n('order')
			};
		var archive = Ext.widget('patientarchivedocumentwindow',{
			documentWindow: win
		});
		archive.down('form').getForm().setValues(values);
	},

	onViewerDocumentsWinClose: function(win){
		if(win.documentType == 'temp'){
			DocumentHandler.destroyTempDocument({id: win.documentId});
		}
	}


});