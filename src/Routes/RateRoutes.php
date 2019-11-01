<?php
use Slim\App;
use Controllers\RateController;
use Middleware\MWComanda;
return function(App $app)
{
    $app->group('/Rate',function()
    {
        $this->post('/',rateApi::class.':EnviarPuntuacion')->add(MWComanda::class.':MWValidarPuntuaciones')->add(MWComanda::class.':MWValidarMesaRate');
    });
}
?>