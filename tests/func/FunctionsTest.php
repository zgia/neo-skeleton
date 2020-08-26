<?php

/**
 * @backupGlobals disabled
 *
 * @internal
 * @coversNothing
 */
class FunctionsTest extends BaseTester
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSendMail()
    {
        $subject = __f('%s, your new password.', 'zgia');
        $body = __f(
            'Hello, %s.<br />Your password is <strong>%s</strong>, Please <a href="%s">login in our website</a> to change your password.<br /><br />%s',
            'zgia',
            's*#(jGd3',
            'https://www.163.com',
            getOption('websitename')
        );

        $attachment = '/Users/liyuntian/Desktop/74601595_2407800279435583_3971153603992394181_n.jpg';
        $done = sendMail($subject, $body, 'zgia@163.com', 'text/html',$attachment);

        \PHPUnit\Framework\assertEquals(1, $done);
    }
}
