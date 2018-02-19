<?php

namespace AppBundle\Controller;

abstract class AbstractController extends \FOS\RestBundle\Controller\FOSRestController
{

    protected function response($code, $data = [], $headers = [])
    {
        $view = $this->view($data);

        $response = $this->handleView($view);
        $response->setStatusCode($code);

        foreach ($headers as $headerName => $headerValue) {
            $response->headers->set($headerName, $headerValue);
        }

        return $response;
    }

    protected function responseLimited($code, $data, int $count, int $limit = null, int $page = null, $headers = [])
    {
        $headers['X-Count'] = $count;

        if ($limit !== null) {
            $headers['X-Pagination-Limit'] = $limit;
        }

        if ($page !== null) {
            $headers['X-Pagination-Page'] = $page;
        }


        return $this->response($code, $data, $headers);
    }

    /**
     * @return \AppBundle\Service\MapperService
     */
    protected function getMapperService()
    {
        return $this->get('mapper_service');
    }

    /**
     * @return \AppBundle\Dto\User
     */
    protected function getUserDto() : \AppBundle\Dto\User
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userDto = $this->getMapperService()->convert($user, \AppBundle\Dto\User::class);
        return $userDto;
    }

}