<?php

    include "connexion.php";

    $methode = $_SERVER['REQUEST_METHOD'];

    $bdd = connexion();

    if(isset($_SERVER['PATH_INFO'])) {
        $URI = explode('/', trim($_SERVER['PATH_INFO'], '/'));
    } else {
        $URI = array();
    }

    $data = json_decode(file_get_contents('php://input'));


    switch ($methode) {
        case 'GET':
            get($bdd, $URI);
            break;
        case 'POST':
            post($bdd, $data);
            break;
        case 'PUT':
            put($bdd, $data);
            break;
        case 'DELETE':
            delete($bdd, $URI, $data);
            break;
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
            case 'getByFolderIdAndTitle':
                getByFolderIdAndTitle($bdd, $URI);
            default:
        }
    }

    function getById($bdd, $URI) {
        
        $id_fiche = array_shift($URI);

        if (isset($id_fiche)) {

            $requete = "SELECT * FROM fiche WHERE id_fiche = :id_fiche";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_fiche'=>$id_fiche));
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            $json = json_encode($user);
            if ($json) {
                echo $json;
            }
            
        }
        
    }

    function getByUserId($bdd, $URI) {
        
        $id_user = array_shift($URI);
        
        if (isset($id_user)) {

            $requete = "SELECT f.* FROM fiche f, folder fo WHERE fo.id_folder = f.id_folder and fo.id_user = :id_user";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_user'=>$id_user));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);

            if (count($fiches) > 0) {
                $json = json_encode($fiches);
                echo $json;
            }
        }
    }

    function getByUserNickname($bdd, $URI) {
        
        $nickname = array_shift($URI);
        
        if (isset($nickname)) { // En fonction du nickname

            $requete = "SELECT f.* FROM fiche f, folder fo, user u WHERE f.id_folder = fo.id_folder and u.id_user = fo.id_user and u.nickname = :nickname";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':nickname'=>$nickname));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            if (count($fiches) != 0) {
                $json = json_encode($fiches);
                echo $json;
            }
        }
    }

    function getByFolderId($bdd, $URI) {

        $id_folder = array_shift($URI);

        if(isset($id_folder)) {

            $requete = "SELECT * FROM fiche f WHERE id_folder = :id_folder";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_folder'=>$id_folder));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            if (count($fiches) != 0) {
                $json = json_encode($fiches);
                echo $json;
            }
        }
    }

    function getByFolderName($bdd, $URI) {

        $name_folder = array_shift($URI);

        if(isset($name_folder)) {

            $requete = "SELECT f.* FROM folder fo, fiche f WHERE fo.id_folder = f.id_folder and fo.name_folder = :name_folder";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':name_folder'=>$name_folder));
            $fiches = $requete->fetchAll(PDO::FETCH_ASSOC);
            if (count($fiches) != 0) {
                $json = json_encode($fiches);
                echo $json;
            }
        }
    }

    function getByFolderIdAndTitle($bdd, $data) {

        $id_folder = array_shift($data);
        $title = array_shift($data);

        if(isset($id_folder, $title)) {

            $requete = "SELECT * FROM fiche WHERE id_folder = :id_folder and title = :title";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_folder'=>$id_folder, ':title'=>$title));

            $fiche = $requete->fetch(PDO::FETCH_ASSOC);
            $json = json_encode($fiche);
            if ($json) {
                echo $json;
            }

        }
    }

    function post($bdd, $data) {

        if (isset($data->title, $data->id_folder, $data->visited)) {
            $title = $data->title;
            $id_folder = $data->id_folder;
            $visited = $data->visited;
            $latitude = NULL;
            $longitude = NULL;

            if(isset($data->latitude, $data->longitude)) {
                $latitude = $data->latitude;
                $longitude = $data->longitude;
            }

            $requete = "SELECT count(*) as nb FROM fiche WHERE id_folder = :id_folder and title = :title";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_folder'=>$id_folder, ':title'=>$title));
            $nb = $requete->fetch(PDO::FETCH_ASSOC);

            if($nb['nb'] == 0) {
                $requete = "INSERT INTO fiche VALUES ( NULL, :id_folder, :title, :visited, :latitude, :longitude)";
                $requete = $bdd->prepare($requete);
                $res = $requete->execute(array(':id_folder' => $id_folder, ':title' => $title, ':visited' => $visited, ':latitude' => $latitude, ':longitude' => $longitude));
                if ($res)
                    getByFolderIdAndTitle($bdd, array('id_folder'=>$id_folder, 'title'=>$title));
            } else {
                echo 'TITLE';
            }
        }
    }

    function put($bdd, $data) {

        $success = true;
        if (isset($data->id_fiche)) {

            $id_fiche = $data->id_fiche;

            $requete = "UPDATE fiche SET ";
            $index = 0;
            foreach ($data as $key => $value) {
                if ($key != 'id_fiche') {
                    if($index == 0)
                        $requete .= "$key = '$value'";
                    else
                        $requete .= ", $key = '$value'";
                    $index++;
                }
            }
            $requete .= " WHERE id_fiche = :id_fiche";
            $requete = $bdd->prepare($requete);
            $res = $requete->execute(array(':id_fiche'=>$id_fiche));
            if($res)
                echo 'EDITED';
        }
    }

    function delete($bdd, $URI, $data) {

        $param = array_shift($URI);

        switch ($param) {
            case 'deleteFiche':
                deleteFiche($bdd, $data);
                break;
            case 'deleteFiches':
                deleteFiches($bdd, $data);
                break;
        }
    }

    function deleteFiche($bdd, $data) {
        if (isset($data->id_fiche)) {
            $id_fiche = $data->id_fiche;
            $requete = "DELETE FROM fiche WHERE id_fiche = :id_fiche";
            $requete = $bdd->prepare($requete);
            $res = $requete->execute(array(':id_fiche'=>$id_fiche));
            if($res) {
                echo 'DELETED';
            }
        }
    }

    function deleteFiches($bdd, $data) {

    }


























?>