<?php

/*
 * This file is part of the LatestProduct
 *
 * Copyright (C) 2018 StringTech Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\LatestProduct\Controller\Block;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

class LatestProductController
{

    /**
     * LatestProductBlock画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {

        $builder = $app['form.factory']->createNamedBuilder('', 'search_product');
        $searchForm = $builder->getForm();

        $searchForm["orderby"] = 2;
        $searchForm['disp_number'] = 8;

        $searchForm->handleRequest($request);

        $searchData = $searchForm->getData();

        $qb = $app['eccube.repository.product']->getQueryBuilderBySearchData($searchData);

        $pagination = $app['paginator']()->paginate(
            $qb,
            1,
            12
        );

        return $app['view']->render('Block\latest_product.twig', array(
            'Products' => $pagination,
        ));
    }

}
