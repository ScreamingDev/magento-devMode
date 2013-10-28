<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell\DevMode\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Change something in the admin user table.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell\DevMode\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class DevMode_Admin_User extends DelegateCommand
{
    public function createAction()
    {
        $user = $this->getParameter()->getOption('user');
        while (!$user)
        {
            $user = $this->prompt("Username [admin]: ", "admin");
        }

        $password = $this->getParameter()->getOption('password');
        while (!$password)
        {
            $password = $this->promptSilent("Password [password123]: ", "password123");
        }

        $firstname = $this->getParameter()->getOption('firstname');
        while (!$firstname)
        {
            $firstname = $this->prompt("First name [John]: ", 'John');
        }

        $lastname = $this->getParameter()->getOption('lastname');
        while (!$lastname)
        {
            $lastname = $this->prompt("Lastname [Doe]: ", "Doe");
        }

        $email = $this->getParameter()->getOption('email');
        while (!$email)
        {
            $email = $this->prompt("E-Mail [webmaster@127.0.0.1]: ", "webmaster@127.0.0.1");
        }

        $model = $this->_getModel();

        $model->load($user, 'username');

        if ($model->getId())
        {
            echo "Already existing user $user";

            return;
        }

        $model->setData(
              array(
                   'username'  => $user,
                   'firstname' => $firstname,
                   'lastname'  => $lastname,
                   'email'     => $email,
              )
        );

        $model->setPassword($password);

        $model->save();
    }


    public function listAction()
    {
        $this->_loadMagento();

        $table = new LeMike_DevMode_Block_Shell_Table(
            array(
                 'user_id'   => 'ID',
                 'username'  => 'Username',
                 'firstname' => 'Firstname',
                 'lastname'  => 'Lastname',
                 'email'     => 'E-Mail',
                 'is_active' => 'Active',
            ),
            $this->_getModel()->getCollection()
        );

        echo $table;
    }


    /**
     * Changing an admin user.
     *
     * Usage:
     *
     * @return void
     */
    public function updateAction()
    {
        // load user
        $identifier = current($this->getParameter()->getArguments());

        if (!$identifier)
        {
            $identifier = current($this->getParameter()->getCommands());
        }

        if (!$identifier)
        {
            echo $this->getMethodHelp(__METHOD__);

            return;
        }

        $this->_loadMagento();
        $mailValidate = new Zend_Validate_EmailAddress(
            array(
                 'allow' => Zend_Validate_Hostname::ALLOW_ALL
            )
        );

        $model = $this->_getModel();
        if (is_numeric($identifier))
        {
            $model->load((int) $identifier);
        }
        elseif (Zend_Validate_EmailAddress)
        {
            $model->load($identifier, 'email');
        }
        else
        {
            $model->loadByUsername($identifier);
        }

        if (!$model->getId())
        {
            echo "User was not found: " . $identifier;

            return;
        }

        printf('Changing #%d: %s <%s>', $model->getId(), $model->getUsername(), $model->getEmail());
        echo PHP_EOL;

        $targetSet = $this->getParameter()->getOption();
        if (empty($targetSet))
        { // quick change by option
            $targetSet = array(
                'username' => null,
                'password' => null,
                'email' => null,
                'firstname' => null,
                'lastname' => null,
                'lastname' => null,
                'is_active' => null,
            );
        }

        foreach ($targetSet as $key => $value)
        {
            if (!$model->hasData($key))
            {
                echo "Skipping invalid field '$key'";
                continue;
            }

            if ($value === true)
            {
                if ($key == "password")
                {
                    $value = $this->promptSilent('Password [keep old]: ');
                }
                else
                {
                    $value = $this->prompt(
                                  ucfirst($key) . ' [' . $model->getData($key) . ']: ',
                                  $model->getData($key)
                    );
                }
            }

            switch ($key)
            {
                case "password":
                    if (trim($value))
                    {
                        $model->setPassword($value);
                    }
                    break;
                default:
                    $model->setData($key, $value);
            }
        }

        $model->isObjectNew(false);

        $model->save();
    }


    /**
     * Fetch a model about admin user.
     *
     * @return Mage_Admin_Model_User
     */
    protected function _getModel()
    {
        $this->_loadMagento();

        $model = Mage::getModel('admin/user');

        return $model;
    }
}
