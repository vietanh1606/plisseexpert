var config = {
    map: {
        '*': {
            fancybox: 'Vicomage_Quickview/js/jquery.fancybox',
            quickview: 'Vicomage_Quickview/js/quickview'
        }
    },
    shim: {
        fancybox: {
            deps: ['jquery']
        },
        quickview: {
            deps: ['jquery']
        }
    }
};