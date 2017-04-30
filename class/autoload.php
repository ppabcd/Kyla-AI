<?php
function autoload($class){
    include __DIR__.DIRECTORY_SEPARATOR.str_replace("\\",DIRECTORY_SEPARATOR,$class).".php";
}
spl_autoload_register('autoload');
