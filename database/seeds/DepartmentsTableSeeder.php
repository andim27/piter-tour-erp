<?php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Flynsarmy\CsvSeeder\CsvSeeder;

class DepartmentsTableSeeder extends CsvSeeder {

    public function __construct()
    {
        $this->table = 'departments';
        //$this->filename = base_path().'/database/seeds/csvs/users_1.csv';
        $this->filename = 'D:/work/PiterTur/code/olta-tour-new/database/seeds/csvs/departments.csv';

    }

    public function run()
    {
        $this->command->info('Run import departments...'.$this->filename);
        $this->command->info('Delimeters is:'.$this->csv_delimiter);
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        //DB::table($this->table)->truncate();

        parent::run();
    }
}
