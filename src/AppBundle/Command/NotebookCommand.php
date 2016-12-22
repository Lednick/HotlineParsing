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

            ->setHelp("")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://hotline.ua/computer/noutbuki-netbuki/385943-883-85763-85764-85765/');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $crawler = $crawler->filterXPath('//*[@id="catalogue"]/div[6]/div[2]/div/div/div[2]/div[1]/b/a');
        var_dump($crawler);

        foreach ($crawler as $el) {
            $notebook = new Notebook();
            $notebook ->setImage(str_replace('../', 'http://hotline.ua/', $el->getAttribute('href')));
            $notebook ->setTitle($el->nextSibling->getElementsByTagName('a')->textContent);
            $em->persist($notebook);

        }
        $em->flush();
    }
}
