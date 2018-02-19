<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\EntityValidationException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $response = new Response();

        $responseContentArray = [
            'type' => (new \ReflectionClass($exception))->getShortName(),
            'message' => $exception->getMessage(),
            'properties' => [],
            'trace' => $exception->getTrace()
        ];

        if ($exception instanceof EntityValidationException) {
            $messages = json_decode($exception->getMessage());
            foreach($messages as $key => $message) {
                $responseContentArray['properties'][$key] = $message;
            }
        }

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else if($exception->getCode() > 200) {
            $response->setStatusCode($exception->getCode());
        } else {
            $response->setStatusCode(400);
        }

        $response->setContent(json_encode($responseContentArray));
        $event->setResponse($response);
    }
}