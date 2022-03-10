<?php
/**
 * Webstatt
 *
 * @author Sebastian Eiweleit <sebastian@eiweleit.de>
 * @website https://webstatt.org
 * @website https://github.com/basteyy/webstatt
 * @license CC BY-SA 4.0
 */

declare(strict_types=1);

namespace basteyy\Webstatt\Models;

use basteyy\Webstatt\Models\Abstractions\PageAbstraction;
use basteyy\Webstatt\Models\Entities\PageEntity;

final class PagesModel extends Model {
    protected string $database_name = 'pages';
    protected int $couting;


    /**
     * @throws \SleekDB\Exceptions\IOException
     * @throws \ReflectionException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     */
    public function getAllOnlinePages() : array {

        $pages = [];

        foreach($this->getRaw()->findBy(['is_online', '=', true]) as $_page) {
            $pages[] = new PageEntity($_page, $this->getPrimaryIdName());
        }

        return $pages;

    }

}