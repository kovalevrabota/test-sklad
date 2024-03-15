<?php
namespace App\Controllers;

use \Core\View;
use \Libraries\Moysklad\Moysklad;

/**
 * Class Authorization
 * @package App\Controllers
 */
class Authorization extends \Core\Controller
{
    /**
     * Show auth page
     *
     * @return void
     */
    public function authAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = array('result' => false);
            
            if(empty($_POST['login']) || empty($_POST['password'])) {
                $data['message'] = 'Заполнены не все поля';
            }else {
                $password = base64_encode($_POST['login'] . ':' . $_POST['password']);

                $checkAuth = Moysklad::checkLogin($password);

                if(empty($checkAuth['errors'])) {
                    //Записываем сессию
                    $_SESSION['login'] = $_POST['login'];
                    $_SESSION['password'] = $password;

                    $data['result'] = true;
                }else {
                    $data['message'] = 'Ошибка доступа'; 
                }
            }

            echo json_encode($data); exit;
        }

        View::render('Authorization/index.php');
    }

    /**
     * Logging out
     */
    public function outAction()
    {
        unset($_SESSION['login']);
        unset($_SESSION['password']);

        header('Location: /login');
        exit;
    }

    /**
     * Check auth
     *
     * @return void
     */
    public static function checkAuth()
    {
        return !empty($_SESSION['login']) ? $_SESSION['login'] : false;
    }
}