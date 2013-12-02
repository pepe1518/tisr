<?php

class Compiler
{
    public static function compile($dir,$filename, $config) {
        $split = explode('.', $filename);
        $extension = array_pop($split);
        $name = implode('.', $split);

        switch ($extension) {
            case 'c':
                return Compiler::compileC($dir,$name, $config);
                break;
            case 'cpp':
                return Compiler::compileCPP($dir,$name, $config);
                break;
            case 'java':
                return Compiler::compileJava($dir,$name, $config);
                break;
        }
    }

    public static function compileC($dir,$name, $config) {
        $command = sprintf($config->c_compiler,
            $config->dir_bin . '/'. $dir.'/' . $name,
            $config->dir_bin . '/' . $dir.'/'. $name,
            $config->dir_bin . '/'. $dir.'/' . $name . '/' . $name,
            $config->dir_problems . '/' . $dir.'/'. $name . '.c'
        );

        return Compiler::exec($command);
    }

    public static function compileCPP($dir,$name, $config) {
        $command = sprintf($config->cpp_compiler,
            $config->dir_bin . '/' . $dir.'/'. $name,
            $config->dir_bin . '/' . $dir.'/'. $name,
            $config->dir_bin . '/'. $dir.'/' . $name . '/' . $name,
            $config->dir_problems . '/'. $dir.'/' . $name . '.cpp'
        );

        return Compiler::exec($command);
    }

    public static function compileJava($dir,$name, $config) {
        $command = sprintf(
             $config->java_compiler,
            $config->dir_bin . '/' . $dir . '/' . $name,
            $config->dir_bin . '/' . $dir . '/' . $name,
            $config->dir_bin . '/' . $dir . '/' . $name,
            $config->dir_problems .'/'. $dir . '/' . $name . '.java'
        );

        return Compiler::exec($command);
    }

    private static function exec($command) {
        ob_start();
        passthru($command, $code);
        $return = ob_get_contents();
        ob_end_clean();

        if ($code == 0) {
            return array(true, $return);
        } else {
            return array(false, $return);
        }
    }
}
