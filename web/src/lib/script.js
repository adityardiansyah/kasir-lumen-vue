function formatRupiah(angka){
    let bilangan = angka;
    let	reverse = bilangan.toString().split('').reverse().join(''),
    ribuan 	= reverse.match(/\d{1,3}/g);
    ribuan	= ribuan.join('.').split('').reverse().join('');
    return ribuan;  
}

function currency(value, separator) {
    if (typeof value == "undefined") return "0";
    if (typeof separator == "undefined" || !separator) separator = ",";
 
    return value.toString()
                .replace(/[^\d]+/g, "")
                .replace(/\B(?=(?:\d{3})+(?!\d))/g, separator);
}


export default {formatRupiah, currency}