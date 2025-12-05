<?php

namespace App\Controller;

use Core\View\Render;

class ReportController
{
    public function generateReport(Render $render)
    {
        echo $render->render('report.html.twig');
    }

    public function report(Render $render)
    {
        echo $render->render('reportGenerate.html.twig');
    }

    public function reportDetails(Render $render)
    {
        echo $render->render('reportDetails.html.twig');
    }
}
