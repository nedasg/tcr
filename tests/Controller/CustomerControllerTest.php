<?php

namespace App\Tests\Controller;

use App\Entity\Customer;
use App\EntityRepository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CustomerControllerTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void {
        self::bootKernel();
        $this->entityManager = self::$container->get(EntityManagerInterface::class);
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testNotifyCustomer()
    {
        $randomCustomerCode = $this->getRandomCustomerCodeFromDB();
        if (!empty($randomCustomerCode)) {
            $url = "http://172.18.0.7/customers/$randomCustomerCode/messages";
            $data = ['body' => 'notification'];

            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $response = $client->post($url, ['json' => $data]);

            $this->assertEquals(201, $response->getStatusCode());
        }
    }

    private function getRandomCustomerCodeFromDB(): ?string
    {
        /** @var CustomerRepository $customerRepository */
        $customerRepository = $this->entityManager->getRepository(Customer::class);

        $customerCodes = $customerRepository->getAllCustomerCodes();

        return $customerCodes[array_rand($customerCodes)]['customerCode'];
    }

}