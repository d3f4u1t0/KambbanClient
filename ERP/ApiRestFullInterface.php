<?php

namespace App\Interfaces;

Interface ApiRestFullInterface
{
    public function setClient($endpont, array $headers);
    public function post($url, array $headers, array $auth);
    public function get($url, array $headers, array $auth);
    public function patch($url, array $headers, array $auth);
    public function puth($url, array $headers, array $auth);
    public function delete($url, array $headers, array $auth);
}
