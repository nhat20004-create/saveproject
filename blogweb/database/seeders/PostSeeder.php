<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    private const IMG = 'https://images.unsplash.com/';

    public function run(): void
    {
        $users = User::where('role', 'member')->get();
        $tags = Tag::all();

        foreach ($this->samples() as $i => [$title, $cat, $photo, $text]) {
            $post = Post::factory()->create([
                'user_id' => $users[$i % $users->count()]->id,
                'category_id' => Category::where('slug', $cat)->value('id'),
                'title' => $title,
                'slug' => 'bai-viet-'.($i + 1),
                'content' => $text,
                'file_path' => self::IMG.$photo.'?w=800',
                'published_at' => now()->subDays(10 - $i),
            ]);
            $post->tags()->attach($tags->random(2));
        }

        $this->extras($users);
    }

    private function extras($users): void
    {
        $cat = Category::first()->id;

        Post::factory()->draft()->create([
            'user_id' => $users[0]->id, 'category_id' => $cat,
            'title' => 'Bài nháp chưa đăng', 'slug' => 'bai-nhap',
        ]);
        Post::factory()->scheduled()->create([
            'user_id' => $users[0]->id, 'category_id' => $cat,
            'title' => 'Bài hẹn giờ đăng', 'slug' => 'bai-hen-gio',
        ]);

        $this->comments();
    }

    private function comments(): void
    {
        $bodies = [
            'Bài viết hay quá, cảm ơn bạn đã chia sẻ!',
            'Mình cũng gặp tình huống này rồi, đọc xong thấy nhẹ đầu hơn nhiều.',
            'Cảm ơn tác giả, đúng thứ mình đang tìm hiểu.',
            'Đồng ý với quan điểm này, nhất là đoạn cuối bài.',
            'Bạn có thể viết thêm một bài chi tiết hơn về chủ đề này không?',
            'Mình đã thử áp dụng và thấy khá hiệu quả, mọi người nên thử nhé.',
            'Hình minh họa đẹp, nội dung ngắn gọn dễ hiểu.',
            'Mình thì nghĩ khác một chút, nhưng vẫn thấy bài viết có nhiều ý hay.',
            'Đọc xong mới thấy mình bỏ sót nhiều điều, cảm ơn bạn nhiều.',
            'Chia sẻ thực tế, không lan man. Chờ bài tiếp theo của bạn!',
            'Mình đã lưu lại để đọc lại sau, rất hữu ích.',
            'Cho mình hỏi thêm: bạn làm điều này được bao lâu rồi?',
            'Nội dung gần gũi với sinh viên như tụi mình, 10 điểm!',
            'Thấy bài này đúng lúc quá, vừa gặp vấn đề tương tự hôm qua.',
            'Viết tốt lắm, mong bạn tiếp tục ra bài đều đặn.',
        ];

        $members = User::where('role', 'member')->get();

        Post::published()->get()->each(function (Post $post) use ($bodies, $members) {
            $writers = $members->where('id', '!=', $post->user_id);
            $total = random_int(2, 5);
            $at = $post->published_at->copy();

            foreach (collect($bodies)->random($total) as $body) {
                $at = $at->copy()->addMinutes(random_int(20, 600));
                if ($at->isFuture()) {
                    $at = now()->subMinutes(random_int(1, 30));
                }

                Comment::factory()->create([
                    'post_id' => $post->id,
                    'user_id' => $writers->random()->id,
                    'body' => $body,
                    'created_at' => $at,
                    'updated_at' => $at,
                ]);
            }
        });
    }

    private function samples(): array
    {
        $a = 'photo-1497366754035-f200968a6e72';
        $b = 'photo-1498050108023-c5249f4df085';
        $c = 'photo-1516321318423-f06f85e504b3';
        $d = 'photo-1519389950473-47ba0277781c';
        $e = 'photo-1521737711867-e3b97375f902';
        $n1 = 'Áp lực công việc kéo dài khiến nhiều người mệt mỏi. Chia sẻ sớm giúp giảm căng thẳng';
        $n2 = 'Làm việc từ xa phổ biến nhưng nhiều nhóm vẫn gặp mặt để tăng kết nối';
        $n3 = 'Sinh viên tận dụng giáo trình mở và tài liệu trực tuyến để ôn thi cuối kỳ';
        $n4 = 'Cập nhật hệ điều hành thường xuyên giúp thiết bị ổn định và an toàn hơn';
        $n5 = 'Nhiều thành phố khuyến khích doanh nghiệp tắt đèn văn phòng sau giờ làm';
        $n6 = 'Tin nhắn giúp trao đổi nhanh nhưng gặp trực tiếp tạo sự thấu hiểu hơn';
        $n7 = 'Các thành viên dự án trao đổi ý tưởng bên máy tính để giải quyết công việc';
        $n8 = 'Học trực tuyến liên tục nhiều giờ khiến sinh viên dễ mất tập trung';
        $n9 = 'Laptop ngày càng quan trọng trong công việc và học tập hằng ngày';
        $n10 = 'Nhiều doanh nghiệp thiết kế văn phòng kính để tận dụng ánh sáng tự nhiên';
        return [
            [
                'Văn phòng sáng cửa kính được nhiều công ty chọn',
                'doi-song', $a, $n10,
            ],
            [
                'Chiếc laptop và những dòng mã trong một ngày',
                'cong-nghe', $b, $n9,
            ],
            [
                'Ngồi học online cả buổi: tránh mất tập trung',
                'hoc-tap', $c, $n8,
            ],
            [
                'Nhóm dự án quây lại quanh những chiếc laptop',
                'cong-nghe', $d, $n7,
            ],
            [
                'Trò chuyện trực tiếp vẫn cần hơn tin nhắn',
                'doi-song', $e, $n6,
            ],
            [
                'Các thành phố siết giờ tắt đèn nơi làm việc',
                'doi-song', $a, $n5,
            ],
            [
                'Vì sao máy cá nhân vẫn cần cập nhật',
                'cong-nghe', $b, $n4,
            ],
            [
                'Sinh viên ôn bài cuối kỳ với giáo trình mở',
                'hoc-tap', $c, $n3,
            ],
            [
                'Làm việc nhóm từ xa vẫn cần một buổi gặp mặt',
                'cong-nghe', $d, $n2,
            ],
            [
                'Áp lực công việc: nói ra sớm để đỡ mang một mình',
                'doi-song', $e, $n1,
            ],
        ];
    }
}
