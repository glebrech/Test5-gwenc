<?php
class validLivreController
{

    public function __construct()
    {
        session_start();
        error_reporting(0);
        require_once "metier/Livre.php";
        require_once "Constantes.php";
        require_once "PDO/LivreDB.php";
        require_once "PDO/connectionPDO.php";
        require_once "controller/Controller.php";
        //TODO
        $auteur = $_POST["auteur"] ?? null;
        $information = $_POST["info"] ?? null;
        $edition = $_POST["edition"] ?? null;
        $titre = $_POST["titre"] ?? null;
        $operation = $_GET["operation"] ?? null;
        if (Controller::auth())
            if ($operation == "insert") {
                try {
                    $livreDB = new LivreDB($pdo);
                    $res = $livreDB->ajout(new Livre($titre, $edition, $information, $auteur));
                    if ($res) { ?>
                    <div class="alert alert-sucess d-flex" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="40" height="40" role="img" aria-label="Success:" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                        <div>
                            <h4 class="alert-heading">Livre est bien ajouté dans la BDD</h4>
                            Titre : <?php echo $titre; ?><br>
                            Edition : <?php echo $edition; ?><br>
                            Information : <?php echo $information; ?><br>
                            Auteur : <?php echo $auteur; ?>
                        </div>
                    </div>
<?php };
                } catch (Exception $e) {
                    throw new Exception(Constantes::EXCEPTION_INSERT_DB_LIVRE);
                }
            } else {
                //erreur on renvoit à la page d'accueil
                header('Location: accueil.php?id=' . $_SESSION["token"]);
            }
    }
}
