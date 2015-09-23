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
     * The event listener recieves an
     * \Servelat\Components\TaskManager\Events\TaskManagerHandleTaskEvent instance.
     *
     * @const string
     */
    const TASK_MANAGER_PROCESS_TASK = 'task_manager.process_task';
}