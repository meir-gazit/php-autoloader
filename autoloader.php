<?php

function readPath($path)
{
    $filesMap = [];
    $filesMap[$path] = $path;

    if (is_dir($path)) {
    
        $files = scandir($path);

        foreach ($files as $file) {
            if ( $file === '.' || $file === '..' || $file === 'autoloader.php' || $file === 'XXXX.sublime-project' || $file === 'XXXX.sublime-workspace' ) {
                continue;
            }

            $actualPath = $path . DIRECTORY_SEPARATOR . $file;
            $filesMap[$actualPath] = $actualPath;

            if (is_dir($actualPath)) {
            
                $tmp = readPath($actualPath);
                $filesMap = $filesMap + $tmp;
            }
        }
    }
    return $filesMap;
}


spl_autoload_register(function($className){

    $path = dirname(__FILE__);
    $files = readPath($path);

    foreach ($files as $file) {
        if(!empty((pathinfo($file))['extension'])) {
            $fileExtension = (pathinfo($file))['extension'];
            $fileName = (pathinfo($file))['filename'];

            if( ($fileExtension === 'php') && ($fileName === $className) ){
                require_once $file;
            }
        }
    }
});
