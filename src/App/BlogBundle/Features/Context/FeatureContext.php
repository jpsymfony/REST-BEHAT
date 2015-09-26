<?php

namespace App\BlogBundle\Features\Context;

use App\CoreBundle\Features\Context\CoreContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends CoreContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }
}
