<?php

namespace App\Helpers;

class HttpRequestResponse
{
    const response_ok = 200;
    const response_created = 201;
    const response_accepted = 202;
    const response_bad_request = 400;
    const response_unauthorized = 401;
    const response_forbidden = 403;
    const response_not_found = 404;
    const response_internal_server_error = 500;

    public function getResponseOk() {
        return self::response_ok;
    }

    public function getResponseCreated() {
        return self::response_created;
    }

    public function getResponseAccepted() {
        return self::response_accepted;
    }

    public function getResponseBadRequest() {
        return self::response_bad_request;
    }

    public function getResponseForbidden() {
        return self::response_forbidden;
    }

    public function getResponseNotFound() {
        return self::response_not_found;
    }

    public function getResponseInternalServerError() {
        return self::response_internal_server_error;
    }

    public function getResponseUnauthorized() {
        return self::response_unauthorized;
    }
}


