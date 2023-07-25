<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\Product\FetchProductsAction;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class OrderController extends AbstractController
{
    /**
     * @Route("/api/v1/public/fast-order/create", methods={"POST"})
     */
    public function createFastOrder(
        Request $httpRequest,
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        OrderProductRepository $orderProductRepository
    ) {

        $rp = $httpRequest->toArray();

        if (   !is_string($rp['name'] ?? null)
            || (mb_strlen($rp['name'], 'UTF-8') < 4)
        ) {
            throw new \Exception('имя: ожидалась строка не менее 4 символов');
        }

        if (   (($rp['phone'] ?? '') === '')
            && (($rp['email'] ?? '') === '')
        ) {
            throw new \Exception('необходимо указать телефон или email');
        }

        $productData = $rp['product'] ?? null;

        $product = $productRepository->findOneByIdOrFail($productData['id']);

        $order = $orderRepository->save(
            (new Order())
                ->setName($rp['name'])
                ->setPhone($rp['phone'] ?? '')
                ->setEmail($rp['email'] ?? '')
                ->setStatus(Order::ORDER_STATUS_NEW)
        , true);

        $i = 0;
        while ($i < $productData['count']) {

            $orderProductRepository->save(
                (new OrderProduct())
                    ->setOrder($order)
                    ->setProduct($product)
                    ->setPrice($product->getPrice())
            , $i === ($productData['count'] - 1));

            $i++;
        }

        return $this->json([
            'id' => $order->getId()
        ]);
    }

    /**
     * @Route("/api/v1/public/order/{id}", methods={"GET"})
     */
    public function getInstance(
        $id,
        OrderRepository $orderRepository,
        FetchProductsAction $fetchProductsAction
    ) {
        $order = $orderRepository->findOneBy(['id' => Ulid::fromBase32($id)]);

        $productIdList = array_unique($order->getOrderProducts()->map(fn(OrderProduct $orderProduct) => $orderProduct->getProduct()->getId())->toArray());

        $counts = [];
        foreach ($order->getOrderProducts()->toArray() as $orderProduct) {
            $counts[$orderProduct->getProduct()->getId()] = ($counts[$orderProduct->getProduct()->getId()] ?? 0) + 1;
        }

        $products = $fetchProductsAction->execute($productIdList);

        foreach ($products as &$product) {
            $product['count'] = $counts[$product['id']] ?? 0;
        }

        return $this->json([
            'id' => $order->getId(),
            'products' => $products
        ]);
    }
}
