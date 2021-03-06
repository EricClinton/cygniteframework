<?php
namespace Apps\Components\Authx;

use Cygnite\Database\ActiveRecords;

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
 * @package                          Apps
 * @subpackages                 Authx Components
 * @filename                         AuthxIdentity
 * @description                    This file is used to map all routing of the cygnite framework
 * @author                           Sanjoy Dey
 * @copyright                      Copyright (c) 2013 - 2014,
 * @link	                    http://www.cygniteframework.com
 * @since	                    Version 1.0
 * @filesource
 * @warning                       Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

class Identity extends ActiveRecords
{
    public $userCredentials = array();

    protected $tableName = 'userdetails';

    protected $database = 'cygnite';

    /**
     * Set user details in useCredential array
     *
     */
    public function __construct($credentials = array())
    {
        parent::__construct();
        $this->userCredentials = $credentials;
    }

    public function getDbName()
    {
        return $this->database; // Your database name goes here
    }

    public function getTableName()
    {
        return $this->database;// Your table name goes here
    }

    /*
     * Define your session key and table fields name to store if user authenticated successfully.
     *
     */
    public function getSessionConfig()
    {
        return array(
            'key' => 'validated_user',
            'value' => array('id','username','email')
        );
    }
}