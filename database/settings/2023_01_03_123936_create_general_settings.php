<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.tax_price', 14);
         $this->migrator->add('general.about','not written yet');
          $this->migrator->add('general.returnPolicy','not written yet');
          $this->migrator->add('general.phone','not written yet');
          $this->migrator->add('general.email','not written yet');
    }
}
