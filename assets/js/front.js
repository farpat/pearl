import '../css/front.scss';
import 'vite/dynamic-import-polyfill';

console.log('console log from front');

let scriptToLoad = document.body.dataset.scriptToLoad;
if (scriptToLoad !== '') {
    import(`./front/pages/${scriptToLoad}.js`);
}