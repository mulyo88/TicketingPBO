function xnumber(value) {
    try {
        return value.replace(/\,/g,'');
    } catch (e) {
        console.log(e)
    }
};