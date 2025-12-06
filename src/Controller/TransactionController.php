<?php

namespace App\Controller;

use App\Model\ProductModel;
use App\Model\TransactionModel;
use App\Validator\ProductExists;
use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use Core\View\Render;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotNull;

class TransactionController
{
    public function __construct() {}
    public function transaction(Render $render, TransactionModel $transactionModel)
    {
        $transactions = $transactionModel->getAllTransactions();
        echo $render->render('transaction.html.twig', ['transactions' => $transactions]);
    }

    public function createTransactionView(Render $render, Session $session)
    {
        $input = $session->getFlashBag()->get('input', []);
        $errors = $session->getFlashBag()->get('errors', []);
        echo $render->render('transactionCreate.html.twig', ['input' => $input, 'errors' => $errors, 'transactionDetails' => []]);
    }

    public function createTransaction(TransactionModel $transactionModel, Request $request,  Redirect $redirect, ProductModel $productModel)
    {

        $validator = Validation::createValidator();
        $fields = [];
        $fields['id'] = (int) $request->request->get('id');
        $fields['amount'] = (int) $request->request->get('amount') ?: null;

        $constraint = new Assert\Collection(fields: [
            'id' => new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\NotBlank(),
                new ProductExists()
            ]),
            'amount' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\PositiveOrZero(),
                new NotNull()
            ]),

        ], allowMissingFields: false, allowExtraFields: true);
        $violations = $validator->validate($fields, $constraint);
        $errors = [];
        foreach ($violations as $violation) {

            $field = trim($violation->getPropertyPath(), '[]');
            $errors[$field] = $violation->getMessage();
        }
        if (!empty($errors)) {
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }

        $transactionId = $transactionModel->newTransaction();
        $product = $productModel->getProductById($fields['id']);
        if ($product['stock'] < $fields['amount']) {
            $errors['amount'] = 'Insufficient stock available.';
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }
        $productModel->updateProductStock($fields['id'], $product['stock'] - $fields['amount']);

        $transactionModel->addItemToTransaction($transactionId, $fields);

        return $redirect->to('/transaction/' . $transactionId . '/create');
        // return header("Location: /transaction/{$transactionId}/details");
    }

    public function createTransactionWithIdView(Render $render, Redirect $redirect, Request $request, Session $session, TransactionModel $transactionModel)
    {
        $id = (int) $request->getParam('id');
        if ($transactionModel->getTransactionById($id) === null) {
            return $redirect->to('/notFound');
        }
        $errors = $session->getFlashBag()->get('errors', []);
        $input = $session->getFlashBag()->get('input', []);
        echo $render->render('transactionCreateWithId.html.twig', ['id' => $id, 'input' => $input, 'errors' => $errors, 'transactionDetails' => $transactionModel->getTransactionDetailsByTransactionId($id)]);
    }

    public function createTransactionWithId(TransactionModel $transactionModel, Request $request,  Redirect $redirect, ProductModel $productModel)
    {
        $id = (int) $request->getParam('id');
        if ($transactionModel->getTransactionById($id) === null) {
            return $redirect->to('/notFound');
        }

        $validator = Validation::createValidator();
        $fields = [];
        $fields['id'] = (int) $request->request->get('id');
        $fields['amount'] = (int) $request->request->get('amount') ?: null;

        $constraint = new Assert\Collection(fields: [
            'id' => new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\NotBlank(),
                new ProductExists()
            ]),
            'amount' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\NotNull(),
                new Assert\PositiveOrZero(),
            ]),

        ], allowMissingFields: false, allowExtraFields: true);
        $violations = $validator->validate($fields, $constraint);
        $errors = [];
        foreach ($violations as $violation) {

            $field = trim($violation->getPropertyPath(), '[]');
            $errors[$field] = $violation->getMessage();
        }
        if (!empty($errors)) {
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }

        $product = $productModel->getProductById($fields['id']);
        if ($product['stock'] < $fields['amount']) {
            $errors['amount'] = 'Insufficient stock available.';
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }
        $productModel->updateProductStock($fields['id'], $product['stock'] - $fields['amount']);

        $transactionModel->addItemToTransaction($id, $fields);

        return $redirect->to('/transaction/' . $id . '/create');
        // return header("Location: /transaction/{$transactionId}/details");
    }

    public function deleteTransactionDetails(Request $request, TransactionModel $transactionModel, Redirect $redirect, ProductModel $productModel)
    {
        $id = (int) $request->getParam('id');
        $transaction = $transactionModel->getTransactionDetailsById($id);
        if ($transaction == null) {
            return $redirect->to('/notFound');
        }
        $productModel->updateProductStock(
            $transaction['product_id'],
            $productModel->getProductById($transaction['product_id'])['stock'] + $transaction['amount']
        );
        $transactionModel->deleteTransactionDetailsById($id);
        return $redirect->back();
    }
}
