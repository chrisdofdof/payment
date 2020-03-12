<?php


namespace Source\Controllers;


interface Controller
{
    function parse($response);

    function execute($request);
}
