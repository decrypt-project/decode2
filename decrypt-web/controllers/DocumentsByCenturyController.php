<?php

namespace PHPMaker2023\decryptweb23;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Documents_by_century controller
 */
class DocumentsByCenturyController extends ControllerBase
{
    // crosstab
    public function crosstab(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentsByCenturyCrosstab");
    }

    // Documentsbycentury
    public function Documentsbycentury(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "DocumentsByCenturyCrosstab", "Documentsbycentury");
    }
}
