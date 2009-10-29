{
 echo 'iPod Nano'
 echo 'iPod shuffle'
 echo 'MacBook Air'
 echo 'MacBook'
 echo 'iPod touch'
 echo 'Housse portefeuille en cuir Belkin pour iPod nano - Noir/Chocolat'
 echo 'Shure SE210 Sound-Isolating Earphones for iPod and iPhone'
} |
 while read product; do 
  perl -wne 'printf "%016.0f%s", rand 2**53, $_' < vendors |
   sort |
   cut -b17- |
   head -300 |
   while read vendor; do
    echo "insert into vendor_support (id_vendor, id_product) select vendor.id_vendor, product_lang.id_product from vendor, product_lang where vendor.title = '$vendor' and product_lang.name = '$product' and product_lang.id_lang = 1;"
   done
 done > vendor_support.NO.sql
