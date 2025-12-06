<?php

namespace App\Controller;

use App\Model\TransactionModel;
use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use Core\View\Render;
use DateTime;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ReportController
{
    public function generateReport(Render $render, Request $request,  Redirect $redirect, TransactionModel $transactionModel)
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(fields: [
            'startDate' => new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Date()
            ]),
            'endDate' =>
            new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Date(),
            ]),
        ], allowMissingFields: false, allowExtraFields: true);
        $violations = $validator->validate($request->request->all(), $constraint);
        $errors = [];
        foreach ($violations as $violation) {

            $field = trim($violation->getPropertyPath(), '[]');
            $errors[$field] = $violation->getMessage();
        }
        if (!empty($errors)) {
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }

        $report = $transactionModel->getReportsByDateRange($request->request->get('startDate'), $request->request->get('endDate'));
        return $redirect->with(['report' => $report, 'input' => $request->request->all()])->back();
        // echo $render->render('report.html.twig');
    }

    public function report(Render $render, Session $session)
    {
        $now = new DateTime();
        $input = [
            'startDate' => $now->format('Y-m-d'),
            'endDate' => $now->format('Y-m-d')
        ];
        array_merge($input, $session->getFlashBag()->get('input', []));
        $errors = $session->getFlashBag()->get('errors', []);
        $report = $session->getFlashBag()->get('report', []);
        echo $render->render('report.html.twig', ['input' => $input, 'report' => $report, 'errors' => $errors]);
    }
}
