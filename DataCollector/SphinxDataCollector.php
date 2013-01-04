<?php
namespace ASK\SphinxSearchBundle\DataCollector;

use ASK\SphinxSearch\SphinxManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class SphinxDataCollector extends DataCollector
{
    /**
     * @var \ASK\SphinxSearch\SphinxManager
     */
    protected $sphinxManager;

    public function __construct(SphinxManager $sphinxManager)
    {
        $this->sphinxManager = $sphinxManager;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['status'] = $this->sphinxManager->getStatus();
    }

    /**
     * @return \ASK\SphinxSearch\SphinxStatus
     */
    public function getStatus()
    {
        return $this->data['status'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'sphinx';
    }
}
