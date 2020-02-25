<?php
namespace Src\Controller;

use Src\TableGateways\PhoneVerificationGateway;

class PhoneVerificationController {

    private $db;
    private $requestMethod;
    private $phoneVerifId;

    private $phoneVerificationGateway;

    public function __construct($db, $requestMethod, $phoneVerifId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->phoneVerifId = $phoneVerifId;

        $this->phoneVerificationGateway = new PhoneVerificationGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->phoneVerifId) {
                    $response = $this->getPhoneVerification($this->phoneVerifId);
                } else {
                    $response = $this->getAllPhoneVerifications();
                };
                break;
            case 'POST':
                $response = $this->createPhoneVerificationFromRequest();
                break;
            case 'PUT':
                $response = $this->updatePhoneVerificationFromRequest($this->phoneVerifId);
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->phoneVerifId);
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

    private function getAllPhoneVerifications()
    {
        $result = $this->phoneVerificationGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getPhoneVerification($id)
    {
        $result = $this->phoneVerificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createPhoneVerificationFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validatePerson($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->phoneVerificationGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = true;
        return $response;
    }

    private function updatePhoneVerificationFromRequest($id)
    {
        $result = $this->phoneVerificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validatePerson($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->phoneVerificationGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = true;
        return $response;
    }

    private function deletePhoneVerification($id)
    {
        $result = $this->phoneVerificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->phoneVerificationGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = true;
        return $response;
    }

    private function validatePerson($input)
    {
        // if (! isset($input['firstname'])) {
        //     return false;
        // }
        // if (! isset($input['lastname'])) {
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