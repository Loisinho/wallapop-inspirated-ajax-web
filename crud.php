<?php
header('Content-type: application/json');

require './inc/config.php';

$pdo = Database::getConection();

// TODO: Complete before use.
$url = '';

if (isset($_GET['op']) && $_GET['op'] != '') {
    switch ($_GET['op']) {
        case 0: // Ads search.
            try {
                if (isset($_GET['filter']) && $_GET['filter'] != '') {
                    $stmt = $pdo->prepare("select * from anuncios where titulo like '%".$_GET['filter']."%' order by fecha limit ".$_GET['offset'].",".$_GET['limit']);
                } else if ($_GET['ownads'] == 'true') {
                    $stmt = $pdo->prepare("select * from anuncios where id_usuario = ".$_SESSION['id']." order by fecha limit ".$_GET['offset'].",".$_GET['limit']);
                } else {
                    $stmt = $pdo->prepare("select * from anuncios order by fecha limit ".$_GET['offset'].",".$_GET['limit']);
                }
            
                $stmt->execute();
            
                if ($stmt->rowCount() != 0) {
                    $myAds = $stmt->fetchAll();
                    echo json_encode($myAds);
                } else {
                    if (isset($_GET['filter']) && $_GET['offset'] == 0 && $_GET['ownads'] == 'false')
                        echo '{"status": "Ads not found."}';
                    else if ($_GET['offset'] != 0 || $_GET['ownads'] == 'false')
                        echo '{"status": "No more ads."}';
                    else
                        echo '{"status": "There are no ads yet."}';
                }
            } catch (PDOException $e) {
                echo "SQL Error: ".$e->getMessage();
            }
            break;
        case 1: // User registration.
            $_POST = limpiarFiltrar($_POST);

            if (camposObligatoriosOK(['nick', 'apellidos', 'nombre', 'email', 'password'], 'post')) {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    if (filter_var($_POST['password'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>"/(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/")))) {
                        try {
                            $stmt = $pdo->prepare('select * from usuarios where nick=? or email=?');
                            
                            $stmt->bindParam(1, $_POST['nick']);
                            $stmt->bindParam(2, $_POST['email']);
                            $stmt->execute();
            
                            $user = $stmt->fetch();
                            if($_POST['nick'] != $user['nick']) {
                                if ($_POST['email'] != $user['email']) {
                                    try {
                                        $stmt = $pdo->prepare('insert into usuarios(nick, nombre, apellidos, email, password) values(?, ?, ?, ?, ?)');
                                        
                                        $encriptada = encriptar($_POST['password']);
                        
                                        $stmt->bindParam(1, $_POST['nick']);
                                        $stmt->bindParam(2, $_POST['nombre']);
                                        $stmt->bindParam(3, $_POST['apellidos']);
                                        $stmt->bindParam(4, $_POST['email']);
                                        $stmt->bindParam(5, $encriptada);
                                        $stmt->execute();
                                        
                                        echo '{"nick": "'.$_POST["nick"].'"}';
                                    } catch (PDOException $e) {
                                        echo "SQL Error: ".$e->getMessage();
                                    }
                                } else {
                                    echo '{"status": "USED_EMAIL"}';
                                }
                            } else {
                                echo '{"status": "USED_NICK"}';
                            }
                        } catch (PDOException $e) {
                            echo "SQL Error: ".$e->getMessage();
                        }
                    } else {
                        echo '{"status": "WRONG_PW"}';
                    }
                } else {
                    echo '{"status": "WRONG_EMAIL"}';
                }
            } else {
                echo '{"status": "BLANK"}';
            }
            break;
        case 2: // Log in.
            $_POST = limpiarFiltrar($_POST);

            if (camposObligatoriosOK(['user', 'password'], 'post')) {
                try {
                    $stmt = $pdo->prepare('select * from usuarios where nick=? or email=?');
                    
                    $stmt->bindParam(1, $_POST['user']);
                    $stmt->bindParam(2, $_POST['user']);
                    $stmt->execute();

                    if ($stmt->rowCount() != 0) {
                        $user = $stmt->fetch();
                        if (comprobarPassword($_POST['password'], $user['password'])) {
                            $_SESSION['logedIn'] = true;
                            $_SESSION['id'] = $user['id'];
                            $_SESSION['nick'] = $user['nick'];
                            $_SESSION['nombre'] = $user['nombre'];
                            $_SESSION['apellidos'] = $user['apellidos'];
                            $_SESSION['email'] = $user['email'];
                            $_SESSION['password'] = $user['password'];
                            
                            echo '{"nick": "'.$user["nick"].'"}';
                        } else {
                            echo '{"status": "WRONG"}';
                        }
                    } else {
                        echo '{"status": "WRONG"}';
                    }
                } catch (PDOException $e) {
                    echo "SQL Error: ".$e->getMessage();
                }
            } else {
                echo '{"status": "BLANK"}';
            }
            break;
        case 3: // Log out.
            if ($_SESSION['logedIn'] == true) {
                session_destroy();
                session_start();
                echo '{"status": "OK"}';
            } else {
                echo '{"status": "ERROR"}';
            }
            break;
        case 4: // Make ad.
            $_POST = limpiarFiltrar($_POST);

            if (camposObligatoriosOK(['titulo', 'descripcion'], 'post')) {
                try {
                    $stmt = $pdo->prepare('insert into anuncios (id_usuario, titulo, descripcion, imagen) values (?, ?, ?, ?)');
                    
                    $stmt->bindParam(1, $_SESSION['id']);
                    $stmt->bindParam(2, $_POST['titulo']);
                    $stmt->bindParam(3, $_POST['descripcion']);

                    if (isset($_FILES['img']) && $_FILES['img']['size'] > 0) {
                        $img = subirFichero($config['img'], 'img');
                        if ($img) {
                            $stmt->bindParam(4, $img);
                            $stmt->execute();
    
                            echo '{"status": "OK"}';
                        } else {
                            echo '{"status": "WRONG"}';
                        }
                    } else {
                        $stmt->bindValue(4, null);
                        $stmt->execute();

                        echo '{"status": "OK"}';
                    }
                } catch (PDOException $e) {
                    echo "SQL Error: ".$e->getMessage();
                }
            } else {
                echo '{"status": "BLANK"}';
            }
            break;
        case 5: // Edit ad.
            $_POST = limpiarFiltrar($_POST);

            if (camposObligatoriosOK(['titulo', 'descripcion'], 'post')) {
                try {
                    $stmt = $pdo->prepare("update anuncios set titulo = ?, descripcion = ?, imagen = ? where id = ? and id_usuario = ?");
    
                    $stmt->bindParam(1, $_POST['titulo']);
                    $stmt->bindParam(2, $_POST['descripcion']);
                    $stmt->bindParam(4, $_POST['id']);
                    $stmt->bindParam(5, $_SESSION['id']);
    
                    if (isset($_FILES['img']) && $_FILES['img']['size'] > 0) {
                        $img = subirFichero($config['img'], 'img');
                        if ($img) {
                            if ($_POST['oldimg'] != '')
                                unlink($config['img'].$_POST['oldimg']);
                            $stmt->bindParam(3, $img);
                        }
                    } else {
                        if ($_POST['oldimg'] != '')
                            $stmt->bindParam(3, $_POST['oldimg']);
                        else
                            $stmt->bindValue(3, null);
                    }
    
                    $stmt->execute();
    
                    echo '{"status": "OK"}';
                } catch (PDOException $e) {
                    echo 'SQL Error: '.$e->getMessage();
                }
            } else {
                echo '{"status": "BLANK"}';
            }
            break;
        case 6: // Delete ad.
            if ($_GET['id']) {
                try {
                    $stmt = $pdo->prepare('select * from anuncios where id = ? and id_usuario = ?');

                    $stmt->bindParam(1, $_GET['id']);
                    $stmt->bindParam(2, $_SESSION['id']);

                    $stmt->execute();

                    $ad = $stmt->fetch();

                    if ($ad['imagen'] != '')
                        unlink($config['img'].$ad['imagen']);

                    $stmt = $pdo->prepare('delete from anuncios where id = ? and id_usuario = ?');

                    $stmt->bindParam(1, $_GET['id']);
                    $stmt->bindParam(2, $_SESSION['id']);

                    $stmt->execute();

                    echo '{"status": "OK"}';
                } catch(PDOException $e) {
                    echo "SQL Error: ".$e->getMessage();
                }
            } else {
                echo '{"status": "ERROR"}';
            }
            break;
        case 7: // Password recovery.
            $_POST = limpiarFiltrar($_POST);

            if (camposObligatoriosOK(['email'], 'post') && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                try {
                    $stmt = $pdo->prepare('select * from usuarios where email=? limit 1');
                    
                    $stmt->bindParam(1, $_POST['email']);
                    $stmt->execute();

                    if ($stmt->rowCount() != 0) {
                        $user = $stmt->fetch();
                        $token = md5(time());
                        try {
                            $stmt = $pdo->prepare("update usuarios set token = ? where email = ?");
                            $stmt->bindParam(1, $token);
                            $stmt->bindParam(2, $user['email']);
                            $stmt->execute();

                            $title = 'Wallapush Password Recovery';
                            $msg = '<html>
                                        <head>
                                            <title>Wallapush password recovery</title>
                                        </head>
                                        <body style="text-align: center;">
                                            <h2 style="color: navy;">Wallapush</h2>
                                            <p>Hi <b>'.$user['nick'].'</b>,</p>
                                            <p>You recently requested to reset your password for your account. Use this link below to reset it:</p>
                                            <br>
                                            <form action="'.$url.'" method="post">
                                                <input type="submit" name="reset" value="RESET PASSWORD" style="padding: 10px; background: navy; border-radius: 10px; color: #fff; cursor: pointer;">
                                                <input type="hidden" id="email" name="email" value="'.$user['email'].'">
                                                <input type="hidden" id="token" name="token" value="'.$token.'">
                                            </form>
                                            <br><br>
                                            <p>If you did not request a password reset, please ignore this email or contact us if you have questions.</p>
                                            <br>
                                            <p>Thanks,<br>Wallapush team.</p>
                                        </body>
                                    </html>';
                            $headers  = 'MIME-Version: 1.0'."\r\n";
                            $headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
                            mail($user['email'], $title, $msg, $headers);
    
                            echo '{"status": "OK"}';
                        } catch (PDOException $e) {
                            echo "SQL Error: ".$e->getMessage();
                        }
                    } else {
                        echo '{"status": "NOTEXIST"}'; 
                    }
                } catch (PDOException $e) {
                    echo "SQL Error: ".$e->getMessage();
                }
            } else {
                echo '{"status": "WRONG"}';
            }
            break;
        case 8: // Reset Password.
            $_POST = limpiarFiltrar($_POST);
            
            if (camposObligatoriosOK(['password'], 'post') && filter_var($_POST['password'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>"/(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/")))) {
                try {
                    $stmt = $pdo->prepare('select * from usuarios where email = ? and token = ?');

                    $stmt->bindParam(1, $_POST['email']);
                    $stmt->bindParam(2, $_POST['token']);

                    $stmt->execute();

                    if ($stmt->rowCount() == 1) {
                        $user = $stmt->fetch();
                        $encriptada = encriptar($_POST['password']);
                        try {
                            $stmt = $pdo->prepare('update usuarios set password = ?, token = ? where id = ?');

                            $stmt->bindParam(1, $encriptada);
                            $stmt->bindValue(2, null);
                            $stmt->bindParam(3, $user['id']);
        
                            $stmt->execute();

                            echo '{"status": "OK"}';
                        } catch (PDOException $e) {
                            echo "SQL Error: ".$e->getMessage();
                        }
                    } else {
                        echo '{"status": "ERROR1"}';
                    }    
                } catch(PDOException $e) {
                    echo "SQL Error: ".$e->getMessage();
                }
            } else {
                echo '{"status": "ERROR2"}';
            }
            break;
        case 9: // Delete user & the user ads.
            try {
                $stmt = $pdo->prepare('select * from anuncios where id_usuario = ?');
                $stmt->bindParam(1, $_SESSION['id']);
                $stmt->execute();

                $ads = $stmt->fetchAll();

                $stmt = $pdo->prepare('delete from anuncios where id_usuario = ?');
                $stmt->bindParam(1, $_SESSION['id']);
                $stmt->execute();

                foreach ($ads as &$ad) {
                    if ($ad['imagen'] != '')
                        unlink($config['img'].$ad['imagen']);
                }
    
                $stmt = $pdo->prepare('delete from usuarios where id = ?');
                $stmt->bindParam(1, $_SESSION['id']);
                $stmt->execute();

                session_destroy();
                session_start();
                echo '{"status": "OK"}';
            } catch (PDOException $e) {
                echo "SQL Error: ".$e->getMessage();
            }
            break;
        case '10': // Load ad to edit.
            if (isset($_GET['id']) && $_GET['id'] != '') {
                try {
                    $stmt = $pdo->prepare('select * from anuncios where id = ? and id_usuario = ?');

                    $stmt->bindParam(1, $_GET['id']);
                    $stmt->bindParam(2, $_SESSION['id']);

                    $stmt->execute();

                    if ($stmt->rowCount() == 1) {
                        $ad = $stmt->fetch();
                        echo json_encode($ad);
                    } else {
                        echo '{"status": "NONE"}';
                    }
                } catch (PDOException $e) {
                    echo "SQL Error: ".$e->getMessage();
                }
            }
            break;
    }
}
