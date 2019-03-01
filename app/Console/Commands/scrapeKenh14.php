<?php
/**
 * Created by PhpStorm.
 * User: Daotu
 * Date: 26/02/2019
 * Time: 9:08 CH
 */

namespace App\Console\Commands;


use App\News;
use Illuminate\Console\Command;
use Goutte;
use Illuminate\Support\Facades\DB;

class scrapeKenh14 extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:kenh14';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $categories = [
//        'thoi-su.html',
        'the-gioi.chn',
        'xa-hoi.chn',
        'xa-hoi/phap-luat.chn',
        ' ',
        'sport.chn',
        'suc-khoe-gioi-tinh.chn',
        'doi-song.chn',
        '2-tek/cong-nghe-vui.chn',
        'doi-song/du-lich.chn',
        'xa-hoi/phap-luat.chn',

    ];

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $countnews = 1;
        $category = $this->categories;
        for ($i = 0; $i < count($category); $i++) {
            $crawler = Goutte::request('GET', 'http://kenh14.vn/' . $category[$i]);
            $linkPost = $crawler->filter('h3.knswli-title a')->each(function ($node) {

                return $node->attr('href');
            });

            foreach ($linkPost as $link) {
                self::scrapePost($link, $i + 1);
                echo "Posted Kenh14 " . $countnews++ . "\n";
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
            $title = $crawler->filter('h1.kbwc-title')->each(function ($node) {
                // print ($node->text()."\n");
                return $node->text();
            });
            if (isset($title[0])) {
                $title = $title[0];
            } else {
                $title = '';
            }

            // $slug = str_slug($title);

            $description = $crawler->filter('h2.knc-sapo')->each(function ($node) {
                return $node->text();
            });
            if (isset($description[0])) {
                $description = $description[0];
            } else {
                $description = '';
            }
//        $description = str_replace('', '', $description);

            $content = $crawler->filter('div.knc-content')->each(function ($node) {
                return $node->html();
            });

            if (isset($content[0])) {
                $content = $content[0];
            } else {
                $content = '';
            }

            //td.pic img
            $img = $crawler->filter('div.knc-content img')->each(function ($node) {
                return $node->attr('src');
            });
            if (isset($img[0])) {
                $img = $img[0];
            } else {
                $img = '';
            }

//        $author = $crawler->filter('li.the-article-author a')->each(function ($node) {
//            return $node->text();
//        })[0];


            $data = [
                'title' => $title,
                'img' => $img,
                'description' => $description,
                'content' => $content,
                'source' => 'http://kenh14.vn/' . $url,
                'author' => '',
                'category_id' => $idCategory
            ];


            News::installNews($data);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }

    }
}
