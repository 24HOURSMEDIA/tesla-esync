<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 21:39
 */

namespace Tesla\Esync\Aws;


use Aws\Common\Aws;
use Aws\Sns\SnsClient;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tesla\Esync\AbstractNotificationService;
use Tesla\Esync\Message\CudMessage;
use Tesla\Esync\Message\JsonMessageSerializer;
use Tesla\Esync\Message\MessageFactory;

class SnsNotificationService extends AbstractNotificationService
{

    protected $systemName = 'Default (undefined) system';

    protected $topicArn = '';
    protected $topicRegion = '';

    /**
     * @var \Aws\Common\Aws
     */
    protected $aws;

    /**
     * @var SnsClient
     */
    private $snsClient;
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;



    function __construct(Aws $aws, $topicArn = null, $topicRegion = null)
    {
        parent::__construct();
        $this->aws = $aws;

        /* @var $sns SnsClient */
        $this->topicArn = $topicArn;
        $this->topicRegion = $topicRegion;
        $sns = $aws->get('sns');
        $sns->setRegion($this->topicRegion);
        $this->snsClient = $sns;
    }

    function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    function getMessageFactory() {
        if (!$this->messageFactory) {
            $this->messageFactory = new MessageFactory();
            $this->messageFactory->setEventDispatcher($this->eventDispatcher);
        }
        return $this->messageFactory;
    }

    function getMessageSerializer() {
        if (!$this->messageSerializer) {
            $this->messageSerializer = new JsonMessageSerializer();
        }
        return $this->messageSerializer;
    }

    function getSnsClient() {
        if ($this->snsClient) return $this->snsClient;
        $this->snsClient = $this->aws->get('sns');
        $this->snsClient->setRegion($this->topicRegion);
        return $this->snsClient;
    }


    /**
     * Set the systemName
     * @param string $systemName
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;

        return $this;
    }

    /**
     * Get the SystemName
     * @return string
     */
    private function getSystemName()
    {
        return $this->systemName;
    }


    protected function sendNotification(CudMessage $message)
    {

        $response = $this->getSnsClient()->publish(
            array(
                'TopicArn' => $this->topicArn,
                'Message' => $this->getMessageSerializer()->serialize($message),
                'Subject' => 'CUD ' . strtoupper($message->getType()) . ' Notification from ' . $this->getSystemName(),
                'MessageStructure' => 'string',
                'MessageAttributes' => array(
                    'originating_system' => array('DataType' => 'String', 'StringValue' => $this->getSystemName())
                )
            )
        );

        return $this;
    }

} 