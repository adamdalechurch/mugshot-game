<?php

namespace MugshotGame\App\Views;

use SimplePHP\Core\Infrastructure\View;
use MugshotGame\App\Data\Arrest;
use MugshotGame\App\Data\Detail;

class MainView extends View
{
    public function __construct( $repo )
    {
        parent::__construct( $repo );
    }

    public function render()
    {
        $arrest = new Arrest();
        $detail = new Detail();

        $arrests = $arrest->get_all();
        $details = $detail->get_all();

        $arrests = $this->format_arrests( $arrests, $details );

        $this->set( 'arrests', $arrests );

        return parent::render();
    }

    private function format_arrests( $arrests, $details )
    {
        $formatted_arrests = [];

        foreach( $arrests as $arrest )
        {
            $formatted_arrest = [
                'id' => $arrest['id'],
                'name' => $arrest['name'],
                'mugshot' => $arrest['mugshot'],
                'book_date' => $arrest['book_date'],
                'book_date_formatted' => $arrest['book_date_formatted'],
                'more_info_url' => $arrest['more_info_url'],
                'details' => []
            ];

            foreach( $details as $detail )
            {
                if( $detail['arrest_id'] == $arrest['id'] )
                {
                    $formatted_arrest['details'][] = $detail['description'];
                }
            }

            $formatted_arrests[] = $formatted_arrest;
        }

        return $formatted_arrests;
    }
}