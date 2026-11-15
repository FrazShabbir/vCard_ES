<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function issue(User $user, string $purpose = 'email_verify'): VerificationCode
    {
        VerificationCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $row = VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'purpose' => $purpose,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        $this->deliver($user->email, $code, $purpose);

        return $row;
    }

    public function verify(User $user, string $code, string $purpose = 'email_verify', bool $consume = true): bool
    {
        $row = VerificationCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->orderByDesc('id')
            ->first();

        if (!$row || !$row->isValid() || !hash_equals($row->code, $code)) {
            return false;
        }

        if ($consume) {
            $row->consumed_at = now();
            $row->save();
        }

        return true;
    }

    protected function deliver(string $email, string $code, string $purpose): void
    {
        // Until a mailer is configured, print to the log in a boxed block.
        $box = implode("\n", [
            '╔══════════════════════════════════════╗',
            '║  OTP for ' . str_pad($purpose, 27) . '║',
            '║  ' . str_pad($email, 36) . '║',
            '║  CODE: ' . $code . '                         ║',
            '╚══════════════════════════════════════╝',
        ]);
        Log::info("\n" . $box);
    }
}
