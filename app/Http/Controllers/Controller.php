<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @SWG\Swagger(
     *     schemes={"http","https"},
     *     host="localhost:8000",
     *     basePath="/",
     *     @SWG\Info(
     *         version="1.0.0",
     *         title="API for test Supermarket",
     *         description="Supermarket API Documentation",
     *         termsOfService="",
     *         @SWG\Contact(
     *             email="codemax120@gmail.com"
     *         ),
     *         @SWG\License(
     *             name="Private License",
     *             url="127.0.0.1"
     *         )
     *     ),
     * )
     */

}
