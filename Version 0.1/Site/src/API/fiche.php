<?php

    include "connexion.php";

    $methode = $_SERVER['REQUEST_METHOD'];

    $bdd = connexion();

    $URI = explode('/', trim($_SERVER['PATH_INFO'],'/'));

    $data = array();
    parse_str(file_get_contents('php://input'), $data);

    switch ($methode) {
        case 'GET':
            get($bdd, $URI);
            break;
        case 'POST':
            post($bdd);
            break;
        case 'PUT':
            put($bdd, $data);
            break;
        case 'DELETE':
            delete($bdd, $data);
            break;
        default:
            echo 'DEFAULT';
    }

    function get($bdd, $URI) {
        
        $func = array_shift($URI);
        
        switch ($func) {
            case 'getById':
                getById($bdd, $URI);
                break;
            case 'getByUserId':
                getByUserId($bdd, $URI);
                break;
            case 'getByUserNickname':
                getByUserNickname($bdd, $URI);
                break;
            default:
                echo 'NULL';
        }
    }

    function getById($bdd, $URI) { // Récupérer le tuple d'user
        
        $id_fiche = array_shift($URI);

        if (isset($id_fiche)) { // En fonction de l'id
            
            $requete = $bdd->query("SELECT * FROM fiche WHERE id_fiche = '$id_fiche'");
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            $json = json_encode($user);
            if ($json == 'false') {
                echo 'NULL';
            } else {
                echo $json;
            }
            
        } else { 
            echo 'NULL';
        }
        
    }

    function getByUserId($bdd, $URI) {
        
        $id_user = array_shift($URI);
        
        if (isset($id_user)) { // En fonction du nickname
            
            $requete = $bdd->query("SELECT * FROM fiche WHERE id_user = '$id_user'");
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            $json = json_encode($fiches);
            if ($json == 'false') {
                echo 'NULL';
            } else {
                echo $json;
            }   
            
        } else {
            echo 'NULL';
        }
    }

    function getByUserNickname($bdd, $URI) {
        
        $nickname = array_shift($URI);
        
        if (isset($nickname)) { // En fonction du nickname
            
            $requete = $bdd->query("SELECT id_user FROM user WHERE nickname = '$nickname'");
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            getByUserId($bdd, $user);
            
        } else {
            echo 'NULL';
        }
    }


    function post($bdd) {
        
        if (isset($_POST['id_user'], $_POST['titre'], $_POST['description'])) {
            $id_user = $_POST['id_user'];
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $bdd->exec("INSERT INTO fiche VALUES ( NULL, '$id_user', '$titre', '$description')");
            echo 'DONE';
        } else {
            echo 'NULL';
        }
        
        
    }

    function put($bdd, $dataPut) {

        if (isset($dataPut['id_fiche'])) {
            $id_fiche = $dataPut['id_fiche'];
            foreach ($dataPut as $key => $value) {
                if ($key != 'id_fiche') {
                    $bdd->exec("UPDATE fiche SET $key = '$value' where id_fiche = $id_fiche");
                }
            }
            echo 'DONE';
        } else {
            echo 'NULL';
        }
    }

    function delete($bdd, $dataDelete) {

        if (isset($dataDelete['id_fiche'])) {
            $id_fiche = $dataDelete['id_fiche'];
            $bdd->exec("DELETE FROM fiche WHERE id_fiche = $id_fiche");
        } else {
            echo 'NULL';
        }
    }


























?>