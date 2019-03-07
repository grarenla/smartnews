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
use Symfony\Component\Console\Helper\ProgressBar;

class scrapeKenh14 extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:kenh14';
    public  $imgDefault = 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/No_image_3x4.svg/1024px-No_image_3x4.svg.png';
    protected $output;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $categories = [
//        'thoi-su.html',
        'the-gioi.chn',
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

        $countNews = 1;

        $category = $this->categories;

        for ($i = 0; $i < count($category); $i++) {
            $crawler = Goutte::request('GET', 'http://kenh14.vn/' . $category[$i]);
            $linkPost = $crawler->filter('h3.knswli-title a')->each(function ($node) {

                return $node->attr('href');
            });

            // waiting 10s
            echo "\n"."Waiting.... to next ".$category[$i]. "\n";
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
                echo "\n"."Posted Kenh14 " . $countNews++ ;
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
                $img = $this ->imgDefault;
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
            News::deletedNewsDuplicate($this->output);
            return response()->json($exception->getMessage(), 500);
        }

    }
}
