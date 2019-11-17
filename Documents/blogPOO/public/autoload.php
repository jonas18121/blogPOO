<?php

function autoload($class){

    if(substr($class, -4) == 'Form') 
    {
        $filename = '../src/HTML/' . $class . '.php';
    }
    elseif(substr($class, -6) == 'Router')
    {
        $filename = '../src/router/' . $class . '.php';
    }
    elseif(substr($class, -9) == 'Validator')
    {
        $filename = '../src/' . $class . '.php';
    }
    elseif(substr($class, -4) == 'Text')
    {
        $filename = '../src/Helpers/' . $class . '.php';
    }
    elseif(substr($class, -5) == 'Table')
    {
        $filename = '../src/table/' . $class . '.php';
    }
    elseif(substr($class, -17) == 'NotFoundException')
    {
        $filename = '../src/table/Exception/' . $class . '.php';
    }
    elseif(substr($class, -3) == 'URL')
    {
        $filename = '../src/' . $class . '.php';
    }
    elseif(substr($class, -4) == 'Auth')
    {
        $filename = '../src/' . $class . '.php';
    }
    elseif(substr($class, -5) == 'Query')
    {
        $filename = '../src/' . $class . '.php';
    }
    elseif(substr($class, -17) == 'SecurityException')
    {
        $filename = '../src/security/' . $class . '.php';
    }
    elseif(substr($class, -5) == 'Model')
    {
        $filename = '../src/Model/' . $class . '.php';
    }
    elseif(substr($class, -8) == 'Database')
    {
        $filename = '../src/config/' . $class . '.php';
    }
    else
    {
        throw new Exception('le chemin est pas bon');
    }
    
    if(file_exists($filename) == true)
    {
        require $filename;
    }
    else
    {
        throw new Exception
        (
            "La classe {$class} ne se trouve pas ".
            "dans le fichier {$filename} ."
        );
    }
}
