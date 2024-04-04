<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AboutController extends AbstractActionController
{
    public function aboutAction()
    {
        return new ViewModel();
    }
}
