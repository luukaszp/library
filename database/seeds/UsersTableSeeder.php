<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Worker;
use App\Reader;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Admin */
        $user = new User();
        $user->name = 'admin';
        $user->surname = 'admin';
        $user->email = 'admin@admin.admin';
        $user->activation_token = Str::random(40);
        $user->email_verified_at = Carbon::now()->toDateTimeString();

        $worker = new Worker();
        $worker->id_number = '123123123123';
        $worker->is_admin = 1;
        $worker->user_id = 1;
        $worker->password = bcrypt('zaq1@WSX');

        $user->save();
        $worker->save();

        /** Worker */
        $user = new User();
        $user->name = 'worker';
        $user->surname = 'worker';
        $user->email = 'worker@worker.worker';
        $user->activation_token = Str::random(40);
        $user->email_verified_at = Carbon::now()->toDateTimeString();

        $worker = new Worker();
        $worker->id_number = '321321321321';
        $worker->is_admin = 0;
        $worker->user_id = 2;
        $worker->password = bcrypt('zaq1@WSX');

        $user->save();
        $worker->save();

        /** Reader */
        $user = new User();
        $user->name = 'reader';
        $user->surname = 'reader';
        $user->email = 'reader@reader.reader';
        $user->activation_token = Str::random(40);
        $user->email_verified_at = Carbon::now()->toDateTimeString();

        $reader = new Reader();
        $reader->card_number = '1212121212';
        $reader->user_id = 3;
        $reader->password = bcrypt('zaq1@WSX');

        $user->save();
        $reader->save();
    }
}
