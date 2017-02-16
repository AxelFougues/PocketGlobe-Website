<?php

    include "connexion.php";

    $methode = $_SERVER['REQUEST_METHOD'];

    $bdd = connexion();

    if(isset($_SERVER['PATH_INFO'])) {
        $URI = explode('/', trim($_SERVER['PATH_INFO'], '/'));
    } else {
        $URI = array();
    }

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
            case 'getByFolderId':
                getByFolderId($bdd, $URI);
                break;
            case 'getByFolderName':
                getByFolderName($bdd, $URI);
                break;
            default:
                echo 'NULL';
        }
    }

    function getById($bdd, $URI) { // Récupérer le tuple d'user
        
        $id_fiche = array_shift($URI);

        if (isset($id_fiche)) { // En fonction de l'id

            $requete = "SELECT * FROM fiche WHERE id_fiche = :id_fiche";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_fiche'=>$id_fiche));
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

            $requete = "SELECT * FROM fiche WHERE id_user = :id_user";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_user'=>$id_user));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            if (count($fiches) == 0) {
                echo 'NULL';
            } else {
                $json = json_encode($fiches);
                echo $json;
            }   
            
        } else {
            echo 'NULL';
        }
    }

    function getByUserNickname($bdd, $URI) {
        
        $nickname = array_shift($URI);
        
        if (isset($nickname)) { // En fonction du nickname

            $requete = "SELECT f.* FROM fiche f, user u WHERE u.id_user = f.id_user and u.nickname = :nickname";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':nickname'=>$nickname));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            if (count($fiches) == 0) {
                echo 'NULL';
            } else {
                $json = json_encode($fiches);
                echo $json;
            }
        } else {
            echo 'NULL';
        }
    }

    function getByFolderId($bdd, $URI) {

        $id_folder = array_shift($URI);

        if(isset($id_folder)) {

            $requete = "SELECT * FROM fiche f WHERE id_folder = :id_folder";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_folder'=>$id_folder));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            if (count($fiches) == 0) {
                echo 'NULL';
            } else {
                $json = json_encode($fiches);
                echo $json;
            }
        } else {
            echo 'NULL';
        }
    }

    function getByFolderName($bdd, $URI) {

        $name_folder = array_shift($URI);

        if(isset($name_folder)) {

            $requete = "SELECT f.* FROM folder fo, fiche f WHERE fo.id_folder = f.id_folder and fo.name_folder = :name_folder";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':name_folder'=>$name_folder));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            if (count($fiches) == 0) {
                echo 'NULL';
            } else {
                $json = json_encode($fiches);
                echo $json;
            }
        } else {
            echo 'NULL';
        }
    }



    function post($bdd) {

        if (isset($_POST['id_user'], $_POST['title'], $_POST['id_folder'], $_POST['visited'])) {
            $id_user = $_POST['id_user'];
            $title = $_POST['title'];
            $id_folder = $_POST['id_folder'];
            $visited = $_POST['visited'];
            $latitude = NULL;
            $longitude = NULL;

            if(isset($_POST['latitude'], $_POST['longitude'])) {
                $latitude = $_POST['latitude'];
                $longitude = $_POST['longitude'];
            }

            $requete = "INSERT INTO fiche VALUES ( NULL, :id_user, :id_folder, :title, :visited, :latitude, :longitude)";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_user'=>$id_user, ':id_folder'=>$id_folder, ':title'=>$title, ':visited'=>$visited, ':latitude'=>$latitude, ':longitude'=>$longitude));
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
                    $requete = "UPDATE fiche SET $key = :v where id_fiche = :id_fiche";
                    $requete = $bdd->prepare($requete);
                    $requete->execute(array(':v'=>$value, ':id_fiche'=>$id_fiche));
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