<?php

namespace App\ApiBundle\Representation;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoriesViewHandler
{
    private $viewHandler;
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, ViewHandlerInterface $viewHandler)
    {
        $this->urlGenerator = $urlGenerator;
        $this->viewHandler = $viewHandler;
    }

    public function handleRepresentation(Categories $representation, array $params = array())
    {
        $links = $this->getNavigationLinks($representation->getData(), $params);

        foreach ($links as $type => $url) {
            $representation->addMeta($type.'_page_link', $url);
        }

        $response = $this->viewHandler->handle(View::create($representation));

        if ($total = $representation->getMeta('total_items')) {
            $response->headers->set('X-Total-Count', $total);
            $response->headers->set('Link', $this->getNavigationLinkHeader($links));
        }

        return $response;
    }

    private function getNavigationLinks(Pagerfanta $pager, array $params = array())
    {
        $page = $pager->getCurrentPage();
        $limit = $pager->getMaxPerPage();

        $links = [];
        if ($pager->getCurrentPage() > 1) {
            $links['first'] = $this->generateUrl('app_api_categories',
                array_merge($params, [
                'offset' => $this->getOffset(1, $limit),
            ]));
        }

        if ($pager->hasPreviousPage()) {
            $links['previous'] = $this->generateUrl('app_api_categories',
                array_merge($params,
                    [
                'offset' => $this->getOffset($pager->getPreviousPage(), $limit),
            ]));
        }

        if ($pager->hasNextPage()) {
            $links['next'] = $this->generateUrl('app_api_categories',
                array_merge($params,
                    [
                'offset' => $this->getOffset($pager->getNextPage(), $limit),
            ]));
        }

        if ($pager->getNbPages() != $page) {
            $links['last'] = $this->generateUrl('app_api_categories',
                array_merge($params,
                    [
                'offset' => $this->getOffset($pager->getNbPages(), $limit),
            ]));
        }

        return $links;
    }

    private function getNavigationLinkHeader(array $links)
    {
        $items = [];
        foreach ($links as $type => $url) {
            $items[] = sprintf('<%s>; rel="%s"', $url, $type);
        }

        return implode(', ', $items);
    }

    private function getOffset($page, $limit)
    {
        return ($page - 1) * $limit;
    }

    private function generateUrl($route, array $params = array())
    {
        return $this->urlGenerator->generate($route, $params, true);
    }
}