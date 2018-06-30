var config = {
	
	map: {
        '*': {	
            bootstrap :  'plugins/bootstrap/js/bootstrap.min',
			slick :  'plugins/slick/slick',
			fancybox :  'plugins/fancybox/js/jquery.fancybox',
			elevatezoom :  'plugins/zoom/jquery.elevatezoom',
			parallax :  'plugins/parallax/jquery.parallax'
        }
    },
	'shim': {
			'slick': {
					deps: ['jquery']
			},
			'bootstrap': {
					deps: ['jquery']
			},
			'fancybox': {
					deps: ['jquery']
			},
			'elevatezoom': {
					deps: ['jquery']
			},
			'parallax': {
					deps: ['jquery']
			}
	},
    deps: [
        "Vicomage_General/js/theme"
    ]
};
 
