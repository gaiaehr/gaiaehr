Ext.define('Kitchensink.view.TouchEvents', {
    extend: 'Ext.Container',

    config: {
        profile: null
    },

    initialize: function() {
        this.callParent(arguments);

        var eventDispatcher = this.getEventDispatcher();

        eventDispatcher.addListener('element', '#touchpad', '*', function(a,b,c,d) {
            var name = d.info.eventName;

            if (!name.match("mouse") && !name.match("click")) {
                var logger = Ext.getCmp('logger'),
                    scroller = logger.getScrollable().getScroller();

                logger.innerHtmlElement.createChild({
                    html: d.info.eventName
                });

                setTimeout(function() {
                    scroller.scrollTo(0, scroller.getSize().y - scroller.getContainerSize().y);
                }, 50);
            }
        });
    },

    onOrientationChange: function(orientation) {
        var touchCard = this.touchCard;

        this.touchCard = new Ext.Container({
            layout: {
                type: orientation == 'portrait' ? 'vbox' : 'hbox',
                align: 'stretch'
            },
            items: [this.touchPad, this.logger]
        });

        if (touchCard && this.getActiveItem() == touchCard) {
            this.setActiveItem(this.touchCard);
        } else {
            this.add(this.touchCard);
        }

        if (touchCard) {
            this.remove(touchCard, true);
        }
    },

    updateProfile: function(profile) {
        var logger = {
                layout: 'fit',
                flex: 1,
                items: [{
                    xtype : 'toolbar',
                    docked: 'top',
                    title : 'Event Log',
                    ui    : 'light'
                }, {
                    styleHtmlContent: true,
                    id        : 'logger',
                    scrollable: true,
                    // @TODO: jacky - needs to make html an inner item on a container automatically
                    html      : '<span>Try using gestures on the area to the right to see how events are fired.</span>'
                }]
            },
            info = {
                flex: 1.5,
                scrollable: true,
                styleHtmlContent: true,
                html: '<p>Sencha Touch comes with a multitude of touch events available on components. Included touch events that can be used are:</p><ul><li>touchstart</li><li>touchmove</li><li>touchend</li><li>touchdown</li><li>scrollstart</li><li>scroll</li><li>scrollend</li><li>tap</li><li>tapstart</li><li>tapcancel</li><li>doubletap</li><li>taphold</li><li>swipe</li><li>pinch</li><li>pinchstart</li><li>pinchend</li></ul>'
            },
            touchPad = {
                flex: 1,
                id  : 'touchpad',
                layout: {
                    type: 'vbox',
                    pack: 'center',
                    align: 'stretch'
                },
                margin: 10,
                items: [{
                    html: 'Touch here!'
                }]
            }, infoCard, touchCard;

        if (profile === 'phone') {
            this.setLayout({type: 'card'});

            this.infoCard = new Ext.Container({
                scrollable: true,
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                items: [{
                    xtype: 'button',
                    ui: 'confirm',
                    text: 'Console',
                    margin: 10,
                    handler: this.onConsoleButtonTap,
                    scope: this
                }, {
                    xtype: 'component',
                    styleHtmlContent: true,
                    html: '<p>Sencha Touch comes with a multitude of touch events available on components. Included touch events that can be used are:</p><ul><li>touchstart</li><li>touchmove</li><li>touchend</li><li>scrollstart</li><li>scroll</li><li>scrollend</li><li>singletap</li><li>tap</li><li>doubletap</li><li>taphold</li><li>swipe</li><li>pinch</li></ul>'
                }]
            });

            this.add(this.infoCard);

            Ext.Viewport.on({
                orientationchange: this.onOrientationChange,
                scope: this
            });

            this.logger = new Ext.Container(logger);
            this.touchPad = new Ext.Container(touchPad);

            this.onOrientationChange(Ext.Viewport.getOrientation());
            this.setActiveItem(0);
        } else {
            this.setLayout({
                type: 'hbox',
                align: 'stretch'
            });

            this.add([{
                docked: 'left',
                width: 250,
                id: 'touchinfopanel',
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                items: [info, logger]
            }, touchPad]);

            Ext.Viewport.un({
                orientationchange: this.onOrientationChange,
                scope: this
            });
        }
    },

    onConsoleButtonTap: function() {
        this.setActiveItem(1);
    }
});