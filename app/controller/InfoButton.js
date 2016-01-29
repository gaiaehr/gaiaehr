Ext.define('App.controller.InfoButton', {
	extend: 'Ext.app.Controller',
	requires: [


	],

	init: function(){
		var me = this;

		me.medline  = 'http://apps2.nlm.nih.gov/medlineplus/services/mpconnect.cfm?';
		me.language = _('lang_code').match(/^es/) ? 'es' : 'en';
		me.codeSytem = {
			'ICD10CM': '2.16.840.1.113883.6.90',
			'ICD10-CM': '2.16.840.1.113883.6.90',
			'ICD-10-CM': '2.16.840.1.113883.6.90',
			'ICD9CM': '2.16.840.1.113883.6.103',
			'ICD9-CM': '2.16.840.1.113883.6.103',
			'ICD-9-CM': '2.16.840.1.113883.6.103',
			'SNOMED': '2.16.840.1.113883.6.96',
			'RXCUI': '2.16.840.1.113883.6.88',
			'NDC': '2.16.840.1.113883.6.69',
			'LN': '2.16.840.1.113883.6.1',
			'LOINC': '2.16.840.1.113883.6.1'
		};

		me.control({

		});

	},

	doGetInfo: function(code, codeType, codeText){
		var me = this;

		var url = me.medline;
		url += 'mainSearchCriteria.v.c=' + code;
		url += '&mainSearchCriteria.v.cs=' + me.codeSytem[codeType];
		url += '&informationRecipient.languageCode.c=' + me.language;

		window.open(url, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=600");
//		WebSearchCodes.Search({ code: code, codeType: codeType, codeText: codeText }, function(data){
//			me.getInformationWindow(data);
//		})
	},

	doGetInfoByUrl: function(url){
		var me = this;
		window.open(url, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=600");
	},

	getInformationWindow: function(data){
		Ext.widget('window', {
			title: _('information'),
			autoShow: true,
			width: 800,
			height: 600,
			data: data,
			autoScroll: true,
			tpl: new Ext.XTemplate(
				'<div class="externalinfo-container">' +
				'<h1>{feed.title._value}</h1>' +
				'<h2>{feed.subtitle._value}</h2>' +
				'<p><span>Author:</span> {feed.author.name._value}</h1>' +
				'<p><span>Updated:</span> {feed.updated._value}</h1>' +
				'<div class="entries">' +
				'   <tpl for="feed.entry">' +
					'<div class="externalinfo-entry">' +
					'<h3><a href="{link.href}">{title._value}</a></h3>' +
					'{summary._value}' +
					'</div>' +
				'   </tpl>' +
				'</div>' +
				'</div>'
			)
		});
	}


});