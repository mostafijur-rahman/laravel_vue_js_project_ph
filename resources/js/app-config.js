let currentUrl = window.location.hostname;
let url = '';
let defaultLang = '';

switch (currentUrl) {

    case 'http://localhost:8000':
        url = 'http://localhost:8000/';
        break;

        // case y:
        //     // code block
        //     break;

        // default:
        //     // code block
}




// if(currentUrl=="http://localhost:8000"){
//     // http://localhost:8000/assets/img/logo.svg
//     url = 'http://localhost:8000/';
// }
// else if(currentUrl=="office.gymwol.local"){
//     routerBase = '',
//     url='http://office.gymwol.local/';
//     siteUrl='http://localhost:3000/';
// }
// else if(currentUrl=="devoffice.gymwol.com"){
//     routerBase = '',
//     url='https://devoffice.gymwol.com/';
//     siteUrl='https://dev.gymwol.com/';
// }
// else if(currentUrl=="testoffice.gymwol.com"){
//     routerBase = '',
//     url='https://testoffice.gymwol.com/';
//     siteUrl='https://test.gymwol.com/';
// }
// else if(currentUrl=="office.gymwol.com"){
//     routerBase = '',
//     url='https://office.gymwol.com/';
//     siteUrl='https://gymwol.com/';
// }


let siteLanguages = [
    { name: 'English', code: 'en-US', urlPrefix: 'en', translationKey: 'en', flag: 'flag-icon-us' },
    { name: 'Bengali', code: 'bn', urlPrefix: 'bn', translationKey: 'bn', flag: 'flag-icon-bd' },
];

if (!localStorage.getItem('ph_current_lang')) {

    const userLocale = navigator.languages && navigator.languages.length ? navigator.languages[0] : navigator.language;
    let browserLang = userLocale.substr(0, 2);

    if (siteLanguages.find((singleLanguage) => singleLanguage.code == browserLang)) {
        localStorage.setItem('ph_current_lang', browserLang);
    } else {
        localStorage.setItem('ph_current_lang', 'bn');
    }
}

export default {
    // routerBase: routerBase,
    baseURL: url,
    // siteUrl: siteUrl,
    // axiosBaseURL: url+'api/',
    // languages: siteLanguages,
    defaultLang: localStorage.getItem('ph_current_lang')
}