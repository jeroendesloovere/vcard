<?php

namespace JeroenDesloovere\VCard\Tests\Util;

use JeroenDesloovere\VCard\Util\UserAgentUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class UserAgentUtilTest
 *
 * @package JeroenDesloovere\VCard\Tests\Util
 */
class UserAgentUtilTest extends TestCase
{
    /**
     *
     */
    public function testGetUserAgent()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1';

        $result = UserAgentUtil::getUserAgent();
        $this->assertEquals('mozilla/5.0 (iphone; cpu iphone os 10_3 like mac os x) applewebkit/602.1.50 (khtml, like gecko) crios/56.0.2924.75 mobile/14e5239e safari/602.1', $result);
    }

    // Test for iOS 3
    /**
     *
     */
    public function testIsIOSIsIOS3()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; U; CPU OS 3_2_2 like Mac OS X; nl-nl) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B500 Safari/531.21.10';

        $result = UserAgentUtil::isIOS();
        $this->assertEquals(true, $result);
    }

    /**
     *
     */
    public function testShouldAttachmentBeCalIsIOS3()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; U; CPU OS 3_2_2 like Mac OS X; nl-nl) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B500 Safari/531.21.10';

        $result = UserAgentUtil::shouldAttachmentBeCal();
        $this->assertEquals(true, $result);
    }

    /**
     *
     */
    public function testIsIOS7IsIOS3()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; U; CPU OS 3_2_2 like Mac OS X; nl-nl) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B500 Safari/531.21.10';

        $result = UserAgentUtil::isIOS7();
        $this->assertEquals(true, $result);
    }

    // Test for iOS 6
    /**
     *
     */
    public function testIsIOSIsIOS6()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B145 Safari/8536.25';

        $result = UserAgentUtil::isIOS();
        $this->assertEquals(true, $result);
    }

    /**
     *
     */
    public function testShouldAttachmentBeCalIsIOS6()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B145 Safari/8536.25';

        $result = UserAgentUtil::shouldAttachmentBeCal();
        $this->assertEquals(true, $result);
    }

    /**
     *
     */
    public function testIsIOS7IsIOS6()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B145 Safari/8536.25';

        $result = UserAgentUtil::isIOS7();
        $this->assertEquals(true, $result);
    }

    // Test for iOS 7
    /**
     *
     */
    public function testIsIOSIsIOS7()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; CPU OS 7_0_2 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A501';

        $result = UserAgentUtil::isIOS();
        $this->assertEquals(true, $result);
    }

    /**
     *
     */
    public function testShouldAttachmentBeCalIsIOS7()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; CPU OS 7_0_2 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A501';

        $result = UserAgentUtil::shouldAttachmentBeCal();
        $this->assertEquals(true, $result);
    }

    /**
     *
     */
    public function testIsIOS7IsIOS7()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; CPU OS 7_0_2 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A501';

        $result = UserAgentUtil::isIOS7();
        $this->assertEquals(true, $result);
    }

    // Test for iOS 10
    /**
     *
     */
    public function testIsIOSIsIOS10()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1';

        $result = UserAgentUtil::isIOS();
        $this->assertEquals(true, $result);
    }

    /**
     *
     */
    public function testShouldAttachmentBeCalIsIOS10()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1';

        $result = UserAgentUtil::shouldAttachmentBeCal();
        $this->assertEquals(false, $result);
    }

    /**
     *
     */
    public function testIsIOS7IsIOS10()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1';

        $result = UserAgentUtil::isIOS7();
        $this->assertEquals(false, $result);
    }
}
