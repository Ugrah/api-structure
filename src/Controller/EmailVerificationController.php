<?php
namespace Src\Controller;

use Src\TableGateways\EmailVerificationGateway;

class EmailVerificationController {

    private $db;
    private $requestMethod;
    private $emailVerifId;

    private $emailVerificationGateway;

    public function __construct($db, $requestMethod, $emailVerifId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->emailVerifId = $emailVerifId;

        $this->emailVerificationGateway = new EmailVerificationGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->emailVerifId) {
                    $response = $this->getEmailVerification($this->emailVerifId);
                } else {
                    $response = $this->getAllEmailVerifications();
                };
                break;
            case 'POST':
                $response = $this->createEmailVerificationFromRequest();
                break;
            case 'PUT':
                $response = $this->updateEmailVerificationFromRequest($this->emailVerifId);
                break;
            case 'DELETE':
                $response = $this->deleteEmailVerification($this->emailVerifId);
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

    private function getAllEmailVerifications()
    {
        $result = $this->emailVerificationGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getEmailVerification($id)
    {
        $result = $this->emailVerificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createEmailVerificationFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateEmailVerification($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->emailVerificationGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = true;
        return $response;
    }

    private function updateEmailVerificationFromRequest($id)
    {
        $result = $this->emailVerificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateEmailVerification($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->emailVerificationGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = true;
        return $response;
    }

    private function deleteEmailVerification($id)
    {
        $result = $this->emailVerificationGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->emailVerificationGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = true;
        return $response;
    }

    private function validateEmailVerification($input)
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