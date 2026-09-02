<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminSeeder extends Seeder
{
    /**
     * 管理者アカウントのログイン情報（平文）。動作確認できるようこのファイルに記載している。
     */
    private const ADMIN_EMAIL    = 'admin@gmail.com';
    private const ADMIN_PASSWORD = 'asdfasdf';

    /**
     * 管理者アバター画像。database/seeders/data/avatars/ 配下のファイル名。
     */
    private const ADMIN_AVATAR = 'administrator.jpg';

    protected User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->user = User::firstOrNew(['email' => self::ADMIN_EMAIL]);

        $this->user->name = 'Administrator';
        $this->user->email = self::ADMIN_EMAIL;
        $this->user->password = Hash::make(self::ADMIN_PASSWORD);
        $this->user->role_id = User::ADMIN_ROLE_ID;
        $this->user->avatar = $this->storeAvatar(self::ADMIN_AVATAR) ?? $this->user->avatar;
        $this->user->save();

        $this->command?->info(sprintf('  Administrator  %s / %s', self::ADMIN_EMAIL, self::ADMIN_PASSWORD));
    }

    /**
     * database/seeders/data/avatars/ の画像を public ディスク（storage/app/public/avatars/）へ
     * コピーし、users.avatar に保存する相対パスを返す。画像が無ければ null。
     */
    private function storeAvatar(string $filename): ?string
    {
        $source = database_path('seeders/data/avatars/' . $filename);
        if (! is_file($source)) {
            $this->command?->warn("  avatar not found: {$source}");

            return null;
        }

        $path = 'avatars/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($source));

        return $path;
    }
}
