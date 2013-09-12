<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMail
 * @package    CoreAdminhtmlControllerTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */

/**
 * Class CoreAdminhtmlControllerTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */
abstract class LeMike_DevMode_Test_Adminhtml extends EcomDev_PHPUnit_Test_Case_Controller
{
    const FAKE_USER_ID = 42;

    protected $_lastArgs = null;

    protected $_fetchedArgs = array();


    public function setUp()
    {
        $this->_fakeLogin();

        $_SERVER['HTTP_HOST'] = 'http://localhost.rmp/';
        $this->app()->getRequest()->setBaseUrl($_SERVER['HTTP_HOST']);

        parent::setUp();
    }


    public function tearDown()
    {
        $adminSession = Mage::getSingleton('admin/session');
        $adminSession->unsetAll();
        $adminSession->getCookie()->delete($adminSession->getSessionName());
        parent::tearDown();
    }


    /**
     * Test whether fake user successfully logged in
     */
    public function testLoggedIn()
    {
        $this->assertTrue((bool)Mage::getSingleton('admin/session')->isLoggedIn());
    }


    /**
     * Test whether logged user is fake
     */
    public function testLoggedUserIsFakeUser()
    {
        /** @var Mage_Admin_Model_User $user */
        $user = Mage::getSingleton('admin/session')->getData('user');
        $this->assertEquals($user->getId(), self::FAKE_USER_ID);
    }


    /**
     * Logged in to Magento with fake user to test an adminhtml controllers
     */
    protected function _fakeLogin()
    {
        $sessionMock = $this->getModelMockBuilder('admin/session')
                       ->disableOriginalConstructor()
                       ->setMethods(null)
                       ->getMock();
        $this->replaceByMock('singleton', 'admin/session', $sessionMock);

        $captchaMock = $this->getModelMockBuilder('captcha/observer');
        $captchaMock->getMock()->expects($this->any())->method('checkUserLoginBackend')->will(
            $this->returnValue($captchaMock->getMock())
        );
        $this->replaceByMock('singleton', 'captcha/observer', $captchaMock);

        $cookieMock = $this->getModelMockBuilder('core/cookie');
        $cookieMock->getMock()->expects($this->any())->method('getDomain')->will($this->returnValue('localhost'));
        $this->replaceByMock('model', 'core/cookie', $cookieMock);

        $this->_registerUserMock();
        Mage::getSingleton('adminhtml/url')->turnOffSecretKey();
        Mage::getSingleton('adminhtml/url')->setData('base_url', 'http://localhost.rmp');

        $session = Mage::getSingleton('admin/session');
        $session->login('some_fake_user', 'some_fake_user.P4ss');
    }


    /**
     * Creates a mock object for admin/user Magento Model
     *
     * @return self
     */
    protected function _registerUserMock()
    {
        $user = $this->getModelMock('admin/user');
        $user->expects($this->any())->method('getId')->will($this->returnValue(self::FAKE_USER_ID));
        $this->replaceByMock('model', 'admin/user', $user);

        return $this;
    }
} 
