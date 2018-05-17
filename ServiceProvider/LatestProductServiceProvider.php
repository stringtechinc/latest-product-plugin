<?php

/*
 * This file is part of the LatestProduct
 *
 * Copyright (C) 2018 StringTech Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\LatestProduct\ServiceProvider;

use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class LatestProductServiceProvider implements ServiceProviderInterface
{

    public function register(BaseApplication $app)
    {
        $app->match(
            '/block/latest_product',
            'Plugin\LatestProduct\Controller\Block\LatestProductController::index'
        )->bind('block_latest_product');
    }

    public function boot(BaseApplication $app)
    {
    }

}
