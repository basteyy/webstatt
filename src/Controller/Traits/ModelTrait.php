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

namespace basteyy\Webstatt\Controller\Traits;

use basteyy\Webstatt\Models\PagesModel;
use basteyy\Webstatt\Models\UsersModel;

trait ModelTrait {

    private array $_models;
    protected UsersModel $users;
    protected PagesModel $pages;

    /**
     * Shortcut for loading the users model
     * @return UsersModel
     */
    protected function getUsersModel() : UsersModel {
        if(!isset($this->users)) {
            $this->users = $this->getModel(UsersModel::class);
        }
        return $this->users;
    }

    /**
     * Shortcut for loading pages model
     * @return PagesModel
     */
    protected function getPagesModel() : PagesModel {
        if(!isset($this->pages)) {
            $this->pages = $this->getModel(PagesModel::class);
        }
        return $this->pages;
    }

    /**
     * Load a model
     * @param string $model_name
     * @return mixed
     */
    protected function getModel(string $model_name): mixed
    {
        if(!isset($this->models[$model_name])) {
            $this->_models[$model_name] = new $model_name($this->getConfigService());
        }

        return $this->_models[$model_name];
    }
}