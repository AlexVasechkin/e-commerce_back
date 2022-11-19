<?php

namespace App\Controller\Api\V1;

use App\Entity\Vendor;
use App\Repository\VendorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        $vendorRepository->save($vendor);

        return $this->json(['payload' => ['id' => $vendor->getId()]]);
    }

    /**
     * @Route("/api/v1/private/vendor/update", name="app_api_v1_vendor_update", methods={"POST"})
     */
    public function update(Request $httpRequest, VendorRepository $vendorRepository)
    {
        $rp = $httpRequest->toArray();

        $v = $vendorRepository->findOneBy(['id' => $rp['id']]);

        isset($rp['name']) ? $v->setName($rp['name']) : null;

        $vendorRepository->save($v);

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
     * @Route("/api/v1/private/vendors", methods={"GET"})
     */
    public function getList(VendorRepository $vendorRepository)
    {

    }

    /**
     * @Route("/api/v1/private/vendor/dict")
     */
    public function getVendorDict(
        VendorRepository $vendorRepository
    ) {
        return $this->json(['paylaod' => array_map(function (Vendor $vendor) {
            return $this->serialize($vendor);
        }, $vendorRepository->findAll())]);
    }
}
