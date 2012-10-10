Ext.define('Oreilly.view.session.Detail', {

	extend: 'Ext.Container',
	xtype: 'session',

	config: {

		layout: 'vbox',
		scrollable: true,

		title: '',

		items: [
			{
				xtype: 'sessionInfo'
			},
			{
				xtype: 'speakers',
				store: 'SessionSpeakers',

				scrollable: false,

				items: [
					{
						xtype: 'listitemheader',
						cls: 'dark',
						html: 'Speakers'
					}
				]
			}
		]

	}
});
