<?php


namespace Brace\Assets;


use Brace\Core\Base\BraceAbstractMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AssetsMiddleware extends BraceAbstractMiddleware
{

    private $assetRoutes = [];

    /**
     * AssetMiddleware constructor.
     * @param string[] $assetRoutes
     */
    public function __construct(array $assetRoutes)
    {
        $this->assetRoutes = $assetRoutes;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        foreach ($this->assetRoutes as $assetRoute) {
            if (startsWith($request->getUri()->getPath(), $assetRoute)) {
                $this->app->assets->handle($request);
            }
        }
    }
}