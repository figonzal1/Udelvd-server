<?php /** @noinspection ForgottenDebugOutputInspection */


use Kreait\Firebase\DynamicLink\AndroidInfo;
use Kreait\Firebase\DynamicLink\CreateDynamicLink;
use Kreait\Firebase\DynamicLink\CreateDynamicLink\FailedToCreateDynamicLink;
use Kreait\Firebase\Factory;

function crearDynamicLink(): bool|string
{
    $factory = (new Factory)->withServiceAccount('../udelvd-server-credentials.json');

    $dynamicLinksDomain = 'https://undiaenlavidade.page.link';
    $dynamicLinks = $factory->createDynamicLinksService($dynamicLinksDomain);

    try {

        $action = CreateDynamicLink::forUrl($dynamicLinksDomain)
            ->withUnguessableSuffix()
            ->withAndroidInfo(
                AndroidInfo::new()
                    ->withFallbackLink('https://undiaenlavidade.cl')
                    ->withPackageName('cl.udelvd')
            );

        $link = $dynamicLinks->createDynamicLink($action);

        $uriString = (string)$link;
        error_log("Create dynamic link:" . $uriString);
        return $uriString;
    } catch (FailedToCreateDynamicLink $e) {
        error_log("Failed to create dynamic link:" . $e->getMessage());
        return false;
    }
}
