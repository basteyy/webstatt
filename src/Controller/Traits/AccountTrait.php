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

use basteyy\Webstatt\Helper\FlashMessages;
use function basteyy\VariousPhpSnippets\__;

trait AccountTrait {

    use ModelTrait;

    /**
     * Strategy to create a new account
     * @param array $data
     * @return bool
     */
    public function isValidNewAccountData(array $data, bool $add_errors_to_flash = true, bool $return_errors_as_array = false) : bool|array {

        $errors = [];

        if(!isset($data['email'])) {
            $errors[] = __('E-Mail address required');
        } else {

            if(is_array($data['email'])) {
                if($data['email'][0] !== $data['email'][1]) {
                    $errors[] = __('You need to confirm the email address');
                }

                $data['email'] = $data['email'][0];
            }

            if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('E-Mail is invalid');
            }

            if(count($errors) === 0 ) {
                if($this->getUsersModel()->findOneByEmail($data['email'])) {
                    $errors[] = __('E-Mail address cannot be used');
                }
            }
        }

        if(!isset($data['password'])) {
            $errors[] = __('A password required');
        } else {

            if (is_array($data['password'])) {
                if($data['password'][0] !== $data['password'][1]) {
                    $errors[] = __('You need to confirm the password');
                }

                $data['password'] = $data['password'][0];
            }

            if(strlen($data['password']) < 8) {
                $errors[] = __('The password must at least have 8 signs');
            }

            #if (!preg_match('/[a-z]+/', $data['password']) && !preg_match('/[A-Z]+/', $data['password'])) {
            #    $errors[] = __('The password must contain mixed case characters');
            #}

            if (!preg_match('~[0-9]+~', $data['password'])) {
                $errors[] = __('The password must contain at least one number');
            }

            if (!preg_match("/[^\da-z]/", $data['password'])) {
                $errors[] = __('The password must contain at least one special character');
            }
        }

        if(!isset($data['terms'])) {
            $errors[] = __('You need to accept the terms');
        }

        if(count($errors) > 0 ) {
            if($add_errors_to_flash) {
                FlashMessages::addErrorMessage($errors);
            }

            if($return_errors_as_array) {
                return $errors;
            }

            return false;
        }

        return true;
    }

}