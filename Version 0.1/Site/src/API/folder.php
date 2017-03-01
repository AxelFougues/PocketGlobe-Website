<?php
/**
 * Created by PhpStorm.
 * User: Guibe
 * Date: 19/02/2017
 * Time: 14:59
 */


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
            delete($bdd, $data);
            break;
    }


    function get($bdd, $URI) {

        $id_user = array_shift($URI);
        $param = array_shift($URI);

        switch ($param) {
            case 'getByUserId':
                getByUserId($bdd, $id_user);
                break;
            case 'getByFolderId':
                getByFolderId($bdd, $id_user, $URI);
                break;
            case 'getByFolderName':
                getByFolderName($bdd, $id_user, $URI);
                break;
            default:
        }
    }

    function getByUserId($bdd, $id_user) {

        if(isset($id_user)) {

            $requete = "SELECT * FROM folder WHERE id_user = :id_user";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_user'=>$id_user));
            $folders = $requete->fetchAll(PDO::FETCH_ASSOC);

            if (count($folders) > 0) {
                $json = json_encode($folders);
                echo $json;
            }
        }
    }

    function getByFolderId($bdd, $id_user, $URI) {

        $id_folder = array_shift($URI);

        if(isset($id_folder)) {

            $requete = "SELECT * FROM folder WHERE id_folder = :id_folder and id_user = :id_user";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_folder'=>$id_folder, ':id_user'=>$id_user));
            $folder = $requete->fetch(PDO::FETCH_ASSOC);

            $json = json_encode($folder);
            if($json) {
                echo $json;
            }
        }
    }

    function getByFolderName($bdd, $id_user, $URI) {

        $folder_name = array_shift($URI);

        if(isset($folder_name)) {

            $requete = "SELECT * FROM folder WHERE name_folder = :folder_name and id_user = :id_user";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':folder_name'=>$folder_name, ':id_user'=>$id_user));
            $folder = $requete->fetch(PDO::FETCH_ASSOC);

            $json = json_encode($folder);
            if($json) {
                echo $json;
            }
        }
    }

    function post($bdd, $data) {

        if(isset($data->id_user, $data->name_folder, $data->defaut)) {

            $id_user = $data->id_user;
            $name_folder = $data->name_folder;
            $defaut = $data->defaut;

            $requete = "SELECT count(*) as nb FROM folder WHERE name_folder = :name_folder and id_user = :id_user";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':name_folder'=>$name_folder, ':id_user'=>$id_user));
            $nb = $requete->fetch(PDO::FETCH_ASSOC);

            if($nb['nb'] == 0) {
                $requete = "INSERT INTO folder VALUES (null, :id_user, :name_folder, :defaut)";
                $requete = $bdd->prepare($requete);
                $res = $requete->execute(array(':id_user'=>$id_user, ':name_folder'=>$name_folder, ':defaut'=>$defaut));

                if($res)
                    getByFolderName($bdd, $id_user, array('name_folder'=>$name_folder));
            } else {
                echo 'NAME';
            }

        }
    }































?>