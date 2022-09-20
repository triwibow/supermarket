<?php
namespace App\Classes;

class RouteConfig {
    public static function getRoute($path, $method="routeWeb")
    {
        try {
            $fullPath = new \RecursiveDirectoryIterator(APPPATH.$path);
            $file = new \RecursiveIteratorIterator($fullPath);
            $allFiles = new \RegexIterator($file, '/\.php$/');
            foreach($allFiles as $pathName => $fileInfo){
                if(!$fileInfo->isFile()) continue;
                $str = "";
                if (substr($pathName, 0, strlen(APPPATH)) == APPPATH) {
                    $str = substr($pathName, strlen(APPPATH));
                }
                if (substr($str, -strlen('.php')) === '.php') {
                    $str = substr($str, 0, -strlen('.php'));
                }

                $str = "App".DIRECTORY_SEPARATOR.str_replace("/", "\\", $str);
                try {
                    $reflection = new \ReflectionClass($str);
                    if($reflection->isAbstract()) continue;
                    $instance = $reflection->newInstanceWithoutConstructor();

                    if(method_exists($instance, $method)){
                        $instance->{$method}();
                    }
                } catch(\Exception $e)
                {
                    
                    throw($e);
                }
            }
        
        } catch(\Exception $e)
        {
            throw($e);
        }
    }
}