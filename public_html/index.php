<?php
    header('Content-Type: application/json');
    require_once '../vendor/autoload.php';
    use App\Services\AuthService;

    $apiRoute = explode('/', $_GET['url']);

    // Verifica se o endereço esta chamando a API
    if(isset($apiRoute[1])){

        // Caso a rota seja uma restrita ao usuário, a JWT é verificada
        if($apiRoute[1] == 'address' | $apiRoute[1] == 'pedido' | $apiRoute[1] == 'cupom'){
            if(AuthService::check()){
                $service = 'App\Services\\'.ucfirst($apiRoute[1]).'Service';
                $method = strtolower($_SERVER['REQUEST_METHOD']);
                if(isset($apiRoute[2])){
                    $requestData = array($apiRoute[2], AuthService::getEmail());
                } else {
                    $requestData = null;
                }
            } else {
                http_response_code(401); //Unauthorized
                exit;
            }
        // Caso não seja
        } else if ($apiRoute[1] != 'auth'){
            $service = 'App\Services\\'.ucfirst($apiRoute[1]).'Service';
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            if(isset($apiRoute[2]) && isset($apiRoute[3])){
                $requestData = array($apiRoute[2], $apiRoute[3]);
            } else if (isset($apiRoute[2])){
                $requestData = array($apiRoute[2]);
            } else {
                $requestData = null;
            }
        } else {
            $service = 'App\Services\AuthService';
            $method = $apiRoute[2];
            $requestData = null;
        }

        try{
            // Chama a classe e função, com os parâmetros que o usuário inseriu (GET)
            $response = call_user_func_array(array(new $service, $method), array($requestData));
            http_response_code(200);
            echo json_encode($response);
            exit;
          } catch (\Exception $e) {
              http_response_code(404);
              echo json_encode(array('success' => false, 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
              exit;
          }   
    } else {
        http_response_code(400); //BadRequest
    }
?>