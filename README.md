# Mysql general_log
Mysql'e gelen sorguları izlemek için kullanabilirsiniz.
PHP 7.1 ve üzerinden çalışır.
Kullanımı için index.php dosyasını açın ve 
$host     = "localhost";
$user     = "root";
$password = "";
değişkenlerini düzenleyin.
Sorguları aktif/pasif hale getirmek için SET komutu kullandığı için tam yetkili bir kullanıcı vermeniz gerekiyor.
Düzenlemelerinizi yaptıktan sonra tarayıcıda açın ve "Aktif et" butonuna tıkladıktan sonra mysql'e gelen sorgularınızı anlık olarak görebilirsiniz.
Aktif/Pasif yaparak eski sorgu kayıtlarını temizleyebilirsiniz.
