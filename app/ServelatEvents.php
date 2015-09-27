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
     * Event task_manager.process_task is thrown
     * after the task is processed.
     *
     * The event listener receives an
     * \Servelat\Components\TaskManager\Events\AfterProcessTaskEvent instance.
     *
     */
    const TASK_MANAGER_AFTER_PROCESS_TASK = 'task_manager.after_process_task';
}