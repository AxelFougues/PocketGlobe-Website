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
            post($bdd, $URI);
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
        
        $id_user = array_shift($URI);


        if (isset($id_user)) { // En fonction de l'id

            $requete = "SELECT * FROM user WHERE id_user = :id_user";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':id_user'=>$id_user));
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            $json = json_encode($user);

            if ($json == false) {
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

            $requete = "SELECT * FROM user WHERE nickname = :nickname";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':nickname'=>$nickname));
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            $json = json_encode($user);

            if ($json == false) {
                echo 'NULL';
            } else {
                echo $json;
            }   
            
        } else {
            echo 'NULL';
        }
    }

    function login($bdd) {

        print_r($_POST);
        if(isset($_POST['nickname'], $_POST['password'])) {
            $nickname = $_POST['nickname'];
            $password = $_POST['password'];

            $requete = "SELECT * from user where nickname = :nickname and password = :password";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':nickname'=>$nickname, ':password'=>$password));
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            $json = json_encode($user);
            if ($json == false) {
                echo 'NULL';
            } else {
                echo $json;
            }

        } else {
            echo 'NULL';
        }
    }

    function post($bdd, $URI) {

        $func = array_shift($URI);
        switch ($func) {
            case 'login':
                login($bdd);
                break;
            default:
                echo 'NULL';
        }
    }

    /*function post($bdd) {

        if (isset($_POST['nickname'], $_POST['mail'], $_POST['password'])) {

            $nickname = $_POST['nickname'];
            $mail = $_POST['mail'];
            $password = $_POST['password'];
            //A REFAIRE NICKNAME PUIS MAIL
            $requete = "SELECT count(*) as nb FROM user WHERE nickname = :nickname or mail = :mail";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':nickname' => $nickname, ':mail'=>$mail));
            $nb = $requete->fetch(PDO::FETCH_ASSOC);
            
            if ($nb['nb'] == 0) {

                $requete = "INSERT INTO user VALUES ( NULL, :nickname, :mail, :password)";
                $requete = $bdd->prepare($requete);
                $requete->execute(array('nickname'=>$nickname, 'mail'=>$mail, 'password'=>$password));

                echo 'DONE';

            } else {
                echo 'NULL';
            }
            
        } else {
            echo 'NULL';
        }
    }*/

    function put($bdd, $dataPut) {

        if (isset($dataPut['id_user'])) {
            $id_user = $dataPut['id_user'];

            foreach ($dataPut as $key => $value) {
                //echo $key;
                if ($key != 'id_user') {
                    echo $key;
                    $requete = "UPDATE user SET $key = :v WHERE id_user = :id_user";
                    $requete = $bdd->prepare($requete);
                    $executed = $requete->execute(array(':v'=>$value, ':id_user'=>$id_user));
                    echo $executed;
                }
            }
            echo 'DONE';
        } else {
            echo 'NULL';
        }
    }

    //A faire après, delete en cascade
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

