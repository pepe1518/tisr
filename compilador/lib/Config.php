<?php

defined('APP_PATH')
    || define('APP_PATH', realpath(dirname(__FILE__) . '/..'));

class Config {

 public $c_compiler =<<<BEGIN
if [ ! -d "%s" ]; then
    mkdir %s;
fi
gcc -lm -o %s %s > /dev/stdout 2> /dev/stdout
BEGIN;

   public $cpp_compiler =<<<BEGIN
if [ ! -d "%s" ]; then
    mkdir %s;
fi
g++ -lm -o %s %s > /dev/stdout 2> /dev/stdout
BEGIN;

    public $java_compiler =<<<BEGIN
if [ ! -d "%s" ]; then
    mkdir %s;
fi
javac -d %s %s > /dev/stdout 2> /dev/stdout
BEGIN;

    public $time_limit = '2'; // in seconds
    public $memory_limit = '1024'; // in kbytes

    public $run_script = '/lib/run.sh %s %s %s %s %s %s %s %s %s';
    public $timeout_script = '/lib/timeout/timeout';

//    -i, --ignore-case
//           ignore case differences in file contents
//    -E, --ignore-tab-expansion
//           ignore changes due to tab expansion
//    -Z, --ignore-trailing-space
//           ignore white space at line end
//    -b, --ignore-space-change
//           ignore changes in the amount of white space
//    -w, --ignore-all-space
//           ignore all white space
//    -B, --ignore-blank-lines
//           ignore changes whose lines are all blank
    public $diff_command = 'diff -qiEZbwB %s %s';

    public $dir_bin = '/data/bin';
    public $dir_input = '/data/input';
    public $dir_output = '/data/output';
    public $dir_problems = '/data/problems';

    public function __construct() {
        $this->run_script = APP_PATH . $this->run_script;
        $this->timeout_script = APP_PATH . $this->timeout_script;

        $this->dir_bin = APP_PATH . $this->dir_bin;
        $this->dir_input = APP_PATH . $this->dir_input;
        $this->dir_output = APP_PATH . $this->dir_output;
        $this->dir_problems = APP_PATH . $this->dir_problems;
    }
}
