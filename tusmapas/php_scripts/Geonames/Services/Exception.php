<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of the PEAR Services_GeoNames package.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT license that is available
 * through the world-wide-web at the following URI:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Services
 * @package   Services_GeoNames
 * @author    David Jean Louis <izi@php.net>
 * @copyright 2008-2009 David Jean Louis
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @version   CVS: $Id: Exception.php 274374 2009-01-23 14:21:59Z izi $
 * @link      http://pear.php.net/package/Services_GeoNames
 * @link      http://www.geonames.org/export/webservice-exception.html
 * @since     File available since release 0.1.0
 * @filesource
 */

/**
 * Include the PEAR_Exception class.
 */
include $_SERVER["DOCUMENT_ROOT"]."/php_scripts".'/PEAR/Exception.php';

/**
 * Base class for exceptions raised by the Services_GeoNames package.
 *
 * @category  Services
 * @package   Services_GeoNames
 * @author    David Jean Louis <izi@php.net>
 * @copyright 2008-2009 David Jean Louis
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @version   Release: 1.0.1
 * @link      http://pear.php.net/package/Services_GeoNames
 * @link      http://www.geonames.org/export/webservice-exception.html
 * @since     Class available since release 0.1.0
 */
class Services_GeoNames_Exception extends PEAR_Exception
{
}

/**
 * Class for HTTP exceptions raised by the Services_GeoNames package.
 * This specific exception allows the user to retrieve the error response
 * returned by the server, for example:
 *
 * <code>
 * try {
 *     $geonames = new Services_GeoNames();
 *     $geonames->someMethod();
 * } catch (Services_GeoNames_HTTPException $exc) {
 *     // HTTP error...
 *     $response = $exc->response;
 * } catch (Services_GeoNames_Exception $exc) {
 *     // API error handling ...
 * }
 * </code>
 *
 * @category  Services
 * @package   Services_GeoNames
 * @author    David Jean Louis <izi@php.net>
 * @copyright 2008-2009 David Jean Louis
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @version   Release: 1.0.1
 * @link      http://pear.php.net/package/Services_GeoNames
 * @link      http://www.geonames.org/export/webservice-exception.html
 * @since     Class available since release 0.1.0
 */
class Services_GeoNames_HTTPException extends Services_GeoNames_Exception
{
    // properties {{{

    /**
     * HTTP_Request2_Response instance.
     *
     * @var HTTP_Request2_Response $response
     */
    public $response;

    // }}}
    // __construct() {{{

    /**
     * Constructor.
     *
     * @param string                 $msg  The exception message
     * @param int|Exception          $p2   Exception code or cause
     * @param HTTP_Request2_Response $resp Optional request response
     *
     * @return void
     */
    public function __construct($msg, $p2 = null, $resp = null)
    {
        parent::__construct($msg, $p2);
        $this->response = $resp;
    }

    // }}}
}
