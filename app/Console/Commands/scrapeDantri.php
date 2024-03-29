<?php

namespace App\Console\Commands;

use App\Http\Controllers\NewsController;
use App\News;
use Illuminate\Console\Command;
use Goutte;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class scrapeDantri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:Dantri';
    public $imgDefault = 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/No_image_3x4.svg/1024px-No_image_3x4.svg.png';
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
        'nhip-song-tre',
        'suc-manh-so',
        'DU LICH',
        'phap-luat',


    ];
    public $pagearr = [];

    public function page($numPage)
    {
        for ($i = 1; $i <= $numPage; $i++) {
            array_push($this->pagearr, "trang-" . $i);
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

        $this->page($this->numPage);
        $category = $this->categories;
        for ($i = 0; $i < count($category); $i++) {
            foreach ($this->pagearr as $pageNumber) {
                $crawler = Goutte::request('GET', 'https://dantri.com.vn/' . $category[$i] . "/" . $pageNumber . '.htm');
                $linkPost = $crawler->filter('a.fon6')->each(function ($node) {

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
                    self::scrapePost('https://dantri.com.vn' . $link, $i + 1);
                    echo "\n"."Posted Dantri " . $countNews++ ;
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

            $title = $crawler->filter('h1.fon31.mgb15')->each(function ($node) {
                return $node->text();
            });
            if (isset($title[0])) {
                $title = $title[0];
            } else {

                $title = '';
            }

            $title = trim($title, ' ');



            $description = $crawler->filter('h2.fon33.mt1.sapo')->each(function ($node) {
                return $node->html();
            });
            if (isset($description[0])) {
                $description = $description[0];
            } else {
                $description = '';
            }
            //cắt chuỗi để tách 2 thẻ article ra

            //Lấy ra chuỗi muốn tách
            $getString = strstr($description, '<br');
            //thay thế chuỗi muốn tách bằng chuỗi rỗng
            $description = str_replace($getString, '', $description);
            //Lắt chữ dân trí ở đầu
            $description = str_replace('<span>Dân trí</span>', '', $description);


            $img = $crawler->filter('.detail-content figure img')->each(function ($node) {
                return $node->attr('src');
            });
            if (isset($img[0])) {
                $img = $img[0];
            } else {

                $img = '';
            }

            $content = $crawler->filter('#divNewsContent')->each(function ($node) {
                return $node->html();
            });

            if (isset($content[0])) {

                $content = $content[0];
            } else {
                $content = '';
            }
            //Lấy ra chuỗi muốn tách
            $getString = strstr($content, '<div');
            //thay thế chuỗi muốn tách bằng chuỗi rỗng
            $content = str_replace($getString, '', $content);


//        $author = $crawler->filter('li.the-article-author a')->each(function ($node) {
//            return $node->text();
//        })[0];


            $data = [
                'title' => $title,
                'img' => $img,
                'description' => $description,
                'content' => $content,
                'source' => $url,
                'user_id' => 2,
                'author' => '',
                'url' => $title,
                'category_id' => $idCategory
            ];

            //   echo $description;
            News::installNews($data);

        } catch (\Exception $exception) {
            News::deletedNewsDuplicate($this->output);
            return response()->json($exception->getMessage(), 500);
        }

    }
}
