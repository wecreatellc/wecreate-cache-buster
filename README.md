# weCreate Cache Buster

weCreate cache buster is just an  extension of Mike Funk's PHP Cache Buster, but with a  
getHashByFilename() method that allows us to simply retrieve the asset hash from a utility  
like gulp-buster and use it in wp_enqueue_style as the version number for example.

WeCreate_Cache_Buster extends BustersPhp & implements BustersPhpInterface. I typically use  
composer to manage the dependency and either use the autoload.php file or require the files  
myself.