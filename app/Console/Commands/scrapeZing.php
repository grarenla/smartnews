<?php

namespace App\Console\Commands;


use App\News;
use Illuminate\Console\Command;
use Goutte;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class scrapeZing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:zing';
    public $imgDefault = 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/No_image_3x4.svg/1024px-No_image_3x4.svg.png';
    protected $output;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $categories = [
//        'thoi-su.html',
        'the-gioi.html',
        'kinh-doanh-tai-chinh.html',
        'the-thao.html',
        'suc-khoe.html',
        'nhip-song.html',
        'cong-nghe.html',
        'du-lich.html',
        'phap-luat.html',


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
        $countNews = 1;

        $category = $this->categories;
        for ($i = 0; $i < count($category); $i++) {
            $crawler = Goutte::request('GET', 'https://news.zing.vn/' . $category[$i]);
            $linkPost = $crawler->filter('div.article-list.listing-layout.responsive p.article-title a')->each(function ($node) {

                return $node->attr('href');
            });

            // waiting 10s
            echo "\n" . "Waiting.... to next " . $category[$i] . "\n";
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
                echo "\n"."Posted Zing " . $countNews++ ;
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
    public function scrapePost($url, $idCategory)
    {
        try {
            $crawler = Goutte::request('GET', $url);
            $title = $crawler->filter('h1.the-article-title')->each(function ($node) {
                // print ($node->text()."\n");
                return $node->text();
            });
            if (isset($title[0])) {
                $title = $title[0];
            } else {
                $title = '';
            }


            $description = $crawler->filter('p.the-article-summary')->each(function ($node) {
                return $node->text();
            });
            if (isset($description[0])) {
                $description = $description[0];
            }
//        $description = str_replace('', '', $description);

            $content = $crawler->filter('div.the-article-body')->each(function ($node) {
                return $node->html();
            });

            if (isset($content[0])) {
                $content = $content[0];
            } else {
                $content = '';
            }

            //cắt chuỗi để tách 2 thẻ article ra

            //Lấy ra chuỗi muốn tách
            $getString = strstr($content, '<table align=');
            //thay thế chuỗi muốn tách bằng chuỗi rỗng
            $content = str_replace($getString, '', $content);


            $img = $crawler->filter('section.main img')->each(function ($node) {
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
                'source' => 'https://news.zing.vn' . $url,
                'user_id' => 2,
                'author' => '',
                'url' => $title,
                'category_id' => $idCategory
            ];

            News::installNews($data);


        } catch (\Exception $exception) {
            News::deletedNewsDuplicate($this->output);
            return response()->json($exception->getMessage(), 500);
        }

    }

}
