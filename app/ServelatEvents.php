<?php


namespace Servelat;

/**
 * Final class ServelatEvents.
 * List of base events.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
final class ServelatEvents
{
    /**
     * Event task_manager.process_task is thrown
     * when the TaskManager component processes the next task.
     *
     * The event listener receives an
     * \Servelat\Components\TaskManager\Events\ProcessTaskEvent instance.
     *
     * @const string
     */
    const TASK_MANAGER_PROCESS_TASK = 'task_manager.process_task';

    /**
     * Event task_manager.after_process_task is thrown
     * after the task is processed.
     *
     * The event listener receives an
     * \Servelat\Components\TaskManager\Events\AfterProcessTaskEvent instance.
     *
     */
    const TASK_MANAGER_AFTER_PROCESS_TASK = 'task_manager.after_process_task';

    /**
     * Event process_manager.process_output is thrown
     * after the output from the process was recieved.
     *
     * The event listener receives an
     * \Servelat\Components\ProcessManager\Events\ProcessOutputEvent instance.
     */
    const PROCESS_MANAGER_PROCESS_OUTPUT = 'process_manager.process_output';

    /**
     * Event process_manager.process_failed is thrown
     * after the process was marked as failed.
     *
     * The event listener receives an
     * \Servelat\Components\ProcessManager\Events\ProcessFailedEvent instance.
     */
    const PROCESS_MANAGER_PROCESS_FAILED = 'process_manager.process_failed';

    /**
     * Event process_manager.process_closed is thrown
     * after the process is stopped.
     *
     * The event listener receives an
     * \Servelat\Components\ProcessManager\Events\ProcessClosedEvent instance.
     */
    const PROCESS_MANAGER_PROCESS_CLOSED = 'process_manager.process_closed';

    /**
     * Event message_broker.unserialize_message is thrown
     * when message broker receives a new message.
     *
     * The event listener receives an
     * \Servelat\Components\MessageBroker\Events\UnserializeMessageEvent instance.
     *
     */
    const MESSAGE_BROKER_UNSERIALIZE_MESSAGE = 'message_broker.unserialize_message';

    /**
     * Event message_broker.after_unserialize_message is thrown
     * after the message was unserialized.
     *
     * The event listener receives an
     * \Servelat\Components\MessageBroker\Events\AfterUnserializeMessageEvent instance.
     */
    const MESSAGE_BROKER_AFTER_UNSERIALIZE_MESSAGE = 'message_broker.after_unserialize_message';
}