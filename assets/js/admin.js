import '../css/admin.scss';
import 'vite/dynamic-import-polyfill';

console.log('console log from admin');

let scriptToLoad = document.body.dataset.scriptToLoad;
if (scriptToLoad !== '') {
    import(`./admin/pages/${scriptToLoad}.js`);
}
