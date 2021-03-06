<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

error_reporting(E_ALL ^ E_DEPRECATED);

require_once __DIR__.'/constants.php';

//In order to avoid autoloaded MediaWiki classes complaining
require_once __DIR__.'/../../mediawiki/core/includes/AutoLoader.php';
require_once __DIR__.'/../../mediawiki/core/includes/Defines.php';
require_once __DIR__.'/../../mediawiki/core/includes/DefaultSettings.php';
require_once __DIR__.'/../../mediawiki/core/includes/GlobalFunctions.php';

if (is_file(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
} else {
    require_once __DIR__.'/../../autoload.php';
}

require_once __DIR__.'/../../../LocalSettings.php';

$app = new \Slim\App();
$app->get('{path:.*}', function (Request $request, Response $response) {
    $params = $request->getQueryParams();
    global $config, $wgScript;
    $config = new \ArchiConfig();
    switch ($params['archiAffichage']) {
        case 'adresseDetail':
            $id = intval($params['archiIdAdresse']);
            $a = new \archiAdresse();
            if (isset($params['archiIdEvenementGroupeAdresse'])) {
                $groupId = $params['archiIdEvenementGroupeAdresse'];
            } else {
                $groupId = $a->getIdEvenementGroupeAdresseFromIdAdresse($id);
            }
            $addressInfo = $a->getArrayAdresseFromIdAdresse($id);
            $return = strip_tags(
                $a->getIntituleAdresseFrom(
                    $id,
                    'idAdresse',
                    [
                        'noHTML'                   => true,
                        'noQuartier'               => true,
                        'noSousQuartier'           => true,
                        'noVille'                  => true,
                        'displayFirstTitreAdresse' => true,
                        'setSeparatorAfterTitle'   => '#',
                        'idEvenementGroupeAdresse' => $groupId,
                    ]
                )
            );
            $return = explode('#', $return);
            $name = $return[0].' ('.$addressInfo['nomVille'].')';
            $name = str_replace("l' ", "l'", $name);
            $name = str_replace("d' ", "d'", $name);
            $name = trim($name, '.');

            return $response->withRedirect($wgScript.'Adresse:'.$name, 301);
        case 'evenementListe':
            switch ($params['selection']) {
                case 'personne':
                    $person = new \ArchiPersonne(intval($params['id']));

                    return $response->withRedirect($wgScript.'Personne:'.$person->prenom.' '.$person->nom, 301);
            }
    }
});
$app->run();
