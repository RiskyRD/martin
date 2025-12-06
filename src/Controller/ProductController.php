<?php

namespace App\Controller;

use App\Model\ProductModel;
use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use Core\View\Render;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;


class ProductController
{
    public function listProducts(Render $render, ProductModel $productModel)
    {
        $products = $productModel->getAllProducts();
        echo $render->render('product.html.twig', ['products' => $products]);
    }

    public function addProduct(Request $request, Redirect $redirect, ProductModel $productModel)
    {
        $validator = Validation::createValidator();
        $fields = [];
        $fields['name'] = $request->request->get('name');
        $fields['stock'] = (int) $request->request->get('stock');
        $fields['price'] = (int) $request->request->get('price');

        $constraint = new Assert\Collection(fields: [
            'name' => new Assert\Sequentially([
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ]),
            'stock' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\PositiveOrZero(),
            ]),
            'price' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
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

        $productModel->saveProduct($fields);
        return $redirect->to('/products');
    }

    public function createProductView(Render $render, Session $session)
    {
        $errors = $session->getFlashBag()->get('errors', []);
        $input = $session->getFlashBag()->get('input', []);
        echo $render->render('productCreate.html.twig', ['errors' => $errors, 'input' => $input]);
    }

    public function updateProductView(Render $render, Request $request, ProductModel $productModel, Redirect $redirect, Session $session)
    {
        $id = $request->getParam('id');
        $product = $productModel->getProductById($id);
        if (!$product) {
            return $redirect->to('/notFound');
        }
        $input = $session->getFlashBag()->get('input', []);
        $errors = $session->getFlashBag()->get('errors', []);
        $product = array_replace($product, $input);
        echo $render->render('productUpdate.html.twig', ['input' => $product, 'errors' => $errors, 'id' => $id]);
    }

    public function updateProduct(Request $request, Redirect $redirect, ProductModel $productModel)
    {
        $id = $request->getParam('id');
        $product = $productModel->getProductById($id);
        if (!$product) {
            return $redirect->to('/notFound');
        }

        $validator = Validation::createValidator();
        $fields = [];
        $fields['name'] = $request->request->get('name');
        $fields['stock'] = (int) $request->request->get('stock');
        $fields['price'] = (int) $request->request->get('price');

        $constraint = new Assert\Collection(fields: [
            'name' => new Assert\Sequentially([
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ]),
            'stock' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\PositiveOrZero(),
            ]),
            'price' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
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

        $productModel->updateProduct((int)$id, $fields);
        return $redirect->to('/products');
    }

    public function deleteProduct(Request $request, Redirect $redirect, ProductModel $productModel)
    {
        $id = $request->getParam('id');
        $product = $productModel->getProductById($id);

        if (!$product) {
            return $redirect->to('/notFound');
        }

        $productModel->deleteProduct((int)$id);
        return $redirect->to('/products');
    }
}
