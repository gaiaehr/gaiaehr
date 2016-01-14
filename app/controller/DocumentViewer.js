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
					if(window.dual){
						dual.msg(_('sweet'), 'document_transferred');
					}else{
						app.msg(_('sweet'), 'document_transferred');
					}
					win.documentWindow.close();
					win.close();
				}else{
					if(dual){
						dual.msg(_('oops'), 'document_transfer_failed', true);
					}else{
						app.msg(_('oops'), 'document_transfer_failed', true);
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
				title: win.documentType +  ' ' + _('order')
			};
		var archive = Ext.widget('patientarchivedocumentwindow',{
			documentWindow: win
		});
		archive.down('form').getForm().setValues(values);
	},

	onViewerDocumentsWinClose: function(win){
		DocumentHandler.destroyTempDocument({id: win.documentId});
	},

	doDocumentView: function(id, type, site){

		var windows = Ext.ComponentQuery.query('documentviewerwindow'),
			src = 'dataProvider/DocumentViewer.php?site=' + (site || app.user.site) + '&id=' + id + '&token=' + app.user.token,
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
	}


});