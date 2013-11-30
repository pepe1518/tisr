<?php

class Compiler
{
    public static function compile($filename, $config) {
        $split = explode('.', $filename);
        $extension = array_pop($split);
        $name = implode('.', $split);

        switch ($extension) {
            case 'c':
                return Compiler::compileC($name, $config);
                break;
            case 'cpp':
                return Compiler::compileCPP($name, $config);
                break;
            case 'java':
                return Compiler::compileJava($name, $config);
                break;
        }
    }

    public static function compileC($name, $config) {
        $command = sprintf($config->c_compiler,
            $config->dir_bin . '/' . $name,
            $config->dir_bin . '/' . $name,
            $config->dir_bin . '/' . $name . '/' . $name,
            $config->dir_problems . '/' . $name . '.c'
        );

        return Compiler::exec($command);
    }

    public static function compileCPP($name, $config) {
        $command = sprintf($config->cpp_compiler,
            $config->dir_bin . '/' . $name,
            $config->dir_bin . '/' . $name,
            $config->dir_bin . '/' . $name . '/' . $name,
            $config->dir_problems . '/' . $name . '.cpp'
        );

        return Compiler::exec($command);
    }

    public static function compileJava($name, $config) {
        $command = sprintf(
             $config->java_compiler,
            $config->dir_bin . '/' . $name,
            $config->dir_bin . '/' . $name,
            $config->dir_bin . '/' . $name,
            $config->dir_problems . '/' . $name . '.java'
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
