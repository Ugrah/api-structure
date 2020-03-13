<?php
namespace Src\Controller;

use Src\TableGateways\VerificationGateway;

class VerificationController {

    private $db;
    private $requestMethod;
    private $verifID;

    private $verificationGateway;

    public function __construct($db, $requestMethod, $verifID)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->verifID = $verifID;

        $this->verificationGateway = new VerificationGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->verifID) {
                    $response = $this->find($this->verifID);
                } else {
                    $response = $this->findAllVerifications();
                };
                break;
            case 'POST':
                $response = $this->createVerificationFromRequest();
                break;
            case 'PUT':
                $response = $this->updateVerificationFromRequest($this->verifID);
                break;
            case 'DELETE':
                $response = $this->deleteVerification($this->verifID);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function findAllVerifications()
    {
        $result = $this->verificationGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function find($id)
    {
        $result = $this->verificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createVerificationFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateVerification($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->verificationGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = true;
        return $response;
    }

    private function updateVerificationFromRequest($id)
    {
        $result = $this->verificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateVerification($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->verificationGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = true;
        return $response;
    }

    private function deleteVerification($id)
    {
        $result = $this->verificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->verificationGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = true;
        return $response;
    }

    private function validateVerification($input)
    {
        // if ( (!isset($input['phone'])) || (!isset($input['email'])) ) {
        //     return false;
        // }

        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}