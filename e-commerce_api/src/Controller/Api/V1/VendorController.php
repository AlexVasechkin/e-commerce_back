<?php

namespace App\Controller\Api\V1;

use App\Entity\Vendor;
use App\Repository\VendorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class VendorController extends AbstractController
{
    /**
     * @Route("/api/v1/private/vendor/create", name="app_api_v1_vendor_create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        VendorRepository $vendorRepository
    ) {
        $rp = $httpRequest->toArray();

        $vendor = (new Vendor())
            ->setName($rp['name'])
        ;

        $vendorRepository->save($vendor, true);

        return $this->json(['id' => $vendor->getId()]);
    }

    /**
     * @Route("/api/v1/private/vendor/update", name="app_api_v1_vendor_update", methods={"POST"})
     */
    public function update(Request $httpRequest, VendorRepository $vendorRepository)
    {
        $rp = $httpRequest->toArray();

        $v = $vendorRepository->findOneBy(['id' => $rp['id']]);

        isset($rp['name']) ? $v->setName($rp['name']) : null;

        $vendorRepository->save($v, true);

        return $this->json([]);
    }

    private function serialize(Vendor $vendor): array
    {
        return [
            'id' => $vendor->getId(),
            'name' => $vendor->getName()
        ];
    }

    /**
     * @Route("/api/v1/private/vendor/{id}", methods={"GET"})
     */
    public function getInstance($id, VendorRepository $vendorRepository)
    {
        $v = $vendorRepository->findOneBy(['id' => $id]);

        if (is_null($v)) {
            throw new NotFoundHttpException('Вендор не найден');
        }

        return $this->json(['payload' => $this->serialize($v)]);
    }

    /**
     * @Route("/api/v1/private/vendors", methods={"POST"})
     */
    public function getList(VendorRepository $vendorRepository)
    {
        return $this->json(['payload' => array_map(function (Vendor $vendor) {
            return $this->serialize($vendor);
        }, $vendorRepository->findAll())]);
    }

    /**
     * @Route("/api/v1/private/vendor/dict")
     */
    public function getVendorDict(
        VendorRepository $vendorRepository
    ) {
        return $this->json(['payload' => array_map(function (Vendor $vendor) {
            return $this->serialize($vendor);
        }, $vendorRepository->findAll())]);
    }

    /**
     * @Route("/api/v1/private/vendor/remove", methods={"POST"})
     */
    public function remove(Request $httpRequest, VendorRepository $vendorRepository)
    {
        $id = $httpRequest->toArray()['id'] ?? 0;

        $v = $vendorRepository->findOneBy(['id' => $id]);

        if (is_null($v)) {
            throw new NotFoundHttpException();
        }

        $vendorRepository->remove($v, true);

        return new Response();
    }
}
