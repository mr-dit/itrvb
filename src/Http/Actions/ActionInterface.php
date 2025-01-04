<?php

namespace App\myHttp\Actions;

use App\myHttp\Request;
use App\myHttp\Response;


interface ActionInterface
{
    public function handle(Request $request): Response;
}
