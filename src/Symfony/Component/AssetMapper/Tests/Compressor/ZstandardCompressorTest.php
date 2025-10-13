<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\AssetMapper\Tests\Compressor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\AssetMapper\Compressor\ZstandardCompressor;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Kévin Dunglas <kevin@dunglas.dev>
 */
class ZstandardCompressorTest extends TestCase
{
    private const WRITABLE_ROOT = __DIR__.'/../Fixtures/zstandard_compressor_filesystem';

    private ?Filesystem $filesystem = null;

    protected function setUp(): void
    {
        if (null !== $reason = (new ZstandardCompressor())->getUnsupportedReason()) {
            $this->markTestSkipped($reason);
        }

        $this->filesystem = new Filesystem();
        if (!file_exists(self::WRITABLE_ROOT)) {
            $this->filesystem->mkdir(self::WRITABLE_ROOT);
        }
    }

    protected function tearDown(): void
    {
        $this->filesystem?->remove(self::WRITABLE_ROOT);
    }

    public function testCompress()
    {
        $this->filesystem->dumpFile(self::WRITABLE_ROOT.'/foo/bar.js', 'foobar');

        (new ZstandardCompressor())->compress(self::WRITABLE_ROOT.'/foo/bar.js');

        $this->assertFileExists(self::WRITABLE_ROOT.'/foo/bar.js.zst');
    }
}
