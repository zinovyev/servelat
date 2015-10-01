<?php


namespace Servelat\Components\TaskManager;

use Servelat\Components\MessageBroker\Events\AfterUnserializeMessageEvent;
use Servelat\Components\MessageBroker\Events\ResponseMessageEvent;
use Servelat\Components\MessageBroker\MessageBroker;
use Servelat\Components\MessageBroker\Messages\JsonMessage;
use Servelat\Components\ProcessManager\Events\ProcessClosedEvent;
use Servelat\Components\ProcessManager\Events\ProcessFailedEvent;
use Servelat\Components\ProcessManager\Events\ProcessOutputEvent;
use Servelat\Components\TaskManager\Events\AfterProcessTaskEvent;
use Servelat\Components\TaskManager\Events\ProcessTaskEvent;
use Servelat\Components\TaskManager\Events\ReturnTaskResultEvent;
use Servelat\ServelatEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class TaskManager.
 * Servelat task manager.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class TaskManager
{
    /**
     * @var \SplQueue
     */
    protected $taskQueue;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function __construct(\SplQueue $taskQueue, EventDispatcher $dispatcher)
    {
        $this->taskQueue = $taskQueue;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Push the task to the queue.
     *
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     * @return $this
     */
    public function addTask(TaskInterface $task)
    {
        $this->taskQueue->push($task);

        return $this;
    }

    /**
     * Count tasks left in the queue.
     *
     * @return int
     */
    public function countTasks()
    {
        return $this->taskQueue->count();
    }

    /**
     * Try to process next task.
     *
     * @return null|\Servelat\Components\ProcessManager\ProcessInterface
     */
    public function handleNext()
    {
        try {
            /** @var TaskInterface $task */
            $task = $this->taskQueue->shift();
        } catch (\RuntimeException $ex) {
            return null;
        }

        // Handle task
        $processTaskEvent = new ProcessTaskEvent($task);
        $this->dispatcher->dispatch(
            ServelatEvents::TASK_MANAGER_PROCESS_TASK,
            $processTaskEvent
        );

        if (null === $process = $processTaskEvent->getProcess()) {
            throw new \RuntimeException('The task was not handled correctly.');
        }

        $afterProcessTaskEvent = new AfterProcessTaskEvent($process);
        $this->dispatcher->dispatch(
            ServelatEvents::TASK_MANAGER_AFTER_PROCESS_TASK,
            $afterProcessTaskEvent
        );

        return $process;
    }

    /**
     * Process after unserialize message.
     *
     * @param \Servelat\Components\MessageBroker\Events\AfterUnserializeMessageEvent $event
     */
    public function onAfterMessageUnserializeEvent(AfterUnserializeMessageEvent $event)
    {
        if (
            MessageBroker::ROUTING_KEY_TASK_MANAGER === $event->getRoutingKey()
            && $task = unserialize(base64_decode($event->getPayload(), true))
        ) {
            if ($task instanceof TaskInterface) {
                $this->addTask($task);
            }

            $event->stopPropagation();
        }
    }

    /**
     * Get processed task and create a response message.
     *
     * @param \Servelat\Components\ProcessManager\Events\ProcessClosedEvent
     */
    public function onAfterProcessClosed(ProcessClosedEvent $event)
    {
        if (null !== $event->getProcess()->getTask()) {
            $taskResult = new TaskResult(
                $event->getProcess()->getTask()->getId(),
                $event->getProcess()->getTask()->getOwnerPid(),
                $event->getProcess()->getExitCode(),
                array_filter($event->getProcess()->getOutputLines())
            );
            $message = new JsonMessage(
                $event->getProcess()->getTask()->getOwnerPid(),
                base64_encode(serialize($taskResult))
            );

            // Dispatch
            $responseMessageEvent = new ResponseMessageEvent($message);
            $this->dispatcher->dispatch(
                ServelatEvents::MESSAGE_BROKER_RESPONSE_MESSAGE,
                $responseMessageEvent
            );
        }
    }

    /**
     * Get processed task and create a response message.
     *
     * @param \Servelat\Components\ProcessManager\Events\ProcessFailedEvent
     *
     */
    public function onAfterProcessFailed(ProcessFailedEvent $event)
    {
        // @TODO handle process when failed...
    }

    /**
     * Get processed task and create a response message.
     *
     * @param \Servelat\Components\ProcessManager\Events\ProcessOutputEvent
     */
    public function onAfterProcessOutput(ProcessOutputEvent $event)
    {
        // @TODO handle process when output is available...
    }
}