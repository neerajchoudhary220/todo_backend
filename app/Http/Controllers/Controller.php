<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use stdClass;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,ValidatesRequests;
    protected $errorStatus       = 500;
    protected $successStatus     = 200;
    protected $validationStatus  = 400;
    protected $unauthStatus      = 401;
    protected $notFoundStatus    = 404;
    protected $invalidPermission = 403;
    protected $response;
    /**
     * @var array|string
     */
    protected $time_zone;
    protected $path;

    public function __construct()
    {
        $this->response  = new stdClass();
    }
}
