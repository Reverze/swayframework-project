<?php

namespace Yuzi\Controller;

use Sway\Component\Controller\Controller;
use Sway\Component\Http\Request;
use Sway\Component\Http\Response;

class ExampleController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response("Hello world!");
    }
}

?>
