<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class IntegerValidator
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        if (filter_var($this->id, FILTER_VALIDATE_INT)) {
            echo "Variable is a integer";
        } else {
            echo "Variable is not a integer";
        }

        return $response;
    }
}
