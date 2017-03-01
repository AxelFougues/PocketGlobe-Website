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
            post($bdd, $URI, $data);
            break;
        case 'PUT':
            put($bdd, $data);
            break;
        case 'DELETE':
            delete($bdd, $data);
            break;
        default:
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

            if ($json != false) {
                echo $json;
            }
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

            if ($json != false) {
                echo $json;
            }
        }
    }

    function login($bdd, $data) {

        if(isset($data->nickname, $data->password)) {
            $nickname = $data->nickname;
            $password = $data->password;

            $requete = "SELECT * from user where nickname = :nickname and password = :password";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':nickname'=>$nickname, ':password'=>$password));
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            $json = json_encode($user);

            if ($json != false) {
                echo $json;
            }
        }
    }

    function post($bdd, $URI, $data) {

        $func = array_shift($URI);
        switch ($func) {
            case 'login':
                login($bdd, $data);
                break;
            case 'register':
                register($bdd, $data);
                break;
            default:
        }
    }


    function register($bdd, $data) {

        if (isset($data->nickname, $data->mail, $data->password)) {

            $nickname = $data->nickname;
            $mail = $data->mail;
            $password = $data->password;

            $requete = "SELECT count(*) as nb FROM user WHERE nickname = :nickname";
            $requete = $bdd->prepare($requete);
            $requete->execute(array(':nickname' => $nickname));
            $nbNickname = $requete->fetch(PDO::FETCH_ASSOC);

            if($nbNickname['nb'] == 0) {

                $requete = "SELECT count(*) as nb FROM user WHERE mail = :mail";
                $requete = $bdd->prepare($requete);
                $requete->execute(array(':mail' => $mail));
                $nbMail = $requete->fetch(PDO::FETCH_ASSOC);

                if($nbMail['nb'] == 0) {

                    $requete = "INSERT INTO user VALUES ( NULL, :nickname, :mail, :password)";
                    $requete = $bdd->prepare($requete);
                    $requete->execute(array('nickname' => $nickname, 'mail' => $mail, 'password' => $password));

                    echo 'DONE';
                } else {
                    echo 'MAIL';
                }

            } else {
                echo 'NICKNAME';
            }

        }
    }

    function put($bdd, $dataPut) {

        if (isset($dataPut->id_user)) {
            $id_user = $dataPut->id_user;

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
        }
    }

    //A faire après, delete en cascade
    function delete($bdd, $dataDelete) {

        if (isset($dataDelete['id_user'])) {
            $id_user = $dataDelete['id_user'];
            $bdd->exec("DELETE FROM fiche WHERE id_user = $id_user");
            $bdd->exec("DELETE FROM user WHERE id_user = $id_user");
            echo 'DONE';
        }
    }








?>

