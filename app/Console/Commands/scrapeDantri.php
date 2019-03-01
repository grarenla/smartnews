<?php

namespace App\Console\Commands;

use App\Http\Controllers\NewsController;
use App\News;
use Illuminate\Console\Command;
use Goutte;
use Illuminate\Support\Facades\DB;

class scrapeDantri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:dantri';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $categories = [

        'su-kien',        
        'xa-hoi',
        'the-gioi',
        'the-thao',
        'giao-duc-khuyen-hoc',
        'tam-long-nhan-ai',
        'kinh-doanh',
        'bat-dong-san',
        'van-hoa',
        'giai-tri',
        'phap-luat',
        'nhip-song-tre',
        'suc-khoe',
        'suc-manh-so',
        'o-to-xe-may',
        'tinh-yeu-gioi-tinh',

    ];
    

//    public function page($numPage)
//    {
//        for ($i = 1; $i < $numPage; $i++) {
//            return array_push($this->pagearr, "-p" . $i);
//        }
//
//    }


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle()
    {
        $countnews = 1;


        $category = $this->categories;
        for ($i = 0; $i < count($category); $i++) {
            
            $crawler = Goutte::request('GET', 'https://dantri.com.vn/' . $category[$i].'.htm');
            $linkPost = $crawler->filter('a.fon6')->each(function ($node) {

                return $node->attr('href');
            });

            foreach ($linkPost as $link) {
                self::scrapePost('https://dantri.com.vn'.$link, $i + 1);
                echo "Posted Dantri " . $countnews++ . "\n";
                }
            
        }

        $duplicateRecords = DB::table('news')            
            ->select('title')
            ->selectRaw('count(`title`) as `occurences`')            
            ->groupBy('title')
            ->having('occurences', '>', 1)
            ->get();
        
        foreach($duplicateRecords as $record) {
            $dontDeleteThisRow  = News::where('title', $record->title)->first();
            DB::table('news')->where('title', $record->title)->where('id', '!=', $dontDeleteThisRow->id)->delete();
        }
        // print_r($duplicateRecords);

        echo "\n" . "Total: " . $countnews . " records" . "\n";
    }


    /**
     * @param $url
     * @param $idCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public static function scrapePost($url, $idCategory)
    {
        try {

            $crawler = Goutte::request('GET', $url);

            $title = $crawler->filter('h1.fon31.mgb15')->each(function ($node) {
                return $node->text();
            })[0];
            // if (isset($title[0])) {
            //     $title = $title[0];
            // } else {
            //     $title = $crawler->filter('h1.title_news_detail')->each(function ($node) {
            //         return $node->text();
            //     });
            //     if (isset($title[0])) {
            //         $title = $title[0];
            //     } else {
            //         $title = '';
            //     }
            // }

            

            $description = $crawler->filter('h2.fon33.mt1.sapo')->each(function ($node) {
                return $node->text();
            })[0];
            // if (isset($description[0])) {
            //     $description = $description[0];
            // } else {
            //     $description = $crawler->filter('section.sidebar_1 p.description')->each(function ($node) {
            //         return $node->text();
            //     });
            //     if (isset($description[0])) {
            //         $description = $description[0];
            //     } else {
            //         $description = '';
            //     }
            // }
//        $description = str_replace('', '', $description);

            $img = $crawler->filter('.detail-content figure img')->each(function ($node) {
                return $node->attr('src');
            });
            if (isset($img[0])) {
                $img = $img[0];
            } else {
                $img = '';
            }

            $content = $crawler->filter('.detail-content')->each(function ($node) {
                return $node->html();
            });

            if (isset($content[0])) {

                $content = $content[0];
            } else {
                $content = '';
            }


//        $author = $crawler->filter('li.the-article-author a')->each(function ($node) {
//            return $node->text();
//        })[0];


            $data = [
                'title' => $title,
                'img' => $img,
                'description' => $description,
                'content' => $content,
                'source' => $url,
                'author' => '',
                'category_id' => $idCategory
            ];


            News::installNews($data);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }

    }
}
