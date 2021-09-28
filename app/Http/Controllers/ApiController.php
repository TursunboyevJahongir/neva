<?php


namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Neva Documentation",
     *      description="L5 Swagger OpenApi description",
     *      @OA\Contact(
     *          email="info@neva.uz"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     * @OA\SecurityScheme(
     *    securityScheme="bearerAuth",
     *    in="header",
     *    name="bearerAuth",
     *    type="http",
     *    scheme="bearer",
     *    bearerFormat="JWT",
     * ),
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Neva API Server"
     * )

     *
     * @OA\Tag(
     *     name="Projects",
     *     description="API Endpoints of Projects"
     * )
     */
    use ApiResponser;
}
