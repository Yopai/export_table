<?php

declare(strict_types=1);

/*
 * This file is part of Export Table for Contao CMS.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/export_table
 */

namespace Markocupic\ExportTable\Listener\ContaoHooks;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Input;

/**
 * @Hook(ReplaceGetInsertTagsListener::HOOK,  priority=ReplaceGetInsertTagsListener::PRIORITY)
 */
class ReplaceGetInsertTagsListener implements ListenerInterface
{
    public const HOOK = 'replaceInsertTags';
    public const PRIORITY = 10;

    /**
     * @var bool
     */
    public static $disableHook = false;

    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public function __invoke(string $insertTag, bool $useCache, string $cachedValue, array $flags, array $tags, array $cache, int $_rit, int $_cnt)
    {
        if (static::$disableHook) {
            return false;
        }

        $inputAdapter = $this->framework->getAdapter(Input::class);
        $elements = explode('::', $insertTag);
        $key = strtoupper($elements[0]);

        // Replace {{GET::key}} with a given value of certain $_GET parameter.
        if ('GET' === $key) {
            if ($elements[1] && '' !== $inputAdapter->get($elements[1])) {
                return $inputAdapter->get($elements[1]);
            }

            return 'empty-string';
        }

        return false;
    }

    public static function disableHook(): void
    {
        self::$disableHook = true;
    }

    public static function enableHook(): void
    {
        self::$disableHook = false;
    }

    public static function isEnabled(): bool
    {
        return self::$disableHook;
    }
}
