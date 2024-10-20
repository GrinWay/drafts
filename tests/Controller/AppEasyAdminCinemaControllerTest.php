<?php

namespace App\Tests\Controller;

use App\Entity\AppEasyAdminCinema;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AppEasyAdminCinemaControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/app/easy/admin/cinema/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(AppEasyAdminCinema::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AppEasyAdminCinema index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'app_easy_admin_cinema[name]' => 'Testing',
            'app_easy_admin_cinema[description]' => 'Testing',
            'app_easy_admin_cinema[duration]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new AppEasyAdminCinema();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDuration('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AppEasyAdminCinema');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new AppEasyAdminCinema();
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setDuration('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'app_easy_admin_cinema[name]' => 'Something New',
            'app_easy_admin_cinema[description]' => 'Something New',
            'app_easy_admin_cinema[duration]' => 'Something New',
        ]);

        self::assertResponseRedirects('/app/easy/admin/cinema/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDuration());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new AppEasyAdminCinema();
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setDuration('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/app/easy/admin/cinema/');
        self::assertSame(0, $this->repository->count([]));
    }
}
