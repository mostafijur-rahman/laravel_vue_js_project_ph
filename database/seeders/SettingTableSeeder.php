<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Setting::setExtraColumns(['company_id' => 1]);

        // company setting
        Setting::set('company.name', 'আপনার কোম্পানীর নাম');
        Setting::set('company.slogan', 'পণ্য পরিবহনে আমরা এক ধাপ এগিয়ে');
        Setting::set('company.address', 'মুক্তবাংলা শপিং, মিরপুর - ১, ঢাকা - ১২১৬');
        Setting::set('company.phone', '01714078743');
        Setting::set('company.email', 'paribahanhishab@gmail.com');
        Setting::set('company.website', 'www.paribahanhishab.com');

        // sofware setting
        Setting::set('company.payment_feature', 'enable');
        Setting::set('company.loan_feature', 'enable');
        
        Setting::set('company.oil_rate', 100);
        
        // image
        // Setting::set('company.logo', $request->input('logo'));
        // Setting::set('company.favicon', $request->input('favicon'));

        // notifcation setting
        Setting::set('company.notify_days_for_document', 15);
        Setting::set('company.notify_days_for_mobil', 15);
        Setting::set('company.notify_days_for_tyre', 15);

        Setting::save();

    }
}