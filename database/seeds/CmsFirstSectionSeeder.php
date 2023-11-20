<?php

use Illuminate\Database\Seeder;

class CmsFirstSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = \App\Models\CmsPages::create([
            'page_name' => 'Home',
            'section_name' => 'first',
            'heading' => 'Buy Or Sell - Deal Zone',
        ]);

        \App\Models\CmsComponent::create([
            'cms_page_id' => $page->id,
            'title' => 'Buy Or Sell With Ease',
            'description' => 'We make buying and selling gear online easy so you can focus on one thing-making music.',
        ]);
        \App\Models\CmsComponent::create([
            'cms_page_id' => $page->id,
            'title' => 'Endless Gear to Discover',
            'description' => 'From vintage synths to rare guitars, find exactly what you need or something unexpected.',
        ]);
        \App\Models\CmsComponent::create([
            'cms_page_id' => $page->id,
            'title' => 'A Team That Has Your Back',
            'description' => 'Buy and sel with confidence. One team of gear experts is here to help if you need a hand',
        ]);
        \App\Models\CmsComponent::create([
            'cms_page_id' => $page->id,
            'title' => 'A Community of Music Makers',
            'description' => 'From rock stars to local music shops, buyers & sellers from al over the world call Reverb home.',
        ]);
    }
}
