<?php

/**
 * Created by PhpStorm.
 * User: danny
 * Date: 10/03/2016
 * Time: 22:24
 */
namespace Webservice\ServicesBundle\Services\Users;

class Users
{
    public function test($name)
    {
        return $name;
    }

    public function hello()
    {
        return "coucou, je suis à l'estiam une école d'informatique";
    }

    public function helloChristian($name)
    {
        return "je m'appelle".$name;
    }
}
