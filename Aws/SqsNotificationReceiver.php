<?php
/**
 * Created by PhpStorm.
 * User: eapbachman
 * Date: 12/07/14
 * Time: 23:01
 */

namespace Tesla\Esync\Aws;


use Aws\Common\Aws;
use Tesla\Esync\Event\MessageEvent;
use Tesla\Esync\Exception\NoRetryNotificationProcessingException;
use Tesla\Esync\Exception\NotificationProcessingException;
use Tesla\Esync\Message\JsonMessageDeserializer;
use Tesla\Esync\NotificationReceiverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Aws\Sqs\SqsClient;
use Tesla\Esync\Message\MessageFactory;
use Tesla\Esync\Message\CudMessage;
use Tesla\Esync\Event\EsyncBaseEvents;

class SqsNotificationReceiver implements NotificationReceiverInterface
{

    /**
     * @var \Aws\Common\Aws
     */
    protected $aws;

    private $queueArn;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    protected $deserializer;

    protected $messageFactory;

    private $callback;

    function __construct(Aws $aws, $queueArn = null)
    {
        $this->aws = $aws;
        $this->queueArn = $queueArn;

    }

    function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    function setCallback($closure)
    {
        $this->callback = $closure;
    }

    /**
     * @return SqsClient
     */
    function getSqs()
    {
        return $this->aws->get('sqs');
    }

    function getMessageFactory()
    {
        if (!$this->messageFactory) {
            $this->messageFactory = new MessageFactory();
            $this->messageFactory->setEventDispatcher($this->eventDispatcher);
        }

        return $this->messageFactory;
    }


    function getDeserializer()
    {
        if (!$this->deserializer) {
            $this->deserializer = new JsonMessageDeserializer($this->getMessageFactory());
        }

        return $this->deserializer;
    }

    /**
     * @return CudMessage|null
     */
    function listen()
    {
        $max = 5;
        $sqs = $this->getSqs();
        $result = $sqs->receiveMessage(
            array(
                'QueueUrl' => $this->queueArn,
                'MaxNumberOfMessages' => min(10, $max),
                'AttributeNames' => array('ApproximateReceiveCount','ApproximateFirstReceiveTimestamp','SentTimestamp')
            )
        );
        $toDelete = array();
        $msgs = $result->getPath('Messages');
        if (is_array($msgs)) {
            foreach ($result->getPath('Messages') as $sqsMessageEnvelope) {
                $messageBody = $sqsMessageEnvelope['Body'];
                $sqsMessage = json_decode($messageBody);
                $message = $this->getDeserializer()->deserialize($sqsMessage->Message);
                try {
                    if ($this->callback) {
                        $this->callback($message, $sqsMessageEnvelope);
                        $toDelete[] = array('Id' => $sqsMessage->MessageId, 'ReceiptHandle' => $sqsMessageEnvelope['ReceiptHandle']);
                    }
                    if ($this->eventDispatcher) {
                        $event = new SqsIncomingMessageEvent($message, $sqsMessageEnvelope);
                        $this->eventDispatcher->dispatch(EsyncBaseEvents::MESSAGE_RECEIVE, $event);
                        if (!$event->isPropagationStopped()) {
                             $toDelete[] = array('Id' => $sqsMessage->MessageId, 'ReceiptHandle' => $sqsMessageEnvelope['ReceiptHandle']);
                        }
                    }
                } catch (NotificationProcessingException $e) {
                    // error that will lead to a retry of the message..
                } catch (NoRetryNotificationProcessingException $e) {
                    // will delete the message..
                    $toDelete[] = array('Id' => $sqsMessage->MessageId, 'ReceiptHandle' => $sqsMessageEnvelope['ReceiptHandle']);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
        // delete messages
        if (count($toDelete)) {
            $sqs->deleteMessageBatch(array('QueueUrl' => $this->queueArn, 'Entries' => $toDelete));
        }
        return $this;
    }

} 