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

namespace basteyy\Webstatt\Helper;

use basteyy\Webstatt\Models\Entities\LayoutEntity;

final class LayoutStorageHelper {

    /** @var LayoutEntity $layout_entity */
    private LayoutEntity $layout_entity;

    /** @var string $folderName Name of the folder for the page */
    private string $folderName = 'layouts';

    private string $fileExtension = 'php';

    public function __construct(LayoutEntity $layoutEntity)
    {
        $this->layout_entity = $layoutEntity;
    }

    /**
     * Get the current body from the file
     * @return string
     */
    public function getBody() : string {

        if(file_exists($this->getLayoutPath())) {
            return file_get_contents($this->getLayoutPath());
        }

        return '';
    }

    /**
     * Write $template_body to the file
     * @param string $template_body
     * @return void
     */
    public function setBody(string $template_body) : void {
        file_put_contents($this->getLayoutPath(), $template_body, LOCK_EX);
    }

    /**
     * Get the absolute filepath to the file
     * @return string
     */
    public function getLayoutPath() : string {

        if (!is_dir(W_PAGE_STORAGE_PATH . $this->folderName)) {
            mkdir(W_PAGE_STORAGE_PATH . $this->folderName, WEBSTATT_DEFAULT_FOLDER_PERMISSIONS, WEBSTATT_CREATE_FOLDER_RECURSIVE);
        }

        return W_PAGE_STORAGE_PATH . $this->folderName . DS . $this->layout_entity->getName() . '.' . $this->fileExtension;
    }


}