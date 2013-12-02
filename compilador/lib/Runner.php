<?php

class Runner
{
    public static function run($dir,$filename, $config) {
        $split = explode('.', $filename);
        $extension = array_pop($split);
        $name = implode('.', $split);

        switch ($extension) {
            case 'c':
                return Runner::runC($dir,$name, $config);
                break;
            case 'cpp':
                return Runner::runCPP($dir,$name, $config);
                break;
            case 'java':
                return Runner::runJava($dir,$name, $config);
                break;
        }
    }

    public static function runC($dir,$name, $config) {
        $command = sprintf($config->run_script,
            $config->timeout_script,
            $config->time_limit,
            $config->memory_limit,
            $config->dir_bin . '/' . $name . '/' . $name,
            $config->dir_input . '/' . $name . '.in',
            $config->dir_bin . '/' . $name . '/' . $name . '.out',
            $config->dir_bin . '/' . $name . '/' . $name . '.time',
            $config->dir_bin . '/' . $name . '/' . $name . '.err.1',
            $config->dir_bin . '/' . $name . '/' . $name . '.err.2'
        );

        return Runner::exec($command);
    }
    
public static function runJava($dir,$name, $config) {
 $command = sprintf($config->run_script,
            $config->timeout_script,
            $config->time_limit,
            $config->memory_limit,
            $config->dir_bin . '/' . $name . '/' . $name,
            $config->dir_input . '/' . $name . '.in',
            $config->dir_bin . '/' . $name . '/' . $name . '.out',
            $config->dir_bin . '/' . $name . '/' . $name . '.time',
            $config->dir_bin . '/' . $name . '/' . $name . '.err.1',
            $config->dir_bin . '/' . $name . '/' . $name . '.err.2'
        );
           $var=exec("java /opt/lampp/htdocs/tisr/compilador/data/bin/e/e < /opt/lampp/htdocs/tisr/compilador/data/input/e.in");
    
           exec("java /opt/lampp/htdocs/tisr/compilador/data/bin/e/e < /opt/lampp/htdocs/tisr/compilador/data/input/e.in", $output);
           print_r($output);
           
	      var_dump($var);
      //  var_dump($command);

        
        
        
       
        return Runner::exec($command);
    }
    
    
    

    public static function runCPP($name, $config) {
        return Runner::runC($name, $config);
    }

    private static function exec($command) {
        ob_start();
    // var_dump( shell_exec($command)); 
      passthru($command, $code);
        $return = ob_get_contents();
        
        ob_end_clean();

        if ($code == 0) {
            return array(true, $return, $code);
        } else {
            return array(false, $return, $code);
        }
    }
}
