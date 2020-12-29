function formatRupiah(angka, prefix){
    let bilangan = angka;
    let	reverse = bilangan.toString().split('').reverse().join(''),
    ribuan 	= reverse.match(/\d{1,3}/g);
    ribuan	= ribuan.join('.').split('').reverse().join('');
    return ribuan;  
}

export default {formatRupiah}