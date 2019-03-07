<?php
/**
 * Created by PhpStorm.
 * User: Daotu
 * Date: 26/02/2019
 * Time: 4:25 CH
 */

namespace App\Console\Commands;


use App\Http\Controllers\NewsController;
use App\News;
use Illuminate\Console\Command;
use Goutte;
use Symfony\Component\Console\Helper\ProgressBar;

class scrapeVnx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:vnx';
    public $imgDefault = 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/No_image_3x4.svg/1024px-No_image_3x4.svg.png';
    public $imgUri = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    protected $output;
    protected $numPage = 5;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $categories = [

        'the-gioi',
        'kinh-doanh',
        'the-thao',
        'suc-khoe',
        'doi-song',
        'khoa-hoc',
        'du-lich',
        'phap-luat',
        'tam-su',
        'thoi-su',

    ];
    public $pageArr = array();
//    [
//        '-p1',
//         '-p2',
//         '-p3',
//         '-p4',
//        '/p1',
//        '/p2',
//    ];


    public function page($numPage, $category)
    {
        unset($this->pageArr);
        $this->pageArr = array();
        if ($category == 1 || $category == 6 || $category == 8 || $category == 9 || $category == 10) {
            for ($i = 1; $i <= $numPage; $i++) {
                return array_push($this->pageArr, "-p" . $i);
            }
        } else {
            for ($i = 1; $i <= $numPage; $i++) {
                return array_push($this->pageArr, "/p" . $i);
            }
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
     * @return void
     */

    public function handle()
    {
        $countNews = 1;
        $category = $this->categories;

        for ($i = 0; $i < count($category); $i++) {

            $this->page($this->numPage, $i + 1);

            foreach ($this->pageArr as $pageNumber) {
                $crawler = Goutte::request('GET', 'https://vnexpress.net/' . $category[$i] . $pageNumber);
                $linkPost = $crawler->filter('section.sidebar_1 h4.title_news a.icon_commend')->each(function ($node) {
                    if ($node->attr('href') == null) {
                        return $node->attr('data-href');
                    }
                    return $node->attr('href');
                });

                // waiting 10s
                echo "\n" . "Waiting.... to next " . $category[$i] . $pageNumber . "\n";
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
                    echo "\n" . "Posted Vnx " . $countNews++;
                }
            }
            //delete duplicate
            News::deletedNewsDuplicate($this->output);
            News::deletedNewsNoImg();
        }

        //count total records
        $totalRecords = $countNews - 1;
        echo "\n" . "Total obtained: " . $totalRecords . " records" . "\n";
    }


    /**
     * @param $url
     * @param $idCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function scrapePost($url, $idCategory)
    {

        try {


            $crawler = Goutte::request('GET', $url);

            $title = $crawler->filter('div.title_news h1')->each(function ($node) {
                return $node->text();
            });
            if (isset($title[0])) {
                $title = $title[0];
            } else {
                $title = $crawler->filter('h1.title_news_detail')->each(function ($node) {
                    return $node->text();
                });
                if (isset($title[0])) {
                    $title = $title[0];
                } else {
                    $title = '';
                }
            }

            // $slug = str_slug($title);
            //.description

            $description = $crawler->filter('h2.short_intro.txt_666')->each(function ($node) {
                return $node->text();
            });
            if (isset($description[0])) {
                $description = $description[0];
            } else {
                $description = $crawler->filter('section.sidebar_1 p.description')->each(function ($node) {
                    return $node->text();
                });
                if (isset($description[0])) {
                    $description = $description[0];
                } else {
                    $description = $crawler->filter('.description')->each(function ($node) {
                        return $node->text();
                    });
                    if (isset($description[0])) {
                        $description = $description[0];
                    } else {
                        $description = '';
                    }

                }
            }

            $img = $crawler->filter('.fck_detail.width_common.block_ads_connect img')->each(function ($node) {
                return $node->attr('src');
            });
            if (isset($img[0])) {
                $img = $img[0];
                if ($img == $this->imgUri) {
                    $img = '';
                }
            } else {
                $img = $crawler->filter('.block_thumb_slide_show img')->each(function ($node) {
                    return $node->attr('src');
                });
                if (isset($img[0])) {
                    $img = $img[0];
                    if ($img == $this->imgUri) {
                        $img = '';
                    }
                } else {
                    $img = ' ';
                }
            }
            // .content_detail.fck_detail.width_common
            //.fck_detail.width_common.block_ads_connect
            $content = $crawler->filter('.content_detail.fck_detail.width_common')->each(function ($node) {
                return $node->html();
            });

            if (isset($content[0])) {

                $content = $content[0];
            } else {
                $content = $crawler->filter('.fck_detail.width_common.block_ads_connect')->each(function ($node) {
                    return $node->html();
                });

                if (isset($content[0])) {

                    $content = $content[0];
                } else {
                    $content = '';
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
                'author' => '',
                'category_id' => $idCategory
            ];


            News::installNews($data);

        } catch (\Exception $exception) {
            News::deletedNewsDuplicate($this->output);
            return response()->json($exception->getMessage(), 500);
        }

    }
}
