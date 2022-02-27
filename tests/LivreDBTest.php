<?php

use PHPUnit\Framework\TestCase;

require_once "Constantes.php";
require_once "metier/Livre.php";
require_once "PDO/LivreDB.php";


class LivreDBTest extends TestCase
{

    /**
     * @var LivreDB
     */
    protected $object;
    protected $pdodb;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        //parametre de connexion à la bae de donnée
        $strConnection = Constantes::TYPE . ':host=' . Constantes::HOST . ';dbname=' . Constantes::BASE;
        $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->pdodb = new PDO($strConnection, Constantes::USER, Constantes::PASSWORD, $arrExtraParam); //Ligne 3; Instancie la connexion
        $this->pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers LivreDB::ajout
     * @todo   Implement testAjout().
     */
    public function testAjout()
    {
        try {
            $this->livre = new LivreDB($this->pdodb);
            $l = new Livre("Le Seigneur des anneaux", "livre de Tolkien", "TOme 1", "JRR Tolkien");
            $this->livre->ajout($l);
            $livre = $this->livre->selectLivre($l->getId());
            $this->assertEquals($l->getAuteur(), $livre->getAuteur());
            $this->assertEquals($l->getInformation(), $livre->getInformation());
            $this->assertEquals($l->getEdition(), $livre->getEdition());
            $this->assertEquals($l->getTitre(), $livre->getTitre());
            $this->livre->suppression($livre);
        } catch (Exception $e) {
            echo 'Exception recue : ', $e->getMessage(), "\n";
        }
    }

    /**
     * @covers LivreDB::update
     * @todo   Implement testUpdate().
     */
    public function testUpdate()
    {
        $this->object = new LivreDB($this->pdodb);
        $l = new Livre("Flaubert", "livre de Flaubert", "Galimard", "titre update");
        $l->setId(58);
        $livre = $this->livre->selectLivre($l->getId());
        $this->assertEquals($l->getAuteur(), $livre->getAuteur());
        $this->assertEquals($l->getInformation(), $livre->getInformation());
        $this->assertEquals($l->getEdition(), $livre->getEdition());
        $this->assertEquals($l->getTitre(), $livre->getTitre());
        $this->livre->suppression($livre);
        $this->object->update($l);
    }

    /**
     * @covers LivreDB::suppression
     * @todo   Implement testSuppression().
     */
    public function testSuppression()
    {
        try {
            $this->livre = new LivreDB($this->pdodb);
            $this->livre->ajout(new Livre("Le Seigneur des anneaux", "livre de Tolkien", "TOme 1", "JRR Tolkien"));
            $livre = $this->livre->selectLivre($this->pdodb->lastInsertId());
            $this->livre->suppression($livre->getId());
            $livre = $this->livre->selectLivre($livre->getId());
            if ($livre != null) {
                $this->markTestIncomplete(
                    "La suppression du livre a échoué"
                );
            }
        } catch (Exception $e) {
            $exception = "Le livre n'est pas présent dans la base de donnée";
            $this->assertEquals($exception, $e->getMessage());
        }
    }

    /**
     * @covers LivreDB::selectAll
     * @todo   Implement testSelectAll().
     */
    public function testSelectAll()
    {
        $this->livre = new LivreDB($this->pdodb);
        $res = $this->livre->selectAll();
        $i = 0;
        $OK = true;
        foreach ($res as $key => $value) {
            $i++;
        }
        if ($i == 0) {
            $this->markTestIncomplete(
                'Aucun résultats'
            );
            $OK = false;
        }
        $this->assertTrue($OK);
    }

    /**
     * @covers LivreDB::selectLivre
     * @todo   Implement testSelectLivre().
     */
    public function testSelectLivre()
    {
        $this->livre = new LivreDB($this->pdodb);
        $livres = new Livre("Le Seigneur des anneaux", "livre de Tolkien", "TOme 1", "JRR Tolkien");
        $this->livre->ajout($livres);
        $livres->setId($this->pdodb->lastInsertId());
        $l = $this->livre->selectLivre($livres->getId());
        $this->assertEquals($l->getId(), $livres->getId());
        $this->assertEquals($l->getAuteur(), $livres->getAuteur());
        $this->assertEquals($l->getInformation(), $livres->getInformation());
        $this->assertEquals($l->getEdition(), $livres->getEdition());
        $this->assertEquals($l->getTitre(), $livres->getTitre());
    }
}
