<?php

namespace App\Controller;

use Core\View\Render;

class ReportController
{
    public function generateReport(Render $render)
    {
        echo $render->render('report.html.twig');
    }
}
