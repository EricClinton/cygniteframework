<?php
namespace Cygnite;

/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                   :  Packages
 * @SubPackages               :  Cygnite
 * @Filename                  :  CF_Loader
 * @Description               :  This is the base loader of controller.
 *                               Controllers extends all base functionality from this BaseController.
 * @Author                    :  Cygnite Dev Team
 * @Copyright                 :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.cygniteframework.com
 * @Since	                  :  Version 1.0
 * @FileSource
 * @Warning                   :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

abstract class BaseController extends CFView
{

    public static $instance;

    /**
     * Constructor function
     *
     * @access    public
     * @return \Cygnite\BaseController class object
     */
    public function __construct()
    {
        parent::__construct();
    }

    //prevent clone.
    public function __clone()
    {

    }

    /**
     * Magic Method for handling errors.
     *
     */
    public function __call($method, $arguments)
    {
        throw new \Exception("Undefined method [$method] called by ".get_class($this).' Controller');
    }

    public static function getCygniteInstance()
    {

    }
}