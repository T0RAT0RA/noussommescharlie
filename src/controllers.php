<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Entity\Charlie;

$app->get('/', function () use ($app) {
    $em     = $app['orm.em'];
    $query  = $em->createQuery("SELECT COUNT(c.id) FROM Entity\Charlie c");
    $total_charlies = $query->getSingleScalarResult();

    return $app['twig']->render('index.html', array(
        'total_charlies' => $total_charlies,
        'display_button' => $app['session']->get('positionAdded')? false : true,
    ));
});

$app->get('/getCharlies', function () use ($app) {
    $em = $app['orm.em'];
    $charlies = $em->getRepository('Entity\Charlie')->findAll();

    return $app->json($charlies);
});

$app->post('/addPosition', function (Request $request) use ($app) {
    $em         = $app['orm.em'];
    $latitude   = $request->get('lat');
    $longitude  = $request->get('lon');
    $success    = true;
    $message    = '';

    if (!$latitude || !$longitude) {
        $success = false;
        $message = 'Missing parameter.';
    }

    if ($app['session']->get('positionAdded')) {
        $success = false;
        $message = 'Already set position.';
    }

    $charlie = new Charlie($latitude, $longitude);

    if ($success) {
        try {
            $em->persist($charlie);
            $em->flush();
            $app['session']->set('positionAdded', true);
         } catch(\Exception $e) {
            $success = false;
         };
    }

    return $app->json([
        'success'   => $success,
        'message'   => $message,
        'latitude'  => $charlie->getLatitude(),
        'longitude' => $charlie->getLongitude(),
    ]);
});