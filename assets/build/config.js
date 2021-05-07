export default {
    entry: {
        'front.js': 'assets/js/front.js',
        'admin.js': 'assets/js/admin.js',
    },
    output: 'public/assets',
    refresh: ['templates/**/*.twig'],
};