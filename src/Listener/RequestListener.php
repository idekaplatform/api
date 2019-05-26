<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->headers->get('Content-Type') === 'application/json') {
            $request->request->replace(json_decode($request->getContent(), true) ?? []);
        }
    }
}