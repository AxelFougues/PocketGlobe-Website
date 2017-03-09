<?php
define("SERVEUR","localhost");
define("USER","root");
define("MDP","");
define("BD","pocketglobe");

function connexion($hote = SERVEUR, $username = USER, $mdp = MDP, $bd = BD)
{

    try
    {
        $bdd = new PDO('mysql:host='.$hote.';dbname='.$bd.';charset=utf8', $username, $mdp);

        return $bdd;
    }
    catch(Exception $e)
    {
        echo('Erreur : '.$e->getMessage());
        return null;
    }
}

?>