import '../css/admin.scss';

console.log('console log from admin');

let scriptToLoad = document.body.dataset.scriptToLoad;
if (scriptToLoad !== '') {
    scriptToLoad = `./admin/pages/${scriptToLoad}`;

    console.log(`--- Chargement ${scriptToLoad} ---`);

    import(`${scriptToLoad}`).then(() => {
        console.info(`Le fichier ${scriptToLoad} a été chargé`);
    }).catch((error) => {
        console.error(`Le fichier ${scriptToLoad} n'a pas été chargé`);
        console.error(error);
    });
}
