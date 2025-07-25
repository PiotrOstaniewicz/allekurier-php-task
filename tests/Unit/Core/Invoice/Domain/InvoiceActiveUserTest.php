<?php

namespace App\Tests\Unit\Core\Invoice\Domain;

use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Exception\InvoiceException;
use App\Core\User\Domain\User;
use PHPUnit\Framework\TestCase;

class InvoiceActiveUserTest extends TestCase
{
    public function test_invoice_can_be_created_for_active_user(): void
    {
        $user = new User('active@example.com');
        $user->activate();
        $invoice = new Invoice($user, 1000);
        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public function test_invoice_cannot_be_created_for_inactive_user(): void
    {
        $this->expectException(InvoiceException::class);
        $this->expectExceptionMessage('Faktura może być wystawiona tylko dla aktywnego użytkownika');
        $user = new User('inactive@example.com');
        new Invoice($user, 1000);
    }
}
