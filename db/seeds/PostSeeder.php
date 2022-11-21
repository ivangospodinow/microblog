<?php

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $count = 50;
        $data = [];
        $date = new DateTime();
        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'createdBy' => 1,
                'title' => $this->lorem(1, 4),
                'content' => $this->getContent(),
                'image' => '/img/post-' . rand(1, 6) . '.jpg',
                'createdAt' => $date->format('Y-m-d H:i:s'),
                'updatedAt' => $date->format('Y-m-d H:i:s'),
                'featured' => 0,
            ];
            $date->sub(new DateInterval('P' . rand(1, 15) . 'D'));
        }

        $data = array_reverse($data);
        $data[0]['featured'] = 1;
        $data[1]['featured'] = 1;
        $data[2]['featured'] = 1;

        $posts = $this->table('posts');
        $posts->insert($data)
            ->saveData();
    }

    public function getContent()
    {
        $content1 = $this->lorem();
        $content2 = $this->lorem();

        $heading1 = substr($this->lorem(), 0, rand(15, 45));
        $heading2 = substr($this->lorem(), 0, rand(15, 45));
        $heading3 = substr($this->lorem(), 0, rand(15, 45));

        return <<<HTML
$content1

Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.
Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum.

$content2

Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum.
Aenean lacinia bibendum nulla sed consectetur.

## $heading1

Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.
Morbi leo risus, porta ac consectetur ac, vestibulum at eros.

### $heading2

Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.

### $heading3

Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
Aenean lacinia bibendum nulla sed consectetur. Etiam porta sem malesuada magna mollis euismod.
Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo
sit amet risus.

- Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
- Donec id elit non mi porta gravida at eget metus.
- Nulla vitae elit libero, a pharetra augue.

Donec ullamcorper nulla non metus auctor fringilla. Nulla vitae elit libero, a pharetra augue.

1. Vestibulum id ligula porta felis euismod semper.
1. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
1. Maecenas sed diam eget risus varius blandit sit amet non magna.

Cras mattis consectetur purus sit amet fermentum. Sed posuere consectetur est at lobortis.

HTML;
    }

    public function lorem($count = 1, $max = 20, $standard = false)
    {
        $output = '';

        if ($standard) {
            $output = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
                'sed do eiusmod tempor incididunt ut labore et dolore magna ' .
                'aliqua.';
        }

        $pool = explode(
            ' ',
            'a ab ad accusamus adipisci alias aliquam amet animi aperiam ' .
            'architecto asperiores aspernatur assumenda at atque aut beatae ' .
            'blanditiis cillum commodi consequatur corporis corrupti culpa ' .
            'cum cupiditate debitis delectus deleniti deserunt dicta ' .
            'dignissimos distinctio dolor ducimus duis ea eaque earum eius ' .
            'eligendi enim eos error esse est eum eveniet ex excepteur ' .
            'exercitationem expedita explicabo facere facilis fugiat harum ' .
            'hic id illum impedit in incidunt ipsa iste itaque iure iusto ' .
            'laborum laudantium libero magnam maiores maxime minim minus ' .
            'modi molestiae mollitia nam natus necessitatibus nemo neque ' .
            'nesciunt nihil nisi nobis non nostrum nulla numquam occaecati ' .
            'odio officia omnis optio pariatur perferendis perspiciatis ' .
            'placeat porro possimus praesentium proident quae quia quibus ' .
            'quo ratione recusandae reiciendis rem repellat reprehenderit ' .
            'repudiandae rerum saepe sapiente sequi similique sint soluta ' .
            'suscipit tempora tenetur totam ut ullam unde vel veniam vero ' .
            'vitae voluptas'
        );

        $max = ($max <= 3) ? 4 : $max;
        $count = ($count < 1) ? 1 : (($count > 2147483646) ? 2147483646 : $count);

        for ($i = 0, $add = ($count - (int) $standard); $i < $add; $i++) {
            shuffle($pool);
            $words = array_slice($pool, 0, mt_rand(3, $max));
            $output .= ((!$standard && $i === 0) ? '' : ' ') . ucfirst(implode(' ', $words)) . '.';
        }

        return $output;
    }

}
