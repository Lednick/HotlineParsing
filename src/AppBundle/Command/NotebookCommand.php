<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;
use AppBundle\Entity\Notebook;

class NotebookCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('parse:notebooks')
            ->setDescription('Parses notebooks from Hotline.ua')
            ->setHelp("");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $em = $this->getContainer()->get('doctrine')->getManager();
        $count = 0;
        for ($page = 0; $page++ < 11;) {
            $crawler = $client->request('GET', 'http://hotline.ua/computer/noutbuki-netbuki/385943-883-85763-85764-85765/?p=' . $page);
            $crawler_result = $crawler->filter('img.max-120');
            $crawler_image = $crawler->filter('div.gd-price-cell')->filter('div.text-14.text-13-480.orng');

            foreach ($crawler_result as $key => $el) {
                if (null === $crawler_image->getNode($key)) {
                    continue;
                } else {
                    $notebook = new Notebook();
                    $notebook->setImage('http://hotline.ua' . $el->getAttribute('src'));
                    $notebook->setTitle($el->getAttribute('alt'));
                    $notebook->setPrice($crawler_image->getNode($key)->nodeValue);
                    $em->persist($notebook);
                    $count++;
                }
            }
        }
        $em->flush();
        $output->writeln([
            'Count of added records:' . $count,
        ]);
    }
}
