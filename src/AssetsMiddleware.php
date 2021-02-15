<?php


namespace Brace\Assets;


use Brace\Core\Base\BraceAbstractMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AssetsMiddleware extends BraceAbstractMiddleware
{


    /**
     * AssetMiddleware constructor.
     * @param string[] $assetRoutes
     */
    public function __construct(
        private array $assetRoutes
    )
    {}


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        foreach ($this->assetRoutes as $assetRoute) {
            if (str_starts_with($request->getUri()->getPath(), $assetRoute)) {
                return $this->app->assets->handle($request);

            }
        }
        return $handler->handle($request);
    }
}