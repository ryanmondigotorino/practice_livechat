<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FinderAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = array_map('str_getcsv', file(base_path('/database/seeds/csv/FinderAccount.csv')));

        if (is_array($csv) || is_object($csv)) {
            Schema::disableForeignKeyConstraints();
            DB::table('finders')->delete();
            $indx = 0;
            foreach ($csv as $key => $val) {
                $indx++;
                if ($indx == 1) continue;

                DB::table('finders')->insert([
                    'id' => $val[0],
                    'firstname' => $val[1],
                    'lastname' => $val[2],
                    'email' => $val[3],
                    'gender' => $val[4],
                    'username' => $val[5],
                    'password' => $val[6],
                    'account_line' => $val[7],
                    'account_status' => $val[8],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
