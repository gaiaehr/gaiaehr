(function() {
    var animations = {
        text  : 'Animations',
        card  : false,
        items: [{
            text: 'Slide',
            card: false,
            preventHide: true,
            animation: {
                type: 'slide',
                direction: 'left',
                duration: 300
            },
            leaf: true
        }]
    //                    {
    //                        text: 'SlideCover',
    //                        card: false,
    //                        preventHide: true,
    //                        cardSwitchAnimation: {
    //                            type: 'slide',
    //                            cover: true
    //                        },
    //                        leaf: true
    //                    },
    //                    {
    //                        text: 'SlideReveal',
    //                        card: false,
    //                        preventHide: true,
    //                        cardSwitchAnimation: {
    //                            type: 'slide',
    //                            reveal: true
    //                        },
    //                        leaf: true
    //                    },
    //                    {
    //                        text: 'Cube',
    //                        card: false,
    //                        preventHide: true,
    //                        animation: {
    //                            type: 'cube',
    //                            easing: 'ease-out'
    //                        },
    //                        leaf: true
    //                    }
    };

    if (Ext.os.deviceType == 'Desktop' || Ext.os.name == 'iOS') {
        animations.items.push({
            text: 'Pop',
            card: false,
            preventHide: true,
            animation: {
                type: 'pop',
                duration: 300,
                scaleOnExit: true
            },
            leaf: true
        }, {
            text: 'Fade',
            card: false,
            preventHide: true,
            animation: {
                type: 'fade',
                duration: 300
            },
            leaf: true
        });
    }

    var root = {
        items: [{
            text : 'User Interface',
            cls  : 'launchscreen',
            items: [{
                text  : 'Buttons',
                leaf  : true
            }, {
                text  : 'Forms',
                leaf  : true
            }, {
                text  : 'List',
                leaf  : true
            }, {
                text  : 'Nested List',
                view  : 'NestedList',
                leaf  : true
            }, {
                text  : 'Icons',
                leaf  : true
            }, {
                text  : 'Toolbars',
                leaf  : true
            }, {
                text  : 'Carousel',
                leaf  : true
            }, {
                text  : 'Tabs',
                leaf  : true
            }, {
                text  : 'Bottom Tabs',
                view  : 'BottomTabs',
                leaf  : true
            }, {
                text  : 'Map',
                view  : 'Map',
                leaf  : true
            }, {
                text  : 'Overlays',
                leaf  : true
            }
            ]
        }]
    };

    //Ext.Array.each(animations, function(anim) {
    //    root.items.push(anim);
    //});

    root.items.push(animations, {
            text  : 'Touch Events',
            view  : 'TouchEvents',
            leaf  : true
        }, {
            text: 'Data',
            items: [{
                text  : 'Nested Loading',
                view  : 'NestedLoading',
                leaf  : true
            }, {
                text  : 'JSONP',
                leaf  : true
            }, {
                text  : 'YQL',
                leaf  : true
            }, {
                text  : 'Ajax',
                leaf  : true
            }]
        }, {
            text: 'Media',
            items: [{
                text  : 'Video',
                leaf  : true
            }, {
                text  : 'Audio',
                leaf  : true
            }]
        });

    Ext.define('Kitchensink.store.Demos', {
        extend  : 'Ext.data.TreeStore',
        model   : 'Kitchensink.model.Demo',
        requires: ['Kitchensink.model.Demo'],

        root: root,
        defaultRootProperty: 'items'
    });
})();
