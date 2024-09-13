<?php

namespace PHPMaker2023\decryptweb23;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * run_cryptool controller
 */
class RunCryptoolController extends ControllerBase
{
    // custom
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RunCryptool");
    }
}
