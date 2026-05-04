<?php

namespace DreamFactory\Core\PubSub\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * Security: Sub::getSubscriptionPayload() must constrain unserialize to a
 * known subscriber-class allowlist.
 *
 * Previously: unserialize(Arr::get($payload, 'data.command')) with no
 * allowed_classes — any object class would be deserialized and its
 * __wakeup/__destruct methods invoked, opening PHP gadget-chain RCE if
 * an attacker could influence queue-job payloads.
 *
 * After the fix, allowed_classes is an explicit list of concrete
 * Subscribe job classes (df-amqp + df-mqtt), and the result is
 * checked via instanceof before its methods are called.
 */
class UnserializeAllowlistTest extends TestCase
{
    private string $contents;

    protected function setUp(): void
    {
        $sourcePath = __DIR__ . '/../../src/Resources/Sub.php';
        $this->assertFileExists($sourcePath);
        $this->contents = file_get_contents($sourcePath);
    }

    public function testUnserializePassesAllowedClasses(): void
    {
        $this->assertMatchesRegularExpression(
            '/unserialize\s*\(.+?[\'"]allowed_classes[\'"]/s',
            $this->contents,
            'unserialize() must pass allowed_classes option to constrain '
            . 'which classes can be deserialized.'
        );
    }

    public function testAllowedClassesListsConcreteSubscribers(): void
    {
        $this->assertMatchesRegularExpression(
            '/AMQP[\\\\]+Jobs[\\\\]+Subscribe/',
            $this->contents,
            'allowed_classes must include the AMQP Subscribe class'
        );
        $this->assertMatchesRegularExpression(
            '/MQTT[\\\\]+Jobs[\\\\]+Subscribe/',
            $this->contents,
            'allowed_classes must include the MQTT Subscribe class'
        );
    }

    public function testInstanceofGuardAfterUnserialize(): void
    {
        $this->assertMatchesRegularExpression(
            '/instanceof\s+\\\\?[A-Z][A-Za-z0-9_\\\\]*BaseSubscriber\b/',
            $this->contents,
            'After unserialize, the result must be checked with `instanceof BaseSubscriber`'
        );
    }
}
