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
            case 'getByNickname':
                getByNickname($bdd, $URI);
                break;
            default:
                echo 'NULL';
        }
    }

    function getById($bdd, $URI) { // Récupérer le tuple d'user
        
        $id = array_shift($URI);


        if (isset($id)) { // En fonction de l'id
            
            $requete = $bdd->query("SELECT * FROM user WHERE id_user = '$id'");
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

    function getByNickname($bdd, $URI) {
        
        $nickname = array_shift($URI);

        if (isset($nickname)) { // En fonction du nickname
            
            $requete = $bdd->query("SELECT * FROM user WHERE nickname = '$nickname'");
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


    function post($bdd) {
        
        if (isset($_POST['nickname'], $_POST['password'])) {
            
            $nickname = $_POST['nickname'];
            $password = $_POST['password'];
            
            $requete = $bdd->query("SELECT count(*) as nb FROM user WHERE nickname = '$nickname'");
            $nb = $requete->fetch(PDO::FETCH_ASSOC);
            
            if ($nb['nb'] == 0) {
                
                $bdd->exec("INSERT INTO user VALUES ( NULL, '$nickname', '$password')");
                echo 'DONE';

            } else {
                echo 'NULL';
            }
            
        } else {
            echo 'NULL';
        }
    }

    function put($bdd, $dataPut) {

        if (isset($dataPut['id_user'])) {
            $id_user = $dataPut['id_user'];
            foreach ($dataPut as $key => $value) {
                if ($key != 'id_user') {
                    $bdd->exec("UPDATE user SET $key = '$value' where id_user = $id_user");
                }
            }
            echo 'DONE';
        } else {
            echo 'NULL';
        }
    }
    
    function delete($bdd, $dataDelete) {

        if (isset($dataDelete['id_user'])) {
            $id_user = $dataDelete['id_user'];
            $bdd->exec("DELETE FROM fiche WHERE id_user = $id_user");
            $bdd->exec("DELETE FROM user WHERE id_user = $id_user");
            echo 'DONE';
        } else {
            echo 'NULL';
        }
    }








?>

