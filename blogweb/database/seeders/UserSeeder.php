<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const MEMBERS = [
        ['Nguyễn Minh Anh', 'minhanh.nguyen92@gmail.com'],
        ['Trần Quốc Bảo', 'baotran.dev@gmail.com'],
        ['Lê Thu Hà', 'thuha.le1998@gmail.com'],
        ['Phạm Gia Huy', 'giahuy.pham07@gmail.com'],
        ['Hoàng Ngọc Lan', 'ngoclan.hoang95@gmail.com'],
        ['Vũ Đức Anh', 'ducanh.vu2000@gmail.com'],
        ['Đặng Khánh Linh', 'khanhlinh.dang@gmail.com'],
        ['Bùi Thanh Tùng', 'tungbui.hn@gmail.com'],
        ['Đỗ Phương Thảo', 'phuongthao.do97@gmail.com'],
        ['Ngô Hữu Phước', 'huuphuoc.ngo89@gmail.com'],
        ['Dương Mỹ Duyên', 'myduyen.duong03@gmail.com'],
        ['Lý Hoàng Nam', 'hoangnam.ly.sg@gmail.com'],
    ];

    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Quản trị viên',
            'email' => 'admin@gmail.com',
            'password' => 'admin1234',
        ]);

        foreach (self::MEMBERS as [$name, $email]) {
            User::factory()->create([
                'name' => $name,
                'email' => $email,
                'password' => '12345678',
            ]);
        }
    }
}
