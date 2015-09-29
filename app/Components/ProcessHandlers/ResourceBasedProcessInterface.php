<?php


namespace Servelat\Components\ProcessHandlers;

/**
 * Interface ResourceBasedProcessInterface.
 * The process built with proc_open() function.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ResourceBasedProcessInterface
{
    /**
     * Set resource returned by the proc_open() function call.
     *
     * @param resource $resource
     * @return $this
     */
    public function setResource($resource);

    /**
     * Set streams (pipes) returned by the proc_open() function call.
     *
     * @param array $streams Array of stream resources
     * @return $this
     */
    public function setStreams(array $streams);
}