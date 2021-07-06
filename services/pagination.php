<?php
require_once('./models/posts.php');
function paginate($con, int $cur_page, int $page_items): array
{
    $items_count = get_posts_count($con);
    $pages_count = intval(ceil($items_count / $page_items));
    $offset = ($cur_page - 1) * $page_items;

    return [$pages_count, $offset];
}
