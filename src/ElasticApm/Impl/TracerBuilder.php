<?php

declare(strict_types=1);

namespace Elastic\Apm\Impl;

use Elastic\Apm\Impl\Util\HiddenConstructorTrait;
use Elastic\Apm\TracerInterface;

/**
 * Code in this file is part of implementation internals and thus it is not covered by the backward compatibility.
 *
 * @internal
 */
final class TracerBuilder
{
    /**
     * Constructor is hidden because startNew() should be used instead
     */
    use HiddenConstructorTrait;

    /** @var bool */
    private $isEnabled = true;

    /** @var ClockInterface|null */
    private $clock;

    /** @var ReporterInterface|null */
    private $reporter;

    public static function startNew(): self
    {
        return new self();
    }

    public function withEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    public function withClock(ClockInterface $clock): self
    {
        $this->clock = $clock;
        return $this;
    }

    public function withReporter(ReporterInterface $reporter): self
    {
        $this->reporter = $reporter;
        return $this;
    }

    public function build(): TracerInterface
    {
        if (!$this->isEnabled) {
            return NoopTracer::instance();
        }

        return new Tracer(
            $this->clock ?? Clock::instance(),
            $this->reporter ?? NoopReporter::instance()
        );
    }
}
