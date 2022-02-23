<?php
require_once "Constantes.php";
require_once "metier/Livre.php";
require_once "MediathequeDB.php";

class LivreDB extends MediathequeDB
{
	private $db; // Instance de PDO
	public $lastId;
	//TODO implementer les fonctions
	public function __construct($db)
	{
		$this->db=$db;
	}
	/**
	 * 
	 * fonction d'Insertion de l'objet Livre en base de donnee
	 * @param Livre $l
	 */
	public function ajout(Livre $l)
	{
		$q = $this->db->prepare('INSERT INTO livre(auteur,information,edition,titre) values(:auteur,:information,:edition,:titre)');

		$q->bindValue(':auteur', $l->getAuteur());
		$q->bindValue(':information', $l->getInformation());
		$q->bindValue(':edition', $l->getEdition());
		$q->bindValue(':titre', $l->getTitre());
		return $q->execute();
		$q->closeCursor();
		$q = NULL;
	}
/**
	 * 
	 * fonction d'update de l'objet Livre en base de donnee
	 * @param Livre $l
	 */
	public function update(Livre $l)
	{
		try {
			$q = $this->db->prepare('UPDATE livre set auteur=:auteur,information=:information,edition=:edition,titre=:titre where id=:id');

			$q->bindValue(':auteur', $l->getAuteur());
			$q->bindValue(':information', $l->getInformation());
			$q->bindValue(':edition', $l->getEdition());
			$q->bindValue(':titre', $l->getTitre());
			$q->bindValue(':id', $l->getId());
			$q->execute();
			$q->closeCursor();
			$q = NULL;
		} catch (Exception $e) {
		  throw new Exception(Constantes::EXCEPTION_DB_LIVRE_UP);
		}
		
	}
    /**
     * 
     * fonction de Suppression de l'objet Livre
     * @param Livre $l
     */
	public function suppression($id){
		$q = $this->db->prepare('delete from livre where id=:id');
		$q->bindValue(':id', $id->getId());
		$res = $q->execute();

		$q->closeCursor();
		$q = NULL;
		return $res;
	}
/**
	 * 
	 * Fonction qui retourne toutes les livres
	 * @throws Exception
	 */
	public function selectAll(){

		$query = 'SELECT  auteur,information,edition,titre FROM livre';
		$q = $this->db->prepare($query);
		$q->execute();

		$arrAll = $q->fetchAll(PDO::FETCH_ASSOC);

		//si pas de livre , on leve une exception
		if (empty($arrAll)) {
			throw new Exception(Constantes::EXCEPTION_DB_LIVRE);
		}

		$result = $arrAll;
		//Clore la requ�te pr�par�e
		$q->closeCursor();
		$q = NULL;
		//retour du resultat
		return $result;
	}
public function selectLivre($id){
	try{
		$query = 'SELECT auteur,information,edition,titre FROM livre  WHERE id= :id ';
		$q = $this->db->prepare($query);


		$q->bindValue(':id', $id);

		$q->execute();

		$arrAll = $q->fetch(PDO::FETCH_ASSOC);
		//si pas d'e personne'adresse , on leve une exception

		if (empty($arrAll)) {
			throw new Exception(Constantes::EXCEPTION_DB_LIVRE);
		}

		$result = $arrAll;

		$q->closeCursor();
		$q = NULL;
		//conversion du resultat de la requete en objet livre
		$res = $this->convertPdoLiv($result);
		//retour du resultat
		return $res;
	}catch (Exception $e){
		throw new Exception(Constantes::EXCEPTION_DB_LIVRE . $e); 
	}
	}
        /**
	 * 
	 * Fonction qui convertie un PDO Livre en objet Livre
	 * @param $pdoLivr
	 * @throws Exception
	 */
	public function convertPdoLiv($pdoLivr){
	if(empty($pdoLivr)){
		throw new Exception(Constantes::EXCEPTION_DB_CONVERT_LIVR);
	}
	try{
	//conversion du pdo en objet
	$obj = (object)$pdoLivr;
			$i = (int)$obj->id;
			$t = (string)$obj->titre;
			$e = (string) $obj->edition;
			$info = (string) $obj->information;
			$a = (string) $obj->auteur;

			//conversion de l'objet en objet livre
			$livre = new Livre($i, $t, $e, $info, $a);
			//affectation de l'id pers
			$livre->setId($obj->id);
			return $livre;
	}catch(Exception $e){
		throw new Exception(Constantes::EXCEPTION_DB_CONVERT_LIVR.$e);
	}
	}
}