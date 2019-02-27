<?php
/**
 * Created by PhpStorm.
 * User: Daotu
 * Date: 26/02/2019
 * Time: 4:25 CH
 */

namespace App\Console\Commands;


use App\News;
use Illuminate\Console\Command;
use Goutte;

class scrape24H extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:24h';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $categories = [
//        'thoi-su.html',
        'tin-tuc-quoc-te-c415.html',
        'kinh-doanh-c161.html',
        'the-thao-c101.html',
        'suc-khoe-doi-song-c62.html',
        'doi-song-showbiz-c729.html',
        'khoa-hoc',
        'du-lich-24h-c76.html',
        'an-ninh-hinh-su-c51.html',


    ];
    public $pagearr = [
        '?vpage=1',
        '?vpage=2',
    ];

//    public function page($numPage)
//    {
//        for ($i = 1; $i < $numPage; $i++) {
//            return array_push($this->pagearr, "?vpage=" . $i);
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $countnews = 1;


        $category = $this->categories;
        for ($i = 0; $i < count($category); $i++) {
            foreach ($this->pagearr as $pageNumber) {
                $crawler = Goutte::request('GET', 'https://www.24h.com.vn/' . $category[$i] . $pageNumber);
                $linkPost = $crawler->filter('article.bxDoiSbIt span.nwsTit a')->each(function ($node) {

                    return $node->attr('href');
                });

                foreach ($linkPost as $link) {
                    self::scrapePost($link, $i + 1);
                    echo "Posted 24h " . $countnews++ . "\n";
                }
            }
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
            $title = $crawler->filter('h1.clrTit')->each(function ($node) {

                return $node->text();
            });
            if (isset($title[0])) {
                $title = $title[0];
            } else {
                $title = '';
            }

            // $slug = str_slug($title);

            $description = $crawler->filter('h2.ctTp')->each(function ($node) {
                return $node->text();
            });
            if (isset($description[0])) {
                $description = $description[0];
            } else {
                $description = '';
            }

            $img = $crawler->filter('article.nwsHt.nwsUpgrade img')->each(function ($node) {
                return $node->attr('src');
            });
            if (isset($img[0])) {
                $img = $img[0];
            } else {
                $img = '';
            }

            $content = $crawler->filter('article.nwsHt.nwsUpgrade p')->each(function ($node) {
                return $node->html();
            });

            if (isset($content[0])) {

                $content = implode('<p>', array_slice($content, 0, -3));
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
