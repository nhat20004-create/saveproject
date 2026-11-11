<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_uses_custom_view(): void
    {
        $this->get('/trang-khong-ton-tai')
            ->assertNotFound()
            ->assertSee('Không tìm thấy trang');
    }
}
