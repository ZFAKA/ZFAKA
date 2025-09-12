<?php
// change admin dir , by yumusb.
$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) !== 'cli') {
    exit("You must run it with cli.");
}
$flag = 0;
$applicationini = file_get_contents('./conf/application.ini');
$init = file_get_contents('./application/init.php');
foreach(scandir("application/modules/") as $p){
    if(file_exists('./application/modules/'.$p.'/controllers/Payment.php')){
        $path = $p;
        $flag = 1;
        //echo $p;
        break;
    }
}
if($flag = 1){
    echo "[+]Your current admin dir is [$path]".PHP_EOL;
    if(count($argv)==1){
        echo "[-]Usage: php change.php Yourdir".PHP_EOL;
        exit();
    }
    $todir = trim($argv[1]);
    if(!preg_match('/^[A-Z][a-z]{3,10}$/',$todir)){
        echo "[-]Your dir format is wrong. Please use [A-Z][a-z]{3,10}".PHP_EOL;
        exit();
    }
    
    echo "[+]We will change [$path] to [$todir]".PHP_EOL;
    if(rename('./application/modules/'.$path,'./application/modules/'.$todir)){
        file_put_contents('./conf/application.ini',preg_replace('/application.modules.*/i', 'application.modules = "Index,Member,Product,'.$todir.',Crontab,Install"', $applicationini));
        file_put_contents('./application/init.php',preg_replace("/.*'ADMIN_DIR.*;/i","define('ADMIN_DIR','$todir');",$init));
        echo "[+]Change [$path] to [$todir] is Success!!".PHP_EOL;
    }else {
        echo "[-]Change [$path] to [$todir] is Failed!!".PHP_EOL;
    }
}else{
    echo "[-] Can't get your admin dir.".PHP_EOL;
}


?>