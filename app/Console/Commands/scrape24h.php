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
use Symfony\Component\Console\Helper\ProgressBar;

class scrape24H extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:24h';
    public  $imgDefault = 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/No_image_3x4.svg/1024px-No_image_3x4.svg.png';
    protected $output;
    protected $numPage = 5;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $categories = [

        'tin-tuc-quoc-te-c415.html',
        'kinh-doanh-c161.html',
        'the-thao-c101.html',
        'suc-khoe-doi-song-c62.html',
        'doi-song-showbiz-c729.html',
        'cong-nghe-thong-tin-c55.html',
        'du-lich-24h-c76.html',
        'an-ninh-hinh-su-c51.html',


    ];
    public $pagearr = [    ];

    public function page($numPage)
    {
        for ($i = 1; $i < $numPage; $i++) {
            return array_push($this->pagearr, "?vpage=" . $i);
        }

    }


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
        $countNews = 1;


        $category = $this->categories;
        $this->page($this->numPage);
        for ($i = 0; $i < count($category); $i++) {
            foreach ($this->pagearr as $pageNumber) {
                $crawler = Goutte::request('GET', 'https://www.24h.com.vn/' . $category[$i] . $pageNumber);
                $linkPost = $crawler->filter('article.bxDoiSbIt span.nwsTit a')->each(function ($node) {

                    return $node->attr('href');
                });

                // waiting 10s
                echo "\n"."Waiting.... to next ".$category[$i].$pageNumber . "\n";
                $progressBar = new ProgressBar($this->output, 100);
                $progressBar->start();
                $u = 0;
                while ($u++ < 10) {
                    sleep(1);
                    $progressBar->advance(10);
                }
                $progressBar->finish();


                foreach ($linkPost as $link) {
                    self::scrapePost($link, $i + 1);
                    echo "\n"."Posted 24h " . $countNews++ ;
                }
            }
            //delete duplicate
            News::deletedNewsDuplicate($this->output);
            News::deletedNewsNoImg();
        }

        //count total records
        $totalRecords = $countNews -1;
        echo "\n" . "Total obtained: " .$totalRecords . " records" . "\n";


    }


    /**
     * @param $url
     * @param $idCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public  function scrapePost($url, $idCategory)
    {
        try {

            $crawler = Goutte::request('GET', $url);

            //.atclTit.mrT10 a

            $title = $crawler->filter('h1.clrTit')->each(function ($node) {
                return $node->text();
            });
            if (isset($title[0])) {
                $title = $title[0];
            } else {
                $title = $crawler->filter('.atclTit.mrT10 a')->each(function ($node) {
                    return $node->text();
                });
                if (isset($title[0])) {
                    $title = $title[0];
                } else {
                    $title = '';
                }
            }

            // $slug = str_slug($title);
            //h2.ctTp
            $description = $crawler->filter('h2.ctTp')->each(function ($node) {
                return $node->text();
            });
            if (isset($description[0])) {
                $description = $description[0];
            } else {
                $description = '';
            }



            $content = $crawler->filter('article.nwsHt.nwsUpgrade p')->each(function ($node) {
                return $node->html();
            });

            if (isset($content[0])) {

                $content = implode('<p>', array_slice($content, 0, -3));
            } else {
                $content = '';
            }

            //article.bxDoiSbIt img
            //img.news-image.initial.loading
            $img = $crawler->filter('.nwsHt.nwsUpgrade img')->each(function ($node) {
                return $node->attr('src');
            });
            if (isset($img[0])) {

                if($img[0] == 'https://cdn.24h.com.vn/images/2014/icon-before-page.png'){
                   if($img[1] == 'https://cdn.24h.com.vn/images/2014/icon-after-page.png')
                    $img = $img[2];

                }else{
                    $img = $crawler->filter('.nwsHt.nwsUpgrade img')->each(function ($node) {
                        return $node->attr('src');
                    });
                    if (isset($img[0])) {
                        $img = $img[0];
                    } else {
                        $img = '';
                    }
                }
                if($img == 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/dc/Flag_placeholder.svg/23px-Flag_placeholder.svg.png'){
                    $img = '';
                }
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
                'user_id' => 1,
                'author' => '',
                'category_id' => $idCategory
            ];


            News::installNews($data);


        } catch (\Exception $exception) {
            News::deletedNewsDuplicate($this->output);
            News::deletedNewsNoImg();
            return response()->json($exception->getMessage(), 500);
        }

    }
}
