<?php
namespace ASK\SphinxSearchBundle\DataCollector;

use ASK\SphinxSearch\Logging\SphinxLogger;
use ASK\SphinxSearch\SphinxManager;
use ASK\SphinxSearch\SphinxQLFormatter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class SphinxDataCollector extends DataCollector
{
    /**
     * @var \ASK\SphinxSearch\SphinxManager
     */
    protected $sphinxManager;

    /**
     * @var \ASK\SphinxSearch\Logging\SphinxLogger
     */
    protected $logger;

    /**
     * @var \ASK\SphinxSearch\SphinxQLFormatter
     */
    protected $qlFormatter;

    /**
     * @param \ASK\SphinxSearch\SphinxManager $sphinxManager
     * @param \ASK\SphinxSearch\Logging\SphinxLogger $logger
     * @param \ASK\SphinxSearch\SphinxQLFormatter $qlFormatter
     */
    public function __construct(SphinxManager $sphinxManager, SphinxLogger $logger, SphinxQLFormatter $qlFormatter)
    {
        $this->sphinxManager = $sphinxManager;
        $this->logger        = $logger;
        $this->qlFormatter   = $qlFormatter;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['status']       = $this->sphinxManager->getStatus();
        $this->data['queries']      = array();
        $this->data['hasError']     = false;
        $this->data['hasWarning']   = false;
        $this->data['apiError']     = '';
        $this->data['apiWarning']   = '';

        if ($apiError = $this->sphinxManager->getApiError()) {
            $this->data['hasError'] = true;
            $this->data['apiError'] = $apiError;
        }

        if ($apiWarning = $this->sphinxManager->getApiWarning()) {
            $this->data['hasWarning'] = true;
            $this->data['apiWarning'] = $apiWarning;
        }

        foreach ($this->logger->getQueries() as $query)
        {
            $queryData = array(
                'ql'            => $this->qlFormatter->format($query['query']),
                'executionTime' => isset($query['executionTime']) ? $query['executionTime'] : false,
                'error'         => '',
                'warning'       => '',
            );

            if (isset($query['result']) && $error = $query['result']->getError()) {
                $this->data['hasError'] = true;
                $queryData['error'] = $error;
            }

            if (isset($query['result']) && $warning = $query['result']->getWarning()) {
                $this->data['hasWarning'] = true;
                $queryData['warning'] = $warning;
            }

            $this->data['queries'][] = $queryData;
        }

    }

    /**
     * @return \ASK\SphinxSearch\SphinxStatus
     */
    public function getStatus()
    {
        return $this->data['status'];
    }

    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * @return float
     */
    public function getExecutionTime()
    {
        $executionTime = 0;
        foreach ($this->getQueries() as $query) {
            if (false !== $query['executionTime']) {
                $executionTime += $query['executionTime'];
            }
        }

        return $executionTime;
    }

    /**
     * @return int
     */
    public function getQueryCount()
    {
        return count($this->getQueries());
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->data['hasError'];
    }

    /**
     * @return bool
     */
    public function hasWarning()
    {
        return $this->data['hasWarning'];
    }

    /**
     * @return string
     */
    public function getApiError()
    {
        return $this->data['apiError'];
    }

    /**
     * @return string
     */
    public function getApiWarning()
    {
        return $this->data['apiWarning'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'sphinx';
    }
}
