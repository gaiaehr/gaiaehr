Ext.define('Oreilly.view.speaker.Detail', {

	extend: 'Ext.Container',
	xtype: 'speaker',

	config: {

		layout: 'vbox',
		scrollable: 'vertical',

		items: [
			{
				xtype: 'speakerInfo'
			},
			{
				xtype: 'list',
				store: 'SpeakerSessions',

				scrollable: false,

				items: [
					{
						xtype: 'listitemheader',
						cls: 'dark',
						html: 'Sessions'
					}
				],

				itemTpl: [
					'{title}'
				]
			}
		]

	}
});
