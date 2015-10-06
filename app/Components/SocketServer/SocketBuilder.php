<?php


namespace Servelat\Components\SocketServer;

/**
 * Class SocketBuilder.
 * The needed socket builder.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class SocketBuilder
{
    /**
     * @var resource
     */
    protected $socket;

    /**
     * @var int
     */
    protected $protocolFamily;

    /**
     * @var int
     */
    protected $socketType;

    /**
     * @var int
     */
    protected $protocolCode;

    /**
     * @var bool
     */
    protected $blocking = false;

    /**
     * Set protocol family.
     *
     * @param int $protocolFamily
     * @return $this
     */
    public function setProtocolFamily($protocolFamily)
    {
        if (!in_array($protocolFamily, [\AF_INET, \AF_INET6, \AF_UNIX])) {
            throw new \InvalidArgumentException('Wrong domain type specified!');
        }
        $this->protocolFamily = $protocolFamily;

        return $this;
    }

    /**
     * Set socket type.
     *
     * @param int $socketType
     * @return $this
     */
    public function setSocketType($socketType)
    {
        if (!in_array($socketType, [\SOCK_STREAM, \SOCK_DGRAM, \SOCK_SEQPACKET, \SOCK_RAW, \SOCK_RDM])) {
            throw new \InvalidArgumentException('Wrong socket type specified.');
        }
        $this->socketType = $socketType;

        return $this;
    }

    /**
     * Set protocol by name.
     *
     * @param string $protocol
     * @return $this
     */
    public function setProtocol($protocol)
    {
        if (false === $protocolCode = getprotobyname($protocol)) {
            throw new \InvalidArgumentException(sprintf('Unknown protocol %s', $protocol));
        }
        $this->protocolCode = $protocolCode;

        return $this;
    }

    /**
     * Set blocking/non-blocking mode.
     *
     * @param bool $blocking
     * @return $this
     */
    public function setBlocking($blocking = true)
    {
        $this->blocking = !!$blocking;

        return $this;
    }

    /**
     * Build socket.
     *
     * @return resource
     */
    public function getSocket()
    {
        if (!isset(
            $this->protocolCode,
            $this->blocking,
            $this->protocolFamily,
            $this->socketType
        )) {
            throw new \LogicException(
                'All values should be set: protocolCode, blocking, protocolFamily, socketType'
            );
        }

        $this->socket = socket_create($this->protocolFamily, $this->socketType, $this->protocolCode);
        if (!$this->blocking) {
            socket_set_nonblock($this->socket);
        }

        return $this->socket;
    }
}