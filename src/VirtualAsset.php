<?php


namespace Brace\Assets;


use Brace\Core\BraceApp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VirtualAsset implements RequestHandlerInterface
{

    private $contentType;

    private $content = [];

    public function __construct (
        private BraceApp $app
    ){}

    public function setContentType(string $contentType) : self
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function addFile(string $filename) : self
    {
        $this->content[] = ["filename" => $filename];
        return $this;
    }

    public function addRaw (string $content) : self
    {
        $this->content[] = ["data" => $content];
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = "";
        foreach ($this->content as $c) {
            if (isset($c["filename"])) {
                if ( ! file_exists($c["filename"]))
                    throw new \InvalidArgumentException("Cannot load asset '{$c['filename']}' in assetset.");
                $data .= file_get_contents($c["filename"]);

            }
            if (isset($c["data"]))
                $data .= $c["data"];
        }
        $resp = $this->app->responseFactory->createResponseWithBody($data, 200);
        if ($this->contentType !== null)
            $resp = $resp->withHeader("Content-Type", $this->contentType);
        return $resp;
    }
}