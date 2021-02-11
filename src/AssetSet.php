<?php


namespace Brace\Assets;


use Brace\Core\BraceApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AssetSet implements RequestHandlerInterface
{

    /**
     * @var VirtualAsset[]
     */
    private $assets = [];

    /**
     * @var BraceApp
     */
    private $app;

    public function __construct (BraceApp $app)
    {
        $this->app = $app;
    }

    public function virtual(string $route, string $contentType = null) : VirtualAsset
    {
        if ( ! isset ($this->assets[$route])) {
            $this->assets[$route] = new VirtualAsset($this->app);
            if ($contentType !== null)
                $this->assets[$route]->setContentType($contentType);
        }
        return $this->assets[$route];
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ( ! isset ($this->assets[$request->getUri()->getPath()]))
            return $this->app->responseFactory->createResponseWithBody("404 Asset not found: '{$request->getUri()->getPath()}'", 404);
        return $this->assets[$request->getUri()->getPath()]->handle($request);
    }
}