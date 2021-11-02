<?php
    header('Content-Type: application/json');
    require_once '../vendor/autoload.php';
    use App\Services\AuthService;

    $apiRoute = explode('/', $_GET['url']);

    // Verifica o endereço da api é válido
    if(isset($apiRoute[1])){
        if($apiRoute[1] == 'address' | $apiRoute[1] == 'creditcard' | $apiRoute[1] == 'pedido'){
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

        } else if ($apiRoute[1] != 'auth'){
            $service = 'App\Services\\'.ucfirst($apiRoute[1]).'Service';
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            if(isset($apiRoute[2])){
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
            $response = call_user_func_array(array(new $service, $method), array($requestData));
            http_response_code(200);
            echo json_encode(array('success' => true, 'data' => $response));
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