<?php if ( ! defined('CF_BASEPATH')) exit('Direct script access not allowed');
/*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                       :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         */


   CF_AppRegistry::import('database', 'Connect',CF_BASEPATH);
   CF_AppRegistry::import('database', 'ActiveRecords',CF_BASEPATH);

  class CF_ApplicationModel extends CF_DBConnect
    {
        public static $dbobj = NUll;
       public  function __construct()
        {
                 parent::cnXN();
               // var_dump(parent::get_cnXn_obj('test'));
        }

        public static function get_db_instance()
        {
            if(is_null(self::$dbobj)):
                 parent::cnXN();
                  return $this;
            endif;


       }
         function __destruct()
         {
              //  parent::flushcnXn();
        }
    }
   CF_AppRegistry::import('loader', 'AppLibraryRegistry',CF_BASEPATH);
   require_once CF_BASEPATH.DS.'loader'.DS.'IRegistry'.EXT;

class CF_AppLoader extends CF_AppLibraryRegistry implements IRegistry
{
                      var $directory = NULL;
                      var $view_path = NULL;
                      var $values =array();
                      private $data = array();
                      protected $loaded = NULL;
                      public $model = NULL;
                      private static $name = array();

                     public function __construct($registry = array())
                    {
                            $expires = 60*60*24*14;
                            header("Pragma: public");
                            header("Cache-Control: maxage=".$expires);
                            header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');

                   }

                    //prevent clone.
                    public function __clone(){}


                    public function __get($key)
                    {
                            if (isset($this->data[$key]))
                                return $this->data[$key];
                    }

                    public function __set($key, $value)
                    {
                            $this->data[$key] = $value;
                    }

                    /*
                    * This function is to load requested model file
                    * @param string (model name)
                    *
                    */
                     public function model($model_name)
                      {
                               $this->directory = str_replace('/', '', APPPATH).DS."models".DS;
                                if(is_readable($this->directory.$model_name.EXT)) :
                                                 $this->directory.$model_name.EXT;
                                                 require_once($this->directory.$model_name.EXT);
                                                     $modelname = $this->make_model($model_name);
                                                if (empty($this->data[$model_name]))
                                                         $modelobj = new $modelname();

                                                $this->_set_object($model_name, $modelobj);
                                else:
                                    $callee = debug_backtrace(); //var_dump($callee);
                                    GlobalHelper::display_errors(E_USER_ERROR, 'Error Occurred ','Unable to load requested model  '.$model_name.EXT, $callee[0]['file'],$callee[0]['line'],TRUE );
                                endif;
                     }

                    private function _set_object($key,$value)
                    {
                            $this->data[$key] = $value;

                    }

                     private function make_model($modelname)
                     {
                            if(!empty($modelname))
                                     $std_model_name = ucfirst($modelname).ucfirst(str_replace('/', '',APPPATH)).'Models';
                            return $std_model_name;
                     }

                    function print_gzipped_output()
                    {
                            $HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
                            if(headers_sent())
                                $encoding = false;
                            else if( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false )
                                $encoding = 'x-gzip';
                            else if( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false )
                                $encoding = 'gzip';
                            else
                                $encoding = false;

                            if( $encoding ):
                                    $contents = ob_get_clean();
                                    $_temp = strlen($contents);
                                    if ($_temp < 2048) :   // no need to waste resources in compressing very little data
                                                print($contents);
                                    else:
                                                header('Content-Encoding: '.$encoding);
                                                print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
                                                $contents = gzcompress($contents, 9);
                                                $contents = substr($contents, 0, $_temp);
                                                print($contents);
                                    endif;
                            else:
                                ob_end_flush();
                            endif;
                    }

                    /*
                    * This function is to load requested view file
                    * @param string (view name)
                    *
                    */

                   protected function render($view ="",$arr_values=array(), $ui_content =NULL)
                  {
                        $path= APPPATH.'views/';
                        self::$name[strtolower($view)] = $view;
                        if(is_readable($path.self::$name[$view].'.view'.EXT)) :
                                    ob_start();
                                    if(is_array($arr_values) && !empty($arr_values)):
                                          $this->values =  $arr_values;// extract($arr_values);
                                           $this->view_path = $path.self::$name[$view].'.view'.EXT;
                                          $this->loadview();
                                    else:
                                        var_dump(self::$name[$view]);
                                          echo $this->view_path = $path.self::$name[$view].'.view'.EXT;
                                          return $this;
                                    endif;
                         else:
                              $callee = debug_backtrace();
                             GlobalHelper::display_errors(E_USER_ERROR, 'Error rendering view page ','Unable to load requested file '.$view_name.'.view'.EXT, $callee[0]['file'],$callee[0]['line'],TRUE );
                         endif;
                     }

                     public function with($arr_values=array())
                     {
                            if(is_array($arr_values))
                                      $this->values =  $arr_values;
                            $this->loadview();
                     }

                     private function loadview()
                     {
                            include_once $this->view_path;
                            $this->output_buffer();
                     }

                     private function output_buffer()
                     {
                                 $output=ob_get_contents();
                                            ob_get_clean();

                                if(isset($ui_content) && $ui_content === "ui_contents")
                                        return $output;
                                else
                                       echo $output;
                                 ob_end_flush();
                     }

                     public function request($key)
                    {
                          if(!is_null($key) || $key != ""):
                              try{
                                      CF_AppRegistry::load_lib_class('CF_'.$key);
                              }catch(Exception $ex){
                                    $ex->getMessage();
                              }
                                 return CF_AppRegistry::load($key);
                       else:
                              $callee = debug_backtrace();
                             GlobalHelper::display_errors(E_USER_ERROR, 'Error Occurred',"Unable to load requested library ".$key, $callee[0]['file'],$callee[0]['line'],TRUE );
                       endif;
                    }
                 /*
                *  Load Helper file
                *
                */
                  public   function helper($file_name,$prefix = FRAMEWORK_PREFIX)
                  {
                                $this->directory = CF_BASEPATH.DS."helpers".DS;
                                if(file_exists($this->directory.$prefix.$file_name.EXT) && is_readable($this->directory.$prefix.$file_name.EXT)):
                                         include_once($this->directory.$prefix.$file_name.EXT);
                                else:
                                    $callee = debug_backtrace();
                                    GlobalHelper::display_errors(E_USER_ERROR, 'Error Occurred',"Unable to load requested helper ".$file_name, $callee[0]['file'],$callee[0]['line'],TRUE );
                               endif;
                        unset($file_name);
                }

                public function __destruct()
                {
                        ob_end_flush(); //ob_end_clean();
                        ob_get_flush();
                        unset($this->directory); unset($this->values);unset($this->view_path);
                  }
}