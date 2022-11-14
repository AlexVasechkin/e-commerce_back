<?php

namespace App\Controller\Api\V1;

use App\Application\Contracts\CreateVendorInterface;
use App\Application\Contracts\UpdateVendorInterface;
use App\Application\Repository\Vendor\DTO\CreateVendorRequest;
use App\Application\Repository\Vendor\DTO\UpdateVendorRequest;
use App\Domain\ValueObjects\StringFixedLength;
use App\Entity\Vendor;
use App\Repository\VendorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class VendorController extends AbstractController
{
    /**
     * @Route("/api/v1/private/vendor/create", name="app_api_v1_vendor_create", methods={"POST"})
     */
    public function create(
        Request $httpRequest,
        CreateVendorInterface $createVendorAction,
        NormalizerInterface $normalizer
    ) {
        try {
            $requestData = $httpRequest->toArray();

            $code = $requestData['code'] ?? null;
            $name = $requestData['name'] ?? null;

            $response = $createVendorAction->create(new CreateVendorRequest(
                (new StringFixedLength($code, 'code: expected string length[1, 128].', 1, 128))->getValue(),
                (new StringFixedLength($name, 'name: expected string length[1, 255].', 1, 255))->getValue()
            ));

            return $this->json([
                'success' => true,
                'errorText' => null,
                'payload' => $normalizer->normalize($response->getVendor())
            ]);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'errorText' => $e->getMessage(),
                'payload' => null
            ]);
        }
    }

    /**
     * @Route("/api/v1/private/vendor/update", name="app_api_v1_vendor_update", methods={"POST"})
     */
    public function update(
        Request $httpRequest,
        UpdateVendorInterface $updateVendorAction,
        NormalizerInterface $normalizer
    ) {
        try {
            $requestData = $httpRequest->toArray();

            $response = $updateVendorAction->update(
                (new UpdateVendorRequest($requestData['id'] ?? null))
                    ->setCode(isset($requestData['code']) ? (new StringFixedLength($requestData['code'],
                        'code: expected string length[1, 128]', 1, 128)) : null)
                    ->setName(isset($requestData['name']) ? (new StringFixedLength($requestData['name'] ?? null,
                        'name: expected string length[1, 255]', 1, 255)) : null)
            );

            return $this->json([
                'success' => true,
                'errorText' => null,
                'payload' => $normalizer->normalize($response->getVendor())
            ]);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'errorText' => $e->getMessage(),
                'payload' => null
            ]);
        }
    }

    /**
     * @param VendorRepository $vendorRepository
     * @param NormalizerInterface $normalizer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/api/v1/private/vendor/dict")
     */
    public function getVendorDict(
        VendorRepository $vendorRepository,
        NormalizerInterface $normalizer
    ) {
        $d = array_map(function (Vendor $vendor) use ($normalizer) {
            return $normalizer->normalize($vendor, null, ['groups' => 'api_dict']);
        }, $vendorRepository->findAll());

        return $this->json([
            'payload' => $d
        ], 200, ['Access-Control-Allow-Origin' => '*']);
    }
}
