import moment from 'moment';

const commonHelper = {
    install(app) {
        app.config.globalProperties.$numFormat = (key) => {
                return Number(key).toLocaleString();
            },
            app.config.globalProperties.$numFormatWithDollar = (key) => {
                return key ? '$' + Number(key).toLocaleString() : '-';
            },
            app.config.globalProperties.$capFirstLetter = (val) => {
                return val.charAt(0).toUpperCase() + val.slice(1);
            },
            app.config.globalProperties.$getHumanDate = (val) => {
                return moment(val, 'YYYY-MM-DD').format('DD/MM/YYYY');
            },
            app.config.globalProperties.$getHumanDateTime = (val) => {
                return moment(val, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY h:mm A');
            },
            app.config.globalProperties.$getCurrentLang = () => {
                const currentLang = localStorage.getItem('ph_current_lang');
                if (!currentLang) {
                    return 'bn';
                } else {
                    return currentLang;
                }
            },
            // app.config.globalProperties.$getHumanDateTime = (emailAddress) => {
            //     let mailformat = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
            //     return (emailAddress.match(mailformat)) ? true : false;
            // },
            app.config.globalProperties.$getErrorStatus = (errorArray, fieldName) => {
                if (errorArray) {
                    return errorArray[fieldName] ? true : false;
                } else {
                    return false;
                }
            },
            app.config.globalProperties.$getErrorMessage = (errorArray, fieldName) => {
                if (errorArray) {
                    return errorArray[fieldName] ? errorArray[fieldName] : '';
                } else {
                    return '';
                }
            },
            app.config.globalProperties.$getSumFromLists = (lists, key1, key2 = null, key3 = null) => {

                if (!key2) {
                    var sumWithInitial = lists.reduce(
                        (accumulator, currentValue) =>
                        Number(accumulator) + Number(currentValue[key1]),
                        0); // 0 = initialValue
                } else {
                    var sumWithInitial = lists.reduce(
                        (accumulator, currentValue) =>
                        Number(accumulator) + Number(currentValue[key1][key2]),
                        0); // 0 = initialValue
                }
                return sumWithInitial;
            },
            app.config.globalProperties.$baseUrl = () => {
                return window.location.hostname;
            }
            // app.config.globalProperties.$getSettingCompany = function() {
            //     axios.get('/api/settings/get-company')
            //         .then((response) => {
            //             console.log(response.data.company);
            //             // return parse.JSON(response.data.company);
            //         }).catch((error) => {
            //             return {}
            //         })
            // }
    }
}

export default commonHelper;