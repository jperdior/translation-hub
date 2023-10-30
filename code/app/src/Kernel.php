<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            return '/tmp/sfcache/'.$this->environment;
        }

        return parent::getCacheDir();
    }
}
