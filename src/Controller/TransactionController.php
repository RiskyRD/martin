<?php

namespace App\Controller;

use Core\View\Render;


class TransactionController
{
    public function transaction(Render $render)
    {
        echo $render->render('transaction.html.twig');
    }

    public function createTransaction(Render $render)
    {
        echo $render->render('transactionCreate.html.twig');
    }
}
